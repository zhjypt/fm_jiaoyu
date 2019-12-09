<?php
/**
 * By 高贵血迹
 */

global $_GPC, $_W;

$operation = in_array ( $_GPC ['op'], array ('default', 'login', 'classinfo', 'check', 'gps', 'banner', 'video','checknew', 'checkold','all','banpai','allcard','getleave','onlineflow','offlineflow','cardinfo','checkforks','getallroom') ) ? $_GPC ['op'] : 'default';
$weid = $_GPC['i'];
$schoolid = $_GPC['schoolid'];
$macid = empty($_GPC['macid']) ? $_GPC['terminalId']:$_GPC['macid'];
$ckmac = pdo_fetch("SELECT * FROM " . tablename($this->table_checkmac) . " WHERE macid = '{$macid}' And schoolid = '{$schoolid}' ");
$school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}' ");
if ($operation == 'default') {
    echo("对不起，你的请求不存在！");
    exit;
}
if(empty($school)){
    echo("找不到本校");
    exit;
}
if(empty($ckmac)){
    echo("没找到设备");
    exit;
}
if($school['is_recordmac'] == 2){
    echo("本校无权使用设备");
    exit;
}
if ($ckmac['is_on'] ==2){
    echo("本设备已关闭");
    exit;
}
if (!empty($_W['setting']['remote']['type'])) {
    $urls = $_W['attachurl'];
} else {
    $urls = $_W['siteroot'].'attachment/';
}
if ($operation == 'all') {
    if(!empty($ckmac)){
        $allclasstimeset = GetDatesetWithBj($school['id'],$weid);
        $classes = pdo_fetchall("SELECT sid as classId,type, sname as className  FROM " . tablename($this->table_classify) . " WHERE weid = '{$weid}' And (type = 'theclass' OR type = 'jsfz') And schoolid = {$school['id']} ORDER BY sid DESC");
        $result = array(
            'code'=>1000,
            'msg'=>'success',
            'ServerTime'=>date('Y-m-d H:i:s',time()),
            'data'=>array()
        );
        $students = array('groupname'=>'r0','sjd'=>'00002359','users'=>array());
        $teachers = array('groupname'=>'teacher','sjd'=>'00002359','users'=>array());
        foreach ($classes as $cls) {
            $this_todaytype = $allclasstimeset[$row['classId']]['timeset']['todaytype'];
            //原来的
            //$sql = "SELECT id as childId, bj_id as classId, s_name as name FROM " . tablename($this->table_students) . " WHERE weid = '{$weid}' And schoolid = {$school['id']} And bj_id = '{$cls['classId']}' ORDER BY id DESC";
            //修改的，迅贞改好后再启用，暂时只是我们服务器用
            $sql = "SELECT id as childId, bj_id as classId, s_name as name,s_type FROM " . tablename($this->table_students) . " WHERE weid = '{$weid}' And schoolid = {$school['id']} And bj_id = '{$cls['classId']}' ORDER BY id DESC";
            $colid = 'sid';
            //如果设备是宿舍考勤，则获取白名单学生列表
            if($ckmac['apid'] != 0){
                $allroomtimeset = GetDatesetWithRoom($school['id'],$weid,$ckmac['apid']);
                $roomlist = pdo_fetchall("SELECT id FROM " . tablename($this->table_aproom) . " WHERE apid = '{$ckmac['apid']}' and schoolid = '{$school['id']}' and weid = '{$weid}' ORDER BY id DESC");
                $room_str = '';
                foreach($roomlist as $key_r=>$value_r){
                    $room_str .=$value_r['id'].',';
                }
                $room_str = trim($room_str,',');
                $condition = " and FIND_IN_SET(roomid,'{$room_str}') ";
                $sql = "SELECT id as childId, bj_id as classId, s_name as name,s_type,roomid FROM " . tablename($this->table_students) . " WHERE weid = '{$weid}' And schoolid = {$school['id']} And bj_id = '{$cls['classId']}' $condition ORDER BY id DESC";
            }
            if($cls['type'] == 'jsfz'){//教师
                $sql = "SELECT id as childId, fz_id as classId, tname as name FROM " . tablename($this->table_teachers) . " WHERE weid = '{$weid}' And schoolid = {$school['id']} And fz_id = '{$cls['classId']}' ORDER BY id DESC";
                $colid = 'tid';
            }
            $class = pdo_fetchall($sql);
            foreach($class as $key =>$row) {
                if($cls['type'] != 'jsfz'){
                    if($ckmac['apid'] == 0 ){
                        if($row['s_type'] != 2){
                            $class[$key]['timeset'] = array(array('start'=>'00:00','end'=>'23:59'));
                        }elseif($row['s_type'] == 2){
                            $class[$key]['timeset'] = $allclasstimeset[$row['classId']]['timeset']['todaytimeset'];
                        }
                    }elseif($ckmac['apid'] != 0) {
                        if($this_todaytype == 1){
                            $class[$key]['timeset'] = array(array('start'=>'00:00','end'=>'23:59'));
                        }else{
                            $class[$key]['timeset'] = $allroomtimeset[$row['roomid']]['time'];
                        }
                    }
                }
                $class[$key]['card2icon'] = array();
                //$class[$key]['signId'] = "";
                $card = pdo_fetchall("SELECT idcard,spic,usertype  FROM " . tablename($this->table_idcard) . " WHERE {$colid} = '{$row['childId']}' ORDER BY id DESC");
                $num = count($card);
                if ($num > 1){
                    foreach($card as $k =>$r){
                        if(!empty($r['idcard'])){
                            $class[$key]['rfid'] .= "#" . $r['idcard'];
                            if($r['usertype'] == '1' ){
                                $thumb = pdo_fetch("SELECT thumb  FROM " . tablename($this->table_teachers) . " WHERE id = '{$row['childId']}' ");
                            }
                            $class[$key]['card2icon'][$r['idcard']]= $r['usertype'] == '1' ? tomedia($thumb['thumb']) :tomedia($r['spic']);
                        }
                    }
                }else{
                    $class[$key]['rfid'] = $card['0']['idcard'];
                    if($card['0']['usertype'] == '1' ){
                        $thumb = pdo_fetch("SELECT thumb  FROM " . tablename($this->table_teachers) . " WHERE id = '{$row['childId']}' ");
                    }
                    $card2icon_key = $card['0']['idcard'] ? $card['0']['idcard']: 0;
                    $class[$key]['card2icon'][$card2icon_key]= $card['0']['usertype'] == '1'?tomedia($thumb['thumb']) :tomedia($card['0']['spic']);
                }
                $class[$key]['rfid'] = ltrim($class[$key]['rfid'],"#");
            }
            if($cls['type'] == 'jsfz'){
                $teachers['users'] = array_merge($teachers['users'],$class);
            }else{
                $students['users'] = array_merge($students['users'],$class);
            }
        }
        $result['data'][] = $students;
        $result['data'][] = $teachers;
    }
    exit(\json_encode($result));
}
if ($operation == 'allcard') {

    if(!empty($ckmac)){
        $result = array(
            'code'=>1000,
            'msg'=>'success',
            'data'=>array(),
            'ServerTime'=>date('Y-m-d H:i:s',time())
        );

        $sql = "SELECT a1.`sname` AS groupname ,a2.`s_name` AS real_name,a2.`icon` AS headIcon,a2.`area`AS tags ,a3.`idcard` AS idcard,s_type FROM `ims_wx_school_classify`  AS a1 LEFT JOIN `ims_wx_school_students` AS a2 ON a1.`sid`=a2.`bj_id` LEFT JOIN `ims_wx_school_idcard` AS a3 ON a2.`id`=a3.`sid` WHERE a1.`schoolid`={$school['id']}  AND a1.`type`='theclass' order by a1.`sname` desc";
        $students = pdo_fetchall($sql);

        foreach ($students as $student) {
            if ($student['idcard']) {
                $result['data'][$student['idcard']] =  array(
                    'groupname'	=> $student['groupname'],
                    'real_name'	=> $student['real_name'],
                    'headIcon'	=> $urls.$student['headIcon'],
                    //'tags'		=> ($student['board']?'住校':'走读').($tags?',':'').$tags
                   // 'tags'		=> $student['s_type'],
                    'tags'		=>  $student['s_type'] == 2?'住校':'走读',
                );
            }
        }

        $sql = "SELECT 
				a1.`sname` AS groupname ,
				a2.`tname` AS real_name,
				a2.`thumb` AS headIcon,
				a2.`star`AS tags ,
				a3.`idcard` AS idcard 
				FROM `ims_wx_school_classify` AS a1 LEFT JOIN `ims_wx_school_teachers` AS a2 ON a1.`schoolid`={$school['id']}  AND a1.`type`='jsfz' LEFT JOIN `ims_wx_school_idcard` AS a3  ON a2.`id`=a3.`tid` ";
        $teachers = pdo_fetchall($sql);

        foreach ($teachers as $teacher) {
            if ($teacher['idcard']) {
                $result['data'][$teacher['idcard']] =  array(
                    'groupname'	=> $teacher['groupname'],
                    'real_name'	=> $teacher['real_name'],
                    'headIcon'	=> $urls.$teacher['headIcon'],
                    //'tags'		=> ($student['board']?'住校':'走读').($tags?',':'').$tags
                    //'tags'		=> $teacher['tags']
                    'tags'		=> '教职工'
                );
            }
        }

    }
    exit(\json_encode($result));
}
if ($operation == 'login') {
    if(!empty($ckmac)){
        $class = pdo_fetchall("SELECT sid as classId, sname as className  FROM " . tablename($this->table_classify) . " WHERE weid = '{$weid}' And (type = 'theclass' OR type = 'jsfz') And schoolid = {$school['id']} ORDER BY sid DESC");
        foreach($class as $key =>$row) {
            $checkclass = pdo_fetch("SELECT type,pname  FROM " . tablename($this->table_classify) . " WHERE sid = '{$row['classId']}'");
            if ($checkclass['type'] == 'theclass'){
                $class[$key]['className'] = $row['className'];
                $class[$key]['channel'] = $row['classId'];
                $class[$key]['channeldesc'] = $row['className'];
            }else{
                $class[$key]['className'] = $checkclass['pname'];
                $class[$key]['channel'] = $row['classId'];
                $class[$key]['channeldesc'] = $checkclass['pname'];
            }
        }
        $result['data']['classInfo'] = $class;
        $result['data']['posturl']   = urlencode($_W['siteroot'] . 'app/index.php?i=' . $weid . '&c=entry&schoolid=' . $schoolid . '&do=checkxz&op=checknew&m=fm_jiaoyu');
        $result['data']['oldposturl']   = urlencode($_W['siteroot'] . 'app/index.php?i=' . $weid . '&c=entry&schoolid=' . $schoolid . '&do=checkxz&op=checkold&m=fm_jiaoyu');
        $result['data']['leaveurl']   = urlencode( $_W['siteroot'] . 'app/index.php?i=' . $weid . '&c=entry&schoolid=' . $schoolid . '&do=checkxz&op=getleave&m=fm_jiaoyu');
        $result['data']['outTimeUrl']   = urlencode($_W['siteroot'] . 'app/index.php?i=' . $weid . '&c=entry&schoolid=' . $schoolid . '&do=checkxz&op=classinfo&m=fm_jiaoyu');
        $result['data']['offlineflow']   = urlencode($_W['siteroot'] . 'app/index.php?i=' . $weid . '&c=entry&schoolid=' . $schoolid . '&do=checkxz&op=offlineflow&m=fm_jiaoyu');
        $result['data']['onlineflow']   = urlencode($_W['siteroot'] . 'app/index.php?i=' . $weid . '&c=entry&schoolid=' . $schoolid . '&do=checkxz&op=onlineflow&m=fm_jiaoyu');
        $result['data']['checkforks']   = urlencode($_W['siteroot'] . 'app/index.php?i=' . $weid . '&c=entry&schoolid=' . $schoolid . '&do=checkxz&op=checkforks&m=fm_jiaoyu');
        $result['data']['cardinfo']   = urlencode($_W['siteroot'] . 'app/index.php?i=' . $weid . '&c=entry&schoolid=' . $schoolid . '&do=checkxz&op=cardinfo&m=fm_jiaoyu');
        $result['data']['banpai']   = urlencode($_W['siteroot'] . 'app/index.php?i=' . $weid . '&c=entry&schoolid=' . $schoolid . '&do=checkxz&op=banpai&m=fm_jiaoyu');
        $result['data']['getallroomurl']   = urlencode($_W['siteroot'] . 'app/index.php?i=' . $weid . '&c=entry&schoolid=' . $schoolid . '&do=checkxz&op=getallroom&m=fm_jiaoyu');
		$result['data']['all']   = urlencode($_W['siteroot'] . 'app/index.php?i=' . $weid . '&c=entry&schoolid=' . $schoolid . '&do=checkxz&op=all&m=fm_jiaoyu');
        $result['data']['allcard']   = urlencode($_W['siteroot'] . 'app/index.php?i=' . $weid . '&c=entry&schoolid=' . $schoolid . '&do=checkxz&op=allcard&m=fm_jiaoyu');
        $result['data']['schoolInfo']= array(
            'name' => $school['title'],
            'schoolId' => $school['id'],
            'logo' => $urls.$school['logo'],
            'tel' => $school['tel']
        );
        $result['data']['userInfo'] = array(
            'email' => "admin@sina.com",
            'name' => '',
            'sex' => '',
            'teacherId' => '',
            'tel' => ''
        );
        if($ckmac['twmac'] == -1){
            $result['data']['tempid'] = 1;
        }else{
            $result['data']['tempid'] = $ckmac['twmac'];
        }
        if($ckmac['cardtype'] == 1){
            $result['data']['cardtype'] = 1;
        }
        if($ckmac['cardtype'] == 2){
            $result['data']['cardtype'] = 2;
        }
        $result['data']['finger'] = 2;
        $result['data']['apid'] = $ckmac['apid'];
        if($macid == '8c:18:d9:cd:e5:0d'){
            $result['data']['TerminalInfo'] = array(
                'fenqq' => 'ttyS2',
                'zhiw' => "ttyS3",
                'card' => "ttyS1",
                'shext' => '',
                'by1' => '',
                'by2' => '',
                'by3' => ''
            );
        }else{
            $result['data']['TerminalInfo'] = array(
                'fenqq' => 'ttyS4',
                'zhiw' => "ttyS3",
                'card' => "ttyS2",
                'shext' => '',
                'by1' => '',
                'by2' => '',
                'by3' => ''
            );
        }


        //返回ipc信息
        $ipc = unserialize($ckmac['ipc']);
        if(!$ipc)$ipc = array();

        $result['data']['ipc'] = $ipc;

        $result['code'] = 1000;
        $result['msg'] = "success";
        $result['ServerTime'] = date('Y-m-d H:i:s',time());

        echo json_encode($result);
    }
}
if ($operation == 'banpai') {
    $nowtime = time();
    $date = date('Y-m-d',$nowtime);
    $riqi = explode ('-', $date);
    $starttime = mktime(0,0,0,$riqi[1],$riqi[2],$riqi[0]);
    $endtime = $starttime + 86399;
    $condition = " AND createtime > '{$starttime}' AND createtime < '{$endtime}'";
    $classid = $ckmac['bj_id'];
    if($school['issale'] == 1){
        $classid = $ckmac['js_id'];
    }
    $theclass = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " WHERE weid = '{$weid}' And schoolid = '{$school['id']}' And sid = '{$classid}'");
    /*基本信息*/
    $banner = unserialize($ckmac['banner']);
    $result['sch_name'] = $school['title'];
    $result['sch_logo'] = tomedia($school['logo']);
    $result['areaid'] = $ckmac['areaid'];
    $result['cityname'] = $ckmac['cityname'];
    $result['mac_id'] = $macid;
    $result['model_type'] = $ckmac['model_type'];
    /*班级*/
    if($school['issale'] == 1){
        $result['classtype'] = 2;
        $result['class_img'] = empty($banner['class_img'])?tomedia($school['thumb']):tomedia($banner['class_img']);
        mload()->model('kc');
        $nowkc_ks = Getnearks($classid,$starttime,$endtime);
        $nowkc = $nowkc_ks['nowkc'][0];//当前课程
        $nowks = $nowkc_ks['nowks'][0];//当前课时
        $result['class_name'] = $nowkc['name'];//正在上课的课程名称或即将开始
        $result['class_course'] = getksbiao($school['id'],$classid,$starttime,$endtime);//未做
        $result['class_room_name'] = $theclass['sname'];//使用教室名称
        $grade = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE weid = '{$weid}' And schoolid = '{$school['id']}' And sid = '{$nowkc['xq_id']}'");
        $result['grade_name'] = $grade['sname'];
        $master = pdo_fetch("SELECT tname,mobile,thumb FROM " . tablename($this->table_teachers) . " WHERE weid = '{$weid}' And schoolid = '{$school['id']}' And id = '{$nowks['tid']}'");
        $result['master_img'] = !empty($master['thumb'])?tomedia($school['tpic']):tomedia($master['thumb']);
        $result['master_name'] = $master['tname'];
        $result['master_phone'] = $master['mobile'];
        $kc_stuinfo = GetStuInfoByKs($school['id'],$nowks['id']);
        $result['stu_num'] = $kc_stuinfo['allstu'];
        $result['ksid'] = $nowks['id'];
        $result['stu_absent_num'] = $kc_stuinfo['leavestu'];
        $result['stu_leave_num'] = $kc_stuinfo['allstu'] - $kc_stuinfo['signstu'];
        $result['class_honor'] = array();
        $class_honor = array('http://kstms.com/img/rongyu.png','http://kstms.com/img/rongyu.png');
        $result['class_honor'] = $class_honor;
        $result['student_honor'] = array();
    }else{
        $result['classtype'] = 1;
        $result['class_img'] = empty($banner['class_img'])?tomedia($school['thumb']):tomedia($banner['class_img']);
        $result['class_name'] = $theclass['sname'];
        $result['class_course'] = getkcbiao($school['id'],$nowtime,$classid);
        $classroom = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE weid = '{$weid}' And schoolid = '{$school['id']}' And sid = '{$theclass['js_id']}'");
        $result['class_room_name'] = empty($classroom) ? $theclass['sname'] : $classroom['sname'];//未绑定教室使用班级名称
        $grade = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE weid = '{$weid}' And schoolid = '{$school['id']}' And sid = '{$theclass['parentid']}'");
        $result['grade_name'] = $grade['sname'];
        $master = pdo_fetch("SELECT tname,mobile,thumb FROM " . tablename($this->table_teachers) . " WHERE weid = '{$weid}' And schoolid = '{$school['id']}' And id = '{$theclass['tid']}'");
        $result['master_img'] = !empty($master['thumb'])?tomedia($school['tpic']):tomedia($master['thumb']);
        $result['master_name'] = $master['tname'];
        $result['master_phone'] = $master['mobile'];
        $stu_num =  pdo_fetchcolumn("select COUNT(*) FROM ".tablename($this->table_students)." WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND bj_id = '{$classid}'");
        $result['stu_num'] = $stu_num;
        $stu_leave_num =  pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_leave) . " where schoolid = '{$schoolid}' And weid = '{$weid}' And tid = 0 And bj_id = '{$classid}' And isliuyan = 0 And status = 1 And endtime1 > '{$endtime}' ");
        $result['stu_leave_num'] = $stu_leave_num;
        $checkmub = pdo_fetchcolumn("SELECT  COUNT(distinct sid) FROM " . tablename($this->table_checklog) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}'  And bj_id = '{$classid}' And isconfirm = 1 $condition ");
        $result['stu_absent_num'] = $stu_num - $checkmub;
        $result['class_honor'] = array();
        $class_honor = array('http://kstms.com/img/rongyu.png','http://kstms.com/img/rongyu.png');
        $result['class_honor'] = $class_honor;
        $student_honor = array();
        if($theclass['is_bjzx'] == 1 && !empty($theclass['star'])){
            $star = unserialize($theclass['star']);
            $student_honor[0] = array(
                'stu_id' => 1,
                'stu_name' => $star['name1'],
                'stu_img' => !empty($star['icon1'])?tomedia($star['icon1']):tomedia($school['spic']),
                'stu_label' => "班级之星"
            );
            $student_honor[1] = array(
                'stu_id' => 2,
                'stu_name' => $star['name2'],
                'stu_img' => !empty($star['icon2'])?tomedia($star['icon2']):tomedia($school['spic']),
                'stu_label' => "班级之星"
            );
            $student_honor[2] = array(
                'stu_id' => 3,
                'stu_name' => $star['name3'],
                'stu_img' => !empty($star['icon3'])?tomedia($star['icon3']):tomedia($school['spic']),
                'stu_label' => "班级之星"
            );
            $student_honor[3] = array(
                'stu_id' => 4,
                'stu_name' => $star['name4'],
                'stu_img' => !empty($star['icon4'])?tomedia($star['icon4']):tomedia($school['spic']),
                'stu_label' => "班级之星"
            );
        }else{
            $student_honor = ''; //班级之星
        }
        $result['student_honor'] = $student_honor;
    }
    $notice = pdo_fetch("SELECT * FROM " . tablename($this->table_notice) . " WHERE weid = '{$weid}' And schoolid = '{$school['id']}' And (type = 1 Or type = 2) ORDER BY createtime DESC ");
    $noticeteacher = pdo_fetch("SELECT tname FROM " . tablename($this->table_teachers) . " WHERE weid = '{$weid}' And schoolid = '{$school['id']}' And id = '{$notice['tid']}'");
    $result['notice_dpt'] = $noticeteacher['tname'];
    $result['notice_name'] = $notice['title'];
    $result['notice_time'] = date('Y-m-d H:i',$notice['createtime']);
    $result['notice_txt'] = $notice['content'];
    /*公播*/
    $result['video_url'] = $banner['video_url'];
    /*考试*/
    $exam_name = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE weid = '{$weid}' And schoolid = '{$school['id']}' And sid = '{$ckmac['qh_id']}'");
    $result['exam_name'] = $exam_name['sname'];
    $result['exam_room_name'] = $ckmac['exam_room_name'];
    $result['exam_plan'] = array();
    if($ckmac['exam_plan']){
        $result['exam_plan'] = unserialize($ckmac['exam_plan']);//压缩数组
    }
    /*备用字段*/
    $result['spare_1'] = '';
    $result['spare_2'] = '';
    $result['spare_3'] = '';
    $result['spare_4'] = '';
    /*处理同步状态*/
    $temp = array(
        'isflow' => 2,
        'class_img' => $banner['class_img'],
        'video_url' => $banner['video_url'],
    );
    $temp1['banner'] = serialize($temp);
    pdo_update($this->table_checkmac, $temp1, array('id' => $ckmac['id']));
    $result['cardtype'] = $ckmac['cardtype'];
    $result['code'] = 1000;
    $result['msg'] = "success";
    $result['ServerTime'] = date('Y-m-d H:i:s',time());
    echo json_encode($result);
}

if ($operation == 'classinfo') {
    $classid = $_GPC['classId'];
    $isfz = pdo_fetch("SELECT type,datesetid  FROM " . tablename($this->table_classify) . " WHERE weid = '{$weid}' And schoolid = '{$school['id']}' And sid = '{$classid}'");
    if ($isfz['type'] == 'theclass'){
        $nowdate = date("Y-n-j",time());
        $nowyear = date("Y",time());
        $nowweek = date("w",time());
        $todaytype = 0;
        $todaytimeset = array(
            array(
                'start'=>'00:00',
                'end'  =>'23:59'
            ),
        );
        if(!empty($isfz['datesetid'])){
            $checkdateset      =  pdo_fetch("SELECT * FROM " . tablename($this->table_checkdateset) . " WHERE weid = '{$weid}' And schoolid = {$school['id']} and  id = '{$isfz['datesetid']}'");
            $checkdateset_holi =  pdo_fetch("SELECT * FROM " . tablename($this->table_checkdatedetail) . " WHERE weid = '{$weid}' And schoolid = {$school['id']} and  checkdatesetid = '{$isfz['datesetid']}' and year = '{$nowyear}' ");

            $checktime         =  pdo_fetchall("SELECT * FROM " . tablename($this->table_checktimeset) . " WHERE weid = '{$weid}' And schoolid = {$school['id']} and  checkdatesetid = '{$isfz['datesetid']}' and date = '{$nowdate}' ORDER BY id ASC ");
            if(!empty($checktime)){
                if($checktime[0]['type'] == 6){
                    //1放假2上课
                    $todaytype = 1;
                }elseif($checktime[0]['type'] == 5){
                    $todaytype    = 2;
                    $todaytimeset = $checktime;
                }
            }else{
                if(($nowdate >= $checkdateset_holi['win_start'] && $nowdate <=$checkdateset_holi['win_end']) || ($nowdate >= $checkdateset_holi['sum_start'] && $nowdate <=$checkdateset_holi['sum_end'])){
                    $todaytype = 1;
                }else{
                    $timeset_work = pdo_fetchall("SELECT start,end FROM " . tablename($this->table_checktimeset) . " WHERE weid = '{$weid}' And schoolid = {$school['id']} and  checkdatesetid = '{$isfz['datesetid']}' and type=1 ORDER BY id ASC ");
                    //星期五
                    if($nowweek == 5){
                        $todaytype = 2;
                        if($checkdateset['friday'] == 1){
                            $timeset_fri = pdo_fetchall("SELECT start,end FROM " . tablename($this->table_checktimeset) . " WHERE weid = '{$weid}' And schoolid = {$school['id']} and  checkdatesetid = '{$isfz['datesetid']}' and type=2 ORDER BY id ASC ");
                            $todaytimeset = $timeset_fri;
                        }else{
                            $todaytimeset = $timeset_work;
                        }
                        //星期六
                    }elseif($nowweek == 6){
                        if($checkdateset['saturday'] == 1){
                            $timeset_sat = pdo_fetchall("SELECT start,end FROM " . tablename($this->table_checktimeset) . " WHERE weid = '{$weid}' And schoolid = {$school['id']} and  checkdatesetid = '{$isfz['datesetid']}' and type=3 ORDER BY id ASC ");
                            $todaytype = 2;
                            $todaytimeset = $timeset_sat;
                        }else{
                            $todaytype = 1;
                        }

                        //星期天
                    }elseif($nowweek == 0){
                        if($checkdateset['sunday'] == 1){
                            $timeset_sun = pdo_fetchall("SELECT start,end FROM " . tablename($this->table_checktimeset) . " WHERE weid = '{$weid}' And schoolid = {$school['id']} and  checkdatesetid = '{$isfz['datesetid']}' and type=4 ORDER BY id ASC ");
                            $todaytype    = 2;
                            $todaytimeset = $timeset_sun;
                        }else{
                            $todaytype    = 1;
                        }
                        //工作日
                    }else{
                        $todaytype    = 2;
                        $todaytimeset = $timeset_work;
                    }
                }
            }

        }

        if(!empty($ckmac['apid'])){
            $todaytype = 0;
            $todaytimeset = array(
                array(
                    'start'=>'00:00',
                    'end'  =>'23:59'
                ),
            );
        }


        $result['data']['todaytype'] = $todaytype;
        $result['data']['todaytimeset'] = $todaytimeset;
        if(!empty($ckmac)){
            $class = pdo_fetchall("SELECT id as childId, bj_id as classId, icon as headIcon, s_name as name,s_type FROM " . tablename($this->table_students) . " WHERE weid = '{$weid}' And schoolid = {$school['id']} And bj_id = '{$classid}' ORDER BY id DESC");
            foreach($class as $key =>$row) {
                if(!empty($row['headIcon'])){
                    $class[$key]['headIcon'] = $urls.$row['headIcon'];
                }else{
                    $class[$key]['headIcon'] = !empty($school['spic'])? $urls.$school['spic'] : "";
                }
                $class[$key]['name'] = $row['name'];
                $class[$key]['signId'] = "";
                $class[$key]['card2icon'] = array();
                $card = pdo_fetchall("SELECT idcard,spic  FROM " . tablename($this->table_idcard) . " WHERE sid = '{$row['childId']}' ORDER BY id DESC");
                $num = count($card);
                if ($num > 1){
                    foreach($card as $k =>$r){
                        if(!empty($r['idcard'])){
                            $class[$key]['signId'] .= "#" . $r['idcard'];
                            $class[$key]['card2icon'][$r['idcard']]= tomedia($r['spic']);
                        }
                    }
                }else{
                    $class[$key]['signId'] = $card['0']['idcard'];
                    $card2icon_key = $card['0']['idcard'] ? $card['0']['idcard']: 0;
                    $class[$key]['card2icon'][$card2icon_key]=tomedia($card['0']['spic']);
                }
                $class[$key]['fingerid1'] = "-1";
                $class[$key]['fingerid2'] = "-1";
                $class[$key]['fingerid3'] = "-1";
                $class[$key]['fingerid4'] = "-1";
                $class[$key]['fingerid5'] = "-1";
            }
            $result['data']['childs'] = $class;
            $result['code'] = 1000;
            $result['msg'] = "success";
            $result['ServerTime'] = date('Y-m-d H:i:s',time());
            echo json_encode($result);
        }
    }else{
        if(!empty($ckmac)){
            $class = pdo_fetchall("SELECT id as TID, fz_id as classId, thumb as headIcon, tname as name FROM " . tablename($this->table_teachers) . " WHERE weid = '{$weid}' And schoolid = {$school['id']} And fz_id = '{$classid}' ORDER BY id DESC");
            foreach($class as $key =>$row) {
                if(!empty($row['headIcon'])){
                    $class[$key]['headIcon'] = $urls.$row['headIcon'];
                }else{
                    $class[$key]['headIcon'] = !empty($school['tpic'])? $urls.$school['tpic'] : "";
                }
                $class[$key]['childId'] = "909".$row['TID'];
                $class[$key]['name'] = $row['name'];
                $class[$key]['signId'] = "";
                $class[$key]['card2icon'] = array();
                $card = pdo_fetchall("SELECT idcard  FROM " . tablename($this->table_idcard) . " WHERE tid = '{$row['TID']}' ORDER BY id DESC");
                $num = count($card);
                if ($num > 1){
                    foreach($card as $k =>$r){
                        if(!empty($r['idcard'])){
                            $class[$key]['signId'] .= "#" . $r['idcard'];
                            $class[$key]['card2icon'][$r['idcard']]= tomedia($row['headIcon']);
                        }
                    }
                }else{
                    $class[$key]['signId'] = $card['0']['idcard'];
                    $card2icon_key = $card['0']['idcard'] ? $card['0']['idcard']: 0;
                    $class[$key]['card2icon'][$card2icon_key]=tomedia($row['headIcon']);
                }
            }
            $result['data']['childs'] = $class;
            $result['code'] = 1000;
            $result['msg'] = "success";
            $result['ServerTime'] = date('Y-m-d H:i:s',time());
            echo json_encode($result);
        }
    }
}

if ($operation == 'check') {
    $fstype = false;
    $ckuser = pdo_fetch("SELECT * FROM " . tablename($this->table_idcard) . " WHERE idcard = :idcard And schoolid = :schoolid ", array(':idcard' =>$_GPC['signId'],':schoolid' =>$schoolid));
    if($_GPC['mactype'] == 'other'){
        $signTime = strtotime($_GPC['signTime']);
    }else{
        $signTime = trim($_GPC['signTime']);
    }
    $checkthisdata = pdo_fetch("SELECT * FROM " . tablename($this->table_checklog) . " WHERE cardid = :cardid And schoolid = :schoolid And createtime = :createtime ", array(':cardid' =>$_GPC['signId'],':schoolid' =>$schoolid,':createtime' =>$signTime));
    if(empty($checkthisdata)){
        if(!empty($ckuser) || $_GPC['signId'] == '9999999999'){
            $times = TIMESTAMP;
            $nowtime = date('H:i',$signTime);
            if($_GPC['picurl']) {
                load()->func('file');
                $urls = "http://www.daren007.com/attachment/";
                $path = "images/fm_jiaoyu/check/". date('Y/m/d/');
                if (!is_dir(IA_ROOT."/attachment/". $path)) {
                    mkdirs(IA_ROOT."/attachment/". $path, "0777");
                }
                $rand = random(30);
                if(!empty($_GPC['picurl'])) {
                    $picurl = $path.$rand."_1.jpg";
                    if($_GPC['mactype'] == 'other'){
                        $pic_url = base64_decode(str_replace(" ","+",$_GPC['picurl']));
                    }else{
                        $pic_url = file_get_contents($urls.$_GPC['picurl']);
                    }
                    file_write($picurl,$pic_url);
                    if (!empty($_W['setting']['remote']['type'])){
                        $remotestatus = file_remote_upload($picurl);
                    }
                    $pic = $picurl;
                }
                if(!empty($_GPC['picurl2'])) {
                    $picurl2 = $path.$rand."_2.jpg";
                    if($_GPC['mactype'] == 'other'){
                        $pic_url2 = base64_decode(str_replace(" ","+",$_GPC['picurl2']));
                    }else{
                        $pic_url2 = file_get_contents($urls.$_GPC['picurl2']);
                    }
                    file_write($picurl2,$pic_url2);
                    if (!empty($_W['setting']['remote']['type'])){
                        $remotestatus = file_remote_upload($picurl2);
                    }
                    $pic2 = $picurl2;
                }
            }
            $signMode = $_GPC['signMode'];
            if($ckmac['type'] !=0){
                include 'checktime2.php';
            }else{
                include 'checktime.php';
            }
            if($_GPC['signId'] == '9999999999'){
                $data = array(
                    'weid' => $weid,
                    'schoolid' => $schoolid,
                    'macid' => $ckmac['id'],
                    'lon' => $_GPC['lon'],
                    'lat' => $_GPC['lat'],
                    'cardid' => $_GPC ['signId'],
                    'type' => "无卡进出",
                    'pic' => $pic,
                    'pic2' => $pic2,
                    'leixing' => $leixing,
                    'createtime' => $signTime
                );
                pdo_insert($this->table_checklog, $data);
                $fstype = true;
            }


            if($ckuser['cardtype'] == 1){
                //个人卡处理
                if(!empty($ckuser['sid'])){
                    $bj = pdo_fetch("SELECT bj_id,roomid FROM " . tablename($this->table_students) . " WHERE id = :id ", array(':id' =>$ckuser['sid']));
                    if($school['is_cardpay'] == 1){
                        if($ckuser['severend'] > $times){
                            if(!empty($ckmac['apid'])){
                                if(!empty($bj['roomid'])){
                                    $this_roomid = $bj['roomid'];
                                    $this_apid = $ckmac['apid'];
                                }else{
                                    $this_roomid = 0 ;
                                    $this_apid = 0 ;
                                }
                                if($leixing == 1){
                                    $ap_type = 1;
                                }elseif($leixing == 2){
                                    $ap_type = 2;
                                }else{
                                    $ap_type = 0 ;
                                }
                                $data = array(
                                    'weid'        => $weid,
                                    'schoolid'    => $schoolid,
                                    'macid'       => $ckmac['id'],
                                    'cardid'      => $_GPC['signId'],
                                    'sid'         => $ckuser['sid'],
                                    'bj_id'       => $bj['bj_id'],
                                    'lon'         => $_GPC['lon'],
                                    'lat'         => $_GPC['lat'],
                                    'pic'         => $pic,
                                    'pic2'        => $pic2,
                                    'sc_ap'		  => 1,
                                    'ap_type' 	  => $ap_type,
                                    'roomid' 	  => $this_roomid,
                                    'apid'		  => $this_apid,
                                    'createtime'  => $signTime
                                );

                            }else{
                                $data = array(
                                    'weid' => $weid,
                                    'schoolid' => $schoolid,
                                    'macid' => $ckmac['id'],
                                    'cardid' => $_GPC ['signId'],
                                    'sid' => $ckuser['sid'],
                                    'bj_id' => $bj['bj_id'],
                                    'type' => $type,
                                    'pic' => $pic,
                                    'pic2' => $pic2,
                                    'lon' => $_GPC['lon'],
                                    'lat' => $_GPC['lat'],
                                    'temperature' => $_GPC ['signTemp'],
                                    'leixing' => $leixing,
                                    'pard' => $ckuser['pard'],
                                    'createtime' => $signTime
                                );
                            }
                            pdo_insert($this->table_checklog, $data);
                            $checkid = pdo_insertid();
                            if($school['send_overtime'] >= 1){
                                $overtime = $school['send_overtime']*60;
                                $timecha = $times - $signTime;
                                if($overtime >= $timecha){
                                    if(is_showyl()){
                                        $this->sendMobileJxlxtz_yl($schoolid, $weid, $ckuser['sid'], $checkid,$ckmac['id']);
                                    }else{
                                        $this->sendMobileJxlxtz($schoolid, $weid, $bj['bj_id'], $ckuser['sid'], $type, $leixing, $checkid, $ckuser['pard']);
                                    }
                                }else{
                                    $result['info'] = "延迟发送之数据将不推送刷卡提示";
                                }
                            }else{
                                if(is_showyl()){
                                    $this->sendMobileJxlxtz_yl($schoolid, $weid, $ckuser['sid'], $checkid,$ckmac['id']);
                                }else{
                                    $this->sendMobileJxlxtz($schoolid, $weid, $bj['bj_id'], $ckuser['sid'], $type, $leixing, $checkid, $ckuser['pard']);
                                }
                            }
                        }else{
                            $result['info'] = "本卡已失效,请联系学校管理员";
                        }
                        $fstype = true;
                    }else{
                        if(!empty($ckmac['apid'])){
                            if(!empty($bj['roomid'])){
                                $this_roomid = $bj['roomid'];
                                $this_apid = $ckmac['apid'];
                            }else{
                                $this_roomid = 0 ;
                                $this_apid = 0 ;
                            }
                            if($leixing == 1){
                                $ap_type = 1;
                            }elseif($leixing == 2){
                                $ap_type = 2;
                            }else{
                                $ap_type = 0 ;
                            }
                            $data = array(
                                'weid'        => $weid,
                                'schoolid'    => $schoolid,
                                'macid'       => $ckmac['id'],
                                'cardid'      => $_GPC['signId'],
                                'sid'         => $ckuser['sid'],
                                'bj_id'       => $bj['bj_id'],
                                'lon'         => $_GPC['lon'],
                                'lat'         => $_GPC['lat'],
                                'pic'         => $pic,
                                'pic2'        => $pic2,
                                'sc_ap'		  => 1,
                                'ap_type' 	  => $ap_type,
                                'roomid' 	  => $this_roomid,
                                'apid'		  => $this_apid,
                                'createtime'  => $signTime
                            );

                        }else{
                            $data = array(
                                'weid' => $weid,
                                'schoolid' => $schoolid,
                                'macid' => $ckmac['id'],
                                'cardid' => $_GPC ['signId'],
                                'sid' => $ckuser['sid'],
                                'bj_id' => $bj['bj_id'],
                                'type' => $type,
                                'pic' => $pic,
                                'pic2' => $pic2,
                                'lon' => $_GPC['lon'],
                                'lat' => $_GPC['lat'],
                                'temperature' => $_GPC ['signTemp'],
                                'leixing' => $leixing,
                                'pard' => $ckuser['pard'],
                                'createtime' => $signTime
                            );
                        }
                        pdo_insert($this->table_checklog, $data);
                        $checkid = pdo_insertid();
                        if($school['send_overtime'] >= 1){
                            $overtime = $school['send_overtime']*60;
                            $timecha = $times - $signTime;
                            if($overtime >= $timecha){
                                if(is_showyl()){
                                    $this->sendMobileJxlxtz_yl($schoolid, $weid, $ckuser['sid'], $checkid,$ckmac['id']);
                                }else{
                                    $this->sendMobileJxlxtz($schoolid, $weid, $bj['bj_id'], $ckuser['sid'], $type, $leixing, $checkid, $ckuser['pard']);
                                }
                            }else{
                                $result['info'] = "延迟发送之数据将不推送刷卡提示";
                            }
                        }else{
                            if(is_showyl()){
                                $this->sendMobileJxlxtz_yl($schoolid, $weid, $ckuser['sid'], $checkid,$ckmac['id']);
                            }else{
                                $this->sendMobileJxlxtz($schoolid, $weid, $bj['bj_id'], $ckuser['sid'], $type, $leixing, $checkid, $ckuser['pard']);
                            }
                        }
                        $fstype = true;
                    }
                }
                if(!empty($ckuser['tid'])){
                    $data = array(
                        'weid' => $weid,
                        'schoolid' => $schoolid,
                        'macid' => $ckmac['id'],
                        'cardid' => $_GPC ['signId'],
                        'tid' => $ckuser['tid'],
                        'type' => $type,
                        'leixing' => $leixing,
                        'pic' => $pic,
                        'pic2' => $pic2,
                        'pard' => 1,
                        'createtime' => $signTime
                    );
                    pdo_insert($this->table_checklog, $data);
                    $fstype = true;
                }
            }elseif($ckuser['cardtype'] ==2){
                //班级卡处理
                $bj_id = $ckuser['bj_id'];
                $ThisCardStudents = pdo_fetchall("SELECT id FROM " . tablename($this->table_students) . " WHERE bj_id = :bj_id and schoolid = :schoolid", array(':bj_id' =>$bj_id,':schoolid'=>$schoolid));
                foreach ($ThisCardStudents as $key=>$value){
                    $data = array(
                        'weid' => $weid,
                        'schoolid' => $schoolid,
                        'macid' => $ckmac['id'],
                        'cardid' => $_GPC['signId'],
                        'sid' => $value['id'],
                        'bj_id' => $bj_id,
                        'type' => $type,
                        'pic' => $pic,
                        'pic2' => $pic2,
                        'lon' => $_GPC['lon'],
                        'lat' => $_GPC['lat'],
                        'temperature' => $_GPC['signTemp'],
                        'leixing' => $leixing,
                        'pard' => $ckuser['pard'],
                        'createtime' => $signTime
                    );
                    pdo_insert($this->table_checklog, $data);
                    $checkid = pdo_insertid();
                    if($school['send_overtime'] >= 1){
                        $overtime = $school['send_overtime']*60;
                        $timecha = $times - $signTime;
                        if($overtime >= $timecha){
                            $this->sendMobileJxlxtz($schoolid, $weid, $bj_id, $value['id'], $type, $leixing, $checkid, $ckuser['pard']);
                        }else{
                            $result['info'] = "延迟发送之数据将不推送刷卡提示";
                        }
                    }else{
                        $this->sendMobileJxlxtz($schoolid, $weid,$bj_id,$value['id'], $type, $leixing, $checkid, $ckuser['pard']);
                    }
                }
                $fstype = true;
            }
        }else{
            $result['info'] = "本卡未绑定任何学生或老师";
        }
    }else{
        $fstype = true;
        $result['info'] = "不可重复相同刷卡数据";
    }
    if ($fstype ==true){
        $result['data'] = "";
        $result['code'] = 1000;
        $result['msg'] = "success";
        $result['ServerTime'] = date('Y-m-d H:i:s',time());
        echo json_encode($result);
        exit;
    }else{
        $result['data'] = "";
        $result['code'] = 300;
        $result['msg'] = "lose";
        $result['ServerTime'] = date('Y-m-d H:i:s',time());
        echo json_encode($result);
        exit;
    }
}

if ($operation == 'checkold') {
    $signData = str_replace('&quot;','"',$_GPC['signData']);
    $datasss = json_decode($signData,true);
    if(!empty($datasss)){
        $up_file = $_FILES;
        foreach($datasss as $k => $v){
            $fstype = false;
            $ckuser = pdo_fetch("SELECT * FROM " . tablename($this->table_idcard) . " WHERE idcard = :idcard And schoolid = :schoolid ", array(':idcard' =>$v['signId'],':schoolid' =>$schoolid));
            $signTime = strtotime($v['signTime']);
            $checkthisdata = pdo_fetch("SELECT * FROM " . tablename($this->table_checklog) . " WHERE cardid = :cardid And schoolid = :schoolid And createtime = :createtime ", array(':cardid' =>$v['signId'],':schoolid' =>$schoolid,':createtime' =>$signTime));
            if(empty($checkthisdata)){
                if(!empty($ckuser) || $v['signId'] == '9999999999'){
                    $times = TIMESTAMP;
                    $nowtime = date('H:i',$signTime);
                    load()->func('file');
                    $files_image = current($up_file);//第一张图
                    $files_image2 = next($up_file);//第二张图
                    $path = "images/fm_jiaoyu/check/". date('Y/m/d/');
                    if(!empty($files_image)) {
                        if (!is_dir(IA_ROOT."/attachment/". $path)) {
                            mkdirs(IA_ROOT."/attachment/". $path, "0777");
                        }
                        $picurl = $path.random(30) .".jpg";
                        move_uploaded_file($_FILES["file1"]["tmp_name"], ATTACHMENT_ROOT."/$picurl");
                        if (!empty($_W['setting']['remote']['type'])){
                            $remotestatus = file_remote_upload($picurl);
                        }
                        $pic = $picurl;
                    }
                    if(!empty($files_image2)) {
                        $picurl2 = $path.random(31) .".jpg";
                        move_uploaded_file($_FILES["file2"]["tmp_name"], ATTACHMENT_ROOT."/$picurl2");
                        if (!empty($_W['setting']['remote']['type'])){
                            $remotestatus = file_remote_upload($picurl2);
                        }
                        $pic2 = $picurl2;
                    }
                    $signMode = $v['signMode'];
                    if($ckmac['type'] !=0){
                        include 'checktime2.php';
                    }else{
                        include 'checktime.php';
                    }
                    if($v['signId'] == '9999999999'){
                        $data = array(
                            'weid' => $weid,
                            'schoolid' => $schoolid,
                            'macid' => $ckmac['id'],
                            'lon' => $v['lon'],
                            'lat' => $v['lat'],
                            'cardid' => $v ['signId'],
                            'type' => "无卡进出",
                            'pic' => $pic,
                            'pic2' => $pic2,
                            'leixing' => $leixing,
                            'createtime' => $signTime
                        );
                        pdo_insert($this->table_checklog, $data);
                        $fstype = true;
                    }
                    if($ckuser['cardtype'] ==1) {
                        if (!empty($ckuser['sid'])) {
                            $bj = pdo_fetch("SELECT bj_id,roomid FROM " . tablename($this->table_students) . " WHERE id = :id ", array(':id' => $ckuser['sid']));
                            if ($school['is_cardpay'] == 1) {
                                if ($ckuser['severend'] > $times) {

                                    if (!empty($ckmac['apid'])) {
                                        if (!empty($bj['roomid'])) {
                                            $this_roomid = $bj['roomid'];
                                            $this_apid   = $ckmac['apid'];
                                        } else {
                                            $this_roomid = 0;
                                            $this_apid   = 0;
                                        }
                                        if ($leixing == 1) {
                                            $ap_type = 1;
                                        } elseif ($leixing == 2) {
                                            $ap_type = 2;
                                        } else {
                                            $ap_type = 0;
                                        }
                                        $data = array(
                                            'weid'       => $weid,
                                            'schoolid'   => $schoolid,
                                            'macid'      => $ckmac['id'],
                                            'cardid'     => $v['signId'],
                                            'sid'        => $ckuser['sid'],
                                            'bj_id'      => $bj['bj_id'],
                                            'lon'        => $v['lon'],
                                            'lat'        => $v['lat'],
                                            'pic'        => $pic,
                                            'pic2'       => $pic2,
                                            'sc_ap'      => 1,
                                            'ap_type'    => $ap_type,
                                            'roomid'     => $this_roomid,
                                            'apid'       => $this_apid,
                                            'createtime' => $signTime
                                        );

                                    } else {
                                        $data = array(
                                            'weid'        => $weid,
                                            'schoolid'    => $schoolid,
                                            'macid'       => $ckmac['id'],
                                            'cardid'      => $v ['signId'],
                                            'sid'         => $ckuser['sid'],
                                            'bj_id'       => $bj['bj_id'],
                                            'type'        => $type,
                                            'pic'         => $pic,
                                            'pic2'        => $pic2,
                                            'lon'         => $v['lon'],
                                            'lat'         => $v['lat'],
                                            'temperature' => $v ['signTemp'],
                                            'leixing'     => $leixing,
                                            'pard'        => $ckuser['pard'],
                                            'createtime'  => $signTime
                                        );
                                    }


                                    /*
                                     $data = array(
                                     'weid' => $weid,
                                     'schoolid' => $schoolid,
                                     'macid' => $ckmac['id'],
                                     'cardid' => $v ['signId'],
                                     'sid' => $ckuser['sid'],
                                     'bj_id' => $bj['bj_id'],
                                     'type' => $type,
                                     'pic' => $pic,
                                     'pic2' => $pic2,
                                     'sc_ap' => 6,
                                     'lon' => $v['lon'],
                                     'lat' => $v['lat'],
                                     'temperature' => $v ['signTemp'],
                                     'leixing' => $leixing,
                                     'pard' => $ckuser['pard'],
                                     'createtime' => $signTime
                                     );*/
                                    pdo_insert($this->table_checklog, $data);
                                    $checkid = pdo_insertid();
                                    if ($school['send_overtime'] >= 1) {
                                        $overtime = $school['send_overtime'] * 60;
                                        $timecha  = $times - $signTime;
                                        if ($overtime >= $timecha) {
                                            if (is_showyl()) {
                                                $this->sendMobileJxlxtz_yl($schoolid, $weid, $ckuser['sid'], $checkid, $ckmac['id']);
                                            } else {
                                                $this->sendMobileJxlxtz($schoolid, $weid, $bj['bj_id'], $ckuser['sid'], $type, $leixing, $checkid, $ckuser['pard']);
                                            }
                                        } else {
                                            $result['info'] = "延迟发送之数据将不推送刷卡提示";
                                        }
                                    } else {
                                        if (is_showyl()) {
                                            $this->sendMobileJxlxtz_yl($schoolid, $weid, $ckuser['sid'], $checkid, $ckmac['id']);
                                        } else {
                                            $this->sendMobileJxlxtz($schoolid, $weid, $bj['bj_id'], $ckuser['sid'], $type, $leixing, $checkid, $ckuser['pard']);
                                        }
                                    }
                                } else {
                                    $result['info'] = "本卡已失效,请联系学校管理员";
                                }
                                $fstype = true;
                            } else {
                                if (!empty($ckmac['apid'])) {
                                    if (!empty($bj['roomid'])) {
                                        $this_roomid = $bj['roomid'];
                                        $this_apid   = $ckmac['apid'];
                                    } else {
                                        $this_roomid = 0;
                                        $this_apid   = 0;
                                    }
                                    if ($leixing == 1) {
                                        $ap_type = 1;
                                    } elseif ($leixing == 2) {
                                        $ap_type = 2;
                                    } else {
                                        $ap_type = 0;
                                    }
                                    $data = array(
                                        'weid'       => $weid,
                                        'schoolid'   => $schoolid,
                                        'macid'      => $ckmac['id'],
                                        'cardid'     => $v['signId'],
                                        'sid'        => $ckuser['sid'],
                                        'bj_id'      => $bj['bj_id'],
                                        'lon'        => $v['lon'],
                                        'lat'        => $v['lat'],
                                        'pic'        => $pic,
                                        'pic2'       => $pic2,
                                        'sc_ap'      => 1,
                                        'ap_type'    => $ap_type,
                                        'roomid'     => $this_roomid,
                                        'apid'       => $this_apid,
                                        'createtime' => $signTime
                                    );

                                } else {
                                    $data = array(
                                        'weid'        => $weid,
                                        'schoolid'    => $schoolid,
                                        'macid'       => $ckmac['id'],
                                        'cardid'      => $v ['signId'],
                                        'sid'         => $ckuser['sid'],
                                        'bj_id'       => $bj['bj_id'],
                                        'type'        => $type,
                                        'pic'         => $pic,
                                        'pic2'        => $pic2,
                                        'lon'         => $v['lon'],
                                        'lat'         => $v['lat'],
                                        'temperature' => $v ['signTemp'],
                                        'leixing'     => $leixing,
                                        'pard'        => $ckuser['pard'],
                                        'createtime'  => $signTime
                                    );
                                }
                                /*$data = array(
                                'weid' => $weid,
                                'schoolid' => $schoolid,
                                'macid' => $ckmac['id'],
                                'cardid' => $v ['signId'],
                                'sid' => $ckuser['sid'],
                                'bj_id' => $bj['bj_id'],
                                'type' => $type,
                                'pic' => $pic,
                                'pic2' => $pic2,
                                'sc_ap' => 7,
                                'lon' => $v['lon'],
                                'lat' => $v['lat'],
                                'temperature' => $v ['signTemp'],
                                'leixing' => $leixing,
                                'pard' => $ckuser['pard'],
                                'createtime' => $signTime
                                );*/
                                pdo_insert($this->table_checklog, $data);
                                $checkid = pdo_insertid();
                                if ($school['send_overtime'] >= 1) {
                                    $overtime = $school['send_overtime'] * 60;
                                    $timecha  = $times - $signTime;
                                    if ($overtime >= $timecha) {
                                        if (is_showyl()) {
                                            $this->sendMobileJxlxtz_yl($schoolid, $weid, $ckuser['sid'], $checkid, $ckmac['id']);
                                        } else {
                                            $this->sendMobileJxlxtz($schoolid, $weid, $bj['bj_id'], $ckuser['sid'], $type, $leixing, $checkid, $ckuser['pard']);
                                        }
                                    } else {
                                        $result['info'] = "延迟发送之数据将不推送刷卡提示";
                                    }
                                } else {
                                    if (is_showyl()) {
                                        $this->sendMobileJxlxtz_yl($schoolid, $weid, $ckuser['sid'], $checkid, $ckmac['id']);
                                    } else {
                                        $this->sendMobileJxlxtz($schoolid, $weid, $bj['bj_id'], $ckuser['sid'], $type, $leixing, $checkid, $ckuser['pard']);
                                    }
                                }
                                $fstype = true;
                            }
                        }
                        if (!empty($ckuser['tid'])) {
                            $data = array(
                                'weid'       => $weid,
                                'schoolid'   => $schoolid,
                                'macid'      => $ckmac['id'],
                                'cardid'     => $v ['signId'],
                                'tid'        => $ckuser['tid'],
                                'type'       => $type,
                                'leixing'    => $leixing,
                                'pic'        => $pic,
                                'pic2'       => $pic2,
                                'pard'       => 1,
                                'createtime' => $signTime
                            );
                            pdo_insert($this->table_checklog, $data);
                            $fstype = true;
                        }
                    }elseif($ckuser['cardtype'] ==2){

                        //班级卡处理
                        $bj_id = $ckuser['bj_id'];
                        $ThisCardStudents = pdo_fetchall("SELECT id FROM " . tablename($this->table_students) . " WHERE bj_id = :bj_id and schoolid = :schoolid", array(':bj_id' =>$bj_id,':schoolid'=>$schoolid));
                        foreach ($ThisCardStudents as $key=>$value){
                            $data = array(
                                'weid' => $weid,
                                'schoolid' => $schoolid,
                                'macid' => $ckmac['id'],
                                'cardid' => $_GPC['signId'],
                                'sid' => $value['id'],
                                'bj_id' => $bj_id,
                                'type' => $type,
                                'pic' => $pic,
                                'pic2' => $pic2,
                                'lon' => $_GPC['lon'],
                                'lat' => $_GPC['lat'],
                                'temperature' => $_GPC['signTemp'],
                                'leixing' => $leixing,
                                'pard' => $ckuser['pard'],
                                'createtime' => $signTime
                            );
                            pdo_insert($this->table_checklog, $data);
                            $checkid = pdo_insertid();
                            if($school['send_overtime'] >= 1){
                                $overtime = $school['send_overtime']*60;
                                $timecha = $times - $signTime;
                                if($overtime >= $timecha){
                                    $this->sendMobileJxlxtz($schoolid, $weid, $bj_id, $value['id'], $type, $leixing, $checkid, $ckuser['pard']);
                                }else{
                                    $result['info'] = "延迟发送之数据将不推送刷卡提示";
                                }
                            }else{
                                $this->sendMobileJxlxtz($schoolid, $weid,$bj_id,$value['id'], $type, $leixing, $checkid, $ckuser['pard']);
                            }
                        }
                        $fstype = true;
                    }
                }else{
		 $fstype = true;
                    $result['info'] = "本卡未绑定任何学生或老师";
                }
            }else{
                $fstype = true;
                $result['info'] = "不可重复相同刷卡数据";
            }
            if ($fstype ==true){
                $result['data'] = "";
                $result['code'] = 1000;
                $result['msg'] = "success";
                $result['ServerTime'] = date('Y-m-d H:i:s',time());
                echo json_encode($result);
                exit;
            }else{
                $result['data'] = "";
                $result['code'] = 300;
                $result['msg'] = "lose";
                $result['ServerTime'] = date('Y-m-d H:i:s',time());
                echo json_encode($result);
                exit;
            }
        }
    }
}

if ($operation == 'checknew') { //专用无障碍通道
    $signData = str_replace('&quot;','"',$_GPC['signData']);
    $datasss = json_decode($signData,true);
    if(!empty($datasss)){
        foreach($datasss as $k => $v){
            $fstype = false;
            $ckuser = pdo_fetch("SELECT * FROM " . tablename($this->table_idcard) . " WHERE idcard = :idcard And schoolid = :schoolid ", array(':idcard' =>$v['signId'],':schoolid' =>$schoolid));
            $signTime = strtotime($v['signTime']);
            $signMode = $v['signMode'];
            $checkthisdata = pdo_fetch("SELECT * FROM " . tablename($this->table_checklog) . " WHERE cardid = :cardid And schoolid = :schoolid And createtime = :createtime ", array(':cardid' =>$v['signId'],':schoolid' =>$schoolid,':createtime' =>$signTime));
            if(empty($checkthisdata)){
                if(!empty($ckuser) || $v['signId'] == '9999999999'){
                    $times = TIMESTAMP;
                    $nowtime = date('H:i',$signTime);
                    if($v['kq_img']) {
                        $pic = $v['kq_img'];
                    }
                    if($v['kq_img_2']) {
                        $pic2 = $v['kq_img_2'];
                    }
                    if($ckmac['type'] !=0){
                        include 'checktime2.php';
                    }else{
                        include 'checktime.php';
                    }
                    if($v['signId'] == '9999999999'){
                        $data = array(
                            'weid' => $weid,
                            'schoolid' => $schoolid,
                            'macid' => $ckmac['id'],
                            'lon' => $v['lon'],
                            'lat' => $v['lat'],
                            'cardid' => $v ['signId'],
                            'type' => "无卡进出",
                            'pic' => $pic,
                            'pic2' => $pic2,
                            'leixing' => $leixing,
                            'createtime' => $signTime
                        );
                        pdo_insert($this->table_checklog, $data);
                        $fstype = true;
                    }

                    if($ckuser['cardtype'] == 1) {


                        if (!empty($ckuser['sid'])) {
                            $bj = pdo_fetch("SELECT bj_id,roomid FROM " . tablename($this->table_students) . " WHERE id = :id ", array(':id' => $ckuser['sid']));
                            if ($school['is_cardpay'] == 1) {
                                if ($ckuser['severend'] > $times) {


                                    if (!empty($ckmac['apid'])) {
                                        if (!empty($bj['roomid'])) {
                                            $this_roomid = $bj['roomid'];
                                            $this_apid   = $ckmac['apid'];
                                        } else {
                                            $this_roomid = 0;
                                            $this_apid   = 0;
                                        }
                                        if ($leixing == 1) {
                                            $ap_type = 1;
                                        } elseif ($leixing == 2) {
                                            $ap_type = 2;
                                        } else {
                                            $ap_type = 0;
                                        }
                                        $data = array(
                                            'weid'       => $weid,
                                            'schoolid'   => $schoolid,
                                            'macid'      => $ckmac['id'],
                                            'cardid'     => $v['signId'],
                                            'sid'        => $ckuser['sid'],
                                            'bj_id'      => $bj['bj_id'],
                                            'lon'        => $v['lon'],
                                            'lat'        => $v['lat'],
                                            'pic'        => $pic,
                                            'pic2'       => $pic2,
                                            'sc_ap'      => 1,
                                            'ap_type'    => $ap_type,
                                            'roomid'     => $this_roomid,
                                            'apid'       => $this_apid,
                                            'createtime' => $signTime
                                        );

                                    } else {
                                        $data = array(
                                            'weid'        => $weid,
                                            'schoolid'    => $schoolid,
                                            'macid'       => $ckmac['id'],
                                            'cardid'      => $v ['signId'],
                                            'sid'         => $ckuser['sid'],
                                            'bj_id'       => $bj['bj_id'],
                                            'type'        => $type,
                                            'pic'         => $pic,
                                            'pic2'        => $pic2,
                                            'lon'         => $v['lon'],
                                            'lat'         => $v['lat'],
                                            'temperature' => $v ['signTemp'],
                                            'leixing'     => $leixing,
                                            'pard'        => $ckuser['pard'],
                                            'createtime'  => $signTime
                                        );
                                    }


                                    /*$data = array(
                                    'weid' => $weid,
                                    'schoolid' => $schoolid,
                                    'macid' => $ckmac['id'],
                                    'cardid' => $v ['signId'],
                                    'sid' => $ckuser['sid'],
                                    'bj_id' => $bj['bj_id'],
                                    'type' => $type,
                                    'pic' => $pic,
                                    'pic2' => $pic2,
                                    'sc_ap' => 8,
                                    'lon' => $v['lon'],
                                    'lat' => $v['lat'],
                                    'temperature' => $v ['signTemp'],
                                    'leixing' => $leixing,
                                    'pard' => $ckuser['pard'],
                                    'createtime' => $signTime
                                    );*/
                                    pdo_insert($this->table_checklog, $data);
                                    $checkid = pdo_insertid();
                                    if ($school['send_overtime'] >= 1) {
                                        $overtime = $school['send_overtime'] * 60;
                                        $timecha  = $times - $signTime;
                                        if ($overtime >= $timecha) {
                                            if (is_showyl()) {
                                                $this->sendMobileJxlxtz_yl($schoolid, $weid, $ckuser['sid'], $checkid, $ckmac['id']);
                                            } else {
                                                $this->sendMobileJxlxtz($schoolid, $weid, $bj['bj_id'], $ckuser['sid'], $type, $leixing, $checkid, $ckuser['pard']);
                                            }
                                        } else {
                                            $result['info'] = "延迟发送之数据将不推送刷卡提示";
                                        }
                                    } else {
                                        if (is_showyl()) {
                                            $this->sendMobileJxlxtz_yl($schoolid, $weid, $ckuser['sid'], $checkid, $ckmac['id']);
                                        } else {
                                            $this->sendMobileJxlxtz($schoolid, $weid, $bj['bj_id'], $ckuser['sid'], $type, $leixing, $checkid, $ckuser['pard']);
                                        }
                                    }
                                } else {
                                    $result['info'] = "本卡已失效,请联系学校管理员";
                                }
                                $fstype = true;
                            } else {


                                if (!empty($ckmac['apid'])) {
                                    if (!empty($bj['roomid'])) {
                                        $this_roomid = $bj['roomid'];
                                        $this_apid   = $ckmac['apid'];
                                    } else {
                                        $this_roomid = 0;
                                        $this_apid   = 0;
                                    }
                                    if ($leixing == 1) {
                                        $ap_type = 1;
                                    } elseif ($leixing == 2) {
                                        $ap_type = 2;
                                    } else {
                                        $ap_type = 0;
                                    }
                                    $data = array(
                                        'weid'       => $weid,
                                        'schoolid'   => $schoolid,
                                        'macid'      => $ckmac['id'],
                                        'cardid'     => $v['signId'],
                                        'sid'        => $ckuser['sid'],
                                        'bj_id'      => $bj['bj_id'],
                                        'lon'        => $v['lon'],
                                        'lat'        => $v['lat'],
                                        'pic'        => $pic,
                                        'pic2'       => $pic2,
                                        'sc_ap'      => 1,
                                        'ap_type'    => $ap_type,
                                        'roomid'     => $this_roomid,
                                        'apid'       => $this_apid,
                                        'createtime' => $signTime
                                    );

                                } else {
                                    $data = array(
                                        'weid'        => $weid,
                                        'schoolid'    => $schoolid,
                                        'macid'       => $ckmac['id'],
                                        'cardid'      => $v ['signId'],
                                        'sid'         => $ckuser['sid'],
                                        'bj_id'       => $bj['bj_id'],
                                        'type'        => $type,
                                        'pic'         => $pic,
                                        'pic2'        => $pic2,
                                        'lon'         => $v['lon'],
                                        'lat'         => $v['lat'],
                                        'temperature' => $v ['signTemp'],
                                        'leixing'     => $leixing,
                                        'pard'        => $ckuser['pard'],
                                        'createtime'  => $signTime
                                    );
                                }


                                /*$data = array(
                                'weid' => $weid,
                                'schoolid' => $schoolid,
                                'macid' => $ckmac['id'],
                                'cardid' => $v ['signId'],
                                'sid' => $ckuser['sid'],
                                'bj_id' => $bj['bj_id'],
                                'type' => $type,
                                'pic' => $pic,
                                'pic2' => $pic2,
                                'sc_ap' => 9,
                                'lon' => $v['lon'],
                                'lat' => $v['lat'],
                                'temperature' => $v ['signTemp'],
                                'leixing' => $leixing,
                                'pard' => $ckuser['pard'],
                                'createtime' => $signTime
                                );*/
                                pdo_insert($this->table_checklog, $data);
                                $checkid = pdo_insertid();
                                if ($school['send_overtime'] >= 1) {
                                    $overtime = $school['send_overtime'] * 60;
                                    $timecha  = $times - $signTime;
                                    if ($overtime >= $timecha) {
                                        if (is_showyl()) {
                                            $this->sendMobileJxlxtz_yl($schoolid, $weid, $ckuser['sid'], $checkid, $ckmac['id']);
                                        } else {
                                            $this->sendMobileJxlxtz($schoolid, $weid, $bj['bj_id'], $ckuser['sid'], $type, $leixing, $checkid, $ckuser['pard']);
                                        }
                                    } else {
                                        $result['info'] = "延迟发送之数据将不推送刷卡提示";
                                    }
                                } else {
                                    if (is_showyl()) {
                                        $this->sendMobileJxlxtz_yl($schoolid, $weid, $ckuser['sid'], $checkid, $ckmac['id']);
                                    } else {
                                        $this->sendMobileJxlxtz($schoolid, $weid, $bj['bj_id'], $ckuser['sid'], $type, $leixing, $checkid, $ckuser['pard']);
                                    }
                                }
                                $fstype = true;
                            }
                        }
                        if (!empty($ckuser['tid'])) {
                            $data = array(
                                'weid'       => $weid,
                                'schoolid'   => $schoolid,
                                'macid'      => $ckmac['id'],
                                'cardid'     => $v ['signId'],
                                'tid'        => $ckuser['tid'],
                                'type'       => $type,
                                'leixing'    => $leixing,
                                'pic'        => $pic,
                                'pic2'       => $pic2,
                                'pard'       => 1,
                                'createtime' => $signTime
                            );
                            pdo_insert($this->table_checklog, $data);
                            $fstype = true;
                        }
                    }elseif($ckuser['cardtype'] == 2){

                        //班级卡处理
                        $bj_id = $ckuser['bj_id'];
                        $ThisCardStudents = pdo_fetchall("SELECT id FROM " . tablename($this->table_students) . " WHERE bj_id = :bj_id and schoolid = :schoolid", array(':bj_id' =>$bj_id,':schoolid'=>$schoolid));
                        foreach ($ThisCardStudents as $key=>$value){
                            $data = array(
                                'weid' => $weid,
                                'schoolid' => $schoolid,
                                'macid' => $ckmac['id'],
                                'cardid' => $_GPC['signId'],
                                'sid' => $value['id'],
                                'bj_id' => $bj_id,
                                'type' => $type,
                                'pic' => $pic,
                                'pic2' => $pic2,
                                'lon' => $_GPC['lon'],
                                'lat' => $_GPC['lat'],
                                'temperature' => $_GPC['signTemp'],
                                'leixing' => $leixing,
                                'pard' => $ckuser['pard'],
                                'createtime' => $signTime
                            );
                            pdo_insert($this->table_checklog, $data);
                            $checkid = pdo_insertid();
                            if($school['send_overtime'] >= 1){
                                $overtime = $school['send_overtime']*60;
                                $timecha = $times - $signTime;
                                if($overtime >= $timecha){
                                    $this->sendMobileJxlxtz($schoolid, $weid, $bj_id, $value['id'], $type, $leixing, $checkid, $ckuser['pard']);
                                }else{
                                    $result['info'] = "延迟发送之数据将不推送刷卡提示";
                                }
                            }else{
                                $this->sendMobileJxlxtz($schoolid, $weid,$bj_id,$value['id'], $type, $leixing, $checkid, $ckuser['pard']);
                            }
                        }
                        $fstype = true;
                    }
                }else{
                    $result['info'] = "本卡未绑定任何学生或老师";
                }
            }else{
                $fstype = true;
                $result['info'] = "不可重复相同刷卡数据";
            }
            if ($fstype ==true){
                $result['data'] = "";
                $result['code'] = 1000;
                $result['msg'] = "success";
                $result['ServerTime'] = date('Y-m-d H:i:s',time());
                echo json_encode($result);
                exit;
            }else{
                $result['data'] = "";
                $result['code'] = 300;
                $result['msg'] = "lose";
                $result['ServerTime'] = date('Y-m-d H:i:s',time());
                echo json_encode($result);
                exit;
            }
        }
    }else{
        $result['data'] = "";
        $result['code'] = 300;
        $result['msg'] = "未接收到刷卡信息";
        $result['ServerTime'] = date('Y-m-d H:i:s',time());
        echo json_encode($result);
        exit;
    }
}

if ($operation == 'gps') {
    $fstype = false;
    $ckuser = pdo_fetch("SELECT * FROM " . tablename($this->table_idcard) . " WHERE idcard = '{$_GPC['signId']}' And weid = '{$weid}' And schoolid = '{$schoolid}' ");
    $bj = pdo_fetch("SELECT bj_id FROM " . tablename($this->table_students) . " WHERE id = '{$ckuser['sid']}' ");
    $checkthisdata = pdo_fetch("SELECT * FROM " . tablename($this->table_checklog) . " WHERE cardid = '{$_GPC['signId']}' And createtime = '{$_GPC['signTime']}' And schoolid = '{$schoolid}' ");
    if(empty($checkthisdata)){
        if(!empty($ckuser)){
            $times = TIMESTAMP;
            $nowtime = date('H:i',$times);
            if($ckmac['type'] !=0){
                include 'checktime2.php';
            }else{
                include 'checktime.php';
            }
            $signTime = trim($_GPC['signTime']);

            if($ckuser['cardtype'] == 1) {


                if (!empty($ckuser['sid'])) {
                    if ($school['is_cardpay'] == 1) {
                        if ($ckuser['severend'] > $times) {
                            $data = array(
                                'weid'        => $weid,
                                'schoolid'    => $schoolid,
                                'macid'       => $ckmac['id'],
                                'cardid'      => $_GPC ['signId'],
                                'sid'         => $ckuser['sid'],
                                'bj_id'       => $bj['bj_id'],
                                'type'        => $type,
                                'temperature' => $_GPC ['signTemp'],
                                'leixing'     => $leixing,
                                'pard'        => $ckuser['pard'],
                                'lon'         => $_GPC['lon'],
                                'lat'         => $_GPC['lat'],
                                'createtime'  => $signTime
                            );
                            pdo_insert($this->table_checklog, $data);
                            $checkid = pdo_insertid();
                            if ($school['send_overtime'] >= 1) {
                                $overtime = $school['send_overtime'] * 60;
                                $timecha  = $times - $signTime;
                                if ($overtime >= $timecha) {
                                    if (is_showyl()) {
                                        $this->sendMobileJxlxtz_yl($schoolid, $weid, $ckuser['sid'], $checkid, $ckmac['id']);
                                    } else {
                                        $this->sendMobileJxlxtz($schoolid, $weid, $bj['bj_id'], $ckuser['sid'], $type, $leixing, $checkid, $ckuser['pard']);
                                    }
                                } else {
                                    $result['info'] = "延迟发送之数据将不推送刷卡提示";
                                }
                            } else {
                                if (is_showyl()) {
                                    $this->sendMobileJxlxtz_yl($schoolid, $weid, $ckuser['sid'], $checkid, $ckmac['id']);
                                } else {
                                    $this->sendMobileJxlxtz($schoolid, $weid, $bj['bj_id'], $ckuser['sid'], $type, $leixing, $checkid, $ckuser['pard']);
                                }
                            }
                            $fstype = true;
                        }
                    } else {
                        $data = array(
                            'weid'        => $weid,
                            'schoolid'    => $schoolid,
                            'macid'       => $ckmac['id'],
                            'cardid'      => $_GPC ['signId'],
                            'sid'         => $ckuser['sid'],
                            'bj_id'       => $bj['bj_id'],
                            'type'        => $type,
                            'temperature' => $_GPC ['signTemp'],
                            'leixing'     => $leixing,
                            'lon'         => $_GPC['lon'],
                            'lat'         => $_GPC['lat'],
                            'pard'        => $ckuser['pard'],
                            'createtime'  => $signTime
                        );
                        pdo_insert($this->table_checklog, $data);
                        $checkid = pdo_insertid();
                        if ($school['send_overtime'] >= 1) {
                            $overtime = $school['send_overtime'] * 60;
                            $timecha  = $times - $signTime;
                            if ($overtime >= $timecha) {
                                if (is_showyl()) {
                                    $this->sendMobileJxlxtz_yl($schoolid, $weid, $ckuser['sid'], $checkid, $ckmac['id']);
                                } else {
                                    $this->sendMobileJxlxtz($schoolid, $weid, $bj['bj_id'], $ckuser['sid'], $type, $leixing, $checkid, $ckuser['pard']);
                                }
                            } else {
                                $result['info'] = "延迟发送之数据将不推送刷卡提示";
                            }
                        } else {
                            if (is_showyl()) {
                                $this->sendMobileJxlxtz_yl($schoolid, $weid, $ckuser['sid'], $checkid, $ckmac['id']);
                            } else {
                                $this->sendMobileJxlxtz($schoolid, $weid, $bj['bj_id'], $ckuser['sid'], $type, $leixing, $checkid, $ckuser['pard']);
                            }
                        }
                        $fstype = true;
                    }
                }
                if (!empty($ckuser['tid'])) {
                    $data = array(
                        'weid'       => $weid,
                        'schoolid'   => $schoolid,
                        'macid'      => $ckmac['id'],
                        'cardid'     => $_GPC ['signId'],
                        'tid'        => $ckuser['tid'],
                        'type'       => $type,
                        'leixing'    => $leixing,
                        'pard'       => 1,
                        'lon'        => $_GPC['lon'],
                        'lat'        => $_GPC['lat'],
                        'createtime' => $signTime
                    );
                    pdo_insert($this->table_checklog, $data);
                    $fstype = true;
                }
            }elseif($ckuser['cardtype'] == 2){

                //班级卡处理
                $bj_id = $ckuser['bj_id'];
                $ThisCardStudents = pdo_fetchall("SELECT id FROM " . tablename($this->table_students) . " WHERE bj_id = :bj_id and schoolid = :schoolid", array(':bj_id' =>$bj_id,':schoolid'=>$schoolid));
                foreach ($ThisCardStudents as $key=>$value){
                    $data = array(
                        'weid' => $weid,
                        'schoolid' => $schoolid,
                        'macid' => $ckmac['id'],
                        'cardid' => $_GPC['signId'],
                        'sid' => $value['id'],
                        'bj_id' => $bj_id,
                        'type' => $type,
                        'lon' => $_GPC['lon'],
                        'lat' => $_GPC['lat'],
                        'leixing' => $leixing,
                        'pard' => $ckuser['pard'],
                        'createtime' => $signTime
                    );
                    pdo_insert($this->table_checklog, $data);
                    $checkid = pdo_insertid();
                    if($school['send_overtime'] >= 1){
                        $overtime = $school['send_overtime']*60;
                        $timecha = $times - $signTime;
                        if($overtime >= $timecha){
                            $this->sendMobileJxlxtz($schoolid, $weid, $bj_id, $value['id'], $type, $leixing, $checkid, $ckuser['pard']);
                        }else{
                            $result['info'] = "延迟发送之数据将不推送刷卡提示";
                        }
                    }else{
                        $this->sendMobileJxlxtz($schoolid, $weid,$bj_id,$value['id'], $type, $leixing, $checkid, $ckuser['pard']);
                    }
                }
                $fstype = true;
            }
        }
    }
    if ($fstype !=false){
        $result['data'] = "";
        $result['code'] = 1000;
        $result['msg'] = "success";
        $result['ServerTime'] = date('Y-m-d H:i:s',time());
        echo json_encode($result);
        exit;
        //print_r($signData);
    }else{
        $result['data'] = "";
        $result['code'] = 300;
        $result['msg'] = "lose";
        $result['ServerTime'] = date('Y-m-d H:i:s',time());
        echo json_encode($result);
        exit;
        //print_r($signData);
    }
}

if ($operation == 'banner') {
    $banner = unserialize($ckmac['banner']);
    $ims = $urls.$banner['pic1'].'#'.$urls.$banner['pic2'].'#'.$urls.$banner['pic3'].'#'.$urls.$banner['pic4'];
    $result['data'] = array(
        'img' => $ims,
        'mc' => $banner['pop']
    );
    $result['code'] = 1000;
    $result['msg'] = "success";
    $result['ServerTime'] = date('Y-m-d H:i:s',time());
    $temp = array(
        'isflow' => 2,
        'pop' => $banner['pop'],
        'video' => $banner['video'],
        'pic1' => $banner['pic1'],
        'pic1' => $banner['pic1'],
        'pic2' => $banner['pic2'],
        'pic3' => $banner['pic3'],
        'pic4' => $banner['pic4'],
        'VOICEPRE' => $banner['VOICEPRE'],
    );
    $temp1['banner'] = serialize($temp);
    pdo_update($this->table_checkmac, $temp1, array('id' => $ckmac['id']));
    echo json_encode($result);
    exit;
}

if ($operation == 'video') {
    $banner = unserialize($ckmac['banner']);
    $result['data'] = array(
        'videoId' => 2,
        'videoUrl' => $banner['video']
    );
    $result['code'] = 1000;
    $result['msg'] = "success";
    $result['ServerTime'] = date('Y-m-d H:i:s',time());
    $temp = array(
        'isflow' => 2,
        'pop' => $banner['pop'],
        'video' => $banner['video'],
        'pic1' => $banner['pic1'],
        'pic1' => $banner['pic1'],
        'pic2' => $banner['pic2'],
        'pic3' => $banner['pic3'],
        'pic4' => $banner['pic4'],
        'VOICEPRE' => $banner['VOICEPRE'],
    );
    $temp1['banner'] = serialize($temp);
    pdo_update($this->table_checkmac, $temp1, array('id' => $ckmac['id']));
    echo json_encode($result);
    exit;
}

if ($operation == 'getleave') {
    $time = $_GPC['signtime'];
    $ckuser        = pdo_fetch("SELECT sid FROM " . tablename($this->table_idcard) . " WHERE idcard = '{$_GPC['iccode']}' And weid = '{$weid}' And schoolid = '{$schoolid}' ");
    $leave        =  pdo_fetch("SELECT sid,startime1,endtime1 FROM " . tablename($this->table_leave) . " WHERE weid = '{$weid}'  And schoolid = '{$schoolid}' and isliuyan = 0 and status = 1 and startime1 <= '{$time}' and endtime1 >= '{$time}' and sid = '{$ckuser['sid']}' ");
    $result['code'] = 1000;
    $result['msg']    = "success";
    if(!empty($leave)){
        $result['data']['openDoor']   = 0;
    }else{
        $result['data']['openDoor']   = 1;
    }

    echo json_encode($result);
    exit;
}


if ($operation == 'onlineflow'){
    $lastId = $_GPC['lastOnlineFlowRecordId'];
    $flowlist = pdo_fetchall("SELECT id,sid,yue_type,cost,cost_type FROM " . tablename($this->table_yuecostlog) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' and id >'{$lastId}' and on_offline = 1 ORDER BY ID ASC");
    $result['code'] = 1000;
    $result['msg']    = "success";
    if(!empty($flowlist)){
        $data['flowlist']   =$flowlist;
    }else{
        $data['flowlist']   = array();
    }

    include '3DES.php';
    $plaintext = json_encode($data);
    $key_3DES = "r0uScmDuH5FLO37AJV2FN72J";// 加密所需的密钥
    $iv_3DES = "1eX24DCe";// 初始化向量
    $ciphertext = TDEA::encrypt($plaintext, $key_3DES, $iv_3DES);//加密
    $result['data'] = $ciphertext;
    echo json_encode($result);
    exit;
}

if ($operation == 'offlineflow'){
    $time = time();
    include '3DES.php';
    $key_3DES = "r0uScmDuH5FLO37AJV2FN72J";// 加密所需的密钥
    $iv_3DES = "1eX24DCe";// 初始化向量

    $data_h = htmlspecialchars_decode($_GPC['data']);
    $plaintext2 = TDEA::decrypt($data_h, $key_3DES, $iv_3DES);//解密
    $data = json_decode($plaintext2,true);
    foreach($data as $row){
        $sid = $row['userno'];
        $cardid = $row['cardid'];
        $thisMacId = $row['macid'];
        $cost = $row['paymoney'];
        $payTime = intval($row['paytime']/1000);
        $payKind = $row['paykind'];
        $flowid = $row['fid'];
        $studentyue = pdo_fetch("SELECT id,chongzhi FROM " . tablename($this->table_students) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' and id = '{$sid}' ");
        if(!empty($studentyue)){
            if($payKind == 15 || $payKind == 16){
                $nowyue = $studentyue['chongzhi'];
                if($school['is_buzhu'] != 0){

                    $studentbuzhu = pdo_fetch("SELECT id,now_yue FROM " . tablename($this->table_buzhulog) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' and sid = '{$sid}' and starttime<='{$payTime}' and endtime >='{$payTime}' ");
                    $nowbuzhu = $studentbuzhu['now_yue']?$studentbuzhu['now_yue']:0;
                    $allyue = $nowbuzhu + $nowyue;
                    //补助
                    if($cost <= $nowbuzhu){
                        $this_this = 1;
                        $after_buzhu = $nowbuzhu - $cost ;
                        $buzhu_data = array(
                            'now_yue' => $after_buzhu,
                        );
                        pdo_update($this->table_buzhulog,$buzhu_data,array('id'=>$studentbuzhu['id']));
                        //消费记录
                        $yuelog = array(
                            'schoolid' 		=> $schoolid,
                            'weid'	   		=> $weid,
                            'sid'	   		=> $sid,
                            'yue_type' 		=> 1,
                            'cost' 	   		=> $cost,
                            'costtime' 		=> $payTime,
                            'cost_type'		=> 2,
                            'macid'			=> $thisMacId,
                            'on_offline' 	=> 2,
                            'createtime' => time(),
                        );
                        pdo_insert($this->table_yuecostlog,$yuelog);
                        //补助和余额
                    }elseif($cost>$nowbuzhu && $nowbuzhu != 0  && $allyue >= $cost){
                        $this_this = 2;
                        $after_buzhu = 0 ;
                        $cost_yue = $cost - $nowbuzhu ;
                        $after_yue = $nowyue - $cost_yue;
                        $buzhu_data = array(
                            'now_yue' => $after_buzhu,
                        );
                        pdo_update($this->table_buzhulog,$buzhu_data,array('id'=>$studentbuzhu['id']));
                        //消费记录
                        $yuelog = array(
                            'schoolid' 		=> $schoolid,
                            'weid'	   		=> $weid,
                            'sid'	   		=> $sid,
                            'yue_type' 		=> 1,
                            'cost' 	   		=> $nowbuzhu,
                            'costtime' 		=> $payTime,
                            'cost_type'		=> 2,
                            'macid'			=> $thisMacId,
                            'on_offline' 	=> 2,
                            'createtime' => time(),
                        );
                        pdo_insert($this->table_yuecostlog,$yuelog);
                        $yue_data = array(
                            'chongzhi' =>$after_yue,
                        );
                        pdo_update($this->table_students,$yue_data,array('id'=>$studentyue['id']));
                        //消费记录
                        $yuelog_yue = array(
                            'schoolid' 		=> $schoolid,
                            'weid'	   		=> $weid,
                            'sid'	   		=> $sid,
                            'yue_type' 		=> 2,
                            'cost' 	   		=> $cost_yue,
                            'costtime' 		=> $payTime,
                            'cost_type'		=> 2,
                            'macid'			=> $thisMacId,
                            'on_offline' 	=> 2,
                            'createtime' => time(),
                        );
                        pdo_insert($this->table_yuecostlog,$yuelog_yue);
                        //余额
                    }elseif($nowbuzhu == 0 && $cost <= $nowyue){
                        $this_this = 3;
                        $after_yue = $nowyue - $cost;
                        $yue_data = array(
                            'chongzhi' =>$after_yue,
                        );
                        pdo_update($this->table_students,$yue_data,array('id'=>$studentyue['id']));
                        //消费记录
                        $yuelog_yue = array(
                            'schoolid' 		=> $schoolid,
                            'weid'	   		=> $weid,
                            'sid'	   		=> $sid,
                            'yue_type' 		=> 2,
                            'cost' 	   		=> $cost,
                            'costtime' 		=> $payTime,
                            'cost_type'		=> 2,
                            'macid'			=> $thisMacId,
                            'on_offline' 	=> 2,
                            'createtime' => time(),
                        );
                        pdo_insert($this->table_yuecostlog,$yuelog_yue);
                    }
                }else{
                    $this_this = 3;
                    $after_yue = $nowyue - $cost;
                    $yue_data = array(
                        'chongzhi' =>$after_yue,
                    );
                    pdo_update($this->table_students,$yue_data,array('id'=>$studentyue['id']));
                    //消费记录
                    $yuelog_yue = array(
                        'schoolid' 		=> $schoolid,
                        'weid'	   		=> $weid,
                        'sid'	   		=> $sid,
                        'yue_type' 		=> 2,
                        'cost' 	   		=> $cost,
                        'costtime' 		=> $payTime,
                        'cost_type'		=> 2,
                        'macid'			=> $thisMacId,
                        'on_offline' 	=> 2,
                        'createtime' => time(),
                    );
                    pdo_insert($this->table_yuecostlog,$yuelog_yue);
                }
                $this->sendMobileOfflinexf($sid,$cost,$thisMacId,$payTime,$schoolid,$weid,1);
            }
        }
    }
    $result['code'] = 1000;
    $result['msg']    = "success";

    echo json_encode($result);
    exit;
}

if ($operation == 'cardinfo'){
    //$data = json_decode($_GPC['data']);
    $lastMaxId = $_GPC['id'];
    $studentlist = pdo_fetchall("SELECT id,s_name,chongzhi,bj_id  FROM " . tablename($this->table_students) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' and id >'{$lastMaxId}' LIMIT 0,200 ");
    $nowtimestart = strtotime(date("Y-m-d",time()));
    $nowtimeend = $nowtimestart + 86399;
    $studentinfo = array();
    foreach($studentlist as $key=>$value){
        $cardinfo =  pdo_fetch("SELECT id,idcard,severend FROM " . tablename($this->table_idcard) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' and sid ='{$value['id']}' and pard = 1  ");
        if($school['is_buzhu'] != 0){
            $student_buzhu =  pdo_fetch("SELECT id,now_yue,starttime,endtime FROM " . tablename($this->table_buzhulog) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' and sid = '{$value['id']}' and starttime<='{$nowtimestart}' and endtime >='{$nowtimeend}' ");
            $studentinfo[$key]['classid'] =  $value['bj_id']; //学生班级id
            $studentinfo[$key]['userName'] = $value['s_name']; //学生姓名
            $studentinfo[$key]['UserNO'] = $value['id']; //学生id
            $studentinfo[$key]['allMoney'] = $value['chongzhi'] + $student_buzhu['now_yue']; //总余额
            $studentinfo[$key]['Cash'] = $value['chongzhi'];   //现金余额
            $studentinfo[$key]['Subsidy'] = $student_buzhu['now_yue']; //补助余额
            $studentinfo[$key]['subsidyStart'] = $student_buzhu['starttime']*1000; //补助有效期开始时间
            $studentinfo[$key]['subsidyEnd'] = $student_buzhu['endtime']*1000; //补助有效期结束时间
            $studentinfo[$key]['cardID'] = $cardinfo['idcard']; //消费id卡号
            $studentinfo[$key]['limitDate'] = $cardinfo['severend']*1000; //有效期结束时间

        }else{
            $studentinfo[$key]['classid'] =  $value['bj_id']; //学生班级id
            $studentinfo[$key]['userName'] = $value['s_name']; //学生姓名
            $studentinfo[$key]['UserNO'] = $value['id']; //学生id
            $studentinfo[$key]['allMoney'] = $value['chongzhi']; //总余额
            $studentinfo[$key]['Cash'] = $value['chongzhi'];   //现金余额
            $studentinfo[$key]['Subsidy'] = 0; //补助余额
            $studentinfo[$key]['subsidyStart'] = 0; //补助有效期开始时间
            $studentinfo[$key]['subsidyEnd'] = 0; //补助有效期结束时间
            $studentinfo[$key]['cardID'] = $cardinfo['idcard']; //消费id卡号
            $studentinfo[$key]['limitDate'] = $cardinfo['severend']*1000; //有效期结束时间

        }

    }

    include '3DES.php';
    $plaintext = json_encode($studentinfo);
    $key_3DES = "r0uScmDuH5FLO37AJV2FN72J";// 加密所需的密钥
    $iv_3DES = "1eX24DCe";// 初始化向量
    $ciphertext = TDEA::encrypt($plaintext, $key_3DES, $iv_3DES);//加密

    $result['code'] = 1000;
    $result['msg']    = "success";
    $result['data'] = $ciphertext;
    echo json_encode($result);
    exit;
}


if ($operation == 'checkforks') {

    $signData = str_replace('&quot;','"',$_GPC['signData']);
    $datasss = json_decode($signData,true);

    if(!empty($datasss)){
        $up_file = $_FILES;
        foreach($datasss as $k => $v){
            if ($school['issale'] == 1) {
                $signTime = strtotime($v['signTime']);
                $classid   = $ckmac['js_id'];
                $nowtime   = time();
                $overtime = $nowtime - $signTime;
                $date      = date('Y-m-d', $signTime);
                $riqi      = explode('-', $date);
                $starttime = mktime(0, 0, 0, $riqi[1], $riqi[2], $riqi[0]);
                $endtime   = $starttime + 86399;
                $condition = " AND createtime > '{$starttime}' AND createtime < '{$endtime}'";
                $ckuser    = pdo_fetch("SELECT * FROM " . tablename($this->table_idcard) . " WHERE idcard = :idcard And schoolid = :schoolid and (sid !=:sid or tid != :tid) ", array(':idcard' => $v['signId'], ':schoolid' => $schoolid,':sid'=>0,':tid'=>0));
                if (!empty($ckuser['sid'])) {
                    $student = pdo_fetch("SELECT s_name FROM " . tablename($this->table_students) . " WHERE id='{$ckuser['sid']}' And schoolid = '{$schoolid}' ");
                    mload()->model('kc');
                    $nowkc_ks = Getnearks($classid, $starttime, $endtime);
                    $nowkc    = $nowkc_ks['nowkc'][0];//当前课程
                    $nowks    = $nowkc_ks['nowks'][0];//当前课时
                    if (!$nowkc_ks) {
                        $result['code'] = 1000;
                        if($overtime <= 60){
                            $result['msg']  = $student['s_name'].'当前无课时';
                        }
                        die(json_encode($result));
                    }
                    //已签到课时
                    $checkAll = pdo_fetchcolumn("select count(*) FROM " . tablename($this->table_kcsign) . " WHERE weid='{$weid}' And schoolid='{$schoolid}' And  kcid = '{$nowkc['id']}' And sid='{$ckuser['sid']}' AND status = 2 ");
                    //已购买课时
                    $buy     = pdo_fetch("select ksnum FROM " . tablename($this->table_coursebuy) . " WHERE weid='{$weid}' And schoolid='{$schoolid}' And  kcid = '{$nowkc['id']}' And sid='{$ckuser['sid']}'");
                    $checkks = pdo_fetch("select id FROM " . tablename($this->table_kcbiao) . " WHERE weid='{$weid}' And schoolid='{$schoolid}' And  id = '{$nowks['id']}'");
                    //课时不存在
                    if (empty($checkks)) {
                        $result['code'] = 1000;
                        if($overtime <= 60){
                            $result['msg']  = $student['s_name'].'当前无课时';
                        }
                        die(json_encode($result));
                    }
                    //当前学生课时已用完
                    if ($checkAll >= $buy['ksnum']) {
                        $result['code'] = 1000;
                        if($overtime <= 60) {
                            $result['msg'] = $student['s_name'].'无剩余可用课时';
                        }
                        die(json_encode($result));
                    }
                    //检查当前课时是否已经签到
                    $checkqd = pdo_fetch("select * FROM " . tablename($this->table_kcsign) . " WHERE weid='{$weid}' And schoolid='{$schoolid}' And  kcid = '{$nowkc['id']}' And ksid = '{$nowks['id']}' And sid='{$ckuser['sid']}' AND (status = 2 or status =1) ");
                    //重复签到
                    if (!empty($checkqd)) {
                        $result['code'] = 1000;
                        if($overtime <= 60) {
                            $result['msg'] = $student['s_name']."请勿重复签到！";
                        }
                        die(json_encode($result));

                    }
                    $kcname = pdo_fetch("select name FROM " . tablename($this->table_tcourse) . " WHERE weid='{$weid}' And schoolid='{$schoolid}' and id = '{$nowkc['id']}' ");

                    $data = array(
                        'kcid'       => $nowkc['id'],
                        'ksid'       => $nowks['id'],
                        'schoolid'   => $schoolid,
                        'weid'       => $weid,
                        'sid'        => $ckuser['sid'],
                        'createtime' => time(),
                        'status'     => 2,
                        'type'       => 0,
                        'kcname'     => $kcname['name']
                    );
                    pdo_insert($this->table_kcsign, $data);
                    $signid = pdo_insertid();
                    $this->sendMobileXsqrqdtz($signid, $schoolid, $weid);
                    $result['code'] = 1000;
                    if($overtime <= 60) {
                        $result['msg'] = $student['s_name']."扣课时成功";
                    }
                    die(json_encode($result));
                }elseif(!empty($ckuser['tid'])){
                    $teacher = pdo_fetch("SELECT tname FROM " . tablename($this->table_teachers) . " WHERE id='{$ckuser['tid']}' And schoolid = '{$schoolid}' ");
                    $result['code'] = 1000;
                    $result['msg'] = $teacher['tname'];
                    die(json_encode($result));
                }else{
                    $result['code'] = 1000;
                    $result['msg'] = "本卡未绑定";
                    die(json_encode($result));
                    exit();
                }
            }else{
                //若为公立模式则直接签到

                //第一次提交，保存考勤数据
                if(!empty($v['signId']) && !empty($v['photoName'])){
                    $fstype = false;
                    $ckuser = pdo_fetch("SELECT sid,tid,pard,severend  FROM " . tablename($this->table_idcard) . " WHERE idcard = :idcard And schoolid = :schoolid and (sid != :sid or tid != :tid)", array(':idcard' =>$v['signId'],':schoolid' =>$schoolid,':sid'=>0,':tid'=>0));

                    $signTime = strtotime($v['signTime']);
                    $checkthisdata = pdo_fetch("SELECT id FROM " . tablename($this->table_checklog) . " WHERE cardid = :cardid And schoolid = :schoolid And createtime = :createtime ", array(':cardid' =>$v['signId'],':schoolid' =>$schoolid,':createtime' =>$signTime));
                    $this_name = '';
                    if(empty($checkthisdata)){
                        if(!empty($ckuser) || $v['signId'] == '9999999999'){
                            $times = TIMESTAMP;
                            $nowtime = date('H:i',$signTime);
                            $pic = $v['photoName'];
                            $pic2 = '';
                            $signMode = $v['signMode'];
                            if($ckmac['type'] !=0){
                                include 'checktime2.php';
                            }else{
                                include 'checktime.php';
                            }
                            if($v['signId'] == '9999999999'){
                                $data = array(
                                    'weid' => $weid,
                                    'schoolid' => $schoolid,
                                    'macid' => $ckmac['id'],
                                    'lon' => $v['lon'],
                                    'lat' => $v['lat'],
                                    'cardid' => $v ['signId'],
                                    'type' => "无卡进出",
                                    'pic' => $pic,
                                    'pic2' => $pic2,
                                    'leixing' => $leixing,
                                    'createtime' => $signTime
                                );
                                pdo_insert($this->table_checklog, $data);
                                $fstype = true;
                            }
                            if(!empty($ckuser['sid'])){
                                $bj = pdo_fetch("SELECT bj_id,roomid FROM " . tablename($this->table_students) . " WHERE id = :id ", array(':id' =>$ckuser['sid']));
                                $student = pdo_fetch("SELECT s_name FROM " . tablename($this->table_students) . " WHERE id='{$ckuser['sid']}' And schoolid = '{$schoolid}' ");
                                $this_name = $student['s_name'];
                                if($school['is_cardpay'] == 1){
                                    if($ckuser['severend'] > $times){
                                        $data = array(
                                            'weid' => $weid,
                                            'schoolid' => $schoolid,
                                            'macid' => $ckmac['id'],
                                            'cardid' => $v ['signId'],
                                            'sid' => $ckuser['sid'],
                                            'bj_id' => $bj['bj_id'],
                                            'type' => $type,
                                            'pic' => $pic,
                                            'pic2' => $pic2,
                                            'lon' => $v['lon'],
                                            'lat' => $v['lat'],
                                            'temperature' => $v ['signTemp'],
                                            'leixing' => $leixing,
                                            'pard' => $ckuser['pard'],
                                            'createtime' => $signTime
                                        );
                                        pdo_insert($this->table_checklog, $data);
                                        $checkid = pdo_insertid();
                                        if($school['send_overtime'] >= 1){
                                            $overtime = $school['send_overtime']*60;
                                            $timecha = $times - $signTime;
                                            if($overtime >= $timecha){
                                                $this->sendMobileJxlxtz($schoolid, $weid, $bj['bj_id'], $ckuser['sid'], $type, $leixing, $checkid, $ckuser['pard']);
                                            }else{
                                                $result['info'] = "延迟发送之数据将不推送刷卡提示";
                                            }
                                        }else{
                                            $this->sendMobileJxlxtz($schoolid, $weid, $bj['bj_id'], $ckuser['sid'], $type, $leixing, $checkid, $ckuser['pard']);
                                        }
                                    }else{
                                        $result['info'] = "本卡已失效,请联系学校管理员";
                                    }
                                    $fstype = true;
                                }else{
                                    $data = array(
                                        'weid' => $weid,
                                        'schoolid' => $schoolid,
                                        'macid' => $ckmac['id'],
                                        'cardid' => $v ['signId'],
                                        'sid' => $ckuser['sid'],
                                        'bj_id' => $bj['bj_id'],
                                        'type' => $type,
                                        'pic' => $pic,
                                        'pic2' => $pic2,
                                        'lon' => $v['lon'],
                                        'lat' => $v['lat'],
                                        'temperature' => $v ['signTemp'],
                                        'leixing' => $leixing,
                                        'pard' => $ckuser['pard'],
                                        'createtime' => $signTime
                                    );
                                    pdo_insert($this->table_checklog, $data);
                                    $checkid = pdo_insertid();
                                    if($school['send_overtime'] >= 1){
                                        $overtime = $school['send_overtime']*60;
                                        $timecha = $times - $signTime;
                                        if($overtime >= $timecha){
                                            if(is_showyl()){
                                                $this->sendMobileJxlxtz_yl($schoolid, $weid, $ckuser['sid'], $checkid,$ckmac['id']);
                                            }else{
                                                $this->sendMobileJxlxtz($schoolid, $weid, $bj['bj_id'], $ckuser['sid'], $type, $leixing, $checkid, $ckuser['pard']);
                                            }
                                        }else{
                                            $result['info'] = "延迟发送之数据将不推送刷卡提示";
                                        }
                                    }else{
                                        if(is_showyl()){
                                            $this->sendMobileJxlxtz_yl($schoolid, $weid, $ckuser['sid'], $checkid,$ckmac['id']);
                                        }else{
                                            $this->sendMobileJxlxtz($schoolid, $weid, $bj['bj_id'], $ckuser['sid'], $type, $leixing, $checkid, $ckuser['pard']);
                                        }
                                    }
                                    $fstype = true;
                                }
                            }
                            if(!empty($ckuser['tid'])){
                                $teacher = pdo_fetch("SELECT tname FROM " . tablename($this->table_teachers) . " WHERE id='{$ckuser['tid']}' And schoolid = '{$schoolid}' ");
                                $this_name = $teacher['tname'];
                                $data = array(
                                    'weid' => $weid,
                                    'schoolid' => $schoolid,
                                    'macid' => $ckmac['id'],
                                    'cardid' => $v ['signId'],
                                    'tid' => $ckuser['tid'],
                                    'type' => $type,
                                    'leixing' => $leixing,
                                    'pic' => $pic,
                                    'pic2' => $pic2,
                                    'pard' => 1,
                                    'createtime' => $signTime
                                );
                                pdo_insert($this->table_checklog, $data);
                                $fstype = true;
                            }
                        }else{
                            $fstype = true;
                            $this_name = "本卡未绑定任何学生或老师";
                        }
                    }else{
                        $fstype = true;
                        $result['info'] = "不可重复相同刷卡数据";
                    }
                    if ($fstype ==true){
                        $result['data'] = $v['photoName'];
                        $result['code'] = 1000;
                        $result['msg'] = $this_name;
                        $result['ServerTime'] = date('Y-m-d H:i:s',time());
                        echo json_encode($result);
                        exit;
                    }else{
                        $result['data'] = "";
                        $result['code'] = 300;
                        $result['msg'] = "lose";
                        $result['ServerTime'] = date('Y-m-d H:i:s',time());
                        echo json_encode($result);
                        exit;
                    }
                //第二次提交，仅保存图片数据
                }elseif(!empty($v['photoName']) && empty($v['signId'])){
                    $photoName = $v['photoName'];
                    load()->func('file');
                    $files_image = current($up_file);//第一张图
                    $files_image2 = next($up_file);//第二张图
                    $path = "images/fm_jiaoyu/check/". date('Y/m/d/');
                    if(!empty($files_image)) {
                        if (!is_dir(IA_ROOT."/attachment/". $path)) {
                            mkdirs(IA_ROOT."/attachment/". $path, "0777");
                        }
                        $picurl = $path.random(30) .".jpg";
                        move_uploaded_file($_FILES["file1"]["tmp_name"], ATTACHMENT_ROOT."/$picurl");
                        if (!empty($_W['setting']['remote']['type'])){
                            $remotestatus = file_remote_upload($picurl);
                        }
                        $pic = $picurl;
                    }
                    $Find  = pdo_fetch("SELECT id FROM " . tablename($this->table_checklog) . " WHERE schoolid = :schoolid And pic = :pic ", array(':schoolid' =>$schoolid,':pic' =>$photoName));
                    if(!empty($Find)){
                        pdo_update($this->table_checklog,array('pic'=>$pic),array('id'=>$Find['id']));
                        $result['data'] = $photoName;
                        $result['code'] = 1000;
                        $result['msg'] = "图片保存成功";
                        $result['ServerTime'] = date('Y-m-d H:i:s',time());
                        echo json_encode($result);
                        exit;
                    }else{
                        $result['data'] =$photoName;
                        $result['code'] = 1000;
                        $result['msg'] = "图片失败，未找到图片记录";
                        $result['ServerTime'] = date('Y-m-d H:i:s',time());
                        echo json_encode($result);
                        exit;
                    }
                }
            }
        }
    }else{
        $result['data'] =$_GPC;
        $result['code'] = 1000;
        $result['back_info'] = "未提交数据";
        $result['msg'] = "未提交数据";
        $result['ServerTime'] = date('Y-m-d H:i:s',time());
        echo json_encode($result);
        exit;
    }
}



if ($operation == 'getallroom'){
    //$data = json_decode($_GPC['data']);
    if($ckmac['apid'] == 0){
        $roomlist = array();
    }else{
        $apinfo =  pdo_fetchall("SELECT name FROM " . tablename($this->table_apartmentset) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' and apid = '{$ckmac['apid']}'");
        $roomlist_temp =  pdo_fetchall("SELECT id,name,noon_start,noon_end,night_start,night_end,apid,floornum FROM " . tablename($this->table_aproom) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' and apid = '{$ckmac['apid']}'");
        $roomlist = array();
        foreach($roomlist_temp as $key=>$value){
            $roomlist[$key]['name'] = $value['name'];
            $roomlist[$key]['id'] = $value['id'];
            $roomlist[$key]['apid'] = $value['apid'];
            $roomlist[$key]['floornum'] = $value['floornum'];
            $roomlist[$key]['time'][] = array(
                'start' => $value['noon_start'],
                'end' => $value['noon_end'],
            );
            $roomlist[$key]['time'][] = array(
                'start' => $value['night_start'],
                'end' => $value['night_end'],
            );

        }
    }
    $result['code'] = 1000;
    $result['msg']    = "success";
    $result['data'] = $roomlist;
    echo json_encode($result);
    exit;
}



?>
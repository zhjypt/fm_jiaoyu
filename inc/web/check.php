<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
global $_GPC, $_W;


$weid              = $_W['uniacid'];
$action            = 'check';
$this1             = 'no5';
//var_dump($_W);
$schoolid          = intval($_GPC['schoolid']);
$logo              = pdo_fetch("SELECT logo,title,gonggao FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}'");
$GLOBALS['frames'] = $this->getNaveMenu($_GPC['schoolid'], $action);
load()->func('tpl');
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$tid_global = $_W['tid'];
$schooltype = $_W['schooltype'];
if(!empty($_W['setting']['remote']['type'])){
    $urls = $_W['attachurl'];
}else{
    $urls = $_W['siteroot'] . 'attachment/';
}
if($operation == 'display'){
    if (!(IsHasQx($tid_global,1002401,1,$schoolid))){
        $this->imessage('非法访问，您无权操作该页面','','error');
    }
    $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_checkmac) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' ORDER BY id DESC");
    foreach($list as $key => $row){
        $item                 = pdo_fetch("SELECT * FROM " . tablename($this->table_checkmac) . " WHERE id = '{$row['id']}'");
        $banner               = unserialize($item['banner']);
        $list[$key]['isflow'] = $banner['isflow'];
        $list[$key]['video']  = $banner['video'];
        $list[$key]['video']  = $banner['video'];
    }
}elseif($operation == 'post'){
    if (!(IsHasQx($tid_global,1002402,1,$schoolid))){
        $this->imessage('非法访问，您无权操作该页面','','error');
    }
    $apartment = pdo_fetchall("SELECT * FROM " . tablename($this->table_apartment) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' ORDER BY id DESC");
    $allroom = pdo_fetchall("SELECT sid,sname FROM " . tablename($this->table_classify) . " WHERE weid = '{$weid}' And type = 'addr' And schoolid = {$schoolid} ORDER BY ssort DESC");
    $class = pdo_fetchall("SELECT sid as id, sname as classes, schoolid as sid, ssort as score, tid FROM " . tablename($this->table_classify) . " WHERE weid = '{$weid}' And type = 'theclass' And schoolid = {$schoolid} ORDER BY ssort DESC");
    $qh = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " WHERE weid = '{$weid}' And type = 'score' And schoolid = {$schoolid} ORDER BY ssort DESC");
    $id     = intval($_GPC['id']);
    $item   = pdo_fetch("SELECT * FROM " . tablename($this->table_checkmac) . " WHERE id = {$id} ");
    $banner = unserialize($item['banner']);
    $set    = unserialize($item['macset']);
    if(checksubmit('submit')){

        $data = array(
            'weid'       => $weid,
            'schoolid'   => $schoolid,
            'name'       => $_GPC['name'],
            'macname'    => intval($_GPC['macname']),
            'twmac'      => empty($_GPC['twmac'])? -1 : trim($_GPC['twmac']),
            'createtime' => time(),
            'bj_id'		 =>intval($_GPC['bj_id']),
            'js_id'		 =>intval($_GPC['js_id']),
            'is_bobao'   =>intval($_GPC['is_bobao']),
            'is_master'  =>intval($_GPC['is_master'])
        );
        if(!empty($id)){
            if($item['macname'] != $data['macname']){
                $this->imessage($item['macname'], $this->createWebUrl('check', array('op' => 'display', 'schoolid' => $schoolid)), 'info');
            }
        }
        if($_GPC['macname'] == 1){
            $data['macid'] = trim($_GPC['macid_jl']);
            $data['stu1'] = intval($_GPC['stu1_jl']);
            $data['stu2'] = intval($_GPC['stu2_jl']);
            $data['stu3'] = intval($_GPC['stu3_jl']);
            $data['type'] = intval($_GPC['type_jl']);
            if($_GPC['is_apcheck_jl'] == 2){
                $data['apid'] = intval($_GPC['ap_id_jl']);
            }else{
                $data['apid'] = 0;
            }
        }
        if($_GPC['macname'] == 7){
            $data['macid'] = trim($_GPC['macid_jl']);

        }
        if($_GPC['macname'] == 2 || $_GPC['macname'] == 5 || $_GPC['macname'] == 6 || $_GPC['macname'] == 11){
            if(empty($_GPC['is_bobao'])){
                if(empty($_GPC['pop']) || empty($_GPC['pic1']) || empty($_GPC['pic2']) || empty($_GPC['pic3']) || empty($_GPC['pic4'])){
                    if($_GPC['macname'] != 6 && $_GPC['is_bobao'] != 1){
                        $this->imessage('广告语、图片1-4，不可为空！', $this->createWebUrl('check', array('op' => 'display', 'schoolid' => $schoolid)), 'info');
                    }
                }
            }
            if($_GPC['macname'] == 11){
                $data['is_heartbeat'] = $_GPC['is_heartbeat'];

            }
            $data['macid'] = trim($_GPC['macid_xz']);
            $data['stu1'] = intval($_GPC['stu1_xz']);
            $data['stu2'] = intval($_GPC['stu2_xz']);
            $data['stu3'] = intval($_GPC['stu3_xz']);
            $data['type'] = intval($_GPC['type_xz']);
            if($_GPC['is_apcheck'] == 2){
                $data['apid'] = intval($_GPC['ap_id']);
            }else{
                $data['apid'] = 0;
            }
            $data['cardtype'] = intval($_GPC['cardtype']);
            $temp = array(
                'isflow'   => 1,
                'pop'      => $_GPC['pop'],
                //开始修改
                'welcome'      => $_GPC['welcome'],
                'password'      => $_GPC['password'],
                'starttime'      => $_GPC['starttime'],
                'shutdowntime'      => $_GPC['shutdowntime'],
                'voice'=> $_GPC['voice'],
                //结束修改
                'video'    => $_GPC['video'],
                'pic1'     => $_GPC['pic1'],
                'pic2'     => $_GPC['pic2'],
                'pic3'     => $_GPC['pic3'],
                'pic4'     => $_GPC['pic4'],
                'VOICEPRE' => $_GPC['VOICEPRE'],
                'VOICEPRE2' => $_GPC['VOICEPRE2'],
            );
            if($_GPC['macname'] == 2 && $_GPC['is_bobao'] == 3){
                $data['qh_id'] = intval($_GPC['qh_id']);
                $data['areaid'] = intval($_GPC['areaid']);
                $data['cityname'] = trim($_GPC['cityname']);
                $data['model_type'] = intval($_GPC['model_type']);
                $data['exam_room_name'] = trim($_GPC['exam_room_name']);
                $temp = array(
                    'isflow'   => 1,
                    'class_img'    => trim($_GPC['class_img']),
                    'video_url'    => trim($_GPC['video_url']),
                );
            }
        }
        if($_GPC['macname'] == 3){
			if($_GPC['is_bobao'] == 1){ //优米校车不判断图片什么的
				if(empty($_GPC['pop1']) || empty($_GPC['pic11']) || empty($_GPC['pic21']) || empty($_GPC['pic31']) || empty($_GPC['pic41']) || empty($_GPC['VOICEPRE1'])){
					$this->imessage('提示语言、滚动公告、图片1-4，不可为空！', $this->createWebUrl('check', array('op' => 'display', 'schoolid' => $schoolid)), 'info');
				}
			}
            if(empty($_GPC['macid_ym'])){
                $this->imessage('抱歉,设备号不能为空', $this->createWebUrl('check', array('op' => 'display', 'schoolid' => $schoolid)), 'error');
            }
            $data['macid'] = trim($_GPC['macid_ym']);
            $data['stu1'] = intval($_GPC['stu1_ym']);
            $data['stu2'] = intval($_GPC['stu2_ym']);
            $data['stu3'] = intval($_GPC['stu3_ym']);
            $data['type'] = intval($_GPC['type_ym']);
            if($_GPC['is_apcheck_ym'] == 2){
                $data['apid'] = intval($_GPC['ap_id_ym']);
            }else{
                $data['apid'] = 0;
            }
            $temp = array(
                'isflow'   => 1,
                'pop'      => $_GPC['pop1'],
                'video'    => $_GPC['video1'],
                'pic1'     => $_GPC['pic11'],
                'pic2'     => $_GPC['pic21'],
                'pic3'     => $_GPC['pic31'],
                'pic4'     => $_GPC['pic41'],
                'VOICEPRE' => $_GPC['VOICEPRE1'],
            );
        }
        if($_GPC['macname'] == 4){
            $data['macid'] = trim($_GPC['macid_abb']);
            $data['type'] = intval($_GPC['type_abb']);
            $temp = array(
                'isflow'   => 1,
                'pop'      => trim($_GPC['foot_pop']),
                'bgimg'    => trim($_GPC['bgimg']),//大图背景对应postion1
                'qrcode'   => trim($_GPC['qrcode']),
                'pic1'     => trim($_GPC['pic1_abb']),
                'pic2'     => trim($_GPC['pic2_abb']),
                'pic3'     => trim($_GPC['pic3_abb']),
                'pic4'     => trim($_GPC['pic4_abb']),
                'pic5'     => trim($_GPC['pic5_abb']),
            );
            if($_GPC['device_id'] || $_GPC['user_id'] || $_GPC['pwd']){
                if(empty($_GPC['endpoint']) || empty($_GPC['bucket']) || empty($_GPC['accessKeyId']) || empty($_GPC['accessKeySecret'])){
                    $this->imessage('启用外置摄像头时,必须在刷卡信息矩形框内设置OSS相关所有设置！', $this->createWebUrl('check', array('op' => 'display', 'schoolid' => $schoolid)), 'info');
                }
            }
            $temp1 = array(
                'weather_id'   		 => trim($_GPC['weather_id']),//天气
                'apikey'   		 	 => trim($_GPC['apikey']),
                'device_id'   		 => trim($_GPC['device_id']),//摄像头
                'user_id'     		 => trim($_GPC['user_id']),
                'pwd'      	  		 => trim($_GPC['pwd']),
                'endpoint'    		 => trim($_GPC['endpoint']),
                'bucket'      		 => trim($_GPC['bucket']),
                'rootUrl'     		 => trim($_GPC['rootUrl']),
                'accessKeyId' 		 => trim($_GPC['accessKeyId']),
                'accessKeySecret'    => trim($_GPC['accessKeySecret']),
            );
        }
        $data['macset'] = serialize($temp1);
        $data['banner'] = serialize($temp);
        if(!empty($id)){
            unset($data['createtime']);
            $data['lastedittime'] = time();
            pdo_update($this->table_checkmac, $data, array('id' => $id));
        }else{
            if($data['macname'] != 1){
                if($data['macname'] == 2){
                    $mactype = 1;
                    $posturl = $_W['siteroot'] . 'app/index.php?i=' . $weid . '&c=entry&schoolid=' . $schoolid . '&do=checkxz&m=fm_jiaoyu';
                }
                if($data['macname'] == 3){
                    $mactype = 2;
                    $posturl = $_W['siteroot'] . 'app/index.php?i=' . $weid . '&c=entry&schoolid=' . $schoolid . '&do=checkym&m=fm_jiaoyu';
                }
                if($data['macname'] == 4){
                    $mactype = 3;
                    $posturl = $_W['siteroot'] . 'app/index.php?i=' . $weid . '&c=entry&schoolid=' . $schoolid . '&do=checkabb&m=fm_jiaoyu';
                }
                if($data['macname'] == 5){
                    $mactype = 4;
                    $posturl = $_W['siteroot'] . 'app/index.php?i=' . $weid . '&c=entry&schoolid=' . $schoolid . '&do=checkhx&m=fm_jiaoyu';
                }
                if($data['macname'] == 6){
                    $mactype = 5;
                    $posturl = $_W['siteroot'] . 'app/index.php?i=' . $weid . '&c=entry&schoolid=' . $schoolid . '&do=checkwn&m=fm_jiaoyu';
                }
                if($data['macname'] == 7){
                    $mactype = 6;
                    $posturl = $_W['siteroot'] . 'app/index.php?i=' . $weid . '&c=entry&schoolid=' . $schoolid . '&do=checkym&m=fm_jiaoyu';
                }
                if($data['macname'] == 11){
                    $mactype = 11;
                    $posturl = $_W['siteroot'] . 'app/index.php?i=' . $weid . '&c=entry&schoolid=' . $schoolid . '&do=checkzb&m=fm_jiaoyu';
                }
                $addmac = opreatmac($data['macid'],$mactype,$posturl,'add',$logo['title']);
                $respoed = json_decode($addmac,true);
                if($respoed['result'] !=0){
                    if($respoed['result'] == 1){
                        $data['lastedittime'] = time();
                        pdo_insert($this->table_checkmac, $data);
                    }
                    if($respoed['result'] == 4){
                        $this->imessage($respoed['info'], $this->createWebUrl('check', array('op' => 'display', 'schoolid' => $schoolid)), 'error');
                    }
                }else{
                    $this->imessage($respoed['info'], $this->createWebUrl('check', array('op' => 'display', 'schoolid' => $schoolid)), 'error');
                }
            }else{
                $data['lastedittime'] = time();
                pdo_insert($this->table_checkmac, $data);
            }
        }

        $this->imessage('操作成功！', $this->createWebUrl('check', array('op' => 'display', 'schoolid' => $schoolid)), 'success');
    }
}elseif($operation == 'posta'){
    $id   = intval($_GPC['id']);
    $item = pdo_fetch("SELECT id,exam_plan FROM " . tablename($this->table_checkmac) . " WHERE id = '{$id}' ");
    $exam_plan = unserialize($item['exam_plan']);
    $lastkey = count($exam_plan) +1;
    $allkm = pdo_fetchall("SELECT sid,sname FROM " . tablename($this->table_classify) . " WHERE weid = '{$weid}' And type = 'subject' And schoolid = {$schoolid} ORDER BY ssort DESC");
    $allteacher = pdo_fetchall("SELECT id,tname FROM " . tablename($this->table_teachers) . " WHERE weid = '{$weid}' And schoolid = {$schoolid} ");
    if(checksubmit('submit')){
        if(!empty($_GPC['new'])){
            $exam = array();
            //print_r($_GPC['exam_course']);
            $i = 0;
            foreach($_GPC['new'] as $key =>$val){
                $data[$i]['exam_course'] = $_GPC['exam_course'][$key];
                $data[$i]['exam_start_time'] = $_GPC['exam_start_time'][$key];
                $data[$i]['exam_end_time'] = $_GPC['exam_end_time'][$key];
                $data[$i]['exam_teacher1'] = $_GPC['exam_teacher1'][$key];
                $data[$i]['exam_teacher2'] = $_GPC['exam_teacher2'][$key];
                $i++;
            }

            $exam = serialize($data);
            pdo_update($this->table_checkmac, array('exam_plan' => $exam,'lastedittime'=>time()), array('id' => $id));
        }
        $this->imessage('操作成功！', $this->createWebUrl('check', array('op' => 'posta','id' => $id, 'schoolid' => $schoolid)), 'success');
    }
}elseif($operation == 'delete'){
    $id   = intval($_GPC['id']);
    $item = pdo_fetch("SELECT id,macname,macid FROM " . tablename($this->table_checkmac) . " WHERE id = '{$id}' ");
    if(empty($item)){
        $this->imessage('抱歉，不存在或是已经被删除！', $this->createWebUrl('check', array('op' => 'display', 'schoolid' => $schoolid)), 'error');
    }
    if($item['macname'] != 1){
        if($item['macname'] == 2){
            $mactype = 1;
            $posturl = '';
        }
        if($item['macname'] == 3){
            $mactype = 2;
            $posturl = '';
        }
        if($item['macname'] == 4){
            $mactype = 3;
            $posturl = '';
        }
        opreatmac($item['macid'],$mactype,$posturl,'del',$logo['title']);
        pdo_delete($this->table_checkmac, array('id' => $id));
    }else{
        pdo_delete($this->table_checkmac, array('id' => $id));
    }
    $this->imessage('删除成功！', $this->createWebUrl('check', array('op' => 'display', 'schoolid' => $schoolid)), 'success');
}elseif($operation == 'change'){


    $id    = intval($_GPC['id']);
    $is_on = intval($_GPC['is_on']);

    $data = array('is_on' => $is_on);
    $data['lastedittime'] = time();
    pdo_update($this->table_checkmac, $data, array('id' => $id));
}else{
    $this->imessage('请求方式不存在');
}
include $this->template('web/check');
?>
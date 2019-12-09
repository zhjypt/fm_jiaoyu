<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
global $_GPC, $_W;
mload()->model('que');
//$schooltype  = $_W['schooltype']; 培训机构的群发还没有做好
$schooltype = false ;
//lee 0725
$lastid = pdo_fetch("SELECT id FROM " . tablename($this->table_class) . " ORDER by id DESC LIMIT 0,1");
$lastids = empty($lastid['id']) ? 1000 :$lastid['id'];


$weid              = $_W['uniacid'];
$this1             = 'no3';
$action            = 'notice';
$GLOBALS['frames'] = $this->getNaveMenu($_GPC['schoolid'], $action);
$schoolid          = intval($_GPC['schoolid']);
$logo              = pdo_fetch("SELECT logo,title FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}'");
$bjlist            = pdo_fetchall("SELECT sid,sname FROM " . tablename($this->table_classify) . " WHERE weid = :weid AND schoolid = :schoolid And type = :type ORDER BY sid ASC, ssort DESC", array(
    ':weid'     => $weid,
    ':schoolid' => $schoolid,
    ':type'     => 'theclass'
));
$kmlist            = pdo_fetchall("SELECT sid,sname FROM " . tablename($this->table_classify) . " WHERE weid = :weid AND schoolid = :schoolid And type = :type ORDER BY sid ASC, ssort DESC", array(
    ':weid'     => $weid,
    ':schoolid' => $schoolid,
    ':type'     => 'subject'
));
$techerlist        = pdo_fetchall("SELECT id,tname FROM " . tablename($this->table_teachers) . " WHERE weid = :weid AND schoolid = :schoolid ORDER BY CONVERT(tname USING gbk)  ASC", array(
    ':weid'     => $weid,
    ':schoolid' => $schoolid,
));

$bj = pdo_fetchall("SELECT sid,sname FROM " . tablename($this->table_classify) . " where weid = :weid AND schoolid = :schoolid And type = :type and is_over!=:is_over ORDER BY CONVERT(sname USING gbk) ASC", array(':weid' => $weid, ':type' => 'theclass', ':schoolid' => $schoolid,':is_over'=>"2"));
$nj = pdo_fetchall("SELECT sid,sname FROM " . tablename($this->table_classify) . " where weid = :weid AND schoolid = :schoolid And type = :type and is_over!=:is_over ORDER BY CONVERT(sname USING gbk) ASC", array(':weid' => $weid, ':type' => 'semester', ':schoolid' => $schoolid,':is_over'=>"2"));
$bj_str_temp = '0,';
foreach($bj as $key_b=>$value_b){
	$bj_str_temp .=$value_b['sid'].",";
}
$bj_str = trim($bj_str_temp,",");
$nj_str_temp = '0,';
foreach($nj as $key_n=>$value_n){
	$nj_str_temp .=$value_n['sid'].",";
}
$nj_str = trim($nj_str_temp,",");
$kclist = pdo_fetchall("SELECT id as sid,name as sname FROM " . tablename($this->table_tcourse) . " WHERE weid = :weid AND schoolid = :schoolid  and FIND_IN_SET(bj_id,:bj_str) and FIND_IN_SET(xq_id,:nj_str) ORDER BY id ASC", array(
    ':weid'     => $weid,
    ':schoolid' => $schoolid,
	':bj_str'   => $bj_str,
	':nj_str'	=> $nj_str
));
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$tid_global = $_W['tid'];
if(is_numeric($tid_global)){
	mload()->model('tea');
	$kclist = GetAllClassInfoByTid($schoolid,2,$schooltype,$tid_global);
}
if($operation =='display' && !(IsHasQx($tid_global,1001201,1,$schoolid))){
	$operation = 'display1';
	$stopurl = $_W['siteroot'] .'web/'.$this->createWebUrl('notice', array('schoolid' => $schoolid,'op'=>$operation));
	header("location:$stopurl");
}
if($operation =='display1' && !(IsHasQx($tid_global,1001211,1,$schoolid))){
	$operation = 'display2';
	$stopurl = $_W['siteroot'] .'web/'.$this->createWebUrl('notice', array('schoolid' => $schoolid,'op'=>$operation));
	header("location:$stopurl");
}
if($operation =='display2' && !(IsHasQx($tid_global,1001221,1,$schoolid))){
	$operation = 'display3';
	$stopurl = $_W['siteroot'] .'web/'.$this->createWebUrl('notice', array('schoolid' => $schoolid,'op'=>$operation));
	header("location:$stopurl");
}
if($operation =='display3' && !(IsHasQx($tid_global,1001231,1,$schoolid))){
	$operation = 'display4';
	$stopurl = $_W['siteroot'] .'web/'.$this->createWebUrl('notice', array('schoolid' => $schoolid,'op'=>$operation));
	header("location:$stopurl");
}
if($operation =='display4' && !(IsHasQx($tid_global,1001241,1,$schoolid))){
	$operation = 'display';
	$stopurl = $_W['siteroot'] .'web/'.$this->createWebUrl('notice', array('schoolid' => $schoolid,'op'=>$operation));
	header("location:$stopurl");
}

//如果是班级通知
if($operation == 'display'){
	if (!(IsHasQx($tid_global,1001201,1,$schoolid))){
		$this->imessage('非法访问，您无权操作该页面','','error');	
	}
    $pindex    = max(1, intval($_GPC['page']));
    $psize     = 20;
    $condition = '';
    $params    = array();
    if(!empty($_GPC['keyword'])){
        $condition          .= " AND title LIKE :keyword";
        $params[':keyword'] = "%{$_GPC['keyword']}%";
    }
    if(!empty($_GPC['bj_id'])){
		if($schooltype){
			$condition .= " AND kc_id = '{$_GPC['bj_id']}'";
		}else{
			$condition .= " AND bj_id = '{$_GPC['bj_id']}'";
		}
    }
	//获取内容
    $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_notice) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' And type = 1 $condition ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
    foreach($list as $key => $row){
	    //获取班级名称
        $bj                   = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " WHERE sid = :sid", array(':sid' => $row['bj_id']));
        $list[$key]['bjname'] = $bj['sname'];
		if($row['kc_id']){
			$kcinfo = pdo_fetch("SELECT name FROM " . tablename($this->table_tcourse) . " WHERE id = :id", array(':id' => $row['kc_id']));
			$list[$key]['bjname'] = $kcinfo['name'];
		}
    }
    //获取总个数
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_notice) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' And type = 1 $condition");
    $pager = pagination($total, $pindex, $psize);
//如果是校园通知
}elseif($operation == 'display1'){
    $pindex    = max(1, intval($_GPC['page']));
    $psize     = 20;
    $condition = '';
    $params    = array();
    if(!empty($_GPC['keyword'])){
        $condition          .= " AND title LIKE :keyword";
        $params[':keyword'] = "%{$_GPC['keyword']}%";
    }
    if(!empty($_GPC['group'])){
        $condition .= " AND groupid = '{$_GPC['group']}'";
    }

    $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_notice) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' And type = 2 $condition ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);

    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_notice) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' And type = 2 $condition ");
    $pager = pagination($total, $pindex, $psize);
    //如果是作业管理
}elseif($operation == 'display2'){
    $pindex    = max(1, intval($_GPC['page']));
    $psize     = 20;
    $condition = '';
    $params    = array();
    if(!empty($_GPC['keyword'])){
        $condition          .= " AND title LIKE :keyword";
        $params[':keyword'] = "%{$_GPC['keyword']}%";
    }
    if(!empty($_GPC['bj_id'])){
		if($schooltype){
			$condition .= " AND kc_id = '{$_GPC['bj_id']}'";
		}else{
			$condition .= " AND bj_id = '{$_GPC['bj_id']}'";
		}
    }
    if(!empty($_GPC['km_id'])){
        $condition .= " AND km_id = '{$_GPC['km_id']}'";
    }

    $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_notice) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' And type = 3 $condition ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
    foreach($list as $key => $row){
        $bj                   = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = :sid", array(':sid' => $row['bj_id']));
        $km                   = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = :sid", array(':sid' => $row['km_id']));
        $list[$key]['bjname'] = $bj['sname'];
		if($row['kc_id']){
			$kcinfo = pdo_fetch("SELECT name FROM " . tablename($this->table_tcourse) . " WHERE id = :id", array(':id' => $row['kc_id']));
			$list[$key]['bjname'] = $kcinfo['name'];
		}
        $list[$key]['kmname'] = $km['sname'];
    }

    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_notice) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' And type = 3 $condition");
    $pager = pagination($total, $pindex, $psize);
    //如果是请假
}elseif($operation == 'display3'){
    $pindex    = max(1, intval($_GPC['page']));
    $psize     = 20;
    $condition = '';
    $params    = array();
    $out_excel = $_GPC['out_excel'];
	$out_excel_allTea = $_GPC['out_excel_allTea'];
	$out_all_endtime = time();
	$out_all_starttime = strtotime('-1 month',$out_all_endtime);
    if(!empty($_GPC['keyword'])){
        $condition          .= " AND title LIKE :keyword";
        $params[':keyword'] = "%{$_GPC['keyword']}%";
    }
    if(!empty($_GPC['bj_id'])){
        $condition .= " AND bj_id = '{$_GPC['bj_id']}'";
    }
    if(!empty($_GPC['fenlei'])){
        if($_GPC['fenlei'] == 1){
            $condition .= " AND sid = 0 ";
        }
        if($_GPC['fenlei'] == 2){
            $condition .= " AND tid = 0 ";
        }
    }
    if(!empty($_GPC['leixing'])){
        $condition .= " AND type = '{$_GPC['leixing']}'";
    }
    if(!empty($_GPC['chuli'])){
        $condition .= " AND status = '{$_GPC['chuli']}'";
    }
  	if(!empty($_GPC['createtime'])) {
	  	if(strtotime($_GPC['createtime']['start']) > 0){
			$starttime = strtotime($_GPC['createtime']['start']);
			$endtime = strtotime($_GPC['createtime']['end']) + 86399;
			$condition .= " AND createtime > '{$starttime}' AND createtime < '{$endtime}'";
		}
	} else {
		$starttime = -1;
		$endtime = -1;
	}

	if($out_excel == "Yes"){
		$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_leave) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' And isliuyan = 0 $condition ORDER BY createtime DESC, id DESC ");
		$ii = 0;
		$array_out = array();
		foreach($list as $key => $row){
		   
			$student              = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where weid = :weid And id = :id ", array(':weid' => $_W ['uniacid'], ':id' => $row['sid']));
			$teacher              = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where weid = :weid And id = :id ", array(':weid' => $_W ['uniacid'], ':id' => $row['tid']));
			$user                 = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where weid = :weid And schoolid = :schoolid And sid = :sid And uid = :uid ORDER BY id DESC", array(':weid' => $_W ['uniacid'], ':schoolid' => $row['schoolid'], ':schoolid' => $schoolid, ':sid' => $row['sid'], ':uid' => $row['uid']));
			$bj                   = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " WHERE sid = :sid", array(':sid' => $row['bj_id']));
			if(!empty($student) || !empty($teacher)){
				$array_out[$ii]['id'] = $ii;
				$array_out[$ii]['s_name'] = $student['s_name']?$student['s_name']:$teacher['tname'];
				if(!empty($row['sid']) && empty($row['tid'])){
					$array_out[$ii]['TorS'] = "学生";
				}elseif(empty($row['sid']) && !empty($row['tid'])){
					$array_out[$ii]['TorS'] = "老师";
				}
				if(is_showpf() && $_GPC['fenlei'] == 1){
					$array_out[$ii]['mobile'] =$teacher['mobile']; 
					$this_calssinfo = pdo_fetch("SELECT * FROM " . tablename($this->table_class) . " where weid = '{$weid}' And id ='{$row['classid']}' ");
					$this_bj_name = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " where weid = '{$weid}' And sid ='{$this_calssinfo['bj_id']}' and type='theclass' ");
					$this_km_name = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " where weid = '{$weid}' And sid ='{$this_calssinfo['km_id']}' and type='subject' ");
					$array_out[$ii]['bjname'] =$this_bj_name['sname']?$this_bj_name['sname']:''; 
					$array_out[$ii]['kmname'] =$this_km_name['sname']?$this_km_name['sname']:''; 
				}
				
				
				$array_out[$ii]['content'] = $row['conet'];
				$array_out[$ii]['timerange'] = date('Y/m/d H:m',$row['startime1'])."-".date('Y/m/d  H:m',$row['endtime1']);
				
				if(is_showpf() && $_GPC['fenlei'] == 1){
					$array_out[$ii]['more_less'] = '';
					$array_out[$ii]['this_nums'] = '';
					if($row['more_less'] == 1){
						$array_out[$ii]['more_less'] = '一天以内';
						$array_out[$ii]['this_nums'] = $row['ksnum']."节";
					}elseif($row['more_less'] == 2){
						$start_time = $row['startime1'];
						$end_time = $row['endtime1'];
						$array_out[$ii]['more_less'] = '一天以上';
						$array_out[$ii]['this_nums'] = ($end_time - $start_time + 1)/86400 . "天";
					}
				}
				
				if($row['status'] == 1){
					$array_out[$ii]['status'] ="通过";
				}elseif($row['status'] == 2){
					$array_out[$ii]['status'] ="拒绝";
				}elseif($row['status'] == 0){
					$array_out[$ii]['status'] ="未处理";
				}elseif($row['status'] == 3){
					 $array_out[$ii]['status'] ="处理中";
				}
				$array_out[$ii]['cltime'] =$row['cltime']?date('Y-m-d H:m:s',$row['cltime']):'未处理';
				if (!(is_showpf() && $_GPC['fenlei'] == 1)){
				$array_out[$ii]['bjname'] = $bj['sname']?$bj['sname']:"教师请假";
				}
				$array_out[$ii]['type'] = $row['type'];
				if(is_showgkk() || is_showpf()){
					 if($row['tktype'] == 0){
						$array_out[$ii]['tktype'] = '无课';
					 }else if($row['tktype'] == 1){
						$array_out[$ii]['tktype'] = '自主调课';
					 }else if($row['tktype'] == 2){
						$array_out[$ii]['tktype'] = '教务处调课';
					 }
				}
			   if ((is_showpf() && $_GPC['fenlei'] == 1)){
				   $cltinfo =  pdo_fetch("SELECT tname FROM " . tablename($this->table_teachers) . " where weid = '{$weid}' And id ='{$row['cltid']}' and schoolid='{$schoolid}' ");
				   $array_out[$ii]['cltname'] = $cltinfo['tname']?$cltinfo['tname']:'';
			   }else{
					if ($row['guanxi'] == 2){
						$array_out[$ii]['guanxi'] ="母亲";
					}elseif( $item['guanxi'] == 3){
						$array_out[$ii]['guanxi'] ="父亲";
					}else if( $item['guanxi'] == 4 ||$item['guanxi'] == 0 ){
						$array_out[$ii]['guanxi'] ="本人";
					} 
			   }

				$ii++;
			}
		}
		if (is_showgkk() || is_showpf()){ 
			$tit_array = array('id', '请假人','教师/学生','理由','请假时间','审核状态','处理时间','班级','类型','调课类型','申请人'); 
		}else{
			$tit_array = array('id', '请假人','教师/学生','理由','请假时间','审核状态','处理时间','班级','类型','申请人'); 
		}
		
		if(is_showpf() && $_GPC['fenlei'] == 1){
			$tit_array = array('id', '请假人','教师/学生','联系电话','班级','科目','理由','请假时间','时长类型','节数/天数','审核状态','处理时间','类型','调课类型','审核人'); 
			
		}
		
		$title="请假记录-".date("Y年m月d日导出",time());
		$this->exportexcel($array_out,$tit_array, $title);
		exit();
	}
	
	
	//时间范围汇总教师请假情况
	if($out_excel_allTea == "Yes"){
		if(!empty($_GPC['out_all_createtime'])) {
			if(strtotime($_GPC['out_all_createtime']['start']) > 0){
				$out_all_starttime = strtotime($_GPC['out_all_createtime']['start']);
				$out_all_endtime = strtotime($_GPC['out_all_createtime']['end']) + 86399;
				$condition_this .= " AND createtime >= '{$out_all_starttime}' AND createtime <= '{$out_all_endtime}'";
			}
		}
		
		$tealist_this = pdo_fetchall("SELECT distinct tid FROM " . tablename($this->table_leave) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' and status = 1 and tid != 0 And isliuyan = 0 $condition_this ORDER BY createtime DESC, id DESC ");
		$ii = 0;
		$array_out = array();
		foreach($tealist_this as $key => $row){
			$teacher  = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where weid = :weid And id = :id ", array(':weid' => $_W ['uniacid'], ':id' => $row['tid']));
			if(!empty($teacher)){
				$array_out[$ii]['id'] = $ii;
				$array_out[$ii]['tname'] = $teacher['tname'];
				$array_out[$ii]['mobile'] = $teacher['mobile']?$teacher['mobile']:'';
				$all_day = pdo_fetchcolumn("SELECT sum((endtime1 - startime1 + 1)/86400) FROM " . tablename($this->table_leave) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}'and status = 1 And isliuyan = 0 and more_less =2 and tid= '{$row['tid']}' $condition_this ");
				$all_ks = pdo_fetchcolumn("SELECT sum(ksnum) FROM " . tablename($this->table_leave) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' and status = 1 And isliuyan = 0 and more_less =1 and tid= '{$row['tid']}' $condition_this ");
				$array_out[$ii]['allcount'] = intval($all_day).'天+'.intval($all_ks).'节'; //总数统计
				$bing_day = pdo_fetchcolumn("SELECT sum((endtime1 - startime1 + 1)/86400) FROM " . tablename($this->table_leave) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' and status = 1 And isliuyan = 0 and more_less =2 and tid= '{$row['tid']}' and type='病假' $condition_this ");
				$bing_ks = pdo_fetchcolumn("SELECT sum(ksnum) FROM " . tablename($this->table_leave) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' and status = 1 And isliuyan = 0 and more_less =1 and tid= '{$row['tid']}' and type='病假' $condition_this ");
				$array_out[$ii]['bingcount'] = intval($bing_day).'天+'.intval($bing_ks).'节'; //病假统计
				$shi_day = pdo_fetchcolumn("SELECT sum((endtime1 - startime1 + 1)/86400) FROM " . tablename($this->table_leave) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' and status = 1 And isliuyan = 0 and more_less =2 and tid= '{$row['tid']}' and type='事假'  $condition_this ");
				$shi_ks = pdo_fetchcolumn("SELECT sum(ksnum) FROM " . tablename($this->table_leave) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' and status = 1 And isliuyan = 0 and more_less =1 and tid= '{$row['tid']}' and type='事假'  $condition_this ");
				$array_out[$ii]['shicount'] = intval($shi_day).'天+'.intval($shi_ks).'节'; //事假统计
				$chai_day = pdo_fetchcolumn("SELECT sum((endtime1 - startime1 + 1)/86400) FROM " . tablename($this->table_leave) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' and status = 1 And isliuyan = 0 and more_less =2 and tid= '{$row['tid']}' and type='公差'  $condition_this ");
				$chai_ks = pdo_fetchcolumn("SELECT sum(ksnum) FROM " . tablename($this->table_leave) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' and status = 1 And isliuyan = 0 and more_less =1 and tid= '{$row['tid']}' and type='公差'  $condition_this ");
				$array_out[$ii]['chaicount'] = intval($chai_day).'天+'.intval($chai_ks).'节'; //公差统计
				$qita_day = pdo_fetchcolumn("SELECT sum((endtime1 - startime1 + 1)/86400) FROM " . tablename($this->table_leave) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' and status = 1 And isliuyan = 0 and more_less =2 and tid= '{$row['tid']}' and type='其他'  $condition_this ");
				$qita_ks = pdo_fetchcolumn("SELECT sum(ksnum) FROM " . tablename($this->table_leave) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' and status = 1 And isliuyan = 0 and more_less =1 and tid= '{$row['tid']}' and type='其他'  $condition_this ");
				$array_out[$ii]['qitacount'] = intval($qita_day).'天+'.intval($qita_ks).'节'; //其他统计
				$ii++;
			}
		}

		$tit_array = array('id', '请假人','联系电话','总数统计','病假统计','事假统计','公差统计','其他统计'); 
		$title="教师请假汇总统计-".date("Y年m月d日",$out_all_starttime)."至".date("Y年m月d日",$out_all_endtime);
		$this->exportexcel($array_out,$tit_array, $title);
		exit(); 
	}
    $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_leave) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' And isliuyan = 0 $condition ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
    foreach($list as $key => $row){
        $member               = pdo_fetch("SELECT * FROM " . tablename('mc_members') . " where uniacid = :uniacid And uid = :uid ", array(':uniacid' => $_W ['uniacid'], ':uid' => $row['uid']));
        $student              = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where weid = :weid And id = :id ", array(':weid' => $_W ['uniacid'], ':id' => $row['sid']));
        $teacher              = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where weid = :weid And id = :id ", array(':weid' => $_W ['uniacid'], ':id' => $row['tid']));
        $user                 = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where weid = :weid And schoolid = :schoolid And sid = :sid And uid = :uid ORDER BY id DESC", array(':weid' => $_W ['uniacid'], ':schoolid' => $row['schoolid'], ':schoolid' => $schoolid, ':sid' => $row['sid'], ':uid' => $row['uid']));
        $bj                   = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " WHERE sid = :sid", array(':sid' => $row['bj_id']));
        $list[$key]['bjname'] = $bj['sname'];
        $list[$key]['avatar'] = $member['avatar'];
        $list[$key]['s_name'] = $student['s_name'];
        $list[$key]['tname']  = $teacher['tname'];
        $list[$key]['guanxi'] = $user['pard'];
		if(is_showpf()){
			$classinfo =pdo_fetch("SELECT bj_id,km_id FROM " . tablename($this->table_class) . " WHERE id ='{$row['classid']}' and schoolid = '{$schoolid}' and weid = '{$weid}' "); 
			$this_bj = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid ='{$classinfo['bj_id']}' and schoolid = '{$schoolid}' and weid = '{$weid}' "); 
			$this_km = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid ='{$classinfo['km_id']}' and schoolid = '{$schoolid}' and weid = '{$weid}' ");
			$list[$key]['bjname'] = $this_bj['sname']." ".$this_km['sname'];
			$start_time = $row['startime1'];
			$end_time = $row['endtime1'];
			if($row['more_less'] == 1){
				$list[$key]['this_nums'] =$row['ksnum']; 
			}elseif($row['more_less'] == 2){
				$list[$key]['this_nums'] =($end_time - $start_time + 1)/86400 ; 
			}
		}	
    }
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_leave) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' And isliuyan = 0 $condition ");
    $pager = pagination($total, $pindex, $psize);
}elseif($operation == 'display4'){
    $pindex    = max(1, intval($_GPC['page']));
    $psize     = 8;
    $condition = '';
    mload()->model('user');
    $leave = pdo_fetchall("SELECT * FROM " . tablename($this->table_leave) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' And isliuyan = 2 And isfrist = 1 $condition ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
    foreach($leave as $index => $row){
        $user                   = pdo_fetch("SELECT pard,sid,tid,userinfo FROM " . tablename($this->table_user) . " where weid = :weid AND id = :id", array(':weid' => $weid, ':id' => $row['userid']));
        $touser                 = pdo_fetch("SELECT pard,sid,tid,userinfo FROM " . tablename($this->table_user) . " where weid = :weid AND id = :id", array(':weid' => $weid, ':id' => $row['touserid']));
        $students               = pdo_fetch("SELECT s_name FROM " . tablename($this->table_students) . " where weid = :weid AND id = :id", array(':weid' => $weid, ':id' => $user['sid']));
        $teacher                = pdo_fetch("SELECT tname FROM " . tablename($this->table_teachers) . " where weid = :weid AND id = :id", array(':weid' => $weid, ':id' => $user['tid']));
        $students1              = pdo_fetch("SELECT s_name FROM " . tablename($this->table_students) . " where weid = :weid AND id = :id", array(':weid' => $weid, ':id' => $touser['sid']));
        $teacher1               = pdo_fetch("SELECT tname FROM " . tablename($this->table_teachers) . " where weid = :weid AND id = :id", array(':weid' => $weid, ':id' => $touser['tid']));
        $leave[$index]['huifu'] = pdo_fetchall("SELECT * FROM " . tablename($this->table_leave) . " where weid = :weid AND leaveid = :leaveid ORDER BY createtime DESC LIMIT 0,7", array(':weid' => $weid, ':leaveid' => $row['id']));
        foreach($leave[$index]['huifu'] as $k => $v){

            $leave[$index]['huifu'][$k]['sj']        = sub_day($v['createtime']);
            $leave[$index]['huifu'][$k]['lastconet'] = $v['conet'];
            $leave[$index]['huifu'][$k]['myid']      = $v['userid'];
            $leave[$index]['huifu'][$k]['mytoid']    = $v['touserid'];
            if($v['userid'] == $it['id']){
                $users = pdo_fetch("SELECT pard,sid,tid,userinfo FROM " . tablename($this->table_user) . " where weid = :weid AND id = :id", array(':weid' => $weid, ':id' => $v['touserid']));
            }
            if($v['touserid'] == $it['id']){
                $users = pdo_fetch("SELECT pard,sid,tid,userinfo FROM " . tablename($this->table_user) . " where weid = :weid AND id = :id", array(':weid' => $weid, ':id' => $v['userid']));
            }
            if($users['sid']){
                $leave[$index]['huifu'][$k]['sf'] = 1;
            }
            if($users['tid']){
                $leave[$index]['huifu'][$k]['sf'] = 2;
            }
        }
        $leave[$index]['tname']   = !empty($teacher['tname']) ? "老师" . $teacher['tname'] : '';
        $leave[$index]['s_name']  = !empty($students['s_name']) ? "学生" . $students['s_name'] : '';
        $leave[$index]['tname1']  = !empty($teacher1['tname']) ? "老师" . $teacher1['tname'] : '';
        $leave[$index]['s_name1'] = !empty($students1['s_name']) ? "学生" . $students1['s_name'] : '';
        $leave[$index]['pard']    = $user['pard'];
    }

    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_leave) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' And isliuyan = 2 And isfrist = 1 $condition ");
    $pager = pagination($total, $pindex, $psize);
}elseif($operation == 'display5'){
    $pindex    = max(1, intval($_GPC['page']));
    $notice_id = intval($_GPC['notice_id']);
    $notice    = pdo_fetch("SELECT title FROM " . tablename($this->table_notice) . " where id = :id ", array(':id' => $notice_id));
    $psize     = 100;
    $condition = '';
    if($_GPC['shenfen'] == 1){
        $shenfen   = intval($_GPC['shenfen']);
        $condition .= " AND sid = '0' ";
    }
    if($_GPC['shenfen'] == 2){
        $shenfen   = intval($_GPC['shenfen']);
        $condition .= " AND tid = '0' ";
    }

    $is_pay = isset($_GPC['is_pay']) ? intval($_GPC['is_pay']) : -1;
    if($is_pay >= 0){
        if($is_pay == 2){
            $nowtime   = TIMESTAMP;
            $condition .= " AND readtime > '0' AND readtime < '{$nowtime}'";
        }else if($is_pay == 1){
            $condition         .= " AND readtime = '0' ";
            $params[':is_pay'] = $is_pay;
        }
    }

    $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_record) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' And noticeid = '{$notice_id}' $condition ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);

    foreach($list as $key => $row){
        $student              = pdo_fetch("SELECT s_name FROM " . tablename($this->table_students) . " where id = :id ", array(':id' => $row['sid']));
        $user                 = pdo_fetch("SELECT pard FROM " . tablename($this->table_user) . " where id = :id", array(':id' => $row ['userid']));
        $teacher              = pdo_fetch("SELECT tname FROM " . tablename($this->table_teachers) . " where id = :id ", array(':id' => $row['tid']));
        $list[$key]['s_name'] = $student['s_name'];
        $list[$key]['guanxi'] = $user['pard'];
        $list[$key]['tname']  = $teacher['tname'];
    }
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_record) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' And noticeid = '{$notice_id}' $condition ");
    $pager = pagination($total, $pindex, $psize);
}elseif($operation == 'posta'){

    load()->func('tpl');
    $leaveid = intval($_GPC['leaveid']);

    if(!empty($leaveid)){
        $pindex  = max(1, intval($_GPC['page']));
        $psize   = 20;
        $item    = pdo_fetch("SELECT * FROM " . tablename($this->table_leave) . " WHERE id = '{$leaveid}'");
        $user    = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where weid = :weid And schoolid = :schoolid And sid = :sid And uid = :uid ", array(':weid' => $_W ['uniacid'], ':schoolid' => $row['schoolid'], ':schoolid' => $schoolid, ':sid' => $item['sid'], ':uid' => $item['uid']));
        $student = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where id = :id ", array(':id' => $item['sid']));
        $bj      = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " WHERE sid = :sid ", array(':sid' => $item['bj_id']));
        $teacher = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where id = :id ", array(':id' => $bj['tid']));
        $list    = pdo_fetchall("SELECT * FROM " . tablename($this->table_leave) . " where schoolid = '{$schoolid}' And weid = '{$weid}' And isliuyan = 1 And leaveid = '{$leaveid}' ORDER BY createtime DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
        $total   = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_leave) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' And leaveid = '{$leaveid}' ");
        $pager   = pagination($total, $pindex, $psize);
        if(empty($item)){
            $this->imessage('抱歉，本信息不存在在或是已经删除！', '', 'error');
        }
    }
}elseif($operation == 'postb'){

    load()->func('tpl');
    $id = intval($_GPC['id']);

    if(!empty($id)){

        $item   = pdo_fetch("SELECT * FROM " . tablename($this->table_notice) . " WHERE id = '{$id}'");
        $bj     = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " WHERE sid = :sid ", array(':sid' => $item['bj_id']));
        $anstype = iunserializer($item['anstype']);
       // var_dump($anstype);
        $ZY_contents = GetZyContent($id,$schoolid,$weid);
        $picarr = iunserializer($item['picarr']);
        //print_r($picarr);
        //$p = array();
        //foreach($picarr as $key => $row){
        //print_r($key);
        //$pic = tomedia($row);
        //$picarr[$key]['p'] = '<img src="'.$pic.'" alt="image" style="width:100%;">';
        //}
		$userdatas = explode(';',$item['userdatas']);
		$dataarr = array();
		foreach($userdatas as $row){
			if($row == 0 || $row != ""){
				$dataarr[] = intval($row);
			}	
		}			
		if($item['usertype'] == 'send_class'){
			mload()->model('stu');
			$arr = GetClassInfoByArr($dataarr,$_W['schooltype'],$schoolid);
		}	
		if($item['usertype'] == 'student'){
			mload()->model('stu');
			$arr = GetStuInfoByArr($dataarr,$_W['schooltype'],$schoolid);
		}
		if($item['usertype'] == 'staff_jsfz'){
			mload()->model('tea');
			$arr = GetFzInfoByArr($dataarr,$_W['schooltype'],$schoolid);
		}
		if($item['usertype'] == 'staff'){
			mload()->model('tea');
			$arr = GetTeaInfoByArr($dataarr,$_W['schooltype'],$schoolid);	
		}		
        if(empty($item)){
            $this->imessage('抱歉，本信息不存在在或是已经删除！', '', 'error');
        }
    }
}elseif($operation == 'post'){

    load()->func('tpl');
	$alljsfz = getalljsfz($schoolid);
	
    if(checksubmit('submit')){
		if($_GPC['type'] == 1){
			if($schooltype == true){
				$total = pdo_fetchall("SELECT distinct sid FROM ".tablename($this->table_order)." where weid = :weid And schoolid = :schoolid And kcid = :kc_id and type = 1 and status = 2 ",array(':weid'=>$_W['weid'], ':schoolid'=>$_GPC['schoolid'], ':kc_id'=>$_GPC['kc_id']));
				 if($total < 2){
					 $this->imessage('你选择群发的对象组已绑定微信人数小于2人，不可使用群发！');
				} 
				//var_dump($total);
				//die();
			}elseif($schooltype == false){
				$total = pdo_fetchcolumn("SELECT COUNT(1) FROM ".tablename($this->table_students)." where weid = :weid And schoolid = :schoolid And bj_id = :bj_id",array(':weid'=>$_W['weid'], ':schoolid'=>$_GPC['schoolid'], ':bj_id'=>$_GPC['bj_id']));
				
				if($total < 2){
					 $this->imessage('你选择群发的对象组已绑定微信人数小于2人，不可使用群发！');
				}
			}
		}
		if($_GPC['type'] == 2){
			$groupid = $_GPC['groupid'];
			if ($groupid >= 4) {
				$total = pdo_fetchcolumn("SELECT id FROM ".tablename($this->table_teachers)." where weid = :weid And schoolid = :schoolid And fz_id = :fz_id " ,array(':weid'=>$_W['weid'], ':schoolid'=>$_GPC['schoolid'], ':fz_id'=>$groupid));	
			}else{		
				if ($groupid == 1) {
					$total = pdo_fetchcolumn("SELECT COUNT(1) FROM ".tablename($this->table_user)." where weid = :weid And schoolid = :schoolid",array(':weid'=>$_W['weid'], ':schoolid'=>$_GPC['schoolid']));
				}
				if ($groupid == 2) {
					$total = pdo_fetchcolumn("SELECT COUNT(1) FROM ".tablename($this->table_teachers)." where weid = :weid And schoolid = :schoolid",array(':weid'=>$_W['weid'], ':schoolid'=>$_GPC['schoolid']));
				}
				if ($groupid == 3) {
					$total = pdo_fetchcolumn("SELECT COUNT(1) FROM ".tablename($this->table_students)." where weid = :weid And schoolid = :schoolid",array(':weid'=>$_W['weid'], ':schoolid'=>$_GPC['schoolid']));
				}
				if($total < 2){
					 $this->imessage('你选择群发的对象组已绑定微信人数小于2人，不可使用群发！');
				}
			}
		}
		if($_GPC['type'] == 3){
			if($schooltype == true){
				$total = pdo_fetchall("SELECT distinct sid FROM ".tablename($this->table_order)." where weid = :weid And schoolid = :schoolid And kcid = :kc_id and type = 1 and status = 2 ",array(':weid'=>$_W['weid'], ':schoolid'=>$_GPC['schoolid'], ':kc_id'=>$_GPC['kc_id']));
				/* if($total < 2){
					 $this->imessage('你选择群发的对象组已绑定微信人数小于2人，不可使用群发！');
				} */
				//var_dump($total);
				//die();
			}elseif($schooltype == false){
				$total = pdo_fetchcolumn("SELECT COUNT(1) FROM ".tablename($this->table_students)." where weid = :weid And schoolid = :schoolid And bj_id = :bj_id",array(':weid'=>$_W['weid'], ':schoolid'=>$_GPC['schoolid'], ':bj_id'=>$_GPC['bj_id']));
				if($total < 2){
					 $this->imessage('你选择群发的对象组已绑定微信人数小于2人，不可使用群发！');
				}
			}
			
		}
	
        if(empty($_GPC['title'])){
            $this->imessage('请输入标题！');
        }

        if(empty($_GPC['tid'])){
            $this->imessage('请选择发送者老师');
        }

        if($_GPC['type'] == 1){
			if($schooltype == true){
				if(empty($_GPC['kc_id'])){
					$this->imessage('请选择发送课程！');
				}
			}elseif($schooltype = false){
				if(empty($_GPC['bj_id'])){
					$this->imessage('请选择发送班级！');
				}
			}
           
        }

        if($_GPC['type'] == 2){
            if(empty($_GPC['groupid'])){
                $this->imessage('请选择接收对象！');
            }
        }

        if($_GPC['type'] == 3){
			if($schooltype == true){
				if(empty($_GPC['kc_id'])){
					$this->imessage('请选择发送课程！');
				}
			}elseif($schooltype == false){
				if(empty($_GPC['bj_id'])){
					$this->imessage('请选择发送班级！');
				}
				if(empty($_GPC['km_id'])){
					$this->imessage('请选择接该作业所属科目！');
				}
			}
        }
        $teacher = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " WHERE id = '{$_GPC['tid']}'");
        $setting = pdo_fetch("SELECT istplnotice FROM " . tablename($this->table_set) . " WHERE :weid = weid", array(':weid' => $weid));
        if(!empty($_GPC['bj_id'])){
            $bj_id = intval($_GPC['bj_id']);
        }else{
            $bj_id = 0;
        }
        if(!empty($_GPC['kc_id'])){
            $kc_id = intval($_GPC['kc_id']);
        }else{
            $kc_id = 0;
        }
        if(!empty($_GPC['km_id'])){
            $km_id = intval($_GPC['km_id']);
        }else{
            $km_id = 0;
        }
        if(!empty($_GPC['groupid'])){
            $groupid = intval($_GPC['groupid']);
        }else{
            $groupid = 0;
        }
        $temp = array(
            'weid'       => $weid,
            'schoolid'   => $schoolid,
            'tid'        => $_GPC['tid'],
            'tname'      => $teacher['tname'],
            'title'      => $_GPC['title'],
            'content'    => $_GPC['content'],
            'outurl'     => $_GPC['outurl'],
            'createtime' => time(),
            'bj_id'      => $bj_id,
            'km_id'      => $km_id,
			//'kc_id'      => $kc_id,
            'type'       => $_GPC['type'],
            'groupid'    => $groupid,
            'ismobile'   => 1,
        );
		$is_private = $_GPC['is_private'];
		//同步更新至文章
		if(!empty($is_private)){
			if($picurl[0]){
				$thumb = $picurl[0];
			}else{
				$thumb = $logo['logo'];
			}
			$lastnews = pdo_fetch("SELECT displayorder FROM " . tablename($this->table_news) . " WHERE :schoolid = schoolid And :type = type ORDER BY displayorder DESC LIMIT 1", array(':schoolid' => $schoolid,':type' => 'article'));
			$displayorder = $lastnews['displayorder'] + 1;
			$news = array(
				'weid' => $weid,
				'schoolid' => $schoolid,
				'title' => $_GPC['title'],
				'content' => $_GPC['content'],
				'thumb' => $thumb,
				'description' => $_GPC['content'],
				'author' =>$teacher['tname'],
				'is_display' => 1,
				'is_show_home' => 1,
				'type' => 'article',
				'displayorder' => $displayorder,
				'createtime' => time(),
			);
			pdo_insert($this->table_news, $news);	
		}

        if ($_GPC['type'] == 2 || $_GPC['type'] == 1) {
            if (is_showpf()) {
                $is_research = $_GPC['is_research'];
                if (!empty($is_research)) {
                    $temp['is_research'] = 1;
                }
            }
        }
		
		pdo_insert($this->table_notice, $temp);
		$notice_id = pdo_insertid();		
        // 如果开启了模板通知
        if($setting['istplnotice'] == 1){
        // 开始 type=3 的 if (问卷写入)
			if($_GPC['type'] == 3 || $_GPC['type'] == 2 ||  $_GPC['type'] == 1 ){	
				$qorder = 1;
				$contents = $_GPC['contents'];
				$ques_title = $_GPC['ques_title'];
				$contentss = $_GPC['contentsss'];
				$ques_type = $_GPC['ques_type'];
				$ques_anspoint = $_GPC['ques_anspoint'];
				if(!empty($ques_title)){
					foreach($ques_title as $key=>$value){
						$WJ_temp = array(
						 'weid'     => $weid,
						 'schoolid' => $schoolid,
						 'tid'      => $_GPC['tid'],
						 'zyid'     => $notice_id,
						 'qorder'   => $qorder,
						 'title'    => $ques_title[$key],
						 'type'     => $ques_type[$key]
						);
						//单选
						if($ques_type[$key] == 1 ){
							$i=1;
							$temp1;
							foreach ($contents[$key] as $keys=>$values){	
								$temp1[$i] = array(
								'title' =>$values);
								$temp1[$i]['is_answer'] ='No';
								foreach($contentss[$key] as $keyss=>$valuess){
									if($valuess == $i){ //输出答案
										$temp1[$i]['is_answer'] = 'Yes';
									}
								}
								$i++;
							}
							$temp11 = iserializer($temp1);
							$WJ_temp['content'] = $temp11;
						//多选
						}elseif($ques_type[$key] == 2){	
								$i=1;
							
								$temp2;
							foreach ($contents[$key] as $keys=>$values){
								foreach($contentss[$key] as $keyss=>$valuess){
									if($valuess == $i)
									{	
									}else{
										//echo "</br>";
									}
								}
								$temp2[$i] = array(
								'title' =>$values);
								$temp2[$i]['is_answer'] ='No';
								foreach($contentss[$key] as $keyss=>$valuess){
									if($valuess == $i){
										//echo "\n答案";
										$temp2[$i]['is_answer'] = 'Yes';
									}
								}
								$i++;
							}
							$temp22 = iserializer($temp2);
							$WJ_temp['content'] = $temp22;
						}elseif($ques_type[$key] == 3){	//问答题
							$anspoint =  $ques_anspoint[$key]; 
							$WJ_temp['content'] = $anspoint;	
						}
						pdo_insert($this->table_questions,$WJ_temp); 				
						$qorder = $qorder + 1 ;     
					}
				}
			}
            if($_GPC['type'] == 1){
				if($schooltype == true){
					header("location:".$this->createWebUrl('sendmsg_muti', array('notice_id' => $notice_id, 'schoolid' => $schoolid, 'weid' => $weid, 'tname' => $teacher['tname'], 'kc_id' => $_GPC['kc_id'],'type'=>1))); 
				}elseif($schooltype == false){
					header("location:".$this->createWebUrl('sendmsg_muti', array('notice_id' => $notice_id, 'schoolid' => $schoolid, 'weid' => $weid, 'tname' => $teacher['tname'], 'bj_id' => $bj_id,'type'=>1))); 
				}
              
            }
            if($_GPC['type'] == 2){
                 header("location:".$this->createWebUrl('sendmsg_muti', array('notice_id' => $notice_id, 'schoolid' => $schoolid, 'weid' => $weid, 'tname' => $teacher['tname'], 'groupid' => $groupid,'type'=>2))); 
            }
            if($_GPC['type'] == 3){
				if($schooltype == true){
					header("location:".$this->createWebUrl('sendmsg_muti', array('notice_id' => $notice_id, 'schoolid' => $schoolid, 'weid' => $weid, 'tname' => $teacher['tname'],'kc_id' => $_GPC['kc_id'],'type'=>3))); 
				}elseif($schooltype == false){
					header("location:".$this->createWebUrl('sendmsg_muti', array('notice_id' => $notice_id, 'schoolid' => $schoolid, 'weid' => $weid, 'tname' => $teacher['tname'], 'bj_id' => $bj_id,'type'=>3))); 	
				}
            }				
        }else{
            $this->imessage('发送失败,请联系管理员开启模板消息！', 'error');
        }
    }

}elseif($operation == 'delete1'){
    $id   = intval($_GPC['id']);
    $item = pdo_fetch("SELECT id,ali_vod_id FROM " . tablename($this->table_notice) . " WHERE id = '$id'");
    if(empty($item)){
        $this->imessage('抱歉，不存在或是已经被删除！', 'error');
    }
	if($item['ali_vod_id']){
		mload()->model('ali');
		$aliyun = GetAliApp($_W['uniacid'],$_GPC['schoolid']);
		$appid = $aliyun['alivodappid'];
		$key = $aliyun['alivodkey'];
		DelAlivod($appid,$key,$item['ali_vod_id']);
	}
    pdo_delete($this->table_notice, array('id' => $id));
    pdo_delete($this->table_record, array('noticeid' => $id));
    pdo_delete($this->table_questions, array('zyid' => $id));
    pdo_delete($this->table_answers, array('zyid' => $id));
    $this->imessage('删除成功！', referer(), 'success');
}elseif($operation == 'delete2'){
    $id   = intval($_GPC['id']);
    $item = pdo_fetch("SELECT * FROM " . tablename($this->table_leave) . " WHERE id = '$id'");
    if(empty($item)){
        $this->imessage('抱歉，不存在或是已经被删除！', $this->createWebUrl('notice', array('op' => 'display', 'schoolid' => $schoolid)), 'success');
    }
    if($item['isfrist'] == 1){
        $this->imessage('抱歉，您不能单独删除学生发起的首句对话，如需删除，请返回列表删除本学生的全部对话！', referer());
    }
    pdo_delete($this->table_leave, array('id' => $id));

    $this->imessage('删除成功！', referer(), 'success');
}elseif($operation == 'shenhe'){
    $id      = intval($_GPC['id']);
    $status  = intval($_GPC['status']);
    $fenlei  = intval($_GPC['fenlei']);
    $leave   = pdo_fetch("SELECT * FROM " . tablename($this->table_leave) . " WHERE id = :id ", array(':id' => $id));
    $bj      = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " WHERE sid = :sid ", array(':sid' => $leave['bj_id']));
    $teacher = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where id = :id ", array(':id' => $bj['tid']));

    $temp = array(
        'cltime' => time(),
        'status' => $status
    );
    pdo_update($this->table_leave, $temp, array('id' => $id));
    if($fenlei == 1){
	    $shname = '管理员';
        $this->sendMobileJsqjsh($id, $schoolid, $weid,$shname);
    }else{
        $this->sendMobileXsqjsh($id, $schoolid, $weid, $teacher['tname']);
        
    }
    $this->imessage('操作成功！', referer(), 'success');
}elseif($operation == 'deleteallrecord'){
    $rowcount    = 0;
    $notrowcount = 0;
    foreach($_GPC['idArr'] as $k => $id){
        $id = intval($id);
        if(!empty($id)){
            $item = pdo_fetch("SELECT * FROM " . tablename($this->table_record) . " WHERE id = :id", array(':id' => $id));
            if(empty($item)){
                $notrowcount++;
                continue;
            }
            pdo_delete($this->table_record, array('id' => $id));
            $rowcount++;
        }
    }
    $message = "操作成功！共删除{$rowcount}条数据,{$notrowcount}条数据不能删除!";

    $data ['result'] = true;

    $data ['msg'] = $message;

    die (json_encode($data));
}elseif($operation == 'deleteall'){
	$rowcount    = 0;
	$notrowcount = 0;
	foreach($_GPC['idArr'] as $k => $id){
		$id = intval($id);
		if(!empty($id)){
			$item = pdo_fetch("SELECT * FROM " . tablename($this->table_notice) . " WHERE id = :id", array(':id' => $id));
			if(empty($item)){
				$notrowcount++;
				continue;
			}
			pdo_delete($this->table_record, array('noticeid' => $id));
			pdo_delete($this->table_notice, array('id' => $id));
		  	pdo_delete($this->table_questions, array('zyid' => $id));
    		pdo_delete($this->table_answers, array('zyid' => $id));
			$rowcount++;
		}
	}
	$message = "操作成功！共删除{$rowcount}条数据,{$notrowcount}条数据不能删除!";

	$data ['result'] = true;

	$data ['msg'] = $message;

	die (json_encode($data));
}elseif($operation == 'clearall'){
   	$itemall = pdo_fetchall("SELECT id,noticeid,sid,tid,userid FROM " . tablename($this->table_record) . " WHERE schoolid = '{$schoolid}'");	
	$rowcount    = 0;
    foreach($itemall as $key => $row){
            $items = pdo_fetch("SELECT id FROM " . tablename($this->table_notice) . " WHERE id = :id", array(':id' => $row['noticeid']));
			$user = pdo_fetch("SELECT id FROM " . tablename($this->table_user) . " WHERE id = :id", array(':id' => $row['userid']));
			$teach = pdo_fetch("SELECT id FROM " . tablename($this->table_teachers) . " WHERE id = :id", array(':id' => $row['tid']));
			$stude = pdo_fetch("SELECT id FROM " . tablename($this->table_students) . " WHERE id = :id", array(':id' => $row['sid']));
            if(empty($items)){
				pdo_delete($this->table_record, array('id' => $row['id']));
                $rowcount++;
                continue;
            }
            if(empty($user)){
				pdo_delete($this->table_record, array('id' => $row['id']));
                $rowcount++;
                continue;
            }
            if($row['sid'] == 0 && empty($teach)){
				pdo_delete($this->table_record, array('id' => $row['id']));
                $rowcount++;
                continue;
            }
            if($row['tid'] == 0 && empty($stude)){
				pdo_delete($this->table_record, array('id' => $row['id']));
                $rowcount++;
                continue;
            }			
    }
	$this->imessage("操作成功！共删除{$rowcount}条阅读数据!", referer(), 'success');
}elseif($operation == 'deletely'){
    $id   = intval($_GPC['id']);
    $item = pdo_fetch("SELECT id FROM " . tablename($this->table_leave) . " WHERE id = '$id'");
    if(empty($item)){
        $this->imessage('抱歉，不存在或是已经被删除！', 'error');
    }
    pdo_delete($this->table_leave, array('leaveid' => $id));

    $this->imessage('删除成功！', referer(), 'success');
}elseif($operation == 'clear'){
   	$itemall = pdo_fetchall("SELECT id,noticeid,sid,tid,userid FROM " . tablename($this->table_record) . " WHERE schoolid = '{$schoolid}'");	
	$rowcount    = 0;
    foreach($itemall as $key => $row){
            $items = pdo_fetch("SELECT id FROM " . tablename($this->table_notice) . " WHERE id = :id", array(':id' => $row['noticeid']));
			$user = pdo_fetch("SELECT id FROM " . tablename($this->table_user) . " WHERE id = :id", array(':id' => $row['userid']));
			$teach = pdo_fetch("SELECT id FROM " . tablename($this->table_teachers) . " WHERE id = :id", array(':id' => $row['tid']));
			$stude = pdo_fetch("SELECT id FROM " . tablename($this->table_students) . " WHERE id = :id", array(':id' => $row['sid']));
            if(empty($items)){
				pdo_delete($this->table_record, array('id' => $row['id']));
                $rowcount++;
                continue;
            }
            if(empty($user)){
				pdo_delete($this->table_record, array('id' => $row['id']));
                $rowcount++;
                continue;
            }
            if($row['sid'] == 0 && empty($teach)){
				pdo_delete($this->table_record, array('id' => $row['id']));
                $rowcount++;
                continue;
            }
            if($row['tid'] == 0 && empty($stude)){
				pdo_delete($this->table_record, array('id' => $row['id']));
                $rowcount++;
                continue;
            }			
    }
	$this->imessage("操作成功！共删除{$rowcount}条阅读数据!", referer(), 'success');	
}
include $this->template('web/notice');
?>
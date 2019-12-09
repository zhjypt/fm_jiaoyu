<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
global $_GPC, $_W;

$weid = $_W['uniacid'];
$this1 = 'no1';
$action = 'start';
if($_W['os'] == 'mobile' && (!empty($_GPC['i']) || !empty($_SERVER['QUERY_STRING']))) {
	//$this->imessage('抱歉，请在电脑端打开本后台！', referer(), 'error');
}
$schoolid = $_GPC['schoolid'];
$GLOBALS['frames'] = $this->getNaveMenu($_GPC['schoolid'],$action);
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$logo = pdo_fetch("SELECT logo,title,is_cost,tpic,spic,issale FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}'");
$schooltype  = $_W['schooltype'];
$schooltypes = unitchecksctype();
if ($operation == 'display') {
	$nowdatatype = SchoolTypeFromLocal($schoolid,$weid);
	$rlsrll = $_W['siteroot'] . 'web/index.php?c=site&a=entry&schoolid=' . $schoolid . '&do=indexajax&op=changeschooltype&m=fm_jiaoyu';
    if(!empty($_GPC['addtime'])) {
        $starttime = strtotime($_GPC['addtime']['start']);
        $endtime = strtotime($_GPC['addtime']['end']) + 86399;
        $condition1 .= " AND createtime > '{$starttime}' AND createtime < '{$endtime}'";
        $condition5 .= " AND createtime > '{$_GPC['addtime']['start']}' AND createtime < '{$_GPC['addtime']['end']}'";
        $condition6 .= " AND createdate > '{$starttime}' AND createdate < '{$endtime}'";
        $condition7 .= " AND jiontime > '{$starttime}' AND jiontime < '{$endtime}'";
        $condition2 .= " AND paytime > '{$starttime}' AND paytime < '{$endtime}'";
    } else {
        $starttime = strtotime('-180 day');
        $endtime = TIMESTAMP;
    }

    $start = mktime(0,0,0,date("m"),date("d"),date("Y"));
    $end = $start + 86399;
    $condition3 .= " AND createtime > '{$start}' AND createtime < '{$end}'";
    $condition4 .= " AND paytime > '{$start}' AND paytime < '{$end}'";
    $params[':start'] = $starttime;
    $params[':end'] = $endtime;
    $bm = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_signup) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' ");
    $bjq = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_bjq) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' ");
    $kq = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_checklog) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' ");
    $dd = pdo_fetchall('SELECT SUM(cose) FROM ' . tablename($this->table_order) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND status = 2 ");

    $ddzj = $dd[0]['SUM(cose)'];
    $baom = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_signup) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' $condition3");
    $bjqz = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename($this->table_bjq) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' $condition3");
    $checklog = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_checklog) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' And isconfirm = 1 $condition3");
    $cost = pdo_fetchall('SELECT SUM(cose) FROM ' . tablename($this->table_order) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND status = 2 $condition4");
    $cose = $cost[0]['SUM(cose)'];
    $ybjs = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_user) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND sid = 0 $condition1");
    $ybxs = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_user) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND tid = 0 $condition1");
	if (!$_W['schooltype']){
		$baomzj = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_signup) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' $condition1");
	}else{
		$baomzj = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_order) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' and type = 1 and status = 2 AND paytime > '{$starttime}' AND paytime < '{$endtime}'");
	} 
    
    $bjqzj = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_bjq) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' $condition1");
	if (!$_W['schooltype']){
		 $checklogzj = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_checklog) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' $condition1");
	}else{
		 $checklogzj = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_kcsign) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' and status = 2 and tid = 0 and sid != 0 and kcid != 0  $condition1");
	} 
   
    $xczj = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_media) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' $condition1");
    $jszj = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_teachers) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' $condition7");
	$allstu  = pdo_fetchall("select id,keyid FROM ".tablename($this->table_students)." WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' $condition6 AND (stheendtime >='{$endtime}' or stheendtime = 0) ");
	$allstuGuoqi  = pdo_fetchall("select id,keyid FROM ".tablename($this->table_students)." WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' $condition6 AND stheendtime < '{$endtime}' AND stheendtime != 0 ");
 	$todayKc = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_kcbiao) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}'  AND date > '{$start}' AND date < '{$end}'");
 	$allKc = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_kcbiao) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}'");
 	$todaySign = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_kcsign) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' $condition3 ");
 	$allSign = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_kcsign) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}'");
 	$todayBuy = pdo_fetchcolumn('SELECT COUNT(distinct sid ) FROM ' . tablename($this->table_order) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND type =1 AND status = 2 $condition3 ");
 	$allBuy = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_order) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND type =1 AND status = 2 ");
 	$todayStu_temp = pdo_fetchall("select id,keyid FROM ".tablename($this->table_students)." WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND createdate > '{$start}' AND createdate < '{$end}'");
 	$allstu_lee_temp = pdo_fetchall("select id,keyid FROM ".tablename($this->table_students)." WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' ");
	$todayStu = 0 ;
 	foreach($todayStu_temp as $val){
		if($val['keyid'] == 0){
			$todayStu++;
		}
		if($val['keyid'] == $val['id']){
			$todayStu++;
		}				
	}
	$allstu_lee = 0 ;
	foreach($allstu_lee_temp as $val){
		if($val['keyid'] == 0){
			$allstu_lee++;
		}
		if($val['keyid'] == $val['id']){
			$allstu_lee++;
		}				
	}
	$xszj = 0;
	foreach($allstu as $val){
		if($val['keyid'] == 0){
			$xszj++;
		}
		if($val['keyid'] == $val['id']){
			$xszj++;
		}				
	}

	$xszjGuoqi = 0;
	foreach($allstuGuoqi as $val){
		if($val['keyid'] == 0){
			$xszjGuoqi++;
		}
		if($val['keyid'] == $val['id']){
			$xszjGuoqi++;
		}				
	}	
    $cost1 = pdo_fetchall('SELECT SUM(cose) FROM ' . tablename($this->table_order) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND status = 2 $condition2");
    $cose1 = $cost1[0]['SUM(cose)'];
    $count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_order) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}'  $condition2 ");
    $data = pdo_fetchall('SELECT * FROM ' . tablename($this->table_order) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' $condition2 ORDER BY paytime DESC LIMIT 0,50");
    $total = array();
    if(!empty($data)) {
        foreach($data as &$da) {
            $total_price = $da['cose'];
            $ky = date('Y-m-d', $da['paytime']);
            $return[$ky]['cose'] += $total_price;
            $return[$ky]['count'] += 1;
            $total['total_price'] += $total_price;
            $total['total_count'] += 1;
            if($da['paytype'] == '1') {
                $return[$ky]['1'] += $total_price;
                $total['total_alipay'] += $total_price;
            } elseif($da['paytype'] == '2') {
                $return[$ky]['2'] += $total_price;
                $total['total_wechat'] += $total_price;
            }
        }
    }
	
    $lastbjq = pdo_fetchall("SELECT uid,shername,createtime,content,isopen,bj_id1,msgtype FROM " . tablename($this->table_bjq) . " where schoolid = '{$schoolid}' And weid = '{$weid}' And type = 0 ORDER BY createtime DESC LIMIT 0,10");
    foreach ($lastbjq as $index => $row) {
        $member = pdo_fetch("SELECT avatar FROM " . tablename ( 'mc_members' ) . " where uniacid = :uniacid And uid = :uid ORDER BY uid ASC", array(':uniacid' => $weid, ':uid' => $row['uid']));
        $bj = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = :sid", array(':sid' => $row['bj_id1']));
        $lastbjq[$index]['bjname'] = $bj['sname'];
        $lastbjq[$index]['avatar'] = $member['avatar'];
        $lastbjq[$index]['time'] = sub_day($row['createtime']);
    }
    $lasttz = pdo_fetchall("SELECT * FROM ".tablename($this->table_notice)." WHERE weid = '{$weid}' And schoolid = '{$schoolid}' ORDER BY createtime DESC LIMIT 0,10");
    foreach($lasttz as $key => $row){
        $bj = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = :sid", array(':sid' => $row['bj_id']));
        $ls = pdo_fetch("SELECT thumb FROM " . tablename($this->table_teachers) . " WHERE id = :id", array(':id' => $row['tid']));
        $lasttz[$key]['bjname'] = $bj['sname'];
        $lasttz[$key]['thumb'] = $ls['thumb'];
        $lasttz[$key]['time'] = sub_day($row['createtime']);
    }
	if($schooltype){
		$lastxk = pdo_fetchall("SELECT * FROM " . tablename($this->table_kcsign) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' and sid != 0 and tid = 0 ORDER BY createtime DESC LIMIT 0,10");
		foreach($lastxk as $index => $row){
			$student = pdo_fetch("SELECT s_name,icon FROM " . tablename($this->table_students) . " WHERE id = '{$row['sid']}' ");
			$kcinfo = pdo_fetch("SELECT name FROM " . tablename($this->table_tcourse) . " WHERE id = '{$row['kcid']}' ");
			$lastxk[$index]['s_name'] = $student['s_name'];
			$lastxk[$index]['sicon'] = $student['icon'];
			$lastxk[$index]['kcname'] = $kcinfo['name'];
			$lastxk[$index]['time'] = sub_day($row['createtime']);
		}
	}else{ 
		$lastkq = pdo_fetchall("SELECT * FROM " . tablename($this->table_checklog) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' ORDER BY createtime DESC LIMIT 0,10");
		foreach($lastkq as $index =>$row){
			$student = pdo_fetch("SELECT s_name,icon FROM " . tablename($this->table_students) . " WHERE id = '{$row['sid']}' ");
			$teacher = pdo_fetch("SELECT tname FROM " . tablename($this->table_teachers) . " WHERE id = '{$row['tid']}' ");
			$qdtid = pdo_fetch("SELECT tname,thumb FROM " . tablename($this->table_teachers) . " WHERE id = '{$row['qdtid']}' ");
			$idcard = pdo_fetch("SELECT pname FROM " . tablename($this->table_idcard) . " WHERE idcard = '{$row['cardid']}' ");
			$mac = pdo_fetch("SELECT name FROM " . tablename($this->table_checkmac) . " WHERE schoolid = '{$row['schoolid']}' And id = '{$row['macid']}' ");
			$banji = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$row['bj_id']}' ");
			$lastkq[$index]['s_name'] = $student['s_name'];
			$lastkq[$index]['sicon'] = $student['icon'];
			$lastkq[$index]['tname'] = $teacher['tname'];
			$lastkq[$index]['thumb'] = $teacher['thumb'];
			$lastkq[$index]['qdtname'] = $qdtid['tname'];
			$lastkq[$index]['mac'] = $mac['name'];
			$lastkq[$index]['pname'] = $idcard['pname'];
			$lastkq[$index]['bj_name'] = $banji['sname'];
			$lastkq[$index]['time'] = sub_day($row['createtime']);
		}
	}
	if(!empty($_GPC['addtime'])) {
		$starttime1 = strtotime($_GPC['addtime']['start']);
		$endtime1 = strtotime($_GPC['addtime']['end']) + 86399;
		$day = timediff($starttime1,$endtime1);
		$day_num =  $day['day']+1;
		$condition9 .= " AND createtime > '{$starttime1}' AND createtime < '{$endtime1}'";
		$condition8 .= " AND ( (startime1 < '{$starttime1}' AND endtime1 > '{$endtime1}') OR ( startime1 > '{$starttime1}' AND startime1 < '{$endtime1}') OR ( endtime1 > '{$starttime1}' AND endtime1 < '{$endtime1}'))";
	} else {
		$condition9 .= " AND createtime > '{$start}' AND createtime < '{$end}'";
		$condition8 .= " AND ( (startime1 < '{$start}' AND endtime1 > '{$end}') OR ( startime1 > '{$start}' AND startime1 < '{$end}') OR ( endtime1 > '{$start}' AND endtime1 < '{$end}'))";
	}
	//培训机构，按课程类型统计
	if(GetSchoolType($schoolid,$weid)){
		$njchecklog = pdo_fetchall("SELECT sid,sname FROM " . tablename($this->table_classify) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND type = 'coursetype' ORDER BY ssort DESC ,sid DESC ");
		array_unshift($njchecklog,array('sid'=>0,'sname'=>'默认类型'));
		if($day_num){
			$days = array();
			$daykey = array();
			for($i = 0; $i < $day_num; $i++){
				$keys = date('Y-m-d', $starttime + 86400 * $i);
				$days[$keys] = 0;
				$daykey[$keys] = 0;
			}
		
			foreach($njchecklog as $key =>$row){
				$njchecklog[$key]['NeedSignNum']  = 0;
				$njchecklog[$key]['DoSignNum'] = 0;
				$njchecklog[$key]['QingJiaNum'] = 0;
				$njchecklog[$key]['ksnum'] = 0;
				$bjqksm = 0;
				foreach($daykey as $key_d=>$value_d){
					$start_d = strtotime($key_d);
					$end_d = $start_d + 86399;
					//var_dump($key_d);
					//var_dump($start_d);
					$allthisbj = pdo_fetchall("SELECT id,name,OldOrNew FROM " . tablename($this->table_tcourse) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND Ctype = '{$row['sid']}' AND ( (start <= '{$start_d}' AND end >= '{$end_d}') OR ( start >= '{$start_d}' AND start <= '{$end_d}') OR ( end >= '{$start_d}' AND end <= '{$end_d}'))  ORDER BY ssort DESC ,id DESC ");
					
				
					
					foreach($allthisbj as $index => $v){
						$stuNum = pdo_fetchcolumn("SELECT COUNT(distinct sid ) FROM " . tablename($this->table_order) . " WHERE schoolid = '{$schoolid}' and weid='{$weid}' and kcid = '{$v['id']}' and type =1 and status = 2 and paytime < '{$end_d}' ");
						
						if($v['OldOrNew'] == 0){
							$ksSum = pdo_fetchall("SELECT  id  FROM " . tablename($this->table_kcbiao) . " WHERE schoolid = '{$schoolid}' and weid='{$weid}' and kcid = '{$v['id']}' and date >= '{$start_d}' and date <= '{$end_d}' ");
							$njchecklog[$key]['NeedSignNum'] += $stuNum * count($ksSum);
							$njchecklog[$key]['ksnum'] += count($ksSum);
							foreach($ksSum as $key_k=>$value_k){
								$stuSign = pdo_fetchcolumn("SELECT  COUNT(distinct sid )  FROM " . tablename($this->table_kcsign) . " WHERE schoolid = '{$schoolid}' and tid = 0 and weid='{$weid}' and kcid='{$v['id']}' and  ksid = '{$value_k['id']}' and status = 2 ");
								$njchecklog[$key]['DoSignNum'] += $stuSign;
								$stuQingJia = pdo_fetchcolumn("SELECT  COUNT(distinct sid )  FROM " . tablename($this->table_kcsign) . " WHERE schoolid = '{$schoolid}' and weid='{$weid}' and kcid='{$v['id']}' and  ksid = '{$value_k['id']}' and status = 3 ");
								$njchecklog[$key]['QingJiaNum'] += $stuQingJia;
								
							}
						}elseif($v['OldOrNew'] == 1){
							$ksSum = pdo_fetchcolumn("SELECT  count(1)  FROM " . tablename($this->table_kcsign) . " WHERE schoolid = '{$schoolid}' and weid='{$weid}' and kcid = '{$v['id']}' and sid = 0 and tid != 0 and createtime >= '{$start_d}' and createtime <= '{$end_d}' and status = 2 ");
							
							$njchecklog[$key]['NeedSignNum'] += $stuNum * $ksSum;
							$njchecklog[$key]['ksnum'] += $ksSum;
							$stuSign = pdo_fetchall("SELECT * FROM " . tablename($this->table_kcsign) . " AS a WHERE (SELECT COUNT(*) FROM " . tablename($this->table_kcsign) . " AS b  WHERE b.sid = a.sid and b.createtime >= '{$start_d}' and b.createtime <= '{$end_d}' and b.status = 2 and b.schoolid = '{$schoolid}' and b.weid='{$weid}' and b.kcid = '{$v['id']}' ) <= '{$ksSum}' and a.createtime >= '{$start_d}' and a.createtime <= '{$end_d}' and a.status = 2 and a.schoolid = '{$schoolid}' and a.weid='{$weid}' and a.kcid = '{$v['id']}' ORDER BY a.sid ASC  ");
							//var_dump($stuSign);
							$njchecklog[$key]['DoSignNum'] += count($stuSign);
							$stuQingJia = pdo_fetchall("SELECT a.sid FROM " . tablename($this->table_kcsign) . " AS a WHERE (SELECT COUNT(*) FROM " . tablename($this->table_kcsign) . " AS b  WHERE b.sid = a.sid and b.createtime >= '{$start_d}' and b.createtime <= '{$end_d}' and status = 3 and b.schoolid = '{$schoolid}' and b.weid='{$weid}' and b.kcid = '{$v['id']}' ) <= '{$ksSum}' and a.createtime >= '{$start_d}' and a.createtime <= '{$end_d}' and a.status = 3 and a.schoolid = '{$schoolid}' and a.weid='{$weid}' and a.kcid = '{$v['id']}'  ORDER BY a.sid ASC  ");
							$njchecklog[$key]['QingJiaNum'] += count($stuQingJia); 
						}
			

					}
					//缺勤人数
					$njchecklog[$key]['NotSignNum'] = ($njchecklog[$key]['NeedSignNum'] - $njchecklog[$key]['DoSignNum'] - $njchecklog[$key]['QingJiaNum'])>0?$njchecklog[$key]['NeedSignNum'] - $njchecklog[$key]['DoSignNum'] - $njchecklog[$key]['QingJiaNum']:0 ;
				}
			}
			
		}else{
			foreach($njchecklog as $key =>$row){
				$allthisbj = pdo_fetchall("SELECT id,name,OldOrNew FROM " . tablename($this->table_tcourse) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND Ctype = '{$row['sid']}' AND ( (start <= '{$start}' AND end >= '{$end}') OR ( start >= '{$start}' AND start <= '{$end}') OR ( end >= '{$start}' AND end <= '{$end}'))  ORDER BY ssort DESC ,id DESC ");
				$njchecklog[$key]['NeedSignNum']  = 0;
				$njchecklog[$key]['DoSignNum'] = 0;
				$njchecklog[$key]['QingJiaNum'] = 0;
				$njchecklog[$key]['ksnum'] = 0;
			
				$bjqksm = 0;
				foreach($allthisbj as $index => $v){
					$stuNum = pdo_fetchcolumn("SELECT COUNT(distinct sid ) FROM " . tablename($this->table_order) . " WHERE schoolid = '{$schoolid}' and weid='{$weid}' and kcid = '{$v['id']}' and type =1 and status = 2 ");
					
					if($v['OldOrNew'] == 0){
						$ksSum = pdo_fetchall("SELECT  id  FROM " . tablename($this->table_kcbiao) . " WHERE schoolid = '{$schoolid}' and weid='{$weid}' and kcid = '{$v['id']}' and date >= '{$start}' and date <= '{$end}' ");
						$njchecklog[$key]['NeedSignNum'] += $stuNum * count($ksSum);
						$njchecklog[$key]['ksnum'] += count($ksSum);
						foreach($ksSum as $key_k=>$value_k){
							$stuSign = pdo_fetchcolumn("SELECT  COUNT(distinct sid )  FROM " . tablename($this->table_kcsign) . " WHERE schoolid = '{$schoolid}' and weid='{$weid}' and kcid='{$v['id']}' and  ksid = '{$value_k['id']}' and status = 2 ");
							$njchecklog[$key]['DoSignNum'] += $stuSign;
							$stuQingJia = pdo_fetchcolumn("SELECT  COUNT(distinct sid )  FROM " . tablename($this->table_kcsign) . " WHERE schoolid = '{$schoolid}' and weid='{$weid}' and kcid='{$v['id']}' and  ksid = '{$value_k['id']}'  and status = 3 ");
							$njchecklog[$key]['QingJiaNum'] += $stuQingJia;
							
						}
					}elseif($v['OldOrNew'] == 1){
						$ksSum = pdo_fetchcolumn("SELECT  count(1)  FROM " . tablename($this->table_kcsign) . " WHERE schoolid = '{$schoolid}' and weid='{$weid}' and kcid = '{$v['id']}' and sid = 0 and tid != 0 and createtime >= '{$start}' and createtime <= '{$end}' and status = 2 ");
						$njchecklog[$key]['NeedSignNum'] += $stuNum * $ksSum;
						$njchecklog[$key]['ksnum'] += $ksSum;
					 	$stuSign = pdo_fetchall("SELECT * FROM " . tablename($this->table_kcsign) . " AS a WHERE (SELECT COUNT(*) FROM " . tablename($this->table_kcsign) . " AS b  WHERE b.sid = a.sid and b.createtime >= '{$start}' and b.createtime <= '{$end}' and b.status = 2 and b.schoolid = '{$schoolid}' and b.weid='{$weid}' and b.kcid = '{$v['id']}' ) <= '{$ksSum}' and a.createtime >= '{$start}' and a.createtime <= '{$end}' and a.status = 2 and a.schoolid = '{$schoolid}' and a.weid='{$weid}' and a.kcid = '{$v['id']}' ORDER BY a.sid ASC  ");
						//var_dump($stuSign);
						$njchecklog[$key]['DoSignNum'] += count($stuSign);
						$stuQingJia = pdo_fetchall("SELECT a.sid FROM " . tablename($this->table_kcsign) . " AS a WHERE (SELECT COUNT(*) FROM " . tablename($this->table_kcsign) . " AS b  WHERE b.sid = a.sid and b.createtime >= '{$start}' and b.createtime <= '{$end}' and status = 3 and b.schoolid = '{$schoolid}' and b.weid='{$weid}' and b.kcid = '{$v['id']}' ) <= '{$ksSum}' and a.createtime >= '{$start}' and a.createtime <= '{$end}' and a.status = 3 and a.schoolid = '{$schoolid}' and a.weid='{$weid}' and a.kcid = '{$v['id']}'  ORDER BY a.sid ASC  ");
						$njchecklog[$key]['QingJiaNum'] += count($stuQingJia); 
					}
		

				}
				//缺勤人数
				$njchecklog[$key]['NotSignNum'] = ($njchecklog[$key]['NeedSignNum'] - $njchecklog[$key]['DoSignNum'] - $njchecklog[$key]['QingJiaNum'] )>0?$njchecklog[$key]['NeedSignNum'] - $njchecklog[$key]['DoSignNum'] - $njchecklog[$key]['QingJiaNum'] :0;
			}
		}
	}else{
		/**各年级出勤情况**/
		$njchecklog = pdo_fetchall("SELECT sid,sname FROM " . tablename($this->table_classify) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND type = 'semester' AND is_over = 1 ORDER BY ssort DESC ,sid DESC ");
	
		if($day_num){
			$days = array();
			$daykey = array();
			for($i = 0; $i < $day_num; $i++){
				$keys = date('Y-m-d', $starttime + 86400 * $i);
				$days[$keys] = 0;
				$daykey[$keys] = 0;
			}
			foreach($njchecklog as $key =>$row){
				$njzrs = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_students) . " WHERE schoolid = :schoolid And xq_id = :xq_id", array(':schoolid' => $schoolid, ':xq_id' => $row['sid']));
				$njchecklog[$key]['njzrs'] = $njzrs;
				$njchecklog[$key]['njcqzs'] = 0;
				$njchecklog[$key]['njqjrs'] = 0;
				$allthisbj = pdo_fetchall("SELECT sid FROM " . tablename($this->table_classify) . " WHERE parentid = '{$row['sid']}' AND is_over = 1 ");
				$bjqksm = 0;
				foreach($allthisbj as $index => $v){
					$allbjqksm = pdo_fetchall("SELECT sid,createtime FROM " . tablename($this->table_checklog) . " WHERE bj_id = '{$v['sid']}' AND leixing = 1 AND isconfirm = 1  $condition9 ");
					foreach($allbjqksm as $da) {
						$k = date('Y-m-d', $da['createtime']);
						if(in_array($k, array_keys($days))) {
							if(!in_array($da['sid'], $daykey[$k][$key][$index])) {
								$daykey[$k][$key][$index] = $da['sid'];
								$njchecklog[$key]['njcqzs']++;
							}
						}
					}
					
					
					$bjqjsm = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_leave) . " WHERE bj_id = '{$v['sid']}' And isliuyan = 0 $condition8 ");
					$njchecklog[$key]['njqjrs'] =  $njchecklog[$key]['njqjrs'] + $bjqjsm;
				}
				$njchecklog[$key]['qqzrs'] = $njzrs*$day_num - $njchecklog[$key]['njcqzs'];
			}
		}else{
			foreach($njchecklog as $key =>$row){
				$njzrs = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_students) . " WHERE schoolid = :schoolid And xq_id = :xq_id", array(':schoolid' => $schoolid, ':xq_id' => $row['sid']));
				$njchecklog[$key]['njzrs'] = $njzrs;
				$njchecklog[$key]['njcqzs'] = 0;
				$njchecklog[$key]['njqjrs'] = 0;
				$allthisbj = pdo_fetchall("SELECT sid FROM " . tablename($this->table_classify) . " WHERE parentid = '{$row['sid']}' AND is_over = 1 ");
				//var_dump($allthisbj);
				foreach($allthisbj as $index => $v){
					$bjqksm = pdo_fetchcolumn("SELECT COUNT(distinct sid) FROM " . tablename($this->table_checklog) . " WHERE bj_id = '{$v['sid']}' AND leixing = 1 AND isconfirm = 1 and sc_ap = 0 and sid != 0  $condition9 ");
					$bjqjsm = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_leave) . " WHERE bj_id = '{$v['sid']}' And isliuyan = 0 $condition8 ");
					$njchecklog[$key]['njcqzs'] =  $njchecklog[$key]['njcqzs'] + $bjqksm;
					$njchecklog[$key]['njqjrs'] =  $njchecklog[$key]['njqjrs'] + $bjqjsm;
				}
				$njchecklog[$key]['qqzrs'] = $njzrs - $njchecklog[$key]['njcqzs'] - $njchecklog[$key]['njqjrs'];
			}
		}
	}
	/**end**/
    include $this->template ( 'web/start' );
}
/**各课程情况**/
if($operation == 'd') {
		$kcid = $_GPC['kcid'];
		$kcinfo = pdo_fetch("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE schoolid = :schoolid And id = :id", array(':schoolid' => $schoolid, ':id' => $kcid));
		$start = mktime(0,0,0,date("m"),date("d"),date("Y"));
		$end = $start + 86399;
		if(!empty($_GPC['addtime'])) {
			$starttime = strtotime($_GPC['start']);
			//var_dump($starttime);
			$endtime = strtotime($_GPC['end']) + 86399;
			if($kcinfo['start'] >$starttime && $kcinfo['start']<$endtime){
				$starttime = $kcinfo['start'];
			}
			if($kcinfo['end'] >$starttime && $kcinfo['end']<$endtime){
				$endtime = $kcinfo['end'];
			}
			//var_dump($kcinfo['start']);
			$condition3 .= " AND createtime > '{$starttime}' AND createtime < '{$endtime}'";
			$day = timediff($starttime,$endtime);
			$day_num =  $day['day']+1;
		} else {
			$condition3 .= " AND createtime > '{$start}' AND createtime < '{$end}'";
			$condition5 .= " AND ( (startime1 < '{$start}' AND endtime1 > '{$end}') OR ( startime1 > '{$start}' AND startime1 < '{$end}') OR ( endtime1 > '{$start}' AND endtime1 < '{$end}'))";
		}
		//var_dump($starttime);
		$NeedSignKc = 0;
		$DoSignKc = 0;
		$QingJiaKc = 0 ;
		$numOfks = 0 ;
		$numofPay = 0 ;
		$numofPrice = 0 ;
		
		if($day_num){
			$days = array();
			$daykey = array();
			for($i = 0; $i < $day_num; $i++){
				$keys = date('Y-m-d', $starttime + 86400 * $i);
				$days[$keys] = 0;
				$daykey[$keys] = 0;
			}
		//var_dump($daykey);
			foreach($daykey as $key_d=>$value_d){
				$start_d = strtotime($key_d);
				$end_d = $start_d + 86399;
				//var_dump($key_d);
				//var_dump($start_d);
		
				$stuNum = pdo_fetchcolumn("SELECT COUNT(distinct sid ) FROM " . tablename($this->table_order) . " WHERE schoolid = '{$schoolid}' and weid='{$weid}' and kcid = '{$kcid}' and type =1 and status = 2 ");
				$newPay = pdo_fetchcolumn("SELECT count(*)  FROM " . tablename($this->table_order) . " WHERE schoolid = '{$schoolid}' and weid='{$weid}' and kcid='{$kcid}' and type = 1 and status = 2 and tid = 0 and sid != 0  and paytime >= '{$start_d}' and paytime <= '{$end_d}' ");
				//var_dump($newPay);
				$newprice =  pdo_fetch("SELECT  sum(cose) as cose  FROM " . tablename($this->table_order) . " WHERE schoolid = '{$schoolid}' and weid='{$weid}' and kcid='{$kcid}' and status = 2 and type = 1 and paytime > '{$start_d}' and paytime < '{$end_d}' ");
				if($kcinfo['OldOrNew'] == 0){
					$ksSum = pdo_fetchall("SELECT  id  FROM " . tablename($this->table_kcbiao) . " WHERE schoolid = '{$schoolid}' and weid='{$weid}' and kcid = '{$kcid}' and date > '{$start_d}' and date < '{$end_d}' ");
					$numOfks += count($ksSum);
					$NeedSignKc += count($ksSum)* $stuNum;
					foreach($ksSum as $key_k=>$value_k){
						$stuSign = pdo_fetchcolumn("SELECT  COUNT(distinct sid )  FROM " . tablename($this->table_kcsign) . " WHERE schoolid = '{$schoolid}' and weid='{$weid}' and kcid='{$kcid}' and  ksid = '{$value_k['id']}' and status = 2 ");
						$DoSignKc += $stuSign;
						$stuQingJia = pdo_fetchcolumn("SELECT  COUNT(distinct sid )  FROM " . tablename($this->table_kcsign) . " WHERE schoolid = '{$schoolid}' and weid='{$weid}' and kcid='{$kcid}' and  ksid = '{$value_k['id']}'  and status = 3 ");
						$QingJiaKc += $stuQingJia;
						
					}
				}elseif($kcinfo['OldOrNew'] == 1){
					$ksSum = pdo_fetchcolumn("SELECT  count(1)  FROM " . tablename($this->table_kcsign) . " WHERE schoolid = '{$schoolid}' and weid='{$weid}' and kcid = '{$kcid}' and sid = 0 and tid != 0 and createtime > '{$start_d}' and createtime < '{$end_d}' and status = 2 ");
					$numOfks += $ksSum;
						/*  if($kcid == 205){
									var_dump($v['id']);
									var_dump($key_d);
									var_dump($ksSum);
								}   */
					$NeedSignKc += $ksSum* $stuNum;
					$stuSign = pdo_fetchall("SELECT * FROM " . tablename($this->table_kcsign) . " AS a WHERE (SELECT COUNT(*) FROM " . tablename($this->table_kcsign) . " AS b  WHERE b.sid = a.sid and b.createtime > '{$start_d}' and b.createtime < '{$end_d}' and b.status = 2 and b.schoolid = '{$schoolid}' and b.weid='{$weid}' and b.kcid = '{$kcid}' ) <= '{$ksSum}' and a.createtime > '{$start_d}' and a.createtime < '{$end_d}' and a.status = 2 and a.schoolid = '{$schoolid}' and a.weid='{$weid}' and a.kcid = '{$kcid}' ORDER BY a.sid ASC  ");
							//var_dump($stuSign);
					$DoSignKc += count($stuSign);
					$stuQingJia = pdo_fetchall("SELECT a.sid FROM " . tablename($this->table_kcsign) . " AS a WHERE (SELECT COUNT(*) FROM " . tablename($this->table_kcsign) . " AS b  WHERE b.sid = a.sid and b.createtime > '{$start_d}' and b.createtime < '{$end_d}' and status = 3 and b.schoolid = '{$schoolid}' and b.weid='{$weid}' and b.kcid = '{$kcid}' ) <= '{$ksSum}' and a.createtime > '{$start_d}' and a.createtime < '{$end_d}' and a.status = 3 and a.schoolid = '{$schoolid}' and a.weid='{$weid}' and a.kcid = '{$kcid}'  ORDER BY a.sid ASC  ");
					$QingJiaKc +=  count($stuQingJia); 
				}
				$numofPay += $newPay;
				$numofPrice += $newprice['cose']?$newprice['cose']:0; 
			
			}
		}else{
			$kcinfo = pdo_fetch("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE schoolid = :schoolid And id = :id", array(':schoolid' => $schoolid, ':id' => $kcid));
			$stuNum = pdo_fetchcolumn("SELECT COUNT(distinct sid ) FROM " . tablename($this->table_order) . " WHERE schoolid = '{$schoolid}' and weid='{$weid}' and kcid = '{$kcid}' and type =1 and status = 2 ");
			$newPay = pdo_fetchcolumn("SELECT  COUNT(distinct sid )  FROM " . tablename($this->table_coursebuy) . " WHERE schoolid = '{$schoolid}' and weid='{$weid}' and kcid='{$kcid}' and createtime > '{$start}' and createtime < '{$end}' ");
			$newprice =  pdo_fetch("SELECT  sum(cose) as cose  FROM " . tablename($this->table_order) . " WHERE schoolid = '{$schoolid}' and weid='{$weid}' and kcid='{$kcid}' and status = 2 and type = 1 and paytime > '{$start}' and paytime < '{$end}' ");
			if($kcinfo['OldOrNew'] == 0){
				$ksSum = pdo_fetchall("SELECT  id  FROM " . tablename($this->table_kcbiao) . " WHERE schoolid = '{$schoolid}' and weid='{$weid}' and kcid = '{$kcid}' and date > '{$start}' and date < '{$end}' ");
				$numOfks = count($ksSum);
				$NeedSignKc = count($ksSum)* $stuNum;
				foreach($ksSum as $key_k=>$value_k){
						$stuSign = pdo_fetchcolumn("SELECT  COUNT(distinct sid )  FROM " . tablename($this->table_kcsign) . " WHERE schoolid = '{$schoolid}' and weid='{$weid}' and kcid='{$kcid}' and  ksid = '{$value_k['id']}' and status = 2 ");
						$DoSignKc += $stuSign;
						$stuQingJia = pdo_fetchcolumn("SELECT  COUNT(distinct sid )  FROM " . tablename($this->table_kcsign) . " WHERE schoolid = '{$schoolid}' and weid='{$weid}' and kcid='{$kcid}' and  ksid = '{$value_k['id']}'  and status = 3 ");
						$QingJiaKc += $stuQingJia;
						
					}
			}elseif($kcinfo['OldOrNew'] == 1){
				$ksSum = pdo_fetchcolumn("SELECT  count(1)  FROM " . tablename($this->table_kcsign) . " WHERE schoolid = '{$schoolid}' and weid='{$weid}' and kcid = '{$kcid}' and sid = 0 and tid != 0 and createtime > '{$start}' and createtime < '{$end}' and status = 2 ");
				$numOfks = $ksSum;
				$NeedSignKc = $ksSum* $stuNum;
				$stuSign = pdo_fetchall("SELECT * FROM " . tablename($this->table_kcsign) . " AS a WHERE (SELECT COUNT(*) FROM " . tablename($this->table_kcsign) . " AS b  WHERE b.sid = a.sid and b.createtime > '{$start}' and b.createtime < '{$end}' and b.status = 2 and b.schoolid = '{$schoolid}' and b.weid='{$weid}' and b.kcid = '{$kcid}' ) <= '{$ksSum}' and a.createtime > '{$start}' and a.createtime < '{$end}' and a.status = 2 and a.schoolid = '{$schoolid}' and a.weid='{$weid}' and a.kcid = '{$kcid}' ORDER BY a.sid ASC  ");
						//var_dump($stuSign);
				$DoSignKc += count($stuSign);
				$stuQingJia = pdo_fetchall("SELECT a.sid FROM " . tablename($this->table_kcsign) . " AS a WHERE (SELECT COUNT(*) FROM " . tablename($this->table_kcsign) . " AS b  WHERE b.sid = a.sid and b.createtime > '{$start}' and b.createtime < '{$end}' and status = 3 and b.schoolid = '{$schoolid}' and b.weid='{$weid}' and b.kcid = '{$kcid}' ) <= '{$ksSum}' and a.createtime > '{$start}' and a.createtime < '{$end}' and a.status = 3 and a.schoolid = '{$schoolid}' and a.weid='{$weid}' and a.kcid = '{$kcid}'  ORDER BY a.sid ASC  ");
				$QingJiaKc +=  count($stuQingJia); 
			}
			$numofPay = intval($newPay);
			$numofPrice = $newprice['cose']?$newprice['cose']:0; 

		}
		 	$data['allthisbj'][] = ' ';
			$data['ksnum'][] =$numOfks ;
			$data['dosign'][] = $DoSignKc;
			$data['qingjia'][] = $QingJiaKc;
			$data['notsign'][] = ($NeedSignKc - $DoSignKc - $QingJiaKc)>0?$NeedSignKc - $DoSignKc - $QingJiaKc:0;
			$data['newPay'][] = $numofPay;
			$data['newcose'][] = $numofPrice;
		die ( json_encode ( $data ) );
	
}
/**end**/
/**各班出勤情况**/
if($operation == 'c') {
	if($_GPC['njid']) {
		$njid = $_GPC['njid'];
	} else {
		$frnjid = pdo_fetch("SELECT sid FROM " . tablename($this->table_classify) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND type = 'semester' AND is_over = 1 ORDER BY ssort DESC ,sid DESC ");
		$njid = $frnjid['sid'];
	}
	$start = mktime(0,0,0,date("m"),date("d"),date("Y"));
	$end = $start + 86399;
	if(!empty($_GPC['addtime'])) {
		$starttime = strtotime($_GPC['start']);
		$endtime = strtotime($_GPC['end']) + 86399;
		$condition3 .= " AND createtime > '{$starttime}' AND createtime < '{$endtime}'";
		$day = timediff($starttime,$endtime);
		$day_num =  $day['day']+1;
	} else {
		$condition3 .= " AND createtime > '{$start}' AND createtime < '{$end}'";
		$condition5 .= " AND ( (startime1 < '{$start}' AND endtime1 > '{$end}') OR ( startime1 > '{$start}' AND startime1 < '{$end}') OR ( endtime1 > '{$start}' AND endtime1 < '{$end}'))";
	}
	
	$allthisbj = pdo_fetchall("SELECT sid,sname FROM " . tablename($this->table_classify) . " WHERE parentid = '{$njid}' ORDER BY ssort DESC ,sid DESC ");
	$allthisbjsname = array();
	$njcqzssss = array();
	$bjkqbl = array();
	$bjzrss = array();
	if($day_num){
		$days = array();
		$daykey = array();
		for($i = 0; $i < $day_num; $i++){
			$keys = date('Y-m-d', $starttime + 86400 * $i);
			$days[$keys] = 0;
			$daykey[$keys] = 0;
		}
		foreach($allthisbj as $index => $v){
			$bjzrs = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_students) . " WHERE schoolid = :schoolid And bj_id = :bj_id", array(':schoolid' => $schoolid, ':bj_id' => $v['sid']));
			$allbjqksm = pdo_fetchall("SELECT sid,createtime FROM " . tablename($this->table_checklog) . " WHERE bj_id = '{$v['sid']}' AND leixing = 1 AND isconfirm = 1  $condition3 ");
			$bjqksm = 0;
			foreach($allbjqksm as $da) {
				$key = date('Y-m-d', $da['createtime']);
				if(in_array($key, array_keys($days))) {
					if(!in_array($da['sid'], $daykey[$key])) {
						$daykey[$key] = $da['sid'];
						$bjqksm++;
					}
				}
			}
			$bjzrss[] = $bjzrs;
			$njcqzssss[] =  $bjqksm;
			$bjkqbl[] =  round($bjqksm/($bjzrs*$day_num)*100,2);
			$allthisbjsname[] = $v['sname'];
		}
	}else{
		foreach($allthisbj as $index => $v){
			$bjzrs = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_students) . " WHERE schoolid = :schoolid And bj_id = :bj_id", array(':schoolid' => $schoolid, ':bj_id' => $v['sid']));
			$bjqksm = pdo_fetchcolumn("SELECT COUNT(distinct sid) FROM " . tablename($this->table_checklog) . " WHERE bj_id = '{$v['sid']}' AND leixing = 1 AND isconfirm = 1  $condition3 ");
			$njcqzssss[] =  $bjqksm;
			$allthisbjsname[] = $v['sname'];
			$bjkqbl[] =  round($bjqksm/$bjzrs*100,2);
		}
	}
	$data['allthisbj'] = $allthisbjsname;
	$data['bjcqzs'] = $njcqzssss;
	$data['bjkqbl'] = $bjkqbl;
	die ( json_encode ( $data ) );
	
}
/**end**/
if($operation == 'a') {
    if(!empty($_GPC['start'])) {
        $starttime = strtotime($_GPC['start']);
        $endtime = strtotime($_GPC['end']) + 86399;
    } else {
        $starttime = 0;
        $endtime = TIMESTAMP;
    }
    if($_W['isajax'] && $_W['ispost']) {
        $datasets = array(
            'unionpay' => array('name' => '银联支付', 'value' => 0),
            'alipay' => array('name' => '支付宝支付', 'value' => 0),
            'baifubao' => array('name' => '百付宝支付', 'value' => 0),
            'wechat' => array('name' => '微信支付', 'value' => 0),
            'cash' => array('name' => '现金支付', 'value' => 0),
            'credit' => array('name' => '余额支付', 'value' => 0)
        );
        $data = pdo_fetchall("SELECT * FROM " . tablename($this->table_order) . 'WHERE weid = :weid AND schoolid = :schoolid and status = 2 and paytime >= :starttime and paytime <= :endtime', array(':weid' => $weid, ':schoolid' => $schoolid, ':starttime' => $starttime, 'endtime' => $endtime));
        foreach($data as $da) {
            if(in_array($da['pay_type'], array_keys($datasets))) {
                $datasets[$da['pay_type']]['value'] += 1;
            }
        }
        $datasets = array_values($datasets);
        message(error(0, $datasets), '', 'ajax');
    }
}
if($operation == 'b') {
    if(!empty($_GPC['start'])) {
        $starttime = strtotime($_GPC['start']);
        $endtime = strtotime($_GPC['end']) + 86399;
        $condition .= " AND createtime > '{$starttime}' AND createtime < '{$endtime}'";
    } else {
        $starttime = strtotime('-30 day');
        $endtime = TIMESTAMP;
        $condition .= "";
    }
    $bjq = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_bjq) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' $condition ");
    $bm = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_signup) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' $condition ");
    $xc = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_media) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' And type = 2 $condition ");
    $tz = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_notice) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' $condition ");
    $kq = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_checklog) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' $condition ");
    $ly = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_leave) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' And isliuyan = 2 And isfrist = 1 $condition ");
    $qj = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_leave) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' And isliuyan = 0 $condition ");
    if($_W['isajax'] && $_W['ispost']) {
        $datasets = array(
            'bjq' => array('name' => '班级圈', 'value' => $bjq),
            'bm' => array('name' => '在线报名', 'value' => $bm),
            'tz' => array('name' => '通知公告', 'value' => $tz),
            'kq' => array('name' => '打卡考勤', 'value' => $kq),
            'ly' => array('name' => '在线留言', 'value' => $ly),
            'xc' => array('name' => '相册', 'value' => $xc),
            'qj' => array('name' => '在线请假', 'value' => $qj)
        );
        $datasets = array_values($datasets);
        message(error(0, $datasets), '', 'ajax');
    }
}

function timediff($begin_time,$end_time){
      if($begin_time < $end_time){
         $starttime = $begin_time;
         $endtime = $end_time;
      }else{
         $starttime = $end_time;
         $endtime = $begin_time;
      }

      //计算天数
      $timediff = $endtime-$starttime;
      $days = intval($timediff/86400);
      //计算小时数
      $remain = $timediff%86400;
      $hours = intval($remain/3600);
      //计算分钟数
      $remain = $remain%3600;
      $mins = intval($remain/60);
      //计算秒数
      $secs = $remain%60;
      $res = array("day" => $days,"hour" => $hours,"min" => $mins,"sec" => $secs);
      return $res;
}

?>
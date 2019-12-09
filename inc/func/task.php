<?php
/**
 * By 高贵血迹
 */
	global $_GPC, $_W;
	$weid = $_GPC['i'];
	$schoolid = $_GPC['schoolid'];
	$starttime = mktime(0,0,0,date("m"),date("d"),date("Y"));
	$endtime = $starttime + 86399;
	if(!empty($schoolid)){
		$condition1 = " AND schoolid = '{$schoolid}' ";
	}
	$condition = " AND date > '{$starttime}' AND date < '{$endtime}'";
	$alljobs = pdo_fetchall("SELECT kcid,type FROM " . tablename('wx_school_task') . " WHERE status = 1 $condition1 ");
	if($alljobs){
		foreach($alljobs as $key => $row){
			if($row['type'] == 1){//课程提醒
				$kcinfo = pdo_fetch("SELECT id,is_tx,txtime FROM " . tablename ('wx_school_tcourse') . " where id = :id ", array(':id' => $row['kcid']));
				if($kcinfo['is_tx'] == 1){
					//$send_kctx = send_kctx($row['kcid'],$kcinfo['txtime']);
					$allks = pdo_fetchall("SELECT id,weid,schoolid,sd_id FROM " . tablename('wx_school_kcbiao') . " WHERE kcid = '{$row['kcid']}' $condition ");
					if($allks){
						foreach($allks as $k => $val){
							$sdinfo = pdo_fetch("SELECT sd_start FROM " . tablename ('wx_school_classify') . " where sid = :sid ", array(':sid' => $val['sd_id']));
							if($sdinfo){
								$checksend = pdo_fetch("SELECT * FROM " . tablename('wx_school_task_list') . " WHERE ksid = '{$val['id']}' And type = 1 ");//查询提醒任务
								if(empty($checksend)){//为空则执行
									$nowtime = time();
									$check_start = strtotime(date("Y-m-d",$nowtime).date(" H:i",$sdinfo['sd_start']));
									$ksstarttime = $check_start - $kcinfo['txtime']*60;
									if($nowtime >= $ksstarttime){//判断时间
										$data = array(
											'weid' => $val['weid'],
											'schoolid' =>  $val['schoolid'],
											'ksid' => $val['id'],
											'type' => 1,
											'createtime' => $nowtime
										);
										pdo_insert('wx_school_task_list', $data);
										$this->sendMobileJssktx($val['id'],$val['schoolid'],$val['weid']);
									}
								}
							}
						}
					}
				}
			}
		}
		$data['alljobs'] = $alljobs;
		$data['result'] = true;
	}else{
		$data['msg'] = "当前无可执行任务";
		$data['result'] = true;
	}
	echo json_encode($data);
	exit;
?>
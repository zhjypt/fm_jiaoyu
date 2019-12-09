<?php 
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
defined('IN_IA') or exit('Access Denied');
load()->func('communication');



function check_plugin($name){
    $item = pdo_fetch("SELECT * FROM " . tablename('modules') . " WHERE name = '{$name}' ");
    $result = false;
    if($item){
        $result = true;
    }
    return $result;
}



//取正1间教室，正在上课或在打卡时间内即将开始打卡上课的课时
function Getnearks($roomid,$starttime,$endtime){
	$allks = pdo_fetchall("SELECT * FROM " . tablename('wx_school_kcbiao') . " WHERE  addr_id = '{$roomid}'  AND date > '{$starttime}' AND date < '{$endtime}' ORDER BY date ASC");
	if($allks){
		$nowtime = time();
		$nowkc = '';
		$nowks = '';
		foreach($allks as $row){
			/**取即将开始和已经开始的课程**/
			$plustime = 0;
			$sdinfo  = pdo_fetch("SELECT sd_start,sd_end FROM " . tablename('wx_school_classify') . " WHERE  sid = '{$row['sd_id']}'");
			$checkkc = pdo_fetch("SELECT * FROM " . tablename('wx_school_tcourse') . " WHERE id = :id ", array(':id' => $row['kcid']));
			if($checkkc['isSign'] == 1){
				$plustime = $checkkc['signTime']*60;
			}else{
				$plustime = 20*60;
			}
			$check_start = strtotime(date("Y-m-d",$nowtime).date(" H:i",$sdinfo['sd_start'])) - $plustime; //当前课时开始时间向前延伸到设置的签到的时间
			$check_end   = strtotime(date("Y-m-d",$nowtime).date(" H:i",$sdinfo['sd_end']));
			if($nowtime >= $check_start && $nowtime <= $check_end){
				$nowkc[] = $checkkc;
				$nowks[] = $row;
			}
		}
		if($nowkc && $nowks){
			$reslut['nowkc'] = $nowkc;
			$reslut['nowks'] = $nowks;
		}else{
			$reslut = false;
		}
	}else{
		$reslut = false;
	}
	return $reslut ;
}

function getksbiao($schoolid,$classid,$starttime,$endtime){
	$allks = pdo_fetchall("SELECT sd_id,kcid,tid FROM " . tablename('wx_school_kcbiao') . " WHERE  addr_id = '{$classid}'  AND date > '{$starttime}' AND date < '{$endtime}' ORDER BY date ASC");
	$week = date("w",time());
	$section = 0;
	foreach($allks as $key => $row){
		$section ++;
		$sd  = pdo_fetch("SELECT sd_start,sd_end FROM " . tablename('wx_school_classify') . " WHERE  sid = '{$row['sd_id']}'");
		$kc = pdo_fetch("SELECT name FROM " . tablename('wx_school_tcourse') . " WHERE id = :id ", array(':id' => $row['kcid']));
		$teacher = pdo_fetch("SELECT tname,thumb FROM " . tablename('wx_school_teachers') . " WHERE id = '{$row['tid']}'");
		$school = pdo_fetch("SELECT tpic FROM " . tablename('wx_school_index') . " WHERE id = '{$schoolid}'");
		$allks[$key]['week'] = $week;
		$allks[$key]['section'] = $section;
		$allks[$key]['course_name'] = $kc['name'];
		$allks[$key]['start_time'] = date(" H:i",$sd['sd_start']);
		$allks[$key]['end_time'] = date(" H:i",$sd['sd_end']);
		$allks[$key]['teacher_name'] = $teacher['tname'];
		$allks[$key]['teacher_img'] = !empty($teacher['thumb'])?tomedia($school['tpic']):tomedia($teacher['thumb']);
		unset($allks[$key]['sd_id']);
		unset($allks[$key]['kcid']);
		unset($allks[$key]['tid']);
	}
	return $allks ;
}

function GetStuInfoByKs($schoolid,$ksid){
	$ksinfo  = pdo_fetch("SELECT * FROM " . tablename('wx_school_kcbiao') . " WHERE id = '{$ksid}' and schoolid = '{$schoolid}' ");
	$signStu = pdo_fetchall("SELECT distinct sid FROM " . tablename('wx_school_kcsign') . " WHERE ksid = '{$ksid}' and schoolid = '{$schoolid}' and tid = 0 and sid != 0 and status = 2");
	$timeinfo = pdo_fetch("SELECT sd_start,sd_end FROM " . tablename('wx_school_classify') . " WHERE sid = '{$ksinfo['sd_id']}' and schoolid = '{$schoolid}' and type = 'timeframe' ");
	$starttime_str = date("Y-m-d",$ksinfo['date'])." ".date("H:i:s",$timeinfo['sd_start']);
	$endtime_str = date("Y-m-d",$ksinfo['date'])." ".date("H:i:s",$timeinfo['sd_end']);
	$starttime = strtotime($starttime_str);
	$endtime = strtotime($endtime_str);
	$LeaveStu = pdo_fetchall("SELECT distinct leaves.sid FROM " . tablename('wx_school_leave') . " as leaves , " . tablename('wx_school_order') . " as orderTab  WHERE orderTab.kcid = '{$ksinfo['kcid']}' and orderTab.schoolid = '{$schoolid}' and orderTab.type = 1 and orderTab.status = 2  and orderTab.sid != 0 and orderTab.sid = leaves.sid and leaves.startime1 <= '{$starttime}' and leaves.endtime1 >= '{$endtime}' "); 
	$AllStu = pdo_fetchall("SELECT distinct sid FROM ". tablename('wx_school_order') . "  WHERE kcid = '{$ksinfo['kcid']}' and schoolid = '{$schoolid}' and type = 1 and status = 2  and sid != 0 ");
	$result['signstu'] = count($signStu);
	$result['leavestu'] = count($LeaveStu);
	$result['allstu'] = count($AllStu);
	return $result;
	
}

function CheckIsShowKm($tid,$bj_id,$km_id,$schoolid){
    if(!empty($km_id)){
        $checkIsSk = pdo_fetch("SELECT * FROM " . tablename('wx_school_user_class') . " where schoolid = '{$schoolid}' and tid = '{$tid}' and bj_id = '{$bj_id}' and km_id = '{$km_id}' ");
        if(!empty($checkIsSk)){
            return true;
        }
    }
    return false;
};

function GetTinfoBykb($bj_id,$km_id,$schoolid){
    $teainfo = pdo_fetch("SELECT * FROM " . tablename('wx_school_user_class') . " where schoolid = '{$schoolid}'  and bj_id = '{$bj_id}' and km_id = '{$km_id}' ");
    $teacher = pdo_fetch("SELECT id,tname FROM " . tablename('wx_school_teachers') . " where schoolid = '{$schoolid}'  and id = '{$teainfo['tid']}' ");

        if(!empty($teacher)){
            return $teacher['tname'];
        }

};



function getkctimetableByBjid($schoolid,$bj_id,$starttime,$endtime){
    $condition = " AND begintime < '{$starttime}' AND endtime > '{$endtime}'";
    $cook = pdo_fetch("SELECT * FROM " . tablename('wx_school_timetable') . " WHERE schoolid = :schoolid And bj_id = :bj_id And ishow = 1 $condition", array(':schoolid' => $schoolid,':bj_id' => $bj_id));
    $week = date("w",$endtime);
    $return = array();
    if($week ==1){
        if($cook['monday']){
            $thecook = iunserializer($cook['monday']);
            $return['sd']['sd_1'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_1_sd']}'");
            $return['sd']['sd_2'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_2_sd']}'");
            $return['sd']['sd_3'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_3_sd']}'");
            $return['sd']['sd_4'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_4_sd']}'");
            $return['sd']['sd_5'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_5_sd']}'");
            $return['sd']['sd_6'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_6_sd']}'");
            $return['sd']['sd_7'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_7_sd']}'");
            $return['sd']['sd_8'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_8_sd']}'");
            $return['sd']['sd_9'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_9_sd']}'");
            $return['sd']['sd_10']= pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_10_sd']}'");
            $return['sd']['sd_11']= pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_11_sd']}'");
            $return['sd']['sd_12']= pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_12_sd']}'");
            $return['km']['km_1'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_1_km']}'");
            $return['km']['km_1']['tname'] = GetTinfoBykb($bj_id,$thecook['mon_1_km'],$schoolid);
            $return['km']['km_2'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_2_km']}'");
            $return['km']['km_2']['tname'] = GetTinfoBykb($bj_id,$thecook['mon_2_km'],$schoolid);
            $return['km']['km_3'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_3_km']}'");
            $return['km']['km_3']['tname'] = GetTinfoBykb($bj_id,$thecook['mon_3_km'],$schoolid);
            $return['km']['km_4'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_4_km']}'");
            $return['km']['km_4']['tname'] = GetTinfoBykb($bj_id,$thecook['mon_4_km'],$schoolid);
            $return['km']['km_5'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_5_km']}'");
            $return['km']['km_5']['tname'] = GetTinfoBykb($bj_id,$thecook['mon_5_km'],$schoolid);
            $return['km']['km_6'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_6_km']}'");
            $return['km']['km_6']['tname'] = GetTinfoBykb($bj_id,$thecook['mon_6_km'],$schoolid);
            $return['km']['km_7'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_7_km']}'");
            $return['km']['km_7']['tname'] = GetTinfoBykb($bj_id,$thecook['mon_7_km'],$schoolid);
            $return['km']['km_8'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_8_km']}'");
            $return['km']['km_8']['tname'] = GetTinfoBykb($bj_id,$thecook['mon_8_km'],$schoolid);
            $return['km']['km_9'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_9_km']}'");
            $return['km']['km_9']['tname'] = GetTinfoBykb($bj_id,$thecook['mon_9_km'],$schoolid);
            $return['km']['km_10']= pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_10_km']}'");
            $return['km']['km_10']['tname'] = GetTinfoBykb($bj_id,$thecook['mon_10_km'],$schoolid);
            $return['km']['km_11']= pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_11_km']}'");
            $return['km']['km_11']['tname'] = GetTinfoBykb($bj_id,$thecook['mon_11_km'],$schoolid);
            $return['km']['km_12']= pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_12_km']}'");
            $return['km']['km_12']['tname'] = GetTinfoBykb($bj_id,$thecook['mon_12_km'],$schoolid);
        }
    }
    if($week ==2){
        if($cook['tuesday']){
            $thecook = iunserializer($cook['tuesday']);
            $return['sd']['sd_1'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_1_sd']}'");
            $return['sd']['sd_2'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_2_sd']}'");
            $return['sd']['sd_3'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_3_sd']}'");
            $return['sd']['sd_4'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_4_sd']}'");
            $return['sd']['sd_5'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_5_sd']}'");
            $return['sd']['sd_6'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_6_sd']}'");
            $return['sd']['sd_7'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_7_sd']}'");
            $return['sd']['sd_8'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_8_sd']}'");
            $return['sd']['sd_9'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_9_sd']}'");
            $return['sd']['sd_10'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_10_sd']}'");
            $return['sd']['sd_11'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_11_sd']}'");
            $return['sd']['sd_12'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_12_sd']}'");
            $return['km']['km_1'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_1_km']}'");
            $return['km']['km_1']['tname'] = GetTinfoBykb($bj_id,$thecook['tus_1_km'],$schoolid);
            $return['km']['km_2'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_2_km']}'");
            $return['km']['km_2']['tname'] = GetTinfoBykb($bj_id,$thecook['tus_2_km'],$schoolid);
            $return['km']['km_3'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_3_km']}'");
            $return['km']['km_3']['tname'] = GetTinfoBykb($bj_id,$thecook['tus_3_km'],$schoolid);
            $return['km']['km_4'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_4_km']}'");
            $return['km']['km_4']['tname'] = GetTinfoBykb($bj_id,$thecook['tus_4_km'],$schoolid);
            $return['km']['km_5'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_5_km']}'");
            $return['km']['km_5']['tname'] = GetTinfoBykb($bj_id,$thecook['tus_5_km'],$schoolid);
            $return['km']['km_6'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_6_km']}'");
            $return['km']['km_6']['tname'] = GetTinfoBykb($bj_id,$thecook['tus_6_km'],$schoolid);
            $return['km']['km_7'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_7_km']}'");
            $return['km']['km_7']['tname'] = GetTinfoBykb($bj_id,$thecook['tus_7_km'],$schoolid);
            $return['km']['km_8'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_8_km']}'");
            $return['km']['km_8']['tname'] = GetTinfoBykb($bj_id,$thecook['tus_8_km'],$schoolid);
            $return['km']['km_9'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_9_km']}'");
            $return['km']['km_9']['tname'] = GetTinfoBykb($bj_id,$thecook['tus_9_km'],$schoolid);
            $return['km']['km_10'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_10_km']}'");
            $return['km']['km_10']['tname'] = GetTinfoBykb($bj_id,$thecook['tus_10_km'],$schoolid);
            $return['km']['km_11'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_11_km']}'");
            $return['km']['km_11']['tname'] = GetTinfoBykb($bj_id,$thecook['tus_11_km'],$schoolid);
            $return['km']['km_12'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_12_km']}'");
            $return['km']['km_12']['tname'] = GetTinfoBykb($bj_id,$thecook['tus_12_km'],$schoolid);
        }
    }
    if($week ==3){
        if($cook['wednesday']){
            $thecook = iunserializer($cook['wednesday']);
            $return['sd']['sd_1'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_1_sd']}'");
            $return['sd']['sd_2'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_2_sd']}'");
            $return['sd']['sd_3'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_3_sd']}'");
            $return['sd']['sd_4'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_4_sd']}'");
            $return['sd']['sd_5'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_5_sd']}'");
            $return['sd']['sd_6'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_6_sd']}'");
            $return['sd']['sd_7'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_7_sd']}'");
            $return['sd']['sd_8'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_8_sd']}'");
            $return['sd']['sd_9'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_9_sd']}'");
            $return['sd']['sd_10'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_10_sd']}'");
            $return['sd']['sd_11'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_11_sd']}'");
            $return['sd']['sd_12'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_12_sd']}'");
            $return['km']['km_1'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_1_km']}'");
            $return['km']['km_1']['tname'] = GetTinfoBykb($bj_id,$thecook['wed_1_km'],$schoolid);
            $return['km']['km_2'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_2_km']}'");
            $return['km']['km_2']['tname'] = GetTinfoBykb($bj_id,$thecook['wed_2_km'],$schoolid);
            $return['km']['km_3'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_3_km']}'");
            $return['km']['km_3']['tname'] = GetTinfoBykb($bj_id,$thecook['wed_3_km'],$schoolid);
            $return['km']['km_4'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_4_km']}'");
            $return['km']['km_4']['tname'] = GetTinfoBykb($bj_id,$thecook['wed_4_km'],$schoolid);
            $return['km']['km_5'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_5_km']}'");
            $return['km']['km_5']['tname'] = GetTinfoBykb($bj_id,$thecook['wed_5_km'],$schoolid);
            $return['km']['km_6'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_6_km']}'");
            $return['km']['km_6']['tname'] = GetTinfoBykb($bj_id,$thecook['wed_6_km'],$schoolid);
            $return['km']['km_7'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_7_km']}'");
            $return['km']['km_7']['tname'] = GetTinfoBykb($bj_id,$thecook['wed_7_km'],$schoolid);
            $return['km']['km_8'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_8_km']}'");
            $return['km']['km_8']['tname'] = GetTinfoBykb($bj_id,$thecook['wed_8_km'],$schoolid);
            $return['km']['km_9'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_9_km']}'");
            $return['km']['km_9']['tname'] = GetTinfoBykb($bj_id,$thecook['wed_9_km'],$schoolid);
            $return['km']['km_10'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_10_km']}'");
            $return['km']['km_10']['tname'] = GetTinfoBykb($bj_id,$thecook['wed_10_km'],$schoolid);
            $return['km']['km_11'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_11_km']}'");
            $return['km']['km_11']['tname'] = GetTinfoBykb($bj_id,$thecook['wed_11_km'],$schoolid);
            $return['km']['km_12'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_12_km']}'");
            $return['km']['km_12']['tname'] = GetTinfoBykb($bj_id,$thecook['wed_12_km'],$schoolid);
        }
    }
    if($week ==4){
        if($cook['thursday']){
            $thecook = iunserializer($cook['thursday']);
            $return['sd']['sd_1'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_1_sd']}'");
            $return['sd']['sd_2'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_2_sd']}'");
            $return['sd']['sd_3'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_3_sd']}'");
            $return['sd']['sd_4'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_4_sd']}'");
            $return['sd']['sd_5'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_5_sd']}'");
            $return['sd']['sd_6'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_6_sd']}'");
            $return['sd']['sd_7'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_7_sd']}'");
            $return['sd']['sd_8'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_8_sd']}'");
            $return['sd']['sd_9'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_9_sd']}'");
            $return['sd']['sd_10'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_10_sd']}'");
            $return['sd']['sd_11'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_11_sd']}'");
            $return['sd']['sd_12'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_12_sd']}'");
            $return['km']['km_1'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_1_km']}'");
            $return['km']['km_1']['tname'] = GetTinfoBykb($bj_id,$thecook['thu_1_km'],$schoolid);
            $return['km']['km_2'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_2_km']}'");
            $return['km']['km_2']['tname'] = GetTinfoBykb($bj_id,$thecook['thu_2_km'],$schoolid);
            $return['km']['km_3'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_3_km']}'");
            $return['km']['km_3']['tname'] = GetTinfoBykb($bj_id,$thecook['thu_3_km'],$schoolid);
            $return['km']['km_4'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_4_km']}'");
            $return['km']['km_4']['tname'] = GetTinfoBykb($bj_id,$thecook['thu_4_km'],$schoolid);
            $return['km']['km_5'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_5_km']}'");
            $return['km']['km_5']['tname'] = GetTinfoBykb($bj_id,$thecook['thu_5_km'],$schoolid);
            $return['km']['km_6'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_6_km']}'");
            $return['km']['km_6']['tname'] = GetTinfoBykb($bj_id,$thecook['thu_6_km'],$schoolid);
            $return['km']['km_7'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_7_km']}'");
            $return['km']['km_7']['tname'] = GetTinfoBykb($bj_id,$thecook['thu_7_km'],$schoolid);
            $return['km']['km_8'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_8_km']}'");
            $return['km']['km_8']['tname'] = GetTinfoBykb($bj_id,$thecook['thu_8_km'],$schoolid);
            $return['km']['km_9'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_9_km']}'");
            $return['km']['km_9']['tname'] = GetTinfoBykb($bj_id,$thecook['thu_9_km'],$schoolid);
            $return['km']['km_10'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_10_km']}'");
            $return['km']['km_10']['tname'] = GetTinfoBykb($bj_id,$thecook['thu_10_km'],$schoolid);
            $return['km']['km_11'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_11_km']}'");
            $return['km']['km_11']['tname'] = GetTinfoBykb($bj_id,$thecook['thu_11_km'],$schoolid);
            $return['km']['km_12'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_12_km']}'");
            $return['km']['km_12']['tname'] = GetTinfoBykb($bj_id,$thecook['thu_12_km'],$schoolid);
        }
    }
    if($week ==5){
        if($cook['friday']){
            $thecook = iunserializer($cook['friday']);
            $return['sd']['sd_1'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_1_sd']}'");
            $return['sd']['sd_2'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_2_sd']}'");
            $return['sd']['sd_3'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_3_sd']}'");
            $return['sd']['sd_4'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_4_sd']}'");
            $return['sd']['sd_5'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_5_sd']}'");
            $return['sd']['sd_6'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_6_sd']}'");
            $return['sd']['sd_7'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_7_sd']}'");
            $return['sd']['sd_8'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_8_sd']}'");
            $return['sd']['sd_9'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_9_sd']}'");
            $return['sd']['sd_10'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_10_sd']}'");
            $return['sd']['sd_11'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_11_sd']}'");
            $return['sd']['sd_12'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_12_sd']}'");
            $return['km']['km_1'] = pdo_fetch("SELECT sname,icon,sid as tname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_1_km']}'");
            $return['km']['km_1']['tname'] = GetTinfoBykb($bj_id,$thecook['fri_1_km'],$schoolid);

            $return['km']['km_2'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_2_km']}'");
            $return['km']['km_2']['tname'] = GetTinfoBykb($bj_id,$thecook['fri_2_km'],$schoolid);

            $return['km']['km_3'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_3_km']}'");
            $return['km']['km_3']['tname'] = GetTinfoBykb($bj_id,$thecook['fri_3_km'],$schoolid);
            $return['km']['km_4'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_4_km']}'");
            $return['km']['km_4']['tname'] = GetTinfoBykb($bj_id,$thecook['fri_4_km'],$schoolid);
            $return['km']['km_5'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_5_km']}'");
            $return['km']['km_5']['tname'] = GetTinfoBykb($bj_id,$thecook['fri_5_km'],$schoolid);
            $return['km']['km_6'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_6_km']}'");
            $return['km']['km_6']['tname'] = GetTinfoBykb($bj_id,$thecook['fri_6_km'],$schoolid);
            $return['km']['km_7'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_7_km']}'");
            $return['km']['km_7']['tname'] = GetTinfoBykb($bj_id,$thecook['fri_7_km'],$schoolid);
            $return['km']['km_8'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_8_km']}'");
            $return['km']['km_8']['tname'] = GetTinfoBykb($bj_id,$thecook['fri_8_km'],$schoolid);
            $return['km']['km_9'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_9_km']}'");
            $return['km']['km_9']['tname'] = GetTinfoBykb($bj_id,$thecook['fri_9_km'],$schoolid);
            $return['km']['km_10'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_10_km']}'");
            $return['km']['km_10']['tname'] = GetTinfoBykb($bj_id,$thecook['fri_10_km'],$schoolid);
            $return['km']['km_11'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_11_km']}'");
            $return['km']['km_11']['tname'] = GetTinfoBykb($bj_id,$thecook['fri_11_km'],$schoolid);
            $return['km']['km_12'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_12_km']}'");
            $return['km']['km_12']['tname'] = GetTinfoBykb($bj_id,$thecook['fri_12_km'],$schoolid);
        }
    }
    if($week ==6){
        if($cook['saturday']){
            $thecook = iunserializer($cook['saturday']);
            $return['sd']['sd_1'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_1_sd']}'");
            $return['sd']['sd_2'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_2_sd']}'");
            $return['sd']['sd_3'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_3_sd']}'");
            $return['sd']['sd_4'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_4_sd']}'");
            $return['sd']['sd_5'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_5_sd']}'");
            $return['sd']['sd_6'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_6_sd']}'");
            $return['sd']['sd_7'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_7_sd']}'");
            $return['sd']['sd_8'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_8_sd']}'");
            $return['sd']['sd_9'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_9_sd']}'");
            $return['sd']['sd_10'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_10_sd']}'");
            $return['sd']['sd_11'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_11_sd']}'");
            $return['sd']['sd_12'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_12_sd']}'");
            $return['km']['km_1'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_1_km']}'");
            $return['km']['km_1']['tname'] = GetTinfoBykb($bj_id,$thecook['sat_1_km'],$schoolid);
            $return['km']['km_2'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_2_km']}'");
            $return['km']['km_2']['tname'] = GetTinfoBykb($bj_id,$thecook['sat_2_km'],$schoolid);
            $return['km']['km_3'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_3_km']}'");
            $return['km']['km_3']['tname'] = GetTinfoBykb($bj_id,$thecook['sat_3_km'],$schoolid);
            $return['km']['km_4'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_4_km']}'");
            $return['km']['km_4']['tname'] = GetTinfoBykb($bj_id,$thecook['sat_4_km'],$schoolid);
            $return['km']['km_5'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_5_km']}'");
            $return['km']['km_5']['tname'] = GetTinfoBykb($bj_id,$thecook['sat_5_km'],$schoolid);
            $return['km']['km_6'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_6_km']}'");
            $return['km']['km_6']['tname'] = GetTinfoBykb($bj_id,$thecook['sat_6_km'],$schoolid);
            $return['km']['km_7'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_7_km']}'");
            $return['km']['km_7']['tname'] = GetTinfoBykb($bj_id,$thecook['sat_7_km'],$schoolid);
            $return['km']['km_8'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_8_km']}'");
            $return['km']['km_8']['tname'] = GetTinfoBykb($bj_id,$thecook['sat_8_km'],$schoolid);
            $return['km']['km_9'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_9_km']}'");
            $return['km']['km_9']['tname'] = GetTinfoBykb($bj_id,$thecook['sat_9_km'],$schoolid);
            $return['km']['km_10'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_10_km']}'");
            $return['km']['km_10']['tname'] = GetTinfoBykb($bj_id,$thecook['sat_10_km'],$schoolid);
            $return['km']['km_11'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_11_km']}'");
            $return['km']['km_11']['tname'] = GetTinfoBykb($bj_id,$thecook['sat_11_km'],$schoolid);
            $return['km']['km_12'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_12_km']}'");
            $return['km']['km_12']['tname'] = GetTinfoBykb($bj_id,$thecook['sat_12_km'],$schoolid);
        }
    }
    if($week == 0){
        if($cook['sunday']){
            $thecook = iunserializer($cook['sunday']);
            $return['sd']['sd_1'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_1_sd']}'");
            $return['sd']['sd_2'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_2_sd']}'");
            $return['sd']['sd_3'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_3_sd']}'");
            $return['sd']['sd_4'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_4_sd']}'");
            $return['sd']['sd_5'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_5_sd']}'");
            $return['sd']['sd_6'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_6_sd']}'");
            $return['sd']['sd_7'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_7_sd']}'");
            $return['sd']['sd_8'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_8_sd']}'");
            $return['sd']['sd_9'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_9_sd']}'");
            $return['sd']['sd_10'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_10_sd']}'");
            $return['sd']['sd_11'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_11_sd']}'");
            $return['sd']['sd_12'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_12_sd']}'");
            $return['km']['km_1'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_1_km']}'");
            $return['km']['km_1']['tname'] = GetTinfoBykb($bj_id,$thecook['sun_1_km'],$schoolid);
            $return['km']['km_2'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_2_km']}'");
            $return['km']['km_2']['tname'] = GetTinfoBykb($bj_id,$thecook['sun_2_km'],$schoolid);
            $return['km']['km_3'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_3_km']}'");
            $return['km']['km_3']['tname'] = GetTinfoBykb($bj_id,$thecook['sun_3_km'],$schoolid);
            $return['km']['km_4'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_4_km']}'");
            $return['km']['km_4']['tname'] = GetTinfoBykb($bj_id,$thecook['sun_4_km'],$schoolid);
            $return['km']['km_5'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_5_km']}'");
            $return['km']['km_5']['tname'] = GetTinfoBykb($bj_id,$thecook['sun_5_km'],$schoolid);
            $return['km']['km_6'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_6_km']}'");
            $return['km']['km_6']['tname'] = GetTinfoBykb($bj_id,$thecook['sun_6_km'],$schoolid);
            $return['km']['km_7'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_7_km']}'");
            $return['km']['km_7']['tname'] = GetTinfoBykb($bj_id,$thecook['sun_7_km'],$schoolid);
            $return['km']['km_8'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_8_km']}'");
            $return['km']['km_8']['tname'] = GetTinfoBykb($bj_id,$thecook['sun_8_km'],$schoolid);
            $return['km']['km_9'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_9_km']}'");
            $return['km']['km_9']['tname'] = GetTinfoBykb($bj_id,$thecook['sun_9_km'],$schoolid);
            $return['km']['km_10'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_10_km']}'");
            $return['km']['km_10']['tname'] = GetTinfoBykb($bj_id,$thecook['sun_10_km'],$schoolid);
            $return['km']['km_11'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_11_km']}'");
            $return['km']['km_11']['tname'] = GetTinfoBykb($bj_id,$thecook['sun_11_km'],$schoolid);
            $return['km']['km_12'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_12_km']}'");
            $return['km']['km_12']['tname'] = GetTinfoBykb($bj_id,$thecook['sun_12_km'],$schoolid);
        }
    }
    return $return;
}

function getkctimetableByTid($schoolid,$tid,$starttime,$endtime){
    mload()->model('tea');
    $myskbjlist = get_myskbj($tid);
    $condition = " AND begintime < '{$starttime}' AND endtime > '{$endtime}'";
    $week = date("w",$endtime);
    $return = array();
    $thecook = '';
    foreach ($myskbjlist as $key=>$value){
        $cook = pdo_fetch("SELECT * FROM " . tablename('wx_school_timetable') . " WHERE schoolid = :schoolid And bj_id = :bj_id And ishow = 1 $condition", array(':schoolid' => $schoolid,':bj_id' => $value['bj_id']));
        $bjinfo =pdo_fetch("SELECT sname,parentid FROM " . tablename('wx_school_classify') . " WHERE schoolid = :schoolid And sid = :sid ", array(':schoolid' => $schoolid,':sid' => $value['bj_id']));
        $njinfo = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE schoolid = :schoolid And sid = :sid ", array(':schoolid' => $schoolid,':sid' => $bjinfo['parentid']));
        if($week ==1){
            if($cook['monday']){
                $thecook = iunserializer($cook['monday']);
                if(!empty($thecook['mon_1_sd'])){
                    $return['sd']['sd_1'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_1_sd']}'");
                }
                if(!empty($thecook['mon_2_sd'])){
                    $return['sd']['sd_2'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_2_sd']}'");
                }
                if(!empty($thecook['mon_3_sd'])){
                    $return['sd']['sd_3'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_3_sd']}'");
                }
                if(!empty($thecook['mon_4_sd'])){
                    $return['sd']['sd_4'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_4_sd']}'");
                }
                if(!empty($thecook['mon_5_sd'])){
                    $return['sd']['sd_5'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_5_sd']}'");
                }
                if(!empty($thecook['mon_6_sd'])){
                    $return['sd']['sd_6'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_6_sd']}'");
                }
                if(!empty($thecook['mon_7_sd'])){
                    $return['sd']['sd_7'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_7_sd']}'");
                }
                if(!empty($thecook['mon_8_sd'])){
                    $return['sd']['sd_8'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_8_sd']}'");
                }
                if(!empty($thecook['mon_9_sd'])){
                    $return['sd']['sd_9'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_9_sd']}'");
                }
                if(!empty($thecook['mon_10_sd'])){
                    $return['sd']['sd_10'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_10_sd']}'");
                }
                if(!empty($thecook['mon_11_sd'])){
                    $return['sd']['sd_11'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_11_sd']}'");
                }
                if(!empty($thecook['mon_12_sd'])){
                    $return['sd']['sd_12'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_12_sd']}'");
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['mon_1_km'],$schoolid)){
                    $return['km']['km_1'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_1_km']}'");
                    $return['km']['km_1']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_1']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['mon_2_km'],$schoolid)){
                    $return['km']['km_2'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_2_km']}'");
                    $return['km']['km_2']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_2']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['mon_3_km'],$schoolid)){
                    $return['km']['km_3'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_3_km']}'");
                    $return['km']['km_3']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_3']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['mon_4_km'],$schoolid)){
                    $return['km']['km_4'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_4_km']}'");
                    $return['km']['km_4']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_4']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['mon_5_km'],$schoolid)){
                    $return['km']['km_5'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_5_km']}'");
                    $return['km']['km_5']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_5']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['mon_6_km'],$schoolid)){
                    $return['km']['km_6'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_6_km']}'");
                    $return['km']['km_6']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_6']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['mon_7_km'],$schoolid)){
                    $return['km']['km_7'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_7_km']}'");
                    $return['km']['km_7']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_7']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['mon_8_km'],$schoolid)){
                    $return['km']['km_8'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_8_km']}'");
                    $return['km']['km_8']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_8']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['mon_9_km'],$schoolid)){
                    $return['km']['km_9'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_9_km']}'");
                    $return['km']['km_9']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_9']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['mon_10_km'],$schoolid)){
                    $return['km']['km_10']= pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_10_km']}'");
                    $return['km']['km_10']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_10']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['mon_11_km'],$schoolid)){
                    $return['km']['km_11']= pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_11_km']}'");
                    $return['km']['km_11']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_11']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['mon_12_km'],$schoolid)){
                    $return['km']['km_12']= pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['mon_12_km']}'");
                    $return['km']['km_12']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_12']['njname'] = $njinfo['sname'];
                }
            }
        }
        if($week ==2){
            if($cook['tuesday']){
                $thecook = iunserializer($cook['tuesday']);
                if(!empty($thecook['tus_1_sd'])){
                    $return['sd']['sd_1'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_1_sd']}'");
                }
                if(!empty($thecook['tus_2_sd'])){
                    $return['sd']['sd_2'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_2_sd']}'");
                }
                if(!empty($thecook['tus_3_sd'])){
                    $return['sd']['sd_3'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_3_sd']}'");
                }
                if(!empty($thecook['tus_4_sd'])){
                    $return['sd']['sd_4'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_4_sd']}'");
                }
                if(!empty($thecook['tus_5_sd'])){
                    $return['sd']['sd_5'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_5_sd']}'");
                }
                if(!empty($thecook['tus_6_sd'])){
                    $return['sd']['sd_6'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_6_sd']}'");
                }
                if(!empty($thecook['tus_7_sd'])){
                    $return['sd']['sd_7'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_7_sd']}'");
                }
                if(!empty($thecook['tus_8_sd'])){
                    $return['sd']['sd_8'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_8_sd']}'");
                }
                if(!empty($thecook['tus_9_sd'])){
                    $return['sd']['sd_9'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_9_sd']}'");
                }
                if(!empty($thecook['tus_10_sd'])){
                    $return['sd']['sd_10'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_10_sd']}'");
                }
                if(!empty($thecook['tus_11_sd'])){
                    $return['sd']['sd_11'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_11_sd']}'");
                }
                if(!empty($thecook['tus_12_sd'])){
                    $return['sd']['sd_12'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_12_sd']}'");
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['tus_1_km'],$schoolid)){
                    $return['km']['km_1'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_1_km']}'");
                    $return['km']['km_1']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_1']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['tus_2_km'],$schoolid)){
                    $return['km']['km_2'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_2_km']}'");
                    $return['km']['km_2']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_2']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['tus_3_km'],$schoolid)){
                    $return['km']['km_3'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_3_km']}'");
                    $return['km']['km_3']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_3']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['tus_4_km'],$schoolid)){
                    $return['km']['km_4'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_4_km']}'");
                    $return['km']['km_4']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_4']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['tus_5_km'],$schoolid)){
                    $return['km']['km_5'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_5_km']}'");
                    $return['km']['km_5']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_5']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['tus_6_km'],$schoolid)){
                    $return['km']['km_6'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_6_km']}'");
                    $return['km']['km_6']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_6']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['tus_7_km'],$schoolid)){
                    $return['km']['km_7'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_7_km']}'");
                    $return['km']['km_7']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_7']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['tus_8_km'],$schoolid)){
                    $return['km']['km_8'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_8_km']}'");
                    $return['km']['km_8']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_8']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['tus_9_km'],$schoolid)){
                    $return['km']['km_9'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_9_km']}'");
                    $return['km']['km_9']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_9']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['tus_10_km'],$schoolid)){
                    $return['km']['km_10']= pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_10_km']}'");
                    $return['km']['km_10']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_10']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['tus_11_km'],$schoolid)){
                    $return['km']['km_11']= pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_11_km']}'");
                    $return['km']['km_11']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_11']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['tus_12_km'],$schoolid)){
                    $return['km']['km_12']= pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['tus_12_km']}'");
                    $return['km']['km_12']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_12']['njname'] = $njinfo['sname'];
                }
            }
        }
        if($week ==3){
            if($cook['wednesday']){
                $thecook = iunserializer($cook['wednesday']);
                if(!empty($thecook['wed_1_sd'])){
                    $return['sd']['sd_1'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_1_sd']}'");
                }
                if(!empty($thecook['wed_2_sd'])){
                    $return['sd']['sd_2'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_2_sd']}'");
                }
                if(!empty($thecook['wed_3_sd'])){
                    $return['sd']['sd_3'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_3_sd']}'");
                }
                if(!empty($thecook['wed_4_sd'])){
                    $return['sd']['sd_4'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_4_sd']}'");
                }
                if(!empty($thecook['wed_5_sd'])){
                    $return['sd']['sd_5'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_5_sd']}'");
                }
                if(!empty($thecook['wed_6_sd'])){
                    $return['sd']['sd_6'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_6_sd']}'");
                }
                if(!empty($thecook['wed_7_sd'])){
                    $return['sd']['sd_7'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_7_sd']}'");
                }
                if(!empty($thecook['wed_8_sd'])){
                    $return['sd']['sd_8'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_8_sd']}'");
                }
                if(!empty($thecook['wed_9_sd'])){
                    $return['sd']['sd_9'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_9_sd']}'");
                }
                if(!empty($thecook['wed_10_sd'])){
                    $return['sd']['sd_10'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_10_sd']}'");
                }
                if(!empty($thecook['wed_11_sd'])){
                    $return['sd']['sd_11'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_11_sd']}'");
                }
                if(!empty($thecook['wed_12_sd'])){
                    $return['sd']['sd_12'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_12_sd']}'");
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['wed_1_km'],$schoolid)){
                    $return['km']['km_1'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_1_km']}'");
                    $return['km']['km_1']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_1']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['wed_2_km'],$schoolid)){
                    $return['km']['km_2'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_2_km']}'");
                    $return['km']['km_2']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_2']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['wed_3_km'],$schoolid)){
                    $return['km']['km_3'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_3_km']}'");
                    $return['km']['km_3']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_3']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['wed_4_km'],$schoolid)){
                    $return['km']['km_4'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_4_km']}'");
                    $return['km']['km_4']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_4']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['wed_5_km'],$schoolid)){
                    $return['km']['km_5'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_5_km']}'");
                    $return['km']['km_5']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_5']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['wed_6_km'],$schoolid)){
                    $return['km']['km_6'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_6_km']}'");
                    $return['km']['km_6']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_6']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['wed_7_km'],$schoolid)){
                    $return['km']['km_7'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_7_km']}'");
                    $return['km']['km_7']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_7']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['wed_8_km'],$schoolid)){
                    $return['km']['km_8'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_8_km']}'");
                    $return['km']['km_8']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_8']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['wed_9_km'],$schoolid)){
                    $return['km']['km_9'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_9_km']}'");
                    $return['km']['km_9']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_9']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['wed_10_km'],$schoolid)){
                    $return['km']['km_10']= pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_10_km']}'");
                    $return['km']['km_10']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_10']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['wed_11_km'],$schoolid)){
                    $return['km']['km_11']= pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_11_km']}'");
                    $return['km']['km_11']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_11']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['wed_12_km'],$schoolid)){
                    $return['km']['km_12']= pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['wed_12_km']}'");
                    $return['km']['km_12']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_12']['njname'] = $njinfo['sname'];
                }
            }
        }
        if($week ==4){
            if($cook['thursday']){
                $thecook = iunserializer($cook['thursday']);
                if(!empty($thecook['thu_1_sd'])){
                    $return['sd']['sd_1'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_1_sd']}'");
                }
                if(!empty($thecook['thu_2_sd'])){
                    $return['sd']['sd_2'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_2_sd']}'");
                }
                if(!empty($thecook['thu_3_sd'])){
                    $return['sd']['sd_3'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_3_sd']}'");
                }
                if(!empty($thecook['thu_4_sd'])){
                    $return['sd']['sd_4'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_4_sd']}'");
                }
                if(!empty($thecook['thu_5_sd'])){
                    $return['sd']['sd_5'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_5_sd']}'");
                }
                if(!empty($thecook['thu_6_sd'])){
                    $return['sd']['sd_6'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_6_sd']}'");
                }
                if(!empty($thecook['thu_7_sd'])){
                    $return['sd']['sd_7'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_7_sd']}'");
                }
                if(!empty($thecook['thu_8_sd'])){
                    $return['sd']['sd_8'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_8_sd']}'");
                }
                if(!empty($thecook['thu_9_sd'])){
                    $return['sd']['sd_9'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_9_sd']}'");
                }
                if(!empty($thecook['thu_10_sd'])){
                    $return['sd']['sd_10'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_10_sd']}'");
                }
                if(!empty($thecook['thu_11_sd'])){
                    $return['sd']['sd_11'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_11_sd']}'");
                }
                if(!empty($thecook['thu_12_sd'])){
                    $return['sd']['sd_12'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_12_sd']}'");
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['thu_1_km'],$schoolid)){
                    $return['km']['km_1'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_1_km']}'");
                    $return['km']['km_1']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_1']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['thu_2_km'],$schoolid)){
                    $return['km']['km_2'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_2_km']}'");
                    $return['km']['km_2']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_2']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['thu_3_km'],$schoolid)){
                    $return['km']['km_3'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_3_km']}'");
                    $return['km']['km_3']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_3']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['thu_4_km'],$schoolid)){
                    $return['km']['km_4'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_4_km']}'");
                    $return['km']['km_4']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_4']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['thu_5_km'],$schoolid)){
                    $return['km']['km_5'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_5_km']}'");
                    $return['km']['km_5']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_5']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['thu_6_km'],$schoolid)){
                    $return['km']['km_6'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_6_km']}'");
                    $return['km']['km_6']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_6']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['thu_7_km'],$schoolid)){
                    $return['km']['km_7'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_7_km']}'");
                    $return['km']['km_7']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_7']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['thu_8_km'],$schoolid)){
                    $return['km']['km_8'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_8_km']}'");
                    $return['km']['km_8']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_8']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['thu_9_km'],$schoolid)){
                    $return['km']['km_9'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_9_km']}'");
                    $return['km']['km_9']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_9']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['thu_10_km'],$schoolid)){
                    $return['km']['km_10']= pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_10_km']}'");
                    $return['km']['km_10']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_10']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['thu_11_km'],$schoolid)){
                    $return['km']['km_11']= pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_11_km']}'");
                    $return['km']['km_11']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_11']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['thu_12_km'],$schoolid)){
                    $return['km']['km_12']= pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['thu_12_km']}'");
                    $return['km']['km_12']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_12']['njname'] = $njinfo['sname'];
                }

            }
        }
        if($week ==5){
            if($cook['friday']){
                $thecook = unserialize($cook['friday']);
                $back = &$thecook;
                if(!empty($thecook['fri_1_sd'])){
                    $return['sd']['sd_1'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_1_sd']}'");
                }
                if(!empty($thecook['fri_2_sd'])){
                    $return['sd']['sd_2'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_2_sd']}'");
                }
                if(!empty($thecook['fri_3_sd'])){
                    $return['sd']['sd_3'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_3_sd']}'");
                }
                if(!empty($thecook['fri_4_sd'])){
                    $return['sd']['sd_4'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_4_sd']}'");
                }
                if(!empty($thecook['fri_5_sd'])){
                    $return['sd']['sd_5'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_5_sd']}'");
                }
                if(!empty($thecook['fri_6_sd'])){
                    $return['sd']['sd_6'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_6_sd']}'");
                }
                if(!empty($thecook['fri_7_sd'])){
                    $return['sd']['sd_7'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_7_sd']}'");
                }
                if(!empty($thecook['fri_8_sd'])){
                    $return['sd']['sd_8'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_8_sd']}'");
                }
                if(!empty($thecook['fri_9_sd'])){
                    $return['sd']['sd_9'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_9_sd']}'");
                }
                if(!empty($thecook['fri_10_sd'])){
                    $return['sd']['sd_10'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_10_sd']}'");
                }
                if(!empty($thecook['fri_11_sd'])){
                    $return['sd']['sd_11'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_11_sd']}'");
                }
                if(!empty($thecook['fri_12_sd'])){
                    $return['sd']['sd_12'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_12_sd']}'");
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['fri_1_km'],$schoolid)){
                    $return['km']['km_1'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_1_km']}'");
                    $return['km']['km_1']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_1']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['fri_2_km'],$schoolid)){
                    $return['km']['km_2'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_2_km']}'");
                    $return['km']['km_2']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_2']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['fri_3_km'],$schoolid)){
                    $return['km']['km_3'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_3_km']}'");
                    $return['km']['km_3']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_3']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['fri_4_km'],$schoolid)){
                    $return['km']['km_4'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_4_km']}'");
                    $return['km']['km_4']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_4']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['fri_5_km'],$schoolid)){
                    $return['km']['km_5'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_5_km']}'");
                    $return['km']['km_5']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_5']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['fri_6_km'],$schoolid)){
                    $return['km']['km_6'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_6_km']}'");
                    $return['km']['km_6']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_6']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['fri_7_km'],$schoolid)){
                    $return['km']['km_7'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_7_km']}'");
                    $return['km']['km_7']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_7']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['fri_8_km'],$schoolid)){
                    $return['km']['km_8'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_8_km']}'");
                    $return['km']['km_8']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_8']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['fri_9_km'],$schoolid)){
                    $return['km']['km_9'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_9_km']}'");
                    $return['km']['km_9']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_9']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['fri_10_km'],$schoolid)){
                    $return['km']['km_10']= pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_10_km']}'");
                    $return['km']['km_10']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_10']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['fri_11_km'],$schoolid)){
                    $return['km']['km_11']= pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_11_km']}'");
                    $return['km']['km_11']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_11']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['fri_12_km'],$schoolid)){
                    $return['km']['km_12']= pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['fri_12_km']}'");
                    $return['km']['km_12']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_12']['njname'] = $njinfo['sname'];
                }

            }
        }
        if($week ==6){
            if($cook['saturday']){
                $thecook = iunserializer($cook['saturday']);
                if(!empty($thecook['sat_1_sd'])){
                    $return['sd']['sd_1'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_1_sd']}'");
                }
                if(!empty($thecook['sat_2_sd'])){
                    $return['sd']['sd_2'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_2_sd']}'");
                }
                if(!empty($thecook['sat_3_sd'])){
                    $return['sd']['sd_3'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_3_sd']}'");
                }
                if(!empty($thecook['sat_4_sd'])){
                    $return['sd']['sd_4'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_4_sd']}'");
                }
                if(!empty($thecook['sat_5_sd'])){
                    $return['sd']['sd_5'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_5_sd']}'");
                }
                if(!empty($thecook['sat_6_sd'])){
                    $return['sd']['sd_6'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_6_sd']}'");
                }
                if(!empty($thecook['sat_7_sd'])){
                    $return['sd']['sd_7'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_7_sd']}'");
                }
                if(!empty($thecook['sat_8_sd'])){
                    $return['sd']['sd_8'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_8_sd']}'");
                }
                if(!empty($thecook['sat_9_sd'])){
                    $return['sd']['sd_9'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_9_sd']}'");
                }
                if(!empty($thecook['sat_10_sd'])){
                    $return['sd']['sd_10'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_10_sd']}'");
                }
                if(!empty($thecook['sat_11_sd'])){
                    $return['sd']['sd_11'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_11_sd']}'");
                }
                if(!empty($thecook['sat_12_sd'])){
                    $return['sd']['sd_12'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_12_sd']}'");
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['sat_1_km'],$schoolid)){
                    $return['km']['km_1'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_1_km']}'");
                    $return['km']['km_1']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_1']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['sat_2_km'],$schoolid)){
                    $return['km']['km_2'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_2_km']}'");
                    $return['km']['km_2']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_2']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['sat_3_km'],$schoolid)){
                    $return['km']['km_3'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_3_km']}'");
                    $return['km']['km_3']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_3']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['sat_4_km'],$schoolid)){
                    $return['km']['km_4'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_4_km']}'");
                    $return['km']['km_4']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_4']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['sat_5_km'],$schoolid)){
                    $return['km']['km_5'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_5_km']}'");
                    $return['km']['km_5']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_5']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['sat_6_km'],$schoolid)){
                    $return['km']['km_6'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_6_km']}'");
                    $return['km']['km_6']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_6']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['sat_7_km'],$schoolid)){
                    $return['km']['km_7'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_7_km']}'");
                    $return['km']['km_7']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_7']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['sat_8_km'],$schoolid)){
                    $return['km']['km_8'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_8_km']}'");
                    $return['km']['km_8']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_8']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['sat_9_km'],$schoolid)){
                    $return['km']['km_9'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_9_km']}'");
                    $return['km']['km_9']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_9']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['sat_10_km'],$schoolid)){
                    $return['km']['km_10']= pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_10_km']}'");
                    $return['km']['km_10']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_10']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['sat_11_km'],$schoolid)){
                    $return['km']['km_11']= pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_11_km']}'");
                    $return['km']['km_11']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_11']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['sat_12_km'],$schoolid)){
                    $return['km']['km_12']= pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sat_12_km']}'");
                    $return['km']['km_12']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_12']['njname'] = $njinfo['sname'];
                }

            }
        }
        if($week == 0){
            if($cook['sunday']){
                $thecook = iunserializer($cook['sunday']);
                if(!empty($thecook['sun_1_sd'])){
                    $return['sd']['sd_1'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_1_sd']}'");
                }
                if(!empty($thecook['sun_2_sd'])){
                    $return['sd']['sd_2'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_2_sd']}'");
                }
                if(!empty($thecook['sun_3_sd'])){
                    $return['sd']['sd_3'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_3_sd']}'");
                }
                if(!empty($thecook['sun_4_sd'])){
                    $return['sd']['sd_4'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_4_sd']}'");
                }
                if(!empty($thecook['sun_5_sd'])){
                    $return['sd']['sd_5'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_5_sd']}'");
                }
                if(!empty($thecook['sun_6_sd'])){
                    $return['sd']['sd_6'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_6_sd']}'");
                }
                if(!empty($thecook['sun_7_sd'])){
                    $return['sd']['sd_7'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_7_sd']}'");
                }
                if(!empty($thecook['sun_8_sd'])){
                    $return['sd']['sd_8'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_8_sd']}'");
                }
                if(!empty($thecook['sun_9_sd'])){
                    $return['sd']['sd_9'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_9_sd']}'");
                }
                if(!empty($thecook['sun_10_sd'])){
                    $return['sd']['sd_10'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_10_sd']}'");
                }
                if(!empty($thecook['sun_11_sd'])){
                    $return['sd']['sd_11'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_11_sd']}'");
                }
                if(!empty($thecook['sun_12_sd'])){
                    $return['sd']['sd_12'] = pdo_fetch("SELECT sname FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_12_sd']}'");
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['sun_1_km'],$schoolid)){
                    $return['km']['km_1'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_1_km']}'");
                    $return['km']['km_1']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_1']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['sun_2_km'],$schoolid)){
                    $return['km']['km_2'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_2_km']}'");
                    $return['km']['km_2']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_2']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['sun_3_km'],$schoolid)){
                    $return['km']['km_3'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_3_km']}'");
                    $return['km']['km_3']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_3']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['sun_4_km'],$schoolid)){
                    $return['km']['km_4'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_4_km']}'");
                    $return['km']['km_4']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_4']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['sun_5_km'],$schoolid)){
                    $return['km']['km_5'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_5_km']}'");
                    $return['km']['km_5']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_5']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['sun_6_km'],$schoolid)){
                    $return['km']['km_6'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_6_km']}'");
                    $return['km']['km_6']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_6']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['sun_7_km'],$schoolid)){
                    $return['km']['km_7'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_7_km']}'");
                    $return['km']['km_7']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_7']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['sun_8_km'],$schoolid)){
                    $return['km']['km_8'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_8_km']}'");
                    $return['km']['km_8']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_8']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['sun_9_km'],$schoolid)){
                    $return['km']['km_9'] = pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_9_km']}'");
                    $return['km']['km_9']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_9']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['sun_10_km'],$schoolid)){
                    $return['km']['km_10']= pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_10_km']}'");
                    $return['km']['km_10']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_10']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['sun_11_km'],$schoolid)){
                    $return['km']['km_11']= pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_11_km']}'");
                    $return['km']['km_11']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_11']['njname'] = $njinfo['sname'];
                }
                if(CheckIsShowKm($tid,$value['bj_id'],$thecook['sun_12_km'],$schoolid)){
                    $return['km']['km_12']= pdo_fetch("SELECT sname,icon FROM " . tablename('wx_school_classify') . " WHERE sid = '{$thecook['sun_12_km']}'");
                    $return['km']['km_12']['bjname'] = $bjinfo['sname'];
                    $return['km']['km_12']['njname'] = $njinfo['sname'];
                }
            }

        }

    }
    return $return;
}



?>
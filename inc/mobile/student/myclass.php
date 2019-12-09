<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
        global $_W, $_GPC;
        $weid = $_W ['uniacid']; 
		$openid = $_W['openid'];
		$schoolid = intval($_GPC['schoolid']);
        
		//教师列表按教师入职时间先后顺序排列，先入职再前

		$it = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where id = :id ", array(':id' => $_SESSION['user']));	

		if($it){
			$school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " where weid = :weid AND id=:id ", array(':weid' => $weid, ':id' => $schoolid));
			$student = pdo_fetch("SELECT xq_id,bj_id FROM " . tablename($this->table_students) . " WHERE schoolid = :schoolid And id = :id", array(':schoolid' => $schoolid,':id' => $it['sid']));			

			$list = pdo_fetchall("SELECT kcid FROM " . tablename($this->table_order) . " WHERE schoolid = :schoolid And sid = :sid And type = :type And status = :status group by kcid ", array(
				':schoolid' => $schoolid,
				':sid' => $it['sid'],
				':type' => 1,
				':status' => 2
			));	
			
			foreach($list as $key => $row){
				$kecheng = pdo_fetch("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE :schoolid = schoolid And :id = id", array(':id' => $row['kcid'], ':schoolid' => $schoolid));
				$km  = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE :schoolid = schoolid And :sid = sid", array(':sid' => $kecheng['km_id'], ':schoolid' => $schoolid));
				$js  = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE :schoolid = schoolid And :sid = sid", array(':sid' => $kecheng['adrr'], ':schoolid' => $schoolid));
				$teacher = pdo_fetch("SELECT tname FROM " . tablename($this->table_teachers) . " WHERE :schoolid = schoolid And :id = id", array(':id' => $kecheng['maintid'], ':schoolid' => $schoolid)); 
				$check = pdo_fetch("SELECT content FROM " . tablename($this->table_kcpingjia) . " WHERE schoolid = '{$schoolid}' And weid = '{$weid}' and sid ='{$it['sid']}' And kcid = '{$row['kcid']}' and type=2 ");
				$list[$key]['name']   = $kecheng['name'];
				$list[$key]['is_hot'] = $kecheng['is_hot'];
				$list[$key]['kmname'] = $km['sname'];
				$list[$key]['jsname'] = $js['sname'];
				$list[$key]['zjanme'] = $teacher['tname'];
				$list[$key]['kmicon'] = tomedia($km['icon']);
				$list[$key]['kcicon'] = tomedia($kecheng['thumb']);
				$list[$key]['kctype'] = $kecheng['OldOrNew'];
				$list[$key]['ReNum']  = $kecheng['ReNum'];
				$list[$key]['check']  = $check;
				if($kecheng['OldOrNew'] == 0){
					$nowtime = time();
					$starttime = mktime(0,0,0,date("m"),date("d"),date("Y"));
					$endtime = $starttime + 86399;
					$condition1 = " AND date > '{$starttime}' AND date < '{$endtime}'";	
					$condition2 = " AND createtime > '{$starttime}' AND createtime < '{$endtime}'";
					$today = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_kcbiao) . " WHERE kcid = '{$kecheng['id']}' AND schoolid = '{$schoolid}' $condition1 ");
					$list[$key]['todays'] = $today;
					if($today){
						$list[$key]['today'] = true;
					}
					$restks = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_kcsign) . " WHERE kcid = '{$row['kcid']}' AND schoolid = '{$schoolid}' And sid = '{$it['sid']}' And status = 2 ");
					$buyks = pdo_fetch("SELECT ksnum FROM " . tablename($this->table_coursebuy) . " WHERE sid = '{$it['sid']}' And kcid='{$row['kcid']}' And  schoolid='{$schoolid}'");
					$list[$key]['buyks'] = $buyks['ksnum'];//总计购买课时
					$list[$key]['yqks'] = $restks; //已签课时
					$list[$key]['restks'] = $buyks['ksnum'] - $restks;//剩余课时					
					$istodyqd = pdo_fetch('SELECT * FROM ' . tablename($this->table_kcsign) . " WHERE kcid = '{$row['kcid']}' AND schoolid = '{$schoolid}' And sid = '{$it['sid']}' And status= 2 $condition2");
					if($istodyqd){
						$list[$key]['todayqd'] = true;
					}					
				}
				if($kecheng['OldOrNew'] == 1){ //查询签到课时和剩余课时
					$starttime = mktime(0,0,0,date("m"),date("d"),date("Y"));
					$endtime = $starttime + 86399;
					$condition2 = " AND createtime > '{$starttime}' AND createtime < '{$endtime}'";					
					$restks = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_kcsign) . " WHERE kcid = '{$row['kcid']}' AND schoolid = '{$schoolid}' And sid = '{$it['sid']}' And status = 2 ");
					$buyks = pdo_fetch("SELECT ksnum FROM " . tablename($this->table_coursebuy) . " WHERE sid = '{$it['sid']}' And kcid='{$row['kcid']}' And  schoolid='{$schoolid}'");
					$list[$key]['buyks'] = $buyks['ksnum'];//总计购买课时
					$list[$key]['yqks'] = $restks; //已签课时
					$list[$key]['restks'] = $buyks['ksnum'] - $restks;//剩余课时
					$istodyqd = pdo_fetch('SELECT * FROM ' . tablename($this->table_kcsign) . " WHERE kcid = '{$row['kcid']}' AND schoolid = '{$schoolid}' And sid = '{$it['sid']}' And status = 2 $condition2");
					if($istodyqd){
						$list[$key]['todayqd'] = true;
					}					
				}				
				if($kecheng['start'] > time()){
					$list[$key]['type'] = 1;//未开始
				}
				if($kecheng['end'] < time()){
					$list[$key]['type'] = 2;//已结课
				}				
				if($kecheng['start'] < time() && time() < $kecheng['end']){
					$list[$key]['type'] = 3;//授课中
				}
				
			}	
			
			include $this->template(''.$school['style2'].'/myclass');
		}else{
			session_destroy();
		    $stopurl = $_W['siteroot'] .'app/'.$this->createMobileUrl('bangding', array('schoolid' => $schoolid));
			header("location:$stopurl");
			exit;
		}
?>
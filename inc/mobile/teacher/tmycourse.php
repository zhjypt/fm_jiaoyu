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
		$nj_id = $_GPC['nj_id'];
		$notOwner = $_GPC['notOwner'];
		//$userid = intval($_GPC['userid']);
        
		
		$userid = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where :schoolid = schoolid And :weid = weid And :openid = openid And :sid = sid", array(':weid' => $weid, ':schoolid' => $schoolid, ':openid' => $openid, ':sid' => 0), 'id');
		$it = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where weid = :weid AND id=:id ORDER BY id DESC", array(':weid' => $weid, ':id' => $userid['id']));
		$tid_global = $it['tid'];
		if($it){
			
			$bj_t = pdo_fetchall("SELECT sid,sname FROM " . tablename($this->table_classify) . " where weid = :weid AND schoolid = :schoolid And type = :type and is_over!=:is_over ORDER BY CONVERT(sname USING gbk) ASC", array(':weid' => $weid, ':type' => 'theclass', ':schoolid' => $schoolid,':is_over'=>"2"));
			$nj_t = pdo_fetchall("SELECT sid,sname FROM " . tablename($this->table_classify) . " where weid = :weid AND schoolid = :schoolid And type = :type and is_over!=:is_over ORDER BY CONVERT(sname USING gbk) ASC", array(':weid' => $weid, ':type' => 'semester', ':schoolid' => $schoolid,':is_over'=>"2"));
			$bj_str_temp = '0,';
			foreach($bj_t as $key_b=>$value_b){
				$bj_str_temp .=$value_b['sid'].",";
			}
			$bj_str = trim($bj_str_temp,",");
			$nj_str_temp = '0,';
			foreach($nj_t as $key_n=>$value_n){
				$nj_str_temp .=$value_n['sid'].",";
			}
			$nj_str = trim($nj_str_temp,",");
			
			$teacher = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " WHERE schoolid = :schoolid And id = :id", array(':schoolid' => $schoolid,':id' => $it['tid']));
			
			//若为校长 {if $teacher['status'] == 2 ||  is_njzr($teacher['id']) }
			if($teacher['status'] ==2 && !(is_njzr($teacher['id']))){
				$listAll = pdo_fetchall("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE weid = {$_W['uniacid']} AND schoolid = '{$schoolid}' And xq_id ='{$nj_id}' and FIND_IN_SET(bj_id,'{$bj_str}') and FIND_IN_SET(xq_id,'{$nj_str}')  ORDER BY end DESC, xq_id DESC");
				$AllNJ = pdo_fetchall("SELECT sid,sname FROM " . tablename($this->table_classify) . " WHERE weid = {$_W['uniacid']} AND schoolid = '{$schoolid}' AND type='semester' and is_over != 2  ORDER BY  CONVERT(sname USING gbk) ASC");
				//var_dump($AllNJ);
			}elseif($teacher['status'] != 2 && is_njzr($teacher['id'])){
				$AllNJ = getallnj($teacher['id']);
				$listAll = pdo_fetchall("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE weid = {$_W['uniacid']} AND schoolid = '{$schoolid}' And xq_id ='{$nj_id}' and FIND_IN_SET(bj_id,'{$bj_str}') and FIND_IN_SET(xq_id,'{$nj_str}')  ORDER BY end DESC, xq_id DESC");
			}
			
			if($notOwner =='notOwner'){
				$list = $listAll;
			}else{
				if($nj_id != 0){
					$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE weid = {$_W['uniacid']} AND schoolid = '{$schoolid}'  And xq_id ='{$nj_id}' AND (tid like '{$it['tid']},%' OR tid like '%,{$it['tid']}' OR tid like '%,{$it['tid']},%' OR tid='{$it['tid']}')  and FIND_IN_SET(bj_id,'{$bj_str}') and FIND_IN_SET(xq_id,'{$nj_str}')  ORDER BY end DESC");
					$kcstr  = '';
					foreach($list as $keyl=>$valuel){
						$kcstr  .= ','.$valuel['id'];
					}
					$kcstr = trim($kcstr,',');
					$allsign =  pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_kcsign) . " WHERE schoolid = '{$schoolid}' And tid = {$it['tid']} And status= 2 and FIND_IN_SET(kcid,'{$kcstr}') ");
					$gudingsign = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_kcsign) . " WHERE schoolid = '{$schoolid}' And tid = {$it['tid']} And status= 2 And type=0 and  FIND_IN_SET(kcid,'{$kcstr}')");
					$freesign = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_kcsign) . " WHERE schoolid = '{$schoolid}' And tid = {$it['tid']} And status= 2 And type =1 and FIND_IN_SET(kcid,'{$kcstr}')" );
					
				}else{
					$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE weid = {$_W['uniacid']} AND schoolid = '{$schoolid}'  AND (tid like '{$it['tid']},%' OR tid like '%,{$it['tid']}' OR tid like '%,{$it['tid']},%' OR tid='{$it['tid']}') and FIND_IN_SET(bj_id,'{$bj_str}') and FIND_IN_SET(xq_id,'{$nj_str}')  ORDER BY end DESC");
					$allsign =  pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_kcsign) . " WHERE schoolid = '{$schoolid}' And tid = {$it['tid']} And status= 2 ");
					$gudingsign = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_kcsign) . " WHERE schoolid = '{$schoolid}' And tid = {$it['tid']} And status= 2 And type=0 ");
					$freesign = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_kcsign) . " WHERE schoolid = '{$schoolid}' And tid = {$it['tid']} And status= 2 And type =1");
				}
				
			}
			foreach($list as $key => $row){
				$km  = pdo_fetch("SELECT sname,icon FROM " . tablename($this->table_classify) . " WHERE :schoolid = schoolid And :sid = sid", array(':sid' => $row['km_id'], ':schoolid' => $schoolid));
				$js  = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE :schoolid = schoolid And :sid = sid", array(':sid' => $row['adrr'], ':schoolid' => $schoolid)); 
				$list[$key]['kmname'] = $km['sname'];
				$list[$key]['jsname'] = $js['sname'];
				$list[$key]['zjanme'] = $teacher['tname'];
				$list[$key]['kmicon'] = tomedia($km['icon']);
				$list[$key]['kcicon'] = tomedia($row['thumb']);
				$list[$key]['kctype'] = $row['OldOrNew'];
				if($row['OldOrNew'] == 0){
					if(empty($notOwner)){
						$nowtime = time();
						$starttime = mktime(0,0,0,date("m"),date("d"),date("Y"));
						$endtime = $starttime + 86399;
						$condition1 = " AND date > '{$starttime}' AND date < '{$endtime}'";	
						$condition2 = " AND createtime > '{$starttime}' AND createtime < '{$endtime}'";	
						$today = pdo_fetchall('SELECT id FROM ' . tablename($this->table_kcbiao) . " WHERE kcid = '{$row['id']}' AND schoolid = '{$schoolid}' And tid='{$it['tid']}' $condition1 ");
						$list[$key]['restks'] = count($today);
						if($today){
							$list[$key]['today'] = true;
						}
						$istodyqd = pdo_fetch('SELECT * FROM ' . tablename($this->table_kcsign) . " WHERE kcid = '{$row['id']}' AND schoolid = '{$schoolid}' And tid = '{$it['tid']}' And status= 2 $condition2");
						if($istodyqd){
							$list[$key]['todayqd'] = true;
						}
					}else{
						$today = pdo_fetchall('SELECT id FROM ' . tablename($this->table_kcbiao) . " WHERE kcid = '{$row['id']}' AND schoolid = '{$schoolid}' ");
						$list[$key]['restks'] = count($today);
						}					
				}
				if($row['OldOrNew'] == 1){ //查询签到课时和剩余课时
					$starttime = mktime(0,0,0,date("m"),date("d"),date("Y"));
					$endtime = $starttime + 86399;
					$condition2 = " AND createtime > '{$starttime}' AND createtime < '{$endtime}'";					
					$restks = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_kcsign) . " WHERE kcid = '{$row['id']}' AND schoolid = '{$schoolid}' And sid !=0 And status= 2 ");
					$allks = $row['AllNum'];
					$list[$key]['allks'] = $allks;//总课时
					$list[$key]['yqks'] = $restks; //已签课时
					$list[$key]['restks'] = $allks - $restks;//剩余课时
					if(empty($notOwner)){
						$istodyqd = pdo_fetch('SELECT * FROM ' . tablename($this->table_kcsign) . " WHERE kcid = '{$row['id']}' AND schoolid = '{$schoolid}' And tid = '{$it['tid']}' And status = 2 $condition2");
						if($istodyqd){
							$list[$key]['todayqd'] = true;
						}
					}					
				}				
				if($row['start'] > time()){
					$list[$key]['type'] = 1;//未开始
				}
				if($row['end'] < time()){
					$list[$key]['type'] = 2;//已结课
				}				
				if($row['start'] < time() && time() < $row['end']){
					$list[$key]['type'] = 3;//授课中
				}
			}
			$school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " where weid = :weid AND id=:id ", array(':weid' => $weid, ':id' => $schoolid));
			include $this->template(''.$school['style3'].'/tmycourse');
		}else{
			session_destroy();
		    $stopurl = $_W['siteroot'] .'app/'.$this->createMobileUrl('bangding', array('schoolid' => $schoolid));
			header("location:$stopurl");
			exit;
		}
?>
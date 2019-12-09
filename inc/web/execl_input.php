<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */global $_W, $_GPC;
   $operation = in_array ( $_GPC ['op'], array ('default','export','input_cj','input_tea','input_teabj','input_teapf','input_stu','input_stupf','input_card','input_cardschool','input_kc','input_ks','input_scpy','input_buzhu','input_bjpf') ) ? $_GPC ['op'] : 'default';

    if ($operation == 'default') {
	           die ( json_encode ( array (
			         'result' => false,
			         'msg' => '参数错误'
	                ) ) );
    }
	if($operation == 'export'){//读取execl文件并返回文件内容array
		if(empty($_FILES['file'])){
			$file = upload_file($_FILES['file1'], 'excel');
		}else{
			$file = upload_file($_FILES['file'], 'excel');
		}
		if(is_error($file)) {
			$result ['result'] = false;
			$result ['msg'] = $file['message'];
			die (json_encode($result));
		}
		$data = read_excel($file);
		if(is_error($data)) {
			$result ['result'] = false;
			$result ['msg'] = $file['message'];
			die (json_encode($result));
		}
		unset($data[1]);
		if(empty($data)) {
			$result ['result'] = false;
			$result ['msg'] = '表格数据不能为空';
			die (json_encode($result));
		}
		$result ['datas'] = $data;
		$result ['count'] = count($data);
		$result ['result'] = true;
		die (json_encode($result));
	}
	if($operation == 'input_cj'){ //导入成绩
		$line = "";
		$schoolid = $_GPC['schoolid'];
		$result ['line'] = $_GPC['line'];
		$strs = json_decode(str_replace('&quot;','"',$_GPC['execl']),true);
		$banji = pdo_fetch("SELECT sid FROM " . tablename($this->table_classify) . " WHERE sname=:sname And schoolid=:schoolid And type = :type ", array(':sname' => trim($strs[1]), ':schoolid'=> $schoolid,':type'=>'theclass'));
		//名字处理
		$stu = pdo_fetch("SELECT id FROM " . tablename($this->table_students) . " WHERE s_name=:s_name And schoolid=:schoolid And bj_id = :bj_id ", array(':s_name' => trim($strs[0]),':bj_id' => $banji['sid'], ':schoolid'=> $schoolid));
		//科目处理
		$kemu = pdo_fetch("SELECT sid FROM " . tablename($this->table_classify) . " WHERE sname=:sname And schoolid=:schoolid And type = :type ", array(':sname' => trim($strs[2]), ':schoolid'=> $schoolid,':type'=>'subject'));
		//年级处理
		$xueqi = pdo_fetch("SELECT parentid FROM " . tablename($this->table_classify) . " WHERE sid=:sid  And schoolid=:schoolid And type = :type ", array(':sid' => $banji['sid'],  ':schoolid'=>$schoolid,':type'=>'theclass'));
		$strss = '';
		foreach($strs as $row){
			$strss .= $row.',';
		}
		$result ['tips'] = '';
		if(empty($banji) || empty($stu) || empty($kemu) || empty($_GPC['qh_id'])){
			if(empty($banji)){
				$result ['tips'] .= '|无此班级-'.$strs[1].'|';
			}
			if(empty($_GPC['qh_id'])){
				$result ['tips'] .= '|无此期号|';
			}
			if(empty($stu)){
				$result ['tips'] .= '|无此学生-'.$strs[0].'|';
			}
			if(empty($kemu)){
				$result ['tips'] .= '|无此科目-'.$strs[2].'|';
			}
			$result ['result'] = false;
			$result ['msg'] = '本条失败';
			die (json_encode($result));
		}else{
			$check = pdo_fetch("SELECT * FROM " . tablename($this->table_score) . " WHERE sid=:sid And schoolid=:schoolid And qh_id=:qh_id And km_id=:km_id ", array(':sid' => $stu['id'], ':schoolid'=> $schoolid, ':qh_id'=> $_GPC['qh_id'], ':km_id'=> $kemu['sid']));
			if($check){
				$result ['tips'] .= '|本期本科已导入-'.$strs[0].'|';
				$result ['result'] = false;
				$result ['msg'] = '本条失败';
				die (json_encode($result));
			}
			$insert = array(
				'weid' => $_W['uniacid'],
				'schoolid' => $schoolid,
				'sid' => $stu['id'],
				'xq_id' => $xueqi['parentid'],
				'qh_id' => $_GPC['qh_id'],
				'bj_id' => $banji['sid'],
				'km_id' => $kemu['sid'],
				'my_score' => trim($strs[3]),
				'info' => trim($strs[4]),
				'is_absent' => intval($strs[5]) == 1 ? 1 : 0,
				'createtime' => time()
			);
			pdo_insert($this->table_score, $insert);
			$score_id = pdo_insertid();
			$result ['strs'] = rtrim($strss,',');
			$result ['result'] = true;
			die (json_encode($result));
		}
	}
	if($operation == 'input_tea'){ //老师
		$line = "";
		$schoolid = $_GPC['schoolid'];
		$result ['line'] = $_GPC['line'];
		$strs = json_decode(str_replace('&quot;','"',$_GPC['execl']),true);
		$assess = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " WHERE tname=:tname AND mobile=:mobile And schoolid=:schoolid ", array(':tname' => trim($strs[0]), ':mobile' => trim($strs[3]), ':schoolid'=> $schoolid));
		if($strs[11]){
			$jsfz = pdo_fetch("SELECT sid FROM " . tablename($this->table_classify) . " WHERE sname=:sname And schoolid=:schoolid And type=:type", array(':sname' => trim($strs[11]), ':schoolid'=> $schoolid, ':type'=> 'jsfz'));
			if(empty($jsfz)){
				$result ['tips'] .= '|系统无此分组-'.$strs[11].'|';
				$result ['result'] = false;
				$result ['msg'] = '本条失败';
				die (json_encode($result));
			}
		}
		$strss = '';
		foreach($strs as $row){
			$strss .= $row.',';
		}
		$result ['tips'] = '';
		if(!empty($assess)){
			$result ['tips'] .= '|此用户已导入-'.$strs[0].'|';
			$result ['result'] = false;
			$result ['msg'] = '本条失败';
			die (json_encode($result));
		}else{
			$insert = array(
				'weid' => $_W['uniacid'],
				'schoolid' => $schoolid,
				'tname' => trim($strs[0]),
				'birthdate' => strtotime(trim($strs[1])),
				'tel' => trim($strs[2]),
				'mobile' => trim($strs[3]),
				'email' => trim($strs[4]),
				'fz_id' => $jsfz['sid'],
				'jiontime' => strtotime(trim($strs[5])),
				'headinfo' => trim($strs[6]),
				'info' => trim($strs[7]),
				'sex' => trim($strs[8]),
				'jinyan' => trim($strs[9]),
				'status' => 1,
				'code' => empty($strs[10]) ? $rand : trim($strs[10]),
				'is_show' => 0,
				'uid' => 0,
			);
			pdo_insert($this->table_teachers, $insert);
			$score_id = pdo_insertid();
			$result ['strs'] = rtrim($strss,',');
			$result ['result'] = true;
			die (json_encode($result));
		}
	}
	if($operation == 'input_teabj'){ //老师
		$line = "";
		$result ['tips'] = '';
		$schoolid = $_GPC['schoolid'];
		$result ['line'] = $_GPC['line'];
		$strs = json_decode(str_replace('&quot;','"',$_GPC['execl']),true);
		if(preg_match('/[\x{4e00}-\x{9fa5}]/u', $strs[2])>0 || preg_match('/[a-zA-Z]/',$strs[2])>0){//含有中文或英文处理
			$bjinfo = pdo_fetch("SELECT sid FROM " . tablename($this->table_classify) . " WHERE sname=:sname And schoolid=:schoolid And type=:type", array(':sname' => trim($strs[2]), ':schoolid'=> $schoolid, ':type'=> 'theclass'));
			if($bjinfo){
				$bj_id = $bjinfo['sid'];
			}else{
				$result ['tips'] .= '|无此班级|'.$strs[2];
				$result ['result'] = false;
				$result ['msg'] = '本条失败';
				die (json_encode($result));
			}
		}else{
			$bj_id = trim($strs[2]);
		}
		if(preg_match('/[\x{4e00}-\x{9fa5}]/u', $strs[3])>0 || preg_match('/[a-zA-Z]/',$strs[3])>0){//含有中文或英文处理
			$kminfo = pdo_fetch("SELECT sid FROM " . tablename($this->table_classify) . " WHERE sname=:sname And schoolid=:schoolid And type=:type", array(':sname' => trim($strs[3]), ':schoolid'=> $schoolid, ':type'=> 'subject'));
			if($kminfo){
				$km_id = $kminfo['sid'];
			}else{
				$result ['tips'] .= '|无此科目|'.$strs[3];
				$result ['result'] = false;
				$result ['msg'] = '本条失败';
				die (json_encode($result));
			}
		}else{
			$km_id = trim($strs[3]);
		}
		$class = pdo_fetch("SELECT * FROM " . tablename($this->table_class) . " WHERE tid=:tid AND bj_id=:bj_id AND km_id=:km_id And schoolid=:schoolid ", array(':tid' => trim($strs[0]), ':bj_id' => $bj_id, ':km_id' => $km_id,':schoolid'=> $schoolid));		
		$strss = '';
		foreach($strs as $row){
			$strss .= $row.',';
		}
		if(!empty($class)){
			$result ['tips'] .= $strs[1].'|本班本科有重复|';
			$result ['result'] = false;
			$result ['msg'] = '本条失败';
			die (json_encode($result));
		}else{		
			$insert = array(
				'weid' => $_W['uniacid'],
				'schoolid' => $schoolid,
				'tid' => trim($strs[0]),
				'bj_id' => $bj_id,
				'km_id' => $km_id,
				'type' => 1
			);
			pdo_insert($this->table_class, $insert);
			$score_id = pdo_insertid();
			$result ['strs'] = rtrim($strss,',');
			$result ['result'] = true;
			die (json_encode($result));
		}
	}
	if($operation == 'input_teapf'){ //老师评分
		$strs = json_decode(str_replace('&quot;','"',$_GPC['execl']),true);
		$line = "";
		$result ['tips'] = '';
		$schoolid = $_GPC['schoolid'];
		$ob_id = $_GPC['ob_id'];
		$result ['line'] = $_GPC['line'];
		if(empty($ob_id)){
			$result ['tips'] .= '|请选择项目|';
			$result ['result'] = false;
			$result ['msg'] = '本条失败';
			die (json_encode($result));
		}
		$tid = pdo_fetch("SELECT id FROM " . tablename($this->table_teachers) . " WHERE tname=:tname AND schoolid=:schoolid  ", array(':tname' => trim($strs[0]),':schoolid'=> $schoolid));	
		$strss = '';
		foreach($strs as $row){
			$strss .= $row.',';
		}
		if(empty($tid)){
			$result ['tips'] .= '|无此用户|-'.$strs[0];
			$result ['result'] = false;
			$result ['msg'] = '本条失败';
			die (json_encode($result));
		}else{
			$parentobid = pdo_fetch("SELECT parentid FROM " . tablename($this->table_classify) . " WHERE sid='{$ob_id}' AND weid='{$_W['uniacid']}' And schoolid='{$schoolid}' and type='tscoreobject'")['parentid'];
			$insert['parentobid'] = $parentobid;
			$fromtid = $_GPC['fromtid'];
			if($_GPC['fromtid'] == 'founder' || $_GPC['fromtid'] == 'owner' ){
				$fromtid = -1 ;
			}else{
				$fromtea = pdo_fetch("SELECT fz_id FROM " . tablename($this->table_teachers) . " WHERE id=:id And schoolid=:schoolid  ", array(':id' => $fromtid, ':schoolid'=> $schoolid));
				$fromfzid = $fromtea['fz_id'];
			}			
			$insert = array(
				'weid' => $_W['uniacid'],
				'schoolid' => $schoolid,
				'tid' => $tid['id'],
				'obid' => $ob_id,
				'parentobid' => $parentobid,
				'fromtid' => $fromtid,
				'fromfzid' => $fromfzid,
				'score' => trim($strs[1]),
				'scoretime' => strtotime($strs[2]),
				'type' => '0',
				'createtime' => time(),
			);
			$check = pdo_fetch("SELECT id FROM " . tablename($this->table_teascore) . " WHERE tid='{$tid['id']}' AND weid='{$_W['uniacid']}' And schoolid='{$schoolid}' and obid = '{$ob_id}' and scoretime = '{$insert['scoretime']}' and type = 0 ");
			if(!empty($check)){
				pdo_update($this->table_teascore, $insert,array('id'=>$check['id']));
			}else{
				pdo_insert($this->table_teascore, $insert);
			}
			$result ['strs'] = rtrim($strss,',');
			$result ['result'] = true;
			die (json_encode($result));
		}
	}
	if($operation == 'input_stu'){ //学生
		$line = "";
		$schoolid = $_GPC['schoolid'];
		$result ['line'] = $_GPC['line'];
		$strs = json_decode(str_replace('&quot;','"',$_GPC['execl']),true);		
		//年级处理
		$xueqi = pdo_fetch("SELECT sid FROM " . tablename($this->table_classify) . " WHERE sname=:sname And schoolid=:schoolid And type=:type", array(':sname' => trim($strs[8]), ':schoolid'=> $schoolid, ':type'=> 'semester'));
		//班级处理
		$banji = pdo_fetch("SELECT sid,parentid FROM " . tablename($this->table_classify) . " WHERE sname=:sname And schoolid=:schoolid And type=:type", array(':sname' => trim($strs[9]), ':schoolid'=> $schoolid, ':type'=> 'theclass'));
		$strss = '';
		foreach($strs as $row){
			$strss .= $row.',';
		}
		$result ['tips'] = '';
		if(empty($banji['parentid']) || empty($banji)){
			if(empty($banji['parentid'])){
				$result ['tips'] .= '|班级无归属年级-'.trim($strs[9]).'|';
			}
			if(empty($banji)){
				$result ['tips'] .= '|无此班级-'.trim($strs[9]).'|';
			}	
			$result ['result'] = false;
			$result ['msg'] = '本条失败';
			die (json_encode($result));
		}else{		
		//绑定码
			$student = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " WHERE bj_id=:bj_id AND mobile=:mobile AND s_name=:s_name And schoolid=:schoolid ", array(':bj_id' => trim($banji['sid']),':mobile' => trim($strs[3]),':s_name' => trim($strs[0]), ':schoolid'=> $schoolid));
			if(!empty($student)){
				$result ['tips'] .= '|重复-'.trim($strs[0]).'|'.trim($strs[3]);
				$result ['result'] = false;
				$result ['msg'] = '本条失败';
				die (json_encode($result));
			}	
			$randStr = str_shuffle('123456789');
			$rand = substr($randStr,0,6);		
			$insert = array(
				'weid' => $_W['uniacid'],
				'schoolid' => $schoolid,
				's_name' => trim($strs[0]),
				'sex' => trim($strs[1]),
				'birthdate' => strtotime(trim($strs[2])),
				'mobile' => trim($strs[3]),
				'homephone' => trim($strs[4]),
				'seffectivetime' => strtotime($strs[5]),
				'stheendtime' => strtotime($strs[6]),
				'area_addr' => trim($strs[7]),
				'numberid' => trim($strs[10]),
				'xq_id' => $xueqi['sid'],
				'bj_id' => $banji['sid'],
				'code' => empty($strs[11]) ? $rand : trim($strs[11]),
				's_type' => empty($strs[12]) ? 0 : intval($strs[12]),
				'createdate' => time()
			);
			pdo_insert($this->table_students, $insert);
			$score_id = pdo_insertid();
			$result ['strs'] = rtrim($strss,',');
			$result ['result'] = true;
			die (json_encode($result));
		}
	}
	if($operation == 'input_stupf'){ //学生评分
		$strs = json_decode(str_replace('&quot;','"',$_GPC['execl']),true);
		$line = "";
		$schoolid = $_GPC['schoolid'];
		$result ['line'] = $_GPC['line'];
		$fromtid = $_GPC['fromtid'];
		$result ['tips'] = '';
		//班级处理
		$banji = pdo_fetch("SELECT sid,parentid FROM " . tablename($this->table_classify) . " WHERE sname=:sname And schoolid=:schoolid And type=:type", array(':sname' => trim($strs[1]), ':schoolid'=> $schoolid, ':type'=> 'theclass'));
		$sid = pdo_fetch("SELECT id FROM " . tablename($this->table_students) . " WHERE s_name=:s_name And schoolid=:schoolid and bj_id = :bj_id  ", array(':s_name' => trim($strs[0]), ':schoolid'=> $schoolid,'bj_id'=>$banji['sid']));
		$strss = '';
		foreach($strs as $row){
			$strss .= $row.',';
		}
		if(empty($banji)){
			$result ['tips'] .= '|无此班级-'.trim($strs[1]).'|';
			$result ['result'] = false;
			$result ['msg'] = '本条失败';
			die (json_encode($result));
		}	
		if(empty($sid)){
			$result ['tips'] .= '|无此用户-'.trim($strs[0]).'|';
			$result ['result'] = false;
			$result ['msg'] = '本条失败';
			die (json_encode($result));
		}else{
			$insert = array();
			$insert['sid'] = $sid['id'];
			$insert['score'] = trim($strs[2]);
			$insert['bj_id'] = $banji['sid'];
			$insert['nj_id'] = $banji['parentid'];
			$insert['fromtid'] = $fromtid;
			if($fromtid == 'founder' || $fromtid == 'owner' ){
				$insert['fromtid'] = $fromtid;
			}else{
				$fromtea = pdo_fetch("SELECT fz_id FROM " . tablename($this->table_teachers) . " WHERE id=:id And schoolid=:schoolid  ", array(':id' => $fromtid,':schoolid'=> $schoolid));
				$insert['fromfzid'] = $fromtea['fz_id'];
			}
			$insert['scoretime'] = strtotime($strs[3]);
			$insert['schoolid'] = $schoolid;
			$insert['weid'] = $_W['uniacid'];
			$insert['type'] = 1;
			$insert['createtime'] = time();
			$check = pdo_fetch("SELECT id FROM " . tablename($this->table_teascore) . " WHERE sid='{$sid['id']}' And schoolid='{$schoolid}'  and scoretime = '{$insert['scoretime']}' and type = 1 ");
			if(!empty($check)){
				pdo_update($this->table_teascore, $insert,array('id'=>$check['id']));
			}else{
				pdo_insert($this->table_teascore, $insert);
			}
			$result ['strs'] = rtrim($strss,',');
			$result ['result'] = true;
			die (json_encode($result));
		}
	}
	if($operation == 'input_kc'){ //导入课程
		$strs = json_decode(str_replace('&quot;','"',$_GPC['execl']),true);
		$line = "";
		$schoolid = $_GPC['schoolid'];
		$result ['line'] = $_GPC['line'];
		$result ['tips'] = '';
		$strss = '';
		foreach($strs as $row){
			$strss .= $row.',';
		}
		//名字处理
		$tid = pdo_fetch("SELECT id FROM " . tablename($this->table_teachers) . " WHERE tname=:tname And schoolid=:schoolid ", array(':tname' => trim($strs[0]), ':schoolid'=> $schoolid));
		//年级处理
		$xueqi = pdo_fetch("SELECT sid FROM " . tablename($this->table_classify) . " WHERE sname=:sname And schoolid=:schoolid And type=:type", array(':sname' => trim($strs[1]), ':schoolid'=> $schoolid, ':type'=> 'semester'));
		//科目处理
		$kemu = pdo_fetch("SELECT sid FROM " . tablename($this->table_classify) . " WHERE sname=:sname And schoolid=:schoolid And type=:type", array(':sname' => trim($strs[3]), ':schoolid'=> $schoolid, ':type'=> 'subject'));
		//教室处理
		$room = pdo_fetch("SELECT sid FROM " . tablename($this->table_classify) . " WHERE sname=:sname And schoolid=:schoolid And type=:type", array(':sname' => trim($strs[8]), ':schoolid'=> $schoolid, ':type'=> 'addr'));
		if(empty($xueqi)){
			$result ['tips'] .= '|无此年级-'.trim($strs[1]).'|';
			$result ['result'] = false;
			$result ['msg'] = '本条失败';
			die (json_encode($result));
		}	
		if(empty($kemu)){
			$result ['tips'] .= '|无此科目-'.trim($strs[3]).'|';
			$result ['result'] = false;
			$result ['msg'] = '本条失败';
			die (json_encode($result));
		}
		if(empty($tid)){
			$result ['tips'] .= '|无此用户-'.trim($strs[0]).'|';
			$result ['result'] = false;
			$result ['msg'] = '本条失败';
			die (json_encode($result));
		}else{
			$check = pdo_fetch("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE name=:name And schoolid=:schoolid ", array(':name' => trim($strs[2]), ':schoolid'=> $schoolid));
			if($check){
				$result ['tips'] .= '|重复导入-'.trim($strs[2]).'|';
				$result ['result'] = false;
				$result ['msg'] = '本条失败';
				die (json_encode($result));
			}
			$insert = array();
			$insert['tid'] = $tid['id'];
			$insert['xq_id'] = $xueqi['sid'];
			$insert['name'] = trim($strs[2]);
			$insert['km_id'] = $kemu['sid'];
			$insert['minge'] = trim($strs[4]);
			$insert['yibao'] = trim($strs[5]);
			$insert['cose'] = trim($strs[6]);
			$insert['dagang'] = trim($strs[7]);
			$insert['adrr'] = $room['sid'];
			$insert['is_hot'] = trim($strs[9]);
			$insert['is_show'] = 1;
			$insert['start'] = strtotime(trim($strs[10]));
			$insert['end'] = strtotime(trim($strs[11]));
			$insert['schoolid'] = $schoolid;
			$insert['weid'] = $_W['uniacid'];
			pdo_insert($this->table_tcourse, $insert);
			$result ['strs'] = rtrim($strss,',');
			$result ['result'] = true;
			die (json_encode($result));
		}
	}
	
	if($operation == 'input_ks'){ //导入课时
		$strs = json_decode(str_replace('&quot;','"',$_GPC['execl']),true);
		$line = "";
		$schoolid = $_GPC['schoolid'];
		$result ['line'] = $_GPC['line'];
		$result ['tips'] = '';
		$strss = '';
		foreach($strs as $row){
			$strss .= $row.',';
		}
		if(preg_match('/[\x{4e00}-\x{9fa5}]/u', $strs[0])>0 || preg_match('/[a-zA-Z]/',$strs[0])>0){//含有中文或英文处理
			$kc = pdo_fetch("SELECT id FROM " . tablename($this->table_tcourse) . " WHERE name=:name And schoolid=:schoolid ", array(':name' => trim($strs[0]), ':schoolid'=> $schoolid));
			if($kc){
				$kcid = $kc['id'];
			}else{
				$result ['tips'] .= '|无此课程|'.$strs[0];
				$result ['result'] = false;
				$result ['msg'] = '本条失败';
				die (json_encode($result));
			}
		}else{
			$kcid = trim($strs[0]);
		}
		if(preg_match('/[\x{4e00}-\x{9fa5}]/u', $strs[1])>0 || preg_match('/[a-zA-Z]/',$strs[1])>0){//含有中文或英文处理
			$tea = pdo_fetch("SELECT id,tname FROM " . tablename($this->table_teachers) . " WHERE tname=:tname And schoolid=:schoolid ", array(':tname' => trim($strs[1]), ':schoolid'=> $schoolid));
			if($tea){
				$tid = $tea['id'];
				$tname = $tea['tname'];
			}else{
				$result ['tips'] .= '|无此老师|'.$strs[1];
				$result ['result'] = false;
				$result ['msg'] = '本条失败';
				die (json_encode($result));
			}
		}else{
			$tid = trim($strs[1]);
			$tname = trim($strs[1]);
		}
		
		//获取时段
		$shiduan = pdo_fetch("SELECT sid,sd_start FROM " . tablename($this->table_classify) . " WHERE sname=:sname And schoolid=:schoolid And type=:type", array(':sname' => trim($strs[2]), ':schoolid'=> $schoolid, ':type'=> 'timeframe'));
		//获取教室ID
		$room = pdo_fetch("SELECT sid FROM " . tablename($this->table_classify) . " WHERE sname=:sname And schoolid=:schoolid And type=:type", array(':sname' => trim($strs[3]), ':schoolid'=> $schoolid, ':type'=> 'addr'));
		$checkkc = pdo_fetch("SELECT tid FROM " . tablename($this->table_tcourse) . " WHERE id=:id ", array(':id' => $kcid));
		if(!in_array($tid,explode(',',$checkkc['tid']))){
			$result ['tips'] .= '|非本课老师|-'.$tname;
			$result ['result'] = false;
			$result ['msg'] = '本条失败';
			die (json_encode($result));
		}
		if(empty($strs[5])){
			$result ['tips'] .= '|请设置日期';
			$result ['result'] = false;
			$result ['msg'] = '本条失败';
			die (json_encode($result));
		}
		if(empty($shiduan)){
			$result ['tips'] .= '|无此时段-'.trim($strs[2]).'|';
			$result ['result'] = false;
			$result ['msg'] = '本条失败';
			die (json_encode($result));
		}	
		if(empty($room)){
			$result ['tips'] .= '|无此教室-'.trim($strs[3]).'|';
			$result ['result'] = false;
			$result ['msg'] = '本条失败';
			die (json_encode($result));
		}
		$settime = strtotime($strs[5]);
		$dates = date('Y-m-d',$settime);
		$riqi = explode ('-', $dates);
		$starttime = mktime(0,0,0,$riqi[1],$riqi[2],$riqi[0]);
		$endtime = $starttime + 86399;
		$condition = " AND date > '{$starttime}' AND date < '{$endtime}'";
		$check = pdo_fetchall("SELECT * FROM " . tablename($this->table_kcbiao) . " WHERE kcid='{$kcid}' And sd_id='{$shiduan['sid']}' $condition ");
		if($check){
			$result ['tips'] .= '|当日本节已导入-'.trim($strs[5]).'|'.trim($strs[2]);
			$result ['result'] = false;
			$result ['msg'] = '本条失败';
			die (json_encode($result));
		}
		$checkroom = pdo_fetchall("SELECT * FROM " . tablename($this->table_kcbiao) . " WHERE addr_id='{$room['sid']}' And sd_id='{$shiduan['sid']}' $condition ");
		if($checkroom){
			$result ['tips'] .= '|当日本节教室已排课-'.trim($strs[3]).'|'.trim($strs[5]).trim($strs[2]);
			$result ['result'] = false;
			$result ['msg'] = '本条失败';
			die (json_encode($result));
		}
		$checkorder = pdo_fetch("SELECT id FROM " . tablename($this->table_kcbiao) . " WHERE kcid=:kcid And nub=:nub ", array(':kcid' => $kcid, ':nub'=> trim($strs[4])));
		if(!empty($checkorder)){
			$result ['tips'] .= '|课时第次重复-'.trim($strs[0]).'|';
			$result ['result'] = false;
			$result ['msg'] = '本条失败';
			die (json_encode($result));
		}else{
			$insert = array();
			$insert['kcid'] = $kcid;
			$insert['tid'] = $tid;
			$insert['sd_id'] = $shiduan['sid'];
			$insert['addr_id'] = $room['sid'];
			$insert['nub'] = trim($strs[4]);
			$lasttime = $dates.date(" H:i",$shiduan['sd_start']);
			$insert['date'] = strtotime($lasttime);
			$insert['schoolid'] = $schoolid;
			$insert['weid'] = $_W['uniacid'];
			pdo_insert($this->table_kcbiao, $insert);
			$result ['strs'] = rtrim($strss,',');
			$result ['result'] = true;
			die (json_encode($result));
		}
	}
	if($operation == 'input_scpy'){ //导入评语
		$weid = $_W['uniacid'];
		$strs = json_decode(str_replace('&quot;','"',$_GPC['execl']),true);
		$line = "";
		$schoolid = $_GPC['schoolid'];
		$result ['line'] = $_GPC['line'];
		$result ['tips'] = '';
		$strss = '';
		foreach($strs as $row){
			$strss .= $row.',';
		}
		$scpyk = pdo_fetch("SELECT id FROM " . tablename($this->table_scpy) . " WHERE title=:title And schoolid=:schoolid ", array(':title' => trim($strs[1]), ':schoolid'=> $schoolid));
		if(!empty($scpyk)){
			$result ['tips'] .= '|重复内容|';
			$result ['result'] = false;
			$result ['msg'] = '本条失败';
			die (json_encode($result));
		}else{
			$insert = array();
			$insert['weid'] = $weid; 
			$insert['schoolid'] = $schoolid;
			$insert['ssort'] = trim($strs[0]);
			$insert['title'] = trim($strs[1]);
			$insert['createtime'] = time();
			pdo_insert($this->table_scpy, $insert);
			$result ['strs'] = rtrim($strss,',');
			$result ['result'] = true;
			die (json_encode($result));
		}	
	}
	if($operation == 'input_card'){ //管理导卡
		$weid = $_W['uniacid'];
		$strs = json_decode(str_replace('&quot;','"',$_GPC['execl']),true);
		$line = "";
		$schoolid = $_GPC['schoolid'];
		$result ['line'] = $_GPC['line'];
		$result ['tips'] = '';
		$strss = '';
		foreach($strs as $row){
			$strss .= $row.',';
		}
		if (empty($strs[4])){
			$result ['tips'] .= '|未设置截至日期|';
			$result ['result'] = false;
			$result ['msg'] = '本条失败';
			die (json_encode($result));
		}
		$card = pdo_fetch("SELECT id FROM " . tablename($this->table_idcard) . " WHERE idcard=:idcard And schoolid=:schoolid ", array(':idcard' => trim($strs[0]), ':schoolid'=> $schoolid));
		if(!empty($card)){
			$result ['tips'] .= '|卡号重复|'.$strs[0];
			$result ['result'] = false;
			$result ['msg'] = '本条失败';
			die (json_encode($result));
		}
		if (!empty($strs[1]) && !empty($strs[2])){
			$result ['tips'] .= '|格式不正确|';
			$result ['result'] = false;
			$result ['msg'] = '本条失败';
			die (json_encode($result));
		}
		if (!empty($strs[1])){
			$banji = pdo_fetch("SELECT sid FROM " . tablename($this->table_classify) . " WHERE sname=:sname And schoolid=:schoolid And type=:type ", array(':sname' => trim($strs[3]), ':schoolid'=> $schoolid, ':type'=> 'theclass'));
			if(!empty($banji)){
				$sid = pdo_fetch("SELECT id,icon FROM " . tablename($this->table_students) . " WHERE s_name=:s_name And schoolid=:schoolid And bj_id=:bj_id ", array(':s_name' => trim($strs[1]), ':schoolid'=> $schoolid, ':bj_id'=> $banji['sid']));
				if(!empty($sid)){
					$checkcard = pdo_fetch("SELECT id FROM " . tablename($this->table_idcard) . " WHERE sid=:sid And schoolid=:schoolid And pard=:pard", array(':sid' => $sid['id'], ':pard'=> intval($strs[5]), ':schoolid'=> $schoolid));
					if($checkcard){
						$result ['tips'] .= '|本关系已绑卡|'.$strs[1].'-'.$strs[5];
						$result ['result'] = false;
						$result ['msg'] = '本条失败';
						die (json_encode($result));
					}
					if (empty($strs[5])){
						$result ['tips'] .= '|未设置关系|';
						$result ['result'] = false;
						$result ['msg'] = '本条失败';
						die (json_encode($result));
					}
					if (empty($strs[6])){
						$result ['tips'] .= '|未填持卡人姓名|';
						$result ['result'] = false;
						$result ['msg'] = '本条失败';
						die (json_encode($result));
					}
				}else{
					$result ['tips'] .= '|无此用户|'.$strs[3].'-'.$strs[1];
					$result ['result'] = false;
					$result ['msg'] = '本条失败';
					die (json_encode($result));
				}
			}else{
				$result ['tips'] .= '|系统无此班级|'.$strs[3];
				$result ['result'] = false;
				$result ['msg'] = '本条失败';
				die (json_encode($result));
			}
			$usertype = 0;
		}
		if (!empty($strs[2])){
			$tid = pdo_fetch("SELECT id,tname,thumb FROM " . tablename($this->table_teachers) . " WHERE tname=:tname And schoolid=:schoolid ", array(':tname' => trim($strs[2]), ':schoolid'=> $schoolid));
			if($tid){
				$techercard = pdo_fetch("SELECT id FROM " . tablename($this->table_idcard) . " WHERE  tid=:tid And schoolid=:schoolid ", array(':tid'=> $tid['id'], ':schoolid'=> $schoolid));
				if(!empty($techercard)){
					$result ['tips'] .= '|已绑其他卡|'.$tid['tname'];
					$result ['result'] = false;
					$result ['msg'] = '本条失败';
					die (json_encode($result));
				}
			}else{
				$result ['tips'] .= '|无此用户|'.$strs[2];
				$result ['result'] = false;
				$result ['msg'] = '本条失败';
				die (json_encode($result));
			}
			$usertype = 1;
		}
		$insert = array();
		$insert['weid'] = $weid; 
		$insert['schoolid'] = $schoolid;
		$insert['idcard'] = trim($strs[0]);
		$insert['sid'] = empty($sid) ? 0 : intval($sid['id']);
		$insert['tid'] = empty($tid) ? 0 : intval($tid['id']);
		$insert['bj_id'] = empty($banji) ? 0 : intval($banji['sid']);
		$insert['createtime'] = empty($strs[6]) ? 0 : time();
		$insert['severend'] = strtotime(trim($strs[4]));
		$insert['spic'] = $sid['icon'];
		$insert['tpic'] = $tid['thumb'];
		$insert['is_on'] = empty($strs[6]) ? 0 : 1;
		$insert['usertype'] = $usertype;
		$insert['pard'] = empty($strs[5]) ? 0 : intval($strs[5]);
		$insert['pname'] = empty($strs[6]) ? 0 : trim($strs[6]);
		pdo_insert($this->table_idcard, $insert);
		$result ['strs'] = rtrim($strss,',');
		$result ['result'] = true;
		die (json_encode($result));
			
	}
	if($operation == 'input_cardschool'){ //管理导卡
		$weid = $_W['uniacid'];
		$strs = json_decode(str_replace('&quot;','"',$_GPC['execl']),true);
		$line = "";
		$schoolid = $_GPC['schoolid'];
		$result ['line'] = $_GPC['line'];
		$result ['tips'] = '';
		$strss = '';
		foreach($strs as $row){
			$strss .= $row.',';
		}
		if (empty($strs[4])){
			$result ['tips'] .= '|未设置截至日期|';
			$result ['result'] = false;
			$result ['msg'] = '本条失败';
			die (json_encode($result));
		}
		$card = pdo_fetch("SELECT id FROM " . tablename($this->table_idcard) . " WHERE idcard=:idcard And schoolid=:schoolid ", array(':idcard' => trim($strs[0]), ':schoolid'=> $schoolid));
		if(empty($card)){
			$result ['tips'] .= '|无效卡|'.$strs[0];
			$result ['result'] = false;
			$result ['msg'] = '本条失败';
			die (json_encode($result));
		}
		if (!empty($strs[1]) && !empty($strs[2])){
			$result ['tips'] .= '|格式不正确|';
			$result ['result'] = false;
			$result ['msg'] = '本条失败';
			die (json_encode($result));
		}
		if (empty($strs[1]) && empty($strs[2])){
			$result ['tips'] .= '|格式不正确|';
			$result ['result'] = false;
			$result ['msg'] = '本条失败';
			die (json_encode($result));
		}
		if (!empty($strs[1])){
			$banji = pdo_fetch("SELECT sid FROM " . tablename($this->table_classify) . " WHERE sname=:sname And schoolid=:schoolid And type=:type ", array(':sname' => trim($strs[3]), ':schoolid'=> $schoolid, ':type'=> 'theclass'));
			if(!empty($banji)){
				$sid = pdo_fetch("SELECT id,icon FROM " . tablename($this->table_students) . " WHERE s_name=:s_name And schoolid=:schoolid And bj_id=:bj_id ", array(':s_name' => trim($strs[1]), ':schoolid'=> $schoolid, ':bj_id'=> $banji['sid']));
				if(!empty($sid)){
					$checkcard = pdo_fetch("SELECT id FROM " . tablename($this->table_idcard) . " WHERE sid=:sid And schoolid=:schoolid And pard=:pard", array(':sid' => $sid['id'], ':pard'=> intval($strs[5]), ':schoolid'=> $schoolid));
					if($checkcard){
						$result ['tips'] .= '|本关系已绑卡|'.$strs[1].'-'.$strs[5];
						$result ['result'] = false;
						$result ['msg'] = '本条失败';
						die (json_encode($result));
					}
					if (empty($strs[5])){
						$result ['tips'] .= '|未设置关系|';
						$result ['result'] = false;
						$result ['msg'] = '本条失败';
						die (json_encode($result));
					}
					if (empty($strs[6])){
						$result ['tips'] .= '|未填持卡人姓名|';
						$result ['result'] = false;
						$result ['msg'] = '本条失败';
						die (json_encode($result));
					}
				}else{
					$result ['tips'] .= '|无此用户|'.$strs[3].'-'.$strs[1];
					$result ['result'] = false;
					$result ['msg'] = '本条失败';
					die (json_encode($result));
				}
			}else{
				$result ['tips'] .= '|系统无此班级|'.$strs[3];
				$result ['result'] = false;
				$result ['msg'] = '本条失败';
				die (json_encode($result));
			}
		}
		if (!empty($strs[2])){
			$tid = pdo_fetch("SELECT id,tname,thumb FROM " . tablename($this->table_teachers) . " WHERE tname=:tname And schoolid=:schoolid ", array(':tname' => trim($strs[2]), ':schoolid'=> $schoolid));
			if($tid){
				$techercard = pdo_fetch("SELECT id FROM " . tablename($this->table_idcard) . " WHERE  tid=:tid And schoolid=:schoolid ", array(':tid'=> $tid['id'], ':schoolid'=> $schoolid));
				if(!empty($techercard)){
					$result ['tips'] .= '|已绑其他卡|'.$tid['tname'];
					$result ['result'] = false;
					$result ['msg'] = '本条失败';
					die (json_encode($result));
				}
			}else{
				$result ['tips'] .= '|无此用户|'.$strs[2];
				$result ['result'] = false;
				$result ['msg'] = '本条失败';
				die (json_encode($result));
			}
		}
		$insert = array();
		$insert['weid'] = $weid; 
		$insert['schoolid'] = $schoolid;
		$insert['idcard'] = trim($strs[0]);
		$insert['sid'] = empty($sid) ? 0 : intval($sid['id']);
		$insert['tid'] = empty($tid) ? 0 : intval($tid['id']);
		$insert['bj_id'] = empty($banji) ? 0 : intval($banji['sid']);
		$insert['createtime'] = empty($strs[6]) ? 0 : time();
		$insert['severend'] = strtotime(trim($strs[4]));
		$insert['spic'] = $sid['icon'];
		$insert['tpic'] = $tid['thumb'];
		$insert['is_on'] = empty($strs[6]) ? 0 : 1;
		$insert['usertype'] = empty($strs[2]) ? 0 : 1;
		$insert['pard'] = empty($strs[5]) ? 0 : intval($strs[5]);
		$insert['pname'] = $tid['tname'];
		pdo_insert($this->table_idcard, $insert);
		$result ['strs'] = rtrim($strss,',');
		$result ['result'] = true;
		die (json_encode($result));
			
	}
	
	if($operation == 'input_buzhu'){ 
		$weid = $_W['uniacid'];
		$strs = json_decode(str_replace('&quot;','"',$_GPC['execl']),true);
		$line = "";
		$schoolid = $_GPC['schoolid'];
		$result ['line'] = $_GPC['line'];
		$result ['tips'] = '';
		$strss = '';
		foreach($strs as $row){
			$strss .= $row.',';
		}

			
		$bj_id = pdo_fetch("SELECT sid,parentid FROM " . tablename($this->table_classify) . " WHERE sname=:sname AND weid=:weid And schoolid=:schoolid  ", array(':sname' => trim($strs[1]), ':weid' => $weid, ':schoolid'=> $schoolid));
		$sid = pdo_fetch("SELECT id FROM " . tablename($this->table_students) . " WHERE s_name=:s_name AND weid=:weid And schoolid=:schoolid and bj_id = :bj_id  ", array(':s_name' => trim($strs[0]), ':weid' => $weid, ':schoolid'=> $schoolid,'bj_id'=>$bj_id['sid']));
		if(empty($sid)){
			$result ['tips'] .= '|无此学生|';
			$result ['result'] = false;
			$result ['msg'] = '本条失败';
			die (json_encode($result));
		}else{
			$insert['sid'] = empty($sid) ? 0 : intval($sid['id']);
			$insert['start_yue'] = trim($strs[2]);
			$insert['now_yue'] = trim($strs[2]);
			$this_starttime = strtotime($strs[3]);
			$this_endtime = strtotime($strs[4]) + 86399;
			$insert['starttime'] = $this_starttime;
			$insert['endtime'] = $this_endtime;
			$insert['schoolid'] = $schoolid;
			$insert['weid'] = $_W['uniacid'];
			$insert['createtime'] = time();
			$check = pdo_fetch("SELECT id FROM " . tablename($this->table_buzhulog) . " WHERE sid='{$sid['id']}' AND weid='{$_W['uniacid']}' And schoolid='{$schoolid}'  and ((starttime <='{$this_starttime}' and endtime >='{$this_starttime}') or (starttime <='{$this_endtime}' and endtime >='{$this_endtime}')  or (starttime >='{$this_starttime}' and endtime <='{$this_endtime}') )");
			if(!empty($check)){
				$result ['tips'] .= '|重复内容|';
				$result ['result'] = false;
				$result ['msg'] = '本条失败';
				die (json_encode($result));
			}else{
				pdo_insert($this->table_buzhulog, $insert);
				$yuelog = array(
					'schoolid' 		=> $schoolid,
					'weid'	   		=> $_W['uniacid'],
					'sid'	   		=> $sid['id'],
					'yue_type' 		=> 1,
					'cost' 	   		=> trim($strs[2]),
					'costtime' 		=> time(),
					'cost_type'		=> 1,
					'on_offline' 	=> 2,
					'createtime' => time()
				); 
				pdo_insert($this->table_yuecostlog,$yuelog);
			}
			$result ['strs'] = rtrim($strss,',');
			$result ['result'] = true;
			die (json_encode($result));
		}	
	}

if($operation == 'input_bjpf'){ //班级评分
    $strs = json_decode(str_replace('&quot;','"',$_GPC['execl']),true);
    $line = "";
    $schoolid = $_GPC['schoolid'];
    $result ['line'] = $_GPC['line'];

    $result ['tips'] = '';
    //班级处理
    $banji = pdo_fetch("SELECT sid,parentid FROM " . tablename($this->table_classify) . " WHERE sname=:sname And schoolid=:schoolid And type=:type", array(':sname' => trim($strs[0]), ':schoolid'=> $schoolid, ':type'=> 'theclass'));
    $strss = '';
    foreach($strs as $row){
        $strss .= $row.',';
    }
    if(empty($banji)){
        $result ['tips'] .= '|无此班级-'.trim($strs[1]).'|';
        $result ['result'] = false;
        $result ['msg'] = '本条失败';
        die (json_encode($result));
    }else{
        $insert = array();
        $insert['score'] = trim($strs[1]);
        $insert['bj_id'] = $banji['sid'];
        $insert['nj_id'] = $banji['parentid'];

        $insert['scoretime'] = strtotime($strs[2]);
        $insert['schoolid'] = $schoolid;
        $insert['weid'] = $_W['uniacid'];
        $insert['type'] = 2;
        $insert['createtime'] = time();
        $check = pdo_fetch("SELECT id FROM " . tablename($this->table_teascore) . " WHERE bj_id='{$banji['sid']}' And schoolid='{$schoolid}'  and scoretime = '{$insert['scoretime']}' and type = 2 ");
        if(!empty($check)){
            pdo_update($this->table_teascore, $insert,array('id'=>$check['id']));
        }else{
            pdo_insert($this->table_teascore, $insert);
        }
        $result ['strs'] = rtrim($strss,',');
        $result ['result'] = true;
        die (json_encode($result));
    }
}
	
	
?>
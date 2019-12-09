<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
   global $_W, $_GPC;
   $operation = in_array ( $_GPC ['op'], array ('default', 'CheckNewMsg', 'binding_for_students', 'binding_for_teachers', 'make_code', 'AddGather', 'GetKmList', 'GetAllKm', 'GetKmInfo', 'GetAllData','gasignup','horder','createtodo','DealWithTodo','TodoDeliver','addsk','yy_order','cyy_t_beizhu','reset_stuinfo','get_user_qr','huifu_mail','get_stu_score','stu_infocard','addvisitors','delvisitors','editvisitors','findvisitors','quxiaovisitors','fwfuzhi','search_tname') ) ? $_GPC ['op'] : 'default';

    if ($operation == 'default') {
	   die ( json_encode ( array (
			 'result' => false,
			 'msg' => '参数错误'
			) ) );		
    }
    if ($operation == 'get_user_qr')  {
		if (! $_GPC ['id']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	    }else{
			load()->func('tpl');
			load()->func('file');
			$student = pdo_fetch("select icon,qrcode_id from " . tablename($this->table_students) . " where id = :id ", array(':id' => $_GPC ['id']));
			if(empty($student['icon'])){
				$spic  = pdo_fetch("SELECT spic FROM " . tablename($this->table_index) . " WHERE id = '{$_GPC ['schoolid']}'");
				if(empty($spic['spic'])){
					$datass ['result'] = false;
					$datass ['msg'] = '获取失败,请上传学生头像';
					 die ( json_encode ( $datass ) );
				}
			}
			$barcode = array(
				'expire_seconds' =>2592000 ,
				'action_name' => '',
				'action_info' => array(
					'scene' => array(
							'scene_id' => $_GPC ['id']
					),
				),
			);
			$uniacccount = WeAccount::create($weid);
			$barcode['action_name'] = 'QR_SCENE';
			$result = $uniacccount->barCodeCreateDisposable($barcode);
			if (is_error($result)) {
				message($result['message'], referer(), 'fail');
			}
			if (!is_error($result)) {
				$showurl = $this->createImageUrlCenterForUser("https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=" . $result['ticket'], $_GPC ['id'], 0, $_GPC ['schoolid']);
				$urlarr = explode('/',$showurl);
				$qrurls = "images/fm_jiaoyu/".$urlarr['4'];	
				$insert = array(
					'weid' => $_W['uniacid'],
					'schoolid' => $_GPC['schoolid'],
					'qrcid' => $_GPC ['id'], 
					'name' => '用户绑定临时二维码', 
					'model' => 1,
					'ticket' => $result['ticket'], 
					'show_url' => $qrurls,
					'qr_url' => ltrim($result['url'],"http://weixin.qq.com/q/"),
					'expire' => $result['expire_seconds'] + time(), 
					'createtime' => time(),
					'status' => '1',
					'type' => '3'
				);
				if(empty($student['qrcode_id'])){
					pdo_insert($this->table_qrinfo, $insert);
					$qrid = pdo_insertid();
				}else{
					$qrid = $student['qrcode_id'];
					pdo_update($this->table_qrinfo, $insert, array('id' =>$qrid));	
				}
				$qrurl = pdo_fetch("SELECT show_url FROM " . tablename($this->table_qrinfo) . " WHERE id = '{$qrid}'");
				$arr = explode('/',$qrurl['show_url']);
				$pathname = "images/fm_jiaoyu/".$arr['2'];
				if (!empty($_W['setting']['remote']['type'])) {
					$remotestatus = file_remote_upload($pathname);
						if (is_error($remotestatus)) {
							message('远程附件上传失败，'.$pathname.'请检查配置并重新上传');
						}
				}
				if(empty($student['qrcode_id'])){
					$temp1['qrcode_id'] = $qrid;
					pdo_update($this->table_students, $temp1, array('id' =>$_GPC ['id']));
					pdo_update($this->table_students, $temp1, array('keyid' =>$_GPC ['id']));
				}
				$datass ['qrimg'] = tomedia($qrurls);
				$datass ['result'] = true;
				$datass ['msg'] = '创建成功';				
			}else{
	   			$datass ['result'] = false;
				$datass ['msg'] = '创建二维码失败';				
			}
            die ( json_encode ( $datass ) );
		}
    }	
	if ($operation == 'CheckNewMsg') {
		$weid = $_GPC['weid'];
		$myid = $_GPC['myid'];
		$levelid = $_GPC['levelid'];
		$lastid = $_GPC['lastid'];
		$lasttime = $_GPC['lasttime'];
		$lastmsg = pdo_fetch("SELECT * FROM " . tablename($this->table_leave) . " where weid = '{$weid}' And leaveid = '{$levelid}' And (userid = '{$myid}' Or touserid = '{$myid}') And createtime >= '{$lasttime}' And id > '{$lastid}'");
		if(!empty($lastmsg)){
			if(!empty($lastmsg['audio'])){
				$audios = iunserializer($lastmsg['audio']);
				$urls = $_W['attachurl']; 
				$datas ['content'] = $urls.$audios['audio'][0];
				$datas ['mediaTime'] = $audios['audioTime'][0];
				$datas ['lastid'] = $lastmsg['id'];
				$datas ['touserid'] = $lastmsg['touserid'];
				$datas ['type'] = 1;
			}if(!empty($lastmsg['picurl'])){
				$picurl = $lastmsg['picurl'];
				$urls = $_W['attachurl']; 
				$datas ['content'] =tomedia($picurl);
				$datas ['mediaTime'] = $audios['audioTime'][0];
				$datas ['lastid'] = $lastmsg['id'];
				$datas ['touserid'] = $lastmsg['touserid'];
				$datas ['type'] = 3;
			}elseif(!empty($lastmsg['conet'])){
				$datas ['touserid'] = $lastmsg['touserid'];
				$datas ['lastid'] = $lastmsg['id'];
				$datas ['content'] = $lastmsg['conet'];
				$datas ['mediaTime'] = 1;
				$datas['type'] = 2;
			}
			$datas['lasttime'] = $lastmsg['createtime'];
			$datas['result'] = true;
		}else{
			$datas['result'] = false;
		}
		die ( json_encode ( $datas ) );
	}		
	if ($operation == 'GetKmList') {
		$qh_id = $_GPC['qh_id'];
		$schoolid = $_GPC['schoolid'];
		$urls = $_W['attachurl']; 
		$lists = pdo_fetchall("SELECT distinct km_id FROM " . tablename($this->table_score) . " where schoolid = :schoolid And qh_id = :qh_id ORDER BY id ASC", array(
			':schoolid' => $schoolid,
			':qh_id'=>$qh_id
		));
		$kmlists = array();
		$kmlists[0]['km_sid'] = 'all_score';
		$kmlists[0]['km_name'] = '总揽';		
		$i = 1;
		foreach($lists as $k =>$r){
			$kminfo = pdo_fetch("SELECT sid,sname,icon FROM " . tablename($this->table_classify) . " where sid = :sid ", array(':sid' => $r['km_id']));
			if(!empty($kminfo)){
				
				$kmlists[$i]['km_sid'] = $kminfo['sid'];
				$kmlists[$i]['km_name'] = $kminfo['sname'];
				if(!empty($kminfo['icon'])){
					$kmlists[$i]['km_icon'] = $urls.$kminfo['icon'];
				}else{
					$school = pdo_fetch("SELECT logo FROM " . tablename($this->table_index) . " where id = :id ", array(':id' => $schoolid));
					$kmlists[$i]['km_icon'] = $urls.$school['logo'];
				}
				$i ++ ;
			}
		}		
		die ( json_encode ( $kmlists ) );
	}
	if ($operation == 'GetAllKm') {
		$sid = $_GPC['sid'];
		$qh_id = $_GPC['qh_id'];
		$schoolid = $_GPC['schoolid'];
		$lists = pdo_fetchall("SELECT distinct km_id FROM " . tablename($this->table_score) . " where schoolid = :schoolid And qh_id = :qh_id ORDER BY id ASC", array(
			':schoolid' => $schoolid,
			':qh_id'=>$qh_id
		));	
		$allkm = array();
		$qhname = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " where sid = :sid ", array(':sid' => $qh_id));
		$kmlists['question_data'] = array();
		$kmlists['question_data']['name'] = $qhname['sname'];
		$kmlists['question_data']['data'] = array();
		$i = 0;
		foreach($lists as $k =>$r){
			$kminfo = pdo_fetch("SELECT sid,sname FROM " . tablename($this->table_classify) . " where sid = :sid ", array(':sid' => $r['km_id']));
			if(!empty($kminfo)){
				$fenshu = pdo_fetch("SELECT my_score FROM " . tablename($this->table_score) . " where sid = '{$sid}' And qh_id = '{$qh_id}' And km_id = '{$r['km_id']}' ");
				//var_dump($fenshu);
				$kmlists['question_data']['data'][$i] = floatval($fenshu['my_score']);
				$allkm[$i] = $kminfo['sname'];
				$i ++;
			}
		}
		
		$this_info = pdo_fetch("select sid, SUM(my_score) as t_score FROM " . tablename($this->table_score) . " where  schoolid = '{$schoolid}' And   bj_id = '{$_GPC['bj_id']}' and qh_id = '{$qh_id}' and sid = '{$sid}'  " ); 
			
		$count_before = pdo_fetchall(" select SUM(my_score)  FROM " . tablename($this->table_score) . "  where  bj_id = '{$_GPC['bj_id']}' and qh_id = '{$qh_id}'   AND schoolid = '{$schoolid}'  group by sid   HAVING SUM(my_score)>'{$this_info['t_score']}'   " );  
			
		$bj_rank = count($count_before)+1;
			 
		$count_before_nj = pdo_fetchall(" select SUM(my_score)  FROM " . tablename($this->table_score) . "  where  xq_id = '{$_GPC['nj_id']}' and qh_id = '{$qh_id}'   AND schoolid = '{$schoolid}'  group by sid  HAVING SUM(my_score)>'{$this_info['t_score']}'  " );  
			
		$nj_rank = count($count_before_nj)+1;
		$kmlists['titles'] = $qhname['sname'];
		$kmlists['subtitles'] = "本期总成绩 班级排名：第{$bj_rank}名 年级排名：第{$nj_rank}名 ";		
		$kmlists['all_km_name'] = $allkm;
		die ( json_encode ( $kmlists ) );
	}
	if ($operation == 'GetKmInfo') {
		$sid = $_GPC['sid'];
		$km_id = $_GPC['km_id'];
		$qh_id = $_GPC['qh_id'];
		$schoolid = $_GPC['schoolid'];
		if($km_id == 'all_score'){
			
			//$this_info = pdo_fetchall("SELECT SUM(my_score) FROM " . tablename($this->table_score) . " where  schoolid = '{$schoolid}'  group by sid  ");
			
			//$this_info = pdo_fetch("SELECT SUM(my_score) as  FROM " . tablename($this->table_classify) . " where  schoolid = '{$schoolid}' And   bj_id = '{$_GPC['bj_id']}' and qh_id = '{$qh_id}' and sid = '{$sid}' ");
			
			
			$this_info = pdo_fetch("select sid, SUM(my_score) as t_score FROM " . tablename($this->table_score) . " where  schoolid = '{$schoolid}' And   bj_id = '{$_GPC['bj_id']}' and qh_id = '{$qh_id}' and sid = '{$sid}'  " ); 
			
			//HAVING SUM(my_score)>'{$this_info['t_score']}'
			$count_before = pdo_fetchall(" select SUM(my_score)  FROM " . tablename($this->table_score) . "  where  bj_id = '{$_GPC['bj_id']}' and qh_id = '{$qh_id}'   AND schoolid = '{$schoolid}'  group by sid   HAVING SUM(my_score)>'{$this_info['t_score']}'   " );  
			
			$bj_rank = count($count_before)+1;
			 
			$count_before_nj = pdo_fetchall(" select SUM(my_score)  FROM " . tablename($this->table_score) . "  where  xq_id = '{$_GPC['nj_id']}' and qh_id = '{$qh_id}'   AND schoolid = '{$schoolid}'  group by sid  HAVING SUM(my_score)>'{$this_info['t_score']}'  " );  
			
			$nj_rank = count($count_before_nj)+1;

			
			$lists = pdo_fetchall("SELECT distinct km_id FROM " . tablename($this->table_score) . " where schoolid = :schoolid And qh_id = :qh_id ORDER BY id ASC", array(
				':schoolid' => $schoolid,
				':qh_id'=>$qh_id
			));	
			$allkm = array();
			$qhname = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " where sid = :sid ", array(':sid' => $qh_id));
			$kmlists['question_data'] = array();
			$kmlists['question_data']['name'] = $qhname['sname'];
			$kmlists['question_data']['data'] = array();
			$i = 0;
			foreach($lists as $k =>$r){
				$kminfo = pdo_fetch("SELECT sid,sname FROM " . tablename($this->table_classify) . " where sid = :sid ", array(':sid' => $r['km_id']));
				if(!empty($kminfo)){
					$fenshu = pdo_fetch("SELECT my_score FROM " . tablename($this->table_score) . " where sid = '{$sid}' And qh_id = '{$qh_id}' And km_id = '{$r['km_id']}' ");
					//var_dump($fenshu);
					$kmlists['question_data']['data'][$i] = floatval($fenshu['my_score']);
					$allkm[$i] = $kminfo['sname'];
					$i ++;
				}
			}
			$kmlists['titles'] = $qhname['sname'];
			$kmlists['subtitles'] = "本期总成绩 班级排名：第{$bj_rank}名 年级排名：第{$nj_rank}名 ";			
			$kmlists['all_km_name'] = $allkm;
			$kmlists['test_bj_all'] = $count_before;
			$kmlists['test_nj_all'] = $this_info;
			die ( json_encode ( $kmlists ) );			
		}else{
			$kminfo = pdo_fetch("SELECT sid,sname FROM " . tablename($this->table_classify) . " where sid = :sid ", array(':sid' => $km_id));
			$allkm = array(trim($kminfo['sname']));
			$lists = pdo_fetchall("SELECT qh_id,my_score,sid FROM " . tablename($this->table_score) . " where schoolid = :schoolid And km_id = :km_id And sid = :sid ORDER BY id ASC", array(':schoolid' => $schoolid,':km_id'=>$km_id,':sid'=>$sid,));
			$test_bj = pdo_fetch("select t.sid, t.my_score,(select count(s.my_score)+1 FROM " . tablename($this->table_score) . " as s  where s.my_score+0>t.my_score+0 And s.km_id = '{$km_id}' and s.bj_id = '{$_GPC['bj_id']}' and s.qh_id = '{$qh_id}'   AND s.schoolid = '{$schoolid}') as rank  FROM " . tablename($this->table_score) . " as t where  t.schoolid = '{$schoolid}' And t.km_id = '{$km_id}' and t.bj_id = '{$_GPC['bj_id']}' and t.qh_id = '{$qh_id}' and sid = '{$sid}'  " ); 
			
			
			$test_nj = pdo_fetch("select t.sid, t.my_score,(select count(s.my_score)+1 FROM " . tablename($this->table_score) . " as s  where s.my_score+0>t.my_score+0 And s.km_id = '{$km_id}' and s.xq_id = '{$_GPC['nj_id']}' and s.qh_id = '{$qh_id}'   AND s.schoolid = '{$schoolid}') as rank  FROM " . tablename($this->table_score) . " as t where  t.schoolid = '{$schoolid}' And t.km_id = '{$km_id}' and t.xq_id = '{$_GPC['nj_id']}' and t.qh_id = '{$qh_id}' and sid = '{$sid}'  " ); 
			$i = 0;
			$kmlist = array();
			foreach($lists as $k =>$r){
				$qhname = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " where sid = :sid ", array(':sid' => $r['qh_id']));
				if(!empty($qhname)){
					$kmlist[$i]['name'] = $qhname['sname'];
					$kmlist[$i]['data'] = array(floatval($r['my_score']));
					unset($kmlist[$i]['qh_id']);
					unset($kmlist[$i]['my_score']);
					$i ++;
				}
			}
			//unset($lists[count($lists) - 1]);
			$kmlists['titles'] = $kminfo['sname'];
			$kmlists['subtitles'] = "本科本期 班级排名：第{$test_bj['rank']}名 年级排名：第{$test_nj['rank']}名 ";			
			$kmlists['question_data'] = $kmlist;
			$kmlists['all_km_name'] = $allkm;
			
			
			die ( json_encode ( $kmlists ) );			
		}
	}	
	if ($operation == 'AddGather') {
		global $_W, $_GPC;
		$weid = $_W ['uniacid'];
		$starttime = mktime(0,0,0,date("m"),date("d"),date("Y"));
		$endtime = $starttime + 86399;
		$condition = " AND createtime > '{$starttime}' AND createtime < '{$endtime}'";		
		//$allbus = pdo_fetchall("SELECT macid FROM " . tablename($this->table_checkmac) . " where weid = '{$weid}' AND schoolid = '{$_GPC['schoolid']}' AND is_bobao = 2  ORDER by id ASC");
		$new_point = '';
		
		$allbus = json_decode(htmlspecialchars_decode($_GPC['allbus']),true);
		//print_r($allbus);
		if($allbus){
			foreach($allbus as $key =>$row){
				$gpslog = pdo_fetchall("SELECT id,lon,lat,createtime FROM " . tablename($this->table_busgps) . " where macid = '{$row['macid']}' AND schoolid = '{$_GPC['schoolid']}' And id > '{$row['lastid']}' $condition ORDER BY createtime ASC");
				if($gpslog){
					$new_point .= '['.$gpslog[0]['lon'].','.$gpslog[0]['lat'].']'.';';
					$allbus[$key]['macid'] = $row['macid'];
					$allbus[$key]['lastid'] = $gpslog[0]['id'];
					$allbus[$key]['lon'] = $gpslog[0]['lon'];
					$allbus[$key]['lat'] = $gpslog[0]['lat'];
				}else{
					$new_point .= '['.$row['lon'].','.$row['lat'].']'.';';
					$allbus[$key]['macid'] = $row['macid'];
					$allbus[$key]['lastid'] = $row['lastid'];
					$allbus[$key]['lon'] = $row['lon'];
					$allbus[$key]['lat'] = $row['lat'];
				}
			}
		}
		$temp['result'] = 0;
		$temp['allbus'] = $allbus;
		$temp['new_point'] = rtrim($new_point,';');
		die ( json_encode ( $temp ) );
	}
	if ($operation == 'make_code') {
		mload()->model('sms');
		$weid = trim($_GPC['weid']);
		$mobile = trim($_GPC['mobile']);
		$schoolid = intval($_GPC['schoolid']);
		$bdset = get_weidset($weid,'bd_set');
		$smsset = get_weidset($weid,'sms_acss');	
		$resttime = empty($bdset['code_time']) ? 1800 : intval($bdset['code_time']);
		$sql = 'DELETE FROM ' . tablename('uni_verifycode') . ' WHERE `createtime`<' . (TIMESTAMP - $resttime);
		pdo_query($sql);
		$sql = 'SELECT * FROM ' . tablename('uni_verifycode') . ' WHERE `receiver`=:receiver AND `uniacid`=:uniacid';
		$pars = array();
		$pars[':receiver'] = $mobile;
		$pars[':uniacid'] = $weid;
		$row = pdo_fetch($sql, $pars);
		$record = array();
		if(!empty($row)) {
			if($row['total'] >= 5) {
				$data ['result'] = false;
				$data ['msg'] = '发送失败,请联系管理员';
			}
			$code = $row['verifycode'];
			$record['total'] = $row['total'] + 1;
		} else {
			$code = random(6, true);
			$record['uniacid'] = $weid;
			$record['receiver'] = $mobile;
			$record['verifycode'] = $code;
			$record['total'] = 1;
			$record['createtime'] = TIMESTAMP;
		}
		if(!empty($row)) {
			pdo_update('uni_verifycode', $record, array('id' => $row['id']));
		} else {
			pdo_insert('uni_verifycode', $record);
		}
		$content = array(
			'code' => $code
		);
		$result = sms_send($mobile, $content, $bdset['sms_SignName'], $bdset['sms_Code'], 'code', $weid, $schoolid);
		//print_r($result);
		if($result['Code'] == 'OK') {
			$data ['result'] = true;
			$data ['msg'] = '验证码发送成功, 请注意查收';
		}else{
			$data ['result'] = false;
			if($smsset['show_res'] == 1){
				$data ['msg'] = "发送失败,原因".$result['Message'];	
			}else{
				$data ['msg'] = "发送失败,请联系管理员";	
			}
		}
		die ( json_encode ( $data ) );		
	}
	if ($operation == 'binding_for_students') {
		$subjectId = trim($_GPC['subjectId']);
		$weid = trim($_GPC['weid']);
		$code = trim($_GPC['code']);
		$mobilecode = trim($_GPC['mobilecode']);
		$xuehao = trim($_GPC['xuehao']);
		$s_name = trim($_GPC['s_name']);
		$uid = trim($_GPC['uid']);
		$openid = trim($_GPC['openid']);
		$mobile = trim($_GPC['mobile']);
		$bdset = get_weidset($weid,'bd_set');
		if ($bdset['bd_type'] ==1 || $bdset['bd_type'] ==3){
			$condition .= " AND code = '{$code}'";
		}
		if ($bdset['bd_type'] ==2 || $bdset['bd_type'] ==3){
			$condition .= " AND numberid = '{$xuehao}'";
		}
		if ($bdset['binding_sms'] ==1){
			$status = check_verifycode($mobile, $mobilecode, $weid);
			if(!$status) {
			     die ( json_encode ( array (
                 'result' => false,
                 'msg' => '短信验证码错误！' 
		          ) ) );
			}
		}		
		$student = pdo_fetch("SELECT id,schoolid,mom,dad,own,other FROM " . tablename($this->table_students) . " where :weid = weid And :s_name = s_name $condition", array(
		         ':weid' => $weid,
				 ':s_name'=>$s_name
				  ));
		$user = pdo_fetch("SELECT id FROM " . tablename($this->table_user) . " where weid = :weid And :schoolid = schoolid And sid = :sid And uid = :uid ", array(
		         ':weid' => $weid,
                 ':schoolid' => $student['schoolid'],				 
		         ':sid' => $student['id'],
				 ':uid' => $uid,
	           	  ));
		if(!empty($user)){
			     die ( json_encode ( array (
                 'result' => false,
                 'msg' => '您已绑定本学生,不可重复绑定！' 
		          ) ) );
		}				  
		if(empty($student)){
			     die ( json_encode ( array (
                 'result' => false,
                 'msg' => '没有找到该生信息,或信息输入有误！' 
		          ) ) );
		}
		if($subjectId == 2){	
			if (!empty($student['mom'])){
				  die ( json_encode ( array (
                 'result' => false,
                 'msg' => '绑定失败，此学生母亲已经绑定了其他微信号！' 
		          ) ) );
			}	  
        }
		if($subjectId == 3){
			if (!empty($student['dad'])){
				  die ( json_encode ( array (
                 'result' => false,
                 'msg' => '绑定失败，此学生父亲已经绑定了其他微信号！' 
		          ) ) );
			}
        }
		if($subjectId == 4){
			if (!empty($student['own'])){
				  die ( json_encode ( array (
                 'result' => false,
                 'msg' => '绑定失败，此学生本人已经绑定了其他微信号！' 
		          ) ) );
			}
        }
		if($subjectId == 5){
			if (!empty($student['other'])){
				  die ( json_encode ( array (
                 'result' => false,
                 'msg' => '绑定失败，此学生家长已经绑定了其他微信号！' 
		          ) ) );
			}
        }		
		if (empty($openid)) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
		}else{
			$main = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where :schoolid = schoolid And weid = :weid AND id=:id ", array(
		         ':weid' => $weid,
	             ':schoolid' => $student['schoolid'],	
		         ':id' => $student['id']
           	  	));
			if($main['keyid'] != 0 )
           	{
				$allstu = pdo_fetchall("SELECT * FROM " . tablename($this->table_students) . " where :schoolid = schoolid And weid = :weid AND keyid=:keyid ", array(
	         	':weid' => $weid,
	     		':schoolid' => $student ['schoolid'],				 
	         	':keyid' => $main['keyid']
	       	  	));
	           	foreach( $allstu as $key => $value )
	           	{
	           		$userdata = array(
						'sid' => $value['id'],
						'weid' =>  $weid,
						'schoolid' => $student['schoolid'],
						'openid' => $openid,
						'pard' => $subjectId,
						'uid' => $uid		
					);
			if(!empty($_GPC['mobile'])){
				$userinfo = array(
					'name' => $s_name.get_guanxi($subjectId),
					'mobile' => $_GPC['mobile']
				);
				$userdata['userinfo'] = iserializer($userinfo);
			}			
			pdo_insert($this->table_user, $userdata);			
			$userid = pdo_insertid();
			if($subjectId == 2){
				$temp = array( 
				    'mom' => $openid,
					'muserid' => $userid,
					'muid'=> $uid
				    );
			}
			if($subjectId == 3){
				$temp = array(
				    'dad' => $openid,
					'duserid' => $userid,
					'duid'=> $uid
				    );
			}
			if($subjectId == 4){
				$temp = array(
				    'own' => $openid,
					'ouserid' => $userid,
					'ouid'=> $uid
				    );
			}
			if($subjectId == 5){
				$temp = array(
				    'other' => $openid,
					'otheruserid' => $userid,
					'otheruid'=> $uid
				    );
			}
            pdo_update($this->table_students, $temp, array('id' => $value['id'])); 
	           	}
	           	
           	}else{
				$userdata = array(
					'sid' => $student['id'],
					'weid' =>  $weid,
					'schoolid' => $student['schoolid'],
					'openid' => $openid,
					'pard' => $subjectId,
					'uid' => $uid		
					);
				if(!empty($_GPC['mobile'])){
					$userinfo = array(
						'name' => $s_name.get_guanxi($subjectId),
						'mobile' => $_GPC['mobile']
					);
					$userdata['userinfo'] = iserializer($userinfo);
				}			
				pdo_insert($this->table_user, $userdata);			
				$userid = pdo_insertid();
				if($subjectId == 2){
					$temp = array( 
					    'mom' => $openid,
						'muserid' => $userid,
						'muid'=> $uid
					    );
				}
				if($subjectId == 3){
					$temp = array(
					    'dad' => $openid,
						'duserid' => $userid,
						'duid'=> $uid
					    );
				}
				if($subjectId == 4){
					$temp = array(
					    'own' => $openid,
						'ouserid' => $userid,
						'ouid'=> $uid
					    );
				}
				if($subjectId == 5){
					$temp = array(
					    'other' => $openid,
						'otheruserid' => $userid,
						'otheruid'=> $uid
					    );
				}
            	pdo_update($this->table_students, $temp, array('id' => $student['id'])); 	
           	}  	
					   			
			$data ['result'] = true;
			$data ['schoolid'] = $student['schoolid'];
			$data ['msg'] = '绑定成功！';
		}
		die ( json_encode ( $data ) );
    }
	
	if ($operation == 'binding_for_teachers') {
		$weid = trim($_GPC['weid']);
		$code = trim($_GPC['code']);
		$tname = trim($_GPC['tname']);
		$uid = trim($_GPC['uid']);
		$openid = trim($_GPC['openid']);
		$mobile = trim($_GPC['mobile']);
		$mobilecode = trim($_GPC['mobilecode']);
		$bdset = get_weidset($weid,'bd_set');
		if ($bdset['binding_sms'] == 1){
			$status = check_verifycode($mobile, $mobilecode, $weid);
			if(!$status) {
			     die ( json_encode ( array (
                 'result' => false,
                 'msg' => '无效验证码或已过期' 
		          ) ) );
			}
		}
		$teacher = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where :weid = weid And :tname = tname And :code = code ", array(
		         ':weid' => $weid,
				 ':tname'=>$tname,
				 ':code'=>$code
				  ));
		$user = pdo_fetch("SELECT id FROM " . tablename($this->table_teachers) . " where :schoolid = schoolid And :weid = weid And :openid = openid", array(
		         ':weid' => $weid,
				 ':schoolid' => $teacher['schoolid'],
				 ':openid'=>$openid
				  ));

		if ($user['id']) {
			  die ( json_encode ( array (
				'result' => false,
				'msg' => '抱歉,你已经绑定了本校其他教师！' 
				   ) ) );
		}				  
				  
		if (empty($openid)) {
			  die ( json_encode ( array (
				'result' => false,
				'msg' => '非法请求！' 
				   ) ) );
		}
		
		if(empty($teacher)){
			 die ( json_encode ( array (
			 'result' => false,
			 'msg' => '姓名,绑定码或手机号有误' 
			  ) ) );
		}
		if(!empty($teacher['openid'])){
			  die ( json_encode ( array (
			 'result' => false,
			 'msg' => '绑定失败，此教师已经绑定了其他微信号！' 
			  ) ) );
        }else{	   
		    pdo_insert($this->table_user, array (
					'tid' => trim($teacher['id']),
					'weid' =>  $weid,
					'schoolid' => $teacher['schoolid'],
					'openid' => $openid,
					'uid' => $uid
			));
			$userid = pdo_insertid();
			$temp = array('openid' => $openid, 'uid' => $uid, 'userid' => $userid);
			if(!empty($mobile)){
				$temp['mobile']= $mobile;
			}			
		    pdo_update($this->table_teachers, $temp, array('id' => $teacher['id']));
			$data ['result'] = true;
			$data ['schoolid'] = $teacher['schoolid'];
			$data ['msg'] = '绑定成功！';
		 die ( json_encode ( $data ) );
		}
    }
    if ($operation == 'GetAllData') {
		$sid = $_GPC['sid'];
		$bj_id = $_GPC['bj_id'];
		$schoolid = $_GPC['schoolid'];
		$lists = get_myqh($bj_id,$schoolid);
		$allkm = array("总分");
		$i = 0;
		foreach($lists as $k =>$r){
			$lists[$i]['name'] = $r['sname'];
			$lists[$i]['data'] = array(get_my_score($sid,$r['sid'],$schoolid));
			unset($lists[$i]['sid']);
			unset($lists[$i]['sname']);				
			$i ++;	
		}
		$kmlists['titles'] = "成绩总揽";
		$kmlists['subtitles'] = "我的成绩总揽";
		$kmlists['question_data'] = $lists;
		$kmlists['all_km_name'] = $allkm;
		die ( json_encode ( $kmlists ) );
	}
	 if ($operation == 'gasignup') {
	  $gaid = $_GPC['gaid'];
	  $userid = $_GPC['userid'];
	  $schoolid = $_GPC['schoolid'];
	  $weid = $_GPC['weid'];
	  $gainfo = pdo_fetch("SELECT id,bjarray FROM " . tablename($this->table_groupactivity) . " where :schoolid = schoolid And  :gaid = id", array(
			':schoolid' => $schoolid,
			':gaid'=>$gaid
		));
		$data ['schoolid'] = $schoolid;
		if(empty($gainfo)){
				$data ['result'] = false;
				$data ['schoolid'] = $schoolid;
				$data ['msg'] = '报名失败，该集体活动不存在或已删除！';
		 die ( json_encode ( $data ) );
		}
		 $userinfo = pdo_fetch("SELECT sid FROM " . tablename($this->table_user) . " where :schoolid = schoolid And  :id = id And tid = :tid ", array(
			':schoolid' => $schoolid,
			':id'=>$userid,
			':tid' => 0
		));
		if(empty($userinfo)){
				$data ['result'] = false;
				$data ['schoolid'] = $schoolid;
				$data ['msg'] = '报名失败，用户不存在！';
		 die ( json_encode ( $data ) );
		}
		$sid = $userinfo['sid'];
		$students =  pdo_fetch("SELECT bj_id FROM " . tablename($this->table_students) . " where :schoolid = schoolid And  :id = id  ", array(
			':schoolid' => $schoolid,
			':id'=>$sid,
		));
		$bjarray =  explode(',', $gainfo['bjarray']);
		if(in_array($students['bj_id'],$bjarray)){
			$inarray = 1;
			 
		}
		if($inarray != 1){
			$data ['result'] = false;
			$data ['schoolid'] = $schoolid;
			$data ['msg'] = '报名失败，该学生所属班级不可报名该活动！';
			die ( json_encode ( $data ) );
		}
		$checksign =  pdo_fetch("SELECT id FROM " . tablename($this->table_groupsign) . " where :schoolid = schoolid And  :sid = sid  And  :gaid = gaid  ", array(
			':schoolid' => $schoolid,
			':sid'=>$sid,
			':gaid' => $gaid
		));
		if(!empty($checksign)){
			$data ['result'] = false;
			$data ['schoolid'] = $schoolid;
			$data ['msg'] = '该学生已经报名，请勿重复报名！';
		 	die ( json_encode ( $data ) );
		}
		$temp = array(
			'schoolid'   =>$schoolid,
			'weid'       =>$weid,
			'gaid'       =>$gaid,
			'userid'	 =>$userid,
			'sid'        =>$sid,
			'createtime' => TIMESTAMP,
			'type'       => 1 

		);
		pdo_insert($this->table_groupsign, $temp);
	  	$data ['result'] = true;
		$data ['schoolid'] = $schoolid;
		$data ['gaid'] = $gaid;
		$data ['userid'] = $userid;
		$data ['msg'] = '报名成功，请勿重复报名！';
		 die ( json_encode ( $data ) );	
    }

    if ($operation == 'horder') {
	  $gaid = $_GPC['gaid'];
	  $userid = $_GPC['userid'];
	  $schoolid = $_GPC['schoolid'];
	  $weid = $_GPC['weid'];
	  $gainfo = pdo_fetch("SELECT id,type,starttime,endtime FROM " . tablename($this->table_groupactivity) . " where :schoolid = schoolid And  :gaid = id", array(
			':schoolid' => $schoolid,
			':gaid'=>$gaid
		));
		$data ['schoolid'] = $schoolid;
		if(empty($gainfo)){
				$data ['result'] = false;
				$data ['schoolid'] = $schoolid;
				$data ['msg'] = '预约失败，该服务不存在或已删除！';
		 die ( json_encode ( $data ) );
		}
		if($gainfo['starttime'] > time() || $gainfo['endtime'] < time()){
			$data ['result'] = false;
			$data ['schoolid'] = $schoolid;
			$data ['msg'] = '预约失败，该服务尚未开始或已经结束！';
			 die ( json_encode ( $data ) );
		}
		$ordertime = strtotime($_GPC['ordertime']);
		if($gainfo['starttime'] > $ordertime || $gainfo['endtime'] < $ordertime){
			$data ['result'] = false;
			$data ['schoolid'] = $schoolid;
			$data ['msg'] = '预约失败，预约时间不在服务起止时间内！';
			 die ( json_encode ( $data ) );
		}
		 $userinfo = pdo_fetch("SELECT sid FROM " . tablename($this->table_user) . " where :schoolid = schoolid And  :id = id And tid = :tid ", array(
			':schoolid' => $schoolid,
			':id'=>$userid,
			':tid' => 0
		));
		if(empty($userinfo)){
				$data ['result'] = false;
				$data ['schoolid'] = $schoolid;
				$data ['msg'] = '预约失败，用户不存在！';
		 die ( json_encode ( $data ) );
		}
		$sid = $userinfo['sid'];
		$students =  pdo_fetch("SELECT bj_id FROM " . tablename($this->table_students) . " where :schoolid = schoolid And  :id = id  ", array(
			':schoolid' => $schoolid,
			':id'=>$sid,
		));

		$checksign =  pdo_fetch("SELECT id FROM " . tablename($this->table_groupsign) . " where :schoolid = schoolid And  :sid = sid  And  :gaid = gaid  ", array(
			':schoolid' => $schoolid,
			':sid'=>$sid,
			':gaid' => $gaid
		));
		if(!empty($checksign)){
			$data ['result'] = false;
			$data ['schoolid'] = $schoolid;
			$data ['msg'] = '该学生已经预约，请勿重复预约！';
		 	die ( json_encode ( $data ) );
		}
		
		$temp = array(
			'schoolid'   =>$schoolid,
			'weid'       =>$weid,
			'gaid'       =>$gaid,
			'userid'	 =>$userid,
			'sid'        =>$sid,
			'createtime' => TIMESTAMP,
			'type'       => $gainfo['type'],
			'servetime'  => strtotime($_GPC['ordertime']),

		);
		pdo_insert($this->table_groupsign, $temp);
	  	$data ['result'] = true;
		$data ['schoolid'] = $schoolid;
		$data ['gaid'] = $gaid;
		$data ['userid'] = $userid;
		$data ['msg'] = '预约成功，请勿重复预约！';
		 die ( json_encode ( $data ) );	
    }

   	if ($operation == 'createtodo') {
		 load()->func('communication');
		 load()->func('file');
		 $data = explode ( '|', $_GPC ['json'] );
		
		if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' ,
					'status' => 2,
                    'info' => '非法请求！' 
		               ) ) );
	    }else{
			
			if (empty($_GPC['openid'])) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求,请刷新页面！' ,
					'status' => 1,
                    'info' => '非法请求！' 
		               ) ) );	   
		    }else{
				$schoolid   = trim($_GPC['schoolid']);
				$weid       = trim($_GPC['weid']);
				$content    = trim($_GPC['content']);
				$uid        = trim($_GPC['uid']);
				$userid     = trim($_GPC['userid']);
				$openid     = trim($_GPC['openid']);
				$audios     = $_GPC ['audioServerid'];
				$audio      = $audios[0];
				$audiotimes = $_GPC['audioTime'];
				$audiotime  = $audiotimes[0];
				$tid        = $_GPC['tid'];
				$todoname   = trim($_GPC['todoname']);
				$jsid       = $_GPC['jsid'];
				$photoUrls  = $_GPC['photoUrls'];
				$photo_temp = serialize($photoUrls);
				if(!empty($audio)){
					$msgtype = 2;//录音
				}
			
				$video = '';
				$school = pdo_fetch("SELECT isopen,txid,txms,savevideoto FROM " . tablename($this->table_index) . " WHERE :weid = weid And :id = id", array(':weid' => $_GPC['weid'], ':id' => $_GPC['schoolid']));
				if(!empty($_GPC['videoMediaId'])){
					$msgtype = 3;//视频
                    mload()->model('ali');
                    $aliyun = GetAliApp($_W['uniacid'],$_GPC['schoolid']);
                    if($aliyun['result']){
                        $appid = $aliyun['alivodappid'];
                        $key = $aliyun['alivodkey'];
                        do {
                            $GetAliVideoUrl = GetAliVideoUrl($appid,$key,trim($_GPC['videoMediaId']));
                        } while (empty($GetAliVideoUrl['PlayURL']));
                        do {
                            $GetAliVideoCover = GetAliVideoCover($appid,$key,trim($_GPC['videoMediaId']));
                        } while (empty($GetAliVideoCover['CoverURL']));
                        $video = $GetAliVideoUrl['PlayURL'];
                        $videoimg = $GetAliVideoCover['CoverURL'];
                    }
				}

				if(!empty($_GPC['linkAddress'])){
					$msgtype = 4;//外链
				}				
				if($audio){
					$versionfile = IA_ROOT . '/addons/fm_jiaoyu/inc/func/auth2.php';
					require $versionfile;
					$mp3name = str_replace('images/bjq/vioce/','',$audio);
					$mp3 = str_replace('.mp3','',$mp3name);
					delvioce($mp3,FM_JIAOYU_HOST);
				}
				$temp = array(
					'weid'       => $weid,
					'schoolid'   => $schoolid,
					'fsid'       => $tid,
					'jsid'       => $jsid,
					'content'    => $content,
					'todoname'   => $todoname,
					'createtime' => time(),
					'acttime'    => time(),
					'status'     => 0,
					'audio' => $audio,
					'audiotime' => $audiotime,
					'videoimg' => $videoimg,
					'video' => $video,
				'ali_vod_id' => trim($_GPC['videoMediaId']),
					'picurls' => $photo_temp
				);
					
	 		 	pdo_insert($this->table_todo, $temp);

		      	$todoid = pdo_insertid();
			   	if (empty($todoid)) {
		                  die ( json_encode ( array (
		                    'result' => false,
		                    'msg' => '创建任务失败，请重试！' 
				               ) ) );
				}else{
					$type = "create";
					$this->sendMobileRwtz($todoid, $schoolid, $weid, $tid,$jsid,$type);
					die ( json_encode ( array (
					 'result' => true,
					 'status' => 1,
					 'msg' => '发送任务成功，请勿重复发送!'
					) ) );
				}
			}
		}
    }

    if ($operation == 'DealWithTodo') {
	    if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	    };
	   	
	   	$todoid    = $_GPC['id'];
	   	$status    = $_GPC['status'];
	   	$agreetype = $_GPC['agreetype'];
	   	$tid       = $_GPC['tid'];
	   	$schoolid  = $_GPC['schoolid'];
	   	$weid      = $_GPC['weid'];

		$datatemp = array(
			'status'  => $status,
			'acttime' => time()
		);
		if($status ==1){
			$datatemp['jjbeizhu1'] = $_GPC['beizhu_jujue'];
		}elseif($status == 5){
			$datatemp['jjbeizhu2'] = $_GPC['beizhu_jujue'];
		}
		if(pdo_update($this->table_todo, $datatemp, array('id' => $todoid))){
			if($agreetype == "first_finish" || $agreetype == "second_finish" || $agreetype == "second_refuse_first_finish"){
				$fsid =pdo_fetch("SELECT fsid FROM " . tablename($this->table_todo) . " where :schoolid = schoolid And :weid = weid And :id = id", array(
			        ':weid'     => $weid,
			        ':schoolid' => $schoolid,
			        ':id'       => $todoid
			  ));
				$type = "finish";
				$this->sendMobileRwtz($todoid, $schoolid, $weid, $tid,$fsid['fsid'],$type);
			}elseif($agreetype == "second_refuse"){
				$jsid =pdo_fetch("SELECT jsid FROM " . tablename($this->table_todo) . " where :schoolid = schoolid And :weid = weid And :id = id", array(
			        ':weid'     => $weid,
			        ':schoolid' => $schoolid,
			        ':id'       => $todoid
			  ));
				$type = "second_refuse";
				$this->sendMobileRwtz($todoid, $schoolid, $weid, $tid,$jsid['jsid'],$type);
			}elseif($agreetype == "first_refuse"){
				$fsid =pdo_fetch("SELECT fsid FROM " . tablename($this->table_todo) . " where :schoolid = schoolid And :weid = weid And :id = id", array(
			        ':weid'     => $weid,
			        ':schoolid' => $schoolid,
			        ':id'       => $todoid
			  ));
				$type = "first_refuse";
				$this->sendMobileRwtz($todoid, $schoolid, $weid, $tid,$fsid['fsid'],$type);
			}
			
			die ( json_encode ( array (
                    'result' => true,
                    'msg' => '操作成功，请勿重复操作！' 
		               ) ) );
		}else{
			die ( json_encode ( array (
                    'result' => false,
                    'msg' => '操作失败，请稍后重试！' 
		               ) ) );
		}

    }	

    if ($operation == 'TodoDeliver') {
	    if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	    };
	   	
	   	$todoid   = $_GPC['todoid'];
	   	$zjid     = $_GPC['zjid'];
	   	$schoolid = $_GPC['schoolid'];
	   	$weid     = $_GPC['weid'];
	   	$tid      = $_GPC['tid'];

		$datatemp = array(
			'status' => 4,
			'zjid'   => $zjid,
			'acttime' => time(),
			'zjbeizhu' => $_GPC['beizhu']
		);
		if(pdo_update($this->table_todo, $datatemp, array('id' => $todoid))){
			$type = "deliver";
			$this->sendMobileRwtz($todoid, $schoolid, $weid,$tid,$zjid,$type);
			die ( json_encode ( array (
                    'result' => true,
                    'msg' => '操作成功，请勿重复操作！' 
		               ) ) );
		}else{
			die ( json_encode ( array (
                    'result' => false,
                    'msg' => '操作失败，请稍后重试！' 
		               ) ) );
		}
    }

    if ($operation == 'addsk') { //增加已试看人
	    if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	    }
	   	$checksk = pdo_fetch("SELECT id FROM " . tablename($this->table_camerask) . " where schoolid = '{$_GPC ['schoolid']}' And carmeraid = '{$_GPC ['carmeraid']}' And userid = '{$_GPC ['userid']}'");
		if(empty($checksk)){
			$data = array(
				'weid' => $_GPC ['weid'],
				'schoolid'   => $_GPC ['schoolid'],
				'carmeraid' => $_GPC ['carmeraid'],
				'userid' => $_GPC ['userid'],
				'createtime' => time(),
			);
			pdo_insert($this->table_camerask, $data);			
		}
    }	

    if ($operation == 'yy_order') { //课程预约试听
	    if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	    }
	    $name = $_GPC['name'];
	    $tel  = $_GPC['telphone'];
	    $kcid = $_GPC['kcid'];
    	if(empty($name) ){
				die ( json_encode ( array (
                    'result' => false,
                    'msg' => '请填写您的姓名！' 
		               ) ) );
			}
			if(empty($tel) ){
					die ( json_encode ( array (
                    'result' => false,
                    'msg' => '请填写您的电话号码！' 
		               ) ) );
			} 
			$timestart = strtotime(date("Ymd",time()));
			$timeend = $timestart + 86399;
			$condition = "And createtime>{$timestart} And createtime < {$timeend}";
			if($_GPC['ordertype'] == 1 ) {
				$checkyy = pdo_fetch("SELECT id FROM " . tablename($this->table_courseorder) . " where weid = '{$_GPC ['weid']}' And schoolid = '{$_GPC ['schoolid']}' And  name ='{$name}' And tel = '{$tel}' $condition ");
				$tid_temp = pdo_fetch("SELECT comtid FROM " . tablename($this->table_index) . " where id = :id ", array(':id' => $_GPC['schoolid']));
				$tid = $tid_temp['comtid'];
			}elseif($_GPC['ordertype'] == 2 ){
				$checkyy = pdo_fetch("SELECT id FROM " . tablename($this->table_courseorder) . " where weid = '{$_GPC ['weid']}' And schoolid = '{$_GPC ['schoolid']}' And  name ='{$name}' And tel = '{$tel}' And kcid = '{$kcid}' $condition ");
				$kcinfo = pdo_fetch("SELECT yytid,tid FROM " . tablename($this->table_tcourse) . " where weid = '{$_GPC ['weid']}' And schoolid = '{$_GPC ['schoolid']}'  And id = '{$kcid}' ");
				if(!empty($kcinfo['yytid']) && $kcinfo['yytid'] != 0  ){
				   	$tid = $kcinfo['yytid'];
			   	}else{
				   $tid = $tid_temp['comtid'];
			   	}
			} 

	   	if(!empty($checkyy)){
		   	 die ( json_encode ( array (
                    'result' => false,
                    'msg' => '您今日已预约过，请勿重复预约！' 
		               ) ) );
	   	}else{
			$datatemp = array( 
				'name'       => $name,
				'tel'        => $tel,
				'beizhu'     => $_GPC['beizhu'],
				'kcid'       => $kcid,
				'weid'       => $_GPC['weid'],
				'schoolid'   => $_GPC['schoolid'],
				'tid'        => $tid,
				'createtime' => time()
			 );
			pdo_insert($this->table_courseorder, $datatemp);
			$insertid = pdo_insertid();
			$this->sendMobileYykctz($insertid,$_GPC['schoolid'],$_GPC['weid']);
			die(json_encode(array(
				'result' => true,
                'msg' => '预约成功，请勿重复预约！'
			)));		
	   	}
    }

    if ($operation == 'cyy_t_beizhu') { //课程预约——教师新增备注
	    if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	    }
	    $beizhu = trim($_GPC['beizhu']);
	    $cyyid = $_GPC['cyyid'];
    	if(empty($beizhu) ){
				die ( json_encode ( array (
                    'result' => false,
                    'msg' => '跟进情况不能为空！' 
		               ) ) );
		}
			$datatemp = array( 
				
				'beizhu'     => $_GPC['beizhu'],
				'cyyid'       => $cyyid,
				'weid'       => $_GPC['weid'],
				'schoolid'   => $_GPC['schoolid'],
				'tid'        => $_GPC['tid'],
				'createtime' => time()
			 );
			 pdo_insert($this->table_cyybeizhu_teacher, $datatemp);
			 $insertid = pdo_insertid();
			 if(!empty($insertid)){
				die(json_encode(array(
					'result' => true,
                    'msg' => '新增跟进成功！'
			 	))); 
			 }else{
				die(json_encode(array(
					'result' => false,
                    'msg' => '新增跟进失败，请稍后重试！'
			 	)));  
			 }	
    }
	if ($operation == 'reset_stuinfo') {
		if (empty($_GPC['schoolid'])) {
			die ( json_encode ( array (
				'result' => false,
				'msg' => '非法请求' 
			) ) );
		}else{
			$sid      = $_GPC['sid'];
			$sex      = $_GPC['sex'];
			$addr     = $_GPC['addr'];
			$name     = $_GPC['name'];
			$numberid = $_GPC['numberid'];
			$mobile   = $_GPC['mobile'];
			$schoolid = $_GPC['schoolid'];

			$stu = pdo_fetch("SELECT numberid FROM " . tablename($this->table_students) . " WHERE :id = id And :schoolid = schoolid ", array(':id' => $sid,':schoolid' => $schoolid));
			if(!empty($stu)){
				if($numberid != $stu['numberid']){
					$number = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " WHERE :numberid = numberid And :schoolid = schoolid ", array(':numberid' => $numberid,':schoolid' => $schoolid));
					if($number){
						$data['result'] = false;
						$data['msg'] = '抱歉,您输入的学籍号已被使用,请联系老师索取';		
						die(json_encode($data));				
					}
				}
				$temp= array(
					's_name' => $name,
					'mobile' => $mobile ,
					'sex'    => $sex,
					'area_addr' => $addr
				);
				if($numberid != $stu['numberid']){
					$temp['numberid'] = $numberid;
				}
				pdo_update($this->table_students,$temp,array('id'=> $sid));
				$data['result'] = true;
				$data['msg'] = "修改成功";			
			}else{
				$data['result'] = false;
				$data['msg'] = '抱歉修改失败';	
			}
			die(json_encode($data));
		}
	}	
	if ($operation == 'huifu_mail') { 
		$huifu = $_GPC['huifu'];
		$id = $_GPC['id'];
		$datatemp = array( 
			'huifu' =>$huifu,
		 );
		pdo_update($this->table_courseorder, $datatemp, array('id' => $id));
		$this->sendMobileYzxxhf($id, $_GPC['schoolid'], $_GPC['weid']);
		die(json_encode(array(
			'result' => true,
            'msg' => '邮件回复成功！'
		)));		
   	
    }
	
	if ($operation == 'get_stu_score') { 
		$schoolid = $_GPC['schoolid'];
		$this_xueqi = $_GPC['this_xueqi'];
		$sid = $_GPC['sid'];
		$student =  pdo_fetch("SELECT s_name FROM ".tablename($this->table_students)." WHERE schoolid = '{$schoolid}' and id = '{$sid}' ");
		if (empty($schoolid)) {
			die ( json_encode ( array (
				'result' => false,
				'msg' => '非法请求' 
			) ) );
		}else{
			$xueqi = pdo_fetch("SELECT * FROM ".tablename($this->table_classify)." WHERE schoolid = '{$schoolid}' and sid = '{$this_xueqi}' and type='xq_score' ");
			$score_list = pdo_fetchall("SELECT * FROM ".tablename($this->table_teascore)." WHERE schoolid='{$schoolid}' and sid = '{$sid}'  and scoretime >='{$xueqi['sd_start']}' and scoretime <='{$xueqi['sd_end']}'  and type = 1 order by scoretime DESC ");
			foreach($score_list as $key_s=>$value_s){
				$bj_rank = pdo_fetch("select count(score)+1 as rank FROM " . tablename($this->table_teascore) . "  where score>'{$value_s['score']}' AND schoolid = '{$schoolid}' AND scoretime ='{$value_s['scoretime']}' and bj_id = '{$value_s['bj_id']}' ");
				$nj_rank = pdo_fetch("select count(score)+1 as rank FROM " . tablename($this->table_teascore) . "  where score>'{$value_s['score']}'  AND schoolid = '{$schoolid}' AND scoretime ='{$value_s['scoretime']}' and nj_id = '{$value_s['nj_id']}' ");
				$score_list[$key_s]['bj_rank'] = $bj_rank['rank'];
				$score_list[$key_s]['nj_rank'] = $nj_rank['rank'];
				$score_list[$key_s]['time_out'] = date("Y-m",$value_s['scoretime']);
			}
			$result['data'] = $score_list ;
			$result['xueqiname'] = $xueqi['sname'];
			$result['sname'] = $student['s_name'];
			die ( json_encode ($result));
		}
    }
	
	
	if ($operation == 'stu_infocard') { 
		$schoolid = $_GPC['schoolid'];
		$sid = $_GPC['sid'];
		$this_data = array(
			's_name' => trim($_GPC['StuName_card']),
			'sex' => $_GPC['Sex_card'],
			'numberid' => $_GPC['NumberId_card'],
			'area_addr' => $_GPC['HomeAddress_card'],
			'birthdate' => strtotime($_GPC['Birthdate_card']),
			'seffectivetime' => strtotime($_GPC['Seffectivetime_card'])
		);
		$infocard = array();
		$infocard['NowAddress'] = $_GPC['NowAddress_card'];
		$infocard['IDcard'] = trim($_GPC['IDcard_card']);
		$infocard['Nation'] = trim($_GPC['Nation_card']);
		$infocard['HomeChild'] = trim($_GPC['HomeChild_card']);
		$infocard['SingleFamily'] = trim($_GPC['SingleFamily_card']);
		$infocard['IsKeep'] = trim($_GPC['IsKeep_card']);
		$infocard['DayOrWeek'] = trim($_GPC['DayOrWeek_card']);
		$infocard['Fxueli'] = trim($_GPC['Fxueli_card']);
		$infocard['Fwork'] = trim($_GPC['Fwork_card']);
		$infocard['Fhobby'] = trim($_GPC['Fhobby_card']);
		$infocard['FWorkPlace'] = trim($_GPC['FWorkPlace_card']);
		$infocard['Mxueli'] = trim($_GPC['Mxueli_card']);
		$infocard['Mwork'] = trim($_GPC['Mwork_card']);
		$infocard['Mhobby'] = trim($_GPC['Mhobby_card']);
		$infocard['MWorkPlace'] = trim($_GPC['MWorkPlace_card']);
		$infocard['GrandFxueli'] = trim($_GPC['GrandFxueli_card']);
		$infocard['GrandFwork'] = trim($_GPC['GrandFwork_card']);
		$infocard['GrandFhobby'] = trim($_GPC['GrandFhobby_card']);
		$infocard['GrandFWorkPlace'] = trim($_GPC['GrandFWorkPlace_card']);
		$infocard['GrandMxueli'] = trim($_GPC['GrandMxueli_card']);
		$infocard['GrandMwork'] = trim($_GPC['GrandMwork_card']);
		$infocard['GrandMhobby'] = trim($_GPC['GrandMhobby_card']);
		$infocard['GrandMWorkPlace'] = trim($_GPC['GrandMWorkPlace_card']);
		$infocard['WGrandFxueli'] = trim($_GPC['WGrandFxueli_card']);
		$infocard['WGrandFwork'] = trim($_GPC['WGrandFwork_card']);
		$infocard['WGrandFhobby'] = trim($_GPC['WGrandFhobby_card']);
		$infocard['WGrandFWorkPlace'] = trim($_GPC['WGrandFWorkPlace_card']);
		$infocard['WGrandMxueli'] = trim($_GPC['WGrandMxueli_card']);
		$infocard['WGrandMwork'] = trim($_GPC['WGrandMwork_card']);
		$infocard['WGrandMhobby'] = trim($_GPC['WGrandMhobby_card']);
		$infocard['WGrandMWorkPlace'] = trim($_GPC['WGrandMWorkPlace_card']);
		$infocard['Otherxueli'] = trim($_GPC['Otherxueli_card']);
		$infocard['Otherwork'] = trim($_GPC['Otherwork_card']);
		$infocard['Otherhobby'] = trim($_GPC['Otherhobby_card']);
		$infocard['OtherWorkPlace'] = trim($_GPC['OtherWorkPlace_card']);
        $MainWatcharr = array();
		if($_GPC['is_f_main'] == 1){
            $MainWatcharr[] = 1;
        }
        if($_GPC['is_m_main'] == 1){
            $MainWatcharr[] = 2;
        }
        if($_GPC['is_gf_main'] == 1){
            $MainWatcharr[] = 3;
        }
        if($_GPC['is_gm_main'] == 1){
            $MainWatcharr[] = 4;
        }
        if($_GPC['is_wgf_main'] == 1){
            $MainWatcharr[] = 5;
        }
        if($_GPC['is_wgm_main'] == 1){
            $MainWatcharr[] = 6;
        }
        if($_GPC['is_other_main'] == 1){
            $MainWatcharr[] = 7;
        }
		$infocard['MainWatcharr'] = json_encode($MainWatcharr);
		$infocard['Childhobby'] = trim($_GPC['Childhobby_card']);
		$infocard['ChildWord'] = trim($_GPC['ChildWord_card']);
		$infocard['SchoolWord'] = trim($_GPC['SchoolWord_card']);
		$this_data['infocard'] = json_encode($infocard);
		pdo_update($this->table_students,$this_data, array('id' => $sid));
		
		$result['status'] = true;
		$result['msg'] = "修改成功";
		die(json_encode($result));
    }
	#添加访问信息
	if ($operation == 'addvisitors')  {

		if (empty($_GPC ['schoolid'])) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	    }
		
		if (empty($_GPC ['openid']))  {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	    }else{
			$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_set) . " WHERE :weid = weid", array(':weid' => $_GPC['weid']));
			if(!empty($_GPC['headimg'])){
				load()->func('communication');
				load()->func('file');
		        $token2 = $this->getAccessToken2();
				$url = 'https://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$_GPC['headimg'];
				$pic_data = ihttp_request($url);
				$path = "images/fm_jiaoyu/img/";
				$picurl = $path.random(30) .".jpg";
				file_write($picurl,$pic_data['content']);
				$files = IA_ROOT . "/attachment/".$picurl;
//				cut($files);//裁剪
				if (!empty($_W['setting']['remote']['type'])) { //
					$remotestatus = file_remote_upload($picurl); //
					if (is_error($remotestatus)) {
						message('远程附件上传失败，请检查配置并重新上传');
					}
				}
			}

			#访问申请时间拼接
			$date = $_GPC['date'];
			$starttime_temp = $_GPC['starttime'];
			$endtime_temp = $_GPC['endtime'];
			$starttime_str = $date.' '.$starttime_temp;
			$endtime_str = $date.' '.$endtime_temp;
			$starttime = strtotime($starttime_str);
			$endtime = strtotime($endtime_str);
			$data = array(
				'weid' => $_GPC['weid'],
				'schoolid' => $_GPC['schoolid'],
				't_id' => $_GPC['t_id'],
				'plate_num' => $_GPC['plate_num'],
				's_name' => trim($_GPC['s_name']),
				'icon' => $picurl,
				'tel' => $_GPC['tel'],
				'idcard' => $_GPC['idcard'],
				'openid' => $_GPC['openid'],
				'status' => 1,
				'sy_id'=> $_GPC['sy_id'],
				'starttime'=> $starttime,
				'endtime'=> $endtime,
				'createtime'=> time(),
				'unit'=> trim($_GPC['unit']),
			);
			$check = pdo_fetch("SELECT * FROM " . tablename($this->table_visitors) . " WHERE :weid = weid AND :idcard = idcard AND :status = status AND ( (:starttime < starttime AND :endtime > endtime) OR ( :starttime > starttime AND :starttime < endtime) OR ( :endtime > starttime AND :endtime < endtime) ) ", array(':weid' => $_GPC['weid'] , ':status' => '1' , ':idcard' => $_GPC['idcard'] , ':starttime' => $starttime , ':endtime' => $endtime));

			if($check){
				$data ['result'] = false;
				$data ['msg'] = '您已经申请过了！';
			}else{
				pdo_insert($this->table_visitors, $data);
				$data ['result'] = true;
				$data ['msg'] = '申请成功！';
                #访问申请结果推送
                $id = pdo_insertid();
                $this->sendMobileTeaVis($id, $_GPC['schoolid'], $_GPC['weid']);
			}
            $res ['result'] = false;
            $res ['msg'] = '您已经申请过了！';
          die ( json_encode ( $res ) );

		}
    }
	#删除访问信息
	if ($operation == 'delvisitors')  {

		if (empty($_GPC ['schoolid'])) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	    }
		if(!empty($_GPC ['id'])){
			pdo_delete($this->table_visitors, array('id' => $_GPC ['id'],'schoolid'=>$_GPC['schoolid']));
		}
		$data ['result'] = true;
		$data ['msg'] = '删除成功';	
        die ( json_encode ( $data ) );
    }
	#修改访问信息
	if ($operation == 'editvisitors')  {

		if (empty($_GPC ['schoolid'])) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	    }
		if(!empty($_GPC ['id'])){
			
		#访问申请时间拼接
		$date = $_GPC['date'];
		$starttime_temp = $_GPC['starttime'];
		$endtime_temp = $_GPC['endtime'];
		$starttime_str = $date.' '.$starttime_temp;
		$endtime_str = $date.' '.$endtime_temp;
		$starttime = strtotime($starttime_str);
		$endtime = strtotime($endtime_str);
			pdo_update($this->table_visitors, array('starttime' => $starttime , 'endtime' => $endtime), array('id' => $_GPC ['id'],'schoolid' => $_GPC ['schoolid']));					
		}
		$data ['result'] = true;
		$data ['msg'] = '修改成功';		
        die ( json_encode ( $data ) );
    }
    //取消
    if ($operation == 'quxiaovisitors')  {

        if (empty($_GPC ['schoolid'])) {
            die ( json_encode ( array (
                'result' => false,
                'msg' => '非法请求！'
            ) ) );
        }
        if(!empty($_GPC ['id'])){
            $lastedittime = time();
            pdo_update($this->table_visitors, array('lastedittime' => $lastedittime , 'status' => 6), array('id' => $_GPC ['id'],'schoolid' => $_GPC ['schoolid']));
        }
        $data ['result'] = true;
        $data ['msg'] = '取消成功';
        $this->sendMobileTeaVis($_GPC ['id'], $_GPC['schoolid'], $_W['weid']);
        die ( json_encode ( $data ) );
    }
	
	#查询一条数据信息
	if ($operation == 'findvisitors')  {

		if (empty($_GPC ['schoolid'])) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	    }
		if(!empty($_GPC ['id'])){
			$data = pdo_fetch("SELECT t_id,id,starttime,endtime,s_name FROM " . tablename($this->table_visitors) . " WHERE :schoolid = schoolid And :id = id", array(
				':schoolid' => $_GPC['schoolid'],
				':id' => $_GPC['id'],
				));
			$res = pdo_fetch("SELECT tname,mobile FROM " . tablename($this->table_teachers) . " WHERE :schoolid = schoolid And :id = id", array(
				':schoolid' => $_GPC['schoolid'],
				':id' => $data['t_id'],
				));
			$data['tname'] = $res['tname'];
			$data['mobile'] = $res['mobile'];
			$data['date'] = date('Y-m-d' , $data['starttime']);
			$data['starttime'] = date('H:i' , $data['starttime']);
			$data['endtime'] = date('H:i' , $data['endtime']);
		}	
			
        die ( json_encode ( $data ) );
    }

    #复制一条记录
    if ($operation == 'fwfuzhi')  {

        if (empty($_GPC ['schoolid'])) {
            die ( json_encode ( array (
                'result' => false,
                'msg' => '非法请求！'
            ) ) );
        }
        if(!empty($_GPC ['id'])){
            $data = pdo_fetch("SELECT * FROM " . tablename($this->table_visitors) . " WHERE :schoolid = schoolid And :id = id", array(
                ':schoolid' => $_GPC['schoolid'],
                ':id' => $_GPC['id'],
            ));
            $res = pdo_fetch("SELECT tname,mobile FROM " . tablename($this->table_teachers) . " WHERE :schoolid = schoolid And :id = id", array(
                ':schoolid' => $_GPC['schoolid'],
                ':id' => $data['t_id'],
            ));
            $syname = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE weid = '{$data['weid']}' And type = 'visireason' And schoolid = {$_GPC['schoolid']} And sid = {$data['sy_id']} ORDER BY sid ASC, ssort DESC");
            $data['sname'] = $syname['sname'];
            $data['tname'] = $res['tname'];
            $data['mobile'] = $res['mobile'];
            $data['realicon'] = "https://manger.daren007.com/attachment/".$data['icon'];
        }
        die ( json_encode ( $data ) );
    }

#复制一条记录
if ($operation == 'search_tname')  {

    if (empty($_GPC ['schoolid'])) {
        die ( json_encode ( array (
            'result' => false,
            'msg' => '非法请求！'
        ) ) );
    }
    $weid = $_W['weid'];
    // 查询老师
    $teachcers = pdo_fetchall("SELECT id,tname,mobile FROM " . tablename($this->table_teachers) . " where schoolid = '{$_GPC ['schoolid']}' and weid='$weid' AND tname LIKE '%{$_GPC['search_tname']}%'");

    if($teachcers){
        $data ['teachcers'] = $teachcers;
        $data ['result'] = true;
        $data ['msg'] = '成功获取！';
    }else{
        $data ['result'] = false;
        $data ['msg'] = '无法查找到此老师，请确认姓名';
    }
    die ( json_encode ( $data ) );
}
	
	
?>
<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */global $_W, $_GPC;
   $operation = in_array ( $_GPC ['op'], array ('default','getbjlist','bdxs','skcsign','tkcsign','xskcqdqr','xskcbq','qrjsqd','txsk','signup','newstu','get_kslist','delsign_one','getxgtemplte','sqingjia','kcpingjia','txkcpj','deletetempstu','get_ks_conent') ) ? $_GPC ['op'] : 'default';
    if ($operation == 'default') {
       	die ( json_encode ( array (
	        'result' => false,
	        'msg' => '参数错误'
            ) ) );
  	}
    if ($operation == 'getxgtemplte') {
		if (empty($_GPC ['schoolid'])) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	    }else{
		    $school=  pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " where  id=:id", array(':id' => $_GPC['schoolid']));
			$student = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where   schoolid=:schoolid AND id=:id", array(':schoolid' => $_GPC['schoolid'], ':id' => $_GPC['sid']));
			$stup = $student['points']?$student['points']:0;			
			$item = pdo_fetch("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE id = :id ", array(':id' => $_GPC['kcid']));
			include $this->template('comtool/xgtemple');
		}
  	}	
	if ($operation == 'get_kslist')  {
		if (! $_GPC ['schoolid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	    }else{
			$starttime = mktime(0,0,0,date("m"),date("d"),date("Y"));
			$endtime = $starttime + 86399;
			$kecheng = pdo_fetch("SELECT OldOrNew FROM " . tablename($this->table_tcourse) . " where schoolid = '{$_GPC['schoolid']}' And id = '{$_GPC['kcid']}' ");
			if($kecheng['OldOrNew'] == 0){
				$condition = " AND date > '{$starttime}' AND date < '{$endtime}'";	
				$kslist = pdo_fetchall("SELECT id,sd_id FROM " . tablename($this->table_kcbiao) . " where schoolid = '{$_GPC['schoolid']}' And kcid = '{$_GPC['kcid']}'  $condition ORDER BY date DESC");
				foreach($kslist as $key => $row){
					$teacher = pdo_fetch("SELECT tname FROM " . tablename($this->table_teachers) . " where id = '{$row['qrtid']}' ");
					$sd = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " where sid = '{$row['sd_id']}' ");
					$kslist[$key]['teacher'] = $teacher['tname'];
					$isqd = pdo_fetch("SELECT status,createtime FROM " . tablename($this->table_kcsign) . " where sid = '{$_GPC['sid']}' And kcid = '{$_GPC['kcid']}' AND ksid = '{$row['id']}' ");
					$kslist[$key]['isqd'] = false;
					$kslist[$key]['isqr'] = false;
					if($isqd['status'] == 2 || $isqd){
						$kslist[$key]['isqd'] = true;
						if($isqd['status'] == 2){
							$kslist[$key]['isqr'] = true;
						}
					}
					$kslist[$key]['qdtime'] = date('H:i',$isqd['createtime']);
					$kslist[$key]['sdname'] = $sd['sname'];
				}
			}
			if($kecheng['OldOrNew'] == 1){
				$condition = " AND createtime > '{$starttime}' AND createtime < '{$endtime}'";	
				$kslist = pdo_fetchall("SELECT id,createtime,qrtid,status FROM " . tablename($this->table_kcsign) . " where schoolid = '{$_GPC['schoolid']}' And kcid = '{$_GPC['kcid']}' AND sid = '{$_GPC['sid']}' $condition ORDER BY createtime DESC");
				foreach($kslist as $key => $row){
					$teacher = pdo_fetch("SELECT tname FROM " . tablename($this->table_teachers) . " where id = '{$row['qrtid']}' ");
					$kslist[$key]['isqr'] = false;
					$kslist[$key]['teacher'] = $teacher['tname'];
					if($row['status'] == 2){
						$kslist[$key]['isqr'] = true;
					}
					$kslist[$key]['sdname'] = "签到".date('H:i',$row['createtime']);
				}
			}
			$data ['OldOrNew'] = $kecheng['OldOrNew'];
   			$data ['kslist'] = $kslist;
			$data ['result'] = true;
			$data ['msg'] = '成功获取！';
          	die ( json_encode ( $data ) );
		}
    }
	if ($operation == 'getbjlist')  {
		if (! $_GPC ['schoolid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	    }else{
			$data = array();
			$bjlist = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where schoolid = '{$_GPC['schoolid']}' And parentid = '{$_GPC['gradeId']}' And type = 'theclass' ORDER BY ssort DESC");
   			$data ['bjlist'] = $bjlist;
			$data ['result'] = true;
			$data ['msg'] = '成功获取！';
          	die ( json_encode ( $data ) );
		}
    }

    if ($operation == 'bdxs') {
		$data = explode ( '|', $_GPC ['json'] );
		if (! $_GPC ['schoolid'] || ! $_W ['openid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	    }
		$subjectId = trim($_GPC['subjectId']);
		$school = pdo_fetch("SELECT bd_type FROM " . tablename($this->table_index) . " WHERE id = {$_GPC['schoolid']} ");
		if ($school['bd_type'] ==1 || $school['bd_type'] ==4 || $school['bd_type'] ==5 || $school['bd_type'] ==7){
			$bdset = get_weidset($_GPC['weid'],'bd_set');
			$sms_set = get_school_sms_set($_GPC ['schoolid']);
			if($sms_set['code'] ==1 && $bdset['sms_SignName'] && $bdset['sms_Code']){
				$mobile = !empty($_GPC['mymobile']) ? $_GPC['mymobile'] : $_GPC['mobile'];
				$status = check_verifycode($mobile, $_GPC['mobilecode'], $_GPC['weid']);
				if(!$status) {
					 die ( json_encode ( array (
					 'result' => false,
					 'msg' => '短信验证码错误或已过期！' 
					  ) ) );
				}				
			}else{
				if(empty($_GPC['mymobile'])){
					$condition .= " AND mobile = '{$_GPC['mobile']}'";
				}
			}
		}
		if ($school['bd_type'] ==2 || $school['bd_type'] ==4 || $school['bd_type'] ==6 || $school['bd_type'] ==7){
			$condition .= " AND code = '{$_GPC['code']}'";
		}
		if ($school['bd_type'] ==3 || $school['bd_type'] ==5 || $school['bd_type'] ==6 || $school['bd_type'] ==7){
			$condition .= " AND numberid = '{$_GPC['xuehao']}'";
		}
		if(empty($_GPC['sid'])){
			$sid = pdo_fetch("SELECT id FROM " . tablename($this->table_students) . " where :schoolid = schoolid And :weid = weid And :s_name = s_name $condition", array(
					 ':weid' => $_GPC ['weid'],
					 ':schoolid' => $_GPC ['schoolid'],
					 ':s_name'=>$_GPC ['s_name']
					));
			$stuid = $sid['id'];
		}else{
			$stuid = $_GPC['sid'];
		}		  
		$user = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where :schoolid = schoolid And weid = :weid AND sid=:sid And uid =:uid ", array(
		         ':weid' => $_GPC ['weid'],
                 ':schoolid' => $_GPC ['schoolid'],				 
		         ':sid' => $stuid,
				 ':uid' => $_GPC['uid'],
	           	  ));				  
        $item = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where :schoolid = schoolid And weid = :weid AND id=:id ", array(
		         ':weid' => $_GPC ['weid'],
                 ':schoolid' => $_GPC ['schoolid'],				 
		         ':id' => $stuid
	           	  ));
		if(!empty($user)){
		    die ( json_encode ( array (
	            'result' => false,
	            'msg' => '您已绑定本学生,不可重复绑定！' 
	        ) ) );
		}				  
		if(empty($stuid)){
		    die ( json_encode ( array (
                'result' => false,
                'msg' => '没有找到该生信息,或信息输入有误！' 
	         ) ) );
		}
		if($subjectId == 2){	
			if (!empty($item['mom'])){
				die ( json_encode ( array (
	                'result' => false,
	                'msg' => '绑定失败，此学生母亲已经绑定了其他微信号！' 
		        ) ) );
			}	  
        }
		if($subjectId == 3){
			if (!empty($item['dad'])){
			  	die ( json_encode ( array (
	                'result' => false,
	                'msg' => '绑定失败，此学生父亲已经绑定了其他微信号！' 
	          	) ) );
			}
        }
		if($subjectId == 4){
			if (!empty($item['own'])){
				die ( json_encode ( array (
	                'result' => false,
	                'msg' => '绑定失败，此学生本人已经绑定了其他微信号！' 
		        ) ) );
			}
        }
		if($subjectId == 5){
			if (!empty($item['other'])){
				die ( json_encode ( array (
	                'result' => false,
	                'msg' => '绑定失败，此学生家长已经绑定了其他微信号！' 
		        ) ) );
			}
        }		
		if (empty($_GPC['openid'])) {
                die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		        ) ) );
		}else{
			if($item['keyid'] != 0 ){
				$allstu = pdo_fetchall("SELECT * FROM " . tablename($this->table_students) . " where :schoolid = schoolid And weid = :weid AND keyid=:keyid ", array(
				':weid' => $_GPC ['weid'],
				':schoolid' => $_GPC ['schoolid'],				 
				':keyid' => $item['keyid']
				));

				foreach( $allstu as $key => $value )
				{
					$userdata = array(
						'sid' => $value['id'],
						'weid' =>  $_GPC ['weid'],
						'schoolid' => $_GPC ['schoolid'],
						'openid' => $_W ['openid'],
						'pard' => $subjectId,
						'uid' => $_GPC['uid'],
						'createtime' => time()
					);
					
					if(!empty($_GPC['mobile']) || !empty($_GPC['mymobile'])){
						if(!$_GPC['mymobile']){
							$userinfo = array(
								'name' => $_GPC['s_name'].get_guanxi($subjectId),
								'mobile' => trim($_GPC['mobile'])
							);								
						}else{
							$userinfo = array(
								'name' => $_GPC['realname'],
								'mobile' => trim($_GPC['mymobile'])
							);								
						}
						$userdata['userinfo'] = iserializer($userinfo);
					}	
					pdo_insert($this->table_user, $userdata);
					$userid = pdo_insertid();
					if($subjectId == 2){
						$temp = array( 
							'mom' => $_GPC['openid'],
							'muserid' => $userid,
							'muid'=> $_GPC['uid']
						);
					}
					if($subjectId == 3){
						$temp = array(
							'dad' => $_GPC['openid'],
							'duserid' => $userid,
							'duid'=> $_GPC['uid']
						);
					}
					if($subjectId == 4){
						$temp = array(
							'own' => $_GPC['openid'],
							'ouserid' => $userid,
							'ouid'=> $_GPC['uid']
						);
					}
					if($subjectId == 5){
						$temp = array(
							'other' => $_GPC['openid'],
							'otheruserid' => $userid,
							'otheruid'=> $_GPC['uid']
						);
					}			
					pdo_update($this->table_students, $temp, array('id' => $value['id']));  
				}
			}else{
				$userdata = array(
					'sid' => trim($stuid),
					'weid' =>  $_GPC ['weid'],
					'schoolid' => $_GPC ['schoolid'],
					'openid' => $_W ['openid'],
					'pard' => $subjectId,
					'uid' => $_GPC['uid'],
					'createtime' => time()
				);
				if(!empty($_GPC['mobile']) || !empty($_GPC['mymobile'])){
					if(!$_GPC['mymobile']){
						$userinfo = array(
							'name' => $_GPC['s_name'].get_guanxi($subjectId),
							'mobile' => trim($_GPC['mobile'])
						);								
					}else{
						$userinfo = array(
							'name' => $_GPC['realname'],
							'mobile' => trim($_GPC['mymobile'])
						);								
					}
					$userdata['userinfo'] = iserializer($userinfo);
				}					
				pdo_insert($this->table_user, $userdata);			
				$userid = pdo_insertid();
				if($subjectId == 2){
					$temp = array( 
						'mom' => $_GPC['openid'],
						'muserid' => $userid,
						'muid'=> $_GPC['uid']
						);
				}
				if($subjectId == 3){
					$temp = array(
						'dad' => $_GPC['openid'],
						'duserid' => $userid,
						'duid'=> $_GPC['uid']
						);
				}
				if($subjectId == 4){
					$temp = array(
						'own' => $_GPC['openid'],
						'ouserid' => $userid,
						'ouid'=> $_GPC['uid']
						);
				}
				if($subjectId == 5){
					$temp = array(
						'other' => $_GPC['openid'],
						'otheruserid' => $userid,
						'otheruid'=> $_GPC['uid']
						);
				}			
				pdo_update($this->table_students, $temp, array('id' => $stuid));   
			}
			$data ['result'] = true;			
			$data ['msg'] = '绑定成功,即将跳转...';
		 die ( json_encode ( $data ) );
		}
    }

	if ($operation == 'skcsign') {
		
		if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
		}else{
			$checkkc = pdo_fetch("select id,tid,maintid,OldOrNew,FirstNum,ReNum FROM ".tablename($this->table_tcourse)." WHERE weid='{$_GPC['weid']}' And schoolid='{$_GPC['schoolid']}' And  id = '{$_GPC['kcid']}'");
			if(empty($checkkc)){
				die ( json_encode ( array (
                    'result' => false,
                    'msg' => '该课程不存在！' 
		               ) ) );
			}
		    $data = array(
				'kcid' => $_GPC['kcid'],
				'schoolid' => $_GPC['schoolid'],
				'weid' => $_GPC['weid'],
				'sid'  => $_GPC['sid'],
				'createtime' => time(),
				'status' => 1,
				'type' => 1 
		    );
		    if($checkkc['OldOrNew'] == 1){
			    $checkAll = pdo_fetchcolumn("select count(*) FROM ".tablename($this->table_kcsign)." WHERE weid='{$_GPC['weid']}' And schoolid='{$_GPC['schoolid']}' And  kcid = '{$_GPC['kcid']}' And sid='{$_GPC['sid']}' AND status=2 ");
			    $buy = pdo_fetch("select ksnum FROM ".tablename($this->table_coursebuy)." WHERE weid='{$_GPC['weid']}' And schoolid='{$_GPC['schoolid']}' And  kcid = '{$_GPC['kcid']}' And sid='{$_GPC['sid']}'");
			    $timeUp = strtotime(date("Ymd",time()));
			    $timeDown = $timeUp + 86399;
			    $checkteacher = pdo_fetch("select id FROM ".tablename($this->table_kcsign)." WHERE weid='{$_GPC['weid']}' And schoolid='{$_GPC['schoolid']}' And  kcid = '{$_GPC['kcid']}' And createtime>{$timeUp} And createtime<{$timeDown} ");
			    if($checkAll >=$buy['ksnum']){
				    die ( json_encode ( array (
                    'result' => false,
                    'msg' => '您的购买课时已用完，请续费后重新签到！' 
		               ) ) );
			    }
		  		pdo_insert($this->table_kcsign, $data);
		    }elseif($checkkc['OldOrNew'] ==0){
			     $checkAll = pdo_fetchcolumn("select count(*) FROM ".tablename($this->table_kcsign)." WHERE weid='{$_GPC['weid']}' And schoolid='{$_GPC['schoolid']}' And  kcid = '{$_GPC['kcid']}' And sid='{$_GPC['sid']}' AND status = 2 ");
			     $buy = pdo_fetch("select ksnum FROM ".tablename($this->table_coursebuy)." WHERE weid='{$_GPC['weid']}' And schoolid='{$_GPC['schoolid']}' And  kcid = '{$_GPC['kcid']}' And sid='{$_GPC['sid']}'");
			    $checkks = pdo_fetch("select id FROM ".tablename($this->table_kcbiao)." WHERE weid='{$_GPC['weid']}' And schoolid='{$_GPC['schoolid']}' And  id = '{$_GPC['ksid']}'");
			    if(empty($checkks)){
					die ( json_encode ( array (
	                    'result' => false,
	                    'msg' => '该课时不存在！' 
			               ) ) );
				}
				if(!empty($checkkc['ReNum'])){
				  if($checkAll >=$buy['ksnum']){
				    die ( json_encode ( array (
                    'result' => false,
                    'msg' => '您的购买课时已用完，请续费后重新签到！' 
		               ) ) );
			    	}
		    	}
				$checkteacher = pdo_fetch("select id FROM ".tablename($this->table_kcsign)." WHERE weid='{$_GPC['weid']}' And schoolid='{$_GPC['schoolid']}' And  kcid = '{$_GPC['kcid']}' And  ksid = '{$_GPC['ksid']}' ");
			   	$data['ksid'] = $_GPC['ksid'];
			   	$data['type'] = 0 ;
			   pdo_insert($this->table_kcsign, $data);
		    }
		    $insertid = pdo_insertid();
		    if(!empty($insertid)){
			    if(empty($checkteacher)){
			      	$this->sendMobileTxjsqd($_GPC['kcid'],$_GPC['schoolid'],$_GPC['weid']);
			    }
			    $data_r ['result'] = true;
				$data_r ['msg'] = '签到成功，请勿重复签到！';	
		      	die ( json_encode ( $data_r ) );
		    }else{
			    $data_r ['result'] = false;
				$data_r ['msg'] = '签到失败，数据无法写入！';	
		      	die ( json_encode ( $data_r ) );
		    }
	    }
	}

	if ($operation == 'tkcsign') {
		if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
		}else{
			$checkkc = pdo_fetch("select id FROM ".tablename($this->table_tcourse)." WHERE weid='{$_GPC['weid']}' And schoolid='{$_GPC['schoolid']}' And  id = '{$_GPC['kcid']}'");
			if(empty($checkkc)){
				die ( json_encode ( array (
                    'result' => false,
                    'msg' => '该课程不存在！' 
		               ) ) );
			}
		    $data = array(
				'kcid' => $_GPC['kcid'],
				'schoolid' => $_GPC['schoolid'],
				'weid' => $_GPC['weid'],
				'tid'  => $_GPC['tid'],
				'createtime' => time(),
				'status' => 1,
				'type' => 1 
		    );
		   
		    if($_GPC['OldOrNew'] == 1){
		    	$time = strtotime(date('Ymd'));
				$time1 =$time + 86399;
				$checksignNotMy = pdo_fetch("select id FROM ".tablename($this->table_kcsign)." WHERE createtime>='{$time}' AND createtime<'{$time1}' And kcid = '{$_GPC['kcid']}' AND tid!='{$_GPC['tid']}' AND sid=0 and status=2 ");
			$checksignMy = pdo_fetch("select id,status FROM ".tablename($this->table_kcsign)." WHERE createtime>='{$time}' AND createtime<'{$time1}' And kcid = '{$_GPC['kcid']}' AND tid='{$_GPC['tid']}' ");
				if(!empty($checksignMy)){
					if($checksignMy['status'] ==1){
						die ( json_encode ( array (
		                    'result' => false,
		                    'msg' => '签到失败！您已经签到，请等待确认' 
		               ) ) );
					}elseif($checksignMy['status'] ==2){
						die ( json_encode ( array (
		                    'result' => false,
		                    'msg' => '签到失败！您已经签到并被确认' 
		               ) ) );
					}
				}
				if(!empty($checksignNotMy)){
					die ( json_encode ( array (
                    'result' => false,
                    'msg' => '签到失败！该课程今日已有其他老师签到' 
		               ) ) );
				}
			 	if($_GPC['is_dq'] == 'njzrdq'){
			    	$data['status'] = 2;
		    	};
		  		pdo_insert($this->table_kcsign, $data);
		    }elseif($_GPC['OldOrNew'] ==0){
			    $checkks = pdo_fetch("select id FROM ".tablename($this->table_kcbiao)." WHERE weid='{$_GPC['weid']}' And schoolid='{$_GPC['schoolid']}' And  id = '{$_GPC['ksid']}'");
			    if(empty($checkks)){
				die ( json_encode ( array (
                    'result' => false,
                    'msg' => '该课时不存在！' 
		               ) ) );
				}
				$checksignMy = pdo_fetch("select id,status FROM ".tablename($this->table_kcsign)." WHERE weid='{$_GPC['weid']}' And schoolid='{$_GPC['schoolid']}' And ksid = '{$_GPC['ksid']}' and  tid = '{$_GPC['tid']}'");
				$checksignNotMy = pdo_fetch("select id FROM ".tablename($this->table_kcsign)." WHERE weid='{$_GPC['weid']}' And schoolid='{$_GPC['schoolid']}' And ksid = '{$_GPC['ksid']}' And status=2 And tid != '{$_GPC['tid']}'");
				if(!empty($checksignMy)){
					if($checksignMy['status'] ==1){
						die ( json_encode ( array (
		                    'result' => false,
		                    'msg' => '签到失败！您已经签到，请等待确认' 
		               ) ) );
					}elseif($checksignMy['status'] ==2){
						die ( json_encode ( array (
		                    'result' => false,
		                    'msg' => '签到失败！您已经签到并被确认' 
		               ) ) );
					}
				}
				if(!empty($checksignNotMy)){
					die ( json_encode ( array (
                    'result' => false,
                    'msg' => '签到失败！该课时已有其他老师签到' 
		               ) ) );
				}
			   	$data['ksid'] = $_GPC['ksid'];
			   	$data['type'] = 0 ;
			 	if($_GPC['is_dq'] == 'njzrdq'){
			    	$data['status'] = 2;
		    	};
			   	pdo_insert($this->table_kcsign, $data);
		    }
		    $insertid = pdo_insertid();
		    if(!empty($insertid)){
			    if($_GPC['is_dq'] != 'njzrdq'){
				   $this->sendMobileJsqrqdtz($insertid, $_GPC ['schoolid'], $_W['uniacid']);
		    	};
			    $data_r ['result'] = true;
				$data_r ['msg'] = '签到成功，请勿重复签到！';	
		      	die ( json_encode ( $data_r ) );
		    }else{
			    $data_r ['result'] = false;
				$data_r ['msg'] = '签到失败，数据无法写入！';	
		      	die ( json_encode ( $data_r ) );
		    }
	    }
	}

	if ($operation == 'xskcqdqr') {
		if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
		}else{
			$signids = explode ( ',', $_GPC ['logids'] );
			if($signids){
				foreach($signids as $row){
					if($row > 0 ){
						pdo_update($this->table_kcsign, array('status' => 2,'qrtid'=>$_GPC['qrtid']), array('id' => $row));
						$this->sendMobileXsqrqdtz($row, $_GPC ['schoolid'], $_W['uniacid']);
					}
				}
				die ( json_encode ( array (
                    'result' => true,
                    'msg' => '签到确认成功！' 
		               ) ) );
			}else{
				die ( json_encode ( array (
                    'result' => false,
                    'msg' => '您没有选择学生！' 
		               ) ) );
			}
	    }
	}

	if ($operation == 'xskcbq') {
		if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
		}else{
			$StuUid = explode ( ',', $_GPC ['StuUid'] );
			$not = '';
			$do = 0;
			$back_stu_arr = array();
			if($StuUid){
				foreach($StuUid as $row){
					if($row > 0 ){
                        $student =  pdo_fetch("select s_name FROM ".tablename($this->table_students)." WHERE weid='{$_GPC['weid']}' And schoolid='{$_GPC['schoolid']}' And id='{$row}'");
						$hasbuynum = pdo_fetchcolumn("select ksnum FROM ".tablename($this->table_coursebuy)." WHERE weid='{$_GPC['weid']}' And schoolid='{$_GPC['schoolid']}' And  kcid = '{$_GPC['kcid']}' AND sid = '{$row}'");
				  		$checkAll = pdo_fetchcolumn("select count(*) FROM ".tablename($this->table_kcsign)." WHERE weid='{$_GPC['weid']}' And schoolid='{$_GPC['schoolid']}' And  kcid = '{$_GPC['kcid']}' And sid='{$row}'");
						$data = array(
							'kcid' => $_GPC['kcid'],
							'schoolid' => $_GPC['schoolid'],
							'weid' => $_GPC['weid'],
							'sid'  => $row,
							'createtime' => $_GPC['time'],
							'status' => 2,
							'type' => 1,
							'qrtid'=>$_GPC['qrtid'] 
					    );
					    if($checkAll >= $hasbuynum){

						    $not .= $student['s_name'].'/';
					    }elseif($checkAll < $hasbuynum){
					       	if($_GPC['OldOrNew'] == 1){
			  					pdo_insert($this->table_kcsign, $data);

			  					$insertid = pdo_insertid();
                                $back_stu_arr[] = array(
                                    'sname' => $student['s_name'],
                                    'id' => $row,
                                    'time' =>date("H:i",time())
                                );
						    }elseif($_GPC['OldOrNew'] ==0){
							    $checkks = pdo_fetch("select id FROM ".tablename($this->table_kcbiao)." WHERE weid='{$_GPC['weid']}' And schoolid='{$_GPC['schoolid']}' And  id = '{$_GPC['ksid']}'");
							    if(empty($checkks)){
								die ( json_encode (array(
				                    'result' => false,
				                    'msg' => '该课时不存在！' 
						               ) ) );
								}
							   	$data['ksid'] = $_GPC['ksid'];
							   	$data['type'] = 0 ;
						   		$data['createtime'] = time() ;
							   	pdo_insert($this->table_kcsign, $data);
							   	$insertid = pdo_insertid();
                                $back_stu_arr[] = array(
                                    'sname' => $student['s_name'],
                                    'id' => $row,
                                    'time' =>date("H:i",time())
                                );
						    }
							$this->sendMobileXsqrqdtz($insertid, $_GPC ['schoolid'], $_W['uniacid']);
							$do++;
						}
					}
				}
				$backstr = $do."名学生操作成功！";
				if($not !=''){
					$backstr.="\n下列学生课时已用完，签到失败：\n".$not;	
				}
				
				die ( json_encode ( array (
                    'result' => true,
                    'msg' =>$backstr,
                    'back_data' => $back_stu_arr
		               ) ) );
			}else{
				die ( json_encode ( array (
                    'result' => false,
                    'msg' => '您没有选择学生！' 
		               ) ) );
			}
	    }
	}

	if ($operation == 'qrjsqd') {
		if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
		}else{
			$kcsignid = $_GPC['id'];
			$checksign = pdo_fetch("select * FROM ".tablename($this->table_kcsign)." WHERE weid='{$_GPC['weid']}' And schoolid='{$_GPC['schoolid']}' And  id = '{$kcsignid}'");
			if(empty($checksign)){
				 die ( json_encode ( array (
                    'result' => false,
                    'msg' => '该签到记录不存在！' 
		               ) ) );
			}else{
				if($checksign['type'] ==0){
					$checkother =  pdo_fetch("select * FROM ".tablename($this->table_kcsign)." WHERE weid='{$_GPC['weid']}' And schoolid='{$_GPC['schoolid']}' And  kcid = '{$checksign['kcid']}' And ksid='{$checksign['ksid']}' And sid=0 And status=2 ");
				}elseif($checksign['type'] ==1){
					$timeUp = strtotime(date("Ymd",$checksign['createtime']));
					$timeDown = $timeUp +86399;
					$checkother =  pdo_fetch("select * FROM ".tablename($this->table_kcsign)." WHERE weid='{$_GPC['weid']}' And schoolid='{$_GPC['schoolid']}' And  kcid = '{$checksign['kcid']}'  And sid=0 And createtime>{$timeUp} And createtime<{$timeDown} And status=2 ");
				}
				if(!empty($checkother)){
					 die ( json_encode ( array (
                    'result' => false,
                    'msg' => '该课时已有其他老师签到成功！' 
		               ) ) );
				}elseif(empty($checkother)){
					pdo_update($this->table_kcsign,array('status'=>2),array('id'=>$kcsignid));
			      	$this->sendMobileQrjsqdtz($_GPC['kcid'],$_GPC['schoolid'],$_GPC['weid']);
					 die ( json_encode ( array (
	                    'result' => true,
	                    'msg' => '确认签到成功！' 
			               ) ) );
				}
			}
	    }
	}
    if ($operation == 'txsk') {
		if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
		}else{
			$id = intval($_GPC['id']);
		    $schoolid = intval($_GPC['schoolid']);
		    $weid = intval($_GPC['weid']);
		    if(empty($id)){
			     die ( json_encode ( array (
                    'result' => false,
                    'msg' => '抱歉，本条信息不存在在或是已经被删除！！' 
		            ) ) );
		    }
		    $this->sendMobileJssktx($id,$schoolid,$weid);
			pdo_update($this->table_kcbiao,array('is_remind'=>1),array('id'=>$id));
			die ( json_encode ( array (
                'result' => true,
                'msg' => '提醒教师成功！' 
	        ) ) );
		}
	}

	if ($operation == 'signup'){
		$data = explode ( '|', $_GPC ['json'] );
		if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	         }
			 
			 
		$shareuserid = $_GPC['shareuserid'];
	
        $setting = pdo_fetch("SELECT * FROM " . tablename($this->table_set) . " WHERE :weid = weid", array(':weid' => $_GPC['weid']));
		$school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " WHERE :id = id", array(':id' => $_GPC['schoolid']));
		$user = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " WHERE :weid = weid And :schoolid = schoolid And :id = id", array(':weid' => $_GPC['weid'], ':schoolid' => $_GPC['schoolid'], ':id' => $_GPC['user']));
		$student = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " WHERE :weid = weid And :schoolid = schoolid And :id = id", array(':weid' => $_GPC['weid'], ':schoolid' => $_GPC['schoolid'], ':id' => $_GPC['sid']));
		$cose = pdo_fetch("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE :id = id", array(':id' => $_GPC['kcid'])); 
		if($_GPC['is_point'] != 0){
			$point_dy = intval($_GPC['point']);
			$dyl = intval($cose['Point2Cost']);
			$dyfy =sprintf("%.2f",  $point_dy / $dyl);;
			$final_cose = $cose['cose'] - $dyfy;
		}else{
			$final_cose =$cose['cose'];
		}
		
		if ($final_cose <= 0) {
            die ( json_encode ( array (
                    'result' => false,
                    'msg' => '抱歉，抵用价格必须小于课程价格'
		               ) ) );			
		}
		$issale = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE :weid = weid And :schoolid = schoolid And :kcid = kcid And :sid = sid", array(':weid' => $_GPC['weid'], ':schoolid' => $_GPC['schoolid'], ':kcid' => $_GPC['kcid'], ':sid' => $_GPC['sid'])); 
		$userinfo = iunserializer($user['userinfo']);
		$yb = pdo_fetchcolumn("select count(*) FROM ".tablename('wx_school_order')." WHERE kcid = '".$cose['id']."' And (status = 2 or type = 2) ");
		$rest = $cose['minge'] - $yb;
		//if ($cose['xq_id'] != 0) {
		//	if ($cose['xq_id'] != $student['xq_id']) {
		//			die ( json_encode ( array (
		//				'result' => false,
		//				'msg' => '本课程只限本年级学生报名！'
		//				) ) );
  //          }					   
		//}
		
		if (empty($userinfo['name'])) {
            die ( json_encode ( array (
                    'result' => false,
                    'msg' => '请前往个人中心完善您的联系方式'
		               ) ) );			
		}		
		if ($rest < 1){
            die ( json_encode ( array (
                    'result' => false,
                    'msg' => '本课程已满'
		               ) ) );			
		}
		if (!empty($issale)) {
            die ( json_encode ( array (
                    'result' => false,
                    'msg' => '抱歉,您已报名本课程,请查看订单'
		               ) ) );			
		}		
		if (time() >= $cose['end']) {
            die ( json_encode ( array (
                    'result' => false,
                    'msg' => '本课程已经结束'
		               ) ) );
		}		
		if (empty($_GPC['openid'])) {
            die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求'
		               ) ) );			
		}else{
			$schoolid = $_GPC['schoolid'];
			$weid = $_GPC['weid'];
			$sid = $_GPC['sid'];
			$userid = $_GPC['uid'];
			$orderid = "{$userid}{$sid}";
			$temp = array(
					'weid' =>  $_GPC ['weid'],
					'schoolid' => $_GPC ['schoolid'],
					'sid' => $_GPC ['sid'],
					'userid' => $_GPC ['user'],
					'type' => 1,
					'status' => 1,
					'kcid' => $_GPC ['kcid'],
					'uid' => $_GPC['uid'],
					'cose' => $final_cose,
					'payweid' => $cose['payweid'],
					'orderid' => $orderid,
					'createtime' => time(),
					'spoint' => $point_dy
			);
			
			$temp['ksnum'] = $cose['FirstNum'];
			
			if(!empty($shareuserid)){
				$ShareUserInfo = pdo_fetch("SELECT sid FROM " . tablename($this->table_user) . " WHERE :weid = weid And :schoolid = schoolid And :id = id", array(':weid' => $_GPC['weid'], ':schoolid' => $_GPC['schoolid'], ':id' => $shareuserid));
				if($ShareUserInfo['sid'] != $_GPC['sid']){
					$temp['shareuserid'] = $shareuserid;
				}
				
			}
			pdo_insert($this->table_order, $temp);
			$order_id = pdo_insertid();
			$data ['result'] = true;
			$data ['msg'] = '报名成功,请前往个人中心查看';
		 die ( json_encode ( $data ) );
		}
    }

	if ($operation == 'newstu'){

		if (! $_GPC['schoolid'] || ! $_GPC['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
		}else{
			if (empty($_GPC['openid'])) {
	           die ( json_encode ( array (
	                    'result' => false,
	                    'msg' => '非法请求'
			               ) ) );			
			}else{
			    $schoolid = intval($_GPC['schoolid']);
			    $weid     = intval($_GPC['weid']);
			    $openid   = $_GPC['openid'];
			    $kcid     = intval($_GPC['kcid']);
			    $uid      = $_GPC['uid'];
			    $sname    = $_GPC ['sname'];
			    $sex      = $_GPC['sex'];
			    $mobile   = $_GPC['mobile'];
			    $addr     = $_GPC['addr'];
			    $nj_id    = $_GPC['nj'];
			    $bj_id    = $_GPC['bj'];
			    $pard     = $_GPC['pard'];
				$shareuserid = $_GPC['shareuserid'];
				$checknewstu = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " WHERE weid = {$weid} And schoolid = {$schoolid} And s_name like '{$sname}' And mobile={$mobile} And sex = {$sex} "); 
				if(!empty($checknewstu)){
					 die ( json_encode ( array (
	                    'result' => false,
	                   'msg' => '对不起，该学生已经存在！' 
			               ) ) );
				}

				$checknewstu1 = pdo_fetch("SELECT id,sname FROM " . tablename($this->table_tempstudent) . " WHERE weid = {$weid} And schoolid = {$schoolid} And sname like '{$sname}' And mobile={$mobile} And sex = {$sex} "); 
				if(!empty($checknewstu1)){

					$hasOrder = pdo_fetch("SELECT id FROM " . tablename($this->table_order) . " WHERE weid = {$weid} And schoolid = {$schoolid} And tempsid = '{$checknewstu1['id']}' And kcid={$_GPC['kcid']} And status = 1 "); 
					if(!empty($hasOrder)){
				 		die ( json_encode ( array (
				                    'result' => false,
				                    'is_order' => true,
				                    'orderId' => $hasOrder['id'],
				                    'tempstuid'=>$checknewstu1['id'],
				                   	'msg' => '对不起，该学生已报名但未支付！' 
			               		) ) );
	            		 	}
				}
				$data_tempstu = array(
					'weid' => $weid,
					'schoolid' => $schoolid,
					'sname' =>$sname,
					'mobile'=> $mobile,
					'sex' => $sex,
					'addr' => $addr,
					'nj_id' =>$nj_id,
					'bj_id' => $bj_id,
					'pard' => $pard,
					'openid' => $openid,
					'uid' => $uid
				);
				pdo_insert($this->table_tempstudent,$data_tempstu);
				$tempstuid = pdo_insertid();
				$school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " WHERE :id = id", array(':id' => $_GPC['schoolid']));
				$cose = pdo_fetch("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE :id = id", array(':id' => $_GPC['kcid'])); 
				$final_cose =$cose['cose'];
				$yb = pdo_fetchcolumn("select count(*) FROM ".tablename($this->table_order)." WHERE kcid = '".$cose['id']."' And (status = 2 or type = 2) ");
				$rest = $cose['minge'] - $yb;
				if ($rest < 1){
		            die ( json_encode ( array (
		                    'result' => false,
		                    'msg' => '本课程已满'
				               ) ) );			
				}
				if (time() >= $cose['end']) {
		            die ( json_encode ( array (
		                    'result' => false,
		                    'msg' => '本课程已经结束'
				               ) ) );
				}		
				$orderid = "{$uid}{$tempstuid}";
				$temp = array(
						'weid' =>  $_GPC ['weid'],
						'schoolid' => $_GPC ['schoolid'],
						'tempsid' => $tempstuid,
						'tempopenid' => $openid,
						'type' => 1,
						'status' => 1,
						'kcid' => $_GPC ['kcid'],
						'uid' => $uid,
						'cose' => $final_cose,
						'payweid' => $cose['payweid'],
						'orderid' => $orderid,
						'createtime' => time(),
				);
				
				$temp['ksnum'] = $cose['FirstNum'];
				if(!empty($shareuserid)){
					$temp['shareuserid'] = $shareuserid;
				}
				pdo_insert($this->table_order, $temp);
				$order_id = pdo_insertid();
				$data ['result'] = true;
				$data ['msg'] = '报名成功,请前往个人中心查看';
				$data ['orderid'] = $order_id;
			 	die ( json_encode ( $data ) );
			}
		}
	} 
	if ($operation == 'delsign_one'){
		if (! $_GPC['schoolid'] || ! $_GPC['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
		}else{
			$signid= $_GPC['signid'];
			$check = pdo_fetch("SELECT id,status FROM " . tablename($this->table_kcsign) . " WHERE :id = id", array(':id' => $signid));
			if(empty($check)){
				 die ( json_encode ( array (
                    'result' => false,
                    'msg' => '该签到记录不存在！' 
		               ) ) );
			}
			if($check['status'] ==2){
				die ( json_encode ( array (
                    'result' => false,
                    'msg' => '该签到记录已被确认，不可删除！' 
		               ) ) );
			}else{
				pdo_delete($this->table_kcsign,array('id'=>$signid));
				die ( json_encode ( array (
                    'result' => true,
                    'msg' => '删除成功！' 
		               ) ) );
			}
		}
	} 
	
	if ($operation == 'sqingjia') {
		if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
		}else{
			$StuUid = explode ( ',', $_GPC ['StuUid'] );
				//die(json_encode($_GPC));
			if($StuUid){
				foreach($StuUid as $row){
					if($row > 0 ){
						 $data = array(
							'kcid' => $_GPC['kcid'],
							'schoolid' => $_GPC['schoolid'],
							'weid' => $_GPC['weid'],
							'sid'  => $row,
							'createtime' => $_GPC['time'],
							'status' => 3,
							'type' => 1,
							'qrtid'=>$_GPC['qrtid'] 
					    );
				       if($_GPC['OldOrNew'] == 1){
		  					pdo_insert($this->table_kcsign, $data);
		  					$insertid = pdo_insertid();
					    }elseif($_GPC['OldOrNew'] ==0){
						    $checkks = pdo_fetch("select id FROM ".tablename($this->table_kcbiao)." WHERE weid='{$_GPC['weid']}' And schoolid='{$_GPC['schoolid']}' And  id = '{$_GPC['ksid']}'");
						    if(empty($checkks)){
							die ( json_encode ( array (
			                    'result' => false,
			                    'msg' => '该课时不存在！' 
					               ) ) );
							}
						   	$data['ksid'] = $_GPC['ksid'];
						   	$data['type'] = 0 ;
					   		$data['createtime'] = time() ;
						   	pdo_insert($this->table_kcsign, $data);
						   	$insertid = pdo_insertid();
					    }
					}
				}
				die ( json_encode ( array (
                    'result' => true,
                    'msg' => '请假成功！' ,
                    'back_time' => date("H:i",time())
		               ) ) );
			}else{
				die ( json_encode ( array (
                    'result' => false,
                    'msg' => '您没有选择学生！' 
		               ) ) );
			}
	    }
	}

	if ($operation == 'kcpingjia') {
		if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'status' => 0,
                    'msg' => '非法请求！' 
		               ) ) );
		}else{
			$check = $_GPC['check'];
			$schoolid = $_GPC['schoolid'];
			$weid = $_GPC['weid'];
			$kcid = $_GPC['kcid'];
			$sid = $_GPC['sid'];
			$userid = $_GPC['userid'];
			$backstr = '';
			$pingjia = $_GPC['pingjia'];
			foreach( $check as $key => $value )
			{
				if($value != 0){
					$temp = array(
						'weid' => $weid,
						'schoolid' => $schoolid,
						'kcid' => $kcid,
						'tid' => $key,
						'sid' => $sid,
						'userid' => $userid,
						'type' => 1,
						'star' => $value,
						'createtime' => time()
					);
					pdo_insert($this->table_kcpingjia,$temp);
					$pingjun =pdo_fetchcolumn("select AVG(star) FROM ".tablename($this->table_kcpingjia)." WHERE weid='{$_GPC['weid']}' And schoolid='{$_GPC['schoolid']}' And  tid = '{$key}' AND star != 0 ");
					pdo_update($this->table_teachers,array('star'=>$pingjun),array('id'=> $key)); 
				}
			}
			if(!empty($pingjia)){
				$temp = array(
						'weid' => $weid,
						'schoolid' => $schoolid,
						'kcid' => $kcid,
						'sid' => $sid,
						'userid' => $userid,
						'type' => 2,
						'content' => $pingjia,
						'createtime' => time()
					);
					pdo_insert($this->table_kcpingjia,$temp);
			}
			die ( json_encode ( array (
                'status' => 1,
                'msg' => '评价完成！'
	               ) ) );
		}
    }

    if ($operation == 'txkcpj') {
		if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'status' => 0,
                    'msg' => '非法请求！' 
		               ) ) );
		}else{
			$schoolid = $_GPC['schoolid'];
			$weid = $_GPC['weid'];
			$kcid = $_GPC['kcid'];
			$kcinfo = pdo_fetch("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE :id = id", array(':id' => $kcid));
			if($kcinfo['is_remind_pj'] == 0){
		 		$send = $this->sendMobileTxkcpj($kcid,$schoolid,$weid);
				 if($send){
				 	pdo_update($this->table_tcourse,array('is_remind_pj' =>1),array('id'=>$kcid));
					die ( json_encode ( array (
		                'result' => true,
		                'msg' => '提醒成功，请勿重复提醒！'
			               ) ) );
		        }else{
			        die ( json_encode ( array (
		                'result' => false,
		                'msg' => '提醒失败，请稍后重试！'
			               ) ) );
		        }
	        }elseif($kcinfo['is_remind_pj'] == 1){
		        die ( json_encode ( array (
		                'result' => false,
		                'msg' => '该课程已经提醒评价，请勿重复提醒！'
			               ) ) );
		        
	        }
		}
    }

  if ($operation == 'deletetempstu') {
		if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'status' => 0,
                    'msg' => '非法请求！' 
		               ) ) );
		}else{ 
			$schoolid = $_GPC['schoolid'];
			$weid = $_GPC['weid'];
			$orderid = $_GPC['orderid'];
			$tempstuid = $_GPC['tempstuid'];
			$kcid = $_GPC['kcid'];
			$orderinfo = pdo_fetch("SELECT tempsid FROM " . tablename($this->table_order) . " WHERE :id = id", array(':id' => $orderid));
			if($orderinfo['tempsid'] == $tempstuid){
				pdo_delete($this->table_order,array('id'=>$orderid));
				pdo_delete($this->table_tempstudent,array('id'=>$tempstuid));
				 die ( json_encode ( array (
		                'result' => true,
		                'msg' => '操作成功！'
			               ) ) );
			}else{
		        die ( json_encode ( array (
		                'result' => false,
		                'msg' => '操作失败，请重试！'
			               ) ) );
	        }
		}
    }
	
	 
?>
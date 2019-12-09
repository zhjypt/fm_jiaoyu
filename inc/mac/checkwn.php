<?php
/**
 * By 高贵血迹
 */
	global $_GPC, $_W;
	$operation = in_array ( $_GPC ['op'], array ('default', 'login', 'class', 'check', 'gps', 'banner', 'video', 'start', 'notice', 'users', 'getdate','getleave','command','getdevremote','checkap','getroomlist') ) ? $_GPC ['op'] : 'default';
	$weid      = $_GPC['i'];
	$schoolid  = $_GPC['schoolid'];
	$macid     = empty($_GPC['macid'])? $_GPC['device_id'] : $_GPC['macid'];
	$ckmac     = pdo_fetch("SELECT * FROM " . tablename($this->table_checkmac) . " WHERE macid = '{$macid}' And weid = '{$weid}' And schoolid = '{$schoolid}' ");
	$school    = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}' ");
	if ($operation == 'default') {
        $result['status'] = -1;
        $result['msg']    = "对不起，你的请求不存在！";
		echo json_encode($result);
		exit;
    }
	if(empty($school)){
        $result['status'] = -1;
        $result['msg']    = "找不到本校，设备未关联学校?";
		echo json_encode($result);		
	}
	if(empty($ckmac)){
        $result['status'] = -1;
        $result['msg']    = "没找到设备,请添加设备";
		echo json_encode($result);
	}	
	if($school['is_recordmac'] == 2){
        $result['status'] = -1;
        $result['msg']    = "本校无权使用设备,请联系管理员";
		echo json_encode($result);		
	}	
	if ($ckmac['is_on'] == 2){
		$result['status'] = -1;
		$result['msg'] = "本设备已关闭,请联系管理员";
		echo json_encode($result);			
	}
	if (empty($_W['setting']['remote']['type'])) { 
		$urls = $_W['SITEROOT'].$_W['config']['upload']['attachdir'].'/'; 
	} else {
		$urls = $_W['attachurl'];
	}
	if ($operation == 'notice') { //getNotice
		if(!empty($school)){
			$banner = unserialize($ckmac['banner']);
			$result['status'] = 0;
			$result['msg'] = "获取数据成功";
			$result['data'] = array(
				'mactag'=>$ckmac['name'],
				'welcome'=>$banner['welcome'],
				'hosturl'=>getoauthurl(),
				'gpsurl'=>$_W['siteroot'] . '',
				'detectionurl'=>'',
				'consumptionurl'=>'',
				'brandurl'=>'',
                'getschoolinfo'  => $_W['siteroot'] . 'app/index.php?i=' . $weid . '&c=entry&schoolid=' . $schoolid . '&do=checkwn&op=login&m=fm_jiaoyu',
                'getclassinfo'   => $_W['siteroot'] . 'app/index.php?i=' . $weid . '&c=entry&schoolid=' . $schoolid . '&do=checkwn&op=class&m=fm_jiaoyu',
                'getstudentinfo'    => $_W['siteroot'] . 'app/index.php?i=' . $weid . '&c=entry&schoolid=' . $schoolid . '&do=checkwn&op=users&m=fm_jiaoyu',
                'posturl'        => $_W['siteroot'] . 'app/index.php?i=' . $weid . '&c=entry&schoolid=' . $schoolid . '&do=checkwn&op=check&m=fm_jiaoyu',
                'leaveurl'       => $_W['siteroot'] . 'app/index.php?i=' . $weid . '&c=entry&schoolid=' . $schoolid . '&do=checkwn&op=getleave&m=fm_jiaoyu',
                'outTimeUrl'     => $_W['siteroot'] . 'app/index.php?i=' . $weid . '&c=entry&schoolid=' . $schoolid . '&do=checkwn&op=class&m=fm_jiaoyu',
                'commondurl'     => $_W['siteroot'] . 'app/index.php?i=' . $weid . '&c=entry&schoolid=' . $schoolid . '&do=checkwn&op=command&m=fm_jiaoyu',
                'DeviceSetUrl'   => $_W['siteroot'] . 'app/index.php?i=' . $weid . '&c=entry&schoolid=' . $schoolid . '&do=checkwn&op=getdevremote&m=fm_jiaoyu',
                'checkapUrl'     => $_W['siteroot'] . 'app/index.php?i=' . $weid . '&c=entry&schoolid=' . $schoolid . '&do=checkwn&op=checkap&m=fm_jiaoyu',
                'getroomlisturl' => $_W['siteroot'] . 'app/index.php?i=' . $weid . '&c=entry&schoolid=' . $schoolid . '&do=checkxz&op=getroomlist&m=fm_jiaoyu',
				'password'=>$banner['password'],
				'starttime'=>$banner['starttime'],
				'shutdowntime'=>$banner['shutdowntime'],
				'posturl1'=>'',
				'posturl2'=>'',
				'deviceType'=>$ckmac['apid']?11:$ckmac['is_bobao'],
			);
			$result['servertime'] = time();
			echo json_encode($result);
			exit;
		}
    }	 
	if ($operation == 'users') { //getstatus获取学生信息
		if(!empty($ckmac)){		                  
			$users = pdo_fetchall("SELECT id,idcard, sid, bj_id, usertype,spic,tid,cardtype FROM " . tablename($this->table_idcard) . " WHERE weid = '{$weid}' And schoolid = {$school['id']} And is_on = 1 ORDER BY id DESC");
				if($users){
					$result['status'] = 0;
					$result['msg'] = "获取数据成功";
					$result['countpage'] = "1";	
					foreach($users as $key =>$row) {
						if($row['usertype'] == 1){
							$teacher = pdo_fetch("SELECT tname,thumb,sex  FROM " . tablename($this->table_teachers) . " WHERE id = '{$row['tid']}' ");
							$users[$key]['relationship'] = '';
							$users[$key]['fingercards'] = array();
							$users[$key]['car_cards'] =array();
							//$users[$key]['id']           = "02".$row['tid'];
							$users[$key]['sex']          = $teacher['sex'];
							$users[$key]['name']         = $teacher['tname'];	
							$users[$key]['s_type']       = 2;	//暂时全部告诉考勤机都是走读学
							$users[$key]['iccode']       = $row['idcard'];
							$users[$key]['cid']          = '';
							$users[$key]['type']         = 2;
							$users[$key]['picrul']       = empty($teacher['thumb']) ? tomedia($school['tpic']) : tomedia($teacher['thumb']);

						}elseif($row['usertype'] == 0){
							$student = pdo_fetch("SELECT s_name,icon,numberid,sex,s_type  FROM " . tablename($this->table_students) . " WHERE id = '{$row['sid']}' ");
							//修改开始
							$users[$key]['cid'] = $row['bj_id'];
							$users[$key]['name'] = $student['s_name'];	
							$users[$key]['s_type'] = $student['s_type'];	//暂时全部告诉考勤机都是走读学

							$relation = pdo_fetch("SELECT  pard,idcard  FROM " . tablename($this->table_idcard) . " WHERE idcard = '{$row['idcard']}' ");
							if($relation['pard']=='1'){
								$users[$key]['relationship'] = '';
							}elseif($relation['pard']=='2'){
								$users[$key]['relationship'] = '母亲';
							}elseif($relation['pard']=='3'){
								$users[$key]['relationship'] = '父亲';
							}elseif($relation['pard']=='4'){
								$users[$key]['relationship'] = '爷爷';
							}elseif($relation['pard']=='5'){
								$users[$key]['relationship'] = '奶奶';
							}elseif($relation['pard']=='6'){
								$users[$key]['relationship'] = '外公';
							}elseif($relation['pard']=='7'){
								$users[$key]['relationship'] = '外婆';
							}elseif($relation['pard']=='8'){
								$users[$key]['relationship'] = '叔叔';
							}elseif($relation['pard']=='9'){
								$users[$key]['relationship'] = '阿姨';
							}elseif($relation['pard']=='10'){
								$users[$key]['relationship'] = '其他';
							}
							$users[$key]['fingercards'] = array();
							$users[$key]['car_cards'] =array();
							//修改结束	
							//$users[$key]['id']      = $row['sid'];
							$users[$key]['sex']     = $student['sex'];
							$users[$key]['name']    = $student['s_name'];
							//暂时全部告诉考勤机都是走读学
							$users[$key]['iccode']  = $row['idcard'];
							$studentidcard = pdo_fetch("SELECT idcard  FROM " . tablename($this->table_idcard) . " WHERE sid = '{$row['sid']}' ");
							$users[$key]['cid']          = $row['bj_id'];
							$users[$key]['type']         = 1;
							if($row['spic']){
								$picrul = tomedia($row['spic']);
							}else{
								$picrul = empty($student['icon']) ? tomedia($school['spic']) : tomedia($student['icon']);//未设置头像，取默认头像
							}
							$users[$key]['picrul']       = $picrul;

						}elseif($row['cardtype'] == 2 && $row['usertype'] == 0){
                            //修改开始
                            $users[$key]['cid'] = $row['bj_id'];
                            $users[$key]['name'] = '班级卡';
                            $users[$key]['s_type'] = 1;	//暂时全部告诉考勤机都是走读学
                            $users[$key]['relationship'] = '';
                            $users[$key]['fingercards'] = array();
                            $users[$key]['car_cards'] =array();
                            //修改结束
                            //$users[$key]['id']      = $row['sid'];
                            $users[$key]['sex']     = 1;
                            $users[$key]['iccode']  = $row['idcard'];
                            $users[$key]['type']         = 1;
                            if($row['spic']){
                                $picrul = tomedia($row['spic']);
                            }else{
                                $picrul = empty($student['icon']) ? tomedia($school['spic']) : tomedia($student['icon']);//未设置头像，取默认头像
                            }
                            $users[$key]['picrul']       = $picrul;
                        }
						unset($users[$key]['usertype']);
                        unset($users[$key]['cardtype']);
                        unset($users[$key]['sid']);
                        unset($users[$key]['tid']);
                        unset($users[$key]['bj_id']);
                        unset($users[$key]['idcard']);
					}
					$result['data'] = $users;
					$result['servertime'] = time();
				}else{
					$result['status'] = -1;
					$result['msg']    = "没有有效考勤卡信息";
				}
			echo json_encode($result);
			exit;
		}
    }	
	if ($operation == 'class') {//获取班级信息
		if(!empty($ckmac)){
			$class = pdo_fetchall("SELECT sid as id, sname as classes, class_device, schoolid as sid, ssort as score, tid,datesetid FROM " . tablename($this->table_classify) . " WHERE weid = '{$weid}' And type = 'theclass' And schoolid = {$school['id']} ORDER BY ssort DESC");
			if($class){
				$nowdate = date("Y-n-j",time());
				$nowyear = date("Y",time());
				$nowweek = date("w",time()); //今天是星期几
				$result['status'] = 0;
				$result['msg'] = "获取班级数据成功";
				foreach($class as $key =>$row) {
					$todaytype = 0;
					$todaytimeset = array(
						array(
							'start'=>'00:00',
							'end'  =>'23:59'
						),
					); 
					if(!empty($row['datesetid'])){
						$checkdateset      =  pdo_fetch("SELECT * FROM " . tablename($this->table_checkdateset) . " WHERE weid = '{$weid}' And schoolid = {$school['id']} and  id = '{$row['datesetid']}'");
						$checkdateset_holi =  pdo_fetch("SELECT * FROM " . tablename($this->table_checkdatedetail) . " WHERE weid = '{$weid}' And schoolid = {$school['id']} and  checkdatesetid = '{$row['datesetid']}' and year = '{$nowyear}' ");
						
						$checktime         =  pdo_fetchall("SELECT * FROM " . tablename($this->table_checktimeset) . " WHERE weid = '{$weid}' And schoolid = {$school['id']} and  checkdatesetid = '{$row['datesetid']}' and date = '{$nowdate}' ORDER BY id ASC ");
						if(!empty($checktime)){
							if($checktime[0]['type'] == 6){
								//1放假2上课
								$todaytype = 1;
							}elseif($checktime[0]['type'] == 5){
								$todaytype    = 2;
								$todaytimeset = $checktime; 
							}
						}else{
							if(($nowdate >= $checkdateset_holi['win_start'] && $nowdate <=$checkdateset_holi['win_end']) || ($nowdate >= $checkdateset_holi['sum_start'] && $nowdate <=$checkdateset_holi['sum_end'])){
								$todaytype = 1;
							}else{
								$timeset_work = pdo_fetchall("SELECT start,end,s_type,out_in FROM " . tablename($this->table_checktimeset) . " WHERE weid = '{$weid}' And schoolid = {$school['id']} and  checkdatesetid = '{$row['datesetid']}' and type=1 ORDER BY id ASC ");
								//星期五
								if($nowweek == 5){
									$todaytype = 2;
									if($checkdateset['friday'] == 1){
										$timeset_fri = pdo_fetchall("SELECT start,end,s_type,out_in FROM " . tablename($this->table_checktimeset) . " WHERE weid = '{$weid}' And schoolid = {$school['id']} and  checkdatesetid = '{$row['datesetid']}' and type=2 ORDER BY id ASC ");
										$todaytimeset = $timeset_fri;
									}else{
										$todaytimeset = $timeset_work;
									}
								//星期六
								}elseif($nowweek == 6){
									if($checkdateset['saturday'] == 1){
										$timeset_sat = pdo_fetchall("SELECT start,end,s_type,out_in FROM " . tablename($this->table_checktimeset) . " WHERE weid = '{$weid}' And schoolid = {$school['id']} and  checkdatesetid = '{$row['datesetid']}' and type=3 ORDER BY id ASC ");
										$todaytype = 2;
										$todaytimeset = $timeset_sat;
									}else{
										$todaytype = 1;
									}
								
								//星期天
								}elseif($nowweek == 0){
									if($checkdateset['sunday'] == 1){
										$timeset_sun = pdo_fetchall("SELECT start,end,s_type,out_in FROM " . tablename($this->table_checktimeset) . " WHERE weid = '{$weid}' And schoolid = {$school['id']} and  checkdatesetid = '{$row['datesetid']}' and type=4 ORDER BY id ASC ");
										$todaytype    = 2;
										$todaytimeset = $timeset_sun;
									}else{
										$todaytype    = 1;
									}
								//工作日	
								}else{
									$todaytype    = 2;
									$todaytimeset = $timeset_work;
								}
							}
						}
						
					}
					if(!empty($ckmac['apid'])){
						$todaytype = 0;
						$todaytimeset = array(
							array(
								'start'=>'00:00',
								'end'  =>'23:59'
							),
						);
					}
                    $class[$key]['outDoorTime'] = array();
					$class[$key]['todaytype']    = $todaytype;
					$class[$key]['todaytimeset'] = $todaytimeset;
					unset($class[$key]['sid']);
                    unset($class[$key]['datesetid']);
                    unset($class[$key]['tid']);
				}			
				$result['data'] = $class;
				$result['servertime'] = time();
			}else{
				$result['status'] = -1;
				$result['msg'] = "本校未添加班级信息";				
			}
			echo json_encode($result);
		}
    }	
	if ($operation == 'login') { //获取学校信息
		$voice='';	
		if(!empty($ckmac)){			
			$result['status'] = 0;
			$result['msg'] = "获取数据成功";
			if($ckmac['banner']){
				$banner = unserialize($ckmac['banner']);
				if($banner['pic1']){
					$pictures = array(tomedia($banner['pic1']));
				}
				if($banner['pic1'] && $banner['pic2']){
					$pictures = array(tomedia($banner['pic1']),tomedia($banner['pic2']));
				}
				if($banner['pic1'] && $banner['pic2'] && $banner['pic3']){
					$pictures = array(tomedia($banner['pic1']),tomedia($banner['pic2']),tomedia($banner['pic3']));
				}
				if($banner['pic1'] && $banner['pic2'] && $banner['pic3'] && $banner['pic4']){
					$pictures = array(tomedia($banner['pic1']),tomedia($banner['pic2']),tomedia($banner['pic3']),tomedia($banner['pic4']));
				}
				if($banner['pic1'] && $banner['pic2'] && $banner['pic3'] && $banner['pic4']  && $banner['pic5']){		
					$pictures = array(tomedia($banner['pic1']),tomedia($banner['pic2']),tomedia($banner['pic3']),tomedia($banner['pic4']),tomedia($banner['pic5']));
				}
				if($banner['VOICEPRE2']){
					$voice = $banner['VOICEPRE2'];
				}				
				$result['data']['position3']['picture'] = $pictures;
				$temp = array(
					'isflow' => 2,
					'pop'  	 	=> $banner['pop'],
					'bgimg'  	=> $banner['bgimg'],
					'qrcode'	=> $banner['qrcode'],
					'video' 	=> $banner['video'],
					'pic1'  	=> $banner['pic1'],
					'pic2'  	=> $banner['pic2'],
					'pic3'   	=> $banner['pic3'],
					'pic4'  	=> $banner['pic4'],
					'pic5'   	=> $banner['pic5'],
					'welcome'      => $banner['welcome'],
					'password'      => $banner['password'],
					'starttime'      => $banner['starttime'],
					'shutdowntime'      => $banner['shutdowntime'],
					'voice'			=> $banner['voice'],
					'VOICEPRE'	=> $banner['VOICEPRE'],
					'VOICEPRE2'	=> $banner['VOICEPRE2']
				);
				$data['banner'] = serialize($temp);
				pdo_update($this->table_checkmac, $data, array('id' => $ckmac['id']));
			}			
			$result['data'] = array(
                'info'     => $banner['pop'],
                'da_start' => array("1", "2", "3", "4", "5", "6", "7"),
                'voice'    => $banner['voice'],
                'address'  => $school['address'],
                'name'     => $school['title'],
                'banner'   => $pictures,
                'video'    => $banner['video'],
                'logo'     => tomedia($school['logo']),
                'da_start' => ["1", "2", "3", "4", "5", "6", "7"],
				'stu1' => $ckmac['stu1'],
				'stu2' => $ckmac['stu2'],
				'stu3' => $ckmac['stu3']
			);
			$result['servertime'] = time();
			echo json_encode($result);
			exit;
		}
    }
	
	if ($operation == 'command') {
		if(!empty($ckmac)){	
			$order = pdo_fetch("SELECT * FROM " . tablename($this->table_online) . " WHERE :macid = macid And result = 2", array(':macid' => trim($ckmac['id'])));
			if($order){
				$result['status']=0;
				$result['msg']='请求成功';
				$result['data']['command']=$order['commond'];
				
			}else{
				$result['status']=0;
				$result['msg']='请求成功';
				$result['data']['command']=0;
				}
				$result['servertime']=time();
		}
		if($order){
			pdo_update($this->table_online, array('result'=>1), array('id' => $order['id']));
		}
		echo json_encode($result);

		exit;

    }
	
	if ($operation == 'start') {

	
    }
	if ($operation == 'gps') {			
		$result['status'] = 0;
		$result['msg']    = "定位上传成功";
		$result['data']   = array();
		echo json_encode($result);
		exit;
    }
	
	if ($operation == 'getdate') {					
		$result['status'] = "0";
		$result['msg']    = "获取数据成功";
		$result['data'] = array(
			'da_start' => array(1,2,3,4,5,6,7)	
		);
		echo json_encode($result);
		exit;
    }
	if ($operation == 'check') {
		$fstype        = false;
		$ckuser        = pdo_fetch("SELECT * FROM " . tablename($this->table_idcard) . " WHERE idcard = '{$_GPC['iccode']}' And weid = '{$weid}' And schoolid = '{$schoolid}' ");
		$bj            = pdo_fetch("SELECT bj_id FROM " . tablename($this->table_students) . " WHERE id = '{$ckuser['sid']}' ");
		$signTime = $_GPC['signtime'];
		$checkthisdata = pdo_fetch("SELECT id FROM " . tablename($this->table_checklog) . " WHERE cardid = '{$_GPC['iccode']}' And createtime = '{$signTime}' And schoolid = '{$schoolid}' ");
        if(empty($checkthisdata)){
			if(!empty($ckuser)){
				$times = time();
				if(!empty($_GPC['headerimg']) || !empty($_GPC['headerimg_second'])){
					load()->func('file');
					load()->func('communication');
					$path = "images/fm_jiaoyu/checkwn/". date('Y/m/d/');
					$rand = random(30);
					if(!empty($_GPC['headerimg'])){
						$picurl = $path.$rand."_1.jpg";
						$files_image = base64_decode($_GPC['headerimg']);
						file_write($picurl,$files_image);
						if (!empty($_W['setting']['remote']['type'])) {
							file_remote_upload($picurl);
						}
						$pic = $picurl;
					}
					if(!empty($_GPC['headerimg_second'])){
						$picurl2 = $path.$rand."_2.jpg";
						$files_image2 = base64_decode($_GPC['headerimg_second']);
						file_write($picurl2,$files_image2);
						if (!empty($_W['setting']['remote']['type'])) {
							file_remote_upload($picurl2);
						}					
						$pic2 = $picurl2;
					}
				}			
				$nowtime = date('H:i',$signTime);
				if($ckmac['type'] !=0){
					include 'checktime2.php';	
				}else{
					$signMode = $_GPC['m_type'];
					include 'checktime.php';	
				}

				if($ckuser['cardtype'] ==1) {


                    if (!empty($ckuser['sid'])) {
                        $roominfo = pdo_fetch("SELECT roomid FROM " . tablename($this->table_students) . " WHERE id = '{$ckuser['sid']}' ");
                        if ($school['is_cardpay'] == 1) {
                            if ($ckuser['severend'] > $times) {
                                if (!empty($ckmac['apid'])) {
                                    if (!empty($roominfo['roomid'])) {
                                        $this_roomid = $roominfo['roomid'];
                                        $this_apid   = $ckmac['apid'];
                                    } else {
                                        $this_roomid = 0;
                                        $this_apid   = 0;
                                    }
                                    if ($leixing == 1) {
                                        $ap_type = 1;
                                    } elseif ($leixing == 2) {
                                        $ap_type = 2;
                                    } else {
                                        $ap_type = 0;
                                    }
                                    $data = array(
                                        'weid'       => $weid,
                                        'schoolid'   => $schoolid,
                                        'macid'      => $ckmac['id'],
                                        'cardid'     => $_GPC['iccode'],
                                        'sid'        => $ckuser['sid'],
                                        'bj_id'      => $bj['bj_id'],
                                        'lon'        => $_GPC['lon'],
                                        'lat'        => $_GPC['lat'],
                                        'pic'        => $pic,
                                        'pic2'       => $pic2,
                                        'sc_ap'      => 1,
                                        'ap_type'    => $ap_type,
                                        'roomid'     => $this_roomid,
                                        'apid'       => $this_apid,
                                        'createtime' => $signTime
                                    );
                                } else {
                                    $data = array(
                                        'weid'        => $weid,
                                        'schoolid'    => $schoolid,
                                        'macid'       => $ckmac['id'],
                                        'cardid'      => $_GPC['iccode'],
                                        'sid'         => $ckuser['sid'],
                                        'bj_id'       => $bj['bj_id'],
                                        'lon'         => $_GPC['lon'],
                                        'lat'         => $_GPC['lat'],
                                        'type'        => $type,
                                        'pic'         => $pic,
                                        'pic2'        => $pic2,
                                        'temperature' => $_GPC['signTemp'],
                                        'leixing'     => $leixing,
                                        'pard'        => $ckuser['pard'],
                                        'createtime'  => $signTime
                                    );

                                }
                                pdo_insert($this->table_checklog, $data);
                                $checkid = pdo_insertid();
                                if ($school['send_overtime'] >= 1) {
                                    $overtime = $school['send_overtime'] * 60;
                                    $timecha  = $times - $signTime;
                                    if ($overtime >= $timecha) {
                                        if (is_showyl()) {
                                            $this->sendMobileJxlxtz_yl($schoolid, $weid, $ckuser['sid'], $checkid, $ckmac['id']);
                                        } else {
                                            $this->sendMobileJxlxtz($schoolid, $weid, $bj['bj_id'], $ckuser['sid'], $type, $leixing, $checkid, $ckuser['pard']);
                                        }

                                    }
                                } else {
                                    if (is_showyl()) {
                                        $this->sendMobileJxlxtz_yl($schoolid, $weid, $ckuser['sid'], $checkid, $ckmac['id']);
                                    } else {
                                        $this->sendMobileJxlxtz($schoolid, $weid, $bj['bj_id'], $ckuser['sid'], $type, $leixing, $checkid, $ckuser['pard']);
                                    }
                                }
                                $fstype = true;
                            } else {
                                $fstype        = false;
                                $result['msg'] = "刷卡失败,本卡已过有效期";
                            }
                        } else {

                            if (!empty($ckmac['apid'])) {
                                if (!empty($roominfo['roomid'])) {
                                    $this_roomid = $roominfo['roomid'];
                                    $this_apid   = $ckmac['apid'];
                                }
                                if ($leixing == 1) {
                                    $ap_type = 1;
                                } elseif ($leixing == 2) {
                                    $ap_type = 2;
                                } else {
                                    $ap_type = 0;
                                }
                                $data = array(
                                    'weid'       => $weid,
                                    'schoolid'   => $schoolid,
                                    'macid'      => $ckmac['id'],
                                    'cardid'     => $_GPC['iccode'],
                                    'sid'        => $ckuser['sid'],
                                    'bj_id'      => $bj['bj_id'],
                                    'lon'        => $_GPC['lon'],
                                    'lat'        => $_GPC['lat'],
                                    'pic'        => $pic,
                                    'pic2'       => $pic2,
                                    'sc_ap'      => 1,
                                    'ap_type'    => $ap_type,
                                    'roomid'     => $this_roomid,
                                    'apid'       => $this_apid,
                                    'createtime' => $signTime
                                );
                            } else {
                                $data = array(
                                    'weid'        => $weid,
                                    'schoolid'    => $schoolid,
                                    'macid'       => $ckmac['id'],
                                    'cardid'      => $_GPC['iccode'],
                                    'sid'         => $ckuser['sid'],
                                    'bj_id'       => $bj['bj_id'],
                                    'lon'         => $_GPC['lon'],
                                    'lat'         => $_GPC['lat'],
                                    'type'        => $type,
                                    'pic'         => $pic,
                                    'pic2'        => $pic2,
                                    'temperature' => $_GPC['signTemp'],
                                    'leixing'     => $leixing,
                                    'pard'        => $ckuser['pard'],
                                    'createtime'  => $signTime
                                );
                            }

                            pdo_insert($this->table_checklog, $data);
                            $checkid = pdo_insertid();
                            if ($school['send_overtime'] >= 1) {
                                $overtime = $school['send_overtime'] * 60;
                                $timecha  = $times - $signTime;
                                if ($overtime >= $timecha) {
                                    if (is_showyl()) {
                                        $this->sendMobileJxlxtz_yl($schoolid, $weid, $ckuser['sid'], $checkid, $ckmac['id']);
                                    } else {
                                        $this->sendMobileJxlxtz($schoolid, $weid, $bj['bj_id'], $ckuser['sid'], $type, $leixing, $checkid, $ckuser['pard']);
                                    }
                                }
                            } else {
                                if (is_showyl()) {
                                    $this->sendMobileJxlxtz_yl($schoolid, $weid, $ckuser['sid'], $checkid, $ckmac['id']);
                                } else {
                                    $this->sendMobileJxlxtz($schoolid, $weid, $bj['bj_id'], $ckuser['sid'], $type, $leixing, $checkid, $ckuser['pard']);
                                }
                            }
                            $fstype = true;
                        }


                    }
                    if (!empty($ckuser['tid'])) {
                        $data = array(
                            'weid'       => $weid,
                            'schoolid'   => $schoolid,
                            'macid'      => $ckmac['id'],
                            'cardid'     => $_GPC ['iccode'],
                            'tid'        => $ckuser['tid'],
                            'type'       => $type,
                            'leixing'    => $leixing,
                            'pic'        => $pic,
                            'pic2'       => $pic2,
                            'pard'       => 1,
                            'createtime' => $signTime
                        );
                        pdo_insert($this->table_checklog, $data);
                        $fstype = true;
                    }
                }elseif($ckuser['cardtype'] == 2){

                    //班级卡处理
                    $bj_id = $ckuser['bj_id'];
                    $ThisCardStudents = pdo_fetchall("SELECT id FROM " . tablename($this->table_students) . " WHERE bj_id = :bj_id and schoolid = :schoolid", array(':bj_id' =>$bj_id,':schoolid'=>$schoolid));
                    foreach ($ThisCardStudents as $key=>$value){
                        $data = array(
                            'weid' => $weid,
                            'schoolid' => $schoolid,
                            'macid' => $ckmac['id'],
                            'cardid' => $_GPC['iccode'],
                            'sid' => $value['id'],
                            'bj_id' => $bj_id,
                            'type' => $type,
                            'lon' => $_GPC['lon'],
                            'lat' => $_GPC['lat'],
                            'pic'        => $pic,
                            'pic2'       => $pic2,
                            'leixing' => $leixing,
                            'pard' => $ckuser['pard'],
                            'createtime' => $signTime
                        );
                        pdo_insert($this->table_checklog, $data);
                        $checkid = pdo_insertid();
                        if($school['send_overtime'] >= 1){
                            $overtime = $school['send_overtime']*60;
                            $timecha = $times - $signTime;
                            if($overtime >= $timecha){
                               $back =  $this->sendMobileJxlxtz($schoolid, $weid, $bj_id, $value['id'], $type, $leixing, $checkid, $ckuser['pard']);

                            }else{
                                $result['info'] = "延迟发送之数据将不推送刷卡提示";
                            }
                        }else{
                            $back = $this->sendMobileJxlxtz($schoolid, $weid,$bj_id,$value['id'], $type, $leixing, $checkid, $ckuser['pard']);

                        }
                    }
                    $fstype = true;
                }

			}else{
				$fstype = false;
				$result['msg'] = "未查询到本卡绑定情况";
			}
		}else{
			$fstype = false;
			$result['msg'] = "失败,本次刷卡为重复提交不写入记录";
		}
		if ($fstype == true){
			$result['status'] = 0;
			$result['msg']    = "刷卡成功";
			echo json_encode($result);
			exit;
        }else{
			$result['status'] = -1;
			//$result['msg'] = "失败";
			echo json_encode($result);
			exit;
		}	
	}
	 
	if ($operation == 'getleave') {	
		$time = $_GPC['signtime'];
		$ckuser        = pdo_fetch("SELECT sid FROM " . tablename($this->table_idcard) . " WHERE idcard = '{$_GPC['iccode']}' And weid = '{$weid}' And schoolid = '{$schoolid}' ");
		$leave        =  pdo_fetch("SELECT sid,startime1,endtime1 FROM " . tablename($this->table_leave) . " WHERE weid = '{$weid}'  And schoolid = '{$schoolid}' and isliuyan = 0 and status = 1 and startime1 <= '{$time}' and endtime1 >= '{$time}' and sid = '{$ckuser['sid']}' ");
		$result['status'] = "0";
		if(!empty($leave)){
			$result['data']['openDoor']   = 0;	
			$result['msg']    = "获取数据成功";
		}else{
			$result['data']['openDoor']   = 1;
			$result['msg']    = "当前时间禁止外出";
		}
		
		echo json_encode($result);
		exit;
    }
	if ($operation == 'getdevremote') {	
		$time = $_GPC['signtime'];
		$pid=$ckmac['id'];
		$list = pdo_fetchall("SELECT deviceId,passType,passDeviceId,cameras FROM " . tablename('wx_school_checkmac_remote') . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' and pid='{$pid}' ORDER BY id DESC");
		foreach($list as $key => $row){
			
			$list[$key]['cameras'] = unserialize($row['cameras']);
			
		}
		
		if(!empty($list)){
			$result['status'] = "0";
			$result['msg']    = "获取数据成功";
			$result['data']   = $list;	
			
		}else{
			$result['status'] = "1";
			$result['msg']    = "空数据";
		}
		
		echo json_encode($result);
		exit;
    }

if ($operation == 'checkap') {
		$fstype        = false;
		$ckuser        = pdo_fetch("SELECT sid,pard,tid,severend FROM " . tablename($this->table_idcard) . " WHERE idcard = '{$_GPC['iccode']}' And weid = '{$weid}' And schoolid = '{$schoolid}' ");
		$bj            = pdo_fetch("SELECT bj_id FROM " . tablename($this->table_students) . " WHERE id = '{$ckuser['sid']}' ");
		$signTime = $_GPC['signtime'];
		if(!empty($ckuser)){
			$times = time();
			$nowtime = date('H:i',$signTime);
			if($ckmac['type'] !=0){
				include 'checktime2.php';	
			}else{
				$signMode = $_GPC['m_type'];
				include 'checktime.php';	
			}
			if(!empty($ckuser['sid'])){
				if(!empty($ckmac['apid'])){
					$nowdate = date("Y-n-j",$signTime);
					$nowweek = date("w",$signTime);
					$student = pdo_fetch("SELECT bj_id,roomid FROM " . tablename($this->table_students) . " WHERE  schoolid = '{$schoolid}' and id = '{$ckuser['sid']}' ");
					$stu_class = pdo_fetch("SELECT datesetid FROM " . tablename($this->table_classify) . " WHERE  schoolid = '{$schoolid}' and sid = '{$student['bj_id']}' ");
					$checktime  =  pdo_fetch("SELECT * FROM " . tablename($this->table_checktimeset) . " WHERE weid = '{$weid}' And schoolid = {$school['id']} and  checkdatesetid = '{$stu_class['datesetid']}' and date = '{$nowdate}' ");
					if(!empty($checktime)){
						if($checktime['type'] == 6){
							$todaytype = 1;//放假
						}elseif($checktime['type'] == 5){
							$todaytype = 2;//上课
						}
					}else{
						if($nowweek != 6 && $nowweek != 0){
							$todaytype = 2;//上课
						}else{
							$todaytype = 1;//放假
						}
					}
					if(!empty($student['roomid'])){
						$roominfo =  pdo_fetch("SELECT * FROM " . tablename($this->table_checktimeset) . " WHERE weid = '{$weid}' And schoolid = {$school['id']} and  id = '{$student['roomid']}' ");

						if($roominfo['noon_start'] != $roominfo['noon_end']){
							$noon_start = $roominfo['noon_start'];
							$noon_end = $roominfo['noon_end'];
						}else{
							$noon_start ='00:00';
							$noon_end = '23:59';
						}
						if($roominfo['night_start'] != $roominfo['night_end']){
							$night_start = $roominfo['night_start'];
							$night_end = $roominfo['night_end'];
						}else{
							$night_start ='00:00';
							$night_end = '23:59';
						}
					}else{
						$noon_start  = '00:00';
						$noon_end 	 = '23:59';
						$night_start = '00:00';
						$night_end 	 = '23:59';
					}
					
					//放假期间，不经过时段判断
					if($todaytype == 1){
						$isCanCheck = 1 ; //1能开门2不能开门
					//上课期间，经过时段判断						
					}elseif($todaytype == 2){
						if(($nowtime >= $noon_start && $nowtime <= $noon_end) || ($nowtime >= $night_start && $nowtime <= $night_end)){
							$isCanCheck = 1 ;
						}else{
							$isCanCheck = 0 ;
						}
					}
					if($roominfo['apid'] != $ckmac['apid']){
						$isCanCheck = 0 ;
					}
				}else{
					$isCanCheck = 1 ;
				}
				if($isCanCheck == 1){
					$fstype = true;
				}elseif($isCanCheck == 0){
					$fstype = false;
					$result['msg'] = "本时段禁止放行";
				}
			}elseif(!empty($ckuser['tid'])){
				$fstype = true;
			}	
		}else{
			$fstype = false;
			$result['msg'] = "未查询到本卡绑定情况";
		}
		if ($fstype == true){
			$result['status'] = 0;
			$result['msg']    = "允许开门";
			echo json_encode($result);
			exit;
        }else{
			$result['status'] = -1;
			//$result['msg'] = "失败";
			echo json_encode($result);
			exit;
		}	
	}

if ($operation == 'getroomlist'){
    $data = array();
    $ii = 0;
    $allclasstimeset = GetDatesetWithBj($school['id'],$weid);
    $allroomtimeset = GetDatesetWithRoom($school['id'],$weid,$ckmac['apid']);
    $roomlist = pdo_fetchall("SELECT id FROM " . tablename($this->table_aproom) . " WHERE apid = '{$ckmac['apid']}' and schoolid = '{$school['id']}' and weid = '{$weid}' ORDER BY id DESC");
    $room_str = '';
    foreach($roomlist as $key_r=>$value_r){
        $room_str .=$value_r['id'].',';
    }
    $room_str = trim($room_str,',');
    $condition = " and FIND_IN_SET(roomid,'{$room_str}') ";
    $studentlist =pdo_fetchall("SELECT id , bj_id,s_name  as name ,roomid FROM " . tablename($this->table_students) . " WHERE weid = '{$weid}' And schoolid = {$school['id']}  $condition ORDER BY id DESC");
    foreach($studentlist as $key =>$row) {
        $this_todaytype = $allclasstimeset[$row['bj_id']]['timeset']['todaytype'];
        if($this_todaytype == 1){
            $studentlist[$key]['timeset'] = array(array('start'=>'00:00','end'=>'23:59'));
        }else{
            $studentlist[$key]['timeset'] = $allroomtimeset[$row['roomid']]['time'];
        }
        $card = pdo_fetchall("SELECT idcard  FROM " . tablename($this->table_idcard) . " WHERE sid = '{$row['id']}' ORDER BY id DESC");
        $studentlist[$key]['rfid'] = $card;
        if(!empty($card)){
            foreach ($card as $key_c =>$value_c){
                $data[$ii] = $row;
                $data[$ii]['idcard'] = $value_c['idcard'];
                if($this_todaytype == 1){
                    $data[$ii]['timeset'] = array(array('start'=>'00:00','end'=>'23:59'));
                }else{
                    $data[$ii]['timeset'] = $allroomtimeset[$row['roomid']]['time'];
                }
                $ii++;
            }
        }
    }
    $teacherlist = pdo_fetchall("SELECT id ,tname as name FROM " . tablename($this->table_teachers) . " WHERE weid = '{$weid}' And schoolid = {$schoolid}  ORDER BY id DESC");
    foreach( $teacherlist as $key=>$row){
        $card = pdo_fetchall("SELECT idcard  FROM " . tablename($this->table_idcard) . " WHERE tid = '{$row['id']}' ORDER BY id DESC");
        if(!empty($card)){
            foreach ($card as $key_c =>$value_c){
                $data[$ii] = $row;
                $data[$ii]['idcard'] = $value_c['idcard'];
                $data[$ii]['bj_id'] = 0;
                $data[$ii]['roomid'] = 0;
                $data[$ii]['timeset'] = array(array('start'=>'00:00','end'=>'23:59'));
                $ii++;
            }
        }
    }

    $result['status'] = 0;
    $result['msg']    = "获取数据成功";
    $result['data']   = $data;
    echo json_encode($result);
    exit;
}



?>
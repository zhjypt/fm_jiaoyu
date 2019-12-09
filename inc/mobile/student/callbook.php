<?php
/**
 * 微教育模块  
 *			
 * @author 高贵血迹
 */
        global $_W, $_GPC;
        $weid = $_W['uniacid'];
		$schoolid = intval($_GPC['schoolid']);
		$schooltype = $_W['schooltype'];
		$openid = $_W['openid'];
		$userss = !empty($_GPC['userid']) ? intval($_GPC['userid']) : 1;
		$obid = 2;
		$act = "tx";
		$schooltype = $_W['schooltype'];
        //查询是否用户登录
		if(!$_SESSION['user']){
			mload()->model('user');
			$_SESSION['user'] = check_userlogin($weid,$schoolid,$openid,$userss);
			if ($_SESSION['user'] ==2){
				include $this->template('bangding');
			}	
		}		
		$it = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where id = :id And openid = :openid ", array(':id' => $_SESSION['user'],':openid' => $openid));
		$school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " where weid = :weid AND id=:id ", array(':weid' => $weid, ':id' => $schoolid));
		$student = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where weid = :weid AND id=:id AND schoolid=:schoolid ", array(':weid' => $weid, ':id' => $it['sid'], ':schoolid' => $schoolid));
		$this->checkobjiect($schoolid, $student['id'], $obid);
        if(!empty($it)){
	        //校长
            $master = pdo_fetchall("SELECT tname,thumb,mobile,id,status,userid,fz_id FROM " . tablename($this->table_teachers) . " WHERE weid = :weid AND schoolid = :schoolid AND status = :status ORDER BY sort DESC", array(
				':weid' => $weid,
				':schoolid' => $schoolid,
				':status' => 2,
			));
			foreach($master as $key => $row){
				if($row['userid']){
					$masteruser = pdo_fetch("SELECT is_allowmsg FROM " . tablename($this->table_user) . " WHERE id = :id ", array(':id' => $row['userid']));
					$master[$key]['is_allowmsg'] = $masteruser['is_allowmsg'];
					
				}
				$master[$key]['Ttitle'] = GetTeacherTitle($row['status'],$row['fz_id']);
			}
            $masterCount = count($master);
            //年级管理
			if($schooltype){
				 $mykclist = pdo_fetchall("SELECT  kcid FROM " . tablename($this->table_order) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND type = 1  and status = 2 and sid = '{$student['id']}'  group by kcid "); 
				 $kclist_str = '';
				foreach($mykclist as $key_k=>$value_k){
					$kclist_str .=$value_k['kcid'].',';	
				}
				$kclist_str = trim($kclist_str,',');
				$tidlist_str = trim($tidlist_str,',');
				//年级管理
				$master1 = pdo_fetchall("SELECT  classify.sid,classify.sname,classify.tid  FROM " . tablename($this->table_tcourse) . " as course,  " . tablename($this->table_classify) . " as classify  WHERE course.weid = '{$weid}' AND course.schoolid = '{$schoolid}' and FIND_IN_SET(course.id,'{$kclist_str}') and course.xq_id = classify.sid group by course.xq_id  ");
				$tidss = array();
				$masterCount1 = 0;
				foreach($master1 as $key => $row){
					$techer = pdo_fetch("SELECT tname,thumb,mobile,id,status,userid,fz_id FROM " . tablename($this->table_teachers) . " WHERE id =  :id ", array(':id' => $row['tid']));
					if($techer){
						if(!in_array($row['tid'],$tidss)){
							$tidss = array($row['tid']);
							$masterCount1++;
						}
						$master1[$key]['tname'] = $techer['tname'];
						$master1[$key]['thumb'] = $techer['thumb'];
						$master1[$key]['mobile'] = $techer['mobile'];
						$master1[$key]['id'] = $techer['id'];
						$master1[$key]['status'] = $techer['status'];
						$master1[$key]['userid'] = $techer['userid'];
						$master1[$key]['fz_id'] = $techer['fz_id'];
						$master1[$key]['Ttitle'] =GetTeacherTitle($techer['status'],$techer['fz_id']);
					}
				}
				$master2 = pdo_fetchall("select  teachers.id as id , teachers.tname, teachers.userid, teachers.mobile, teachers.thumb, teachers.status, teachers.fz_id,course.id as kcid  FROM ".tablename($this->table_tcourse)." as course,  " . tablename($this->table_teachers) . " as teachers WHERE course.weid = '{$weid}' AND course.schoolid = '{$schoolid}' and FIND_IN_SET(course.id,'{$kclist_str}')   and (course.tid like concat('%', teachers.id, '%')  or course.tid like concat('%', teachers.id)   or course.tid like concat(teachers.id, '%')   or course.tid =teachers.id)  ORDER BY CONVERT(teachers.tname USING gbk) ASC "); 
				foreach($master2 as $key => $row){
					if($row['userid']){
						$masteruser = pdo_fetch("SELECT is_allowmsg FROM " . tablename($this->table_user) . " WHERE id = :id ", array(':id' => $row['userid']));
						$master2[$key]['is_allowmsg'] = $masteruser['is_allowmsg'];
					}
					$master2[$key]['kmname']= pdo_fetch("SELECT name FROM " .tablename($this->table_tcourse)."WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND id = '{$row['kcid']}' ")['name'];
					
					
				};
				$masterCount2 = count($master2);  


				
				//获取家长
				$stupardlist = $mykclist;
				foreach($stupardlist as $key_mk=>$value_mk){
					$bj = pdo_fetch("SELECT name as sname FROM " . tablename($this->table_tcourse) . " WHERE  id = '{$value_mk['kcid']}' ");
					$xs1 = pdo_fetchall("SELECT students.id,students.s_name,students.icon FROM " . tablename($this->table_students) . " as students, " . tablename($this->table_order) . " as orders  where orders.schoolid = '{$schoolid}' And orders.weid = '{$weid}' And orders.kcid = '{$value_mk['kcid']}' and orders.type = 1 and orders.status = 2 and orders.sid = students.id group by orders.sid ORDER BY CONVERT(students.s_name USING gbk) ASC ");
					$bj1count = 0;
					foreach($xs1 as $k => $r){
						$xs1[$k]['sid'] = pdo_fetchall("SELECT userinfo,pard,id,uid,is_allowmsg,sid FROM " . tablename($this->table_user) . " WHERE weid = :weid AND schoolid = :schoolid AND sid = :sid ", array(
							':weid' => $weid,
							':schoolid' => $schoolid,
							':sid' => $r['id']
						));
						foreach($xs1[$k]['sid'] as $key =>$row){
							$member = pdo_fetch("SELECT avatar FROM " . tablename ( 'mc_members' ) . " where uniacid = :uniacid And uid = :uid ", array(':uniacid' => $weid, ':uid' => $row['uid']));
							$xs1[$k]['sid'][$key]['avatar'] = $member['avatar'];
							if ($row['userinfo']){
								$userinfo = iunserializer($row['userinfo']);
								$xs1[$k]['sid'][$key]['name'] = $userinfo['name'];
								$xs1[$k]['sid'][$key]['mobile'] = $userinfo['mobile'];
							}
						$bj1count ++;
						}	
					}
					$stupardlist[$key_mk]['xs1'] = $xs1;
					$stupardlist[$key_mk]['bj1count'] = $bj1count;
					$stupardlist[$key_mk]['bj'] = $bj;
					
				}
				
				
				if(!empty($student['bj_id'])){
					$bj = pdo_fetch("SELECT sid,sname FROM " . tablename($this->table_classify) . " where schoolid = '{$schoolid}' And weid = '{$weid}' And sid = '{$student['bj_id']}' ");	
					$xs1 = pdo_fetchall("SELECT id,s_name,icon FROM " . tablename($this->table_students) . " WHERE weid = :weid AND schoolid = :schoolid AND bj_id = :bj_id ", array(
						':weid' => $weid,
						':schoolid' => $schoolid,
						':bj_id' => $student['bj_id']
					));
					$bj1count = 0;
					foreach($xs1 as $k => $r){
						$xs1[$k]['sid'] = pdo_fetchall("SELECT userinfo,pard,id,uid,is_allowmsg,sid FROM " . tablename($this->table_user) . " WHERE weid = :weid AND schoolid = :schoolid AND sid = :sid ", array(
							':weid' => $weid,
							':schoolid' => $schoolid,
							':sid' => $r['id']
						));
						foreach($xs1[$k]['sid'] as $key =>$row){
							$member = pdo_fetch("SELECT avatar FROM " . tablename ( 'mc_members' ) . " where uniacid = :uniacid And uid = :uid ", array(':uniacid' => $weid, ':uid' => $row['uid']));
							$xs1[$k]['sid'][$key]['avatar'] = $member['avatar'];
							if ($row['userinfo']){
								$userinfo = iunserializer($row['userinfo']);
								$xs1[$k]['sid'][$key]['name'] = $userinfo['name'];
								$xs1[$k]['sid'][$key]['mobile'] = $userinfo['mobile'];
							}
						$bj1count ++;
						}	
					}
				}
				
			}else{
				$master1 = pdo_fetchall("SELECT sid,sname,tid FROM " . tablename($this->table_classify) . " WHERE weid = :weid AND schoolid = :schoolid AND type = :type ORDER BY sid DESC", array(
					':weid' => $weid,
					':schoolid' => $schoolid,
					':type' => 'semester',
				));
				$tidss = array();
				$masterCount1 = 0;
				foreach($master1 as $key => $row){
					$techer = pdo_fetch("SELECT tname,thumb,mobile,id,status,userid,fz_id FROM " . tablename($this->table_teachers) . " WHERE id =  :id ", array(':id' => $row['tid']));
					if($techer){
						if(!in_array($row['tid'],$tidss)){
							$tidss = array($row['tid']);
							$masterCount1++;
						}
						$master1[$key]['tname'] = $techer['tname'];
						$master1[$key]['thumb'] = $techer['thumb'];
						$master1[$key]['mobile'] = $techer['mobile'];
						$master1[$key]['id'] = $techer['id'];
						$master1[$key]['status'] = $techer['status'];
						$master1[$key]['userid'] = $techer['userid'];
						$master1[$key]['fz_id'] = $techer['fz_id'];
						$master1[$key]['Ttitle'] =GetTeacherTitle($techer['status'],$techer['fz_id']);
					}
				}
			  

				// 新版的
					//这里查的是新表
					//从class表里匹配学生班级
				$master2temp = pdo_fetchall("SELECT DISTINCT max(tid),max(bj_id),max(km_id) FROM " . tablename($this->table_class) . " WHERE weid = :weid AND schoolid = :schoolid  AND bj_id =:bj_id group BY tid ORDER BY id DESC", array(
					':weid' => $weid,
					':schoolid' => $schoolid,
					':bj_id' => $student['bj_id'],
				));
					
				$master2 = array();
				foreach($master2temp as $key => $row){
					$master2[$key] = pdo_fetch("SELECT id,tname,userid,mobile,thumb,status,fz_id FROM " .tablename($this->table_teachers)."WHERE weid = :weid AND schoolid = :schoolid AND id =:tid ", array(
						':weid'     => $weid,
						':schoolid' => $schoolid,
						':tid'    => $row['max(tid)'],
						));				
				};
				foreach($master2 as $key => $row){
						if($row['userid']){
							$masteruser = pdo_fetch("SELECT is_allowmsg FROM " . tablename($this->table_user) . " WHERE id = :id ", array(':id' => $row['userid']));
							$master2[$key]['is_allowmsg'] = $masteruser['is_allowmsg'];
						}
						$master2[$key]['kemu']= pdo_fetchall("SELECT km_id FROM " .tablename($this->table_class)."WHERE weid = :weid AND schoolid = :schoolid AND tid =:tid AND bj_id=:bj_id ",array(
						':weid'     => $weid,
						':schoolid' => $schoolid,
						':tid'    => $row['id'],
						'bj_id'   => $student['bj_id'],
						));
						foreach($master2[$key]['kemu'] as $k => $r){
							$kemunam = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = :sid", array(':sid' => $r['km_id']));
							$master2[$key]['kemu'][$k]['kemus'] = $kemunam['sname'];
						};	
						$master2[$key]['Ttitle'] = GetTeacherTitle($row['status'],$row['fz_id']);
				};
				$masterCount2 = count($master2);   
				if(!empty($student['bj_id'])){
					$bj = pdo_fetch("SELECT sid,sname FROM " . tablename($this->table_classify) . " where schoolid = '{$schoolid}' And weid = '{$weid}' And sid = '{$student['bj_id']}' ");	
					$xs1 = pdo_fetchall("SELECT id,s_name,icon FROM " . tablename($this->table_students) . " WHERE weid = :weid AND schoolid = :schoolid AND bj_id = :bj_id ", array(
						':weid' => $weid,
						':schoolid' => $schoolid,
						':bj_id' => $student['bj_id']
					));
					$bj1count = 0;
					foreach($xs1 as $k => $r){
						$xs1[$k]['sid'] = pdo_fetchall("SELECT userinfo,pard,id,uid,is_allowmsg,sid FROM " . tablename($this->table_user) . " WHERE weid = :weid AND schoolid = :schoolid AND sid = :sid ", array(
							':weid' => $weid,
							':schoolid' => $schoolid,
							':sid' => $r['id']
						));
						foreach($xs1[$k]['sid'] as $key =>$row){
							$member = pdo_fetch("SELECT avatar FROM " . tablename ( 'mc_members' ) . " where uniacid = :uniacid And uid = :uid ", array(':uniacid' => $weid, ':uid' => $row['uid']));
							$xs1[$k]['sid'][$key]['avatar'] = $member['avatar'];
							if ($row['userinfo']){
								$userinfo = iunserializer($row['userinfo']);
								$xs1[$k]['sid'][$key]['name'] = $userinfo['name'];
								$xs1[$k]['sid'][$key]['mobile'] = $userinfo['mobile'];
							}
						$bj1count ++;
						}	
					}
				}	
				
			}
								
			include $this->template(''.$school['style2'].'/callbook');
        }else{
			session_destroy();
		    $stopurl = $_W['siteroot'] .'app/'.$this->createMobileUrl('bangding', array('schoolid' => $schoolid));
			header("location:$stopurl");
			exit;
        }        
?>
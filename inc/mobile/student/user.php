<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */

		$weid = $_W['uniacid'];
		$openid = $_W['openid'];	
		$schoolid = $_GPC['schoolid'];
		$userss = intval($_GPC['userid']);
		$act = "wd";
        //查询是否用户登录
		if(empty($schoolid)){
			$itess = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where weid = '{$weid}' And openid = '{$openid}' And tid = 0 ");
			if(!empty($itess)){
				$userss = $itess['id'];
			}
		}
		if(!empty($userss)){
			$ite = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where  id = :id ", array(':id' => $userss));
			if(!empty($ite)){
				$_SESSION['user'] = $ite['id'];
				$schoolid = $ite['schoolid'];
			}else{
				session_destroy();
				$stopurl = $_W['siteroot'] .'app/'.$this->createMobileUrl('myschool', array('schoolid' => $schoolid));
				header("location:$stopurl");
				exit;
			}			
		}else{
			if(empty($_SESSION['user'])){
				$userid = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where :schoolid = schoolid And :weid = weid And :openid = openid And :tid = tid LIMIT 0,1 ", array(':weid' => $weid, ':schoolid' => $schoolid, ':openid' => $openid, ':tid' => 0), 'id');
				if(!empty($userid)){
					$_SESSION['user'] = $userid['id'];
					$schoolid = $userid['schoolid'];
				}
			}
		}
		$user =  get_myallclass($weid,$openid);
		$it = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where :schoolid = schoolid And openid = :openid AND id=:id ", array(':schoolid' => $schoolid,':openid' => $openid, ':id' => $_SESSION['user']));
		$pard = get_guanxi($it['pard']);
		if(!$pard){
			$pard = '本人';
		}
		if(!empty($schoolid)){
			if($schoolid != $it['schoolid']){
				session_destroy();
				$stopurl = $_W['siteroot'] .'app/'.$this->createMobileUrl('myschool', array('schoolid' => $schoolid));
				header("location:$stopurl");
				exit;
			}
		}
		$school = pdo_fetch("SELECT title,spic,is_rest,shoucename,is_video,videoname,is_zjh,is_recordmac,style2,userstyle,gonggao,copyright,Is_point,is_chongzhi,is_shoufei FROM " . tablename($this->table_index) . " where weid = :weid AND id=:id ", array(':weid' => $weid, ':id' => $schoolid));
		$guid = need_guid($it['id'],$schoolid,1);
		if(!empty($guid)){
			pdo_update($this->table_user, array('is_frist' => 2), array('id' => $it['id']));	
			$stopurl = $_W['siteroot'] . 'app/index.php?i=' . $weid . '&c=entry&do=guid&m=fm_jiaoyu'.'&schoolid='.$schoolid.'&guid='.$guid.'&place=user';;
			header("location:$stopurl");
			exit;
		}
        if($user != false){
			$students = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where weid = :weid AND id=:id AND schoolid=:schoolid ", array(':weid' => $weid, ':id' => $it['sid'], ':schoolid' => $schoolid));	
			$now_time = time();
			$student_buzhu = pdo_fetch("SELECT * FROM " . tablename($this->table_buzhulog) . " where weid ='{$weid}' AND sid = '{$it['sid']}' and starttime <= '{$nowtime}' and endtime >= '{$nowtime}' ");		
			$all_yue =$students['chongzhi'] +  $student_buzhu['now_yue'];

			
			$rest = check_unpay($it['sid']);
			$resttz = pdo_fetchcolumn("SELECT count(*) FROM ".tablename($this->table_record)." WHERE sid = '{$it['sid']}' And (type = 1 Or type = 3) And readtime < 1 And userid = '{$it['id']}' ");
			$restzy = pdo_fetchcolumn("SELECT count(*) FROM ".tablename($this->table_record)." WHERE sid = '{$it['sid']}' And type = 2 And readtime = '0' And userid = '{$it['id']}' ");
			$restly = pdo_fetchcolumn("SELECT count(*) FROM ".tablename($this->table_leave)." WHERE touserid = '{$it['id']}' And isliuyan = 2 And isread = 1 ");
			$mybanji = pdo_fetch("SELECT sname,qun FROM " . tablename($this->table_classify) . " WHERE :schoolid = schoolid And :sid = sid ", array(':schoolid' => $schoolid, ':sid' => $students['bj_id']));			
			$icons1 = pdo_fetchall("SELECT * FROM " . tablename($this->table_icon) . " where weid = :weid And schoolid = :schoolid And place = :place And status = :status ORDER by id ASC", array(':weid' => $weid, ':schoolid' => $schoolid,':place' => 3,':status' =>1 ));
			 $stutop = pdo_fetch("SELECT * FROM " . tablename($this->table_icon) . " where weid = '{$weid}' And schoolid = '{$schoolid}' And place = 12 ");
			foreach($icons1 as $key => $row){
				$icons1[$key]['ismassges'] = false;
				if(strpos($row['url'],'szuoyelist')){
					$icons1[$key]['ismassges'] = true;
					$icons1[$key]['shengyu'] = $restzy;
				}
				if(strpos($row['url'],'snoticelist')){
					$icons1[$key]['ismassges'] = true;
					$icons1[$key]['shengyu'] = $resttz;
				}
				if(strpos($row['url'],'slylist')){
					$icons1[$key]['ismassges'] = true;
					$icons1[$key]['shengyu'] = $restly;
				}				
			}

			$icons2 = pdo_fetchall("SELECT * FROM " . tablename($this->table_icon) . " where weid = :weid And schoolid = :schoolid And place = :place And status = :status ORDER by id ASC", array(':weid' => $weid, ':schoolid' => $schoolid,':place' =>4 , ':status'=>1 ));
			$icons3 = pdo_fetchall("SELECT * FROM " . tablename($this->table_icon) . " where weid = :weid And schoolid = :schoolid And place = :place And status = :status ORDER by id ASC", array(':weid' => $weid, ':schoolid' => $schoolid , ':place' =>5 , ':status'=>1 ));
			$item = pdo_fetch("SELECT nickname,realname FROM " . tablename ( 'mc_members' ) . " where uniacid = :uniacid AND uid=:uid ", array(':uid' => $it['uid'], ':uniacid' => $weid)); 

		    $userinfo = iunserializer($it['userinfo']);
		    $this->checkpay($schoolid, $students['id'], $it['id'], $it['uid']);
			if($schoolid){
				include $this->template(''.$school['style2'].'/'.$school['userstyle'].'');
			}else{
				include $this->template('students/user');
			}

        }else{
			session_destroy();
		    $stopurl = $_W['siteroot'] .'app/'.$this->createMobileUrl('myschool', array('schoolid' => $schoolid));
			header("location:$stopurl");
			exit;
        }

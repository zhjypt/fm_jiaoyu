<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */       
        global $_W, $_GPC;
        $weid = $_W['uniacid'];
		$schoolid = intval($_GPC['schoolid']);
		$openid = $_W['openid'];
		$kcid = $_GPC['kcid'];
		$sid = $_GPC['sid'];

        //查询是否用户登录
		mload()->model('user');
		$it = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where :schoolid = schoolid And openid = :openid AND sid=:sid ", array(':schoolid' => $schoolid,':openid' => $openid, ':sid' => $sid));
		$userid = $it['id'];
		$_SESSION['user'] = $userid;
		$kcinfo =  pdo_fetch("SELECT * FROM " . tablename($this->table_tcourse) . " where id = :id And schoolid = :schoolid ", array(':id' => $kcid,':schoolid' => $schoolid));
		//var_dump($kcinfo);
		 $category = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " WHERE weid = :weid AND schoolid = :schoolid ", array(':weid' => $weid, ':schoolid' => $schoolid), 'sid');
		
		$pard = get_guanxi($it['pard']);
		if(!$pard){
			$pard = '本人';
		}
		$sss = $category[51]['sname'];
		
		$school = pdo_fetch("SELECT style2,title,spic,tpic,title,headcolor,thumb FROM " . tablename($this->table_index) . " where weid = :weid AND id=:id", array(':weid' => $weid, ':id' => $schoolid));
		$student = pdo_fetch("SELECT s_name,icon FROM " . tablename($this->table_students) . " where id = :id", array(':id' => $it['sid']));
	 	
		//var_dump($tname_array);
		
		

	
        if(!empty($it)){
			$t_array = explode(',',$kcinfo['tid']);
			$tname_array = array();
			$check = pdo_fetch("SELECT content FROM " . tablename($this->table_kcpingjia) . " WHERE schoolid = '{$schoolid}' And weid = '{$weid}' and sid ='{$sid}' And kcid = '{$kcid}' and type=2 ");
			foreach( $t_array as $key_t => $value_t )
			{
				$teacher_all =  pdo_fetch("SELECT id,tname,thumb FROM " . tablename($this->table_teachers) . " WHERE schoolid = :schoolid And id = :id", array(':schoolid' => $schoolid,':id' => $value_t));	
				$tname_array[$value_t]=$teacher_all;
				if(!empty($check)){
					$check_t = pdo_fetchcolumn("SELECT star FROM " . tablename($this->table_kcpingjia) . " WHERE schoolid = '{$schoolid}' And weid = '{$weid}' and sid ='{$sid}' And kcid = '{$kcid}' And tid =$value_t ");
					$tname_array[$value_t]['star'] = $check_t;
				}
			}
		
			include $this->template(''.$school['style2'].'/kcpingjia');
        }else{
			session_destroy();
		    $stopurl = $_W['siteroot'] .'app/'.$this->createMobileUrl('bangding', array('schoolid' => $schoolid));
			header("location:$stopurl");
			exit;
        }        
?>
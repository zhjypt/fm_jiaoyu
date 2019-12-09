<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
        global $_W, $_GPC;
        $weid = $_W ['uniacid']; 
		$openid = $_W['openid'];
        $id = intval($_GPC['id']);
		$schoolid = intval($_GPC['schoolid']);
		$schooltype = $_W['schooltype'];
		//获取基本信息
		if(empty($_GPC['userid'])){
			$it = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where id = :id ", array(':id' => $_SESSION['user']));
		}elseif(!empty($_GPC['userid'])){
			$it = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where id = :id ", array(':id' => $_GPC['userid']));
			$_SESSION['user'] = $_GPC['userid'];
		}
		$category = pdo_fetchall("SELECT sid,sname FROM " . tablename($this->table_classify) . " WHERE weid = :weid AND schoolid = :schoolid ORDER BY sid ASC, ssort DESC", array(':weid' => $weid, ':schoolid' => $schoolid), 'sid');
		//var_dump($category);
		$student = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where weid = :weid AND schoolid=:schoolid AND id=:id", array(':weid' => $weid, ':schoolid' => $schoolid, ':id' => $it['sid']));
		$share = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_order) . " where weid = '{$weid}' AND schoolid='{$schoolid}' AND shareuserid='{$it['id']}' and kcid = '{$id}' and type=1 and status = 2 ");
		//var_dump($share);
		$stup=$student['points']?$student['points']:0;
		$school = pdo_fetch("SELECT style2,title,tpic,logo,Is_point,thumb FROM " . tablename($this->table_index) . " where weid = :weid AND id = :id ", array(':weid' => $weid, ':id' => $schoolid));

		$item = pdo_fetch("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE id = :id ", array(':id' => $id));
		//var_dump($item);
        $t_array = explode(',',$item['tid']);
		$tname_array = ' ';
		foreach( $t_array as $key_t => $value_t )
		{
			$teacher_all =  pdo_fetch("SELECT tname FROM " . tablename($this->table_teachers) . " WHERE schoolid = :schoolid And id = :id", array(':schoolid' => $schoolid,':id' => $value_t));	
			$tname_array.=$teacher_all['tname']."/";
		}
		$tname_array_end = trim($tname_array,"/");
		
		$time = strtotime(date('Ymd'));
		$time1 =$time + 86399;
		if($item['OldOrNew'] == 0){
			$isHaveKs = pdo_fetch("select * FROM ".tablename($this->table_kcbiao)." WHERE date>'{$time}' AND date<'{$time1}' And kcid = '{$item['id']}'");
			if (!empty($isHaveKs)){
				$hasSign = pdo_fetch("select id FROM ".tablename($this->table_kcsign)." WHERE createtime>='{$time}' AND createtime<'{$time1}' And kcid = '{$item['id']}' AND ksid = '{$isHaveKs['id']}' AND sid='{$it['sid']}' ");
			}
			//获取课时信息
			$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_kcbiao) . " WHERE weid = :weid AND schoolid =:schoolid AND kcid = :kcid  ORDER BY date ASC", array(':weid' => $weid, ':schoolid' => $schoolid, ':kcid' => $id));
			//var_dump($list);
			foreach( $list as $key => $value )
			{
				$checksign = pdo_fetch("SELECT id,status FROM " . tablename($this->table_kcsign) . " WHERE weid='{$weid}' AND schoolid='{$schoolid}' AND  ksid = '{$value['id']}' AND kcid='{$id}' AND sid='{$it['sid']}' ");
				if(!empty($checksign)){
					$list[$key]['checksign'] = $checksign['status'];
				}else{
					$list[$key]['checksign'] = 0;
				}
			}
		}else{
			$hasSign = pdo_fetch("select id FROM ".tablename($this->table_kcsign)." WHERE createtime>='{$time}' AND createtime<'{$time1}' And kcid = '{$item['id']}' AND sid='{$it['sid']}'  ");
			$signlist =  pdo_fetchall("select * FROM ".tablename($this->table_kcsign)." WHERE kcid = '{$item['id']}' AND sid='{$it['sid']}' AND weid='{$weid}' AND schoolid='{$schoolid}' ORDER BY createtime ASC ");
			//var_dump($signlist);
		}
		
		$allnum = $item['AllNum'];
		$ygks = pdo_fetch("SELECT * FROM " . tablename($this->table_coursebuy) . " WHERE kcid = :kcid AND sid=:sid ", array(':kcid' => $id,':sid'=>$it['sid']));

		//获取该课程教师信息
		if($item['maintid'] != 0){
			  $teacher = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where weid = :weid AND schoolid=:schoolid AND id=:id", array(':weid' => $weid, ':schoolid' => $schoolid, ':id' => $item['maintid']));
		}else{
			 $teacher = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where weid = :weid AND schoolid=:schoolid AND id=:id", array(':weid' => $weid, ':schoolid' => $schoolid, ':id' => $item['tid']));
		}

		$title = $item['name'];
       
		$title1 .= "我分享了一个课程，快来看看吧";  
		$sharetitle = $title1;
		$sharedesc = $title."【课程分享】";
		$shareimgUrl = tomedia($item['thumb']);
		$links = $_W['siteroot'] .'app/'.$this->createMobileUrl('kcinfo', array('schoolid' => $schoolid,'id' => $id,'shareuserid'=>$it['id'],'fenxiang'=> 'fenxiang'));
        include $this->template(''.$school['style2'].'/mykcinfo');
?>
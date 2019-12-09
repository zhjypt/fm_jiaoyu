<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
        global $_W, $_GPC;
        $weid = $this->weid;
        $from_user = $this->_fromuser;
		$schoolid = intval($_GPC['schoolid']);
		$scsid = intval($_GPC['sid']);
		$type = intval($_GPC['type']);
		$openid = $_W['openid'];
		$bj_id = intval($_GPC['bj_id']);
        
        //查询是否用户登录		
		$userid = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where :schoolid = schoolid And :weid = weid And :openid = openid And :sid = sid", array(':weid' => $weid, ':schoolid' => $schoolid, ':openid' => $openid, ':sid' => 0), 'id');
		
		$it = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where weid = :weid AND id=:id ORDER BY id DESC", array(':weid' => $weid, ':id' => $userid['id']));
        
		$school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " where weid = :weid AND id=:id ORDER BY ssort DESC", array(':weid' => $weid, ':id' => $schoolid));
		
		$name = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where weid = :weid AND :schoolid = schoolid AND id=:id", array(':weid' => $weid, ':schoolid' => $schoolid, ':id' => $scsid));
		
		$last = pdo_fetch("SELECT * FROM " . tablename($this->table_media) . " where weid = :weid AND :schoolid = schoolid AND sid=:sid ORDER BY createtime DESC LIMIT 0,1 ", array(':weid' => $weid, ':schoolid' => $schoolid, ':sid' => $scsid));

		$total = pdo_fetchcolumn("SELECT count(*) FROM " . tablename($this->table_media) . " where schoolid = '{$schoolid}' And type = 1 And sid = '{$scsid}' ");	
		
		if ($_GPC['getalist']) {
			$pageSize = intval($_GPC['pageSize']);
			$nowPage = intval($_GPC['nowPage']);
			$item_per_page = empty($_GPC['pageSize']) ? 10 : intval($_GPC['pageSize']);
			$page_number = max(1, intval($_GPC['nowPage']));  
			if (!empty($_GPC['bj_id'])) {
				$condition .= " And (bj_id1 = '{$_GPC['bj_id']}' or bj_id2 = '{$_GPC['bj_id']}' or bj_id3 = '{$_GPC['bj_id']}')";
			}
			if(!is_numeric($page_number)){  
	   			header('HTTP/1.1 500 Invalid page number!');  
	    		exit();  
			}
				      	
			$position = ($page_number-1) * $item_per_page;
			$condition .= " And schoolid=" .$schoolid;
			if (!empty($scsid)) {
				$condition .= " And sid=" .$scsid;
			}
			//if (!empty($_GPC['type'])) {
				$condition .= " And type=" .$type;
			//} 
			$xclist =pdo_fetchall("SELECT * FROM " . tablename($this->table_media) . " WHERE weid = {$_W['uniacid']} $condition ORDER BY createtime DESC, isfm DESC LIMIT " . $position . ',' . $item_per_page );
			
			foreach ($xclist as $key => $value) {
				$students = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where weid = :weid AND :schoolid = schoolid AND id=:id", array(
					':weid' => $weid,
					':schoolid' => $schoolid,
					':id' => $value['sid']
					));
				$stotal = pdo_fetchcolumn("SELECT count(*) FROM " . tablename($this->table_media) . " where schoolid = '{$schoolid}' And type = 1 And sid = '{$value['sid']}' ");	  
				$xclist[$key]['id'] = $value['id'];	
				$xclist[$key]['date'] = date('Y-m-d', $value['createtime']);	
				$xclist[$key]['tag'] = $students['s_name'];	
				$xclist[$key]['wlzytype'] = $value['sid'];
				$xclist[$key]['total'] = $stotal;
				$xclist[$key]['tagid'] = $value['uid'];
				$xclist[$key]['image'] = tomedia($value['picurl']);
			}
			$datas = array(
				'ret' => array('code' => '200','msg' => 'success'),
				'data' => array(
							'imageList' => $xclist,
								)
						);
						
				echo json_encode($datas);
				exit;
		}
		
        if(!empty($userid['id']) && $userid['sid'] == 0){
            
		 	include $this->template(''.$school['style3'].'/xc');
        }else{
	        session_destroy();
            $stopurl = $_W['siteroot'] .'app/'.$this->createMobileUrl('bangding', array('schoolid' => $schoolid));
			header("location:$stopurl");
        }        

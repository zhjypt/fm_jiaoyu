<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
        global $_GPC, $_W;
       
        $weid = $_W['uniacid'];
        $action = 'booksrecord';
		$this1 = 'no8';
		$GLOBALS['frames'] = $this->getNaveMenu($_GPC['schoolid'],$action);
	    $schoolid = intval($_GPC['schoolid']);
		$logo = pdo_fetch("SELECT logo,title FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}'");
		$school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " where id = :id ", array(':id' => $schoolid));
		$xueqi = pdo_fetchall("SELECT sid,sname FROM " . tablename($this->table_classify) . " where weid = :weid AND schoolid = :schoolid And type = :type  ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'semester', ':schoolid' => $schoolid));
		$bj   = pdo_fetchall("SELECT sid,sname FROM " . tablename($this->table_classify) . " where weid = :weid AND schoolid = :schoolid And type = :type  ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'theclass', ':schoolid' => $schoolid));	
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		 $tid_global = $_W['tid'];
		if (!(IsHasQx($tid_global,1003701,1,$schoolid))){
			$this->imessage('非法访问，您无权操作该页面','','error');	
		} 
        if($operation == 'display') {
			
            $pindex = max(1, intval($_GPC['page']));
            $psize = 10;
            $condition = '';
    			
											
        			
			$is_return = isset($_GPC['is_return']) ? intval($_GPC['is_return']) : -1;
			if($is_return >= 0) {
				$condition .= " AND status = '{$is_return}'";
			}			
			if(!empty($_GPC['borrowtime'])) {
				$starttime = strtotime($_GPC['borrowtime']['start']);
				$endtime = strtotime($_GPC['borrowtime']['end']) + 86399;
				$condition .= " AND borrowtime >= '{$starttime}' AND borrowtime <= '{$endtime}'";
			} else {
				$starttime = strtotime('-600 day');
				$endtime = TIMESTAMP;
			}
      
			if(!empty($_GPC['njid']) && empty($_GPC['bjid'])){
				$students = pdo_fetchall('SELECT id FROM ' . tablename($this->table_students) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}'and xq_id = '{$_GPC['njid']}' ");
				$stuid_str = '';
				foreach($students as $key_s=>$value_s){
					$stuid_str .= $value_s['id'].",";
				}
				$stuid_str = trim($stuid_str,",");
				$condition .="and FIND_IN_SET(sid,'{$stuid_str}') ";
			}
			
			if(!empty($_GPC['bjid'])){
				$students = pdo_fetchall('SELECT roomid FROM ' . tablename($this->table_students) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}'and bj_id = '{$_GPC['bjid']}' ");
				$stuid_str = '';
				foreach($students as $key_s=>$value_s){
					$stuid_str .= $value_s['id'].",";
				}
				$stuid_str = trim($stuid_str,",");
				$condition .="and FIND_IN_SET(sid,'{$stuid_str}') ";
			}
			if(!empty($_GPC['StuName'])){
				$students = pdo_fetchall('SELECT id FROM ' . tablename($this->table_students) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' and s_name LIKE '%{$_GPC['StuName']}%' ");
				$roomid_str = '';
				foreach($students as $key_s=>$value_s){
					$stuid_str .= $value_s['id'].",";
				}
				$stuid_str = trim($stuid_str,",");
				$condition .="and FIND_IN_SET(sid,'{$stuid_str}') ";
			}
			if(!empty($_GPC['BookName'])){
				$condition .="and bookname like '%{$_GPC['BookName']}%' ";
			}

            $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_booksborrow) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' $condition ORDER BY createtime DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
				foreach($list as $index => $row){
					$stu_info =	pdo_fetch('SELECT s_name,xq_id,bj_id,icon FROM ' . tablename($this->table_students) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}'and id = '{$row['sid']}' ");
					$list[$index]['nj_name'] = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " where weid = '{$weid}' AND schoolid ='{$schoolid}'  and sid = '{$stu_info['xq_id']}' ")['sname'];
					$list[$index]['bj_name'] = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " where weid = '{$weid}' AND schoolid ='{$schoolid}'  and sid = '{$stu_info['bj_id']}' ")['sname'];
					$list[$index]['stuname'] = $stu_info['s_name'];
					$list[$index]['stuicon'] = $stu_info['icon'];
				}
            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_booksborrow) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' $condition");

            $pager = pagination($total, $pindex, $psize);			
		

        }elseif ($operation == 'returnbooks') {
            $id = $_GPC['id'];
			$schoolid = $_GPC['schoolid'];
			$check = pdo_fetch('SELECT * FROM ' . tablename($this->table_booksborrow) . " WHERE schoolid = '{$schoolid}' and id = '{$id}'"); 
			if(empty($check)){
				$this->imessage('归还失败，未查询到该条借阅信息！', referer(), 'error');
			}else{
				$time = time();
				pdo_update($this->table_booksborrow,array('status'=>2,'returntime'=>$time),array('id'=>$id));
				 $this->imessage('归还成功！！',$this->createWebUrl('booksrecord', array('op' => 'display', 'schoolid' => $schoolid)), 'success');
			}
        }
		elseif ($operation == 'delete') {
            $id = $_GPC['id'];
			$schoolid = $_GPC['schoolid'];
			$check = pdo_fetch('SELECT * FROM ' . tablename($this->table_booksborrow) . " WHERE schoolid = '{$schoolid}' and id = '{$id}'"); 
			if(empty($check)){
				$this->imessage('删除失败，未查询到该条借阅信息', referer(), 'error');
			}else{
				pdo_delete($this->table_booksborrow,array('id'=>$id));
				 $this->imessage('删除成功',$this->createWebUrl('booksrecord', array('op' => 'display', 'schoolid' => $schoolid)), 'success');
			}
        } 	
        include $this->template ( 'web/booksrecord' );
?>
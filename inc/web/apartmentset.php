<?php
	global $_GPC, $_W;
   
	$weid = $_W['uniacid'];
	$action = 'apartmentset';
	$this1 = 'no7';
	$GLOBALS['frames'] = $this->getNaveMenu($_GPC['schoolid'],$action);
	$schoolid = intval($_GPC['schoolid']);
	$logo = pdo_fetch("SELECT logo,title FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}'");
	$school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " where id = :id ", array(':id' => $schoolid));
					
	$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
	$tid_global = $_W['tid'];
	
		if($tid_global !='founder' && $tid_global != 'owner'){
			$loginTeaFzid =  pdo_fetch("SELECT fz_id FROM " . tablename ($this->table_teachers) . " where weid = :weid And schoolid = :schoolid And id =:id ", array(':weid' => $weid,':schoolid' => $schoolid,':id'=>$tid_global));
			$qxarr = GetQxByFz($loginTeaFzid['fz_id'],1,$schoolid);
			$toPage = 'apartmentset';
			if( !(strstr($qxarr,'1003201'))){
				$toPage = 'aproomset';
			}
			if(!(strstr($qxarr,'1003211')) && $toPage == 'aproomset'){
				$toPage = 'apartmentset';
			}
			if($toPage != 'apartmentset'){
				$stopurl = $_W['siteroot'] .'web/'.$this->createWebUrl($toPage, array('schoolid' => $schoolid,'op'=>'display'));
				header("location:$stopurl");
			}
		}
	
	
	
	if ($operation == 'display') {
		if (!(IsHasQx($tid_global,1003201,1,$schoolid))){
			$this->imessage('非法访问，您无权操作该页面','','error');	
		} 	
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$condition = '';
		
		if(!empty($_GPC['sea_name'])){
			$condition .= "and name like '%{$_GPC['sea_name']}%' ";
		}
		$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_apartment) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' $condition ORDER BY ssort DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
		 foreach($list as $index => $row){
			$thisroom = pdo_fetchall('SELECT id FROM ' . tablename($this->table_aproom) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' and apid = '{$row['id']}' ");
			$stu_count = 0 ;
			foreach($thisroom as $key_r=>$value_r){
				$stu_count += pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_students) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' and roomid = '{$value_r['id']}'");
			}
			$list[$index]['stuCount'] = $stu_count;
			$list[$index]['roomCount'] = count($thisroom);
			if(!empty($row['tid'])){
				$list[$index]['tname'] =  pdo_fetch("SELECT tname FROM " . tablename($this->table_teachers) . " WHERE id = '{$row['tid']}'")['tname'];
			}
		} 
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_apartment) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' $condition");
		$pager = pagination($total, $pindex, $psize);			
	}elseif($operation == 'post'){
		if (!(IsHasQx($tid_global,1003202,1,$schoolid))){
			$this->imessage('非法访问，您无权操作该页面','','error');	
		} 
		
		$teacherslist = pdo_fetchall('SELECT id,tname FROM ' . tablename($this->table_teachers) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' ORDER BY CONVERT(tname USING gbk) ASC  ");
		$id = intval($_GPC['id']);
		if(!empty($id)){
			$apartment = pdo_fetch("SELECT * FROM " . tablename($this->table_apartment) . " WHERE id = '{$id}'");
			if(!empty($apartment['tid'])){
				$apartment['tname'] =  pdo_fetch("SELECT tname FROM " . tablename($this->table_teachers) . " WHERE id = '{$apartment['tid']}'")['tname'];
			}
		}else{
			$apartment = array(
				'ssort' => 0,
			);
		}
		if(checksubmit('submit')){
			$wrong_back = '';
			if(!empty($id)){
				if(!empty($_GPC['old'])){
					if(empty($_GPC['ApartName'])){
						$this->imessage('抱歉，楼栋名称不能为空！', referer(), 'error');
					}
					$check_name = pdo_fetch('SELECT id FROM ' . tablename($this->table_apartment) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}'and name = '{$_GPC['ApartName']}' and id != '{$id}'  ");
					if(!empty($check_name)){
						$wrong_back .= $_GPC['ApartName']." | ";
						//$this->imessage('抱歉，楼栋名称重复！', referer(), 'error');
					}else{
						$data = array(
							'weid'      => $weid,
							'schoolid'  => $_GPC['schoolid'],
							'name'      => $_GPC['ApartName'],
							'ssort'     => intval($_GPC['ssort']),
							'tid'		=> $_GPC['tid'],
						);
						pdo_update($this->table_apartment, $data, array('id' => $id));
						
					}
					
				}

				if(!empty($_GPC['new'])){
					foreach($_GPC['new'] as $key => $value){
						$name = trim($_GPC['ApartName_new'][$key]);
						if(empty($name)){
							$this->imessage('抱歉，楼栋名称不能为空！', referer(), 'error');
						}
						$check_name_this = pdo_fetch('SELECT id FROM ' . tablename($this->table_apartment) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}'and name = '{$name}' and id != '{$id}'  ");
						if(!empty($check_name_this)){
							$wrong_back .= $name." | ";
						}else{
							$data = array(
								'weid'     	=> $weid,
								'schoolid' 	=> $_GPC['schoolid'],
								'name'    	=> $name,
								'ssort'    	=> intval($_GPC['ssort_new'][$key]),
								'tid'		=> $_GPC['tid_new'][$key],							
							);	
							pdo_insert($this->table_apartment, $data);
						}
					}
				}
			}else{
				if(!empty($_GPC['new'])){
					foreach($_GPC['new'] as $key => $value){
						$name = trim($_GPC['ApartName_new'][$key]);
						if(empty($name)){
							$this->imessage('抱歉，楼栋名称不能为空！', referer(), 'error');
						}
						$check_name_this_new = pdo_fetch('SELECT id FROM ' . tablename($this->table_apartment) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}'and name = '{$name}' and id != '{$id}'  ");
						if(!empty($check_name_this_new)){
							$wrong_back .= $name." | ";
						}else{
							$data = array(
								'weid'     	=> $weid,
								'schoolid' 	=> $_GPC['schoolid'],
								'name'    	=> $name,
								'ssort'    	=> intval($_GPC['ssort_new'][$key]),
								'tid'		=> $_GPC['tid_new'][$key],
							);	
							pdo_insert($this->table_apartment, $data);
						}
					}
				}			 
			}
			$back_str = '操作完成！';
			if($wrong_back != ''){
				$back_str .='</br>以下楼栋名称重复，操作失败:'.$wrong_back;
				
			}
		   $this->imessage($back_str,$this->createWebUrl('apartmentset', array('op' => 'display', 'schoolid' => $schoolid)), 'success');
		}
	}elseif ($operation == 'delete') {
		$id = intval($_GPC['id']);
		if (empty($id)) {
			$this->imessage('抱歉，本条信息不存在在或是已经被删除！');
		}
		$check = pdo_fetch("SELECT * FROM " . tablename($this->table_apartment) . " WHERE id = :id ", array(':id' => $id));
		if (empty($check)) {
			$this->imessage('抱歉，本条信息不存在在或是已经被删除！');
		}
		pdo_delete($this->table_apartment,array('id' => $id));
		$this->imessage('操作成功！', referer(), 'success'); 
	}	  

   include $this->template ( 'web/apartmentset' );
?>
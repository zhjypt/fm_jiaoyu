<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
        global $_GPC, $_W;
       
        $weid = $_W['uniacid'];
        $action = 'kecheng';
		$this1 = 'no2';
		$GLOBALS['frames'] = $this->getNaveMenu($_GPC['schoolid'],$action);
	    $schoolid = intval($_GPC['schoolid']);
	    $is_start = !empty($_GPC['is_start'])?$_GPC['is_start'] : -1 ;
		$logo = pdo_fetch("SELECT logo,title FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}'");
		$school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " where id = :id ", array(':id' => $schoolid));

		$it = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " WHERE sid = :sid", array(':sid' => $sid));
		$xueqi = pdo_fetchall("SELECT sid,sname FROM " . tablename($this->table_classify) . " where weid = :weid AND schoolid = :schoolid And type = :type ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'semester', ':schoolid' => $schoolid));		
		$km = pdo_fetchall("SELECT sid,sname FROM " . tablename($this->table_classify) . " where weid = :weid AND schoolid = :schoolid And type = :type ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'subject', ':schoolid' => $schoolid));
		$bj = pdo_fetchall("SELECT sid,sname FROM " . tablename($this->table_classify) . " where weid = :weid AND schoolid = :schoolid And type = :type ORDER BY CONVERT(sname USING gbk) ASC", array(':weid' => $weid, ':type' => 'theclass', ':schoolid' => $schoolid));
		$xq = pdo_fetchall("SELECT sid,sname FROM " . tablename($this->table_classify) . " where weid = :weid AND schoolid = :schoolid And type = :type ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'week', ':schoolid' => $schoolid));
		$sd = pdo_fetchall("SELECT sid,sname FROM " . tablename($this->table_classify) . " where weid = :weid AND schoolid = :schoolid And type = :type ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'timeframe', ':schoolid' => $schoolid));
		$qh = pdo_fetchall("SELECT sid,sname FROM " . tablename($this->table_classify) . " where weid = :weid AND schoolid = :schoolid And type = :type ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'score', ':schoolid' => $schoolid));
        $category = pdo_fetchall("SELECT sid,sname FROM " . tablename($this->table_classify) . " WHERE weid = :weid AND schoolid = :schoolid ORDER BY sid ASC, ssort DESC", array(':weid' => $weid, ':schoolid' => $schoolid), 'sid');
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        $tid_global = $_W['tid'];
      
		if($tid_global !='founder' && $tid_global != 'owner'){
			$loginTeaFzid =  pdo_fetch("SELECT fz_id FROM " . tablename ($this->table_teachers) . " where weid = :weid And schoolid = :schoolid And id =:id ", array(':weid' => $weid,':schoolid' => $schoolid,':id'=>$tid_global));
			$qxarr = GetQxByFz($loginTeaFzid['fz_id'],1,$schoolid);
			$toPage = 'kecheng';
			if( !(strstr($qxarr,'1000901'))){
				$toPage = 'kcbiao';
			}
			if(!(strstr($qxarr,'1000921')) && $toPage == 'kcbiao'){
				$toPage = 'kcsign';
			}
			if(!(strstr($qxarr,'1000941')) && $toPage == 'kcsign'){
				$toPage = 'gongkaike';
			}
			if(!(strstr($qxarr,'1000951')) && $toPage == 'gongkaike'){
				$toPage = 'kecheng';
			}

			if($toPage != 'kecheng'){
				$stopurl = $_W['siteroot'] .'web/'.$this->createWebUrl($toPage, array('schoolid' => $schoolid,'op'=>'display'));
				header("location:$stopurl");
			}
		}
	 	
        if ($operation == 'post') {
	        if (!(IsHasQx($tid_global,1000902,1,$schoolid))){
				$this->imessage('非法访问，您无权操作该页面','','error');	
			}
			/* $bj = pdo_fetchall("SELECT sid,sname FROM " . tablename($this->table_classify) . " where weid = :weid AND schoolid = :schoolid And type = :type and is_over!=:is_over ORDER BY CONVERT(sname USING gbk) ASC", array(':weid' => $weid, ':type' => 'theclass', ':schoolid' => $schoolid,':is_over'=>"2"));
			$xq = pdo_fetchall("SELECT sid,sname FROM " . tablename($this->table_classify) . " where weid = :weid AND schoolid = :schoolid And type = :type  and is_over!=:is_over ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'week', ':schoolid' => $schoolid,':is_over'=>"2")); */
            load()->func('tpl');
            $id = intval($_GPC['id']);
            $courseType =  pdo_fetchall("SELECT sid,sname FROM " . tablename($this->table_classify) . " where weid = '{$weid}' AND schoolid ='{$schoolid}' AND type='coursetype' ORDER BY ssort DESC ");
            $addr =  pdo_fetchall("SELECT sid,sname FROM " . tablename($this->table_classify) . " where weid = '{$weid}' AND schoolid ='{$schoolid}' AND type='addr' ORDER BY ssort DESC ");
			$payweid = pdo_fetchall("SELECT * FROM " . tablename('account_wechats') . " where level = 4 ORDER BY acid ASC");
			$teachers = pdo_fetchall("SELECT id,tname FROM " . tablename ($this->table_teachers) . " where weid = :weid And schoolid = :schoolid ORDER BY  CONVERT(tname USING gbk)  ASC ", array(
					':weid' => $weid,
					':schoolid' => $schoolid
				) );
            if (!empty($id)) {
                $item = pdo_fetch("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE id = :id ", array(':id' => $id));
                $uniarr = explode(',', $item['tid']);
				$teachers = pdo_fetchall("SELECT id,tname FROM " . tablename ($this->table_teachers) . " where weid = :weid And schoolid = :schoolid ORDER BY  CONVERT(tname USING gbk)  ASC ", array(
					':weid' => $weid,
					':schoolid' => $schoolid
				) );
                if (empty($item)) {   
                    $this->imessage('抱歉，本条信息不存在在或是已经删除！', '', 'error');
                }
				mload()->model('print');
				$nowprints = explode(',', $item['printarr']);
				$printers = printers($schoolid);
				$printer_name = printer_name();
            }
            if (checksubmit('submit')) {
	            if(empty($_GPC['tidarr'])){
					$this->imessage('请至少选择一位授课老师！', '', 'referer');
				}
				$checkkc = pdo_fetch("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE id = :id ", array(':id' => $id));
	            $tidarray = implode(',', $_GPC['tidarr']);
	            $AllNum   = 0;
	            $FirstNum = 0;
	            $ReNum    = 0;
	            $RePrice  = 0;
	            $signTime = 0;
	            $isSign = !empty($_GPC['is_sign'])?$_GPC['is_sign']:$checkkc['isSign'];
	            if($isSign == 1  ){
		            $signTime = $_GPC['signTime'];
				}
				
	            $AllNum   = intval($_GPC['AllNum']);
				$FirstNum = intval($_GPC['FirstNum']);
				$ReNum    = intval($_GPC['ReNum']);
				$RePrice  = $_GPC['RePrice'];
	            if($_GPC['OldOrNew'] == 1 ){
					$isSign   = 1;
				}
				if(empty($_GPC['kcthumb'])){
					$this->imessage('课程图片不能为空！', '', 'referer');
				}
				if(empty($_GPC['xq'])){
					$this->imessage('年级不能为空！', '', 'referer');
				}
				if($_GPC['is_tx'] == 1){
					if(empty($_GPC['txtime'])){
						$this->imessage('抱歉，开启定时上课提醒必须设置提前提醒时间', '', 'referer');
					}
				}
				if(empty($_GPC['km'])){
					$this->imessage('科目不能为空！', '', 'referer');
				}
				if($AllNum == 0){
					$this->imessage('总课时不能为空！', '', 'referer');
				}
				if($AllNum == 0){
					$this->imessage('首购课时不能为空！', '', 'referer');
				}
				if(count( $_GPC['tidarr']) > 1){
					if(! empty($_GPC['maintid'])){
						$maintid = $_GPC['maintid'];
					}else{
						$this->imessage('主讲老师不能为空！', '', 'referer');
					}
				}else{
					$maintid = $tidarray;
				}
				
				;
	            $data     = array(
				    'weid'     => $weid,
				    'schoolid' => $schoolid,
				    'tid'      => $tidarray,
				    'xq_id'    => trim($_GPC['xq']),
				    'km_id'    => trim($_GPC['km']),
				    'bj_id'    => trim($_GPC['bj']),
				    'name'     => trim($_GPC['name']),
				    'minge'    => trim($_GPC['minge']),
				    'yibao'    => trim($_GPC['yibao']),
				    'cose'     => trim($_GPC['cose']),
				    'dagang'   => trim($_GPC['dagang']),
				    'adrr'     => trim($_GPC['adrr']),
					'is_dm'    => intval($_GPC['is_dm']),
					'is_tx'    => intval($_GPC['is_tx']),
					'txtime'   => intval($_GPC['txtime']),
				    'is_hot'   => intval($_GPC['is_hot']),
				    'is_show'  => intval($_GPC['is_show']),
				    'ssort'    => intval($_GPC['ssort']),
				    'start'    => strtotime($_GPC['start']),
				    'end'      => strtotime($_GPC['end']),
					'printarr' => implode(',', $_GPC['printarr']),
					'is_print' => intval($_GPC['is_print']),
				    'payweid'  => empty($_GPC['payweid']) ? $weid : $_GPC['payweid'],
				    'signTime' => $signTime,
				    'isSign'   => $isSign,
				    'AllNum'   => $AllNum,
				    'FirstNum' => $FirstNum,
				    'ReNum'    => $ReNum,
				    'RePrice'  => $RePrice,
				    'thumb'	   => $_GPC['kcthumb'],
					'bigimg'   => $_GPC['bigimg'],
				    'Ctype'    => $_GPC['Ctype'],
				    'maintid'  => $maintid,
				    'Point2Cost'=> intval($_GPC['Point2Cost']),
				    'MinPoint' => intval($_GPC['MinPoint']),
				    'MaxPoint' => intval($_GPC['MaxPoint']),
				    'yytid'	   =>$_GPC['yytid'],
				    'is_tuijian' => $_GPC['is_tuijian']
				    
                );
				if(!empty($_GPC['OldOrNew'])){
					$data['OldOrNew'] = intval($_GPC['OldOrNew']);
				}
                if (empty($id)) {
                    pdo_insert($this->table_tcourse, $data);
					$kcid = pdo_insertid();
                } else {
                    pdo_update($this->table_tcourse, $data, array('id' => $id));
					$kcid = $id;
                }
				if($_GPC['is_tx']){
					$task = pdo_fetch("SELECT * FROM " . tablename('wx_school_task') . " WHERE kcid = '{$kcid}' And type = 1 ");
					$temp = array(
						'weid'     		=> $weid,
						'schoolid' 		=> $schoolid,
						'kcid'     		=> $kcid,
						'status'  		=> $_GPC['is_tx'],
						'type'     		=> 1,
						'createtime'    => time()
					);
					if(empty($task)){
						pdo_insert('wx_school_task', $temp);
					}else{
						unset($temp['createtime']);
						pdo_update('wx_school_task', $temp, array('id' => $task['id']));
					}
				}
                $this->imessage('修改成功！', $this->createWebUrl('kecheng', array('op' => 'display', 'schoolid' => $schoolid)), 'success');
            }
        } elseif ($operation == 'display') {
	        if (!(IsHasQx($tid_global,1000901,1,$schoolid))){
				$this->imessage('非法访问，您无权操作该页面','','error');	
			}
            if (checksubmit('submit')) { //排序
                if (is_array($_GPC['ssort'])) {
                    foreach ($_GPC['ssort'] as $id => $val) {
                        $data = array('ssort' => intval($_GPC['ssort'][$id]));
                        pdo_update($this->table_tcourse, $data, array('id' => $id));
                    }
                }
                $this->imessage('批量修排序成功!', $url);
            }			

            $pindex    = max(1, intval($_GPC['page']));
            $psize     = 10;
            $condition = '';
            $time = time();
			$is_start = !empty($_GPC['is_start'])?$_GPC['is_start'] : -1 ;
			switch ( $is_start )
			{
				case -1 :
					break;
				case 1 :
					$condition .= "AND start > {$time}";
					break;
				case 2 :
					$condition .= "AND start <= {$time} AND end >= {$time}";
					break;
				case 3 :
					$condition .= " AND end < {$time}";
					break;		
				default:
					break;
			}
		    if (!empty($_GPC['name'])) {
                $condition .= " AND name LIKE '%{$_GPC['name']}%' ";
            }
            if (!empty($_GPC['tname'])) {
	            $tname = trim($_GPC['tname']);
	            $tid = pdo_fetch("SELECT id FROM " . tablename ($this->table_teachers) . " where weid='{$weid}' AND schoolid='{$schoolid}' AND tname LIKE '%$tname%' ");
                $condition .= "AND (tid like '{$tid['id']},%' OR tid like '%,{$tid['id']}' OR tid like '%,{$tid['id']},%' OR tid='{$tid['id']}') ";
            }
            if (!empty($_GPC['bj_id'])) {
                $cid = intval($_GPC['bj_id']);
                $condition .= " AND bj_id = '{$cid}'";
            }	
            if (!empty($_GPC['km_id'])) {
                $cid = intval($_GPC['km_id']);
                $condition .= " AND km_id = '{$cid}'";
            }
			$bj_str = '';
			foreach($bj as $key=>$value){
				$bj_str .=$value['sid'].","; 
			}	
			//var_dump($bj_str);
			$bj_str_f = trim($bj_str,",");		
            $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' $condition ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
                $listAll = pdo_fetchall("SELECT id,name,end FROM " . tablename($this->table_tcourse) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' ORDER BY id DESC " );
				foreach($list as $key => $row){
					$tidarray =  explode(',', $row['tid']);
					if($row['Ctype'] != 0 ){
						$course_type = pdo_fetch("SELECT sname FROM " . tablename ($this->table_classify) . " where sid = :sid ", array(':sid' => $row['Ctype']));pdo_fetch("SELECT sname FROM " . tablename ($this->table_classify) . " where sid = :sid ", array(':sid' => $row['Ctype']));
						$list[$key]['course_type'] = $course_type['sname'];
					}
					$yb = pdo_fetchcolumn("select count(distinct sid) FROM ".tablename($this->table_order)." WHERE kcid = '".$row['id']."' And status = 2 ");
					$allks = pdo_fetchcolumn("select count(*) FROM ".tablename($this->table_kcbiao)." WHERE kcid = '".$row['id']."'");
					$list[$key]['allks'] = $allks;
					$list[$key]['tid'] =  explode(',', $row['tid']);
					$list[$key]['shengyu'] = $row['minge'] - $yb;
					$list[$key]['yib'] = $yb +$row['yibao'];
					foreach( $tidarray as $key1 => $value )
					{
						$teacher = pdo_fetch("SELECT * FROM " . tablename ($this->table_teachers) . " where id = :id ", array(':id' => $value));
						$yb = pdo_fetchcolumn("select count(*) FROM ".tablename($this->table_order)." WHERE kcid = '".$row['id']."' And status = 2 ");
						$allks = pdo_fetchcolumn("select count(*) FROM ".tablename($this->table_kcbiao)." WHERE kcid = '".$row['id']."'");
						$list[$key]['tname'][$key1]['tname'] = $teacher['tname'];
						$list[$key]['tname'][$key1]['tid'] = $teacher['id'];

					}
				}
            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_tcourse) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' $condition");

            $pager = pagination($total, $pindex, $psize);
        } elseif ($operation == 'delete') {
            $id = intval($_GPC['id']);
            if (empty($id)) {
                $this->imessage('抱歉，本条信息不存在在或是已经被删除！');
            }
            pdo_delete($this->table_tcourse, array('id' => $id));
            $this->imessage('删除成功！', referer(), 'success');
        } elseif ($operation == 'deleteall') {
            $rowcount = 0;
            $notrowcount = 0;
            foreach ($_GPC['idArr'] as $k => $id) {
                $id = intval($id);
                if (!empty($id)){
                    $goods = pdo_fetch("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE id = :id", array(':id' => $id));
                    if (empty($goods)) {
                        $notrowcount++;
                        continue;
                    }
                    pdo_delete($this->table_tcourse, array('id' => $id, 'weid' => $weid));
                    $rowcount++;
                }
            }
            $this->imessage("操作成功！共删除{$rowcount}条数据,{$notrowcount}条数据不能删除!", '', 0);
        } elseif ($operation == 'add') {
	         if (!(IsHasQx($tid_global,1000922,1,$schoolid))){
				$this->imessage('非法访问，您无权操作该页面','','error');	
			}
	         $addr =  pdo_fetchall("SELECT sid,sname FROM " . tablename($this->table_classify) . " where weid = '{$weid}' AND schoolid ='{$schoolid}' AND type='addr' ORDER BY ssort DESC ");
			load()->func('tpl');
            $id = intval($_GPC['id']);
            if (!empty($id)) {
                $item = pdo_fetch("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE id = :id", array(':id' => $id));	
                $tidarray = explode(',', $item['tid']);
                foreach( $tidarray as $key => $value )
                {
                	$teachers[$key] = pdo_fetch("SELECT id,tname FROM " . tablename ($this->table_teachers) . " where id = :id ", array(':id' => $value));		
                }
                if (empty($item)) {
                    $this->imessage('抱歉，课程不存在或是已经删除！', '', 'error');
                }
            }
			if (checksubmit('submit')) {
				$wrong_back = '';
				if(!empty($_GPC['new'])){
					foreach($_GPC['new'] as $key => $name){
						$sdinfo = pdo_fetch("SELECT sd_start,sd_end FROM " . tablename ($this->table_classify) . " where sid = :sid ", array(':sid' => $_GPC['sd_new'][$key]));
					
						$lasttime =$_GPC['date_new'][$key].date(" H:i",$sdinfo['sd_start']);
						$check_start = strtotime($_GPC['date_new'][$key].date(" H:i",$sdinfo['sd_start']));
						$check_end   = strtotime($_GPC['date_new'][$key].date(" H:i",$sdinfo['sd_end']));
						$check =  pdo_fetch("SELECT id FROM " . tablename ($this->table_kcbiao) . " where addr_id=:addr_id AND date>=:start AND date<= :end ", array(':addr_id' => $_GPC['skaddr_new'][$key],':start'=>$check_start,':end'=>$check_end));
					    $data = array(
						    'weid' => $weid,
							'schoolid' => $schoolid,
							'tid' => intval($_GPC['sktid_new'][$key]),
							'kcid' => trim($_GPC['kcid']),
							'bj_id' => trim($_GPC['bj_id']),
							'km_id' => trim($_GPC['km_id']),					
							'sd_id' => trim($_GPC['sd_new'][$key]),
							'xq_id' => trim($_GPC['xq']),					
							'nub' => trim($_GPC['nub_new'][$key]),
							'isxiangqing' => trim($_GPC['isxiangqing']),
							'content' => trim($_GPC['content']),
							'date' => strtotime($lasttime),
							'addr_id'=> $_GPC['skaddr_new'][$key]
		                );
		                if(empty($_GPC['sktid_new'][$key])){
							$this->imessage('授课老师不能为空！', '', 'referer');
						}
						if (istrlen($_GPC['nub_new'][$key]) == 0) {
		                    $this->imessage('没有输入编号.', '', 'error');
		                }
	                	if(!empty($check)){
		                	$chongtukc = pdo_fetch("SELECT kcid,nub FROM " . tablename ($this->table_kcbiao) . " where id='{$check['id']}' ");
		                	$chongtukecheng = pdo_fetch("SELECT name FROM " . tablename ($this->table_tcourse) . " where id='{$chongtukc['kcid']}' ");
							$wrong_back .="第".$_GPC['nub_new'][$key]."课 与 ".$chongtukecheng['name']."【第".$chongtukc['nub']."课时】冲突";
						}else{	
							pdo_insert($this->table_kcbiao, $data);
						}
					}
					$back_str = '操作成功！';
					if($wrong_back != ''){
						$back_str .='</br>以下课时教室冲突，新增失败:'.$wrong_back;
						
					}
					$this->imessage($back_str, $this->createWebUrl('kecheng', array('op' => 'display', 'schoolid' => $schoolid)), 'success');  
				}   
            }
		}	
        include $this->template ( 'web/kecheng' );
?>
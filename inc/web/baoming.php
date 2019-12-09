<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
        global $_GPC, $_W;
        
        $weid = $_W['uniacid'];
        $action = 'kecheng';
		$GLOBALS['frames'] = $this->getNaveMenu($_GPC['schoolid'],$action);
	    $schoolid = intval($_GPC['schoolid']);
		$kcid1 = intval($_GPC['kcid']);
		$logo = pdo_fetch("SELECT logo,title FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}'");
		$school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " where id = :id ORDER BY ssort DESC", array(':id' => $schoolid));
		$is_pay = ($_GPC['is_pay']) ? intval($_GPC['is_pay']) : -1;
		$kecheng = pdo_fetch("SELECT * FROM " . tablename($this->table_tcourse) . " where id = :id", array(':id' => $kcid1));
		
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        $tid_global = $_W['tid'];
    	if ($operation == 'display') {
			if (!(IsHasQx($tid_global,1000931,1,$schoolid))){
				$this->imessage('非法访问，您无权操作该页面','','error');	
			}
            $pindex = max(1, intval($_GPC['page']));
            $psize = 20;
            $condition = '';
            if (!empty($_GPC['kcid'])) {
                $kcid = intval($_GPC['kcid']);
                $condition .= " AND kcid = '{$kcid}'";
            }
			if(!empty($_GPC['kcname'])){
		     	$kcname = trim($_GPC['kcname']);
			    $kcsearch = pdo_fetchall("SELECT id FROM " . tablename($this->table_tcourse) . " WHERE weid='{$weid}' AND schoolid='{$schoolid}' and name LIKE '%$kcname%' ");
			    $kcid_temp = '';
			    if(!empty($kcsearch)){
				    foreach( $kcsearch as $key => $value )
				    {
				    	$kcid_temp .=$value['id'].",";
				    }
				    $kcid_str = trim($kcid_temp,",");
			        $condition .= " AND  FIND_IN_SET (kcid,{$kcid_str}) ";
		        }
		        else{
			         $condition .= " AND kcid =0 ";
		        }
		    }
			
			if($is_pay > 0) {
				$condition .= " AND status = '{$is_pay}'";
				$params[':is_pay'] = $is_pay;
			}			
			if(!empty($_GPC['createtime'])) {
				$starttime = strtotime($_GPC['createtime']['start']);
				$endtime = strtotime($_GPC['createtime']['end']) + 86399;
				$condition .= " AND createtime > '{$starttime}' AND createtime < '{$endtime}'";
			} else {
				$starttime = strtotime('-200 day');
				$endtime = TIMESTAMP;
			}
			$params[':start'] = $starttime;
			$params[':end'] = $endtime;
			
            $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_order) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND type = 1 $condition GROUP BY kcid,sid ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
				foreach($list as $index => $row){
							$kc = pdo_fetch("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE id = :id ", array(':id' => $row['kcid']));
							$student = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " WHERE id = :id ", array(':id' => $row['sid']));
							$user = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " WHERE id = :id ", array(':id' => $row['userid']));
							$buycourse = pdo_fetchcolumn("SELECT ksnum FROM " . tablename($this->table_coursebuy) . " WHERE sid = :sid AND kcid=:kcid and  schoolid =:schoolid", array(':sid' => $row['sid'],':kcid'=> $row['kcid'],':schoolid'=> $schoolid));
							$hasSign =  pdo_fetchcolumn("SELECT count(*) FROM " . tablename($this->table_kcsign) . " WHERE sid = :sid AND kcid=:kcid and  schoolid =:schoolid AND status =2 ", array(':sid' => $row['sid'],':kcid'=> $row['kcid'],':schoolid'=> $schoolid));
							$list[$index]['restnum'] = $buycourse - $hasSign;
							$list[$index]['buycourse'] = $buycourse;
							$list[$index]['hasSign'] = $hasSign;
							$list[$index]['kcnanme'] = $kc['name'];
							$list[$index]['s_name'] = $student['s_name'];
							$list[$index]['userinfo'] = $user['userinfo'];
							$list[$index]['pard'] = $user['pard'];
				}
            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_order) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND type = 1 $condition ");
            $pager = pagination($total, $pindex, $psize);
			
        } elseif ($operation == 'delete') {
            $id = intval($_GPC['id']);
            if (empty($id)) {
                $this->imessage('抱歉，本条信息不存在在或是已经被删除！', referer(), 'error');
            }
            pdo_delete($this->table_order, array('id' => $id));
            $this->imessage('删除成功！', referer(), 'success');
        } elseif ($operation == 'tuifei') {
            $id = intval($_GPC['id']);
            if (empty($id)) {
                $this->imessage('抱歉，本条信息不存在在或是已经被删除！');
            }
			$data = array('status' => 3); 
            pdo_update($this->table_order, $data, array('id' => $id));
            $this->imessage('删除成功！', referer(), 'success');
        } elseif ($operation == 'deleteall') {
            $rowcount = 0;
            $notrowcount = 0;
            foreach ($_GPC['idArr'] as $k => $id) {
                $id = intval($id);
                if (!empty($id)) {
                    $goods = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE id = :id", array(':id' => $id));
                    if (empty($goods)) {
                        $notrowcount++;
                        continue;
                    }
                    pdo_delete($this->table_order, array('id' => $id, 'weid' => $weid));
                    $rowcount++;
                }
            }			
			$message = "操作成功！共删除{$rowcount}条数据,{$notrowcount}条数据不能删除!";
			$data ['result'] = true;
			$data ['msg'] = $message;
			die (json_encode($data));
        }	
        include $this->template ( 'web/baoming' );
?>
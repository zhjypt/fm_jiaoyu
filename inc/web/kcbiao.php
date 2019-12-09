<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
global $_GPC, $_W;

$weid              = $_W['uniacid'];
$action            = 'kecheng';
$this1             = 'no2';
$GLOBALS['frames'] = $this->getNaveMenu($_GPC['schoolid'], $action);
$schoolid          = intval($_GPC['schoolid']);
$logo              = pdo_fetch("SELECT logo,title,is_kb FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}'");

/** 学期? */
$xueqi = pdo_fetchall("SELECT sid,sname FROM " . tablename($this->table_classify) . " where weid = :weid AND schoolid = :schoolid And type = :type ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'semester', ':schoolid' => $schoolid));

/** 科目? */
$km = pdo_fetchall("SELECT sid,sname FROM " . tablename($this->table_classify) . " where weid = :weid AND schoolid = :schoolid And type = :type ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'subject', ':schoolid' => $schoolid));

/** 班级? */
$bj = pdo_fetchall("SELECT sid,sname FROM " . tablename($this->table_classify) . " where weid = :weid AND schoolid = :schoolid And type = :type ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'theclass', ':schoolid' => $schoolid));

/** 星期? */
$xq = pdo_fetchall("SELECT sid,sname FROM " . tablename($this->table_classify) . " where weid = :weid AND schoolid = :schoolid And type = :type ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'week', ':schoolid' => $schoolid));

/** 时段? */
$sd = pdo_fetchall("SELECT sid,sname FROM " . tablename($this->table_classify) . " where weid = :weid AND schoolid = :schoolid And type = :type ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'timeframe', ':schoolid' => $schoolid));

/**教室**/
$js = pdo_fetchall("SELECT sid,sname FROM " . tablename($this->table_classify) . " where weid = :weid AND schoolid = :schoolid And type = :type ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'addr', ':schoolid' => $schoolid));
/** 期号 */
$qh = pdo_fetchall("SELECT sid,sname FROM " . tablename($this->table_classify) . " where weid = :weid AND schoolid = :schoolid And type = :type ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'score', ':schoolid' => $schoolid));

$allkc = pdo_fetchall("SELECT id,name FROM " . tablename($this->table_tcourse) . " WHERE  weid = :weid AND schoolid = :schoolid ORDER BY  CONVERT(name USING gbk)  ASC", array(':weid' => $weid, ':schoolid' => $schoolid));
$category = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " WHERE weid = :weid AND schoolid = :schoolid ORDER BY sid ASC, ssort DESC", array(':weid' => $weid, ':schoolid' => $schoolid), 'sid');

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$tid_global = $_W['tid'];
if($tid_global !='founder' && $tid_global != 'owner'){
		$loginTeaFzid =  pdo_fetch("SELECT fz_id FROM " . tablename ($this->table_teachers) . " where weid = :weid And schoolid = :schoolid And id =:id ", array(':weid' => $weid,':schoolid' => $schoolid,':id'=>$tid_global));
		$qxarr = GetQxByFz($loginTeaFzid['fz_id'],1,$schoolid);
}
if($operation == 'post'){
 	if (!(IsHasQx($tid_global,1000922,1,$schoolid))){
		$this->imessage('非法访问，您无权操作该页面','','error');	
	}
  	$addr =  pdo_fetchall("SELECT sid,sname FROM " . tablename($this->table_classify) . " where weid = '{$weid}' AND schoolid ='{$schoolid}' AND type='addr' ORDER BY ssort DESC ");
    load()->func('tpl');
    $id = intval($_GPC['id']);
    if(!empty($id)){
        $item     = pdo_fetch("SELECT * FROM " . tablename($this->table_kcbiao) . " WHERE id = :id ", array(':id' => $id));
        $kc       = pdo_fetch("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE id = :id ", array(':id' => $item['kcid']));
        $teachers = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " WHERE id = :id ", array(':id' => $item['tid']));	
         $tidarray = explode(',', $kc['tid']);
                foreach( $tidarray as $key => $value )
                {
                	$allteacher[$key] = pdo_fetch("SELECT id,tname FROM " . tablename ($this->table_teachers) . " where id = :id ", array(':id' => $value));		
                }
        
        if(empty($item)){
            $this->imessage('抱歉，本条信息不存在在或是已经删除！', '', 'error');
        }
    }
    if(checksubmit('submit')){
	    if(empty($_GPC['sktid'])){
		     $this->imessage('授课老师不能为空！', '', 'referer');
	    }
	    $sdinfo = pdo_fetch("SELECT sd_start FROM " . tablename ($this->table_classify) . " where sid = :sid ", array(':sid' => $_GPC['sd']));
		$lasttime =$_GPC['date'].date(" H:i",$sdinfo['sd_start']);
		$check_start = strtotime($_GPC['date'].date(" H:i",$sdinfo['sd_start']));
		$check_end   = strtotime($_GPC['date'].date(" H:i",$sdinfo['sd_end']));
		$check =  pdo_fetch("SELECT id FROM " . tablename ($this->table_kcbiao) . " where addr_id=:addr_id AND date>=:start AND date<= :end ", array(':addr_id' => $_GPC['skaddr'],':start'=>$check_start,':end'=>$check_end));
        $data = array(
            'weid'        => $weid,
            'schoolid'    => $schoolid,
            'tid'         => intval($_GPC['sktid']),
            'kcid'        => trim($_GPC['kcid']),
            'bj_id'       => trim($_GPC['bj_id']),
            'km_id'       => trim($_GPC['km_id']),
            'sd_id'       => trim($_GPC['sd']),
            'xq_id'       => trim($_GPC['xq']),
            'nub'         => trim($_GPC['nub']),
            'isxiangqing' => trim($_GPC['isxiangqing']),
            'content'     => trim($_GPC['content']),
            'date'        => strtotime($lasttime),
        );

        if(empty($id)){
            $this->imessage('抱歉，本课时不存在在或是已经删除！', '', 'error');
        }else{
	        if(!empty($check)){
				$this->imessage('抱歉，本课时与其他课时冲突！', '', 'error');
			}else{	
				pdo_update($this->table_kcbiao, $data, array('id' => $id));
				 $this->imessage('修改成功！', $this->createWebUrl('kcbiao', array('op' => 'display', 'schoolid' => $schoolid)), 'success');
			}
            
        }
       
    }
}elseif($operation == 'display'){
	if (!(IsHasQx($tid_global,1000921,1,$schoolid))){
		$this->imessage('非法访问，您无权操作该页面','','error');	
	}
    $pindex    = max(1, intval($_GPC['page']));
    $psize     = 20;
    $condition = '';
    $time = time();
	$is_start = !empty($_GPC['is_start'])?$_GPC['is_start'] : -1 ;
	switch ( $is_start )
	{
		case -1 :
			break;
		case 1 :
			$condition .= "AND date > {$time}";
			break;
		case 2 :
			$condition .= "AND date <= {$time} ";
			break;	
		default:
			break;
	}
    if(!empty($_GPC['name'])){
        $condition .= " AND id LIKE '%{$_GPC['name']}%' ";
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
	        $condition .= " AND kcid in ({$kcid_str}) ";
        }
        else{
	         $condition .= " AND kcid =0 ";
        }
    }
   
	if (!empty($_GPC['tname'])) {
	            $tname = trim($_GPC['tname']);
	            $tid = pdo_fetch("SELECT id FROM " . tablename ($this->table_teachers) . " where weid='{$weid}' AND schoolid='{$schoolid}' AND tname ='{$tname}'");
                $condition .= "AND tid ='{$tid['id']}' ";
            }
    if(!empty($_GPC['kc_id'])){
        $kcid       = intval($_GPC['kc_id']);
        $condition .= " AND kcid = '{$kcid}'";
    }

    if(!empty($_GPC['km_id'])){
        $cid       = intval($_GPC['km_id']);
        $condition .= " AND km_id = '{$cid}'";
    }
    if(!empty($_GPC['js_id'])){
        $jsid       = intval($_GPC['js_id']);
        $condition .= " AND addr = '{$jsid}'";
    }

    if(!empty($_GPC['xq_id'])){
        $cid       = intval($_GPC['xq_id']);
        $condition .= " AND xq_id = '{$cid}'";
    }

    if(!empty($_GPC['sd_id'])){
        $cid       = intval($_GPC['sd_id']);
        $condition .= " AND sd_id = '{$cid}'";
    }

    if(!empty($_GPC['kstime'])) {
	   // if()
				$starttime = strtotime($_GPC['kstime']['start']);
				$endtime = strtotime($_GPC['kstime']['end']) + 86399;
				if($starttime >0 && $endtime>0 )
				{
					$condition .= " AND date > '{$starttime}' AND date < '{$endtime}'";
				}
				
			} else {
				//$starttime = strtotime('-600 day');
				//$endtime = TIMESTAMP;
			}
    /** 课程表? */
    //var_dump($condition);
  //  die();
  if(!empty($_GPC['kcName'])){
  		$kcFromKeId = pdo_fetchcolumn("SELECT id FROM " . tablename($this->table_tcourse) . " WHERE weid='{$weid}' AND schoolid='{$schoolid}' and name = '{$_GPC['kcName']}' ");
  		$condition .= "AND kcid = {$kcFromKeId}";
  }
    $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_kcbiao) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' $condition ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
    foreach($list as $key => $row){
        $teacher = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where id = :id ", array(':id' => $row['tid']));
        $kc      = pdo_fetch("SELECT * FROM " . tablename($this->table_tcourse) . " where id = :id ", array(':id' => $row['kcid']));
        $bmstu   = pdo_fetchcolumn("SELECT COUNT(distinct sid ) FROM " . tablename($this->table_order) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND type = 1 AND status = 2 and kcid = '{$row['kcid']}' AND sid != 0  " );
        $signstu = pdo_fetchcolumn("SELECT COUNT(distinct sid ) FROM " . tablename($this->table_kcsign) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}'  AND status = 2 and kcid = '{$row['kcid']}' and ksid = '{$row['id']}' AND sid != 0   " );
         $leavetu = pdo_fetchcolumn("SELECT COUNT(distinct sid ) FROM " . tablename($this->table_kcsign) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}'  AND status = 3 and kcid = '{$row['kcid']}' and ksid = '{$row['id']}' AND sid != 0   " );
         $signtid = pdo_fetch("SELECT tid FROM " . tablename($this->table_kcsign) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}'  AND status = 2 and kcid = '{$row['kcid']}' and ksid = '{$row['id']}' AND sid = 0  AND tid != 0  " );
         $teaSign = pdo_fetch("SELECT tname FROM " . tablename($this->table_teachers) . " where id = :id ", array(':id' => $signtid['tid']));
     	$weekarray=array("星期日","星期一","星期二","星期三","星期四","星期五","星期六"); //先定义一个数组
//echo "星期".$weekarray[date("w")];
        $list[$key]['tname']  = $teacher['tname'];
        $list[$key]['kcname'] = $kc['name'];
        $list[$key]['adrr']   = $kc['adrr'];
     	$list[$key]['unsign']  = $bmstu - $signstu ;
     	$list[$key]['signstu']  = $signstu?$signstu:0;
     	$list[$key]['leavetu']  = $leavetu?$leavetu:0;
     	$list[$key]['teaSign']  = $teaSign['tname'];
     	$list[$key]['week'] = $weekarray[date("w",$row['date'])];
    }
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_kcbiao) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' $condition");

    $pager = pagination($total, $pindex, $psize);
}elseif($operation == 'delete'){
    $id = intval($_GPC['id']);
    if(empty($id)){
        $this->imessage('抱歉，本条信息不存在在或是已经被删除！');
    }
    pdo_delete($this->table_kcbiao, array('id' => $id));
    $this->imessage('删除成功！', referer(), 'success');
}elseif($operation == 'remind'){
    $id = intval($_GPC['id']);
    $schoolid = intval($_GPC['schoolid']);
    $weid = intval($_GPC['weid']);
    if(empty($id)){
        $this->imessage('抱歉，本条信息不存在在或是已经被删除！');
    }
   	pdo_update($this->table_kcbiao,array('is_remind'=>1),array('id'=>$id));
  	$this->sendMobileJssktx($id,$schoolid,$weid);
    $message = "提醒老师授课成功!";
	$data ['result'] = true;
	$data ['msg'] = $message;
	die (json_encode($data));
}elseif($operation == 'remindall'){
    $rowcount    = 0;
    $notrowcount = 0;
    foreach($_GPC['idArr'] as $k => $id){
        $id = intval($id);
        if(!empty($id)){
            $hasRemind = pdo_fetch("SELECT is_remind FROM " . tablename($this->table_kcbiao) . " WHERE id = :id", array(':id' => $id));
            if($hasRemind['is_remind'] ==1){
                $notrowcount++;
            }elseif($hasRemind['is_remind'] ==0){
	            $rowcount++;
             	pdo_update($this->table_kcbiao,array('is_remind'=>1),array('id'=>$id));
  				$this->sendMobileJssktx($id,$schoolid,$weid);
            }
        }
    }
	$message = "操作成功！共提醒{$rowcount}课时,{$notrowcount}课时无法重复提醒!";
	$data ['result'] = true;
	$data ['msg'] = $message;
	die (json_encode($data));
}elseif($operation == 'deleteall'){
    $rowcount    = 0;
    $notrowcount = 0;
    foreach($_GPC['idArr'] as $k => $id){
        $id = intval($id);
        if(!empty($id)){
            $goods = pdo_fetch("SELECT * FROM " . tablename($this->table_kcbiao) . " WHERE id = :id", array(':id' => $id));
            if(empty($goods)){
                $notrowcount++;
                continue;
            }
            pdo_delete($this->table_kcbiao, array('id' => $id, 'weid' => $weid));
            $rowcount++;
        }
    }
	$message = "操作成功！共删除{$rowcount}条数据,{$notrowcount}条数据不能删除!";
	$data ['result'] = true;
	$data ['msg'] = $message;
	die (json_encode($data));	
}
include $this->template('web/kcbiao');
?>
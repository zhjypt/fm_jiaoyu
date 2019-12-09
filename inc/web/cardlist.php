<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
global $_GPC, $_W;

$weid              = $_W['uniacid'];
$action            = 'cardlist';
$this1             = 'no5';
$GLOBALS['frames'] = $this->getNaveMenu($_GPC['schoolid'], $action);
$schoolid          = intval($_GPC['schoolid']);
$schooltype        = $_W['schooltype'];
$logo              = pdo_fetch("SELECT logo,title,spic,tpic,is_cardlist,is_cardpay,cardset FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}'");
$nj = pdo_fetchall("SELECT sid,sname FROM " . tablename($this->table_classify) . " where weid = :weid And schoolid = :schoolid And type = :type ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'semester', ':schoolid' => $schoolid));
$bj = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = :weid AND schoolid = :schoolid And type = :type ORDER BY ssort DESC", array(':weid' => $weid, ':type' => 'theclass', ':schoolid' => $schoolid));
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$tid_global = $_W['tid'];
if($operation == 'post'){
	if (!(IsHasQx($tid_global,1002502,1,$schoolid))){
		$this->imessage('非法访问，您无权操作该页面','','error');	
	}
    load()->func('tpl');
    $id = intval($_GPC['id']);
	$card = empty($_GPC['idcard_sd'])? trim($_GPC['idcard_kk']) : trim($_GPC['idcard_sd']);
    if(!empty($id)){
        $item    = pdo_fetch("SELECT * FROM " . tablename($this->table_idcard) . " WHERE id = :id", array(':id' => $id));
        $student = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where id = :id ", array(':id' => $item['sid']));
        $teacher = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where id = :id ", array(':id' => $item['tid']));
        $bj      = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " where sid = :sid ", array(':sid' => $item['bj_id']));
		$allcard_no    = pdo_fetchall("SELECT * FROM " . tablename($this->table_idcard) . " WHERE schoolid = :schoolid And sid = :sid And tid =:tid", array(':schoolid' => $schoolid,':sid' => '0',':tid' => '0'));
        if(empty($item)){
            $this->imessage('抱歉，本条信息不存在在或是已经删除！', '', 'error');
        }
    }
    if(checksubmit('submit')){
        $lastedittime = time();
        $data = array(
			'idcard' => trim($card),
            'severend' => strtotime($_GPC['severend']),
            'lastedittime'=> $lastedittime,
        );
		if(!empty($id)){
			if($_GPC['idcard_sd']){
				if($_GPC['idcard_sd'] != $item['idcard']){
					$idcard = pdo_fetch("SELECT * FROM " . tablename($this->table_idcard) . " WHERE idcard = :idcard And schoolid = :schoolid", array(':idcard' => trim($_GPC['idcard_sd']),':schoolid' => $schoolid));
					if($idcard){
						$this->imessage('抱歉，本卡号已经存在,请检查卡库！', '', 'error');
					}
				}
			}
		}
        if(empty($id)){
            $this->imessage('抱歉，本条信息不存在在或是已经删除！', '', 'error');
        }else{
            pdo_update($this->table_idcard, $data, array('id' => $id));
            if(is_showZB()){
                CreateHBtodo_ZB($schoolid,$weid,$lastedittime,14);
            }

        }
        $this->imessage('修改成功！', $this->createWebUrl('cardlist', array('op' => 'display', 'schoolid' => $schoolid)), 'success');
    }
}elseif($operation == 'display'){
	if (!(IsHasQx($tid_global,1002501,1,$schoolid))){
		$this->imessage('非法访问，您无权操作该页面','','error');	
	}
    $pindex    = max(1, intval($_GPC['page']));
    $psize     = 30;
    $condition = '';

    if(!empty($_GPC['idcard'])){
        $cid       = $_GPC['idcard'];
        $condition .= " AND idcard LIKE '%{$cid}%'";
    }
	
    if(!empty($_GPC['s_name'])){
        $s_name       = trim($_GPC['s_name']);
		if($_GPC['bj_id'] != 0){
			$student = pdo_fetch("SELECT id FROM " . tablename($this->table_students) . " where schoolid = '{$schoolid}' And bj_id = '{$_GPC['bj_id']}' And s_name = '{$s_name}' ");
		}else{
			$this->imessage('抱歉，精确搜索学生请选择班级！', '', 'error');
		}
		if($student){
			$condition   .= " AND sid = '{$student['id']}' ";
		}
    }

    if(!empty($_GPC['tname'])){
        $tname       = trim($_GPC['tname']);
		$condition   .= " AND pname LIKE '%{$tname}%'";
    }	

    if(!empty($_GPC['bj_id']) && empty($_GPC['s_name'])){
        $bj_id     = intval($_GPC['bj_id']);
        $condition .= " AND bj_id = '{$bj_id}'";
    }

    if($_GPC['type'] == 1){
        $condition .= " AND sid >= 1";
    }
    if($_GPC['type'] == 2){
        $condition .= " AND tid >= 1";
    }
    if($_GPC['type'] == 3){
        $condition .= " AND sid < 1 AND tid < 1";
    }
    $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_idcard) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' $condition ORDER BY sid DESC, tid DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
    foreach($list as $key => $row){
        $student              = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where id = :id ", array(':id' => $row['sid']));
        $teacher              = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where id = :id ", array(':id' => $row['tid']));
        
        $jxlog                = pdo_fetchall("SELECT * FROM " . tablename($this->table_checklog) . " where cardid = :cardid", array(':cardid' => $row['idcard']));
        $list[$key]['s_name'] = $student['s_name'];
		$list[$key]['sicon'] = !empty($student['icon'])?tomedia($student['icon']):tomedia($logo['spic']);
		$list[$key]['ticon'] = !empty($teacher['thumb'])?tomedia($teacher['thumb']):tomedia($logo['tpic']);
        $list[$key]['scardicon']  = tomedia($row['spic']);
		$list[$key]['tcardicon']  = tomedia($row['tpic']);
		$list[$key]['tname']  = $teacher['tname'];
		if($schooltype){
			$bjlist  = pdo_fetch("SELECT name  FROM " . tablename($this->table_tcourse) . " where id = :id ", array(':id' => $row['bj_id']));
			$list[$key]['bjname'] = $bjlist['name'];
		}else{
			$bjlist               = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " where sid = :sid ", array(':sid' => $row['bj_id']));
			$list[$key]['bjname'] = $bjlist['sname'];
		}
        
        $list[$key]['num']    = count($jxlog);
    }
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_idcard) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' $condition");
    $pager = pagination($total, $pindex, $psize);
	$xskshl = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_idcard) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}'  AND sid != 0 ");	
	$jskshl = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_idcard) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}'  AND tid != 0 ");
	$kksm = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_idcard) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}'  AND tid = 0 AND sid = 0");
	//////////导出空卡/////////////////
	if($_GPC['out_put'] == 'out_put'){
		$listss = pdo_fetchall("SELECT * FROM " . tablename($this->table_idcard) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' And sid = 0 And tid = 0 ORDER BY id DESC");
		if($listss){
			$ii   = 0;
			foreach($listss as $index => $row){
				$arr[$ii]['idcard'] = 's'.$row['idcard'];
				$ii++;
			}
			$this->exportexcel($arr, array('卡号'), '空卡');
			exit();
		}else{
			$this->imessage('抱歉，本校无可用空卡号,请联系管理添加！');
		}
	}
}elseif($operation == 'recording'){
	mload()->model('tea');
	$schoolid = $_GPC['schoolid'];
	$allbjlist = GetAllClassInfoByTid($schoolid,2,$schooltype,$_W['tid']);
	$allguanxi = getallpardset();
	$bj_id = $_GPC['bj_id'];
	if($bj_id){
		if($schooltype){
			$nowbj = pdo_fetch("SELECT name as sname FROM " . tablename($this->table_tcourse) . " where id = :id ", array(':id' => $bj_id));
			$thisKcStu = pdo_fetchall("SELECT distinct sid FROM " . tablename($this->table_order) . " where schoolid = '{$schoolid}' And kcid = '{$bj_id}' And type=1  And sid != 0 ORDER BY id DESC ");
			//var_dump($thisKcStu);
			$bjallrs = count($thisKcStu);
			$Stu_str_temp = '';
			foreach($thisKcStu as $u){
				$Stu_str_temp .=$u['sid'].",";
			}
			$stu_str = trim($Stu_str_temp,",");
			
			$stulist = pdo_fetchall("SELECT id,s_name,icon FROM " . tablename($this->table_students) . " where schoolid = '{$schoolid}' And FIND_IN_SET(id,'{$stu_str}') ORDER BY id DESC  ");
			foreach($stulist as $key => $row){
				$stulist[$key]['icon'] = !empty($row['icon'])?tomedia($row['icon']):tomedia($logo['spic']);
				$cards = pdo_fetchall('SELECT idcard,severend,pard,pname FROM ' . tablename($this->table_idcard) . " WHERE sid = '{$row['id']}' AND schoolid = '{$schoolid}' ");
				$stulist[$key]['hascards'] = 1;
				if($cards){
					$stulist[$key]['cards'] = $cards;
					$stulist[$key]['hascards'] = 2;
				}
				
			}
		}else{
			$nowbj  = pdo_fetch("SELECT sid,sname FROM " . tablename($this->table_classify) . " where sid = :sid ", array(':sid' => $bj_id));
			$stulist = pdo_fetchall("SELECT id,s_name,icon,schoolid FROM " . tablename($this->table_students) . " where schoolid = '{$schoolid}' And bj_id = '{$nowbj['sid']}' "); 
			foreach($stulist as $key => $row){
				$stulist[$key]['icon'] = !empty($row['icon'])?tomedia($row['icon']):tomedia($logo['spic']);
				$cards = pdo_fetchall('SELECT idcard,severend,pard,pname FROM ' . tablename($this->table_idcard) . " WHERE sid = '{$row['id']}' AND schoolid = '{$schoolid}' ");
				$stulist[$key]['hascards'] = 1;
				if($cards){
					$stulist[$key]['cards'] = $cards;
					$stulist[$key]['hascards'] = 2;
				}
				
			}
		}
		$stulist = array_sorts($stulist,'hascards','asc');
	}
}elseif($operation == 'getcardinfo'){
    $lastedittime = time();
	$sid = $_GPC['sid'];
	$bj_id = $_GPC['bj_id'];
	$idcard = $_GPC['idcard'];
	$pard = intval($_GPC['pard']);
	$pname = $_GPC['pname'];
	$now = date('Y-m-d',time());
	$severend = strtotime($_GPC['severend']);
	$checkcard = pdo_fetch("SELECT * FROM " . tablename($this->table_idcard) . " WHERE schoolid = :schoolid And idcard = :idcard", array(':schoolid' => $schoolid,':idcard' => $idcard));
	if($logo['is_cardlist'] == 1){
		if($_W['tid'] != "founder" && $_W['tid'] != "owner"){
			if(!empty($checkcard)){
				if(!empty($checkcard['pard'])){
					$data['msg'] = "抱歉，本卡已绑定其他用户";
					$data['result'] = false;
					die (json_encode($data));
					exit;
				}
			}else{
				$data['msg'] = "本卡不是有效卡，请联系管理员索取有效卡";
				$data['result'] = false;
				die (json_encode($data));
				exit;
			}
		}else{
			if(!empty($checkcard['sid']) || !empty($checkcard['tid'])){
				$data['msg'] = "抱歉，本卡已绑定其他用户";
				$data['result'] = false;
				die (json_encode($data));
				exit;
			}
		}
	}
	if(!empty($checkcard['pard'])){
		$data['msg'] = "抱歉，本卡已绑定其他用户";
		$data['result'] = false;
		die (json_encode($data));
		exit;
	}
	$checkpard = pdo_fetch("SELECT * FROM " . tablename($this->table_idcard) . " WHERE :schoolid = schoolid And :sid = sid And :pard = pard", array(
		':schoolid' => $schoolid,
		':sid' => $sid,
		':pard' => $pard
	));		
	if (!empty($checkpard)) {
			$data['msg'] = "你选择的关系已经绑定其他卡！";
			$data['result'] = false;
			die (json_encode($data));
			exit;
	}
	if($logo['is_cardpay'] == 1){
		$card = unserialize($logo['cardset']);
		if($card['cardtime'] == 1){
			if($checkcard['is_frist'] == 1 || empty($checkcard)){
				$severend = $card['endtime1'] * 86400 + time();
			}else{
				$severend = time();
			}
		}else{
			$severend = $card['endtime2'];
		}
	}else{
		if($checkcard['severend']){
			$severend = $checkcard['severend'];
		}else{
			if($now == $_GPC['severend']){
				$data['msg'] = "抱歉，请在本页顶部选择到期日期";
				$data['result'] = false;
				die (json_encode($data));
				exit;
			}
		}
	}
	$temp = array(
		'weid' => $weid,
		'schoolid' => $schoolid,
		'idcard' => $idcard,
		'sid' => $sid,
		'bj_id' => $bj_id,
		'pname' => $pname,
		'pard' => $pard,
		'usertype' => 0,//学生
		'is_on' => 1,
		'createtime' => time(),
		'severend' => $severend,
        'lastedittime' => $lastedittime,
	);
	if(empty($checkcard['id'])){
		pdo_insert($this->table_idcard, $temp);
	}else{
		pdo_update($this->table_idcard, $temp, array('id' => $checkcard['id']));
	}
    if(is_showZB()) {
        CreateHBtodo_ZB($schoolid, $weid, $lastedittime, 14);
    }
	$data['msg'] = "录卡成功";
	$data['result'] = true;
	die (json_encode($data));
}elseif($operation == 'writecard'){
    $lastedittime = time();
	$idcard = $_GPC['idcard'];
	$severend = strtotime($_GPC['severend']);
	$now = date('Y-m-d',time());
	if($now == $_GPC['severend']){
		$data['msg'] = "抱歉，请在本页顶部设置到期日期";
		$data['result'] = false;
		die (json_encode($data));
		exit;
	}
	$checkcard = pdo_fetch("SELECT * FROM " . tablename($this->table_idcard) . " WHERE schoolid = :schoolid And idcard = :idcard", array(':schoolid' => $schoolid,':idcard' => $idcard));
	$temp = array(
		'weid' => $weid,
		'schoolid' => $schoolid,
		'idcard' => $idcard,
		'sid' => 0,
		'bj_id' => 0,
		'pname' => 0,
		'pard' => 0,
		'usertype' => 0,
		'is_on' => 0,
		'createtime' => 0,
		'severend' => $severend,
        'lastedittime' => $lastedittime,
	);
	if($checkcard){
		$data['msg'] = "录卡成功,清除旧卡信息成功";
		$data['result'] = true;
		pdo_update($this->table_idcard, $temp, array('id' => $checkcard['id']));
	}else{
		pdo_insert($this->table_idcard, $temp);
		$data['msg'] = "录卡成功";
		$data['result'] = true;
	}
	die (json_encode($data));
}elseif($operation == 'change_endtime'){
	$rowcount    = 0;
	$notrowcount = 0;
	$setendtime = strtotime($_GPC['setendtime']);			
	foreach($_GPC['idArr'] as $k => $id){
		$id = intval($id);
		$checkcard = pdo_fetch("SELECT * FROM " . tablename($this->table_idcard) . " WHERE id = :id", array(':id' => $id));
		if($checkcard){
			pdo_update($this->table_idcard, array('severend' => $setendtime), array('id' => $id));
			$rowcount++;
		}else{
			$notrowcount++;
			continue;			
		}
	}
	$data ['result'] = true;
	$data ['msg'] = "操作成功！共修改{$rowcount}张卡,{$notrowcount}张不能修改!";
	die (json_encode($data));
	exit;
}elseif($operation == 'jiebang'){
    $lastedittime = time();
    $id  = intval($_GPC['id']);
    $row = pdo_fetch("SELECT idcard FROM " . tablename($this->table_idcard) . " WHERE id = :id", array(':id' => $id));
    if(empty($row)){
        $this->imessage('抱歉，本卡不存在或是已经被删除！');
    }
    $temp = array(
        'sid'        => 0,
        'tid'        => 0,
        'pard'       => 0,
        'bj_id'      => 0,
        'usertype'   => 3,
        'createtime' => '',
        'pname'      => '',
        'severend'   => '',
        'spic'       => '',
        'tpic'       => '',
        'lastedittime' => $lastedittime,
        'cardtype'   => 1
    );
   // pdo_delete($this->table_checklog, array('cardid' => $row['idcard']));
    pdo_update($this->table_idcard, $temp, array('id' => $id));
    if(is_showZB()) {
        CreateHBtodo_ZB($schoolid, $weid, $lastedittime, 14);
    }
    $this->imessage('解绑成功！', referer(), 'success');
}elseif($operation == 'delete'){
    $id = intval($_GPC['id']);
    if(empty($id)){
        $this->imessage('抱歉，本条信息不存在在或是已经被删除！');
    }
    pdo_delete($this->table_idcard, array('id' => $id));
    $this->imessage('删除成功！', referer(), 'success');
}elseif($operation == 'deleteall'){
    $rowcount    = 0;
    $notrowcount = 0;
    foreach($_GPC['idArr'] as $k => $id){
        $id = intval($id);
        if(!empty($id)){
            $goods = pdo_fetch("SELECT * FROM " . tablename($this->table_idcard) . " WHERE id = :id", array(':id' => $id));
            if(empty($goods)){
                $notrowcount++;
                continue;
            }
            pdo_delete($this->table_idcard, array('id' => $id, 'weid' => $weid));
            $rowcount++;
        }
    }
    $data ['result'] = true;

    $data ['msg'] = "操作成功！共删除{$rowcount}条数据,{$notrowcount}条数据不能删除!";

    die (json_encode($data));	
}elseif($operation == 'bjcardbd'){
    $bj_id = $_GPC['bj_id'];
    $cardid = $_GPC['CardId'];
    $serverendtime = $_GPC['serverendtime'];
    $todaydate = date("Y-m-d");

    if(empty($bj_id)){
        $data ['result'] = false;
        $data ['msg'] = "操作失败，请选择班级！！";
        die (json_encode($data));
    }

    if(empty($cardid)){
        $data ['result'] = false;
        $data ['msg'] = "操作失败，请输入卡号！！";
        die (json_encode($data));
    }

    if($serverendtime == $todaydate){
        $data ['result'] = false;
        $data ['msg'] = "操作失败，请选择卡到期时间！！";
        die (json_encode($data));
    }

    $check_type = 0;
    $check = pdo_fetch("SELECT * FROM " . tablename($this->table_idcard) . " WHERE schoolid = :schoolid And idcard = :idcard", array(':schoolid' => $schoolid,':idcard' => $cardid));
    if(!empty($check)){
        if($check['sid'] != 0 || $check['tid'] != 0 || ($check['cardtype'] == 2 && $check['bj_id'] !=0)){
            $check_type = 1;//卡已绑定
        }
    }elseif(empty($check)){
        $check_bj = pdo_fetch("SELECT * FROM " . tablename($this->table_idcard) . " WHERE schoolid = :schoolid And bj_id = :bj_id and cardtype = :cardtype ", array(':schoolid' => $schoolid,':bj_id' => $bj_id,':cardtype'=>2));
        if(!empty($check_bj)){
            $check_type = 2;//班级已绑定
        }
    }

    if($check_type == 1){
        $data ['result'] = false;
        $data ['msg'] = "操作失败，当前卡已绑定，请绑定其他卡！！";
        die (json_encode($data));
    }elseif($check_type == 2){
        $data ['result'] = false;
        $data ['msg'] = "操作失败，当前班级已绑定，请绑定其他班级！！";
        die (json_encode($data));
    }else{

        if(!empty($check)){
            $data = array(
                'cardtype'=> 2,
                'bj_id' => $bj_id,
                'createtime'=>time(),
                'severend' => strtotime($serverendtime)
            );
            pdo_update($this->table_idcard,$data,array('id'=>$check['id']));
        }else{
            $data = array(
              'schoolid'=>$schoolid,
              'weid'=>$weid,
              'cardtype' => 2,
              'bj_id' => $bj_id,
              'idcard' =>$cardid,
              'createtime'=>time(),
              'severend' => strtotime($serverendtime)
            );
            pdo_insert($this->table_idcard,$data);
        }
        $data ['result'] = true;
        $data ['msg'] = "绑定成功！！";
        die (json_encode($data));


    }

}
include $this->template('web/cardlist');
?>
<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */global $_W, $_GPC;
   $operation = in_array ( $_GPC ['op'], array ('default','reciveConfirm', 'delmallorder', 'mallorder','sigeup','deleteclass','creatorder','xuefeiidcard','xufeiob','buyvod','xgks','chongzhi','buy_charge') ) ? $_GPC ['op'] : 'default';

     if ($operation == 'default') {
	           die ( json_encode ( array (
			         'result' => false,
			         'msg' => '参数错误'
	                ) ) );
              }			

	if ($operation == 'sigeup') {
		$data = explode ( '|', $_GPC ['json'] );
		if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	         }
		$shareuserid = $_GPC['shareuserid'];	   
        $setting = pdo_fetch("SELECT * FROM " . tablename($this->table_set) . " WHERE :weid = weid", array(':weid' => $_GPC['weid']));
		$school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " WHERE :id = id", array(':id' => $_GPC['schoolid']));
		$user = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " WHERE :weid = weid And :schoolid = schoolid And :id = id", array(':weid' => $_GPC['weid'], ':schoolid' => $_GPC['schoolid'], ':id' => $_GPC['user']));
		$student = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " WHERE :weid = weid And :schoolid = schoolid And :id = id", array(':weid' => $_GPC['weid'], ':schoolid' => $_GPC['schoolid'], ':id' => $_GPC['sid']));
	    
		$cose = pdo_fetch("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE :id = id", array(':id' => $_GPC['kcid'])); 

		$issale = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE :weid = weid And :schoolid = schoolid And :kcid = kcid And :sid = sid", array(':weid' => $_GPC['weid'], ':schoolid' => $_GPC['schoolid'], ':kcid' => $_GPC['kcid'], ':sid' => $_GPC['sid'])); 
		
		$userinfo = iunserializer($user['userinfo']);
		$yb = pdo_fetchcolumn("select count(*) FROM ".tablename('wx_school_order')." WHERE kcid = '".$cose['id']."' And (status = 2 or type = 2) ");
		$rest = $cose['minge'] - $yb;

		
		if (empty($userinfo['name'])) {
            die ( json_encode ( array (
                    'result' => false,
                    'msg' => '请前往个人中心完善您的联系方式'
		               ) ) );			
		}		
		
		if ($rest < 1){
            die ( json_encode ( array (
                    'result' => false,
                    'msg' => '本课程已满'
		               ) ) );			
		}
		
		if (!empty($issale)) {
            die ( json_encode ( array (
                    'result' => false,
                    'msg' => '抱歉,您已报名本课程,请查看订单'
		               ) ) );			
		}		
		
		if (time() >= $cose['end']) {
            die ( json_encode ( array (
                    'result' => false,
                    'msg' => '本课程已经结束'
		               ) ) );
		}		
		
		if (empty($_GPC['openid'])) {
            die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求'
		               ) ) );			

		}else{
			
			$schoolid = $_GPC['schoolid'];
			
			$weid = $_GPC['weid'];
			
			$sid = $_GPC['sid'];
			
			$userid = $_GPC['uid'];

			$orderid = "{$userid}{$sid}";
			$temp = array(
					'weid' =>  $_GPC ['weid'],
					'schoolid' => $_GPC ['schoolid'],
					'sid' => $_GPC ['sid'],
					'userid' => $_GPC ['user'],
					'type' => 1,
					'status' => 1,
					'kcid' => $_GPC ['kcid'],
					'uid' => $_GPC['uid'],
					'cose' => $cose['cose'],
					'payweid' => $cose['payweid'],
					'orderid' => $orderid,
					'createtime' => time(),
			);
			$temp['ksnum'] = $cose['FirstNum'];
			if(!empty($shareuserid)){
				$ShareUserInfo = pdo_fetch("SELECT sid FROM " . tablename($this->table_user) . " WHERE :weid = weid And :schoolid = schoolid And :id = id", array(':weid' => $_GPC['weid'], ':schoolid' => $_GPC['schoolid'], ':id' => $shareuserid));
				if($ShareUserInfo['sid'] != $_GPC['sid']){
					$temp['shareuserid'] = $shareuserid;
				}
				
			}
			pdo_insert($this->table_order, $temp);
   
			$order_id = pdo_insertid();
						
			$data ['result'] = true;
			
			$data ['msg'] = '报名成功,请前往个人中心查看';

		 die ( json_encode ( $data ) );
		}
    }

	if ($operation == 'deleteclass') {
		$data = explode ( '|', $_GPC ['json'] );
		if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	         }
						 		 		 			  				  
		if (empty($_GPC['openid'])) {
                  die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
		}else{
			
			pdo_delete($this->table_order, array('id' => $_GPC['kcid']));	
   			
			$data ['result'] = true;
			
			$data ['msg'] = '删除成功！';	
			
          die ( json_encode ( $data ) );
		  
		}
    }
	if ($operation == 'creatorder') {
		$data = explode ( '|', $_GPC ['json'] );
				
		$od1 = $_GPC ['od1'];
		$od2 = $_GPC ['od2'];
		$od3 = $_GPC ['od3'];
		$od4 = $_GPC ['od4'];
		$od5 = $_GPC ['od5'];
		$kc1 = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " where weid = :weid AND schoolid=:schoolid AND id=:id ", array(':weid' => $_GPC ['weid'], ':schoolid' => $_GPC ['schoolid'], ':id' => $od1)); 
        $kc2 = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " where weid = :weid AND schoolid=:schoolid AND id=:id ", array(':weid' => $_GPC ['weid'], ':schoolid' => $_GPC ['schoolid'], ':id' => $od2));			
		$kc3 = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " where weid = :weid AND schoolid=:schoolid AND id=:id ", array(':weid' => $_GPC ['weid'], ':schoolid' => $_GPC ['schoolid'], ':id' => $od3));			
		$kc4 = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " where weid = :weid AND schoolid=:schoolid AND id=:id ", array(':weid' => $_GPC ['weid'], ':schoolid' => $_GPC ['schoolid'], ':id' => $od4));
		$kc5 = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " where weid = :weid AND schoolid=:schoolid AND id=:id ", array(':weid' => $_GPC ['weid'], ':schoolid' => $_GPC ['schoolid'], ':id' => $od5));
        $cose = $kc1['cose'] + $kc2['cose'] + $kc3['cose'] + $kc4['cose'] + $kc5['cose'];
		
		$temp = array(
		   'weid' => $_GPC ['weid'],
           'schoolid' => $_GPC ['schoolid'],	   
		   'od1' => $od1,
		   'od2' => $od2,
		   'od3' => $od3,
		   'od4' => $od4,
		   'od5' => $od5,
		   'payweid' => $kc1['payweid'],
		   'cose' => $cose,
		   'status'=>1
		);

		pdo_insert($this->table_wxpay, $temp);
			
		$wxpay_id = pdo_insertid();	
   			
		$data ['result'] = true;
		$url = $this->createMobileUrl('pay', array('schoolid' => $_GPC['schoolid'], 'cose' => $cose, 'wxpay' => $wxpay_id));
		$data ['msg'] = $url;
						
        die ( json_encode ( $data ) );
    }
	
	if ($operation == 'xuefeiidcard')  {
		$item = pdo_fetch("SELECT * FROM " . tablename($this->table_idcard) . " WHERE :id = id", array(':id' => $_GPC['id']));
		if (empty($item)) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '无此卡！' 
		               ) ) );
	    }else{
			$checkold = $order = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE bdcardid = '{$_GPC['id']}' And schoolid = '{$_GPC['schoolid']}' And sid = '{$_GPC['sid']}' And status = 1 And type = 5 ");
			if(!$checkold){
				$school = pdo_fetch("SELECT cardset FROM " . tablename($this->table_index) . " WHERE id = :id ", array(':id' => $_GPC['schoolid']));
				$card = unserialize($school['cardset']);
				$temp1 = array(
								'weid' =>  $_GPC['weid'],
								'schoolid' => $_GPC['schoolid'],
								'type' => 5,
								'status' => 1,
								'uid' => $_GPC['uid'],
								'userid' => $_GPC['userid'],
								'sid' => $_GPC['sid'],
								'cose' => $card['cardcost'],
								'payweid' => $card['payweid'],
								'orderid' => time(),
								'bdcardid' => $_GPC['id'],
								'createtime' => time(),
							);
				pdo_insert($this->table_order, $temp1);
				$order_id = pdo_insertid();
				$url = $this->createMobileUrl('gopay', array('schoolid' => $_GPC['schoolid'], 'orderid' => $order_id));
				
				$data ['result'] = true;
				$data ['msg'] = $url;
			}else{
				$data ['result'] = false;
				$data ['msg'] = "抱歉,本卡续费订单您已创建,请前往订单中心完成支付";				
			}
          die ( json_encode ( $data ) );
		  
		}
    }
	if ($operation == 'xufeiob')  {
		$item = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE :lastorderid = lastorderid ", array(':lastorderid' => $_GPC['id']));
		if ($item['status'] == 1){
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '本项目您尚有未续费订单，请点击待缴费菜单支付！' 
		               ) ) );			
		}
		if (empty($_GPC['openid'])) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '您无权操作！' 
		               ) ) );
	    }else{
			$order = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE :id = id ", array(':id' => $_GPC['id']));
			$orderid = "{$order['uid']}{$order['sid']}";
			$date = array(
				'weid' =>  $_GPC['weid'],
				'schoolid' => $_GPC['schoolid'],
				'sid' => $order['sid'],
				'userid' => $order['userid'],
				'type' => 3,
				'status' => 1,
				'obid' => $order['obid'],
				'costid' => $order['costid'],
				'uid' => $order['uid'],
				'cose' => $order['cose'],
				'orderid' => $orderid,
				'lastorderid' => $_GPC['id'],
				'payweid' => $order['payweid'],
				'createtime' => time(),
			);
			pdo_update($this->table_order, array('xufeitype' => 1), array('id' => $order['id']));	
			pdo_insert($this->table_order, $date);
			$order_id = pdo_insertid();
			$url = $this->createMobileUrl('gopay', array('schoolid' => $_GPC['schoolid'], 'orderid' => $order_id));
			
			$data ['result'] = true;
			$data ['msg'] = $url;
			
          die ( json_encode ( $data ) );
		  
		}
    }
   if ($operation == 'mallorder')  {
		$weid      = $_GPC['weid'];
		$schoolid  = $_GPC['schoolid'];
		$GoodId    = $_GPC['GoodId'];
		$GoodPoint = intval($_GPC['GoodPoint']);
		$GoodPrice = $_GPC['GoodPrice'];
		$AddId     = $_GPC['AddId'];
		$userid    = $_GPC['userid'];
		$NumOfGood = intval($_GPC['NumOfGood']);
		$beizhu    = trim( $_GPC['beizhu']);
		$allPrice  = $GoodPrice * $NumOfGood;
		$allpoint  = $GoodPoint * $NumOfGood;
		$qhtype    = $_GPC['qhtype'];
		$tid       = $_GPC['tid'];
		if(!empty($_GPC['sid'])){
			$sid   = $_GPC['sid'];
		}else{
			$sid   = 0 ;
		}
		$mallinfo = pdo_fetch("SELECT mallsetinfo,Is_point FROM " . tablename($this->table_index) . " WHERE :schoolid = id AND weid=:weid ", array(':schoolid' => $schoolid,':weid'=>$weid ));
		
		$qty = pdo_fetch("SELECT qty,cop,points FROM " . tablename($this->table_mall) . " WHERE :id = id ", array(':id' => $GoodId));
		
		
		$TTaddress = pdo_fetch("SELECT * FROM " . tablename($this->table_address) . " WHERE :id = id ", array(':id' => $AddId));
		$tadd = $TTaddress['province'].$TTaddress['city'].$TTaddress['county'].$TTaddress['address'];
		if($qhtype == 1){
			$tadd = $TTaddress['province'].$TTaddress['city'].$TTaddress['county'].$TTaddress['address'];
		}elseif($qhtype == 2){
			$tadd = "到校自取";
		}
		
if ($qty['qty'] < $NumOfGood ) {
              
			$data ['result'] = true;
			$data ['info'] = "下单失败,商品库存不足";
		 die ( json_encode ( $data ) );
	    }else{
		$temp = array(
		'weid'      => $weid,
		'schoolid'  => $schoolid,
		'tid'       => $tid,
		'sid'       => $sid,
		'goodsid'   => $GoodId,
		'allcash'   => $allPrice,
		'allpoint'  => $allpoint,
		'count'     => $NumOfGood,
		'cop'       => $qty['cop'],
		'addressid' => $AddId,
		'tname'     => $TTaddress['name'],
		'tphone'    => $TTaddress['phone'],
		'taddress'  => $tadd,
		'beizhu'    => $beizhu,
		'createtime' => time(),
		'status'    => 1
		);
		if(!empty($sid))
		{
			$temp['userid'] = $userid;
			if($mallinfo['Is_point'] != 1){
				$temp['allpoint'] = 0;
			}
			
		}
		$uid = $_GPC['uid'];
	
		$mallinfoDE = iunserializer($mallinfo['mallsetinfo']);
		
		$payweid = $mallinfoDE['payweid'];
		pdo_insert($this->table_mallorder,$temp);
		$morderid = pdo_insertid();
		//var_dump($temp);

		$orderTemp = array(
		'weid' => $weid,
		'schoolid' => $schoolid,
		'uid'  => $uid,
		'userid' => $userid,
		'cose' => $allPrice,
		'status' => 1,
		'type' => 6,
		'createtime' => time(),
		'morderid' => $morderid,
		'payweid' => $payweid
		);
		pdo_insert($this->table_order,$orderTemp);
		$Torderid = pdo_insertid();
		pdo_update($this->table_mallorder, array('torderid' => $Torderid), array('id' =>$morderid));
		$data ['result'] = true;
		$data ['info'] = "创建订单成功";
		 die ( json_encode ( $data ) );

	  } 
   }
     if ($operation == 'delmallorder'){
	     $morderid = $_GPC['morderid'];
	     $orderid = $_GPC['orderid'];
	     $mtemp = pdo_fetch("SELECT * FROM " . tablename($this->table_mallorder) . " WHERE id=:id ", array(':id'=>$morderid ));
	     $otemp = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE id=:id ", array(':id'=>$orderid ));
	     if(!empty($mtemp) && !empty($otemp) )
	     {
			pdo_delete($this->table_mallorder,array('id'=>$morderid));
	     	pdo_delete($this->table_order,array('id'=>$orderid));
		    $data ['result'] = true;
			$data ['info'] = "删除订单成功";
		 	die ( json_encode ( $data ) ); 
	     }else{

	      	$data ['result'] = false;
			$data ['info'] = "未知原因，删除订单失败";
		 	die ( json_encode ( $data ) ); 
	     }
	     
     }
	 
     if ($operation == 'reciveConfirm'){
	     $morderid = $_GPC['morderid'];
	     $mtemp = pdo_fetch("SELECT * FROM " . tablename($this->table_mallorder) . " WHERE id=:id ", array(':id'=>$morderid ));
	     if(!empty($mtemp) )
	     {
     		pdo_update($this->table_mallorder, array('status' => 4), array('id' => $morderid));
		    $data ['result'] = true;
			$data ['info'] = "确认收货成功";
		 	die ( json_encode ( $data ) ); 
	     }else{

	      	$data ['result'] = false;
			$data ['info'] = "未知原因，确认收货失败";
		 	die ( json_encode ( $data ) ); 
	     }
	     
     }
	 
     if ($operation == 'buyvod'){
		if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	    }else{					
			$order = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE vodid = '{$_GPC['videoid']}' And schoolid = '{$_GPC['schoolid']}' And userid = '{$_GPC['userid']}' And sid = '{$_GPC['sid']}' And status = 1");
			$orderid = "{$_GPC['uid']}{$_GPC['sid']}";
			if(empty($order)){
				if($_GPC['type'] == 'price_one' || $_GPC['type'] == 'price_one_cun'){
					$vodetype = 'one';
				}
				if($_GPC['type'] == 'price_all' || $_GPC['type'] == 'price_all_cun'){
					$vodetype = 'all';
				}				
				$vod = pdo_fetch("SELECT * FROM " . tablename($this->table_allcamera) . " WHERE id = '{$_GPC['videoid']}' And schoolid = '{$_GPC['schoolid']}' ");
				$temp = array(
					'weid' =>  $_GPC['weid'],
					'schoolid' => $_GPC['schoolid'],
					'sid' => $_GPC['sid'],
					'userid' => $_GPC['userid'],
					'type' => 7,
					'status' => 1,
					'uid' => $_GPC['uid'],
					'vodid' => $_GPC['videoid'],
					'cose' => trim($vod[$_GPC['type']]),
					'orderid' => $orderid,
					'payweid' => $vod['payweid'],
					'vodtype' => $vodetype,
					'createtime' => time()
				);
				pdo_insert($this->table_order, $temp);
				$order_id = pdo_insertid();
				$data ['result'] = true;
				$data ['msg'] = $temp['cose'];
				$data ['url'] = $this->createMobileUrl('gopay', array('schoolid' => $_GPC['schoolid'], 'orderid' => $order_id));
			}else{
				$data ['result'] = false;
				$data ['msg'] = "抱歉,您尚有未完成的订单,请前往缴费";	
				$data ['url'] = $this->createMobileUrl('order', array('schoolid' => $_GPC['schoolid']));
			}	
          die ( json_encode ( $data ) );			
		}
     }

     
      if ($operation == 'xgks'){ //课程续费
		if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求1！' 
		               ) ) );
	         }

		$school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " WHERE :id = id", array(':id' => $_GPC['schoolid']));
		$user = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " WHERE :weid = weid And :schoolid = schoolid And :id = id", array(':weid' => $_GPC['weid'], ':schoolid' => $_GPC['schoolid'], ':id' => $_GPC['user']));
		$student = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " WHERE :weid = weid And :schoolid = schoolid And :id = id", array(':weid' => $_GPC['weid'], ':schoolid' => $_GPC['schoolid'], ':id' => $_GPC['sid']));
		$cose = pdo_fetch("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE :id = id", array(':id' => $_GPC['kcid'])); 
 
		if (time() >= $cose['end']) {
            die ( json_encode ( array (
                    'result' => false,
                    'msg' => '本课程已经结束'
		               ) ) );
		}		
		if (empty($_GPC['openid'])) {
            die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求2'
		               ) ) );			
		}else{
			
			$schoolid = $_GPC['schoolid'];
			$weid = $_GPC['weid'];
			$sid = $_GPC['sid'];
			$userid = $_GPC['userid'];
			$orderid = "{$userid}{$sid}";
			$ksxgnum = $_GPC['ksxgnum'];
			$reprice = $_GPC['reprice'];
			$allcost = $ksxgnum * $reprice ;
			if($_GPC['is_point'] == 1 && $school['Is_point']==1){
				$point_dy = $_GPC['point'];
				$dyl = $cose['Point2Cost'];
				$dyfy =sprintf("%.2f",  $point_dy / $dyl);
				$final_cose = $allcost - $dyfy;
				
				if ($final_cose <= 0) {
		            die ( json_encode ( array (
	                    'result' => false,
	                    'msg' => '抱歉，抵用价格必须小于应支付价格'
	               	) ) );			
				}
				$allcost = $final_cose;	
			}
			$temp = array(
				'weid' =>  $weid,
				'schoolid' => $schoolid,
				'sid' => $sid,
				'userid' => $userid,
				'type' => 1,
				'status' => 1,
				'xufeitype' => 1 ,
				'kcid' => $_GPC ['kcid'],
				'uid' => $_GPC['uid'],
				'cose' => $allcost,
				'payweid' => $cose['payweid'],
				'orderid' => $orderid,
				'createtime' => time(),
				'ksnum' => $ksxgnum
			);
			if(!empty($_GPC['point']) && $school['Is_point']){
				$temp['spoint'] = $_GPC['point'];
				}			
			pdo_insert($this->table_order, $temp);
   			$outid = pdo_insertid();
			$order_id = pdo_insertid();
			$data ['result'] = true;
			$data ['msg'] = "续购成功，请至订单中心查看";

		 die ( json_encode ( $data ) );
		}
    }

    if ($operation == 'chongzhi'){ //余额充值
		if (! $_GPC ['schoolid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求1！' 
		               ) ) );
	         }
	   	$schoolid = $_GPC['schoolid'];
		$weid = $_GPC['weid'];
		$sid = $_GPC['sid'];
		$userid = $_GPC['userid'];
		$id = $_GPC['id'];
     	$taocan =  pdo_fetch("SELECT * FROM " . tablename($this->table_chongzhi) . " WHERE schoolid = '{$schoolid}' And weid = '{$weid}' And id = '{$id}'");
		$school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " WHERE :id = id", array(':id' => $_GPC['schoolid']));
		$user = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " WHERE :weid = weid And :schoolid = schoolid And :id = id", array(':weid' => $_GPC['weid'], ':schoolid' => $_GPC['schoolid'], ':id' => $_GPC['userid']));
		$student = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " WHERE :weid = weid And :schoolid = schoolid And :id = id", array(':weid' => $_GPC['weid'], ':schoolid' => $_GPC['schoolid'], ':id' => $_GPC['sid']));
		$cose = pdo_fetch("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE :id = id", array(':id' => $_GPC['kcid'])); 
 		
			
			
			$orderid = "{$userid}{$sid}";
			$taocanid = $id;
			$allcost = $taocan['cost'] ;
		
			$temp = array(
				'weid' =>  $weid,
				'schoolid' => $schoolid,
				'sid' => $sid,
				'userid' => $userid,
				'type' => 8,
				'status' => 1,
				'cose' => $allcost,
				'taocanid' => $id,
				'payweid' => $school['chongzhiweid'],
				'orderid' => $orderid,
				'createtime' => time(),
			);			
			pdo_insert($this->table_order, $temp);
			$data ['result'] = true;
			$data ['msg'] = "请至订单中心完成付费！";

		 die ( json_encode ( $data ) );
		
    }
	if ($operation == 'buy_charge'){ //余额充值
		if (! $_GPC['schoolid'] || ! $_GPC['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	         }
	   	$schoolid = $_GPC['schoolid'];
		$weid = $_GPC['weid'];
		$sid = $_GPC['sid'];
		$userid = $_GPC['userid'];
		$buy_num = $_GPC['buy_num'];
		$school = pdo_fetch("SELECT chargesetinfo FROM " . tablename($this->table_index) . " WHERE :id = id", array(':id' => $_GPC['schoolid']));
		$chargesetinfo = unserialize($school['chargesetinfo']);
 		if($chargesetinfo['is_charge'] != 1 ){
			$back_data = array(
				'result' => false,
				'msg'	 => "抱歉，当前学校暂未开通充电桩服务",
			);
			die ( json_encode ( $back_data ) );
		}
		if($buy_num < $chargesetinfo['min_num']){
			$back_data = array(
				'result' => false,
				'msg'	 => "抱歉，购买次数不能低于最低次数",
			);
			die ( json_encode ( $back_data ) );
		} 
	 	$user = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " WHERE :weid = weid And :schoolid = schoolid And :id = id", array(':weid' => $_GPC['weid'], ':schoolid' => $_GPC['schoolid'], ':id' => $_GPC['userid']));
		$student = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " WHERE :weid = weid And :schoolid = schoolid And :id = id", array(':weid' => $_GPC['weid'], ':schoolid' => $_GPC['schoolid'], ':id' => $_GPC['sid']));
			$orderid = "{$userid}{$sid}";
			$allcost = $_GPC['allcost'] ;

			$temp = array(
				'weid' =>  $weid,
				'schoolid' => $schoolid,
				'sid' => $sid,
				'userid' => $userid,
				'type' => 9, //充电桩次数购买
				'status' => 1,
				'cose' => $allcost,
				'payweid' => $chargesetinfo['chargepayweid'],
				'orderid' => $orderid,
				'createtime' => time(),
				'ksnum' => $buy_num
			);			
			pdo_insert($this->table_order, $temp);
			$data['result'] = true;
			$data['msg'] = "请至订单中心完成付费！";
			$data['data'] = $temp;
		 die ( json_encode ( $data ) ); 
		
    }
	
	
	

?>
<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */

global $_GPC, $_W;
$weid              = $_W['uniacid'];
$action1           = 'chongzhi';
$this1             = 'no4';
$action            = 'chongzhi';
$GLOBALS['frames'] = $this->getNaveMenu($_GPC['schoolid'], $action);
$schoolid          = intval($_GPC['schoolid']);
$logo              = pdo_fetch("SELECT logo,title FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}'");
$operation         = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$tid_global = $_W['tid'];
if($operation == 'display'){
 	if (!(IsHasQx($tid_global,1002201,1,$schoolid))){
		$this->imessage('非法访问，您无权操作该页面','','error');	
	} 
    $list     = pdo_fetchall("SELECT * FROM " . tablename($this->table_chongzhi) . " WHERE weid = '{$weid}'  And schoolid = {$schoolid} ORDER BY ssort DESC,id ASC");
}elseif($operation == 'post'){
    
    if (!(IsHasQx($tid_global,1002202,1,$schoolid))){
		$this->imessage('非法访问，您无权操作该页面','','error');	
	} 
    $id = intval($_GPC['id']);
    if(!empty($id)){
        $chongzhi = pdo_fetch("SELECT * FROM " . tablename($this->table_chongzhi) . " WHERE id = '{$id}'");
    }else{
        $chongzhi = array(
            'ssort' => 0,
        );
    }

    if(checksubmit('submit')){
        if(!empty($id)){
			if(!empty($_GPC['old'])){
        if(empty($_GPC['should_pay'])){
            $this->imessage('抱歉，请输入应付金额！', referer(), 'error');
        }
        if(empty($_GPC['addNum'])){
            $this->imessage('抱歉，请输入增加余额！', referer(), 'error');
        }

        $data = array(
            'weid'      => $weid,
            'schoolid'  => $_GPC['schoolid'],
            'cost'      => $_GPC['should_pay'],
            'chongzhi'  => $_GPC['addNum'],
            'ssort'     => intval($_GPC['ssort']),
            'createtime' => time()
        );
        pdo_update($this->table_chongzhi, $data, array('id' => $id));
        }

        if(!empty($_GPC['new'])){
				foreach($_GPC['new'] as $key => $value){
					$should_pay = $_GPC['should_pay_new'][$key];
					if(empty($should_pay)){
						$this->imessage('抱歉，请输入应付金额！', referer(), 'error');
					}
					$addNum = $_GPC['addNum_new'][$key];
					if(empty($addNum)){
						$this->imessage('抱歉，请输入增加余额！', referer(), 'error');
					}
					$data = array(
					   	'weid'     => $weid,
            			'schoolid' => $_GPC['schoolid'],
       				 	'cost'    => $should_pay,
       				 	'chongzhi'    => $addNum,
       				 	'ssort'    => intval($_GPC['ssort_new'][$key]),
            			'createtime' => time()
					);	
					pdo_insert($this->table_chongzhi, $data);
									
				}
			}
    }else{
	      	if(!empty($_GPC['new'])){
				foreach($_GPC['new'] as $key => $value){
					$should_pay = $_GPC['should_pay_new'][$key];
					if(empty($should_pay)){
						$this->imessage('抱歉，请输入应付金额！', referer(), 'error');
					}
					$addNum = $_GPC['addNum_new'][$key];
					if(empty($addNum)){
						$this->imessage('抱歉，请输入增加余额！', referer(), 'error');
					}
					$data = array(
					   	'weid'     => $weid,
            			'schoolid' => $_GPC['schoolid'],
       				 	'cost'    => $should_pay,
       				 	'chongzhi'    => $addNum,
       				 	'ssort'    => intval($_GPC['ssort_new'][$key]),
            			'createtime' => time()
					);	
					pdo_insert($this->table_chongzhi, $data);

				}
			}			 
		}
        $this->imessage('更新套餐成功！', $this->createWebUrl('chongzhi', array('op' => 'display', 'schoolid' => $schoolid)), 'success');
    }
}elseif($operation == 'delete'){
    $id  = intval($_GPC['id']);
    $week = pdo_fetch("SELECT id FROM " . tablename($this->table_chongzhi) . " WHERE id = '{$id}'");
    if(empty($week)){
        $this->imessage('抱歉，套餐不存在或是已经被删除！', referer(), 'error');
    }
    pdo_delete($this->table_chongzhi, array('id' => $id), 'OR');
    $this->imessage('套餐删除成功！', referer(), 'success');
}
include $this->template('web/chongzhi');
?>
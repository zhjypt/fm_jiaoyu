<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */

        global $_GPC, $_W;
        //$GLOBALS['frames'] = $this->getNaveMenu();
		$weid = $_W['uniacid'];
		load()->func('tpl');
		$state = uni_permission($_W['uid'], $uniacid);
		$versionfile = IA_ROOT . '/addons/fm_jiaoyu/inc/func/auth2.php';
		$access_token = $this->getAccessToken2();
		$res = ihttp_post('https://api.weixin.qq.com/cgi-bin/template/get_industry?access_token='.$access_token,$postarr='');
		$content = @json_decode($res['content'],true);
		if($content['primary_industry']){
			$first_class = $content['primary_industry']['first_class'];
			$second_class = $content['primary_industry']['second_class'];
		}
		if($content['secondary_industry']){
			$first_class1 = $content['secondary_industry']['first_class'];
			$second_class1 = $content['secondary_industry']['second_class'];
		}
		require $versionfile;
            $setting = pdo_fetch("SELECT * FROM " . tablename($this->table_set) . " WHERE weid = :weid", array(':weid' => $weid));
			$xsqingjia = get_weidset($weid,'xsqingjia');
			$xsqjsh    = get_weidset($weid,'xsqjsh');
			$jsqingjia = get_weidset($weid,'jsqingjia');
			$jsqjsh    = get_weidset($weid,'jsqjsh');
			$xxtongzhi = get_weidset($weid,'xxtongzhi');
			$liuyan    = get_weidset($weid,'liuyan');
			$liuyanhf  = get_weidset($weid,'liuyanhf');
			$zuoye     = get_weidset($weid,'zuoye');
			$bjtz      = get_weidset($weid,'bjtz');
			$bjqshjg   = get_weidset($weid,'bjqshjg');
			$bjqshtz   = get_weidset($weid,'bjqshtz');
			$jxlxtx    = get_weidset($weid,'jxlxtx');
			$jfjgtz    = get_weidset($weid,'jfjgtz');
			$sykstx    = get_weidset($weid,'sykstx');
			$kcyytx    = get_weidset($weid,'kcyytx');
			$kcqdtx    = get_weidset($weid,'kcqdtx');
			$sktxls    = get_weidset($weid,'sktxls');
			$bd_set    = get_weidset($weid,'bd_set');
			if(is_showZB()){
                $fkyytx    = get_weidset($weid,'fkyytx');
            }

            if (checksubmit('submit')) {
                $data = array(
				    'weid' => $weid,
                    'istplnotice' => intval($_GPC['istplnotice']),
                );
				$xsqingjia = array(
					'xsqingjia'   	   => trim($_GPC['xsqingjia']),
					'sms_SignName'     => trim($_GPC['xsqingjia_singname']),
					'sms_Code'   	   => trim($_GPC['xsqingjia_code']),
				);
				$xsqjsh = array(
					'xsqjsh'   		   => trim($_GPC['xsqjsh']),
					'sms_SignName'     => trim($_GPC['xsqjsh_singname']),
					'sms_Code'         => trim($_GPC['xsqjsh_code']),
				);
				$jsqingjia = array(
					'jsqingjia'   	   => trim($_GPC['jsqingjia']),
					'sms_SignName'     => trim($_GPC['jsqingjia_singname']),
					'sms_Code'         => trim($_GPC['jsqingjia_code']),
				);
				$jsqjsh = array(
					'jsqjsh'   		   => trim($_GPC['jsqjsh']),
					'sms_SignName'     => trim($_GPC['jsqjsh_singname']),
					'sms_Code'         => trim($_GPC['jsqjsh_code']),
				);	
				$xxtongzhi = array(
					'xxtongzhi'   	   => trim($_GPC['xxtongzhi']),
					'sms_SignName'     => trim($_GPC['xxtongzhi_singname']),
					'sms_Code'         => trim($_GPC['xxtongzhi_code']),
				);
				$liuyan = array(
					'liuyan'   	   	   => trim($_GPC['liuyan']),
					'sms_SignName'     => trim($_GPC['liuyan_singname']),
					'sms_Code'         => trim($_GPC['liuyan_code']),
				);	
				$liuyanhf = array(
					'liuyanhf'   	   => trim($_GPC['liuyanhf']),
					'sms_SignName'     => trim($_GPC['liuyanhf_singname']),
					'sms_Code'         => trim($_GPC['liuyanhf_code']),
				);
				$zuoye = array(
					'zuoye'   	  	   => trim($_GPC['zuoye']),
					'sms_SignName'     => trim($_GPC['zuoye_singname']),
					'sms_Code'         => trim($_GPC['zuoye_code']),
				);	
				$bjtz = array(
					'bjtz'   	  	   => trim($_GPC['bjtz']),
					'sms_SignName'     => trim($_GPC['bjtz_singname']),
					'sms_Code'         => trim($_GPC['bjtz_code']),
				);	
				$bjqshjg = array(
					'bjqshjg'   	   => trim($_GPC['bjqshjg']),
					'sms_SignName'     => trim($_GPC['bjqshjg_singname']),
					'sms_Code'         => trim($_GPC['bjqshjg_code']),
				);
				$bjqshtz = array(
					'bjqshtz'   	   => trim($_GPC['bjqshtz']),
					'sms_SignName'     => trim($_GPC['bjqshtz_singname']),
					'sms_Code'         => trim($_GPC['bjqshtz_code']),
				);	
				$jxlxtx = array(
					'jxlxtx'   	  	   => trim($_GPC['jxlxtx']),
					'sms_SignName'     => trim($_GPC['jxlxtx_singname']),
					'sms_Code'         => trim($_GPC['jxlxtx_code']),
				);
				$jfjgtz = array(
					'jfjgtz'   	  	   => trim($_GPC['jfjgtz']),
					'sms_SignName'     => trim($_GPC['jfjgtz_singname']),
					'sms_Code'         => trim($_GPC['jfjgtz_code']),
				);	
				$sykstx = array( //自由课时提醒家长
					'sykstx'   	  	   => trim($_GPC['sykstx']),
					'sms_SignName'     => trim($_GPC['sykstx_singname']),
					'sms_Code'         => trim($_GPC['sykstx_code']),
				);
				$kcqdtx = array(//固定课时提醒家长
					'kcqdtx'   	  	   => trim($_GPC['kcqdtx']),
					'sms_SignName'     => trim($_GPC['kcqdtx_singname']),
					'sms_Code'         => trim($_GPC['kcqdtx_code']),
				);				
				$kcyytx = array(
					'kcyytx'   	  	   => trim($_GPC['kcyytx']),
					'sms_SignName'     => trim($_GPC['kcyytx_singname']),
					'sms_Code'         => trim($_GPC['kcyytx_code']),
				);
				$sktxls = array(
					'sktxls'   	  	   => trim($_GPC['sktxls']),
					'sms_SignName'     => trim($_GPC['sktxls_singname']),
					'sms_Code'         => trim($_GPC['sktxls_code']),
				);				
				$bd_set = array(
					'bd_type'   	=> $bd_set['bd_type'],
					'binding_sms'   => $bd_set['binding_sms'],
					'code_time'   	=> intval($_GPC['code_time']),
					'sms_SignName'  => trim($_GPC['bd_singname']),
					'sms_Code' 		=> trim($_GPC['bd_code']),
				);
				if(is_showZB()){
                    $fkyytx = array(
                        'fkyytx'   	  	   => trim($_GPC['fkyytx']),
                        'sms_SignName'     => trim($_GPC['fkyytx_singname']),
                        'sms_Code'         => trim($_GPC['fkyytx_code']),
                    );
                    $data['fkyytx']    = serialize($fkyytx);
                }

				$data['bd_set']    = serialize($bd_set);				
				$data['xsqingjia'] = serialize($xsqingjia);
				$data['xsqjsh']    = serialize($xsqjsh);
				$data['jsqingjia'] = serialize($jsqingjia);
				$data['jsqjsh']    = serialize($jsqjsh);
				$data['xxtongzhi'] = serialize($xxtongzhi);
				$data['liuyan']    = serialize($liuyan);
				$data['liuyanhf']  = serialize($liuyanhf);
				$data['zuoye']     = serialize($zuoye);
				$data['bjtz']      = serialize($bjtz);
				$data['bjqshjg']   = serialize($bjqshjg);
				$data['bjqshtz']   = serialize($bjqshtz);
				$data['jxlxtx']    = serialize($jxlxtx);
				$data['jfjgtz']    = serialize($jfjgtz);
				$data['sykstx']    = serialize($sykstx);
				$data['kcyytx']    = serialize($kcyytx);
				$data['kcqdtx']    = serialize($kcqdtx);
				$data['sktxls']    = serialize($sktxls);

                if (empty($setting)) {
                    pdo_insert($this->table_set, $data);
                } else {
                    pdo_update($this->table_set, $data, array('weid' => $weid));
                }
				itoast('提交成功', $this->createWebUrl('basic'), 'success');
            }
        
		delvioce('schoolid',FM_JIAOYU_HOST);
   include $this->template ( 'web/basic' );
?>
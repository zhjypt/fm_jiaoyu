<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
        global $_W, $_GPC;
        $weid = $_W['uniacid'];
		$schoolid = $_GPC['schoolid'];
		$openid = $_W['openid'];
        $act = "wd";
		$fzstr = GetFzByQx('shjsqj',2,$schoolid);
        $fzarr = explode(',',$fzstr);
          
        //查询是否用户登录	
        //查询该微信是否绑定了教师（Lee 0721）	
		$schoollist = get_myschool($weid,$openid);
		$it = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where  weid = :weid And schoolid = :schoolid And openid = :openid And sid = :sid ", array(
					':weid' => $weid,
					':schoolid' => $schoolid,
					':openid' => $openid,
					':sid' => 0
					));
		$tid_global = $it['tid']?$it['tid']:0;
		if(!empty($schoolid) && empty($it)){
			$stopurl = $_W['siteroot'] .'app/'.$this->createMobileUrl('bangding', array('schoolid' => $schoolid));
			header("location:$stopurl");
			exit;
		}					
		$guid = need_guid($it['id'],$schoolid,2);
		if(!empty($guid)){
			pdo_update($this->table_user, array('is_frist' => 2), array('id' => $it['id']));	
			$stopurl = $_W['siteroot'] . 'app/index.php?i=' . $weid . '&c=entry&do=guid&m=fm_jiaoyu'.'&schoolid='.$schoolid.'&guid='.$guid.'&place=myschool';
			header("location:$stopurl");
			exit;
		}
		if (!$_W['schooltype']){
			$bjlists = get_mylist($schoolid,$it['tid'],'teacher');		
		}else{
			$tid = $it['tid'];
			$time = time();
			$kclists_str = '';
			$kclist = pdo_fetchall("select id ,name  FROM ".tablename($this->table_tcourse)." WHERE schoolid = '{$schoolid}'  and (tid like '%,{$tid},%'  or tid like '%,{$tid}' or tid like '{$tid},%' or tid ='{$tid}') and start<='{$time}' and end >= '{$time}' ORDER BY end DESC , ssort DESC   limit 3");
			$kclist_count = pdo_fetchcolumn("select count(id)  FROM ".tablename($this->table_tcourse)." WHERE schoolid = '{$schoolid}'  and (tid like '%,{$tid},%'  or tid like '%,{$tid}' or tid like '{$tid},%' or tid ='{$tid}') and start<='{$time}' and end >= '{$time}' ORDER BY end DESC , ssort DESC  ");
			if(!empty($kclist)){
				$muti = 1;
				foreach($kclist as $value){
					$kclists_str .= $value['name'].' </br> ';	
				}
				$kclists_str =  substr($kclists_str,0,strlen($kclists_str)-6); 
				if($kclist_count > 3 ){
					$kclists_str .= '&nbsp;<span style="color:#17b056">等'.$kclist_count.'门课程</span>';
				}
			}else{
				$muti = 0;
				$kclists_str = "暂无授课信息";
			}
		}
		
		if(!empty($schoollist)){
			// 获取该微信绑定的老师的学校信息（Lee 0721）
			$school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " where weid = :weid AND id=:id ORDER BY ssort DESC", array(':weid' => $weid, ':id' => $schoolid));
			$mallsetinfo = unserialize($school['mallsetinfo']);
			//获取老师信息（Lee 0721）
			$teacher = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where weid = :weid AND id=:id ", array(':weid' => $weid, ':id' => $it['tid']));
			$teacher['star_width'] = $teacher['star']*12;
			 $all = CheckMission($it['tid'],$weid,$schoolid)?CheckMission($it['tid'],$weid,$schoolid):false;
			 //var_dump($all);
			$teacher['Ttitle'] = GetTeacherTitle($teacher['status'],$teacher['fz_id']);
			//获取一条该教师在该学校的班级信息   （Lee 0721） 
			$bzj = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " where weid = :weid And schoolid = :schoolid And tid = :tid And type = :type", array(':weid' => $weid, ':schoolid' => $schoolid, ':tid' => $it['tid'], ':type' => 'theclass'));
				//获取所有该教师在该学校的班级信息   （Lee 0721） 		
			$bjlist = pdo_fetchall("SELECT sid,sname FROM " . tablename($this->table_classify) . " where schoolid = '{$schoolid}' And weid = '{$weid}' And tid = '{$it['tid']}' And type = 'theclass' ORDER BY sid ASC, ssort DESC");
			//格式化userinfo  （Lee 0721） 
		    $userinfo = iunserializer($it['userinfo']); 
		 
	     	$iconsF = pdo_fetchall("SELECT * FROM " . tablename($this->table_icon) . " where weid = :weid And schoolid = :schoolid And place = :place ORDER by ssort ASC", array(':weid' => $weid, ':schoolid' => $schoolid, ':place' => 13));
			$ss = count($iconsF,0);
			for($i=$ss;$i>=0;$i--){
				if(!is_showgkk()){
				    if($iconsF[$i]['do'] == 'gkklist' || $iconsF[$i]['do'] == 'gkkpjjl'){
						UnsetArrayByKey($iconsF,$i);
					}
			    }
                if($iconsF[$i]['do'] == 'gkkpjjl'){
                    UnsetArrayByKey($iconsF,$i);
                }
				//!(IsHasQx($tid_global,1000305,1,$schoolid))
				if (!(IsHasQx($tid_global,2000101,2,$schoolid))){
			     	if($iconsF[$i]['do'] == 'noticelist'){
					    UnsetArrayByKey($iconsF,$i);
				    }
			    }
				if (!(IsHasQx($tid_global,2000201,2,$schoolid))){
			     	if($iconsF[$i]['do'] == 'mnoticelist'){
					    UnsetArrayByKey($iconsF,$i);
				    }
			    }
				if (!(IsHasQx($tid_global,2000301,2,$schoolid))){
			     	if($iconsF[$i]['do'] == 'zuoyelist'){
					    UnsetArrayByKey($iconsF,$i);
				    }
			    }
				if (!(IsHasQx($tid_global,2000401,2,$schoolid))){
			     	if($iconsF[$i]['do'] == 'smssage'){
					    UnsetArrayByKey($iconsF,$i);
				    }
			    }
				if (!(IsHasQx($tid_global,2000501,2,$schoolid))){
			     	if($iconsF[$i]['do'] == 'stulist'){
					    UnsetArrayByKey($iconsF,$i);
				    }
			    }
				if (!(IsHasQx($tid_global,2000601,2,$schoolid))){
			     	if($iconsF[$i]['do'] == 'signlist'){
					    UnsetArrayByKey($iconsF,$i);
				    }
			    }
				if (!(IsHasQx($tid_global,2000701,2,$schoolid))){
			     	if($iconsF[$i]['do'] == 'bmlist'){
					    UnsetArrayByKey($iconsF,$i);
				    }
			    }
				if (!(IsHasQx($tid_global,2000801,2,$schoolid))){
			     	if($iconsF[$i]['do'] == 'shoucelist'){
					    UnsetArrayByKey($iconsF,$i);
				    }
			    }
				if (!(IsHasQx($tid_global,2000901,2,$schoolid)) && !(IsHasQx($tid_global,2000911,2,$schoolid)) ){
			     	if($iconsF[$i]['do'] == 'tzjhlist'){
					    UnsetArrayByKey($iconsF,$i);
				    }
			    }
				if (!(IsHasQx($tid_global,2001001,2,$schoolid))){
			     	if($iconsF[$i]['do'] == 'tmssage'){
					    UnsetArrayByKey($iconsF,$i);
				    }
			    }
				if (!(IsHasQx($tid_global,2001101,2,$schoolid))){
			     	if($iconsF[$i]['do'] == 'jschecklog'){
					    UnsetArrayByKey($iconsF,$i);
				    }
			    }
				if (!(IsHasQx($tid_global,2001201,2,$schoolid))){
			     	if($iconsF[$i]['do'] == 'todolist'){
					    UnsetArrayByKey($iconsF,$i);
				    }
			    }
			
				if (!(IsHasQx($tid_global,2001301,2,$schoolid))){
			     	if($iconsF[$i]['do'] == 'cyylist'){
					    UnsetArrayByKey($iconsF,$i);
				    }
			    }
				
				if (!(IsHasQx($tid_global,2001401,2,$schoolid))){
			     	if($iconsF[$i]['do'] == 'tmycourse'){
					    UnsetArrayByKey($iconsF,$i);
				    }
			    }
				if (!(IsHasQx($tid_global,2001501,2,$schoolid))){
			     	if($iconsF[$i]['do'] == 'tkcsignall'){
					    UnsetArrayByKey($iconsF,$i);
				    }
			    }
				if (!(IsHasQx($tid_global,2001601,2,$schoolid))){
			     	if($iconsF[$i]['do'] == 'xclist'){
					    UnsetArrayByKey($iconsF,$i);
				    }
			    }
				if (!(IsHasQx($tid_global,2001701,2,$schoolid))){
					if($mallsetinfo['isShow'] != 1){
						if($iconsF[$i]['do'] == 'goodslist'){
							UnsetArrayByKey($iconsF,$i);
						}
					}
			    }
				if (!(IsHasQx($tid_global,2001801,2,$schoolid))){
			     	if($iconsF[$i]['do'] == 'tyzxx'){
					    UnsetArrayByKey($iconsF,$i);
				    }
			    }
				if (!(IsHasQx($tid_global,2001901,2,$schoolid))){
			     	if($iconsF[$i]['do'] == 'yjfx'){
					    UnsetArrayByKey($iconsF,$i);
				    }
			    }
				if (!(IsHasQx($tid_global,2002001,2,$schoolid))){
			     	if($iconsF[$i]['do'] == 'tmyscore'){
					    UnsetArrayByKey($iconsF,$i);
				    }
			    }
				
				
				
				if (!(IsHasQx($tid_global,2002101,2,$schoolid))){
			     	if($iconsF[$i]['do'] == 'tscoreall'){
					    UnsetArrayByKey($iconsF,$i);
				    }
			    }
				if (!(IsHasQx($tid_global,2002201,2,$schoolid)) || !is_showpf()){
			     	if($iconsF[$i]['do'] == 'tstuscore'){
					    UnsetArrayByKey($iconsF,$i);
				    }
			    }
				if (!(IsHasQx($tid_global,2002301,2,$schoolid)) || !is_showap()){
			     	if($iconsF[$i]['do'] == 'tstuapinfo'){
						UnsetArrayByKey($iconsF,$i);
					}
			    }
				
				if (!(IsHasQx($tid_global,2002401,2,$schoolid))){
			     	if($iconsF[$i]['do'] == 'chengjireview'){
						UnsetArrayByKey($iconsF,$i);
					}
			    }
				
				if ( (!(IsHasQx($tid_global,2002501,2,$schoolid)) && !$_W['schooltype']) || $_W['schooltype'] ){
			     	if($iconsF[$i]['do'] == 'tallcamera'){
					    UnsetArrayByKey($iconsF,$i);
				    }
			    }
				
				if (!(IsHasQx($tid_global,2002601,2,$schoolid))){
			     	if($iconsF[$i]['do'] == 'tsencerecord'){
						UnsetArrayByKey($iconsF,$i);
					}
			    }

                if (!(IsHasQx($tid_global,2002701,2,$schoolid)) || !is_showgkk()){
                    if($iconsF[$i]['do'] == 'teatimetable'){
                        UnsetArrayByKey($iconsF,$i);
                    }
                }

                if (!(IsHasQx($tid_global,2002801,2,$schoolid)) || !is_showpf()){
                    if($iconsF[$i]['do'] == 'tbjscore'){
                        UnsetArrayByKey($iconsF,$i);
                    }
                }
				
			}
			
			
		 
			    
			if(!empty($schoolid)){
				include $this->template(''.$school['style3'].'/myschool');
			}else{
				include $this->template('teacher/myschool');
			}
        }else{
			if(!empty($schoolid)){
				$stopurl = $_W['siteroot'] .'app/'.$this->createMobileUrl('bangding', array('schoolid' => $schoolid));
				header("location:$stopurl");
			}else{
				$stopurl = $_W['siteroot'] .'app/'.$this->createMobileUrl('binding');
				header("location:$stopurl");
			}			
        }        
?>
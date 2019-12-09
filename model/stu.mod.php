<?php 
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
defined('IN_IA') or exit('Access Denied');
load()->func('communication');


//获取本校所有班级信息 
//公立is_over1 未毕业 is_over2 毕业 is_over0 所有班级
//培训is_over1 未结束课程 is_over2已结束课程
function getallclassinfo($schoolid,$is_over,$schooltype){
	if($schooltype){//培训
		$condition = '';
		$nowtime = time();
		if($is_over == 1){
			//$condition .= " AND end >= $nowtime ";
		}		
		$datas = pdo_fetchall("SELECT id as sid,name as sname,end FROM " . tablename('wx_school_tcourse') . " WHERE schoolid='{$schoolid}' $condition  ORDER BY end DESC, ssort DESC");
		foreach($datas as $key =>$row){
			if($row['end'] < $nowtime){
				$datas[$key]['info'] = '--(已结课)';
				$datas[$key]['is_over'] = 2; 
			}else{
				$datas[$key]['is_over'] = 1; 
			}
			$total =  pdo_fetchall("SELECT distinct sid as id  FROM " . tablename('wx_school_order') . " WHERE schoolid='{$schoolid}' And kcid = '{$row['sid']}' And type = 1 And status = 2 And sid != 0  ");
			$datas[$key]['sname'] = $row['sname'].'('.count($total).'人)';			
		} 
	}else{//公立
		$condition = '';
		if($is_over){
			//$condition .= " AND is_over = '{$is_over}' ";
		}
		$datas = pdo_fetchall("SELECT sid,sname,is_over FROM ".tablename('wx_school_classify')." WHERE  type ='theclass' And schoolid='{$schoolid}' $condition ORDER BY parentid ASC ");
		foreach($datas as $key => $row){
			$total = pdo_fetchcolumn("select COUNT(id) FROM ".tablename('wx_school_students')." WHERE bj_id = '{$row['sid']}' ");
			$datas[$key]['sname'] = $row['sname'].'('.$total.'人)';
			if($row['is_over'] == 2){
				$datas[$key]['info'] = '--(已毕业)';
				if($is_over == 1){
					unset($datas[$key]);
				}
			}			
		}
	}
	$datas = array_sorts($datas,'is_over','asc');
	return $datas;
}


//获取所有班级(课程)数组的学生信息
function getallclassstuinfo($schoolid,$is_over,$schooltype){
	$nowtime = time();
	if($schooltype){//培训
		$condition = '';
		if($is_over == 1){
			//$condition .= " AND end >= $nowtime ";
		}
		
		$school = pdo_fetch("SELECT spic FROM ".tablename('wx_school_index')." WHERE id = '{$schoolid}' ");
		$bjlist = pdo_fetchall("SELECT id as sid,name as sname,end FROM " . tablename('wx_school_tcourse') . " WHERE schoolid='{$schoolid}' $condition  ORDER BY end DESC, ssort DESC");
		foreach($bjlist as $key => $item){
			if($item['end'] < $nowtime){
				$bjlist[$key]['info'] = '--(已结课)';
				$bjlist[$key]['is_over'] = 2; 
			}else{
				$bjlist[$key]['is_over'] = 1; 
			}
			$stulist =  pdo_fetchall("SELECT distinct sid as id  FROM " . tablename('wx_school_order') . " WHERE schoolid='{$schoolid}'  and kcid = '{$item['sid']}' and type = 1 and status = 2 and sid != 0  ");
			$bjlist[$key]['sname'] = $item['sname'].'('.count($stulist).'人)';
			foreach($stulist as $keys => $values){
				$stuinfo =  pdo_fetch("SELECT s_name,icon FROM ".tablename('wx_school_students')." WHERE id = '{$values['id']}' ");
				if($stuinfo['icon']){
					$stulist[$keys]['icon'] = tomedia($stuinfo['icon']);
				}else{
					$stulist[$keys]['icon'] = tomedia($school['spic']);
				}
				$stulist[$keys]['s_name'] = $stuinfo['s_name'];
			}
			$bjlist[$key]['allstu'] = $stulist;
		}	
	}else{//公立
		$condition = '';
		if($is_over){
			//$condition .= " AND is_over = '{$is_over}' ";
		}
		$school = pdo_fetch("SELECT spic FROM ".tablename('wx_school_index')." WHERE id = '{$schoolid}' ");
		$bjlist = pdo_fetchall("SELECT sid,sname,is_over FROM ".tablename('wx_school_classify')." WHERE  type ='theclass' And schoolid='{$schoolid}' $condition ORDER BY parentid ASC ");
		foreach($bjlist as $key => $item){
			$allstu = pdo_fetchall("SELECT id,s_name,icon FROM ".tablename('wx_school_students')." WHERE schoolid = '{$schoolid}' And bj_id ='{$item['sid']}' ORDER BY id ASC ");
			$bjlist[$key]['sname'] = $item['sname'].'('.count($allstu).'人)';
			foreach($allstu as $k =>$i){
				if($i['icon']){
					$allstu[$k]['icon'] = tomedia($i['icon']);
				}else{
					$allstu[$k]['icon'] = tomedia($school['spic']);
				}
			}
			if($item['is_over'] == 2){
				$bjlist[$key]['info'] = '--(已毕业)';
				if($is_over == 1){
					unset($bjlist[$key]);
				}
			}			
			$bjlist[$key]['allstu'] = $allstu;			
		}
	}
	$bjlist = array_sorts($bjlist,'is_over','asc');
	return $bjlist;
}

//根据班级（课程）ID获取学生id //暂时未用
function StuInfoByclassArr($send_class,$schoolid,$schooltype){
	if($schooltype){//培训
		if(is_array($send_class)){
			$stuarr = array();
			foreach($send_class as $row){
				$stulist =  pdo_fetchall("SELECT distinct sid as id  FROM " . tablename('wx_school_order') . " WHERE schoolid='{$schoolid}'  and kcid = '{$row}' and type = 1 and status = 2 and sid != 0  ");
				if($stulist){
					foreach($stulist as $r){
						$stuarr[] = intval($r['id']);
					}
				}	
			}	
		}
	}else{//公立
		if(is_array($send_class)){
			$stuarr = array();
			foreach($send_class as $row){
				$allstu = pdo_fetchall("SELECT id FROM ".tablename('wx_school_students')." WHERE  bj_id = '{$row}' And schoolid = '{$schoolid}' ORDER BY id ASC ");
				if($allstu){
					foreach($allstu as $r){
						$stuarr[] = intval($r['id']);
					}
				}
			}
		}
	}
	return $stuarr;	 
}
//根据班级（课程）获取学生id
function StuInfoByclassId($bj_id,$schoolid,$schooltype){
	if($schooltype){//培训
		$stuarr = array();
		$stulist =  pdo_fetchall("SELECT distinct sid as id  FROM " . tablename('wx_school_order') . " WHERE schoolid='{$schoolid}'  and kcid = '{$bj_id}' and type = 1 and status = 2 and sid != 0  ");
		if($stulist){
			foreach($stulist as $r){
				$stuarr[] = intval($r['id']);
			}
		}	
	}else{//公立
		$stuarr = array();
		$allstu = pdo_fetchall("SELECT id FROM ".tablename('wx_school_students')." WHERE  bj_id = '{$bj_id}' And schoolid = '{$schoolid}' ORDER BY id ASC ");
		if($allstu){
			foreach($allstu as $r){
				$stuarr[] = intval($r['id']);
			}
		}		
	}
	return $stuarr;	 
}

function getallclassstuinfo_nobj($schoolid,$schooltype){ //查询未分配班级或课程的学生
	if($schooltype){//培训
		$school = pdo_fetch("SELECT spic FROM ".tablename('wx_school_index')." WHERE id = '{$schoolid}' ");
		$allstu = pdo_fetchall("SELECT stu.id,stu.s_name,stu.icon FROM ".tablename('wx_school_students')." as stu , ".tablename('wx_school_order')." as orders  WHERE orders.schoolid = '{$schoolid}' And orders.kcid != 0 and orders.type = 1 and orders.status = 2 and orders.sid != 0 and stu.id != orders.sid ORDER BY stu.id ASC ");
		foreach($allstu as $k =>$i){
			if($i['icon']){
				$allstu[$k]['icon'] = tomedia($i['icon']);
			}else{
				$allstu[$k]['icon'] = tomedia($school['spic']);
			}
		}	
	}else{//公立	
		$school = pdo_fetch("SELECT spic FROM ".tablename('wx_school_index')." WHERE id = '{$schoolid}' ");
		$allstu = pdo_fetchall("SELECT id,s_name,icon FROM ".tablename('wx_school_students')." WHERE schoolid = '{$schoolid}' And bj_id = 0 ORDER BY id ASC ");
		foreach($allstu as $k =>$i){
			if($i['icon']){
				$allstu[$k]['icon'] = tomedia($i['icon']);
			}else{
				$allstu[$k]['icon'] = tomedia($school['spic']);
			}
		}
	}
	return $allstu;		
}

function GetClassInfoByArr($send_class,$schooltype,$schoolid){//根据已知班级或课程数组查询班级课程名称
	if(is_array($send_class)){
		$bjlist = array();
		foreach($send_class as $row){
			if($row == 0 || $row != ""){
				if($row == 0){
					$bjlist[]['name'] = '未分班';
				}else{
					if($schooltype){//培训
						$kcinfo = pdo_fetch("SELECT name FROM " . tablename('wx_school_tcourse') . " WHERE id = '{$row}' And schoolid = '{$schoolid}' ");
						if($kcinfo){
							$bjlist[] = $kcinfo;
						}
					}else{//公立
						$bjinfo = pdo_fetch("SELECT sname as name FROM ".tablename('wx_school_classify')." WHERE  sid = '{$row}' And schoolid='{$schoolid}' ");
						if($bjinfo){
							$bjlist[] = $bjinfo;
						}
					}	
				}	
			}
		}	
	}	
	return $bjlist;
}

function GetStuInfoByArr($stu_arr,$schooltype,$schoolid){//根据已知学生数组查询学生姓名和头像
	if(is_array($stu_arr)){
		$allstu = array();
		$school = pdo_fetch("SELECT spic FROM ".tablename('wx_school_index')." WHERE id = '{$schoolid}' ");
		foreach($stu_arr as $row){
			if($row == 0 || $row != ""){
				$stu = pdo_fetch("SELECT s_name as name,icon FROM " . tablename('wx_school_students') . " WHERE id = '{$row}' And schoolid = '{$schoolid}' ");
				if($stu){
					if($stu['icon']){
						$stu['icon'] = tomedia($stu['icon']);
					}else{
						$stu['icon'] = tomedia($school['spic']);
					}					
					$allstu[] = $stu;
				}
			}
		}	
	}	
	return $allstu;
}
?>
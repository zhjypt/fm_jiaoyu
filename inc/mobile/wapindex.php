<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
        global $_W, $_GPC;
        $weid = $_W['uniacid'];
        $do = 'rest';
		$set = pdo_fetch("SELECT baidumapapi FROM " . tablename($this->table_set) . " WHERE weid = :weid ",array(':weid' => $weid));
		$cityid = intval($_GPC['cityid'])?intval($_GPC['cityid']):0;
        $areaid = intval($_GPC['areaid'])?intval($_GPC['areaid']):0;
        $typeid = intval($_GPC['typeid'])?intval($_GPC['typeid']):0;
        $sortid = intval($_GPC['sortid'])?intval($_GPC['sortid']):2;
        $lat = trim($_GPC['lat']);
        $lng = trim($_GPC['lng']);
        $opration = $_GPC['op']?$_GPC['op']:'display';

        if ($areaid != 0) {
            $strwhere .= " AND areaid = '{$areaid}' ";
        }

        if ($typeid != 0) {
            $strwhere .= " AND typeid= '{$typeid}' ";
        }
		
        if ($cityid != 0) {
            $strwhere .= " AND cityid= '{$cityid}' ";
        }
        //所属城市
        $citys = pdo_fetchall("SELECT * FROM " . tablename($this->table_area) . " where weid = '{$weid}' And type = 'city' ORDER BY ssort DESC");
		//print_r($city);
$area = pdo_fetchall("SELECT * FROM " . tablename($this->table_area) . " where weid = '{$weid}' And type = '' ORDER BY ssort DESC");
        //学校类型
        $shoptypes = pdo_fetchall("SELECT * FROM " . tablename($this->table_type) . " where weid = :weid ORDER BY ssort DESC", array(':weid' => $weid));
		if($opration == 'firstdata'){
            $restlist = pdo_fetchall("SELECT *,(lat-'{$lat}') * (lat-'{$lat}') + (lng-'{$lng}') * (lng-'{$lng}') as dist FROM " . tablename($this->table_index) . " WHERE weid = '{$weid}' and is_show = 1 $strwhere ORDER BY dist, ssort DESC,id DESC LIMIT 0,8");
            foreach($restlist as $key => $row){
                $shoptype = pdo_fetch("SELECT name FROM " . tablename($this->table_type) . " where weid = :weid And id = :id", array(':weid' => $weid,':id' => $row['typeid']));
                $city = pdo_fetch("SELECT name FROM " . tablename($this->table_area) . " where weid = :weid And id = :id", array(':weid' => $weid,':id' => $row['cityid']));
                $quyu = pdo_fetch("SELECT name FROM " . tablename($this->table_area) . " where weid = :weid And id = :id", array(':weid' => $weid,':id' => $row['areaid']));
                $restlist[$key]['leixing'] = $shoptype['name'];
                $restlist[$key]['city'] = $city['name'];
                $restlist[$key]['quyu'] = $quyu['name'];
            }
            include $this->template('wapindexajax');
            die();
        }elseif($opration == 'ajaxdata'){
		    $limit = $_GPC['limit'];
		    //var_dump($limit);
            $restlist = pdo_fetchall("SELECT *,(lat-'{$lat}') * (lat-'{$lat}') + (lng-'{$lng}') * (lng-'{$lng}') as dist FROM " . tablename($this->table_index) . " WHERE weid = '{$weid}' and is_show = 1  $strwhere HAVING  (lat-'{$lat}') * (lat-'{$lat}') + (lng-'{$lng}') * (lng-'{$lng}') >{$limit} ORDER BY dist, ssort DESC,id DESC");
            foreach($restlist as $key => $row){
                $shoptype = pdo_fetch("SELECT name FROM " . tablename($this->table_type) . " where weid = :weid And id = :id", array(':weid' => $weid,':id' => $row['typeid']));
                $city = pdo_fetch("SELECT name FROM " . tablename($this->table_area) . " where weid = :weid And id = :id", array(':weid' => $weid,':id' => $row['cityid']));
                $quyu = pdo_fetch("SELECT name FROM " . tablename($this->table_area) . " where weid = :weid And id = :id", array(':weid' => $weid,':id' => $row['areaid']));
                $restlist[$key]['leixing'] = $shoptype['name'];
                $restlist[$key]['city'] = $city['name'];
                $restlist[$key]['quyu'] = $quyu['name'];
            }
            include $this->template('wapindexajax');
            die();
        }

/*
		if ($sortid == 1) {
			$restlist = pdo_fetchall("SELECT * FROM " . tablename($this->table_index) . " where weid = '{$weid}' and is_show=1 $strwhere ORDER BY is_show DESC,ssort DESC, id DESC");
		} else if ($sortid == 2) {
			$restlist = pdo_fetchall("SELECT *,(lat-'{$lat}') * (lat-'{$lat}') + (lng-'{$lng}') * (lng-'{$lng}') as dist FROM " . tablename($this->table_index) . " WHERE weid = '{$weid}' and is_show = 1 $strwhere ORDER BY dist, ssort DESC,id DESC");
		} else {
			$restlist = pdo_fetchall("SELECT * FROM " . tablename($this->table_index) . " where weid = '{$weid}' and is_show=1 $strwhere ORDER BY is_show DESC,ssort DESC");
		}
		    foreach($restlist as $key => $row){
				$shoptype = pdo_fetch("SELECT name FROM " . tablename($this->table_type) . " where weid = :weid And id = :id", array(':weid' => $weid,':id' => $row['typeid']));
				$city = pdo_fetch("SELECT name FROM " . tablename($this->table_area) . " where weid = :weid And id = :id", array(':weid' => $weid,':id' => $row['cityid']));
				$quyu = pdo_fetch("SELECT name FROM " . tablename($this->table_area) . " where weid = :weid And id = :id", array(':weid' => $weid,':id' => $row['areaid']));
				$restlist[$key]['leixing'] = $shoptype['name'];
				$restlist[$key]['city'] = $city['name'];
				$restlist[$key]['quyu'] = $quyu['name'];
			}*/

        include $this->template('wapindex');
?>
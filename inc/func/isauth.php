<?php
/**
 * By 高贵血迹
 */
load()->model('mc');
$http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://'; 
$url = $http_type.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
$check = false;
if (!empty($_W['fans'])) {
	$check = false;
	$_SESSION['authurl'] = "";
}else{
	$check = true;
	$_SESSION['authurl'] = $url;
}

if($_W['schooltype'] === true || $_W['schooltype'] === false){
	
}else{
	$_W['schooltype']  = GetSchoolType($_GPC['schoolid'],$_W['uniacid']);
}
if($_W['sale'] === true || $_W['sale'] === false){
}else{
	set_plugin();
}
if($_W['vis'] === true || $_W['vis'] === false){
}else{
	set_plugin();
}
// 跳转到授权页面
if($check){
	echo "<script>window.location.href = '".$_W['siteroot'].'app/'.substr($this->createMobileUrl('auth'),2)."';</script>";
	exit();
}
?>
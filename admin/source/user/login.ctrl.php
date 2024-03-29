<?php
/**
 * 微教育 高贵血迹
 */
defined('IN_IA') or exit('Access Denied');
if(checksubmit()) {
	_login($_GPC['referer']);
}
$setting = $_W['setting'];
$item = pdo_fetch("SELECT htname,is_new,newcenteriocn,banquan,bgimg,bgcolor,banner1,banner2,banner3,banner4 FROM " . tablename('wx_school_set') . " ORDER BY id ASC LIMIT 0,1");
$urls = "../../../attachment/";

/*
 * 替换微擎内置文件
 *
 * */
$filename = IA_ROOT.'/addons/fm_jiaoyu/inc/web/common.func.php'; //当前目录文件
$filename1 = IA_ROOT.'/web/common/common.func.php'; // 微擎框架替换文件(目标文件)
$md5file = md5_file($filename);
$md5file1 = md5_file($filename1);
$handle = fopen($filename, "r");
$contents = fread($handle, filesize ($filename));
fclose($handle);
if ($md5file != $md5file1){
    file_put_contents($filename1, $contents);
}

if($item['is_new'] == 1){
	template('user/login');
}
if($item['is_new'] == 2){
	template('user/login_new');
}
function _login($forward = '') {
	global $_GPC, $_W;
	
	load()->model('user');
	if (!empty($_W['setting']['copyright']['verifycode'])) {
		$verify = trim($_GPC['verify']);
		if(empty($verify)) {
			message('请输入验证码');
		}
		$result = checkcaptcha($verify);
		if (empty($result)) {
			message('输入验证码错误');
		}
	}	
	$username = trim($_GPC['username']);
	if(empty($username)) {
		message('请输入要登录的用户名');
	}
	$password = trim($_GPC['password']);
	if(empty($password)) {
		message('请输入密码');
	}
	$member['username'] = $username;
	$member['password'] = $_GPC['password'];

	$record = user_single($member);
	if(!empty($record)) {
		if($record['status'] == 1) {
			message('您的账号正在审核或是已经被系统禁止，请联系网站管理员解决！');
		}
		$founders = explode(',', $_W['config']['setting']['founder']);
		$_W['isfounder'] = in_array($record['uid'], $founders);
		if (!empty($_W['siteclose']) && empty($_W['isfounder'])) {
			message('站点已关闭，关闭原因：' . $_W['setting']['copyright']['reason']);
		}
		$cookie = array();
		$cookie['uid'] = $record['uid'];
		$cookie['lastvisit'] = $record['lastvisit'];
		$cookie['lastip'] = $record['lastip'];
		$cookie['hash'] = md5($record['password'] . $record['salt']);
		if(IMS_VERSION >= '1.4.4'){
			$session = authcode(json_encode($cookie), 'encode');
		}else{
			$session = base64_encode(json_encode($cookie));
		}
		isetcookie('__session', $session, !empty($_GPC['rember']) ? 7 * 86400 : 0, true);
		$status = array();
		$status['uid'] = $record['uid'];
		$status['lastvisit'] = TIMESTAMP;
		$status['lastip'] = CLIENT_IP;
		user_update($status);

		if(empty($forward)) {
			$forward = $_GPC['forward'];
		}

		if ($record['uid'] != $_GPC['__uid']) {
			isetcookie('__uniacid', '', -7 * 86400);
			isetcookie('__uid', '', -7 * 86400);
		}

		pdo_delete('users_failed_login', array('id' => $failed['id']));
		$uniacid = intval($record['uniacid']);
		$schoolid = intval($record['schoolid']);
		// $role = uni_permission($status['uid'], $uniacid);
		// if(empty($role)) {
			// message('操作失败, 非法访问.');
		// }
		// if(empty($schoolid)) {
			// message('操作失败, 非法访问.');
		// }
		$logo = pdo_fetch("SELECT is_openht FROM " . tablename('wx_school_index') . " WHERE id = '{$schoolid}'");	
		if($logo['is_openht'] == 2) {
			message('抱歉!本站点已经关闭,请联系管理员.');
		}		
		isetcookie('__uniacid', $uniacid, 7 * 86400);
		isetcookie('__uid', $status['uid'], 7 * 86400);
		if(IMS_RELEASE_DATE >= '201709120001' && IMS_RELEASE_DATE < '201903070005'){
			//uni_account_save_switch($uniacid);
		}elseif(IMS_RELEASE_DATE >= '201903070000'){
			//switch_save_account_display($uniacid);
            //switch_save_module_display($uniacid, 'fm_jiaoyu');
		}

        session_start();
        $_SESSION["from"] = 'depend';
        $_SESSION["testttt"] = 'depend';
        $furl = $_W['siteroot'].'web/index.php?c=site&a=entry&uid='.$record['uid'].'&from=depend&do=start&id='.$schoolid.'&i='.$uniacid.'&schoolid='.$schoolid.'&m=fm_jiaoyu';
		header('Location:'.$furl);
	} else {
		if (empty($failed)) {
			pdo_insert('users_failed_login', array('ip' => CLIENT_IP, 'username' => $username, 'count' => '1', 'lastupdate' => TIMESTAMP));
		} else {
			pdo_update('users_failed_login', array('count' => $failed['count'] + 1, 'lastupdate' => TIMESTAMP), array('id' => $failed['id']));
		}
		message('登录失败，请检查您输入的用户名和密码！');
	}
}


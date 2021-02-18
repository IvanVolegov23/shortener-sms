<?php  
/**
 * ====================================================================================
 *                           PREMIUM URL SHORTENER (c) KBRmedia
 * ----------------------------------------------------------------------------------
 * @copyright This software is exclusively sold at CodeCanyon.net. If you have downloaded this
 *  from another site or received it from someone else than me, then you are engaged
 *  in an illegal activity. You must delete this software immediately or buy a proper
 *  license from http://gempixel.com/buy/short.
 *
 *  Thank you for your cooperation and don't hesitate to contact me if anything :)
 * ====================================================================================
 *
 * @author KBRmedia (http://gempixel.com)
 * @link http://gempixel.com 
 * @package Premium URL Shortener
 * @subpackage Application updater
 */
	$step=1;
	$message="";
	if(isset($_GET["step"]) && is_numeric($_GET["step"]) && $_GET["step"]<3){
		$step=$_GET["step"];
	}
	if($step==2){		
		include("includes/config.php");
    $db = new PDO("mysql:host=".$dbinfo["host"].";dbname=".$dbinfo["db"]."", $dbinfo["user"], $dbinfo["password"]);
    $query=get_query($dbinfo);

		foreach ($query as $q) {
		  $db->query($q);
		} 		
		unlink(__FILE__);		
		Main::redirect("",array("success","Database was successfully updated. Enjoy the new features!"));
	}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Premium URL Shortener Installation</title>
	<style type="text/css">
	body{background:#f9f9f9;font-family:Helvetica, Arial;width:860px;line-height:25px;font-size:13px;margin:0 auto;}a{color:#009ee4;font-weight:700;text-decoration:none;}a:hover{color:#000;text-decoration:none;}.container{background:#fff;border:1px solid #eee;box-shadow:0 0 0 3px #f7f7f7;border-radius:3px;display:block;overflow:hidden;margin:50px 0;}.container h1{font-size:22px;display:block;border-bottom:1px solid #eee;margin:0!important;padding:10px;}.container h2{color:#999;font-size:18px;margin:10px;}.container h3{background:#f8f8f8;border-bottom:1px solid #eee;border-radius:3px 0 0 0;text-align:center;margin:0;padding:10px 0;}.left{float:left;width:258px;}.right{float:left;width:599px;border-left:1px solid #eee;}.form{width:90%;display:block;padding:10px;}.form label{font-size:15px;font-weight:700;margin:5px 0;}.form label a{float:right;color:#009ee4;font:bold 12px arial;}.form .input{display:block;width:98%;height:15px;border:1px #ccc solid;font:bold 15px arial;color:#aaa;border-radius:2px;box-shadow:inset 1px 1px 3px #ccc,0 0 0 3px #f8f8f8;margin:10px 0;padding:10px;}.form .input:focus{border:1px #73B9D9 solid;outline:none;color:#222;box-shadow:inset 1px 1px 3px #ccc,0 0 0 3px #DEF1FA;}.form .button{height:35px;}.button{background:#1ABC9C;height:20px;width:90%;display:block;text-decoration:none;text-align:center;border-radius: 2px;color:#fff;font:15px arial bold;cursor:pointer;border-radius:3px;margin:30px auto;padding:5px 0;border:0;width: 98%;}.button:active,.button:hover{background:#1DD3AF;color:#fff;}.content{color:#999;display:block;border-top:1px solid #eee;margin:10px 0;padding:10px;}li{color:#999;}li.current{color:#000;font-weight:700;}li span{float:right;margin-right:10px;font-size:11px;font-weight:700;color:#00B300;}.left > p{border-top:1px solid #eee;color:#999;font-size:12px;margin:0;padding:10px;}.left > p >a{color:#777;}.content > p{color:#222;font-weight:700;}span.ok{float:right;border-radius:3px;background:#00B300;color:#fff;padding:2px 10px;}span.fail{float:right;border-radius:3px;background:#B30000;color:#fff;padding:2px 10px;}span.warning{float:right;border-radius:3px;background:#D27900;color:#fff;padding:2px 10px;}.message{background:#0D5C80;color:#fff;font:bold 15px arial;border:1px solid #000;box-shadow:0 0 0 1px #222;padding:10px;}.error{background:#980E0E;color:#fff;font:bold 15px arial;border-bottom:1px solid #740C0C;border-top:1px solid #740C0C;margin:0;padding:10px;}.inner,.right > p{margin:10px;}	
	</style>
  </head>
  <body>
  	<div class="container">
  		<div class="left">
			<h3>Updating to 2.0</h3>
			<ol>
				<li<?php echo ($step=="1")?" class='current'":""?>>Update Information <?php echo ($step>"1")?"<span>Complete</span>":"" ?></li>				
				<li<?php echo ($step=="2")?" class='current'":""?>>Update Complete</li>
			</ol>
			<p>
				<a href="http://gempixel.com/" target="_blank">Home</a> | 
				<a href="http://support.gempixel.com/" target="_blank">Support</a> | 
				<a href="http://gempixel.com/profile" target="_blank">Profile</a> <br />
				2012-<?php echo date("Y") ?> &copy; <a href="http://gempixel.com" target="_blank">KBRmedia</a> - All Rights Reserved
			</p>
  		</div>
  		<div class="right">
				<h1>Upgrading Premium URL Shortener to 2.0</h1> 
				<p>
					You are about to upgrade this software to version <strong>2.0</strong>. Please note that this will only update your database and NOT your files. It is strongly recommended that you first backup your database then your existing files in case something unexpected occurs. 
				</p>
				<p>
					Version 2.0 is a complete rebuild of the software with performance, maintainability and security in mind. For this reason, <strong>the whole structure</strong> was changed. It is a complete different version and has nothing to do with the previous version. So <strong>please read</strong> the update manually carefully in order to make this update as smoothly as possible. 
				</p>
				<p><strong>NOTE</strong> If you have made a lot of changes to the script and wish to keep those changes, <strong>DO NOT UPDATE</strong> as this will completely overwrite your changes. Also if you are happy with the current version and don't want to go through the struggle of updating this, don't update. Otherwise, click the button below to proceed.</p>

				<a href="update2.php?step=2" class="button">I am ready, please update my database</a>		
  		</div>  		
  	</div>
  </body>
</html>
<?php 
function get_query($dbinfo){
	$query[]="CREATE TABLE IF NOT EXISTS `".trim($dbinfo["prefix"])."bundle` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `name` varchar(255) NOT NULL,
	  `userid` mediumint(9) NOT NULL,
	  `date` datetime NOT NULL,
	  `access` varchar(10) NOT NULL DEFAULT 'private',
	  `view` int(11) NOT NULL DEFAULT '0',
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";

	$query[]="INSERT INTO  `{$dbinfo["prefix"]}settings` (`config` ,`var`) VALUES 
	('captcha_public', ''),
	('captcha_private', ''),
	('private', '0'),
	('facebook_app_id', ''),
	('facebook_secret', ''),
	('twitter_key', ''),
	('twitter_secret', ''),
	('safe_browsing', ''),
	('default_lang', 'en'),
	('user_activate', '0'),
	('domain_blacklist', ''),
	('keyword_blacklist', ''),
	('user_history', '0'),
	('timer', '');";

	$query[]="UPDATE  `{$dbinfo["prefix"]}settings` SET  `var` =  '0' WHERE  `config` =  'captcha';";
	$query[]="UPDATE  `{$dbinfo["prefix"]}settings` SET  `var` =  '' WHERE  `config` =  'analytic';";
	$query[]="UPDATE  `{$dbinfo["prefix"]}settings` SET  `var` =  'default' WHERE  `config` =  'theme';";

	$query[]="ALTER TABLE `{$dbinfo["prefix"]}url` ADD `meta_title` varchar(255) NOT NULL";
	$query[]="ALTER TABLE `{$dbinfo["prefix"]}url` ADD `pass` varchar(255) NOT NULL";
	$query[]="ALTER TABLE `{$dbinfo["prefix"]}url` ADD `meta_description` varchar(255) NOT NULL";
	$query[]="ALTER TABLE `{$dbinfo["prefix"]}url` ADD `ads` int(11) NOT NULL DEFAULT '1'";
	$query[]="ALTER TABLE `{$dbinfo["prefix"]}url` ADD `bundle` mediumint(9) NOT NULL";
	$query[]="ALTER TABLE `{$dbinfo["prefix"]}url` ADD `public` int(11) NOT NULL DEFAULT '0'";

	$query[]="ALTER TABLE  `{$dbinfo["prefix"]}user` ADD `username` varchar(255) NOT NULL";
	$query[]="ALTER TABLE  `{$dbinfo["prefix"]}user` ADD `ads` int(1) NOT NULL DEFAULT '1'";
	$query[]="ALTER TABLE  `{$dbinfo["prefix"]}user` ADD `active` int(11) NOT NULL DEFAULT '1'";
	$query[]="ALTER TABLE  `{$dbinfo["prefix"]}user` ADD `banned` int(1) NOT NULL DEFAULT '0'";
	$query[]="ALTER TABLE  `{$dbinfo["prefix"]}user` ADD `public` int(11) NOT NULL DEFAULT '0'";
	return $query;
}

?>
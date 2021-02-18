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
 * @subpackage Application installer
 */
	if(!isset($_SESSION)) session_start();
	$error="";
	$message=(isset($_SESSION["msg"])?$_SESSION["msg"]:"");
	if(!isset($_GET["step"]) || $_GET["step"]=="1" || $_GET["step"] < "1"){
		$step = "1";
	}elseif($_GET["step"] > "1" && $_GET["step"]<="5"){
		$step = $_GET["step"];
	}else{
		die("Oups. Looks like you did not follow the instructions! Please follow the instructions otherwise you will not be able to install this script.");
	}
	switch ($step) {
		case '2':
			if(file_exists("includes/config.php")) $error='Configuration file already exists. Please delete or rename "config.php" and recopy "config_sample.php" from the original zip file. You cannot continue until you do this.';  

			if(isset($_POST["step2"])){
			if (empty($_POST["host"]))  $error.="<p>- You forgot to enter your host.</p>"; 
            if (empty($_POST["name"])) $error.="<p>- You forgot to enter your database name.</p>"; 
            if (empty($_POST["user"])) $error.="<p>- You forgot to enter your username.</p>"; 
	            if(empty($error)){
					 try{
					    $db = new PDO("mysql:host=".$_POST["host"].";dbname=".$_POST["name"]."", $_POST["user"], $_POST["pass"]);
						generate_config($_POST);
		                $query=get_query();
						foreach ($query as $q) {
						  $db->query($q);
						} 
						$_SESSION["msg"]="Database has been successfully imported and configuration file has been created.";
						header("Location: install.php?step=3");
					  }catch (PDOException $e){
					    $error = $e->getMessage();
					  }
          }							
			}
		break;
		case '3':
			if(!file_exists("includes/config.php")) die("<div class='error'>The file includes/config.php cannot be found. If the file includes/config_sample.php exists rename that to includes/config.php and refresh this page.</div>");			
					@include("includes/config.php");
					

					$_SESSION["msg"]="";

					if($db->get("user",["admin"=>"1"], ["limit" => "1"])){
						$error.="<div class='error'>You have already created an admin account! You can no longer use this form.</div>"; 
					}

			    if(isset($_POST["step3"])){
			            if (empty($_POST["email"]))  $error.="<div class='error'>You forgot to enter your email.</div>"; 
			            if (empty($_POST["pass"])) $error.="<div class='error'>You forgot to enter your password.</div>"; 
			            if (empty($_POST["url"])) $error.="<div class='error'>You forgot to enter the url.</div>"; 
			    	if(!$error){

			    	$data=array(
				    	":admin"=>"1",
				    	":email"=>$_POST["email"],
				    	":username"=>$_POST["username"],
				    	":password"=>Main::encode($_POST["pass"]),
				    	":date"=>"NOW()",
				    	":pro"=>"1",
				    	":auth_key"=>Main::encode(Main::strrand()),
				    	":last_payment" => date("Y-m-d H:i:s",time()),
				    	":expiration" => date("Y-m-d H:i:s",time()+315360000),
				    	":api" => Main::strrand(12)
			    	);

					  $db->insert("user",$data);					  
					  $db->update("settings",array("var"=>"?"),array("config"=>"?"),array($_POST["url"],"url"));
					  $db->update("settings",array("var"=>"?"),array("config"=>"?"),array($_POST["email"],"email"));
					  $_SESSION["msg"]="Your admin account has been successfully created.";
					  $_SESSION["site"]=$_POST["url"];
					  $_SESSION["username"]=$_POST["username"];
					  $_SESSION["email"]=$_POST["email"];
					  $_SESSION["password"]=$_POST["pass"];
					  header("Location: install.php?step=4"); 
			        }   
			    }		
		break;
		case '4':
			$_SESSION["msg"]="";
			@include("includes/config.php");
					if(!file_exists(ROOT."/.htaccess")){
					  	$content = "### Generated on ".date("d-m-Y H:i:s", strtotime("now"))."\n\nRewriteEngine On\n\n# Handle Authorization Header\n\n
	RewriteCond %{HTTP:Authorization} ^(.*)\n\nRewriteRule .* - [e=HTTP_AUTHORIZATION:%1]\n\n#Rewritebase /\n\n## Admin \n\nRewriteCond %{REQUEST_FILENAME} !-d\n\nRewriteCond %{REQUEST_FILENAME} !-f\n\nRewriteRule ^admin/(.*)?$ admin/index.php?a=$1 [QSA,NC,L]\n\nRewriteRule ^sitemap.xml$ sitemap.php\n\n## App \n\nRewriteCond %{REQUEST_FILENAME} !-d\n\nRewriteCond %{REQUEST_FILENAME} !-f\n\nRewriteRule ^(.*)?$ index.php?a=$1	[QSA,NC,L]\n\nErrorDocument 404 /index.php?a=404";
					  	$file = fopen(ROOT."/.htaccess", "w");
					  	fwrite($file, $content);
					  	fclose($file);						
					}
		break;
		case '5':
			header("Location: index.php"); 
			unset($_SESSION);
			unlink(__FILE__);
			
			if(file_exists("main.zip")){
				unlink('main.zip');
			}
			if(file_exists("updater.php")){
				unlink('updater.php');
			}
		break;
	}
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Premium URL Shortener Installation</title>
	<style type="text/css">
		body{background-color: rgb(237,242,247);font-family:Helvetica, Arial;width:860px;line-height:25px;font-size:13px;margin:0 auto;}a{color:#009ee4;font-weight:700;text-decoration:none;}a:hover{color:#000;text-decoration:none;}.container{background: #fff;box-shadow: 0 4px 6px -1px rgba(0,0,0,.1), 0 2px 4px -1px rgba(0,0,0,.06);border-radius: 10px;display: block;overflow: hidden;margin: 50px 0;}.container h1{font-size:20px;display:block;border-bottom:1px solid #eee;margin:0!important;padding:20px 10px;}.container h2{color:#999;font-size:18px;margin:10px;}.container h3{background:#fff;border-bottom:1px solid #eee;border-radius:3px 0 0 0;text-align:center;margin:0;padding:20px 0;}.left{float:left;width:258px;}.right{float:left;width:601px;border-left:1px solid #eee;}.form{width:90%;display:block; padding: 10px 20px;}.form label{font-size:15px;font-weight:700;margin:20px 0px 5px;display: block;}.form label a{float:right;color:#009ee4;font:bold 12px Helvetica, Arial; padding-top: 5px;}.form .input{display: block;width: 95%;height: 15px;border: 1px #ddd solid;font: bold 15px Helvetica, Arial;color: #aaa;border-radius: 3px;margin: 10px 0;padding: 10px 25px;}.form .input:focus{border:1px #73B9D9 solid;outline:none;color:#222;box-shadow:inset 1px 1px 3px #ccc,0 0 0 3px #DEF1FA;}.form .button{height:35px;}.button{background-color: #4f37ac;font-weight: 700;background-image: -moz-linear-gradient(0deg, #0854a9 0%, #4f37ac 100%);background-image: -webkit-linear-gradient(0deg, #0854a9 0%, #4f37ac 100%);width:90%;display:block;text-decoration:none;text-align:center;border-radius: 2px;color:#fff;font:15px Helvetica, Arial bold;cursor:pointer;border-radius:25px;margin:30px auto; padding:10px 0;border:0;}.button:active,.button:hover{opacity: 0.9; color: #fff;}.content{color:#999;display:block;border-top:1px solid #eee;margin:10px 0;padding:10px;}li{color:#999;}li.current{color:#000;font-weight:700;}li span{float:right;margin-right:10px;font-size:11px;font-weight:700;color:#00B300;}.left > p{border-top:1px solid #eee;color:#999;font-size:12px;margin:0;padding:10px;}.left > p >a{color:#777;}.content > p{color:#222;font-weight:700;}span.ok{float:right;border-radius:3px;background-color: #59d8c5;font-weight: 700;background-image: -moz-linear-gradient(0deg, #59d8c5 0%, #68b835 100%);background-image: -webkit-linear-gradient(0deg, #59d8c5 0%, #68b835 100%);background-image: -ms-linear-gradient(0deg, #59d8c5 0%, #68b835 100%);color:#fff;padding:2px 10px;}span.fail{float:right;border-radius:3px;background-color: #FF3146;font-weight: 700;background-image: -moz-linear-gradient(0deg, #f04c74 0%, #FF3146 100%);background-image: -webkit-linear-gradient(0deg, #f04c74 0%, #FF3146 100%);background-image: -ms-linear-gradient(0deg, #f04c74 0%, #FF3146 100%);color:#fff;padding:2px 10px;}span.warning{float:right;border-radius:3px;background:#D27900;color:#fff;padding:2px 10px;}.message{background:#1F800D;color:#fff;font:bold 15px Helvetica, Arial;border:1px solid #000;padding:10px;}.error{    background-color: #FF3146;background-image: -moz-linear-gradient(0deg, #f04c74 0%, #FF3146 100%);background-image: -webkit-linear-gradient(0deg, #f04c74 0%, #FF3146 100%);background-image: -ms-linear-gradient(0deg, #f04c74 0%, #FF3146 100%);color:#fff;font:bold 15px Helvetica, Arial;margin:0;padding:10px;}.inner,.right > p{margin:10px;}
	</style>
  </head>
  <body>
  	<div class="container">
  		<div class="left">
			<h3>Installation Process</h3>
			<ol>
				<li<?php echo ($step=="1")?" class='current'":""?>>Requirement Check <?php echo ($step>"1")?"<span>Completed</span>":"" ?></li>
				<li<?php echo ($step=="2")?" class='current'":""?>>Database Installation<?php echo ($step>"2")?"<span>Completed</span>":"" ?></li>
				<li<?php echo ($step=="3")?" class='current'":""?>>Admin Creation<?php echo ($step>"3")?"<span>Completed</span>":"" ?></li>
				<li<?php echo ($step=="4")?" class='current'":""?>>Installation Complete</li>
			</ol>
			<p>
				<a href="https://gempixel.com/" target="_blank">Home</a> | 
				<a href="https://gempixel.com/products" target="_blank">Products</a> | 
				<a href="https://support.gempixel.com/" target="_blank">Support</a>
				<p>2012-<?php echo date("Y") ?> &copy; <a href="https://gempixel.com" target="_blank">GemPixel</a><br>All Rights Reserved.</p>
			</p>
  		</div>
  		<div class="right">
					<h1>Installation of Premium URL Shortener</h1> 
					<?php if(!empty($message)) echo "<div class='message'>$message</div>"; ?>
					<?php if(!empty($error)) echo "<div class='error'>$error</div>"; ?>
					<?php if($step=="1"): ?>		
						<h2>1.0 Requirement Check</h2>
						<p>
							These are some of the important requirements for this software. "Red" means it is vital to this script, "Orange" means it is required but not vital and "Green" means it is good. If one of the checks is "Red", you will not be able to install this script because without that requirement, the script will not work.
						</p>
						<div class="content">
							<p>
							PHP Version (v7.2+)
							<?php echo check('version')?>
							</p>
							It is very important to have at least PHP Version 7.2. It is highly recommended that you use 7.2 or later for best performance.
						</div>
						<div class="content">
							<p>PDO Driver must be enabled 
								<?php echo check('pdo')?>
							</p>
							PDO driver is very important so it must enabled. Without this, the script will not connect to the database hence it will not work at all. If this check fails, you will need to contact your web host and ask them to either enable it or configure it properly.
						</div>					
						<div class="content">
							<p><i>config_sample.php</i> must be accessible. 
								<?php echo check('config')?>
							</p>
							This installation will open that file to put values in so it must be accessible. Make sure that file is there in the <b>includes</b> folder and is writable.
						</div>		
						<div class="content">
							<p><i>content/</i> folder must be writable. 
								<?php echo check('content')?>
							</p>
							Many things will be uploaded to that folder so please make sure it has the proper permission.
						</div>												
						<div class="content">
							<p><i>allow_url_fopen</i> Enabled
								<?php echo check('file')?>
							</p>
							The function <strong>file_get_contents</strong> is used to interact with external servers or APIs.
						</div>
						<div class="content">
							<p>cURL Enabled <?php echo check('curl')?></p>
							cURL is used to interact with external servers or APIs.
						</div>				
					<?php if(!$error) echo '<a href="?step=2" class="button">Requirements are met. You can now proceed.</a>'?>
					<?php elseif($step=="2"): ?>	
					<h2>2.0 Database Configuration</h2>
					<p>
						Now you have to set up your database by filling the following fields. Make sure you fill them correctly.
					</p>
					<form method="post" action="?step=2" class="form">
					    <label>Database Host <a>Usually it is localhost.</a></label>
					    <input type="text" name="host" class="input" required />
					    
					    <label>Database Name</label>
					    <input type="text" name="name" class="input" required />
					    
					    <label>Database User </label>
					    <input type="text" name="user" class="input" required />    
					    
					    <label>Database Password</label>
					    <input type="password" name="pass" class="input" />   

					    <label>Database Prefix <a>Prefix for your tables (Optional) e.g. short_</a></label>
					    <input type="text" name="prefix" class="input" value="" />       

					    <label>Security Key <a>Keep this secret!</a></label>
					    <input type="text" name="key" class="input" value="<?php echo "PUS".md5(rand(0,100000)).md5(rand(0,100000)) ?>" />   

					    <button type="submit" name="step2" class='button'>Create configuration file</button>    
					</form>
					<?php elseif($step=="3"): ?>
					<p>
						Now you have to create an admin account by filling the fields below. Make sure to add a valid email and a strong password. For the site URL, make sure to remove the last slash.
					</p>
					  <form method="post" action="?step=3" class="form">
					        <label>Admin Email</label>
					        <input type="email" name="email" class="input" required />

					        <label>Admin Username</label>
					        <input type="text" name="username" class="input" minlenght="3" required />	

					        <label>Admin Password (min 5 characters)</label>
					        <input type="password" name="pass" class="input" minlength="5" required />   

					        <label>Site URL <a>Including http:// but without the ending slash "/"</a></label>
					        <input type="text" name="url" class="input" value="<?php echo get_domain() ?>" placeholder="http://" required />  

					        <input type="submit" name="step3" value="Finish installation" class='button' />     
					  </form>		
					<?php elseif($step=="4"): ?>
				       <p>
			 				The script has been successfully installed and your admin account has been created. Please click "Delete Install" button below to attempt to delete this file. Please make sure that it has been successfully deleted. 
				       </p>
				       <p>
				       	  Once clicked, you may see a blank page otherwise you will be redirected to your main page. If you see a blank, don't worry it is normal. All you have to do is to go to your main site, login using the info below and configure your site by clicking the "Admin" menu and then "Settings". Thanks for your purchase and enjoy :)
				       </p>
				       <p>
				       <strong>Login URL: <a href="<?php get('site') ?>/user/login" target="_blank"><?php get('site') ?>/user/login</a></strong> <br />
				       <strong>Email: <?php get('email') ?></strong> <br />
				       <strong>Username: <?php get('username') ?></strong> <br />
				       <strong>Password: <?php get('password') ?></strong>
				       </p>	       
				       <a href="?step=5" class="button">Delete install.php</a>	       
					<?php endif; ?>					
  		</div>  		
  	</div>
  </body>
</html>
<?php 
function get_domain(){
	$http = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
	$url = "{$http}://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$url = str_replace("/install.php?step=3", "", $url);
	return $url;
}
function get($what){
	if(isset($_SESSION[strip_tags(trim($what))])){
		echo $_SESSION[strip_tags(trim($what))];
	}
}
function check($what){
	switch ($what) {
		case 'version':
			if(PHP_VERSION >= "7.2"){
				return "<span class='ok'>You have ".PHP_VERSION."</span>";
			}else{
				global $error;
				$error.=1;
				return "<span class='fail'>You have ".PHP_VERSION."</span>";
			}
			break;
		case 'config':
			if(@file_get_contents('includes/config_sample.php') && is_writable('includes/config_sample.php')){
				return "<span class='ok'>Accessible</span>";
			}else{
				global $error;
				$error.=1;
				return "<span class='fail'>Not Accessible</span>";
			}
			break;
		case 'content':
			if(is_writable('content')){
				return "<span class='ok'>Accessible</span>";
			}else{
				global $error;
				$error.=1;
				return "<span class='fail'>Not Accessible</span>";
			}
			break;			
		case 'pdo':
			if(defined('PDO::ATTR_DRIVER_NAME') && class_exists("PDO")){
				return "<span class='ok'>Enabled</span>";
			}else{
				global $error;
				$error.=1;
				return "<span class='fail'>Disabled</span>";
			}
			break;
		case 'file':
			if(ini_get('allow_url_fopen')){
				return "<span class='ok'>Enabled</span>";
			}else{
				return "<span class='warning'>Disabled</span>";
			}
			break;	
		case 'curl':
			if(in_array('curl', get_loaded_extensions())){
				return "<span class='ok'>Enabled</span>";
			}else{
				return "<span class='warning'>Disabled</span>";
			}
			break;						
	}
}
function get_query(){

$query[] = "CREATE TABLE IF NOT EXISTS `".trim($_POST["prefix"])."ads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(191) DEFAULT NULL,
  `type` enum('728','468','300','resp','splash','frame') DEFAULT NULL,
  `code` text,
  `impression` int(12) DEFAULT '0',
  `enabled` enum('0','1') DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;";

$query[] = "CREATE TABLE IF NOT EXISTS `".trim($_POST["prefix"])."bundle` (
  `id` int(11) AUTO_INCREMENT,
  `name` varchar(191) NULL,
  `slug` varchar(191) NULL,
  `userid` mediumint(9) NULL,
  `date` datetime NULL,
  `access` varchar(10) NOT NULL DEFAULT 'private',
  `view` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;";

$query[] = "CREATE TABLE IF NOT EXISTS `".trim($_POST["prefix"])."coupons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(191) DEFAULT NULL,
  `description` text,
  `code` varchar(191) DEFAULT NULL,
  `discount` int(3) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `used` int(9) NOT NULL DEFAULT '0',
  `validuntil` timestamp NULL DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;";

$query[] = "CREATE TABLE IF NOT EXISTS `".trim($_POST["prefix"])."domains` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) DEFAULT NULL,
  `domain` varchar(191) DEFAULT NULL,
  `redirect` varchar(191) DEFAULT NULL,
  `redirect404` varchar(191) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;";

$query[] = "CREATE TABLE IF NOT EXISTS `".trim($_POST["prefix"])."overlay` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(9) DEFAULT NULL,
  `name` varchar(191) DEFAULT NULL,
  `type` varchar(191) NOT NULL DEFAULT 'message',
  `data` text,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;";

$query[] = "CREATE TABLE IF NOT EXISTS `".trim($_POST["prefix"])."page` (
  `id` int(11) AUTO_INCREMENT,
  `name` varchar(191) NULL,
  `seo` varchar(191) NULL,
  `content` text NULL,
  `menu` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=2 ;";

$query[] = "CREATE TABLE IF NOT EXISTS `".trim($_POST["prefix"])."payment` (
  `id` int(11) AUTO_INCREMENT,
  `tid` varchar(191) NULL,
  `userid` bigint(20) NULL,
  `status` varchar(191) NULL,
  `amount` decimal(10,2) NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expiry` datetime NULL,
  `trial_days` int(5) DEFAULT NULL,
  `data` text NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";

$query[] = "CREATE TABLE IF NOT EXISTS `".trim($_POST["prefix"])."plans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(191) DEFAULT NULL,
  `slug` varchar(191) DEFAULT NULL,
  `description` text,
  `icon` varchar(191) DEFAULT NULL,
  `trial_days` int(11) DEFAULT NULL,
  `price_monthly` float NOT NULL DEFAULT '0',
  `price_yearly` float NOT NULL DEFAULT '0',
  `free` int(1) NOT NULL DEFAULT '0',
  `numclicks` int(9) DEFAULT NULL,
  `numurls` int(9) DEFAULT NULL,
  `permission` text,
  `status` int(1) NOT NULL DEFAULT '0',
  `stripeid` varchar(191) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;";

$query[] = "CREATE TABLE IF NOT EXISTS `".trim($_POST["prefix"])."posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(191) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `slug` varchar(191) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `views` int(9) NOT NULL DEFAULT '0',
  `image` varchar(191) DEFAULT NULL,
  `meta_title` varchar(191) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `published` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;";


$query[] = "CREATE TABLE IF NOT EXISTS `".trim($_POST["prefix"])."reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` text,
  `bannedlink` text,
  `type` varchar(191) DEFAULT NULL,
  `ip` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;";

$query[] = "CREATE TABLE IF NOT EXISTS `".trim($_POST["prefix"])."settings` (
  `config` varchar(20),
  `var` text NULL,
  PRIMARY KEY (`config`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;";


$query[] = "INSERT INTO `".trim($_POST["prefix"])."settings` (`config`, `var`) VALUES
('url', ''),
('title', ''),
('description', ''),
('api', '1'),
('user', '1'),
('sharing', '1'),
('geotarget', '1'),
('adult', '1'),
('maintenance', '0'),
('keywords', ''),
('theme', 'cleanex'),
('apikey', ''),
('ads', '1'),
('captcha', '0'),
('ad728', ''),
('ad468', ''),
('ad300', ''),
('frame', '0'),
('facebook', ''),
('twitter', ''),
('email', ''),
('fb_connect', '0'),
('analytic', ''),
('private', '0'),
('facebook_app_id', ''),
('facebook_secret', ''),
('twitter_key', ''),
('twitter_secret', ''),
('safe_browsing', ''),
('captcha_public', ''),
('captcha_private', ''),
('tw_connect', '0'),
('multiple_domains', '0'),
('domain_names', ''),
('tracking', '1'),
('update_notification', '1'),
('default_lang', ''),
('user_activate', '0'),
('domain_blacklist', ''),
('keyword_blacklist', ''),
('user_history', '0'),
('pro_yearly', ''),
('show_media', '0'),
('pro_monthly', ''),
('paypal_email', ''),
('logo', ''),
('timer', ''),
('smtp', ''),
('style', ''),
('font', ''),
('currency', 'USD'),
('news', '<strong>Installation successful</strong> Please go to the admin panel to configure important settings including this message!'),
('gl_connect', '0'),
('require_registration', '0'),
('phish_api', ''),
('phish_username', ''),
('aliases', ''),
('pro', '1'),
('google_cid', ''),
('google_cs', ''),
('public_dir', '0'),
('devicetarget', '1'),
('homepage_stats', '1'),
('home_redir', ''),
('detectadblock', '0'),
('timezone', ''),
('freeurls', '10'),
('allowdelete', '1'),
('serverip', ''),
('favicon', ''),
('advanced', '0'),
('purchasecode', ''),
('alias_length', '5'),
('theme_config', ''),
('schemes', 'https,ftp,http'),
('email.activated', '<p><b>Hello</b></p><p>Your account has been successfully activated at {site.title}.</p>'),
('email.activation', '<p><b>Hello!</b></p><p>You have been successfully registered at {site.title}. To login you will have to activate your account by clicking the URL below.</p><p><a href=\"http://{user.activation}\" target=\"_blank\">{user.activation}</a></p>'),
('email.registration', '<p><b>Hello!</b></p><p>You have been successfully registered at {site.title}. You can now login to our site at <a href=\"http://{site.link}\" target=\"_blank\">{site.link}</a>.</p>'),
('email.reset', '<p><b>A request to reset your password was made.</b> If you <b>didn\'t</b> make this request, please ignore and delete this email otherwise click the link below to reset your password.</p>\r\n		      <b><div style=\"text-align: center;\"><b><a href=\"http://{user.activation}\" class=\"link\">Click here to reset your password.</a></b></div></b></p><p>\r\n		      <p>If you cannot click on the link above, simply copy &amp; paste the following link into your browser.</p>\r\n		      <p><a href=\"http://{user.activation}\" target=\"_blank\">{user.activation}</a></p>\r\n		      <p><b>Note: This link is only valid for one day. If it expires, you can request another one.</b></p>'),
('email.invitation', '<p><b>Hello!</b></p><p>You have been invited to join our team at {site.title}. To accept the invitation, please click the link below.</p><p><a href=\"http://{user.invite}\" target=\"_blank\">{user.invite}</a></p>'),
('blog', '1'),
('root_domain', '1'),
('slackclientid', ''),
('slacksecretid', ''),
('slackcommand', ''),
('slacksigningsecret', ''),
('contact', '1'),
('report', '1'),
('customheader', ''),
('customfooter', ''),
('saleszapier', ''),
('pppublic', ''),
('ppprivate', ''),
('manualapproval', '0');";


$query[] = "CREATE TABLE IF NOT EXISTS `".trim($_POST["prefix"])."splash` (
  `id` int(11) AUTO_INCREMENT,
  `userid` bigint(12) NULL,
  `name` varchar(191) NULL,
  `data` text NULL,
  `date` datetime NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";


$query[] = "CREATE TABLE IF NOT EXISTS `".trim($_POST["prefix"])."stats` (
  `id` int(11) AUTO_INCREMENT,
  `short` varchar(191) NULL,
  `urlid` bigint(20) NULL,
  `urluserid` bigint(20) NOT NULL DEFAULT '0',
  `date` datetime NULL,
  `ip` varchar(191) NULL,
  `country` varchar(191) NULL,
  `domain` varchar(191) NULL,
  `referer` text NULL,
  `browser` text NULL,
  `os` text NULL,    
  PRIMARY KEY (`id`),
  KEY `short` (`short`),
  KEY `urlid` (`urlid`), 
  KEY `urluserid` (`urluserid`), 
  KEY `ip` (`ip`),
  KEY `country` (`country`),
  KEY `domain` (`domain`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";

$query[] = "CREATE TABLE IF NOT EXISTS `".trim($_POST["prefix"])."url` (
  `id` int(20) AUTO_INCREMENT,
  `userid` int(16) NOT NULL DEFAULT '0',
  `alias` varchar(191) NULL,
  `custom` varchar(191) NULL,
  `url` text NULL,
  `location` text NULL,
  `devices` text NULL,
  `domain` text NULL,
  `description` text NULL,
  `date` datetime NULL,
  `pass` varchar(191) NULL,
  `click` bigint(20) NOT NULL DEFAULT '0',
  `uniqueclick` bigint(20) NOT NULL DEFAULT '0',
  `meta_title` varchar(191) NULL,
  `meta_description` text NULL,
  `ads` int(1) NOT NULL DEFAULT '1',
  `bundle` mediumint(9) NULL,
  `public` int(1) NOT NULL DEFAULT '0',
  `archived` int(1) NOT NULL DEFAULT '0',
  `type` varchar(64) NULL,
  `pixels` varchar(191) NULL,
  `expiry` date NULL,
  `parameters` text NULL,
  `status` INT(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `alias` (`alias`),
  KEY `custom` (`custom`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";


$query[] = "CREATE TABLE IF NOT EXISTS `".trim($_POST["prefix"])."user` (
  `id` int(11) AUTO_INCREMENT,
  `auth` text NULL,
  `auth_id` varchar(191) NULL,
  `admin` int(1) NOT NULL DEFAULT '0',
  `email` varchar(191) NULL,
  `name` varchar(191) NULL,
  `username` varchar(20) NULL,
  `password` varchar(191) NULL,
  `address` text NULL,
  `date` datetime NULL,
  `api` varchar(20) NULL,
  `ads` int(1) NOT NULL DEFAULT '1',
  `active` int(1) NOT NULL DEFAULT '1',
  `banned` int(1) NOT NULL DEFAULT '0',
  `public` int(1) NOT NULL DEFAULT '0',
  `domain` varchar(191) NULL,
  `media` int(1) NOT NULL DEFAULT '0',
  `splash_opt` int(1) NOT NULL DEFAULT '0',
  `splash` text NULL,
  `auth_key` varchar(191) NULL,
  `last_payment` datetime NULL,
  `expiration` datetime NULL,
  `pro` int(1) NOT NULL DEFAULT '0',
  `planid` int(9) DEFAULT NULL,
  `overlay` text NULL,
  `fbpixel` text NULL,
  `linkedinpixel` text NULL,
  `adwordspixel` text NULL,
  `twitterpixel` text NULL,
  `adrollpixel` text NULL,
  `quorapixel` text NULL,
  `gtmpixel` text,
  `defaulttype` varchar(191) NULL,
  `teamid` int(11) NULL,
  `teampermission` text NULL,
  `secret2fa` varchar(191) NULL,
  `slackid` varchar(191) DEFAULT NULL,
  `zapurl` varchar(191) DEFAULT NULL,
  `zapview` varchar(191) DEFAULT NULL,
  `trial` int(1) NOT NULL DEFAULT '0',
  `profiledata` text,
  `avatar` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `api` (`api`),
  KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";

$query[] = "CREATE TABLE IF NOT EXISTS `".trim($_POST["prefix"])."zender_actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `event` tinyint(4) NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `devices` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `keywords` text COLLATE utf8mb4_unicode_ci,
  `link` mediumtext COLLATE utf8mb4_unicode_ci,
  `message` mediumtext COLLATE utf8mb4_unicode_ci,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;";

$query[] = "CREATE TABLE IF NOT EXISTS `".trim($_POST["prefix"])."zender_contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `gid` int(11) NOT NULL,
  `phone` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;";

$query[] = "CREATE TABLE IF NOT EXISTS `".trim($_POST["prefix"])."zender_devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `did` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `version` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `manufacturer` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;";

$query[] = "CREATE TABLE IF NOT EXISTS `".trim($_POST["prefix"])."zender_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;";

$query[] = "CREATE TABLE IF NOT EXISTS `".trim($_POST["prefix"])."zender_keys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `key` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `devices` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `permissions` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;";

$query[] = "CREATE TABLE IF NOT EXISTS `".trim($_POST["prefix"])."zender_languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iso` char(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `translations` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;";

$query[] = "CREATE TABLE IF NOT EXISTS `".trim($_POST["prefix"])."zender_packages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `send_limit` int(11) NOT NULL,
  `receive_limit` int(11) NOT NULL,
  `contact_limit` int(11) NOT NULL,
  `device_limit` int(11) NOT NULL,
  `key_limit` int(11) NOT NULL,
  `webhook_limit` int(11) NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` float NOT NULL,
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;";

$query[] = "INSERT INTO `{$_POST["prefix"]}zender_packages` VALUES (1,250,250,50,3,5,5,'Starter',0,'2020-04-09 02:26:47') ;";

$query[] = "CREATE TABLE IF NOT EXISTS `".trim($_POST["prefix"])."zender_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roles` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `logged` tinyint(4) NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;";

$query[] = "CREATE TABLE IF NOT EXISTS `".trim($_POST["prefix"])."zender_quota` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `sent` int(11) NOT NULL,
  `received` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;";

$query[] = "CREATE TABLE IF NOT EXISTS `".trim($_POST["prefix"])."zender_received` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `did` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `receive_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;";

$query[] = "CREATE TABLE IF NOT EXISTS `".trim($_POST["prefix"])."zender_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `permissions` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;";

$query[] = "CREATE TABLE IF NOT EXISTS `".trim($_POST["prefix"])."zender_scheduled` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `did` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sim` tinyint(4) NOT NULL,
  `groups` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `numbers` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `repeat` tinyint(4) NOT NULL,
  `send_date` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;";

$query[] = "CREATE TABLE IF NOT EXISTS `".trim($_POST["prefix"])."zender_sent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `did` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sim` tinyint(4) NOT NULL,
  `phone` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL,
  `priority` tinyint(4) NOT NULL,
  `api` tinyint(4) NOT NULL,
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;";

$query[] = "CREATE TABLE IF NOT EXISTS `".trim($_POST["prefix"])."zender_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;";

$query[] = "INSERT INTO `{$_POST["prefix"]}zender_settings` VALUES (1,'site_name','Zender'),(2,'site_desc','Your awesome website!'),(3,'purchase_code',''),(4,'default_lang','1'),(5,'registrations','1'),(6,'mail_function','2'),(7,'site_mail','noreply@yourdomain.com'),(8,'smtp_host','smtp.gmail.com'),(9,'smtp_port','587'),(10,'smtp_username',''),(11,'smtp_password',''),(12,'paypal_username',''),(13,'paypal_password',''),(14,'paypal_signature',''),(15,'stripe_secret',''),(16,'recaptcha_key',''),(17,'recaptcha_secret',''),(18,'package_name','com.zender.gateway'),(19,'app_name','Zender Gateway'),(20,'app_desc','The awesome app!'),(21,'app_color','#2f3237'),(22,'app_send','5'),(23,'app_receive','60'),(24,'builder_email',''),(25,'protocol','1'),(26,'paypal_test','1'),(28,'theme_background','#2f3237'),(29,'theme_highlight','#ffffff'),(30,'stripe_key',''),(31,'mollie_key',''),(32,'currency','usd'),(33,'providers','paypal,stripe,mollie'),(34,'livechat','1') ;";

$query[] = "CREATE TABLE IF NOT EXISTS `".trim($_POST["prefix"])."zender_subscriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;";

$query[] = "CREATE TABLE IF NOT EXISTS `".trim($_POST["prefix"])."zender_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `format` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;";

$query[] = "CREATE TABLE IF NOT EXISTS `".trim($_POST["prefix"])."zender_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `provider` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;";

$query[] = "CREATE TABLE IF NOT EXISTS `".trim($_POST["prefix"])."zender_vouchers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package` int(11) NOT NULL,
  `code` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;";

$query[] = "CREATE TABLE IF NOT EXISTS `".trim($_POST["prefix"])."zender_webhooks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `secret` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `devices` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;";

$query[] = "CREATE TABLE IF NOT EXISTS `".trim($_POST["prefix"])."zender_widgets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(4) NOT NULL,
  `size` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;";

$query[] = "INSERT INTO `{$_POST["prefix"]}zender_widgets` VALUES (1,2,'lg','center','la la-gavel','Terms of Service','&lt;h3&gt;Terms of Service : &lt;/h3&gt;\n  &lt;p&gt;By using {system_site_name} you agree to and are bound by these Terms and Conditions in their entirety and, without reservation, all applicable laws and regulations, and you agree that you are responsible for compliance with\n      any applicable laws. These Terms of Service govern your use of this website. If you do not agree with any of these terms, you are prohibited from using {system_site_name}.\n  &lt;/p&gt;\n\n\n  &lt;h3&gt;Acceptable use : &lt;/h3&gt;\n  &lt;ul&gt;\n      &lt;li&gt;\n          &lt;i class=&quot;fa fa-circle&quot;&gt;&lt;/i&gt;\n          &lt;p&gt;You must not use {system_site_name} in any way that can cause damage to {system_site_name} or in any way which is unlawful, illegal, fraudulent or harmful, or in connection with any illegal, fraudulent, or harmful activity.\n          &lt;/p&gt;\n      &lt;/li&gt;\n      &lt;li&gt;\n          &lt;i class=&quot;fa fa-circle&quot;&gt;&lt;/i&gt;\n          &lt;p&gt;You must not use this website to send any sort of commercial communications.\n          &lt;/p&gt;\n      &lt;/li&gt;\n      &lt;li&gt;\n          &lt;i class=&quot;fa fa-circle&quot;&gt;&lt;/i&gt;\n          &lt;p&gt;You must not use this website for any purposes related to marketing without the permission of {system_site_name}.&lt;/p&gt;\n      &lt;/li&gt;\n      &lt;li&gt;\n          &lt;i class=&quot;fa fa-circle&quot;&gt;&lt;/i&gt;\n          &lt;p&gt;You must not use this website to publish or distribute any material which consists of (or is linked to) any spyware, computer virus, Trojan horse, worm, keylogger, rootkit, or other malicious software.&lt;/p&gt;\n      &lt;/li&gt;\n  &lt;/ul&gt;\n\n  &lt;h3&gt;Membership : &lt;/h3&gt;\n  &lt;ul&gt;\n      &lt;li&gt;\n          &lt;i class=&quot;fa fa-circle&quot;&gt;&lt;/i&gt;\n          &lt;p&gt;Users must be 18 years old and above or 13 years to 18 years old with parental permission. A user between the ages of 13 to 18 certifies that a parent has given permission before signing up. &lt;/p&gt;\n      &lt;/li&gt;\n      &lt;li&gt;\n          &lt;i class=&quot;fa fa-circle&quot;&gt;&lt;/i&gt;\n          &lt;p&gt;Users must provide valid and truthful information during all stages. &lt;/p&gt;\n      &lt;/li&gt;\n      &lt;li&gt;\n          &lt;i class=&quot;fa fa-circle&quot;&gt;&lt;/i&gt;\n          &lt;p&gt;Users must not create more than one account per person, as having multiple accounts may result in all accounts being suspended and all points forfeited\n          &lt;/p&gt;\n      &lt;/li&gt;\n      &lt;li&gt;\n          &lt;i class=&quot;fa fa-circle&quot;&gt;&lt;/i&gt;\n          &lt;p&gt;Users must not use a proxy or attempt to mask or reroute their internet connection. That will result in your all accounts being suspended.&lt;/p&gt;\n      &lt;/li&gt;\n      &lt;li&gt;\n          &lt;i class=&quot;fa fa-circle&quot;&gt;&lt;/i&gt;\n          &lt;p&gt;Account balance may not be transferred, exchanged, sold, or otherwise change ownership under any circumstances, except by {system_site_name}&lt;/p&gt;\n      &lt;/li&gt;\n      &lt;li&gt;\n          &lt;i class=&quot;fa fa-circle&quot;&gt;&lt;/i&gt;\n          &lt;p&gt;We reserve the right to close your account, and forfeit any points, if you have violated our terms of service agreement. &lt;/p&gt;\n      &lt;/li&gt;\n      &lt;li&gt;\n          &lt;i class=&quot;fa fa-circle&quot;&gt;&lt;/i&gt;\n          &lt;p&gt;We reserve the right to close your account due to inactivity of 9 or more months. An inactive account is defined as an account that has not earned any gems for 9 or more months&lt;/p&gt;\n      &lt;/li&gt;\n  &lt;/ul&gt;\n\n  &lt;h3&gt;Indemnity : &lt;/h3&gt;\n  &lt;p&gt;You hereby indemnify {system_site_name} and undertake to keep {system_site_name} indemnified against any losses, damages, costs, liabilities, and/or expenses (including without limitation legal expenses) and any amounts paid by {system_site_name}\n      to a third party in settlement of a claim or dispute on the advice of {system_site_name}’s legal advisers) incurred or suffered by {system_site_name} arising out of any breach by you of any provision of these terms and conditions,\n      or arising out of any claim that you have breachedany provision of these terms and conditions.\n  &lt;/p&gt;\n\n  &lt;h3&gt;No warranties : &lt;/h3&gt;\n  &lt;p&gt;{system_site_name} is provided “as is” without any representations or warranties. {system_site_name} makes no representations or warranties in relation to this website or the information and materials provided on this website.&lt;/p&gt;\n  &lt;p&gt;{system_site_name} does not warrant that:&lt;/p&gt;\n\n  &lt;ul&gt;\n      &lt;li&gt;\n          &lt;i class=&quot;fa fa-circle&quot;&gt;&lt;/i&gt;\n          &lt;p&gt;The website will be constantly available, or available at all moving forward.\n          &lt;/p&gt;\n      &lt;/li&gt;\n      &lt;li&gt;\n          &lt;i class=&quot;fa fa-circle&quot;&gt;&lt;/i&gt;\n          &lt;p&gt;The information on this website is complete, true, or non-misleading.&lt;/p&gt;\n      &lt;/li&gt;\n  &lt;/ul&gt;\n\n  &lt;h3&gt;Privacy : &lt;/h3&gt;\n  &lt;p&gt;For details about our privacy policy, please refer to the privacy policy section.&lt;/p&gt;\n\n  &lt;h3&gt;Unenforceable provisions : &lt;/h3&gt;\n  &lt;p&gt;If any provision of this website disclaimer is, or is found to be, unenforceable under applicable law, that will not affect the enforceability of the other provisions of this website disclaimer.&lt;/p&gt;\n\n  &lt;h3&gt;Links : &lt;/h3&gt;\n  &lt;p&gt;Responsibility for the content of external links (to web pages of third parties) lies solely with the operators of the linked pages.&lt;/p&gt;\n\n  &lt;h3&gt;Modifications: &lt;/h3&gt;\n  &lt;p&gt;{system_site_name} may revise these terms of use for its website at any time without notice. By using this web site you are agreeing to be bound by the then current version of these terms of service.&lt;/p&gt;\n\n  &lt;h3&gt;Breaches of these terms and conditions: &lt;/h3&gt;\n  &lt;ul&gt;\n      &lt;li&gt;\n          &lt;i class=&quot;fa fa-circle&quot;&gt;&lt;/i&gt;\n          &lt;p&gt;{system_site_name} reserves the rights under these terms and conditions to take action if you breach these terms and conditions in any way. &lt;/p&gt;\n      &lt;/li&gt;\n      &lt;li&gt;\n          &lt;i class=&quot;fa fa-circle&quot;&gt;&lt;/i&gt;\n          &lt;p&gt;{system_site_name} may take such action as seems appropriate to deal with the breach, including suspending your access to the website, suspending your earnings made trough {system_site_name},prohibiting you from accessing the\n              website, or bringing court proceedings against you.&lt;/p&gt;\n      &lt;/li&gt;\n  &lt;/ul&gt;','2020-04-18 09:40:09'),(2,2,'lg','center','la la-lock','Privacy Policy','&lt;h3&gt;Your privacy is important to us: &lt;/h3&gt;\n&lt;p&gt;Therefore, we guarantee that:&lt;/p&gt;\n&lt;ul&gt;\n    &lt;li&gt;\n        &lt;i class=&quot;fa fa-circle&quot;&gt;&lt;/i&gt;\n        &lt;p&gt;We do not rent or sell your personal information to anyone.&lt;/p&gt;\n    &lt;/li&gt;\n    &lt;li&gt;\n        &lt;i class=&quot;fa fa-circle&quot;&gt;&lt;/i&gt;\n        &lt;p&gt;Any personal information you provide will be secured by us.&lt;/p&gt;\n    &lt;/li&gt;\n    &lt;li&gt;\n        &lt;i class=&quot;fa fa-circle&quot;&gt;&lt;/i&gt;\n        &lt;p&gt;You will be able to erase all the data we have stored on you at any given time. To request data termination, please contact our customer support.&lt;/p&gt;\n    &lt;/li&gt;\n&lt;/ul&gt;\n\n&lt;h3&gt;Third-party services: &lt;/h3&gt;\n&lt;p&gt;We use third-party services in order to operate our website. Please note that these services may contain links to third-party apps, websites or services that are not operated by us. We make no representation or warranties\n    with regard to and are not responsible for the content, functionality, legality, security, accuracy, or other aspects of such third-party apps, websites or services. Note that, when accessing and/or using these third-party\n    services, their own privacy policy may apply.&lt;/p&gt;\n\n&lt;h3&gt;Google Analytics: &lt;/h3&gt;\n&lt;p&gt;This website uses Google Analytics, a web analytics service provided by Google, Inc. (“Google”). Google Analytics uses “cookies”, which are text files placed on your computer, to help the website analyze how users use the\n    site. The information generated by the cookie about your use of the website will be transmitted to and stored by Google on servers in the United States . In case IP-anonymisation is activated on this website, your IP\n    address will be truncated within the area of Member States of the European Union or other parties to the Agreement on the European Economic Area. Only in exceptional cases the whole IP address will be first transferred\n    to a Google server in the USA and truncated there. The IP-anonymisation is active on this website. Google will use this information on behalf of the operator of this website for the purpose of evaluating your use of\n    the website, compiling reports on website activity for website operators and providing them other services relating to website activity and internet usage. The IP-address, that your Browser conveys within the scope\n    of Google Analytics, will not be associated with any other data held by Google. You may refuse the use of cookies by selecting the appropriate settings on your browser, however please note that if you do this you may\n    not be able to use the full functionality of {system_site_name}. You can also opt-out from being tracked by Google Analytics with effect for the future by downloading and installing Google Analytics Opt-out Browser Addon\n    for your current web browser: https://tools.google.com/dlpage/gaoptout?hl=en.\n&lt;/p&gt;\n\n&lt;h3&gt;Information we collect: &lt;/h3&gt;\n&lt;p&gt;Information we collect: &lt;/p&gt;\n&lt;ul&gt;\n    &lt;li&gt;\n        &lt;i class=&quot;fa fa-circle&quot;&gt;&lt;/i&gt;\n        &lt;p&gt;Google ID (to identify you in our database)&lt;/p&gt;\n    &lt;/li&gt;\n    &lt;li&gt;\n        &lt;i class=&quot;fa fa-circle&quot;&gt;&lt;/i&gt;\n        &lt;p&gt;Google First &amp;amp; Last name&lt;/p&gt;\n    &lt;/li&gt;\n    &lt;li&gt;\n        &lt;i class=&quot;fa fa-circle&quot;&gt;&lt;/i&gt;\n        &lt;p&gt;Google Email&lt;/p&gt;\n    &lt;/li&gt;\n    &lt;li&gt;\n        &lt;i class=&quot;fa fa-circle&quot;&gt;&lt;/i&gt;\n        &lt;p&gt;Google avatar image&lt;/p&gt;\n    &lt;/li&gt;\n&lt;/ul&gt;\n&lt;p&gt;We do not collect passwords or any other sensitive information.&lt;/p&gt;','2020-04-18 09:41:12'),(3,2,'lg','center','la la-info-circle','About Company','&lt;p&gt;The growth of virtual items and microtransactions have been enormous over the last few years. The prices for these items or microtransactions are often twice the price of the game itself. We understand people want these\n    exclusive in-game rewards, but we also see how they are too expensive for many. Therefore we came up with the idea, {system_site_name}.\n&lt;/p&gt;\n&lt;p&gt;We started providing a service directly targeted towards the game Counter-Strike: Global Offensive, however, we quickly came to realize this had the potential to reach a much broader audience. Now we have expanded our service\n    for not only gamers, but also others that want gift cards to shop at their favorite place, cryptocurrencies to start their crypto adventure, or just direct PayPal cash to spend on whatever you want.&lt;/p&gt;\n&lt;p&gt;We’re excited for what the future has to bring, and we will continuously be pushing out updates and new features to keep {system_site_name} the leading platform in the GPT industry\n&lt;/p&gt;\n&lt;p&gt;The growth of virtual items and microtransactions have been enormous over the last few years. The prices for these items or microtransactions are often twice the price of the game itself. We understand people want these\n    exclusive in-game rewards, but we also see how they are too expensive for many. Therefore we came up with the idea, {system_site_name}.\n&lt;/p&gt;\n                    ','2020-04-18 09:41:31'),(4,2,'xl','center','la la-terminal','API Guide','&lt;div class=&quot;embed-responsive&quot;&gt;\n    &lt;iframe class=&quot;embed-responsive-item position-relative&quot; scrolling=&quot;no&quot; zender-iframe=&quot;{site_url}/api&quot;&gt;&lt;/iframe&gt;\n&lt;/div&gt;','2020-04-21 22:08:08'),(5,1,'sm','center','','300x600','&lt;div class=&quot;text-center&quot;&gt;\n    &lt;img src=&quot;//assets.titansystems.xyz/300x600.png&quot; class=&quot;img-fluid&quot;&gt;\n&lt;/div&gt;','2020-04-20 19:32:14'),(6,1,'sm','center','','728x90','&lt;div class=&quot;m-4&quot;&gt;\n  &lt;div class=&quot;text-center&quot;&gt;\n      &lt;img src=&quot;//assets.titansystems.xyz/728x90.png&quot; class=&quot;img-fluid&quot;&gt;\n  &lt;/div&gt;\n&lt;/div&gt;','2020-04-20 18:36:33'),(7,1,'sm','center','','Clients','&lt;div class=&quot;row min-30 flex center&quot;&gt;\n     &lt;div class=&quot;col-lg-2 col-md-3 col-6&quot;&gt;\n        &lt;div class=&quot;company-item&quot;&gt;\n            &lt;img src=&quot;{_assets(&quot;images/brand.png&quot;)}&quot;&gt;\n        &lt;/div&gt;\n    &lt;/div&gt;\n\n    &lt;div class=&quot;col-lg-2 col-md-3 col-6&quot;&gt;\n        &lt;div class=&quot;company-item&quot;&gt;\n            &lt;img src=&quot;{_assets(&quot;images/brand.png&quot;)}&quot;&gt;\n        &lt;/div&gt;\n    &lt;/div&gt;\n\n    &lt;div class=&quot;col-lg-2 col-md-3 col-6&quot;&gt;\n        &lt;div class=&quot;company-item&quot;&gt;\n            &lt;img src=&quot;{_assets(&quot;images/brand.png&quot;)}&quot;&gt;\n        &lt;/div&gt;\n    &lt;/div&gt;\n\n    &lt;div class=&quot;col-lg-2 col-md-3 col-6&quot;&gt;\n        &lt;div class=&quot;company-item&quot;&gt;\n            &lt;img src=&quot;{_assets(&quot;images/brand.png&quot;)}&quot;&gt;\n        &lt;/div&gt;\n    &lt;/div&gt;\n\n    &lt;div class=&quot;col-lg-2 col-md-3 col-6&quot;&gt;\n        &lt;div class=&quot;company-item&quot;&gt;\n            &lt;img src=&quot;{_assets(&quot;images/brand.png&quot;)}&quot;&gt;\n        &lt;/div&gt;\n    &lt;/div&gt;\n\n    &lt;div class=&quot;col-lg-2 col-md-3 col-6&quot;&gt;\n        &lt;div class=&quot;company-item&quot;&gt;\n            &lt;img src=&quot;{_assets(&quot;images/brand.png&quot;)}&quot;&gt;\n        &lt;/div&gt;\n    &lt;/div&gt;\n&lt;/div&gt;','2020-04-22 13:15:24'),(12,2,'lg','center','la la-phone','Contact','\n&lt;h4 class=&quot;text-uppercase&quot;&gt;\n  For questions &amp; concerns\n&lt;/h4&gt;\n&lt;p&gt;Send us an email at mail@domain.com&lt;/p&gt;\n\n&lt;div class=&quot;mt-3&quot;&gt;\n  &lt;img src=&quot;{_assets(&quot;images/map.png&quot;)}&quot; style=&quot;width: 100%&quot;&gt;\n&lt;/div&gt;','2020-04-23 16:25:27'),(13,1,'sm','center','','300x300','&lt;div class=&quot;text-center&quot;&gt;\n    &lt;img src=&quot;//assets.titansystems.xyz/300x300.png&quot; class=&quot;img-fluid&quot;&gt;\n&lt;/div&gt;','2020-04-24 12:27:31') ;";

$query[]=<<<QUERY
INSERT INTO `{$_POST["prefix"]}page` (`id`, `name`, `seo`, `content`, `menu`) VALUES
(1, 'Terms and Conditions', 'terms', 'Please edit me when you can. I am very important.', 1);
QUERY;
return $query;
}
function generate_config($array){
	if(!empty($array)){
	    $file = file_get_contents('includes/config_sample.php');
	    $file = str_replace("RHOST",trim($array["host"]),$file);
	    $file = str_replace("RDB",trim($array["name"]),$file);
	    $file = str_replace("RUSER",trim($array["user"]),$file);
	    $file = str_replace("RPASS",trim($array["pass"]),$file);
	    $file = str_replace("RPRE",trim($array["prefix"]),$file);
	    $file = str_replace("RPUB",trim(md5(api())),$file);
	    $file = str_replace("RKEY",trim($array["key"]),$file);
	    $fh = fopen('includes/config_sample.php', 'w') or die("Can't open config_sample.php. Make sure it is writable.");
	    fwrite($fh, $file);
	    fclose($fh);
	    rename("includes/config_sample.php", "includes/config.php");
	}
}
function api(){
  $l = "12";
  $api = "";
  $r = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
  srand((double)microtime()*1000000);
  for($i = 0; $i < $l; $i++) { 
    $api .= $r[rand()%strlen($r)]; 
  }
  return $api;
}
?>
<?php
/**
 * ====================================================================================
 *                           Premium URL Shortener (c) KBRmedia
 * ----------------------------------------------------------------------------------
 * @copyright This software is exclusively sold at CodeCanyon.net. If you have downloaded this
 *  from another site or received it from someone else than me, then you are engaged
 *  in an illegal activity. You must delete this software immediately or buy a proper
 *  license from http://codecanyon.net/user/KBRmedia/portfolio?ref=KBRmedia.
 *
 *  Thank you for your cooperation and don't hesitate to contact me if anything :)
 * ====================================================================================
 *
 * @author KBRmedia (http://gempixel.com)
 * @link http://gempixel.com 
 * @license http://gempixel.com/license
 * @package Theme Functions
 */

if(_VERSION < 5.4) return FALSE;

Main::hook("header", "themeheader");
Main::hook("admin.theme.hasOption", "themehasOption");
Main::hook("admin.theme.getOptions", "themegetOptions");

Main::hook("menu.dropdown", "customMenu");

/**
 * [customMenu description]
 * @author GemPixel <https://gempixel.com>
 * @version 1.0
 * @return  [type] [description]
 */
function customMenu(){
	if (isDark()){
		return '<li><a href="'.Main::href("user/settings").'?darkmode=off"><span class="fa fa-moon"></span> '.e("Disable Dark Mode").'</a></li>';
	} else {
		return '<li><a href="'.Main::href("user/settings").'?darkmode=on"><span class="fa fa-moon"></span>'.e("Activate Dark Mode").'</a></li>';		
	}
}
/**
 * [themeheader description]
 * @author KBRmedia <https://gempixel.com>
 * @version 1.0
 * @param   [type] $user [description]
 * @return  [type]       [description]
 */
function themeheader($user){

	if(isset($_COOKIE["darkmode"]) && $_COOKIE["darkmode"] == "on"){
		Main::set("body_class", "dark-mode");
	}else{
		Main::set("body_class", "");
	}

	if(isset($_GET["darkmode"]) && $_GET["darkmode"] == "on"){
		Main::cookie("darkmode", "on", 31 * 24 * 60);
		Main::set("body_class", "dark-mode");
		return Main::redirect("user");
	}

	if(isset($_GET["darkmode"]) && $_GET["darkmode"] == "off"){
		Main::cookie("darkmode", "off", -60);
		return Main::redirect("user");
	}

}
/**
 * [isDark description]
 * @author KBRmedia <https://gempixel.com>
 * @version 1.0
 * @return  boolean [description]
 */
function isDark(){
	if(isset($_COOKIE["darkmode"]) && $_COOKIE["darkmode"] == "on") return true;
	return false;
}
/**
 * [theme_hasOption description]
 * @author GemPixel <https://gempixel.com>
 * @version 1.0
 * @return  [type] [description]
 */
function themehasOption(){
	return TRUE;
}
/**
 * [theme_getOptions description]
 * @author GemPixel <https://gempixel.com>
 * @version 1.0
 * @return  [type] [description]
 */
function themegetOptions(){
	global $config, $db;

	$option = $config["theme_config"];
	if(!is_array($option)) $option = json_decode(json_encode($option), TRUE);

	if(isset($_POST["token"])){
    if(!Main::validate_csrf_token($_POST["token"])){
      return Main::redirect(Main::ahref("themes/options","",FALSE),array("danger","Something went wrong, please try again."));
    }

		$data = [];
		unset($_POST["token"]);

		foreach ($_POST as $key => $value) {
			$data[$key] = Main::clean($value, 2);
		}

		if(isset($_FILES["hero"]) && !empty($_FILES["hero"]["tmp_name"])) {
			
			$ext = array("image/png"=>"png","image/jpeg"=>"jpg","image/jpg"=>"jpg"); 

			if(!isset($ext[$_FILES["hero"]["type"]])) return Main::redirect(Main::ahref("themes/options","",FALSE),array("danger",e("Custom Image must be either a PNG or a JPEG.")));

      if($_FILES["hero"]["size"] > 500*1024) return Main::redirect(Main::ahref("themes/options","",FALSE),array("danger",e("Custom Image must be either a PNG or a JPEG (Max 500kb).")));   

			$data["hero"] = Main::clean($_FILES["hero"]["name"], 3, TRUE);
			move_uploaded_file($_FILES["hero"]['tmp_name'], ROOT."/content/".$data["hero"]);  
			if(isset($option["hero"]) && !empty($option["hero"]) && file_exists(ROOT."/content/".$option["hero"])){
				unlink(ROOT."/content/".$option["hero"]);
			}
		}

		if(isset($_POST["remove_logo"]) && isset($option["hero"]) && !empty($option["hero"]) && file_exists(ROOT."/content/".$option["hero"])){
			$data["hero"] = "";
			unlink(ROOT."/content/".$option["hero"]);			
		}
		
		$db->update("settings", ["var" => "?"], ["config" => "?"], [json_encode($data), "theme_config"]);
		return Main::redirect(Main::ahref("themes/options","",FALSE), ["success", "Settings are successfully saved."]);
	}

	if(!isset($option["hero"])) $option["hero"] = "";
	if(!isset($option["homeheader"])) $option["homeheader"] = "";
	if(!isset($option["homedescription"])) $option["homedescription"] = "";
  

	$content = "<div class='row'>
						<div class='col-md-8'>
						<div class='panel panel-default'>
						<div class='panel-heading'>Theme Settings</div>
						<div class='panel-body'>
							<form action='".Main::ahref("themes/options")."' method='post' enctype='multipart/form-data' id='setting-form'>
								<div class='form-group'>
									".(!empty($option["hero"]) ? '
						    		<a href="#" id="remove_logo" class="btn btn-info btn-xs pull-right">Remove Current Image</a>'
						    	:'')."								
								 <label for='hero' class='control-label'>Custom Home Page Image</label>
								 <input type='file' class='form-control' name='hero' id='hero' value=\"{$option["hero"]}\">
								 <p class='help-block'>This will replace the default hero image that comes shipped with the script. JPG or PNG. 500 kb max. Recommended size: 560x710</p>								 
								</div>
								<div class='form-group'>
								 <label for='homeheader' class='control-label'>Home Main Header</label>
								 <input type='text' class='form-control' name='homeheader' id='homeheader' value=\"{$option["homeheader"]}\">
								 <p class='help-block'>This will replace the home main header right before the shortener form. If you leave it empty, the site title will be shown.</p>
								</div>	
								<div class='form-group'>
								 <label for='homedescription' class='control-label'>Home Main Description</label>
								 <textarea class='form-control' name='homedescription' id='homedescription'>{$option["homedescription"]}</textarea>
								 <p class='help-block'>This will replace the home main description right before the shortener form. If you leave it empty, the site description will be shown.</p>
								</div>																	
						  	<hr>								
								";
		$content .= "
				        ".Main::csrf_token(TRUE)."
				        <button class='btn btn-primary'>Save Settings</button>          
				      </form>
				     </div>
		     </div></div>
			     <div class='col-md-4'>
							<div class='panel panel-default'>
								<div class='panel-heading'>Help</div>
								<div class='panel-body'>	
									<p><strong>HTML Usage</strong></p>
									<p>You can use the following HTML elements: ".htmlentities('<b> <i> <s> <u> <strong> <span> <p> <br>')."</p>

									<p><strong>Translating Strings</strong></p>
									<p>If you add a new title or a new description, you can still translate them to any language by simply adding it via the language manager.</p>

									<p><strong>Support</strong></p>
									<p>For support, please open a ticket by clicking the link below. Make sure to add your purchase codes for faster response.</p>
									<a href='https://support.gempixel.com' class='btn btn-primary btn-xs' target='_blank'>Open Support</a>
								</div>							
							</div>		     
			     </div>
		     </div>";
		return $content;
}
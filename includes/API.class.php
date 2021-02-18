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
 * @package Premium URL Shortener
 * @subpackage Short Class
 */
class API extends App{
	/**
	 * Allowed actions
	 * @var array
	 */
	protected $actions = array("url", "user", "fullpage");
	/**
	 * [$key description]
	 * @var null
	 */
	protected $key = NULL;
	/**
	 * [$user description]
	 * @var null
	 */
	protected $user = NULL;

	/**
	 * Class Constructer 
	 */
	public function __construct($db, $config, $do, $id){
  	$this->config = $config;
  	$this->db = $db;
  	$this->method = $do;
  	$this->endpoint = $id;

  	// Clean Request
  	if(isset($_GET)) $_GET = array_map("Main::clean", $_GET);
		if(isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] > 0) $this->page = Main::clean($_GET["page"]);
		$this->http = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://":"http://");
		$this->check();
		
		$this->config["captcha"] = 0;
		$this->config["private"] = 0;
		$this->config["user"] = 1;
		$this->config["require_registration"] = 0;		

		return $this->index();
	}
	/**
	 * Index
	 * @since  5.9.4
	 */
	protected function index(){
		// Check if enabled
		if(!$this->config["api"]) return $this->error("001");		
		
		// Check if key exists
		if(empty($this->method) && (isset($_GET["key"]) || isset($_GET["api"]))){

			$key = isset($_GET["key"]) ? $_GET["key"] : $_GET["api"];
			// Check KEY
			if(!empty($key) && strlen($key) >= 4){
				return $this->legacy_shorten($key);
			}

			return $this->error("002");
		}	

		if($this->method == "fullpage")	 return $this->fullpage();

		if($_SERVER["REQUEST_METHOD"] != "POST") return $this->error("000");

		//	Get User Token
		$headers = apache_request_headers();

		if(!isset($headers["Authorization"])) return $this->error("002");

		$this->key = str_replace("Token ", "", $headers["Authorization"]);

		if(!$this->user = $this->db->get("user",["api" => "?"], ["limit" => 1], [$this->key])) return $this->error("002");

		// Run Methods
		if(!empty($this->method)){
			if(in_array($this->method, $this->actions) && method_exists(__CLASS__, $this->method)){
				// Run Method
				return $this->{$this->method}();
			}				
		}

		// Run Error
		return $this->error("000");
	}
	/**
	 * [user description]
	 * @author GemPixel <https://gempixel.com>
	 * @version 5.9.4
	 * @return  [type] [description]
	 */
	protected function user(){		

		if($this->user->admin) {
			$fn = "user_{$this->endpoint}";
			if(!method_exists(__CLASS__, $fn)) return $this->error("000");

			return $this->$fn();
		}

		return $this->error("013");
	}
	/**
	 * [user_create description]
	 * @author GemPixel <https://gempixel.com>
	 * @version 5.9.4
	 * @return  [type] [description]
	 */
	protected function user_create(){

		$raw = file_get_contents("php://input");
		
		if(empty($raw)) return $this->error("014");

		if(!$data = json_decode($raw)) return $this->error("014");

		if(!isset($data->email) || empty($data->email) || !Main::email($data->email)) return $this->build(["error" => 1, "msg" => "Email required. Please enter a valid email."]);

		// Check email in database
		if(!empty($data->email) && $this->db->get("user",array("email"=>"?"),"",array($data->email))) return $this->build(["error" => 1, "msg" => "Account already exists with this email."]);

		if(!isset($data->username) || empty($data->username) || !Main::username($data->username)) return $this->build(["error" => 1, "msg" => "Username required. Please enter a valid username."]);
		
		if($this->db->get("user", array("username"=>"?"), array("limit"=>1), array($data->username))) return $this->build(["error" => 1, "msg" => "An account is already associated with this username. Please choose another username."]);
		
		// Check Password
		if(empty($data->password) || strlen($data->password) < 5) return $this->build(["error" => 1, "msg" => "Password required. Password must contain at least 5 characters."]);

		$auth_key = Main::encode($this->config["security"].Main::strrand());

		$unique = Main::strrand(20);

		// Prepare Data
		$query = [
				":email" => Main::clean($data->email,3),
				":username" => Main::clean($data->username,3),
				":password" => Main::encode($data->password),
				":auth_key" => $auth_key,
				":api" => $unique,
				":date" => date("Y-m-d H:i:s")
		];

		if(isset($data->planid) && $this->db->get("plans", ["id" => $data->planid], ["limit" => 1])){
			$query[":planid"] = $data->planid;
			$query[":pro"] = 1;
			$query[":last_payment"] = date("Y-m-d H:i:s");
			if(isset($data->expiration)){
				$query[":expiration"] = date("Y-m-d H:i:s", strtotime($data->expiration));
			}else{
				$query[":expiration"] = date("Y-m-d H:i:s", strtotime("+1 month"));
			}
		}

		if($this->db->insert("user", $query)){					
				return $this->build([
						"error" => 0,
						"msg" => "User has been registered",
						"data" => [
							"id" => $this->db->pdo()->lastInsertId(),				
							"email" => Main::clean($data->email,3),
							"username" => Main::clean($data->username,3) 
						]
				]);
		}

		return $this->error("000");
	}
	/**
	 * [user_get description]
	 * @author GemPixel <https://gempixel.com>
	 * @version 5.9.4
	 * @return  [type] [description]
	 */
	protected function user_get(){

		$raw = file_get_contents("php://input");
		
		if(empty($raw)) return $this->error("014");

		if(!$data = json_decode($raw)) return $this->error("014");		

		if(!isset($data->userid)) return $this->error("014");

		if(!$user = $this->db->get("user", ["id" => $data->userid], ["limit" => 1])) return $this->build(["error" => 1, "msg" => "User does not exist."]); 

		$response = [];

		$response["email"] = $user->email;
		$response["username"] = $user->username;
		$response["date"] = $user->date;
		$response["avatar"] = $this->avatar($user);
		$response["pro"] = $user->pro;
		$response["planid"] = $user->planid;
		$response["expiration"] = $user->expiration;
		$response["last_payment"] = $user->last_payment;

		return $this->build(["error" => 0, "data" => $response]);
	}

	/**
	 * [url description]
	 * @author GemPixel <https://gempixel.com>
	 * @version 5.9.4
	 * @return  [type] [description]
	 */
	protected function url(){
		$fn = "url_{$this->endpoint}";
		if(!method_exists(__CLASS__, $fn)) return $this->error("000");

		return $this->$fn();
	}

	/**
	 * [url_add description]
	 * @author GemPixel <https://gempixel.com>
	 * @version 1.0
	 * @return  [type] [description]
	 */
	protected function url_add(){

		$raw = file_get_contents("php://input");
		
		if(empty($raw)) return $this->error("014");

		if(!$data = json_decode($raw)) return $this->error("014");			

		include(ROOT."/includes/Short.class.php");

		$short = new Short($this->db,$this->config);

		$array = [];

		$array["private"] = TRUE;

		$array["url"]	= Main::clean($data->url, 3, TRUE);

		$array["type"] = "";
		
		if(isset($data->custom) && !empty($data->custom)) $array["custom"] = Main::slug($data->custom);

		if(isset($data->password) && !empty($data->password)) $array["password"] = Main::clean($data->password, 3, TRUE);

		if(isset($data->domain) && !empty($data->domain)) $array["domain"] = Main::clean($data->domain, 3, TRUE);

		if(isset($data->expiry) && !empty($data->expiry) && strtotime("now") > strtotime($data->expiry)) $array["expiry"] = Main::clean($data->expiry, 3, TRUE);

		if(isset($data->type)){

			if($this->user->pro || $this->user->admin) {
				if(!in_array($data->type, ["direct", "frame", "splash"])) return $this->error("009");
				$array["type"] = Main::clean($data->type);

			}else{
				if(!in_array($data->type, ["direct", "frame", "splash"])) return $this->error("009");
				if(!$this->config["pro"]) $array["type"] = Main::clean($data->type);
			}			

		}

		if(isset($data->geotarget)){
			foreach ($data->geotarget as $country ) {
				$array["location"][] = $country->location;
				$array["target"][] = $country->link;
			}
		}

		if(isset($data->devicetarget)){
			foreach ($data->devicetarget as $device ) {
				$array["device"][] = $device->device;
				$array["dtarget"][] = $device->link;
			}
		}


		$result = $short->add($array, ["noreturn" => TRUE, "api" => TRUE, "user" => $this->user]);

		return $this->build($result);		
	}
	/**
	 * [url_get description]
	 * @author GemPixel <https://gempixel.com>
	 * @version 1.0
	 * @return  [type] [description]
	 */
	protected function url_get(){
		
		$raw = file_get_contents("php://input");
		
		if(empty($raw)) return $this->error("014");

		if(!$data = json_decode($raw)) return $this->error("014");			

		$limit = isset($data->limit) && is_numeric($data->limit) ? $data->limit : 10;

		$page = isset($data->page) && is_numeric($data->page) ? $data->page : 1;

		$order = isset($data->order) && in_array($data->order, ["date","click"]) ? $data->order : "date";

		$urls = $this->db->get("url", ["userid" => $this->user->id], ["order" => $order, "limit"=> (($page-1)*$limit.", {$limit}"), "count" => true]);		

    if(($this->db->rowCount%$limit)<>0) {
      $max = floor($this->db->rowCount/$limit)+1;
    } else {
      $max = floor($this->db->rowCount/$limit);
    }   
    if($page > 1 && $page > $max) return $this->build(["error" =>  1, "msg" => "Page unavailable. Maximum page is {$max}."]);

		$data = [];

		$data["result"] = $this->db->rowCount;
		$data["perpage"] = $limit;
		$data["currentpage"] = $page;
		$data["nextpage"] = $page < $max ? $page+1 : $max;
		$data["maxpage"] = $max;

		foreach ($urls as $url) {
			$data["urls"][] = [
											"id"							=> (int) $url->id,
											"alias" 					=> $url->alias.$url->custom,
											"shorturl"  			=> (empty($url->domain) ? $this->config["url"] : $url->domain)."/".$url->alias.$url->custom,
											"longurl"					=> $url->url,
											"clicks"					=> (int) $url->click,
											"title"						=> $url->meta_title,
											"description"			=> $url->meta_description,
											"date"						=> $url->date,
									 ];
		}
		
		return $this->build(["error" => "0", "data" => $data]);    
	}
	/**
	 * [url_stats description]
	 * @author GemPixel <https://gempixel.com>
	 * @version 1.0
	 * @return  [type] [description]
	 */
	protected function url_stats(){

		$raw = file_get_contents("php://input");
		
		if(empty($raw)) return $this->error("014");

		if(!$data = json_decode($raw)) return $this->error("014");		

		if(!isset($data->urlid)) return $this->error("007");

		if(!$url = $this->db->get("url", ["id" => $data->urlid, "userid" => $this->user->id], ["limit" => 1])){
			return $this->error("008");
		}

		if($url->userid != $this->user->id) return $this->error("008");

		// Unique Clicks
		$unique = $this->db->count("stats","urlid = '{$url->id}' GROUP by ip");

		// Countries
		$countries = $this->db->get(array("count"=>"country AS country, COUNT(country) AS count","table"=>"stats"),array("urlid"=>"?"),array("group"=>"country","order"=>"count", "limit" => "10"),array($url->id));  
    
    $i=0;
    $top_country = [];

    foreach ($countries as $country) {
      $top_country[ucwords($country->country)] = $country->count;
    }

    arsort($top_country);

    // referrers
		
		$top_referrers = [];

    $referrers = $this->db->get(array("count"=>"domain AS domain, COUNT(domain) AS count","table"=>"stats"),array("urlid"=>"?"), array('group' => "domain", "limit" => 10),array($url->id));
 
    $browsers = $this->db->get(array("count"=>"browser as browser, COUNT(browser) AS count","table"=>"stats"),array("urlid"=>"?"), array('group' => "browser","limit"=>10, "order" => "count"),array($url->id));

    $os = $this->db->get(array("count"=>"os as os, COUNT(os) AS count","table"=>"stats"),array("urlid"=>"?"), array('group' => "os","limit"=>10,"order" => "count"),array($url->id));		    

    $fb = $this->db->count("stats","urlid = '$url->id' AND (domain LIKE '%facebook.%' OR domain LIKE '%fb.%')");
    $tw = $this->db->count("stats","urlid = '$url->id' AND (domain LIKE '%twitter.%' OR domain LIKE '%t.co%')");
    $gl = $this->db->count("stats","urlid = '$url->id' AND (domain LIKE '%plus.url.google%')");

    foreach ($referrers as $referrer) {
    	if(empty($referrer->domain)) $referrer->domain = e("Direct, email and other");
    	if(!preg_match("~facebook.~", $referrer->domain) && !preg_match("~fb.~", $referrer->domain) && !preg_match("~t.co~", $referrer->domain) && !preg_match("~twitter.~", $referrer->domain) && !preg_match("~plus.url.google.~", $referrer->domain)){
    		$top_referrers[$referrer->domain] = $referrer->count;
    	}
    }  

    $top_browsers = [];
    foreach ($browsers as $browser) {
      $top_browsers[ucwords($browser->browser)] = $browser->count;
    }
    $top_os = [];
    foreach ($os as $o) {
      $top_os[ucwords($o->os)] = $o->count;
    }

    arsort($top_referrers); 
    arsort($top_browsers); 
    arsort($top_os); 
		
		return $this->build([
								"error" => 0,
								"details" => [
										"shorturl" 		=> (empty($url->domain) ? $this->config["url"] : $url->domain)."/".$url->alias.$url->custom,
										"longurl" 		=> $url->url,
										"title" 			=> $url->meta_title,
										"description" => $url->meta_description,
										"location"		=> json_decode($url->location, TRUE),
										"device"			=> json_decode($url->devices, TRUE),
										"expiry"			=> $url->expiry,
										"date"				=> $url->date
								],
								"data" => [
										"clicks"  			 		=> (int) $url->click,
										"uniqueClicks" 			=> (int) $unique,
										"topCountries" 			=> (int) $top_country,
										"topReferrers" 		 	=> (int) $top_referrers,
										"topBrowsers"				=> (int) $top_browsers,
										"topOs"							=> (int) $top_os,
										"socialCount"	 => [
													"facebook" => (int) $fb,
													"twitter"  => (int) $tw,
													"google"   => (int) $gl
										]
								]
						]);
	}

	/**
	 * Will be deprecated soon
	 * @author KBRmedia <http://gempixel.com>
	 * @version 5.9.4
	 * @return  [type] [description]
	 */
	private function legacy_shorten($key){

		// Get User
		if(!$user = $this->db->get("user",["api" => "?"], ["limit" => 1], [$key])) return $this->error("002");

		$this->key = $key;
		$this->user = $user;

		$this->user->plan = $this->db->get("plans", ["id" => $user->planid], ["limit" => 1]);

		if($this->isTeam($user) && !$this->teamPermission("api.create", $user)){
			// Run Error
			return $this->error("000");			
		}

		if(!$user->active || $user->banned) return $this->error("009");

		if(!isset($_GET["url"]) || empty($_GET["url"])) return $this->error("004");

		include(ROOT."/includes/Short.class.php");
		$short = new Short($this->db,$this->config);

		$array = [];

		$array["private"] = TRUE;

		$array["url"]	= Main::clean($_GET["url"],3,TRUE);

		$array["type"] = "";
		
		if(isset($_GET["custom"]) && !empty($_GET["custom"])) $array["custom"] = Main::slug($_GET["custom"]);

		if(isset($_GET["pass"]) && !empty($_GET["pass"])) $array["password"] = Main::clean($_GET["pass"], 3, TRUE);

		if(isset($_GET["domain"]) && !empty($_GET["domain"])) $array["domain"] = Main::clean($_GET["domain"], 3, TRUE);

		if(isset($data->type)){

			if($this->user->pro) {
				if(!in_array($data->type, ["direct", "frame", "splash","overlay"])) return $this->error("009");
				$array["type"] = Main::clean($data->type);

			}else{
				if(!in_array($data->type, ["direct", "frame", "splash"])) return $this->error("009");
				if(!$this->config["pro"]) $array["type"] = Main::clean($data->type);
			}			

		}

		$result = $short->add($array, ["noreturn" => TRUE, "api" => TRUE, "user" => $this->user]);

		return $this->build($result,isset($result["short"]) ? $result["short"] :"");
	}
	/**
	 * [fullpage description]
	 * @author GemPixel <https://gempixel.com>
	 * @version 1.0
	 * @return  [type] [description]
	 */
	protected function fullpage(){
		
		$key = isset($_GET["key"]) ? $_GET["key"] : $_GET["api"];

			// Get User
		if(!$user = $this->db->get("user", "MD5(api) = ?", ["limit" => 1], [$key])) return $this->error("002");

		$this->key = $key;
		$this->user = $user;

		$this->user->plan = $this->db->get("plans", ["id" => $user->planid], ["limit" => 1]);

		if($this->isTeam($user) && !$this->teamPermission("api.create", $user)){
			// Run Error
			return $this->error("000");			
		}

		if(!$user->active || $user->banned) return $this->error("009");

		if(!isset($_GET["url"]) || empty($_GET["url"])) return $this->error("004");

		include(ROOT."/includes/Short.class.php");
		$short = new Short($this->db,$this->config);

		$array = [];

		$array["private"] = TRUE;

		$array["url"]	= Main::clean($_GET["url"],3,TRUE);

		$array["type"] = "";	

		$result = $short->add($array, ["noreturn" => TRUE, "api" => TRUE, "user" => $this->user]);

		return $this->build($result,isset($result["short"]) ? $result["short"] :"");			
	}
	/**
	 * [error description]
	 * @author KBRmedia <http://gempixel.com>
	 * @version 5.2
	 * @param   [type] $code [description]
	 * @return  [type]       [description]
	 */
	private function error($code){
		$list = [
							"000" => "Wrong endpoint or invalid API request.",
							"001" => "API service is disabled.",
							"002" => "A valid API key is required to use this service.",
							"003" => "You have been banned for abuse.",
							"004" => "Please enter a valid URL.",
							"005" => "This URL couldn't be found. Please double check it.",
							"006" => "This URL is private or password-protected.",
							"007" => "You must send an alias paramater with URLs alias as the value.",
							"008" => "This URL does not exist or is not associated with your account.",
							"009" => "You account is either not active or banned for abuse.",
							"010" => "The redirection type is invalid.",
							"011" => "You do not have the permission to use the API system. Contact administrator.",
							"012" => "You need to upgrade your code to API v2.",
							"013" => "You do not have the permission to use this endpoint.",
							"014" => "Data is missing or incomplete"
					];

		if(!isset($list[$code])) $code = "002";

		return $this->build(["error" => 1, "msg" => $list[$code]]);
	}
	/**
	 * [build description]
	 * @author KBRmedia <http://gempixel.com>
	 * @version 5.2
	 * @param   [type] $array [description]
	 * @param   string $text  [description]
	 * @return  [type]        [description]
	 */
	private function build($array, $text=""){
		// Set Header
		header("content-type: application/javascript");

		// JSONP Request
		if(isset($_GET["callback"])){
			return print("{$_GET["callback"]}(".json_encode($array).")");
		}

		// Text
		if(isset($_GET["format"]) && $_GET["format"] == "text"){
			header("content-Type: text/plain");
			return print($text);
		}

		// JSON
		return print(json_encode($array));		
	}
}
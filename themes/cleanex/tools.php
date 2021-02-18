<?php defined("APP") or die() // Settings Page ?>
<div class="main-content row">	
  <div id="user-content" class="col-md-12">  	
  	<?php echo $this->ads(728) ?>
		<?php echo Main::message() ?>  			
    <div class="row">
      <div class="col-md-3">
        <div class="panel panel-default">
          <div class="panel-heading"><i class="fa fa-wrench"></i> <?php echo e("Tools &amp; Integrations") ?></div>
          <ul class="nav tabs">
            <li class="active"><a href="#quick"><?php echo e("Quick Shortener") ?></a></li>
            <li><a href="#bk"><?php echo e("Bookmarklet") ?></a></li>
            <li><a href="<?php echo Main::href("user/tools/api") ?>" data-link = "true"><?php echo e("Developer API") ?> 2.0</a></li>
            <li><a href="#jshort"><?php echo e("Full-Page Script") ?></a></li>
            <li><a href="#zapier"><?php echo e("Zapier Integration") ?></a></li>
            <?php if (isset($slack)): ?>
              <li><a href="#slack"><?php echo e("Slack Integration") ?></a></li>
            <?php endif ?>
          </ul>
          <br>
        </div>
      </div>
      <div class="col-md-9">
        <div class="tabbed" id="quick">
          <div class="panel panel-default">
            <div class="panel-body">
              <h3><i class="glyphicon glyphicon-flash"></i> <?php echo e("Quick Shortener") ?></h3>

              <p><?php echo e("This tool allows you to quickly shorten any URL in any page without using any fancy method. This is perhaps the quickest and the easiest method available for you to shorten URLs across all platforms. This method will generate a unique short URL for you that you will be able to access anytime from your dashboard.") ?></p>

              <p><?php echo e("Use your quick URL below to shorten any URL by adding the URL after /q/?u=. <strong>For security reasons, you need to be logged in and using the remember me feature.</strong> Check out the examples below to understand how to use this method.") ?></p>
              <pre class="code"><span><?php echo Main::href("q/?u=URL_OF_SITE") ?></span></pre>

              <p><strong><?php echo e("Examples") ?></strong></p>
              <pre class="code"><span><?php echo Main::href("q/?u=https://www.google.com") ?></span><span><?php echo Main::href("q/?u=gempixel.com") ?></span><span><?php echo Main::href("q/?u=http://www.apple.com/iphone-7/") ?></span></pre>

              <p><strong><?php echo e("Notes") ?></strong></p>
              <p>
                <?php echo e("Please note that this method does not return anything. It simply redirects the user to the redirection page. However if you need the actual short URL, you can always get it from your dashboard.") ?>
              </p>
            </div>                
          </div>
        </div>
        <div class="tabbed" id="bk">
          <div class="panel panel-default">
            <div class="panel-body">
              <h3><i class="glyphicon glyphicon-bookmark"></i> <?php echo e("Bookmarklet") ?></h3>

              <p><?php echo e("You can use our bookmarklet tool to instantaneously shorten any site you are currently viewing and if you are logged in on our site, it will be automatically saved to your account for future access. Simply drag the following link to your bookmarks bar or copy the link and manually add it to your favorites.") ?></p>

              <a class='btn btn-primary' href="javascript:void((function () {var h = '<?php echo $this->config["url"]?>';if (h.indexOf('http:') >= 0 && window.location.protocol == 'https:') {window.location = h+'/?bookmark=1&token=<?php echo md5($this->config["public_token"])?>&amp;url=' + encodeURIComponent(document.URL);} else {var e = document.createElement('script');e.setAttribute('data-url', h);e.setAttribute('data-token', '<?php echo md5($this->config["public_token"])?>');e.setAttribute('id', 'gem_bookmarklet');e.setAttribute('type', 'text/javascript');e.setAttribute('src', h+'/static/bookmarklet.js?v=<?php echo time() ?>');document.body.appendChild(e);}})());" rel='nofollow' title='<?php echo e('Drag me to your Bookmark Bar') ?>' style='cursor:move'> <i class="glyphicon glyphicon-arrows"></i> <?php echo e('Shorten URL')?> (<?php echo explode(" ", $this->config["title"])[0] ?>)</a>

              <p><?php echo e("If you can't drag the link above, use your browser's bookmark editor to create a new bookmark and add the URL below as the link.") ?></p>
              <pre class="code"><span>javascript:void((function(){if(window.location.protocol=='https:'){window.location='<?php echo $this->config["url"] ?>/?bookmark=1&amp;token=<?php echo md5($this->config["public_token"])?>&amp;url='+encodeURIComponent(document.URL);}else{var e=document.createElement('script');e.setAttribute('data-url','<?php echo $this->config["url"]?>');e.setAttribute('data-token','<?php echo md5($this->config["public_token"])?>');e.setAttribute('id','gem_bookmarklet');e.setAttribute('type','text/javascript');e.setAttribute('src','<?php echo $this->config["url"]?>/static/bookmarklet.js?v=<?php echo time() ?>');document.body.appendChild(e)}})());</span></pre>
              
              <p><strong><?php echo e("Notes") ?></strong></p>
              <p>
                <?php echo e("Please note that for secured sites that use SSL, the widget will not pop up due to security issues. In that case, the user will be redirected our site where you will be able to view your short URL.") ?>
              </p>                    
            </div>
          </div>
        </div>
        <div class="tabbed" id="api">
          <div class="panel panel-default">
            <div class="panel-body">
              <h3><i class="glyphicon glyphicon-cloud"></i> <?php echo e("API Usage in PHP") ?></h3>

              <p><?php echo e("An API key is required for requests to be processed by the system. Once a user registers, an API key is automatically generated for this user. The API key must be sent with each request via the key parameter (see full example below). If the API key is not sent or is expired, there will be an error. Please make sure to keep your API key secret to prevent abuse.") ?></p>

              <p><strong><?php echo e("Your API key") ?></strong></p>
              <pre class="code"><span><?php echo $this->user->api ?></span></pre>
              <a href="<?php echo Main::href("user/tools/regenerate").Main::nonce("regenerate_api") ?>" class="btn btn-primary delete" title="<?php echo e("Regenerate API Key") ?>" data-content="<?php echo e("If you proceed, your current applications will not work anymore. You will need to change your api key for it to work again.") ?>"><?php echo e("Regenerate") ?></a>
              <hr>
              <h5><?php echo e("Sending a request for shortening a URL") ?></h5>
              <p><?php echo e("To send a request, the user must use the following format where the variables api and url are required. In the example below, the URL of the demo is used but you should use your own domain name. To request a custom alias, simply add &custom= at the end.") ?></p>

              <pre class="code"><span>GET <?php echo Main::href("api/?key={$this->user->api}") ?>&amp;url=THELONGURLTOBESHORTENED&amp;custom=CUSTOMALIAS</span></pre>

              <h5><?php echo e("For premium members only") ?></h5>

              <pre class="code"><span>GET <?php echo Main::href("api/?key={$this->user->api}") ?>&amp;url=THELONGURLTOBESHORTENED&amp;custom=CUSTOMALIAS&amp;type=REDIRECTYPE</span></pre>
              
              <hr>
              <h5><?php echo e("Server response") ?></h5>
              <p><?php echo e("As before, the response will encoded in JSON format (default). This is done to facilitate cross-language usage. The first element of the response will always tell if an error has occurred (error: 1) or not (error: 0). The second element will change with respect to the first element. If there is an error, the second element will be named 'msg'. which contains the source of error, otherwise it will be named 'short' which contains the short URL. (See below for an example)") ?></p>

              <p><strong><?php echo e("No errors") ?></strong></p>
              <pre class="code"><span>{</span><span class="m-x-4">"error":0,</span><span class="m-x-4">"short":<?php echo json_encode(Main::href("DkZOb")) ?></span><span>}</span></pre>
              
              <p><strong><?php echo e("An error has occurred")?></strong></p>
              <pre class="code"><span>{</span><span class="m-x-4">"error":1,</span><span class="m-x-4">"msg":"Please enter a valid url"</span><span> }</span></pre>

              <p><strong><?php echo e("Using plain text format") ?></strong></p>

              <p><?php echo e("You can now request the response to be in plain text by just adding &format=text at the end of your request. Note that if an error occurs, it will not output anything so you can assume if it is empty then there is an error.") ?></p>
              <hr>
              <p><strong><?php echo e("Using the API in PHP") ?></strong></p>
              <p><?php echo e("To use the API in your PHP application, you have to send a GET request through file_get_contents or cURL: Both are reliable methods. You can copy the function below. Everything is already set up for you.") ?></p>

              <pre class="code"><span>&lt;?php</span><span class="m-x-3">/**** Sample PHP Function ***/</span><span class="m-x-3">function shorten($url, $custom = "", $format = "json") { </span><span class="m-x-4">$api_url = "<?php echo Main::href("api/?key={$this->user->api}") ?>";</span><span class="m-x-4">$api_url .= "&url=".urlencode(filter_var($url, FILTER_SANITIZE_URL));</span><span class="m-x-4">if(!empty($custom)){</span><span class="m-x-5">$api_url .= "&custom=".strip_tags($custom);</span><span class="m-x-4">}</span><span class="m-x-4">$curl = curl_init();</span><span class="m-x-4">curl_setopt_array($curl, array(</span><span class="m-x-5">CURLOPT_RETURNTRANSFER => 1,</span><span class="m-x-5">CURLOPT_URL => $api_url</span><span class="m-x-4">));</span><span class="m-x-4">$Response = curl_exec($curl);</span><span class="m-x-4">curl_close($curl);<span class="m-x-3"></span><span class="m-x-4">if($format == "text"){</span><span class="m-x-5">$Ar = json_decode($Response,TRUE);</span><span class="m-x-5">if($Ar["error"]){</span><span class="m-x-6">return $Ar["msg"];</span><span class="m-x-5">}else{</span><span class="m-x-6">return $Ar["short"];</span><span class="m-x-5">}</span><span class="m-x-4">}else{</span><span class="m-x-5">return $Response;</span><span class="m-x-4">}</span><span class="m-x-3">}<br>?&gt;</span></pre>

              <p><strong><?php echo e("Simple Usage") ?></strong></p>    
              <pre class="code"><span>&lt;?php</span><span class="m-x-4">echo shorten("https://google.com");</span><span>?&gt;</span></pre>

              <p><strong><?php echo e("Usage with custom alias") ?></strong></p>    
              <pre class="code"><span>&lt;?php</span><span class="m-x-4">echo shorten("https://google.com", "google");</span><span>?&gt;</span></pre>

              <p><strong><?php echo e("Usage with custom alias and text format") ?></strong></p>    
              <pre class="code"><span>&lt;?php</span><span class="m-x-4">echo shorten("https://google.com", "google", "text");</span><span>?&gt;</span></pre>              
            </div>
          </div>
        </div>
        <div class="tabbed" id="jshort">
          <div class="panel panel-default">
            <div class="panel-body">
               <h3><i class="glyphicon glyphicon-refresh"></i> <?php echo e("Full-Page Script") ?></h3>
               
               <p><?php echo e("This script allows you to shorten all (or select) URLs on your website very easily. All you need to do is to copy and paste the code below at the end of your page. You can customize the selector as you wish to target URLs in a specific selector. Note you can just  copy the code below because everything is already for you.") ?></p>

               <p><pre class="code"><span class="m-x-3">&lt;script type=&quot;text/javascript&quot;&gt;</span><span class="m-x-4">var key = &quot;<?php echo md5($this->user->api) ?>&quot;;</span><span class="m-x-3">&lt;/script&gt;<span class="m-x-3">&lt;script type=&quot;text/javascript&quot; src=&quot;<?php echo Main::href("script.js") ?>&quot;&gt;&lt;/script&gt;</span></span></pre></p>
          
               <h5><?php echo e("Choosing custom select") ?></h5>
               <p><?php echo e("By default, this script shortens all URLs in a page. If you want to target specific URLs then you can add a selector paramater. You can see an example below where the script will only shorten URLs having a class named mylink or all direct link in the .content container or all links in the .comments container") ?></p>

               <p><pre class="code"><span class="m-x-3">&lt;script type=&quot;text/javascript&quot;&gt;</span><span class="m-x-4">var key = &quot;<?php echo md5($this->user->api) ?>&quot;;</span><span class="m-x-4">var selector = &quot;.mylink, .content > a, .comments a&quot;;</span><span class="m-x-3">&lt;/script&gt;<span class="m-x-3">&lt;script type=&quot;text/javascript&quot; src=&quot;<?php echo Main::href("script.js") ?>&quot;&gt;&lt;/script&gt;</span></span></pre></p>

               <h5><?php echo e("Excluding domain names") ?></h5>
               <p><?php echo e("You can exclude domain names if you wish. You can add an exclude parameter to exclude domain names. The example below shortens all URLs but excludes URLs from google.com or gempixel.com") ?></p>

               <p><pre class="code"><span class="m-x-3">&lt;script type=&quot;text/javascript&quot;&gt;</span><span class="m-x-4">var key = &quot;<?php echo md5($this->user->api) ?>&quot;;</span><span class="m-x-4">var exclude = [&quot;google.com&quot;,&quot;gempixel.com&quot;];</span><span class="m-x-3">&lt;/script&gt;<span class="m-x-3">&lt;script type=&quot;text/javascript&quot; src=&quot;<?php echo Main::href("script.js") ?>&quot;&gt;&lt;/script&gt;</span></span></pre></p>

               <h5><?php echo e("Restricting domain names") ?></h5>
               <p><?php echo e("You can restrict domain names by adding an include parameter to restrict domain names. The example below shortens all URLs within the include domain name.") ?></p>

               <p><pre class="code"><span class="m-x-3">&lt;script type=&quot;text/javascript&quot;&gt;</span><span class="m-x-4">var key = &quot;<?php echo  md5($this->user->api) ?>&quot;;</span><span class="m-x-4">var include = [&quot;google.com&quot;];</span><span class="m-x-3">&lt;/script&gt;<span class="m-x-3">&lt;script type=&quot;text/javascript&quot; src=&quot;<?php echo Main::href("script.js") ?>&quot;&gt;&lt;/script&gt;</span></span></pre></p>

            </div>
          </div>
        </div>
        <div class="tabbed" id="zapier">
          <div class="panel panel-default">
              <div class="panel-body">
               <h3>
                <i class="fa fa-bolt"></i> <?php echo e("Zapier Integration") ?>
                  <?php if($this->user->zapurl || $this->user->zapview): ?>
                    <span class='pull-right tools-status'><i class="fa fa-check-circle"></i> <?php echo e("Active") ?></span>
                  <?php endif ?>                
               </h3>
               <p><?php echo e("You can use Zapier to automate campaigns. By adding the URL to the zapier webhook, we will send you important information to that webhook so you can use them.") ?></p>
                <form action="<?php echo Main::href("user/tools/zapier") ?>" method="post">
                  <div class="form-group">
                    <label for="zapurl" class="control-label"><?php echo e("URL Zapier Notification") ?></label>
                    <input type="text" id="zapurl" name="zapurl" class="form-control" placeholder="e.g. https://" value="<?php echo $this->user->zapurl ?>">
                    <p class="help-block"><?php echo e("We will send a notification to this URL when you create a short URL.") ?></p>
                  </div>
                  <div class="form-group">
                    <label for="zapview" class="control-label"><?php echo e("Views Zapier Notification") ?></label>
                    <input type="text" id="zapview" name="zapview" class="form-control" placeholder="e.g. https://" value="<?php echo $this->user->zapview ?>">
                    <p class="help-block"><?php echo e("We will send a notification to this URL when someone clicks your URL.") ?></p>
                  </div>
                  <?php echo Main::csrf_token(true) ?>
                  <button class="btn btn-primary" type="submit"><?php echo e("Save") ?></button>
                </form>
                <hr>
                <h3><?php echo e("Sample Response") ?></h3>
                <strong><?php echo e("URL Zapier Notification") ?></strong>
                <pre class="code">{<br>&nbsp;&nbsp;"type":"url",<br>&nbsp;&nbsp;"longurl":"https://google.com",<br>&nbsp;&nbsp;"shorturl":"<?php echo Main::href("") ?>C2Rxy",<br>&nbsp;&nbsp;"title":"Google",<br>&nbsp;&nbsp;"date":"17-05-2020 04:17:44"<br>}</pre>

                <br>
                <strong><?php echo e("Views Zapier Notification") ?></strong>
                <pre class="code">{<br>&nbsp;&nbsp"type":"view",<br>&nbsp;&nbsp;"shorturl":"<?php echo Main::href("") ?>C2Rxy",<br>&nbsp;&nbsp;"country":"Canada",<br>&nbsp;&nbsp;"referer":"https://yahoo.com",<br>&nbsp;&nbsp;"os":"Windows",<br>&nbsp;&nbsp;"browser":"Chrome",<br>&nbsp;&nbsp;"date":"17-05-2020 04:20:19"<br>}</pre>                                  
              </div>
            </div>

        </div>
        <?php if (isset($slack)): ?>      
          <div class="tabbed" id="slack">
            <div class="panel panel-default">
              <div class="panel-body">
                 <h3>
                  <i class="fab fa-slack"></i> <?php echo e("Slack Integration") ?>
                  <?php if($this->user->slackid): ?>
                    <span class='pull-right tools-status'><i class="fa fa-check-circle"></i> <?php echo e("Connected") ?></span>
                  <?php endif ?>
                 </h3>
                 <p><?php echo e("You can integrate this app with your slack account and shorten directly from the slack interface using the command line below. This slack integration will save all of your links in your account in case you need to access them later. Please see below how to use the command.") ?></p>
                 <?php if ($this->user->slackid): ?>
                    <h5><strong><?php echo e("Slack Command") ?></strong></h5>
                    <p><pre class="code">/<?php echo $this->config["slackcommand"] ?></pre></p>

                    <h5><strong><?php echo e("Example") ?></strong></h5>
                    <p><pre class="code">/<?php echo $this->config["slackcommand"] ?> https://google.com</pre></p>    

                    <h5><strong><?php echo e("Example with custom name") ?></strong></h5>
                    <p><?php echo e("To send a custom alias, use the following paramter (ABCDXYZ). This will tell the script to choose shorten the link with the custom alias ABCDXYZ.") ?></p>
                    <p><pre class="code">/<?php echo $this->config["slackcommand"] ?> (google) https://google.com</pre></p>                    
                 <?php else: ?>
                    <p><?php echo $slack->generateAuth() ?></p>
                 <?php endif ?>

                 <p><?php echo e("The slack command will return you the short link if everything goes well. In case there is an error, it will return you the original link. If you use the custom parameter and the alias is already taken, the script will automatically increment the alias by 1 - this means if you choose 'google' and 'google' is not available, the script will use google-1") ?></p>
              </div>
            </div>
          </div>
        <?php endif ?>
      </div>
    </div>          
  </div><!--/#user-content-->
</div><!--/.row-->
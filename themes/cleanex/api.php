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
            <li><a href="<?php echo Main::href("user/tools#quick") ?>" data-link = "true"><?php echo e("Quick Shortener") ?></a></li>
            <li><a href="<?php echo Main::href("user/tools#bk") ?>" data-link = "true"><?php echo e("Bookmarklet") ?></a></li>
            <li class="active"><a href="<?php echo Main::href("user/tools/api") ?>" data-link = "true"><?php echo e("Developer API") ?> 2.0</a></li>
            <li><a href="<?php echo Main::href("user/tools#jshort") ?>" data-link = "true"><?php echo e("Full-Page Script") ?></a></li>
            <li><a href="<?php echo Main::href("user/tools#zapier") ?>" data-link = "true"><?php echo e("Zapier Integration") ?></a></li>
            <?php if (isset($slack)): ?>
              <li><a href="<?php echo Main::href("user/tools#slack") ?>" data-link = "true"><?php echo e("Slack Integration") ?></a></li>
            <?php endif ?>
          </ul>
          <br>
        </div>
      </div>
      <div class="col-md-9">        
        <div id="api">
          <?php if ($this->user->admin): ?>
            <p class="alert alert-info"><strong>Hey <?php echo $this->user->name  ? $this->user->name : "Admin"?></strong> A more powerful API is available for admins where you can create accounts or get user's data. You can find more info <a href="https://gempixel.com/docs/premium-url-shortener-api-guide" class="btn btn-xs btn-primary" target="_blank">here</a></p>
          <?php endif ?>
          <div class="panel panel-default">
            <div class="panel-body">
              <h3><i class="glyphicon glyphicon-cloud"></i> <?php echo e("Developer API 2.0") ?></h3>

              <p><?php echo e("An API key is required for requests to be processed by the system. Once a user registers, an API key is automatically generated for this user. The API key must be sent with each request.(see full example below). If the API key is not sent or is expired, there will be an error. Please make sure to keep your API key secret to prevent abuse.") ?></p>

              <p><strong><?php echo e("Your API key") ?></strong></p>
              <pre class="code"><span><?php echo $this->user->api ?></span></pre>
              <a href="<?php echo Main::href("user/tools/regenerate").Main::nonce("regenerate_api") ?>" class="btn btn-primary delete" title="<?php echo e("Regenerate API Key") ?>" data-content="<?php echo e("If you proceed, your current applications will not work anymore. You will need to change your api key for it to work again.") ?>"><?php echo e("Regenerate") ?></a>
              <hr>
              <p class="alert alert-info"><?php printf("Developer API 1.0 will be deprecated soon. We highly recommend you upgrade to API 2.0. If you still need to access API 1.0, you can do so by clicking %s", "<a class='btn btn-primary btn-xs' href='".Main::href("user/tools#api")."'>".e("here")."</a>") ?></p>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-body">
              <h3><?php echo e("Authentication") ?></h3>          
              <p><?php echo e("To authenticate with the API system, you need to send your API key as an authorization token with each request. You can see sample code below.") ?></p>
              <div class="btn-group code-lang">
                <a href="#curl" class="btn btn-sm active">cURL</a>
                <a href="#php" class="btn btn-sm">PHP</a>
              </div>
              <div class="code-selector" data-id="curl">
                <pre><code class="bash"><?php echo str_replace("                  ","", "curl --location --request POST '".Main::href("api/url/add")."' \ 
                  --header 'Authorization: Token {$this->user->api}' \
                  --header 'Content-Type: application/json' \ ") ?></code></pre>                
              </div>
              <div class="code-selector" data-id="php">
                <pre><code class="php"><?php echo str_replace("                  ","", '$curl = curl_init();
                  curl_setopt_array($curl, array(
                    CURLOPT_URL => "'.Main::href("api/url/add").'",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 2,
                    CURLOPT_TIMEOUT => 10,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_HTTPHEADER => array(
                      "Authorization: Token '.$this->user->api.'",
                      "Content-Type: application/json",
                    ),
                  ));

                  $response = curl_exec($curl);') ?></code></pre>                
              </div>      
            </div>
          </div>

          <div class="panel panel-default">
            <div class="panel-body">
              <h3><?php echo e("Shortening a link") ?></h3>   
              <p><?php echo e("To shorten a url, you need to send a valid date in JSON via a POST request. The data must be sent as the raw body of your request as shown below. The example below shows all the parameters you can send but you are not required to send all (See table for more info).") ?></p>
              <div class="btn-group code-lang">
                <a href="#curl" class="btn btn-sm active">cURL</a>
                <a href="#php" class="btn btn-sm">PHP</a>
              </div>
              <div class="code-selector" data-id="curl">
                <pre><code class="bash"><?php echo str_replace("                  ","", "curl --location --request POST '".Main::href("api/url/add")."' \
                  --header 'Authorization: Token {$this->user->api}' \
                  --header 'Content-Type: application/json' \
                  --data-raw '{
                      \"url\": \"https://google.com\",
                      \"custom\": \"google\",
                      \"password\": \"mypass\",
                      \"domain\": \"http://goo.gl\",
                      \"expiry\": \"2020-11-11 12:00:00\",
                      \"type\": \"splash\",
                      \"geotarget\": [{
                          \"location\": \"Canada\",
                          \"link\": \"https://google.ca\"
                        },
                        {
                          \"location\": \"United States\",
                          \"link\": \"https://google.us\"
                        }
                      ],
                      \"devicetarget\": [{
                          \"device\": \"iPhone\",
                          \"link\": \"https://google.com\"
                        },
                        {
                          \"device\": \"Android\",
                          \"link\": \"https://google.com\"
                        }
                      ]
                    }'") ?></code></pre>                
              </div>
              <div class="code-selector" data-id="php">
                <pre><code class="php"><?php echo str_replace("                  ","", '$curl = curl_init();

                  curl_setopt_array($curl, array(
                    CURLOPT_URL => "'.Main::href("api/url/add").'",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 2,
                    CURLOPT_TIMEOUT => 10,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_HTTPHEADER => array(
                      "Authorization: Token '.$this->user->api.'",
                      "Content-Type: application/json",
                    ),
                    CURLOPT_POSTFIELDS => \'{
                      "url": "https://google.com",
                      "custom": "google",
                      "password": "mypass",
                      "domain": "http://goo.gl",
                      "expiry": "2020-11-11 12:00:00",
                      "type": "splash",
                      "geotarget": [{
                          "location": "Canada",
                          "link": "https://google.ca"
                        },
                        {
                          "location": "United States",
                          "link": "https://google.us"
                        }
                      ],
                      "devicetarget": [{
                          "device": "iPhone",
                          "link": "https://google.com"
                        },
                        {
                          "device": "Android",
                          "link": "https://google.com"
                        }
                      ]
                    }\',
                  ));

                  $response = curl_exec($curl);

                  curl_close($curl);
                  echo $response;') ?></code></pre>                
              </div>  
   
              <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>
                          <strong><?php echo e("Parameter") ?></strong>
                        </th>
                        <th>
                          <strong><?php echo e("Description") ?></strong>
                        </th>
                      </tr>
                    </thead>

                    <tbody>    
                      <tr>
                        <td><strong>url</strong></td>
                        <td><i>(<?php echo e("required") ?>)</i> <?php echo e("Long URL to shorten.") ?></td>
                      </tr>                                       
                      <tr>
                        <td>custom</td>
                        <td><i>(<?php echo e("optional") ?>)</i> <?php echo e("Custom alias instead of random alias.") ?></td>
                      </tr>
                      <tr>
                        <td>type</td>
                        <td><i>(<?php echo e("optional") ?>)</i> <?php echo e("Redirection type [direct, frame, splash]") ?></td>
                      </tr>   
                      <tr>
                        <td>password</td>
                        <td><i>(<?php echo e("optional") ?>)</i> <?php echo e("Password protection") ?></td>
                      </tr>  
                      <tr>
                        <td>domain</td>
                        <td><i>(<?php echo e("optional") ?>)</i> <?php echo e("Custom Domain") ?></td>
                      </tr> 
                      <tr>
                        <td>expiry</td>
                        <td><i>(<?php echo e("optional") ?>)</i> <?php echo e("Expiration for the link example ") ?> <?php echo date("Y-m-d H:i:s") ?></td>
                      </tr> 
                      <tr>
                        <td>geotarget</td>
                        <td><i>(<?php echo e("optional") ?>)</i> <?php echo e("Geotargetting data") ?></td>
                      </tr>
                      <tr>
                        <td>devicetarget</td>
                        <td><i>(<?php echo e("optional") ?>)</i> <?php echo e("Device Targetting data") ?></td>
                      </tr>                                                                                                           
                    </tbody>
                  </table>                   
              </div>

              <h5><?php echo e("Server response") ?></h5>
              <p><?php echo e("As before, the response will encoded in JSON format (default). This is done to facilitate cross-language usage. The first element of the response will always tell if an error has occurred (error: 1) or not (error: 0). The second element will change with respect to the first element. If there is an error, the second element will be named 'msg'. which contains the source of error, otherwise it will be named 'short' which contains the short URL. (See below for an example)") ?></p>

              <pre><code class="json"><?php echo str_replace("                  ","", '{
                    "error" : 0,
                    "short": "'.Main::href("TlSo4").'"
                  }') ?></code></pre> 
            </div>
          </div>

          <div class="panel panel-default">
            <div class="panel-body">
              <h3><?php echo e("Get your links") ?></h3>   
              <p><?php echo e("To get your links via the API, you can use the /api/url/get endpoint. You can also filter data (See table for more info).") ?></p>
              <div class="btn-group code-lang">
                <a href="#curl" class="btn btn-sm active">cURL</a>
                <a href="#php" class="btn btn-sm">PHP</a>
              </div>
              <div class="code-selector" data-id="curl">
                <pre><code class="bash"><?php echo str_replace("                  ","", "curl --location --request POST '".Main::href("api/url/get")."' \
                  --header 'Authorization: Token {$this->user->api}' \
                  --header 'Content-Type: application/json' \
                  --data-raw '{
                      \"limit\": 12,
                      \"page\" : 2,
                      \"order\": \"date\"
                  }'") ?></code></pre>                
              </div>
              <div class="code-selector" data-id="php">
                <pre><code class="php"><?php echo str_replace("                  ","", '$curl = curl_init();

                  curl_setopt_array($curl, array(
                    CURLOPT_URL => "'.Main::href("api/url/get").'",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 2,
                    CURLOPT_TIMEOUT => 10,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_HTTPHEADER => array(
                      "Authorization: Token '.$this->user->api.'",
                      "Content-Type: application/json",
                    ),
                    CURLOPT_POSTFIELDS => \'{
                        "limit": 2,
                        "page" : 1,
                        "order": "date"
                    }\',
                  ));

                  $response = curl_exec($curl);

                  curl_close($curl);
                  echo $response;') ?></code></pre>                
              </div>  
   
              <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>
                          <strong><?php echo e("Parameter") ?></strong>
                        </th>
                        <th>
                          <strong><?php echo e("Description") ?></strong>
                        </th>
                      </tr>
                    </thead>

                    <tbody>    
                      <tr>
                        <td>limit</td>
                        <td><i>(<?php echo e("optional") ?>)</i> <?php echo e("Per page data result") ?></td>
                      </tr> 
                      <tr>
                        <td>page</td>
                        <td><i>(<?php echo e("optional") ?>)</i> <?php echo e("Current page request") ?></td>
                      </tr>
                      <tr>
                        <td>order</td>
                        <td><i>(<?php echo e("optional") ?>)</i> <?php echo e("Sort data between date or click") ?></td>
                      </tr>                                                                                                           
                    </tbody>
                  </table>                   
              </div>
              
              <h5><?php echo e("Server response") ?></h5>

              <pre><code class="json"><?php echo str_replace("                  ","", '{
                    "error": "0",
                    "data": {
                      "result": 2,
                      "perpage": 2,
                      "currentpage": 1,
                      "nextpage": 1,
                      "maxpage": 1,
                      "urls": [{
                        "id": 2,
                        "alias": "google",
                        "shorturl": "'.Main::href("google").'",
                        "longurl": "https:\/\/google.com",
                        "clicks": 0,
                        "title": "Google",
                        "description": "",
                        "date": "2020-11-10 18:01:43"
                      }, {
                        "id": 1,
                        "alias": "googlecanada",
                        "shorturl": "'.Main::href("googlecanada").'",
                        "longurl": "https:\/\/google.ca",
                        "clicks": 0,
                        "title": "Google Canada",
                        "description": "",
                        "date": "2020-11-10 18:00:25"
                      }]
                    }
                  }') ?></code></pre> 
            </div>
          </div>

          <div class="panel panel-default">
            <div class="panel-body">
              <h3><?php echo e("Get link stats") ?></h3>   
              <p><?php echo e("To get statistics for a short link via the API, you can use the /api/url/stats endpoint. You need to send the url id (See table for more info).") ?></p>
              <div class="btn-group code-lang">
                <a href="#curl" class="btn btn-sm active">cURL</a>
                <a href="#php" class="btn btn-sm">PHP</a>
              </div>
              <div class="code-selector" data-id="curl">
                <pre><code class="bash"><?php echo str_replace("                  ","", "curl --location --request POST '".Main::href("api/url/stats")."' \
                  --header 'Authorization: Token {$this->user->api}' \
                  --header 'Content-Type: application/json' \
                  --data-raw '{
                      \"urlid\": 2
                  }'") ?></code></pre>                
              </div>
              <div class="code-selector" data-id="php">
                <pre><code class="php"><?php echo str_replace("                  ","", '$curl = curl_init();

                  curl_setopt_array($curl, array(
                    CURLOPT_URL => "'.Main::href("api/url/stats").'",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 2,
                    CURLOPT_TIMEOUT => 10,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_HTTPHEADER => array(
                      "Authorization: Token '.$this->user->api.'",
                      "Content-Type: application/json",
                    ),
                    CURLOPT_POSTFIELDS => \'{
                      "urlid": 2
                    }\',
                  ));

                  $response = curl_exec($curl);

                  curl_close($curl);
                  echo $response;') ?></code></pre>                
              </div>  
   
              <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>
                          <strong><?php echo e("Parameter") ?></strong>
                        </th>
                        <th>
                          <strong><?php echo e("Description") ?></strong>
                        </th>
                      </tr>
                    </thead>

                    <tbody>    
                      <tr>
                        <td><strong>urlid</strong></td>
                        <td><i>(<?php echo e("required") ?>)</i> <?php echo e("Short URL ID") ?></td>
                      </tr>                                                                                                          
                    </tbody>
                  </table>                   
              </div>
              
              <h5><?php echo e("Server response") ?></h5>

              <pre><code class="json"><?php echo str_replace("                  ","", '{
                    "error": 0,
                    "details": {
                      "shorturl": "'.Main::href("google").'",
                      "longurl": "https:\/\/google.com",
                      "title": "Google",
                      "description": "",
                      "location": {
                        "canada": "https:\/\/google.ca",
                        "united states": "https:\/\/google.us"
                      },
                      "device": {
                        "iphone": "https:\/\/google.com",
                        "android": "https:\/\/google.com"
                      },
                      "expiry": null,
                      "date": "2020-11-10 18:01:43"
                    },
                    "data": {
                      "clicks": 0,
                      "uniqueClicks": 0,
                      "topCountries": 0,
                      "topReferrers": 0,
                      "topBrowsers": 0,
                      "topOs": 0,
                      "socialCount": {
                        "facebook": 0,
                        "twitter": 0,
                        "google": 0
                      }
                    }
                  }') ?></code></pre> 
            </div>
          </div>

          <div class="panel panel-default">
            <div class="panel-body">
              <h3><?php echo e("Error handling") ?></h3>   
              <p><?php echo e("You should always check for the error key. In case there is an error, it will be set to 1 and a msg key will describe the issue.") ?></p>              

              <pre><code class="json"><?php echo str_replace("                  ","", '{
                    "error" : 1,
                    "msg": "Please enter valid url"
                  }') ?></code></pre> 
            </div>
          </div>
        </div>
      </div>
    </div>          
  </div>
</div>
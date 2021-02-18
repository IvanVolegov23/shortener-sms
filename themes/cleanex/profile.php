<?php defined("APP") or die() ?>
<!DOCTYPE html>
<html lang="en" prefix="og: http://ogp.me/ns#">
  <head>       
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">    
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1.0" />  
    <meta name="description" content="<?php echo Main::description() ?>" />
    <!-- Open Graph Tags -->
    <?php echo Main::ogp(); ?> 

    <title><?php echo Main::title() ?></title> 
        
    <!-- Bootstrap core CSS -->
    <link href="<?php echo $this->config["url"] ?>/static/css/bootstrap.min.css" rel="stylesheet">
	  <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
	  <script type="text/javascript" src="<?php echo $this->config["url"] ?>/static/bootstrap.min.js"></script>
	  <script type="text/javascript" src="<?php echo $this->config["url"] ?>/static/application.fn.js"></script>
	 	<script type="text/javascript">
	    var appurl="<?php echo $this->config["url"] ?>";
	    var token="<?php echo $this->config["public_token"] ?>";  
	  </script>    
    <script type="text/javascript" src="<?php echo $this->config["url"] ?>/static/application.js"></script>
    <?php Main::enqueue() // Add scripts when needed ?>  
</head>
<body id="custom-profile" class="custom-profile_<?php echo $profile["scheme"] ?>">
	<div class="text-center">
		<div class="custom-profile_header">
			<img src="<?php echo $this->avatar($user) ?>" class="user-avatar">
			<h3><span><?php echo $profile["name"] ?></span> <em>@<?php echo $user->username ?></em></h3>
			<?php if (!empty($this->id)): ?>
				<a href="<?php echo Main::href("u/{$user->username}") ?>" class="btn btn-custom btn-xs"><?php echo e("Back to Profile") ?></a>	
			<?php endif ?>				
			<?php if ($profile["bundle"]): ?>
				<a href="#" data-id="<?php echo base64_encode(Main::strrand(3).$user->id) ?>" data-active="active" data-action="bundles" class="btn btn-custom btn-xs fetchBundles" id="view-lists"><?php echo e("View Lists") ?></a>	
			<?php endif ?>			
		</div>

		<div class="custom-profile_body">
			<?php if (!empty($this->id)): ?>
				<div class="panel panel-default">
					<?php if ($urls): ?>
						<?php foreach ($urls as $url): ?>
							<?php include(TEMPLATE."/shared/public_url_loop.php") ?>
						<?php endforeach ?>
						<?php echo $pagination ?>
					<?php else: ?>
						<?php echo e("No URLs found.") ?>												
					<?php endif ?>						
				</div>
			<?php else: ?>
				<div class="custom-profile_links">
						<?php foreach ($profile["links"] as $i => $links): ?>
							<a href="<?php echo $links["short"] ?>" target="_blank" class="btn btn-block" data-id='<?php echo $i++ ?>'><span><?php echo $links["text"] ?><span></a>							
						<?php endforeach ?>													
				</div>
			<?php endif ?>
		</div>	
	</div>		
	<div class="custom-profile_branding">
    <a href="<?php echo $this->config["url"] ?>">
      <?php if (!empty($this->config["logo"])): ?>
      <img src="<?php echo $this->config["url"] ?>/content/<?php echo $this->config["logo"] ?>" alt="<?php echo $this->config["title"] ?>">
      <?php else: ?>
        <?php echo $this->config["title"] ?>
      <?php endif ?>
    </a>		
	</div>
	<script>   
    $("body").height($(document).height());
    $(document).resize(function(){
    	$("body").height($(document).height());
    });
  </script>	  
  <script type="text/javascript" src="<?php echo $this->config["url"] ?>/static/server.js"></script> 	
	<script type="text/javascript">
    <?php 
      $js_lang = array(
        "del" => e("Delete"),
        "url" => e("URL"),
        "count" => e("Country"),
        "copied"  =>  e("Copied"),
        "geo" => e("Geotargeting data for"),
        "error" => e('Please enter a valid URL.'),
        "success" => e('URL has been successfully shortened. Click Copy or CRTL+C to Copy it.'),
        "stats" => e('You can access the statistic page via this link'),
        "copy" => e('Copied to clipboard.'),
        "from" => e('Redirect from'),
        "to" => e('Redirect to'),
        "share" => e('Share this on'),
        "congrats"  => e('Congratulation! Your URL has been successfully shortened. You can share it on Facebook or Twitter by clicking the links below.'),
        "qr" => e('Save QR Code'),
        "continue"  =>  e("Continue"),
        "cookie" => e("This website uses cookies to ensure you get the best experience on our website."),
        "cookieok" => e("Got it!"),
        "cookiemore" => e("Learn more"),
        "couponinvalid" => e("The coupon enter is not valid"),
        "minurl" => e("You must select at least 1 url."),
        "minsearch" => e("Keyword must be more than 3 characters!"),
        "modal" => [
          "title" => e("Are you sure you want to proceed?"),
          "proceed" => e("Proceed"),
          "cancel" => e("Cancel"),
          "close" => e("Close"),
          "content" => e("Note that this action is permanent. Once you click proceed, you <strong>may not undo</strong> this. Click anywhere outside this modal or click <a href='#close' class='close-modal'>close</a> to close this.")
        ]
      );
    ?>
    var lang = <?php echo json_encode($js_lang) ?>;
  </script>    
 <?php Main::enqueue('footer') ?> 
</body>
</html>
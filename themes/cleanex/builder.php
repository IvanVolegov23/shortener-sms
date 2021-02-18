<?php defined("APP") or die() // Main User Page Template (used to add dynamic pages) ?>
<div class="row">	
  <div id="user-content" class="col-md-7">  	
		<?php echo Main::message() ?>  	
		<div class="main-content panel panel-default panel-body">
			<h3><?php echo e("Profile Builder") ?></h3>

			<p><?php echo e("The profile builder allows you to customize your profile so you can only share one link and users can view all of the links on a single page.") ?></p>
			
			<form action="<?php echo Main::href("user/builder") ?>" method="post" id="profile-builder">
				<div class="form-group">
					<label class="control-label"><?php echo e("Profile Name") ?></label>
					<input type='text' class='form-control' name='name'  placeholder='e.g. Google' value='<?php echo $profile["name"] ?>'>	
					<p class="help-block"><?php echo e("This field is optional. If left empty, only the username will show.") ?></p>				
				</div>

				<div class='form-group' id="themes">
			    <label class='control-label'><?php echo e("Page Style") ?></label>
		      <ul class='themes-style' data-callback = 'changeTheme'>
		      	<li><a href='' <?php echo ($profile["scheme"] == "default" ? "class='current'": "") ?>data-class='default' style='background: linear-gradient(135deg, rgba(255,255,255,1) 49%, rgba(0,212,255,1) 49%);'>White/Blue</a></li>

		      	<li><a href='' <?php echo ($profile["scheme"] == "white-green" ? "class='current'": "") ?> data-class='white-green' style='background: linear-gradient(135deg, rgba(255,255,255,1) 49%, rgba(0,255,154,1) 49%);'>White/Green</a></li>

		      	<li><a href='' <?php echo ($profile["scheme"] == "white-red" ? "class='current'": "") ?>data-class='white-red' style='background: linear-gradient(135deg, rgba(255,255,255,1) 49%, rgba(255,0,61,1) 49%);'>White/Red</a></li>
						
						<li><a href='' <?php echo ($profile["scheme"] == "white-black" ? "class='current'": "") ?>data-class='white-black' style='background: linear-gradient(135deg, rgba(255,255,255,1) 49%, rgba(0,0,0,1) 49%);'>White/Black</a></li>

		      	<li><a href='' <?php echo ($profile["scheme"] == "red-pink" ? "class='current'": "") ?>data-class='red-pink' style='background: linear-gradient(135deg, rgba(255,0,61,1) 0%, rgba(255,0,134,1) 100%);'>Red/Pink</a></li>

		      	<li><a href='' <?php echo ($profile["scheme"] == "green-yellow" ? "class='current'": "") ?>data-class='green-yellow' style='background: linear-gradient(135deg, rgba(34,193,195,1) 0%, rgba(253,187,45,1) 100%);'>Green/Yellow</a></li>

		      	<li><a href='' <?php echo ($profile["scheme"] == "blue-blue" ? "class='current'": "") ?>data-class='blue-blue' style='background: linear-gradient(135deg, #00C9FF 0%, #92FE9D 100%);'>Blue/Blue</a></li>

		      	<li><a href='' <?php echo ($profile["scheme"] == "black-blue" ? "class='current'": "") ?>data-class='black-blue' style='background: linear-gradient(135deg, #4b6cb7 0%, #182848 100%);'>Black/Blue</a></li>

		      	<li><a href='' <?php echo ($profile["scheme"] == "red-violet" ? "class='current'": "") ?>data-class='red-violet' style='background: linear-gradient(135deg, #FC466B 0%, #3F5EFB 100%);'>Red-Violet</a></li>

		      	<li><a href='' <?php echo ($profile["scheme"] == "black" ? "class='current'": "") ?>data-class='black' style='background: linear-gradient(135deg, rgba(75,75,75,1) 0%, rgba(0,0,0,1) 100%);'>Black</a></li>
		      </ul>			
		      <input type='hidden' name='scheme' id='theme_value' value='<?php echo $profile["scheme"] ?>'>			    
		  	</div>	
				<hr>
				<h3><?php echo e("Links") ?> <small>(max 10)</small> <a href='' class='addLinks btn btn-xs btn-default pull-right'><?php echo e("Add Link") ?></a></h3>
				<p class='help-block'><?php echo e("You can add up to 10 links. To add an extra link click Add Link above. To ignore a field, leave it empty. If you have already shortened the link added below, we will use the short url as the link to the button otherwise we will create a new short url so you can track clicks. Please do not paste short links.") ?></p>

				<div class='links'>					
					<?php if ($profile["links"]): ?>
						<?php foreach ($profile["links"] as $i => $links): ?>
							<div class='form-group' data-id = '<?php echo $i++ ?>'>
								<div class="row">
									<div class="col-md-6">
										<label class="control-label"><?php echo e("Link") ?></label>
										<input type='text' class='form-control' name='links[]'  value="<?php echo $links["link"] ?>">								
									</div>
									<div class="col-md-6">
										<label class="control-label"><?php echo e("Label") ?></label>
										<input type='text' class='form-control' name='text[]'  data-input='label' value="<?php echo $links["text"] ?>">
									</div>
								</div>								
							</div>	
							<p><a href='#' class='btn btn-danger btn-xs deleteLinks'><?php echo e("Delete") ?></a></p>												
						<?php endforeach ?>						
					<?php else: ?>
						<div class='form-group' data-id = '1'>
							<div class="row">
								<div class="col-md-6">
									<label class="control-label"><?php echo e("Link") ?></label>
									<input type='text' class='form-control' name='links[]'  placeholder='e.g. https://google.com'>								
								</div>
								<div class="col-md-6">
									<label class="control-label"><?php echo e("Label") ?></label>
									<input type='text' class='form-control' name='text[]'  data-input='label' placeholder='e.g. Instagram'>
								</div>
							</div>
						</div>								
					<?php endif ?>							
				</div>	
				<hr>
				<ul class="form_opt" data-id="bundle" data-callback = "showBundle">
					<li class="text-label"><?php echo e("Enable Public Bundles")?>
					<small><?php echo e("Enable a button to show all public bundles.")?></small>
					</li>
					<li><a href="" class="last<?php echo (!$profile["bundle"] ? " current" : "") ?>" data-value="0"><?php echo e("No")?></a></li>
					<li><a href="" class="first<?php echo ($profile["bundle"] ? " current" : "") ?>" data-value="1"><?php echo e("Yes")?></a></li>
				</ul>
				<input type="hidden" name="bundle" id="bundle" value="<?php echo $profile["bundle"] ?>">
				<?php /** 
				<ul class="form_opt" data-id="all" data-callback = "showAll">
					<li class="text-label"><?php echo e("Enable Public Links")?>
					<small><?php echo e("Enable a button to show all public links.")?></small>
					</li>
					<li><a href="" class="last<?php echo (!$profile["all"] ? " current" : "") ?>" data-value="0"><?php echo e("No")?></a></li>
					<li><a href="" class="first<?php echo ($profile["all"] ? " current" : "") ?>" data-value="1"><?php echo e("Yes")?></a></li>
				</ul>
				<input type="hidden" name="all" id="all" value="<?php echo $profile["all"] ?>">
				**/ ?>
				<?php echo Main::csrf_token(true) ?>	

				<p><input type="submit" class="btn btn-primary" value="<?php echo e("Save Profile") ?>"></p>							
			</form>
			
		</div>	
  </div><!--/#user-content-->
  <div id="widgets" class="col-md-5">
  	<?php if (!$this->user->public): ?>
  		<div class="alert alert-danger"><?php echo e("You have to make your profile public for this page to be accessible.") ?></div>
  	<?php endif ?>
  	<div class="panel panel-default">
			<div class="text-center">
				<div id="custom-profile" class="custom-profile_<?php echo $profile["scheme"] ?>">
						<div class="custom-profile_header">
							<img src="<?php echo $this->user->avatar ?>" class="user-avatar">
							<h3><span><?php echo $profile["name"] ?></span> <em>@<?php echo $this->user->username ?></em></h3>
							<a href="#" class="btn btn-custom btn-xs" id="view-lists" <?php echo (!$profile["bundle"] ? 'style="display: none"' : '') ?>><?php echo e("View Lists") ?></a>							
							<?php /** 					
								<a href="#" class="btn btn-custom btn-xs" id="view-links" <?php echo (!$profile["all"] ? 'style="display: none"' : '') ?>><?php echo e("View Links") ?></a>	
							 **/ ?>
						</div>
						<div class="custom-profile_body">
							<div class="custom-profile_links">

								<?php if ($profile["links"]): ?>
									<?php foreach ($profile["links"] as $i => $links): ?>
										<a href="#" class="btn btn-block" data-id='<?php echo $i++ ?>'><span><?php echo $links["text"] ?><span></a>							
									<?php endforeach ?>						
								<?php else: ?>
									<a href="#" class="btn btn-block" data-id='1'><span>#1<span></a>								
								<?php endif ?>									
							</div>
						</div>			
				</div>
			</div>
		</div>
		<div class="panel panel-default panel-body">
			<div class="row">
				<div class="col-md-8">
					<h3><?php echo e("Public Profile") ?></h3>

					<p><i class='glyphicon glyphicon-link'></i> <?php echo Main::href("u/{$this->user->username}") ?> <a href='#' class='inline-copy copy' data-clipboard-text='<?php echo Main::href("u/{$this->user->username}") ?>'><?php echo e("Copy") ?></a></p>

					<p><i class='glyphicon glyphicon-qrcode'></i> <?php echo Main::href("u/{$this->user->username}/qr") ?> <a href='#' class='inline-copy copy' data-clipboard-text='<?php echo Main::href("u/{$this->user->username}/qr") ?>'><?php echo e("Copy") ?></a></p>					
				</div>
				<div class="col-md-4">
					<div class="pull-right">
						<img src="<?php echo Main::href("u/{$this->user->username}/qr") ?>" width="100" class="img-responsive">
					</div>
				</div>
			</div>
		</div>
  </div><!--/#widgets-->
</div><!--/.row-->
<div class="hidden links-template">
	<div class='form-group' data-id='null'>
		<div class="row">
			<div class="col-md-6">
				<label class="control-label"><?php echo e("Link") ?></label>
				<input type='text' class='form-control' name='links[]'  placeholder='e.g. https://google.com'>								
			</div>
			<div class="col-md-6">
				<label class="control-label"><?php echo e("Label") ?></label>
				<input type='text' class='form-control' name='text[]'  data-input='label' placeholder='e.g. Instagram'>
			</div>
		</div>
	</div>
	<p><a href='#' class='btn btn-danger btn-xs deleteLinks'><?php echo e("Delete") ?></a></p>
</div>
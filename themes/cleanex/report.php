<?php defined("APP") or die() ?>
<section class="dark">
	<div class="container">
		<ol class="breadcrumb">
		  <li><a href="<?php echo Main::href("") ?>"><?php echo e("Home") ?></a></li>
		  <li class="active"><?php echo e("Report Link") ?></li>
		</ol>
	</div>
</section>
<section>
	<div class="container">    
		<div class="centered form">      
      <?php echo Main::message() ?>
      <form role="form" class="live_form" method="post" action="<?php echo Main::href("report")?>">
      	<h3><?php echo e("Report Link") ?></h3>
      	<p><?php echo e("Please report a link that you consider risky or dangerous. We will review all cases and take measure to remove the link."); ?></p>
      	<hr>
        <div class="form-group">
          <label><?php echo e("Email") ?> (<?php echo e("Required") ?>)</label>
          <input type="email" class="form-control" name="email" value="<?php echo ($this->logged() ? $this->user->email : "") ?>" required>		            
        </div>  
        <div class="form-group">
          <label><?php echo e("Short Link") ?> (<?php echo e("Required") ?>)</label>
          <input type="text" class="form-control" name="link" value="" placeholder="e.g. <?php echo Main::href('abc123') ?>">
        </div>  
        <div class="form-group">
          <label><?php echo e("Reason") ?> (<?php echo e("Required") ?>)</label>
          <select name="reason" id="reason">
            <option value="spam"><?php echo e("Spam") ?></option>
            <option value="fraudulent"><?php echo e("Fraudulent") ?></option>
            <option value="malicious"><?php echo e("Malicious") ?></option>
            <option value="phishing"><?php echo e("Phishing") ?></option>
          </select>
        </div>        
				<p><?php echo Main::captcha() ?></p>
        <?php echo Main::csrf_token(TRUE) ?>
        <button type="submit" class="btn btn-primary"><?php echo e("Send") ?></button>       
      </form>        
		</div>
	</div>
</section>
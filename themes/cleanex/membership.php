<?php defined("APP") or die() // Settings Page ?>
<div class="row">	
  <div id="user-content" class="col-md-8">  	
  	<?php echo $this->ads(728) ?>
		<?php echo Main::message() ?>  			
		<?php echo $this->last_payments() ?>
  </div><!--/#user-content-->
  <div id="widgets" class="col-md-4">
  	<?php echo $this->sidebar() ?>  	
		<?php if($this->pro() && !$this->isTeam()) : ?>
			<div class="panel panel-default panel-body">
				<h3><?php echo e("Your Premium Membership") ?></h3>
				<?php $plan = $this->db->get("plans", ["id" => $this->user->planid], ["limit" => 1]) ?>
				<p><strong><?php echo e("Plan Name") ?></strong>: <?php echo $plan->name ?> <?php echo ($this->user->trial ? "(".e("Free Trial").")" : "") ?></p>							
				<p><strong><?php echo e("Last Payment") ?></strong>: <?php echo date("F d, Y", strtotime($this->user->last_payment)) ?></p>
				<p><strong><?php echo e("Expiry") ?></strong>: <?php echo date("F d, Y", strtotime($this->user->expiration)) ?></p>
				<?php if(!$this->user->trial && isset($this->config["pt"]) && $this->config["pt"] == "stripe"): ?>
				<hr>
				<h3><?php echo e("Cancel Membership") ?></h3>
				<p><?php echo e("You can cancel your membership whenever your want. Upon request, your membership will be canceled right before your next payment period. This means you can still enjoy premium features until the end of your membership.") ?></p>
				<p><a href="" class="btn btn-danger btn-round ajax_call" data-action="cancel" data-title="<?php echo e("Cancel Membership") ?>"><?php echo e("Cancel membership") ?></a></p>
				<?php endif ?>
			</div>			
			<div class="panel panel-default panel-body">
				<h3><?php echo e("Plan Limits") ?></h3>
	       <div class="member-stats">
					<p><span><?php echo $this->count("user_urls") ?> <?php echo e("out of")?> <?php echo ($this->user->plan->numurls > 0 ? $this->user->plan->numurls : e("Unlimited")) ?></span> <?php echo e('URLs') ?></p>

	        <?php echo ($this->user->plan->permission->splash->enabled ? '<p><span>'.$this->count("user_splash").' '.e("out of").' '.($this->user->plan->permission->splash->count > 0 ? $this->user->plan->permission->splash->count : e("Unlimited")).'</span> '.e('Splash Pages').'</p>' : '') ?>

	        <?php echo ($this->user->plan->permission->overlay->enabled ? '<p><span>'.$this->count("user_overlay").' '.e("out of").'  '.($this->user->plan->permission->overlay->count > 0 ? $this->user->plan->permission->overlay->count : e("Unlimited")).'</span> '.e('Overlay Pages').'</p>' : '') ?>

	        <?php echo ($this->user->plan->permission->pixels->enabled ? '<p><span>'.$this->count("user_pixels").' '.e("out of").' '.($this->user->plan->permission->pixels->count > 0 ? $this->user->plan->permission->pixels->count : e("Unlimited")).'</span> '.e('Tracking Pixels').'</p>' : '') ?>

	        <?php echo ($this->user->plan->permission->team->enabled ? '<p><span>'.$this->count("user_team").' '.e("out of").'  '.($this->user->plan->permission->team->count > 0 ? $this->user->plan->permission->team->count : e("Unlimited")).'</span> '.e('Team Members').'</p>' : '') ?>

	        <?php echo ($this->user->plan->permission->domain->enabled ? '<p><span>'.$this->count("user_domain").' '.e("out of").' '.($this->user->plan->permission->domain->count > 0 ? $this->user->plan->permission->domain->count : e("Unlimited")).'</span> '.e('Custom Domain').'</p>' : '') ?>	       	
	       </div>
			</div>	
		<?php endif ?>  				
  </div><!--/#widgets-->
</div><!--/.row-->
<?php defined("APP") or die() ?>
<section id="checkout">
	<div class="container">
		<?php echo Main::message() ?>
		<div class="row">
			<div class="col-md-3">
				<div class="panel">
					<div class="panel-heading"><strong><?php echo $term ?></strong></div>
					<div class="panel-body">
						<ul>
							<li><?php echo $text ?> - <?php echo $plan->name ?> <span class="pull-right"><?php echo Main::currency($this->config["currency"], $price) ?></span></li>
							<li class="total"><strong><?php echo e("Total") ?> <span class="pull-right"><?php echo Main::currency($this->config["currency"],$price) ?></span></strong></li>
						</ul>
						<?php if (isset($this->config["pt"]) && in_array($this->config["pt"], ['stripe', 'paypalapi'])): ?>
							<p class="info">
								<?php echo e("Next payment will be automatically charged at the beginning of each period.") ?>
							</p>
						<?php endif ?>
					</div>
				</div>
			</div>
			<div class="col-md-9">
				<div class="panel">
					<div class="panel-body">
						<h3><?php echo e("Checkout") ?></h3>
						<form action="<?php echo Main::href("upgrade/{$this->do}/{$plan->id}") ?>" method="post" id="payment-form">
						<div class="form-group">
								<label for="name"><?php echo e("Full Name") ?></label>
								<input type="text" class="form-control" id="name" name="name" value="<?php echo $this->user->name ?>" required>
							</div>							
							<div class="form-group">
								<label for="address"><?php echo e("Address") ?></label>
								<input type="text" class="form-control" id="address" name="address" value="<?php echo (isset($address->address) ? $address->address : "" ) ?>" required>
							</div>
							<div class="row">
								<div class="col-md-3 col-xs-6">
									<div class="form-group">
										<label for="city"><?php echo e("City") ?></label>
										<input type="text" class="form-control" id="city" name="city" placeholder="e.g. New York" value="<?php echo (isset($address->city) ? $address->city : "" ) ?>" required>
									</div>									
								</div>
								<div class="col-md-3 col-xs-6">
									<div class="form-group">
										<label for="state"><?php echo e("State/Province") ?></label>
										<input type="text" class="form-control" id="state" name="state" placeholder="e.g. NY" value="<?php echo (isset($address->state) ? $address->state : "" ) ?>" required>
									</div>										
								</div>
								<div class="col-md-3 col-xs-6">
									<div class="form-group">
										<label for="country"><?php echo e("Country") ?></label>
										<select name="country" id="country" required>
											<?php echo Main::countries($this->country()) ?>
										</select>
									</div>									
								</div>								
								<div class="col-md-3 col-xs-6">
									<div class="form-group">
										<label for="zip"><?php echo e("Zip/Postal code") ?></label>
										<input type="text" class="form-control" id="zip" name="zip" placeholder="e.g. 44205" value="<?php echo (isset($address->zip) ? $address->zip : "" ) ?>" required>
									</div>										
								</div>								
							</div>					
							<?php echo Main::csrf_token(TRUE) ?>
							<?php if(isset($this->config["pt"]) && $this->config["pt"] == "stripe"): ?>
									<hr>
									<script src="https://js.stripe.com/v3/"></script>
									<input type="hidden" id="stripeToken">
								  <div class="form-row">
								    <label for="card-element">
								      <?php echo e("Credit or debit card") ?>
								    </label>
								    <div id="card-element"></div>
								    <div id="card-errors" role="alert"></div>
								  </div>		
								  <br>					
									<div class="row">
										<div class="col-md-6 col-xs-6">
											<div class="form-group">
												<label for="coupon"><?php echo e("Coupon Code") ?></label>
												<input type="text" class="form-control" id="coupon" name="coupon" placeholder="">
											</div>										
										</div>								
									</div>
									<br>				
									<button class="stripe-button btn btn-primary" type="submit"><?php echo e("Pay with Card") ?></button>																							
							<?php else: ?>							
								<br>
								<button class="btn btn-secondary btn-round" type="submit"><?php echo e("Pay with PayPal") ?></button>
							<?php endif; ?>
						</form>
					</div>
				</div>
				<?php if (isset($this->config["pt"]) && $this->config["pt"] == "stripe"): ?>
					<p><img src="<?php echo Main::fstatic("img/powered_by_stripe.png") ?>" alt="Stripe" class="pull-right"></p>				
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
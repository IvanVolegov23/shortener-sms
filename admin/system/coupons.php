<?php if(!defined("APP")) die()?>
<p class="alert alert-success">
  Please note that at this moment, coupons only work with Stripe Payments. PayPal will be supported very soon. Please note that you cannot edit a coupon. You will need to delete it and create a new one.
</p>
<div class="panel panel-default">
  <div class="panel-heading">
    Coupons
    <a href="<?php echo Main::ahref("coupons/add") ?>" class="pull-right btn btn-primary btn-xs">Add Coupon</a>
  </div>  
  <div class="panel-null">
    <div class="table-responsive">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Coupon Name</th>
            <th>Coupon Code</th>
            <th>Discount</th>
            <th>Valid Until</th>
            <th>Used</th>
            <th></th>
          </tr>
        </thead>
        <tbody>          
          <?php foreach ($coupons as $coupon): ?>
            <tr data-id="<?php echo $coupon->id ?>">
              <td><?php echo $coupon->id ?></td>
              <td><?php echo $coupon->name ?></td>
              <td><span class="label label-primary"><?php echo $coupon->code ?></span></td>
              <td><?php echo $coupon->discount ?>% OFF</td>
              <td><?php echo $coupon->validuntil ? date("d-m-Y", strtotime($coupon->validuntil)) : "N/A"?></td>
              <td><?php echo $coupon->used ?> times</td>
              <td>
                <a href="<?php echo Main::ahref("coupons/delete/{$coupon->id}").Main::nonce("delete_coupon-{$coupon->id}") ?>" class="btn btn-danger btn-xs delete">Delete</a>                
              </td>
            </tr>      
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
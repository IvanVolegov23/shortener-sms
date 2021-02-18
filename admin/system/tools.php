<?php if(!defined("APP")) die(); // Protect this page ?>
<div class="row">
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        Delete Inactive URLs
      </div>
      <div class="panel-body">
        <p>
          This tool deletes URLs that did not receive any clicks in the last 30 days. It can free up some resource in your database.
        </p>
        <a href="<?php echo Main::ahref("urls/inactive").Main::nonce("inactive_urls") ?>" class="btn btn-danger delete">Delete Inactive URLs</a>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        Delete Inactive Users
      </div>
      <div class="panel-body">
        <p>
          This tool deletes users who registered but did not activate their account. This can be users attempting to use fake emails or even spammers.
        </p>
        <a href="<?php echo Main::ahref("users/inactive").Main::nonce("inactive_users") ?>" class="btn btn-danger delete">Delete Inactive Users</a> 
      </div>
    </div> 
    <div class="panel panel-default">
      <div class="panel-heading">
        Remove Anonymous Links
      </div>
      <div class="panel-body">
        <p>
          This tool deletes all URLs (and their associated stats) shortened by anonymous users (non-registered). If you are experiencing slow response, this is recommended. You can also choose a date to remove all anon links before.
        </p>
        <p class="alert alert-danger"><strong>Important</strong> This will remove all anonymous users' links and unless you have a mysql backup, you will not be able to restore these.</p>
        <form action="<?php echo Main::ahref("urls/flush") ?>" method="post">
          <div class="form-group">
            <label for="" class="control-label">Remove Links Before:</label>
            <input type="text" data-toggle ="datepicker" class="form-control" name="date" placeholder="Leave empty to remove all urls" autocomplete="off">
          </div>
          <?php echo Main::csrf_token(true) ?>
          <button type = "submit" class="btn btn-danger">Remove URLs</button>
        </form>
      </div>
    </div>    
  </div>
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        Export URLs
      </div>
      <div class="panel-body">
        <p>
          This tool allows you to generate a list of urls in CSV format. Some basic data such clicks will be included as well.
        </p>
        <a href="<?php echo Main::ahref("urls/export") ?>" class="btn btn-primary">Export URLs</a>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        Export Users
      </div>
      <div class="panel-body">
        <p>
          This tool allows you to generate a list of users in CSV format. You can then import that in the email marketing tools.
        </p>
        <a href="<?php echo Main::ahref("users/export") ?>" class="btn btn-primary">Export Users</a> 
      </div>
    </div>      
    <div class="panel panel-default">
      <div class="panel-heading">
        Optimize Database
      </div>
      <div class="panel-body">
        <p>
          This tool optimizes the database for it to perform better. This only run an OPTIMIZE query to clear the overhead in the database.
        </p>
        <a href="<?php echo Main::ahref("tools/optimize") ?>" class="btn btn-primary">Run</a>
      </div>
    </div>
  </div>
</div>
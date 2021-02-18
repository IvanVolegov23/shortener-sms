<?php if(!defined("APP")) die()?>
<div class="row">
  <div class="col-sm-9">
    <div class="panel panel-default">
      <div class="panel-heading">
        Report Links (<?php echo $count ?>)
      </div>      
      <div class="panel-null">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Short URL</th>
                <th>Long Link</th>
                <th>Reason</th>
                <th>Email</th>
                <th>Status</th>
                <th>Options</th>
              </tr>
            </thead>
            <tbody>          
              <?php foreach ($reports as $report): ?>
                <?php 

                    $current = parse_url($report->url, PHP_URL_HOST);

                    $link = explode("?", $report->url);
                    $action = explode("/", $link[0]);

                    if("http://".$current == $this->config["url"] || "https://".$current == $this->config["url"]){
                      // Fetch URL and show 404 if doesn't exist
                      $url = $this->db->get("url","(BINARY alias=:id OR BINARY custom=:id) AND (domain LIKE :domain OR domain IS NULL OR  domain = '')",array("limit"=> 1),array(":id"=> end($action), ":domain" => "%$current"));

                    }else{
                      // Fetch URL and show 404 if doesn't exist
                      $url = $this->db->get("url","(BINARY alias=:id OR BINARY custom=:id) AND domain LIKE :domain",array("limit"=> 1),array(":id"=> end($action), ":domain" => "%$current"));  
                    }                
                ?>
                <tr data-id="<?php echo $report->id ?>">
                  <td><a href="<?php echo $report->url ?>" target="_blank"><?php echo $report->url ?></a></td>
                  <td><?php if ($url): ?>
                        <?php echo $url->url ?>
                      <?php else: ?>
                        URL not found.
                      <?php endif ?>                      
                  </td>                  
                  <td><?php echo ucfirst($report->type) ?></td>
                  <td><?php echo $report->email ?></td>
                  <td>
                    <?php if($report->status == "1"): ?>
                      <span class="label label-danger">URL Banned</span>
                    <?php elseif($report->status == "2"): ?>
                      <span class="label label-danger">Domain Banned</span>
                    <?php else: ?>
                      <span class="label label-success">Active</span>
                    <?php endif ?>
                  </td>         
                  <td>
                    <a href="<?php echo Main::ahref("reports/url/{$report->id}") ?>" class="btn btn-primary btn-xs delete" data-content="Disable this url to prevent shortening by any user.">Ban URL</a>    
                    <a href="<?php echo Main::ahref("reports/domain/{$report->id}") ?>" class="btn btn-primary btn-xs delete" data-content="Disable this domain to prevent shortening by any user.">Ban Domain</a>
                    <a href="<?php echo Main::ahref("reports/delete/{$report->id}").Main::nonce("delete_report-{$report->id}") ?>" class="btn btn-danger btn-xs delete" data-content="Deleting this report will allow users to shorten url and its domain name again.">Delete</a>                    
                  </td>
                </tr>      
              <?php endforeach ?>
            </tbody>
          </table> 
        </div>
      </div>
    </div>     
  </div>
  <div class="col-sm-3">
    <div class="panel panel-default">
      <div class="panel-heading">Banning Domains/Links</div>
      <div class="panel-body">
        <p>You can ban domains or links as soon as someone reports it. By banning the link, any other user who tries to shorten this link will be prevented.</p>        

        <p> If you ban the domain, any user who tries to shorten any link in that domain will not be allowed. Banned domains are added to the list in the Settings > <a href="<?php echo Main::ahref("settings#security") ?>">Security Settings</a>.</p>
      </div>
    </div>
  </div>
</div>
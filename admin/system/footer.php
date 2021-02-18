<?php if(!defined("APP")) die()?>
						<footer>
						  <a href="http://gempixel.com/docs/premium-url-shortener?utm_source=app-admin&amp;utm_medium=footer&amp;utm_campaign=documentation" target="_blank">Online Documentation</a> - Version <?php echo _VERSION ?> <a href="http://cdn.gempixel.com/updater/index.php?token=<?php echo md5('shortener'); ?>&amp;current=<?php echo _VERSION; ?>" target="_blank">(Check for update)</a>
						  <div class="pull-right">
						  	2013 - <?php echo date("Y") ?> &copy; <a href="http://gempixel.com">KBRmedia</a>. All Rights Reserved.
						  </div>
						</footer>
      		</div>
      	</div>
    	</div>    
    	<script>
    		var lang = <?php echo json_encode([        
    			"modal" => [
	          "title" => "Are you sure you want to proceed?",
	          "proceed" => "Proceed",
	          "cancel" => "Cancel",
	          "close" => "Close",
	          "content" => "Note that this action is permanent. Once you click proceed, you <strong>may not undo</strong> this. Click anywhere outside this modal or click <a href='#close' class='close-modal'>close</a> to close this."
	        ]
	      ]) ?>
    	</script>
    <?php Main::admin_enqueue(TRUE) ?>
    </body>
</html>
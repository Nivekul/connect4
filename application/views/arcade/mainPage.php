
<!DOCTYPE html>

<html>
	
	<head>

	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="<?= base_url() ?>/js/jquery.timers.js"></script>
	<script>
		$(function(){
			$('#availableUsers').everyTime(500,function(){
					$('#availableUsers').load('<?= site_url("arcade/getAvailableUsers")?>');

					$.getJSON('<?= site_url("arcade/getInvitation") ?>',function(data, text, jqXHR){
						if (data && data.invited) {
							var user=data.login;
							var time=data.time;
							if(confirm('Play with ' + user)) 
								$.getJSON('<?= site_url("arcade/acceptInvitation")?>',function(data, text, jqXHR){
									if (data && data.status == 'success')
										window.location.href = '<?= site_url("board/index") ?>'
								});
							else  
								$.post('<?= site_url("arcade/declineInvitation")?>');
						}
					});
				});
			});
	
	</script>
	</head> 
<body>
	<div class='Grid fade'>
	
<?php 
	if (isset($errmsg)) 
		echo "<p>$errmsg</p>";
?>
	<div class='title_small'>Available Users:</div>
	<div id="availableUsers" class="normal">
	</div>
	
	
</div>	
</body>

</html>


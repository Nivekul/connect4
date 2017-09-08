
<!DOCTYPE html>

<html>
	<head>
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="<?= base_url() ?>/js/jquery.timers.js"></script>
	<script>

		var otherUser = "<?= $otherUser->login ?>";
		var user = "<?= $user->login ?>";
		var status = "<?= $status ?>";
		var board = new board();
		var redPawn = '<?= base_url() ?>images/pawn_red.png';
		var bluePawn = '<?= base_url() ?>images/pawn_blue.png';
		var pawn = redPawn;
		var turn;

		board.init();

		if (board.myturn) {
			turn = 1;
		} else {
			pawn = bluePawn;
			turn = 2;
		}
		
		$(function(){

			$('#drop0').click(function() {board.drop(0); });
			$('#drop1').click(function() {board.drop(1); });
			$('#drop2').click(function() {board.drop(2); });
			$('#drop3').click(function() {board.drop(3); });
			$('#drop4').click(function() {board.drop(4); });
			$('#drop5').click(function() {board.drop(5); });
			$('#drop6').click(function() {board.drop(6); });

			$('body').everyTime(1000,function(){
					if (status == 'waiting') {
						$.getJSON('<?= site_url("arcade/checkInvitation") ?>',function(data, text, jqZHR){
								if (data && data.status=='rejected') {
									alert("Sorry, your invitation to play was declined!");
									window.location.href = '<?= site_url("arcade/index") ?>';
								}
								if (data && data.status=='accepted') {
									status = 'playing';
									$('#status').html('Playing ' + otherUser);
								}
								
						});
					}
					var url = "<?= site_url('board/getMsg') ?>";
					$.getJSON(url, function (data,text,jqXHR){
						if (data && data.status=='success') {
							var conversation = $('[name=conversation]').val();
							var msg = data.message;
							if (msg.length > 0)
								$('[name=conversation]').val(conversation + "\n" + otherUser + ": " + msg);
						}
					});

					if (board.myturn) {
						$('#drop0').addClass('clickable');
						$('#drop1').addClass('clickable');
						$('#drop2').addClass('clickable');
						$('#drop3').addClass('clickable');
						$('#drop4').addClass('clickable');
						$('#drop5').addClass('clickable');
						$('#drop6').addClass('clickable');

						board.update();
						console.log(board.myturn);
					} else {
						$('#drop0').removeClass('clickable');
						$('#drop1').removeClass('clickable');
						$('#drop2').removeClass('clickable');
						$('#drop3').removeClass('clickable');
						$('#drop4').removeClass('clickable');
						$('#drop5').removeClass('clickable');
						$('#drop6').removeClass('clickable');

						board.draw();
						board.check();
						console.log(board.myturn);
					}
			});

			$('form').submit(function(){
				var arguments = $(this).serialize();
				var url = "<?= site_url('board/postMsg') ?>";
				$.post(url,arguments, function (data,textStatus,jqXHR){
						var conversation = $('[name=conversation]').val();
						var msg = $('[name=msg]').val();
						$('[name=conversation]').val(conversation + "\n" + user + ": " + msg);
						});
				return false;
				});	



		});
			
		function board() {
			this.board = [];
			this.myturn = null;
			this.row = 6;
			this.column = 7;

			this.init = function() {
				for (var i = 0; i < this.column; i++) {
					this.board[i] = [];
					for (var j = 0; j < this.row; j++) {
						this.board[i][j] = 0;
					}
				}

				this.getTurn();
			}

			this.drop = function(index) {
				var i = 0;
				column = this.board[index];
				while (column[i]) {
					i++;
				}
				if (this.myturn && i < this.row) {
					this.board[index][i] = turn;
					this.addPawn(index, i, turn);
					this.check();
					this.changeTurn();
					this.update();
					console.log(this.myturn);
				}
			}

			this.update = function() {
				var url = "<?= site_url('board/updateState') ?>";
				var board = JSON.stringify(this.board);
				console.log(board);
				$.post(url, {state : board});
			}

			this.changeTurn = function() {
				var url = "<?= site_url('board/changeTurn') ?>";

				var myturn;

				$.ajax({
			    	url: url,
			    	async: false,
			    	dataType: 'json',
			    	success: function(data,text,jqXHR) {
			    		myturn = data.myturn;
			    	}
			    });
			    this.myturn = myturn;
			}

			this.getTurn = function() {
				var url = "<?= site_url('board/getTurn') ?>";

				var myturn;

				$.ajax({
			    	url: url,
			    	async: false,
			    	dataType: 'json',
			    	success: function(data,text,jqXHR) {
			    		myturn = data.myturn;
			    	}
			    });
			    this.myturn = myturn;
			    console.log(myturn);
			}

			this.get = function() {
				var url = "<?= site_url('board/getState') ?>";

				var board;

				$.ajax({
			    	url: url,
			    	async: false,
			    	dataType: 'json',
			    	success: function(data,text,jqXHR) {
			    		board = data.state;
			    	}
			    });

			    return JSON.parse(board);
			}

			this.draw = function() {
				var url = "<?= site_url('board/getState') ?>";

				var board = this.get();
				console.log(JSON.stringify(board));

				for (var i = 0; i < this.column; i++) {
					for (var j = 0; j < this.row; j++) {
						if (this.board[i][j] == 0 && board[i][j] != this.board[i][j]) {
							if (board[i][j] == 1) {
								$('#'+i+j).css('background-image', 'url("'+redPawn+'")');
							} else if (board[i][j] == 2) {
								$('#'+i+j).css('background-image', 'url("'+bluePawn+'")');
							}
							this.addPawn(i, j, board[i][j]);
							this.changeTurn();
						}
					}
				}

				this.board = board;
			}

			this.addPawn = function(i,j,color) {
				if (color == 1) {
					$('#'+i+j).css('background-image', 'url("'+redPawn+'")').addClass('drop');
				}
				else {
					$('#'+i+j).css('background-image', 'url("'+bluePawn+'")').addClass('drop');
				}
			}

			this.check = function() {
				var winner = 0;
				for (var i = 0; i < this.column; i++) {
					for (var j = 0; j < this.row; j++) {
						var count = 0;
						if (this.board[i][j]) count++;
						if (count == 42) winner = 3;
					}
				}

				for (var i = 0; i < 7; i++) {
					for (var j = 0; j < 3; j++) {
						if (this.board[i][j] &&
							this.board[i][j] == this.board[i][j+1] &&
							this.board[i][j] == this.board[i][j+2] &&
							this.board[i][j] == this.board[i][j+3]) {
							winner = this.board[i][j];
						}
					}
				}
				for (var i = 0; i < 4; i++) {
					for (var j = 0; j < 6; j++) {
						if (this.board[i][j] &&
							this.board[i][j] == this.board[i+1][j] &&
							this.board[i][j] == this.board[i+2][j] &&
							this.board[i][j] == this.board[i+3][j]) {
							winner = this.board[i][j];
						}
					}
				}
				for (var i = 0; i < 4; i++) {
					for (var j = 0; j < 3; j++) {
						if (this.board[i][j] &&
							this.board[i][j] == this.board[i+1][j+1] &&
							this.board[i][j] == this.board[i+2][j+2] &&
							this.board[i][j] == this.board[i+3][j+3]) {
							winner = this.board[i][j];
						}
					}
					for (var j = 3; j < 6; j++) {
						if (this.board[i][j] &&
							this.board[i][j] == this.board[i+1][j-1] &&
							this.board[i][j] == this.board[i+2][j-2] &&
							this.board[i][j] == this.board[i+3][j-3]) {
							winner = this.board[i][j];
						}
					}
				}
				if (winner) {
					if (winner == 1) {
						var url = "<?= site_url('board/updateStatus') ?>";
						$.post(url, {status : 2}, function (){
							if (turn == 1) setTimeout(function(){window.location.replace('<?= site_url("arcade/won") ?>')}, 2000);
							else setTimeout(function(){window.location.replace('<?= site_url("arcade/lost") ?>')}, 2000);
						});
					} else if (winner == 2) {
						var url = "<?= site_url('board/updateStatus') ?>";
						$.post(url, {status : 3}, function (){
							if (turn == 2) setTimeout(function(){window.location.replace('<?= site_url("arcade/won") ?>')}, 2000);
							else setTimeout(function(){window.location.replace('<?= site_url("arcade/lost") ?>')}, 2000);
						});
					} else {
						var url = "<?= site_url('board/updateStatus') ?>";
						$.post(url, {status : 4}, function (){
							setTimeout(function(){window.location.replace('<?= site_url("arcade/tie") ?>')}, 2000);
						});
					}
				}
			}
		}



	</script>
	</head> 
<body>
	<div class='Grid fade'>

		<div class='inGrid center sixty inline'>
			<div class='board inline border-left border-right border-bottom'>
				<div id='0' class='column inline'>
					<div id='drop0' class='slot inline'>
					</div>
					<div id='05' class='square inline'>
					</div>
					<div id='04' class='square inline'>
					</div>
					<div id='03' class='square inline'>
					</div>
					<div id='02' class='square inline'>
					</div>
					<div id='01' class='square inline'>
					</div>
					<div id='00' class='square inline'>
					</div>
				</div>
				<div id='1' class='column inline'>
					<div id='drop1' class='slot inline'>
					</div>
					<div id='15' class='square inline'>
					</div>
					<div id='14' class='square inline'>
					</div>
					<div id='13' class='square inline'>
					</div>
					<div id='12' class='square inline'>
					</div>
					<div id='11' class='square inline'>
					</div>
					<div id='10' class='square inline'>
					</div>
				</div>
				<div id='2' class='column inline'>
					<div id='drop2' class='slot inline'>
					</div>
					<div id='25' class='square inline'>
					</div>
					<div id='24' class='square inline'>
					</div>
					<div id='23' class='square inline'>
					</div>
					<div id='22' class='square inline'>
					</div>
					<div id='21' class='square inline'>
					</div>
					<div id='20' class='square inline'>
					</div>
				</div>
				<div id='3' class='column inline'>
					<div id='drop3' class='slot inline'>
					</div>
					<div id='35' class='square inline'>
					</div>
					<div id='34' class='square inline'>
					</div>
					<div id='33' class='square inline'>
					</div>
					<div id='32' class='square inline'>
					</div>
					<div id='31' class='square inline'>
					</div>
					<div id='30' class='square inline'>
					</div>
				</div>
				<div id='4' class='column inline'>
					<div id='drop4' class='slot inline'>
					</div>
					<div id='45' class='square inline'>
					</div>
					<div id='44' class='square inline'>
					</div>
					<div id='43' class='square inline'>
					</div>
					<div id='42' class='square inline'>
					</div>
					<div id='41' class='square inline'>
					</div>
					<div id='40' class='square inline'>
					</div>
				</div>
				<div id='5' class='column inline'>
					<div id='drop5' class='slot inline'>
					</div>
					<div id='55' class='square inline'>
					</div>
					<div id='54' class='square inline'>
					</div>
					<div id='53' class='square inline'>
					</div>
					<div id='52' class='square inline'>
					</div>
					<div id='51' class='square inline'>
					</div>
					<div id='50' class='square inline'>
					</div>
				</div>
				<div id='6' class='column inline'>
					<div id='drop6' class='slot inline'>
					</div>
					<div id='65' class='square inline'>
					</div>
					<div id='64' class='square inline'>
					</div>
					<div id='63' class='square inline'>
					</div>
					<div id='62' class='square inline'>
					</div>
					<div id='61' class='square inline'>
					</div>
					<div id='60' class='square inline'>
					</div>
				</div>
			</div>
		</div>

		<div class='forty inline top'>
			<div id='status' class="title"> 
			<?php 
				if ($status == "playing")
					echo "Playing with " . $otherUser->login;
				else
					echo "Waiting on " . $otherUser->login;
			?>
			</div>
<?php
			echo form_textarea('conversation', set_value('conversation'), 'style="width:100%" readonly');
			echo form_open();
			echo form_input('msg',set_value(''),'placeholder="Message" style="width:100%"');
			echo '<br>';
			echo form_submit('Send','Send');
			echo form_close();

			if (isset($errmsg)) 
				echo "<p>$errmsg</p>";
?>
		</div>
	</div>
</body>

</html>


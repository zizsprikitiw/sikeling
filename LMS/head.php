		<div class="span12">
			<div class="header">
				<div class="pull-left">
					<img class="stilogo" src="LMS/utamalapan.png">
				</div>
			</div>
			<div class="alert alert-info"><Strong>Heads Up!</strong>&nbsp;Welcome to Perpustakaan Pustekbang							
				<div class="pull-right">
					<i class="icon-calendar icon-large"></i>
					<?php
						$Today = date('y:m:d');
						$new = date('l, F d, Y', strtotime($Today));
						echo $new;
					?>
				</div>
			</div>	
		</div>
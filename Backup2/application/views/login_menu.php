	<!-- Navigation starts -->
      <nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation" > 
	  		 <ul class="nav navbar-nav"> 
			 	<!-- User Group -->
				  <li class="dropdown">
					<a href="#" class="dropdown-toggle" ><span class="label label-success"><i class="fa fa-group"></i></span>  <?php 
						if($this->session->userdata('struktural_id') != '0'){
							echo $this->session->userdata('struktural_name');
						}elseif($this->session->userdata('fungsional_id') != '0'){
							echo $this->session->userdata('fungsional_name');
						}elseif($this->session->userdata('posisi_id') != '0'){
							echo $this->session->userdata('posisi_name');
						}else{
							echo $this->session->userdata('group_name'); 
						} 																		
						?> | <?php echo $this->session->userdata('pusat_name'); ?></a>          
				</li>
					<!-- END: User Group -->
			 </ul>
			        												
			<ul class="nav navbar-nav pull-right">									
			<!-- Menu Profile -->			
			  <li class="dropdown pull-right">            
				<a data-toggle="dropdown" class="dropdown-toggle" href="#">
				  <span class="label label-primary"><i class="fa fa-user"></i></span> <?php echo $user->nama; ?> <b class="caret"></b>              
				</a>
				
				<!-- Dropdown menu -->
				<ul class="dropdown-menu">
				  <!--li><a href="#"><i class="fa fa-user"></i> Profile</a></li-->
				  <li><a href="#modalChangePassword" data-toggle="modal"><i class="fa fa-cogs" ></i> Ganti Password</a></li>
				  <li><a href="<?php echo site_url('login/logout'); ?>"><i class="fa fa-sign-out"></i> Logout</a></li>
				</ul>
			  </li>
			  <!-- END:Menu Profile -->
			  
			</ul>			
      </nav>	
	  
	  <!-- Modal BEGIN:CHANGE PASSWORD-->
		<div id="modalChangePassword" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
			<div class="modal-dialog">
				<div class="modal-content">
					<!-- Form starts.  -->
					<form class="form-horizontal" role="form" id="form_change_password" name="form_change_password">
					  <div class="modal-header">			                        
						<h4 class="modal-title">Ganti Password</h4>
					  </div>
					  <div class="modal-body">
							<!-- Password -->
							<div class="form-group">
								<label class="control-label col-lg-4" for="old_password">Password Lama</label>
								<div class="col-lg-6">
									<input type="password" class="form-control" id="old_password" name="old_password" placeholder="Password Lama" value="">
								</div>								  
							</div>
							
							<div class="form-group">
								<label class="control-label col-lg-4" for="new_password">Password Baru</label>
								<div class="col-lg-6">
									<input type="password" class="form-control" id="new_password" name="new_password" placeholder="Password Baru" value="">
								</div>								  
							</div>
							
							<div class="form-group">
								<label class="control-label col-lg-4" for="new_password_confirm">Ulangi Password Baru</label>
								<div class="col-lg-6">
									<input type="password" class="form-control" id="new_password_confirm" name="new_password_confirm" placeholder="Password Baru" value="">
								</div>								  
							</div>
							
							<div id="msgChangePassword"></div>
															
							<div hidden="true">
								<input type="hidden" class="form-control" id="user_id_password" name="user_id_password" value="<?php echo '{'.$this->config->item('min_password_length', 'ion_auth').'}'; ?>" >								
							</div>   
							
					  </div>	<!--END modal-body-->
					  <div class="modal-footer">																	
						<input type="Submit" class="btn btn-sm btn-primary" value="Simpan" id="btnChangePassword" name="btnChangePassword">		
						<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
					  </div>
				  </form>
				</div>	<!--END modal-content-->
			</div>	<!--END modal-dialog-->
		</div>
		<!-- Modal END:CHANGE PASSWORD-->
		
		 <!--script src="jscripts/jquery-2.1.3.js"></script-->
		<script type="text/javascript">
			$('#btnChangePassword').click(function(){												
				var form_data = {					
					old_password: $('#old_password').val(),
					new_password: $('#new_password').val(),
					new_password_confirm: $('#new_password_confirm').val()								
				};
				   			   
				   $.ajax({
				      	type: "POST",
				       	url: "<?=site_url('login/change_password')?>",
				       	data: form_data,
						success: function(msg){
							$('#msgChangePassword').html('<div class="alert alert-info">' + msg + '</div>');
						}
				   });
				   				  
				 return false;
		     });
			 
		</script>
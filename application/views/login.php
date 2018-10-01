<?PHP $this->load->view('header'); ?>	
<!-- Begin CONTENT ===============================================-->
	
		<!-- Form area -->
		<div class="admin-form">
			<div class="container">
			
				<div class="row">
					<div class="col-md-12">
					<!-- Widget starts -->
						<div class="widget worange">
						<!-- Widget head -->
							<div class="widget-head">
								<i class="fa fa-lock"></i> Login 
						  	</div>
			
						  	<div class="widget-content">
								<div class="padd">
							  <!-- Login form -->
							  <form class="form-horizontal" id="loginform" name="loginform" action="login" method="post" accept-charset="utf-8">
								<!-- Username -->
								<div class="form-group">
									<label class="control-label col-lg-3" for="identity"><?php echo lang('login_identity_label');?></label>
								  	<div class="col-lg-9">								  	
										<input type="text" class="form-control" id="identity" name="identity" value="<?php $this->form_validation->set_value('identity'); ?>" placeholder="Username">								  
								  	</div>
								</div>
								<!-- Password -->
								<div class="form-group">
									<label class="control-label col-lg-3" for="inputPassword">Password</label>
								  	<div class="col-lg-9">
										<input type="password" class="form-control" id="password" name="password" placeholder="Password">
								  	</div>								  
								</div>
								<!-- Remember me checkbox and sign in button -->
								<div class="form-group">
								<div class="col-lg-9 col-lg-offset-3">
								  <!--div class="checkbox">
										<label>
										<php echo lang('login_remember_label', 'remember');?>
										<php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?>									
										</label>
									</div-->
								</div>
								</div>
									<div class="col-lg-9 col-lg-offset-3">										
										<button type="submit" class="btn btn-info btn-sm"><?php echo lang('login_submit_btn');?></button>
										<button type="reset" class="btn btn-default btn-sm">Reset</button>
									</div>
								<br />
							  </form>
							  
							  <div id="infoMessage"><?php echo $message;?></div>
							  
							</div>
							</div>
						  
							<div class="widget-foot">
								<!--p><a href="forgot_password"><php echo lang('login_forgot_password');?></a></p-->							 																	                             </div>
						</div>  
				  </div>
				</div>
			  </div> 
			</div>					      
		
    </div>

  
<!-- Content ends -->	
<!-- End CONTENT ===============================================-->
<?PHP $this->load->view('footer'); ?>	
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <!-- Title and other stuffs -->
  <title><?php echo $user_menu['page_title'].' - '.$this->config->item('site_name'); ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="author" content="">

    	
  <!-- Stylesheets -->
  <link href="<?=base_url()?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">  
  
  <!-- Font awesome icon -->
  <link rel="stylesheet" href="<?=base_url()?>assets/bootstrap/css/font-awesome.min.css"> 
   
  <!-- jQuery UI -->
  <link rel="stylesheet" href="<?=base_url()?>assets/bootstrap/css/jquery-ui.css"> 
  <!-- Calendar -->
  <link rel="stylesheet" href="<?=base_url()?>assets/bootstrap/css/fullcalendar.css">
  <!-- prettyPhoto -->
  <link rel="stylesheet" href="<?=base_url()?>assets/bootstrap/css/prettyPhoto.css">  
  <!-- Star rating -->
  <link rel="stylesheet" href="<?=base_url()?>assets/bootstrap/css/rateit.css">
  <!-- Date picker -->
  <link rel="stylesheet" href="<?=base_url()?>assets/bootstrap/css/bootstrap-datetimepicker.min.css">
  <!-- CLEditor -->
  <link rel="stylesheet" href="<?=base_url()?>assets/bootstrap/css/jquery.cleditor.css"> 
  <!-- Data tables -->
  <link rel="stylesheet" href="<?=base_url()?>assets/bootstrap/css/jquery.dataTables.css">  
  <!-- Bootstrap toggle -->
  <link rel="stylesheet" href="<?=base_url()?>assets/bootstrap/css/jquery.onoff.css">
  <!-- Main stylesheet -->
  <link href="<?=base_url()?>assets/bootstrap/css/style.css" rel="stylesheet">
  <!-- Widgets stylesheet -->
  <link href="<?=base_url()?>assets/bootstrap/css/widgets.css" rel="stylesheet">     
  <!-- Bootstrap PDF Viewer -->
 
   
		 	
	<script src="<?php echo base_url('assets/jquery/jquery-2.1.4.min.js')?>"></script>  <!-- jQuery -->
	<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script><!-- Bootstrap --> 
	<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script><!-- Data tables -->
  	<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js')?>"></script>	  
  	<script src="<?php echo base_url('assets/bootstrap/js/respond.min.js')?>"></script>
	<script src="<?php echo base_url('assets/bootstrap/js/dataTables.rowsGroup.js')?>"></script>					 

	
	
	 <link href="<?=base_url()?>assets/bootstrap/css/bootstrap_form_override.css" rel="stylesheet">
     <link rel="stylesheet" href="<?=base_url()?>assets/print/buttons.dataTables.min.css">  
  <!--[if lt IE 9]>
  <script src="<?=base_url()?>assets/bootstrap/js/html5shiv.js"></script>
  <![endif]-->
	

  <!-- Favicon -->
  <link rel="shortcut icon" href="<?=base_url()?>assets/bootstrap/img/favicon/favicon.png">
</head>

<body>

<div class="navbar navbar-fixed-top bs-docs-nav" role="banner" >
     <?php
	 	if ($this->ion_auth->logged_in())
		{
			$this->load->view('login_menu');
		}		
	?>	
            	
    </div>
  </div>



<!-- Header starts -->
  <header>
    <div class="container" >
      <div class="row" >
	
        <!-- Logo section style="margin-top:-14px;" height="118px"-->
        <div class="col-md-4"  height="118px">
          <!-- Logo. -->		  
		  <table id="Table_01" width="1000" height="118" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<img src="<?=base_url()?>assets/bootstrap/img/logo_1_01.png" width="143" height="118" alt=""></td>
					<td>
						<img src="<?=base_url()?>assets/bootstrap/img/logo_1_02.png" width="350" height="118" alt=""></td>
					<td>
						<img src="<?=base_url()?>assets/bootstrap/img/logo_1_03.png" width="202" height="118" alt=""></td>
					<td>
						<img src="<?=base_url()?>assets/bootstrap/img/logo_1_04.png" width="305" height="118" alt=""></td>
				</tr>
			</table>
          <!-- Logo ends -->
        </div>        				    
		
      </div>
    </div>
  </header>

<!-- Header ends -->

<!-- Main content starts -->

<div class="content" >
	<?php
		if ($this->ion_auth->logged_in())
		{
			$this->load->view('sidebar_menu');
		}				
	?>	
	
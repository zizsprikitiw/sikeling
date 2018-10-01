<?PHP $this->load->view('header'); ?>	
<!-- Begin CONTENT ===============================================-->
	<!-- Main bar -->
  	<div class="mainbar" >      
	    <!-- Page heading -->
	    <div class="page-head" >
	       <h3 class="pull-left" ><i class="title_page_icon <?php echo $user_menu['page_icon'] ?>"></i><?php echo $user_menu['page_title'] ?></h3>
        <!-- Breadcrumb -->              	
			<div class="clearfix"></div>
	    </div>
	    <!-- Page heading ends -->
  
		<!-- Matter -->
	    <div class="matter" >
			<div class="container" >								
				<!-- Message -->				
				<?php
					if ($message != '')
					{
						echo '<div id="infoMessage" class="alert alert-info">'.$message.'</div>';
					}								
				?>
				<!-- End Message -->																								 
				<input type="hidden" value="<?php echo $proyek_id; ?>" name="proyek_id" id="proyek_id"/>
				<input type="hidden" value="<?php echo $proyek_tahun; ?>" name="proyek_tahun" id="proyek_tahun"/>
				<!-- project list -->
				<div class="row">
					<div class="col-md-12" >
						<div class="well" id="proyek_title">
							<?php echo $proyek_detail; ?>
						</div>																																										
					</div> <!-- col -->
				</div> <!-- row -->	
				
				<div class="row">
					<div class="col-md-12" >
						<?PHP $this->load->view($button_url); ?>																																											
					</div> <!-- col -->
				</div> <!-- row -->	
																			  												
			<div class="clearfix">&nbsp;<br /><br /><br /><br /><br /><br /></div>		  												
			</div><!-- Containers ends -->
		 </div><!-- Matter ends -->
			  		  
		<script type="text/javascript">
			var save_method; //for save method string
			var table;			
			
			//function create lecect box
			function select_box(data,item_select,item_sel)
			{				
				//insert select item				
				var len_item = item_sel.length;
				select_val = -1;
				for(var i=0; i<len_item; i++){
					//get id selected
					for(var key in item_select){
						if(key == item_sel[i]){
							select_val = item_select[key];
						} 
					}
					
					var sel = $("#"+item_sel[i]);						
					sel.empty();					
					var len_sub = data[item_sel[i]].length;
					htmlString = "";					
					for(var j=0; j<len_sub; j++){
						if((select_val == -1) & (j==0)){
							selected_str = "selected='selected'";
						}else if(data[item_sel[i]][j].id_item == select_val){
							selected_str = "selected='selected'";
						}else{
							selected_str = "";
						}
						
						htmlString = htmlString+ "<Option value="+data[item_sel[i]][j].id_item+" "+selected_str+">"+data[item_sel[i]][j].nama_item+"</option>"							
					}
					sel.html(htmlString);	
				}	
			}
									
			$(document).ready(function() {		
				//Ajax Load data tahun dan pusat
				//load_select_posisi();
				 								
				
				//filter tahun on change	
				//$('#posisi').on('change',function(){					
					//reload_table();
				//});	
				 										
				
				
		  	});//end document																
															
												
			
	  </script>		
	  
														
    </div><!-- Mainbar ends -->   
   <div class="clearfix"></div>

</div>
<!-- Content ends -->	
<!-- End CONTENT ===============================================-->
<?PHP $this->load->view('footer'); ?>	
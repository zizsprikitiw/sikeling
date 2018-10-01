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
				<div id="page_message"></div>
				<?php
					if ($message != '')
					{
						echo '<div id="infoMessage" class="alert alert-info">'.$message.'</div>';
					}								
				?>
				<!-- End Message -->																								 
				
				<!-- project list -->
				<div class="row">
					<div class="col-md-12" >
					
						<div class="widget">
							<!-- Widget title -->
							<div class="widget-head">
								<div class="pull-left"><?php echo $user_menu['page_title'].' Tahun '.$tahun_proyek; ?></div>
								<div class="widget-icons pull-right" id="row_select_tahun" style="display:none;">
									<select name="filter_tahun" id="filter_tahun" class="form-control" >
											<option value="" >-- Pilih --</option>
										</select> 
								</div>  
								<div class="clearfix"></div>
							</div>							
							<div class="widget-content referrer" style="padding-top:0px; padding-bottom:10px; padding-left:5px; padding-right:5px;">
								<!-- Widget content -->
							  												  
							 		<!-- Table Page -->									
									<table class="table" cellpadding="0" cellspacing="0" border="0" id="table_proyek" width="100%">
										<thead style="visibility:hidden; border:none;">
											<tr>													  
											  <th >Judul Proyek</th>													  
											</tr>
										</thead>													
									</table>						
									<div class="clearfix"><input type="hidden" value="<?php echo $tahun_proyek; ?>" name="tahun_proyek" id="tahun_proyek"/> </div>									
									<!-- Table Page -->
												
								 <!-- END Widget content -->
							</div>
										
						</div> <!-- END Widget -->
																														
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
					htmlString = "";//<option value='-- Pilih --' >-- Pilih --</option>						
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
			
			//function load select filter
			function load_select_filter(){
				 $.ajax({
						url : "<?php echo site_url('proyek/data_init/')?>" ,
						type: "POST",
						dataType: "JSON",
						success: function(data)
						{
							var tahun = document.getElementById('tahun_proyek').value;
							
							if((tahun == '') | (tahun == 'all')){
								tahun = -1;
								document.getElementById('row_select_tahun').style.display = "block";
								//document.getElementById('row_select_tahun').style.visibility = "visible";								
							}else{
								document.getElementById('row_select_tahun').style.display = "none";
								//document.getElementById('row_select_tahun').style.visibility = "hidden";
							}
							
							row_select_tahun
							var item_sel=["filter_tahun"];
							var item_select = {"filter_tahun":tahun};															
							select_box(data,item_select, item_sel);			
							reload_table();																	
						},
						error: function (jqXHR, textStatus, errorThrown)
						{
							alert('Error get data from ajax');
						}
					});	
			}
			
			$(document).ready(function() {		
				//Ajax Load data tahun dan pusat
				load_select_filter();
				 								
				
				//filter tahun on change	
				$('#filter_tahun').on('change',function(){					
					reload_table();
				});	
				 										
				//load data table														
				table_proyek = $('#table_proyek').DataTable({ 			
					"processing": true, //Feature control the processing indicator.
					"serverSide": true, //Feature control DataTables' server-side processing mode.
					"deferLoading": 0, // here				
					"paging": false,
					"ordering": false,
					"bFilter": false,
					"bInfo": false,
					// Load data for the table's content from an Ajax source
					"ajax": {
						"url": "<?php echo site_url('proyek/data_list/')?>",
						"type": "POST",						
						"data": function ( d ) {
								var item_selectbox = document.getElementById('filter_tahun');
								d.filter_tahun = item_selectbox.options[item_selectbox.selectedIndex].value;								
							}					
					},
					
					//Set column definition initialisation properties.
					"columnDefs": [
						{ 
						  "targets": [ -1 ], //last column
						  "searchable": false,
						  "orderable": false, //set not orderable
						},
					],
					
		
				});//end load data table
				
		  	});//end document																
															
			function reload_table()
			{				
			  table_proyek.ajax.reload(null,false); //reload datatable ajax 
			}											
			
			
	  </script>		
	  
														
    </div><!-- Mainbar ends -->   
   <div class="clearfix"></div>

</div>
<!-- Content ends -->	
<!-- End CONTENT ===============================================-->
<?PHP $this->load->view('footer'); ?>	
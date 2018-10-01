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
				
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label col-lg-2">Tahun:</label>
							<div class="col-lg-5">                               
								<select name="filter_tahun" id="filter_tahun" class="form-control">
									<option value="" >-- Pilih --</option>
								</select> 
							</div>
					  </div>										  												
					</div>
					
					<div class="col-md-2" align="right">
						<!-- Button to trigger modal -->
						<button class="btn btn-success" onClick="data_add()"><i class="fa fa-plus"></i> Input Logbook</button>	
					</div>									
				</div>
				<hr style="border-top: 1px solid #ccc;border-bottom: 1px solid #fff;" />
				
				<!-- Table Page -->
				<div class="page-tables">
					<!-- Table -->
					<div class="table-responsive">
						<table class="table-hover table-bordered " cellpadding="0" cellspacing="0" border="0" id="table" width="100%">
							<thead style="background-color:#006699; color:#FFFFFF;">
								<tr>
								  <th width="70px" style="max-width:70px"><b>No</b></th>
								  <th ><b>Nama File</b></th>
								  <th width="150px" style="max-width:150px"><b>Tgl Laporan</b></th>
								  <th width="180px" style="max-width:180px"><b>Tgl Kirim</b></th>								  
								  <th width="60px" style="max-width:60px"></th>								  
								</tr>
							</thead>													
						</table>						
						<div class="clearfix"></div>									
					</div>
				</div>
				<!-- Table Page -->								
								  												
			<div class="clearfix">&nbsp;<br /><br /><br /><br /><br /><br /></div>		  												
			</div><!-- Containers ends -->
		 </div><!-- Matter ends -->
			  		  
		<script type="text/javascript">		
			var pdf_file;							 
				  
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
						url : "<?php echo site_url('laporan_logbook/data_init/')?>" ,
						type: "POST",
						dataType: "JSON",
						success: function(data)
						{
							var item_sel=["filter_tahun"];
							var item_select = {"filter_tahun":-1};															
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
				
				$("#modalLaporan").bind("hidden.bs.modal", function(e){
					var form_data = {
						pdf_file: pdf_file					
					};
					
					$.ajax({
							url : "<?php echo site_url('laporan_logbook/show_laporan_delete')?>",
							type: "POST",
							dataType: "JSON",
							data: form_data,
							success: function(data)
							{								    								
							},
							error: function (jqXHR, textStatus, errorThrown)
							{						
								alert('Error adding / update data');									
							}
						});	
				});
					
				//load data table																
			 	table = $('#table').DataTable({ 			
					"processing": true, //Feature control the processing indicator.
					"serverSide": true, //Feature control DataTables' server-side processing mode.
					"deferLoading": 0, // here	
					
					// Load data for the table's content from an Ajax source
					"ajax": {
						"url": "<?php echo site_url('laporan_logbook/data_list')?>",
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
				
								
		  	});																
		
			function reload_table()
			{
			  table.ajax.reload(null,false); //reload datatable ajax 
			}									
			
			function data_add()
			{			  				  
			  save_method = 'add';
			  $('#add_form')[0].reset(); // reset form on modals		  
		      $('#modal_message').html('');  //reset message
			  document.getElementById('div_upload_status').style.display = "none";	
			  			  
			  $('#modalAddForm').modal('show'); // show bootstrap modal
			  $('.modal-title').text('Tambah Data'); // Set Title to Bootstrap modal title						
			}						
			
			function data_save_validation()
			{					 								  
			  var form = document.getElementById('add_form');					  
			  var form_data = new FormData(form);					  
			  form_data.append("tgl_dokumen", document.getElementById('tgl_dokumen').value);			 				  
			  var fileInput = document.getElementById('file_laporan');
			  var file = fileInput.files[0];					
			  form_data.append("file_laporan", file);						  				  					  				  					  
						
			   // ajax adding data to database
				  $.ajax({
					url : "<?php echo site_url('laporan_logbook/data_save_validation/')?>",
					type: "POST",
					data: form_data,
					processData: false,
					contentType: false,
					dataType: "JSON",
					success: function(data)
					{
					   //if success close modal and reload ajax table
					   if(data['status'] == true){
							//Validation OK, next upload file								
							data_save_logbook(data['new_file_name']);																																
					   }else{							
							$('#modal_message').html('<div class="alert alert-info">' + data['status'] + '</div>');							
					   }					   					  
					},
					error: function (jqXHR, textStatus, errorThrown)
					{						
						alert('Error adding / update data');									
					}
				});
			}
						
			function data_save_logbook(new_file_name)
			{					 								  
			  var form = document.getElementById('add_form');					  
			  var form_data = new FormData(form);					  
			  form_data.append("new_file_name", new_file_name);	
			  form_data.append("tgl_dokumen", document.getElementById('tgl_dokumen').value);			 				  
			  var fileInput = document.getElementById('file_laporan');
			  var file = fileInput.files[0];					
			  form_data.append("file_laporan", file);	
			  
			  document.getElementById('div_upload_status').style.display = "block";	
			  $('#new_filename').html(fileInput.value);
			  					  				  					  				  					  
						
			   // ajax adding data to database
				  $.ajax({
				  	xhr: function() {
						var xhr = new window.XMLHttpRequest();
					
						xhr.upload.addEventListener("progress", function(evt) {
						  if (evt.lengthComputable) {
								var percentComplete = evt.loaded / evt.total;
								percentComplete = parseInt(percentComplete * 100);
								$('#status_upload').html("Status upload: "+percentComplete+"%");	
								$('#status_progressbar').html(percentComplete+"% Complete");	
								 document.getElementById('div_progressbar').style.width = percentComplete+"%";							
								//console.log(percentComplete);
						
								if (percentComplete === 100) {
									//console.log(percentComplete);
								}					
						  }
						}, false);
					
						return xhr;
					  },
					url : "<?php echo site_url('laporan_logbook/data_save_logbook/')?>",
					type: "POST",
					data: form_data,
					processData: false,
					contentType: false,
					dataType: "JSON",
					success: function(data)
					{
					   //if success close modal and reload ajax table
					   if(data['status'] == true){		
					   		setTimeout(function(){
							},2000);																																						
							 $('#modalAddForm').modal('hide');					   		
							 $('#modal_message').html('');
							 $('#page_message').html('<div class="alert alert-info">Data berhasil di simpan.</div>');
							load_select_filter();
							reload_table();
					   }else{							
							$('#modal_message').html('<div class="alert alert-info">' + data['status'] + '</div>');							
					   }					   					  
					},
					error: function (jqXHR, textStatus, errorThrown)
					{						
						alert('Error adding / update data');									
					}
				});
			}
			
			function show_laporan(logbook_id)
			{				 				
				var form_data = {
						logbook_id: logbook_id					
					};
					
				$.ajax({
						url : "<?php echo site_url('laporan_logbook/show_laporan')?>",
						type: "POST",
						dataType: "JSON",
						data: form_data,
						success: function(data)
						{						    														
							$('#judul_laporan').html('<b>' + data['logbook_title'] + '</b>');					    														
							pdf_file = 	data['logbook_filename_path'];																						
							document.getElementById('pdf_frame_laporan').src = data['logbook_filename_url'];																																																
							$('#modalLaporan').modal('show'); // show bootstrap modal when complete loaded										 						   					   				  
						},
						error: function (jqXHR, textStatus, errorThrown)
						{						
							alert('Error adding / update data');									
						}
					});									
			}
	  </script>
	  
	   <!-- Modal BEGIN:ADD DATA-->
		<div id="modalAddForm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<!-- Form starts.  -->
					<form class="form-horizontal" role="form" id="add_form" action="#" autocomplete="nope" enctype="multipart/form-data">
					  <div class="modal-header">			                        
						<h4 class="modal-title">Tambah</h4>
					  </div>
					  <div class="modal-body">									  		 											
							<input type="hidden" value="" name="id"/> 

							<div class="form-group">
							  <label class="col-lg-3 control-label" for="tgl_dokumen">Tanggal Laporan</label>
							  <div class="col-lg-7">
									<div id="datetimepicker1" class="input-append input-group dtpicker">
										<input data-format="dd-MM-yyyy" type="text" class="form-control" readonly="true" id="tgl_dokumen" name="tgl_dokumen">
										<span class="input-group-addon add-on">
											<i data-time-icon="fa fa-times" data-date-icon="fa fa-calendar"></i>
										</span>
									</div>
							  </div>
							</div>
							
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="file_laporan">File Laporan</label>
							  <div class="col-lg-9">
									<span class="btn btn-default btn-file">
										<input type="file"  id="file_laporan" name="file_laporan" placeholder=""  style="width:400px">
									</span>									
							  </div>							  
							</div>	
							
							<div class="form-group" id="div_upload_status" style="display:none">
							  <label class="col-lg-3 control-label" for="file_laporan"></label>
							  <div class="col-lg-9">
									<strong><i class="fa fa-upload"></i> <label id="new_filename"></label></strong>
									  <div class="file-meta" id="status_upload">Status upload: %</div>													 									
									  <!-- Progress bar -->
									  <div class="progress progress-striped active">
										  <div class="progress-bar progress-bar-info"  role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 0%" id="div_progressbar">
											<span class="sr-only" id="status_progressbar">% Complete</span>
										  </div>
										</div>									
							  </div>							  
							</div>								
							
							<div id="modal_message"></div>
					  </div>	<!--END modal-body-->
					  <div class="modal-footer">										
						<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>	
						<button type="button" id="btnSave" onClick="data_save_validation()" class="btn btn-sm btn-success">Simpan</button>								
					  </div>
				  </form>
				</div>	<!--END modal-content-->
			</div>	<!--END modal-dialog-->
		</div>
		<!-- Modal END:ADD DATA-->					 
		
		<!-- Modal BEGIN:BACA LAPORAN-->
		<div id="modalLaporan" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">					
					  <div class="modal-header">			                        
						<h4 class="modal-title">Laporan</h4>
					  </div>
					  <div class="modal-body">	
					  		<div class="row" >
								<div class="col-lg-12">
									<div id="judul_laporan" align="left"></div>
								</div>
							</div>

							<iframe id="pdf_frame_laporan" src="" width="100%" height="700px" frameborder="0"></iframe>														
					  </div>	<!--END modal-body-->
					  <div class="modal-footer">										
						<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>														
					  </div>
				  
				</div>	<!--END modal-content-->
			</div>	<!--END modal-dialog-->
		</div>
		<!-- Modal END:BACA LAPORAN-->
		
														
    </div><!-- Mainbar ends -->   
   <div class="clearfix"></div>

</div>

<!-- Content ends -->	
<!-- End CONTENT ===============================================-->
<?PHP $this->load->view('footer'); ?>	
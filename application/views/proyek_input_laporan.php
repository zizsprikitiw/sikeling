				<div id="page_message"></div>
				<div class="widget" id="div_input_laporan">
					<!-- Widget title -->
					<div class="widget-head" >
						<div class="pull-left">Input Laporan</div>
						<div class="widget-icons pull-right" style="display:none;">							 
						</div>  
						<div class="clearfix"></div>
					</div>							
					<div class="widget-content referrer" >
						<!-- Widget content -->
						<div id="modal_message"></div>
						<!-- Form starts.  -->
						<br />
						<form class="form-horizontal" role="form" id="add_form_note" action="#" autocomplete="nope" enctype="multipart/form-data">						  								<input type="hidden" value="" name="id" id="id"/>   	
								<input type="hidden" value="baru" name="status" id="status"/>   	 																			 
								<div class="form-group">
								  <label class="col-lg-2 control-label" for="posisi">Posisi</label>
								  <div class="col-lg-9">
										<select name="users_posisi_id" id="users_posisi_id" class="form-control" style="max-width:90%;">
											<option value="0" >-- Pilih --</option>
										</select>
								  </div>
								</div>	
								
								<div class="form-group" id="div_jenis_laporan">
								  <label class="col-lg-2 control-label" for="jenis_laporan">Jenis</label>
								  <div class="col-lg-3">
									<select name="jenis_laporan" id="jenis_laporan" class="form-control" style="max-width:90%;">											
											<option value="1" >Laporan</option>
											<option value="2" >Paper</option>
											<option value="3" >MOM</option>
											<option value="4" >Absensi</option>
										</select>
								  </div>							  
								</div>																
								
								<div class="form-group">
								  <label class="col-lg-2 control-label" for="judul">Judul</label>
								  <div class="col-lg-7">
									<input type="text" class="form-control" id="judul" name="judul" placeholder="Judul Laporan">
								  </div>							  
								</div>															
								
								<div class="form-group">
								  <label class="col-lg-2 control-label" for="resume">Abstrak</label>
								  <div class="col-lg-7 text-area" >
								  	 <textarea class="cleditor" name="resume" id="resume" ></textarea>									  
								  </div>							  
								</div>
								
								<div class="form-group">
								  <label class="col-lg-2 control-label" for="bulan_laporan">Bulan</label>
								  <div class="col-lg-3">
									   	<select name="bulan_laporan" id="bulan_laporan" class="form-control" style="max-width:90%;">
											<option value="0" >-- Pilih --</option>											
										</select>
								  </div>								  							  
								</div> 
								
								<div class="form-group">
								  <label class="col-lg-2 control-label" for="file_laporan">File Laporan</label>
								  <div class="col-lg-9" >								  													
										<input type="file"  id="file_laporan" name="file_laporan" placeholder=""  style="width:450px; vertical-align:middle">																		
								  </div>							  
								</div>			
								<!--Download status -->					
								<div class="form-group" id="div_upload_status" style="display:none">
								  <label class="col-lg-2 control-label" for="file_laporan"></label>
								  <div class="col-lg-9" style="width:450px">
										<!--strong><i class="fa fa-upload"></i> <label id="new_filename"></label></strong-->
										  <div class="file-meta" id="status_upload">Status upload: %</div>													 									
										  <!-- Progress bar -->
										  <div class="progress progress-striped active">
											  <div class="progress-bar progress-bar-info"  role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 0%" id="div_progressbar">
												<span class="sr-only" id="status_progressbar">% Complete</span>
											  </div>
											</div>									
								  </div>							  
								</div>
								
								<div class="form-group">
								  <label class="col-lg-2 control-label" ></label>
								  <div class="col-lg-3">
									   	<button type="button" id="btnSave" onClick="data_save_validation()" class="btn btn-sm btn-success" disabled="disabled">Simpan</button>
										<button type="button" id="btnAdd" onClick="data_add()" class="btn btn-sm btn-primary" >Input Baru</button>
								  </div>								  							  
								</div> 
								
								<div id="form_add_message"></div>
														 
							  <div class="modal-footer">
									<div class="pull-left" id="div_status_laporan">Status Laporan: Baru</div>									
							  </div>
					  </form>											  																	
						 <!-- END Widget content -->
					</div>								
				</div> <!-- END Widget -->								
				
				<!-- Table Page -->
				<div class="page-tables">
				<!-- Table -->
				<div class="table-responsive">
					<table class="table-hover table-bordered " cellpadding="0" cellspacing="0" border="0" id="table_note" width="100%">
						<thead style="background-color:#006699; color:#FFFFFF;">
							<tr>
							  <th width="70px" style="max-width:70px"><b>No</b></th>
							  <th style="min-width:200px"><b>Judul</b></th>
							  <th ><b>Nama File</b></th>
							  <th width="130px" style="max-width:130px"><b>Tgl Laporan</b></th>
							  <th width="175px" style="max-width:175px"><b>Tgl Kirim</b></th>
							  <th width="80px" style="max-width:80px" >Revisi</th>
							  <th width="60px" style="max-width:60px" >DL</th>								  
							</tr>
						</thead>													
					</table>						
					<div class="clearfix"></div>									
				</div>
			</div>
			<!-- Table Page -->	
				
				<script type="text/javascript">					
					var pdf_file;
					
					//function load select filter
					function load_select_posisi(){
						$('#add_form_note')[0].reset(); 
						var form_data = {
							proyek_id: document.getElementById('proyek_id').value	
						};
						
						 $.ajax({
								url : "<?php echo site_url('proyek/load_select_posisi/')?>" ,
								type: "POST",
								dataType: "JSON",
								data: form_data,
								success: function(data)
								{																																																					
									var item_sel=["users_posisi_id"];
									var item_select = {"users_posisi_id":-1};															
									select_box(data,item_select, item_sel);	
									
									var item_sel=["bulan_laporan"];
									var item_select = {"bulan_laporan":-1};															
									select_box(data,item_select, item_sel);	
									
									reload_table_note()										
								},
								error: function (jqXHR, textStatus, errorThrown)
								{
									alert('Error get data from ajax');
								}
							});	
					}					
										
					$(document).ready(function() {								
						//Ajax Load data tahun dan pusat
						load_select_posisi();
						
						$("#modalLaporan").bind("hidden.bs.modal", function(e){
							var form_data = {
								pdf_file: pdf_file					
							};
							
							$.ajax({
									url : "<?php echo site_url('proyek/show_laporan_delete')?>",
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
																		
						//filter tahun on change	
						$('#users_posisi_id').on('change',function(){																											
							if(document.getElementById('users_posisi_id').value == '0'){
								document.getElementById('btnSave').disabled = 1;																
							}else{
								document.getElementById('btnSave').disabled = 0;									
							}
							reload_table_note();														
						});	
																
						$('#jenis_laporan').on('change',function(){																												
							reload_table_note();								
						});	
							
						$('#bulan_laporan').on('change',function(){																												
							reload_table_note();								
						});											
																		
						//load data table																
						table_note = $('#table_note').DataTable({ 			
							"processing": true, //Feature control the processing indicator.
							"serverSide": true, //Feature control DataTables' server-side processing mode.
							"deferLoading": 0, // here	
							"ordering": false,
							"paging": true,							
							
							// Load data for the table's content from an Ajax source
							"ajax": {
								"url": "<?php echo site_url('proyek/data_list_note')?>",
								"type": "POST",						
								"data": function ( d ) {
										d.proyek_id = document.getElementById('proyek_id').value;	
										var item_selectbox = document.getElementById('users_posisi_id');
										d.users_posisi_id = item_selectbox.options[item_selectbox.selectedIndex].value;	
										var item_selectbox = document.getElementById('jenis_laporan');
										d.jenis_laporan = item_selectbox.options[item_selectbox.selectedIndex].value;								
										var item_selectbox = document.getElementById('bulan_laporan');
										d.bulan_laporan = item_selectbox.options[item_selectbox.selectedIndex].value;											
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
					
					function reload_table_note()
					{						
					  	table_note.ajax.reload(null,false); //reload datatable ajax 
					}
					
					function disable_input(opsi)
					{
						document.getElementById('users_posisi_id').disabled = opsi;	
						document.getElementById('jenis_laporan').disabled = opsi;		
						document.getElementById('bulan_laporan').disabled = opsi;							
					}
					
					function data_add()
					{
						$('#form_add_message').html('');
						$('#modal_message').html('');
						document.getElementById('div_upload_status').style.display = "none";
						$('[name="id"]').val("");
						$('[name="status"]').val("baru"); 
						$('[name="judul"]').val(""); 
						$("#resume").val(""); 
						$("#resume").cleditor()[0].updateFrame();
						$('[name="file_laporan"]').val(""); 
						$('#bulan_laporan').prop('selectedIndex',0);
						disable_input(0);								
						$('#div_status_laporan').html("Status Laporan: Baru");			
					}
										
					function data_revisi(note_id)
					{			
						$('#form_add_message').html('');
						$('#modal_message').html('');
						document.getElementById('div_upload_status').style.display = "none";
								 					 
						var form_data = {
							note_id: note_id				
						};		
					  //Ajax Load data from ajax
					  $.ajax({			  		
							url : "<?php echo site_url('proyek/data_revisi/')?>" ,
							type: "POST",
							dataType: "JSON",
							data: form_data,
							success: function(data)
							{
								$('[name="status"]').val("revisi");				   
								$('[name="id"]').val(data['note'].id);  
								$('[name="judul"]').val(data['note'].judul);
								$("#resume").val(data['note'].resume); 
								$("#resume").cleditor()[0].updateFrame();							
								var item_sel=["bulan_laporan"];
								var item_select = {"bulan_laporan":data['note'].bulan};															
								select_box(data,item_select, item_sel);	
								
								disable_input(1);									
								$('#div_status_laporan').html("Status Laporan: Revisi");
								window.location.href = "#div_input_laporan";
												
							},
							error: function (jqXHR, textStatus, errorThrown)
							{
								alert('Error get data from ajax');
							}
						});
					}
					
					function show_laporan(note_id)
					{				 				
						var form_data = {
								note_id: note_id					
							};
							
						$.ajax({
								url : "<?php echo site_url('proyek/show_laporan')?>",
								type: "POST",
								dataType: "JSON",
								data: form_data,
								success: function(data)
								{															    														
									$('#judul_laporan').html('<b>' + data['note_title'] + '</b>');					    														
									pdf_file = 	data['note_filename_path'];																						
									document.getElementById('pdf_frame_laporan').src = data['note_filename_url'];																																																
									$('#modalLaporan').modal('show'); // show bootstrap modal when complete loaded										 						   					   				  
								},
								error: function (jqXHR, textStatus, errorThrown)
								{						
									alert('Error adding / update data');									
								}
							});									
					}
					
					function data_save_validation()
					{
						$('#form_add_message').html('');
						$('#modal_message').html('');
						document.getElementById('div_upload_status').style.display = "none";
						  disable_input(0);					 								  
						  var form = document.getElementById('add_form_note');					  
						  var form_data = new FormData(form);								  
						  form_data.append("status", document.getElementById('status').value);
						  form_data.append("users_posisi_id", document.getElementById('users_posisi_id').value);						  
						  form_data.append("judul", document.getElementById('judul').value);						  
						  form_data.append("bulan_laporan", document.getElementById('bulan_laporan').value);						     						  		
						  var fileInput = document.getElementById('file_laporan');
						  var file = fileInput.files[0];					
						  form_data.append("file_laporan", file);						  				  					  				  					  
									
						   // ajax adding data to database
							  $.ajax({
								url : "<?php echo site_url('proyek/data_save_validation/')?>",
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
										data_save_note(data['new_file_name']);	
										
										if(document.getElementById('status').value == 'revisi'){
											disable_input(1);
										}																																										
								   }else{							
										$('#form_add_message').html('<div class="alert alert-info">' + data['status'] + '</div>');							
								   }					   					  
								},
								error: function (jqXHR, textStatus, errorThrown)
								{						
									alert('Error adding / update data');									
								}
							});
					}
					
					function data_save_note(new_file_name)
					{					 								  
						$('#form_add_message').html('');
						$('#modal_message').html('');
						document.getElementById('div_upload_status').style.display = "none";
						disable_input(0);					 								  
						var form = document.getElementById('add_form_note');					  
						var form_data = new FormData(form);		
						form_data.append("id", document.getElementById('id').value);	
						form_data.append("status", document.getElementById('status').value);
						form_data.append("users_posisi_id", document.getElementById('users_posisi_id').value);
						form_data.append("jenis_laporan", document.getElementById('jenis_laporan').value);
						form_data.append("judul", document.getElementById('judul').value);
						form_data.append("resume", document.getElementById('resume').value);
						form_data.append("bulan_laporan", document.getElementById('bulan_laporan').value);
						form_data.append("proyek_tahun", document.getElementById('proyek_tahun').value);							
						form_data.append("new_file_name", new_file_name);						     						  		
						var fileInput = document.getElementById('file_laporan');
						var file = fileInput.files[0];					
						form_data.append("file_laporan", file);						  
					  	document.getElementById('div_upload_status').style.display = "block";						  
						//$('#new_filename').html(fileInput.value);
																												  								
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
											document.getElementById('div_upload_status').style.display = "none";
											//console.log(percentComplete);
										}					
								  }
								}, false);
							
								return xhr;
							  },
							url : "<?php echo site_url('proyek/data_save_note/')?>",
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
									 $('#form_add_message').html('');
									 $('#modal_message').html('<div class="alert alert-info">Data berhasil di simpan.</div>');
									reload_table_note();	
							   }else{							
									$('#form_add_message').html('<div class="alert alert-info">' + data['status'] + '</div>');																								
							   }					   					  
							},
							error: function (jqXHR, textStatus, errorThrown)
							{						
								// alert('Error adding / update data'); agung
								alert('Data sudah tersimpan');									
							}
						});
					}																					
																																				
			  </script>	
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
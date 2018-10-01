				<div id="page_message"></div>
				<div class="widget" id="div_input_berita">
					<!-- Widget title -->
					<div class="widget-head" >
						<div class="pull-left">Input Berita Kegiatan</div>
						<div class="widget-icons pull-right" style="display:none;">							 
						</div>  
						<div class="clearfix"></div>
					</div>							
					<div class="widget-content referrer" >
						<!-- Widget content -->
						<div id="modal_message"></div>
						<!-- Form starts.  -->
						<br />
						<form class="form-horizontal" role="form" id="add_form_berita" action="#" autocomplete="nope" enctype="multipart/form-data">						  								<input type="hidden" value="" name="id" id="id"/>   	
								<input type="hidden" value="baru" name="status" id="status"/>   	 																			 								
								<div class="form-group">
								  <label class="col-lg-2 control-label" for="judul">Judul</label>
								  <div class="col-lg-7">
									<input type="text" class="form-control" id="judul" name="judul" placeholder="Judul Laporan">
								  </div>							  
								</div>															
								
								<div class="form-group">
								  <label class="col-lg-2 control-label" for="isi">Isi</label>
								  <div class="col-lg-7 text-area" >
									 <textarea class="cleditor" name="isi" id="isi"></textarea>									  
								  </div>							  
								</div>
								
								<div class="form-group">
								  <label class="col-lg-2 control-label" for="file_gambar">Gambar</label>
								  <div class="col-lg-9">
										<span class="btn btn-default btn-file">
											<input type="file"  id="file_gambar" name="file_gambar" placeholder=""  style="width:450px">
										</span>
										<label id="nama_file_gambar"></label> 							
								  </div>							  
								</div>
								
								<div class="form-group">
								  <label class="col-lg-2 control-label" for="file_berita">File</label>
								  <div class="col-lg-9">
										<span class="btn btn-default btn-file">
											<input type="file"  id="file_berita" name="file_berita" placeholder=""  style="width:450px">
										</span>
										<label id="nama_file_berita"></label>									
								  </div>							  
								</div>	
								<!--Download status -->	
								<div class="form-group" id="div_upload_status" style="display:none">
								  <label class="col-lg-2 control-label" ></label>
								  <div class="col-lg-9">
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
									   	<button type="button" id="btnSave" onClick="data_save_validation_berita()" class="btn btn-sm btn-success">Simpan</button>
										<button type="button" id="btnAdd" onClick="data_add_berita()" class="btn btn-sm btn-primary" >Input Baru</button>
								  </div>								  							  
								</div> 
								
								<div id="form_add_message"></div>
														 
							  <div class="modal-footer">
									<div class="pull-left" id="div_status_laporan">Status Berita: Baru</div>									
							  </div>
					  </form>											  																	
						 <!-- END Widget content -->
					</div>								
				</div> <!-- END Widget -->								
				
				<!-- Table Page -->
				<div class="page-tables">
				<!-- Table -->
				<div class="table-responsive">
					<table class="table-hover table-bordered " cellpadding="0" cellspacing="0" border="0" id="table" width="100%">
							<thead style="background-color:#006699; color:#FFFFFF;">
								<tr>
								  <th width="70px" style="max-width:70px"><b>No</b></th>
								  <th ><b>Judul Berita</b></th>
								  <th width="150px" style="max-width:150px"><b>Tgl Berita</b></th>
								  <th width="60px" style="max-width:60px"></th>								  
								</tr>
							</thead>													
						</table>						
					<div class="clearfix"></div>									
				</div>
			</div>
			<!-- Table Page -->	
				
				<script type="text/javascript">					
					var monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];				
					var delete_file_gambar;
					var delete_file_berita;
					var save_method;															
					
					$(document).ready(function() {									  																				
						save_method = 'add';
						
						//load data table																
						table = $('#table').DataTable({ 			
							"processing": true, //Feature control the processing indicator.
							"serverSide": true, //Feature control DataTables' server-side processing mode.							
							"ordering": false,
							
							// Load data for the table's content from an Ajax source
							"ajax": {
								"url": "<?php echo site_url('proyek/data_list_berita')?>",
								"type": "POST",						
								"data": function ( d ) {
										d.proyek_id = document.getElementById('proyek_id').value;								
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
				
					function reload_table_berita()
					{
					  table.ajax.reload(null,false); //reload datatable ajax 
					}									
					
					function data_add_berita()
					{			  				  
					  save_method = 'add';
					  $('#add_form_berita')[0].reset(); // reset form on modals		
					  $("#isi").val(""); 
					  $("#isi").cleditor()[0].updateFrame();  
					  $('#form_add_message').html('');  //reset message
					  $('#modal_message').html('');					  
					  $('#nama_file_gambar').html('');
					  $('#nama_file_berita').html('');
					  document.getElementById('div_upload_status').style.display = "none";						
					  $('#div_status_laporan').html("Status Laporan: Baru");
					}
					
					function data_edit_berita(id)
					{			  				  
					  save_method = 'update';
					  delete_file_gambar = 'false';
					  delete_file_berita = 'false';
					  $('#add_form_berita')[0].reset(); // reset form on modals		  
					  $("#isi").val(""); 
			  		  $("#isi").cleditor()[0].updateFrame();
					  $('#form_add_message').html('');  //reset message
					  $('#modal_message').html('');
					  $('#nama_file_gambar').html('');
					  $('#nama_file_berita').html('');
					  document.getElementById('div_upload_status').style.display = "none";	
								  
					  //Ajax Load data from ajax
					  $.ajax({			  		
							url : "<?php echo site_url('proyek/data_edit_berita/')?>/" + id,
							type: "GET",
							dataType: "JSON",
							success: function(data)
							{				   
								$('[name="id"]').val(data['list'].id);  
								$('[name="judul"]').val(data['list'].judul);
								$("#isi").val(data['list'].isi); 
					    		$("#isi").cleditor()[0].updateFrame();
								
								$('#div_status_laporan').html("Status Laporan: Revisi");
								window.location.href = "#div_input_berita";
								
								if(data['list'].filegambar != ""){
									$('#nama_file_gambar').html(data['list'].filegambar+' <button class="btn btn-xs btn-danger" onClick="file_delete_berita('+ "'gambar','true'" +')"><i class="fa fa-times"></i></button>');
								}
								
								if(data['list'].filename != ""){
									$('#nama_file_berita').html(data['list'].filename+' <button class="btn btn-xs btn-danger" onClick="file_delete_berita('+ "'berita','true'" +')"><i class="fa fa-times"></i></button>');
								}						
								
							},
							error: function (jqXHR, textStatus, errorThrown)
							{
								alert('Error get data from ajax');
							}
						});				
					}						
					
					function file_delete_berita(param, value)
					{
						if(param == "gambar"){
							if(value == "true"){
								delete_file_gambar = 'true';
								$('#nama_file_gambar').html('');
							}else{
								delete_file_gambar = 'false';
							}
						}
						
						if(param == "berita"){
							if(value == "true"){
								delete_file_berita = 'true';
								$('#nama_file_berita').html('');
							}else{
								delete_file_berita = 'false';
							}
						}				
					}
					
					function data_save_validation_berita()
					{					 								  
					  var form = document.getElementById('add_form_berita');					  
					  var form_data = new FormData(form);					  
					  var file_berita = document.getElementById('file_berita');
					  var file = file_berita.files[0];					
					  form_data.append("file_berita", file);			  
					  var file_gambar = document.getElementById('file_gambar');
					  var file = file_gambar.files[0];					
					  form_data.append("file_gambar", file);
					  form_data.append("proyek_id", document.getElementById('proyek_id').value);
					  																													  								
					   // ajax adding data to database
						  $.ajax({
							url : "<?php echo site_url('proyek/data_save_validation_berita/')?>",
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
									data_save_berita(data['new_file_berita'], data['new_file_gambar']);																																
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
								
					function data_save_berita(new_file_berita, new_file_gambar)
					{					 								  
					  var form = document.getElementById('add_form_berita');					  
					  var form_data = new FormData(form);					  
					  form_data.append("new_file_berita", new_file_berita);	
					  form_data.append("new_file_gambar", new_file_gambar);				  
					  form_data.append("delete_file_gambar", delete_file_gambar);	
					  form_data.append("delete_file_berita", delete_file_berita);
					  form_data.append("proyek_id", document.getElementById('proyek_id').value);	
		
					  var file_berita = document.getElementById('file_berita');
					  var file = file_berita.files[0];					
					  form_data.append("file_berita", file);			  
					  var file_gambar = document.getElementById('file_gambar');
					  var file = file_gambar.files[0];					
					  form_data.append("file_gambar", file);	
					  
					  if((new_file_berita != "") | (new_file_gambar != "")){
							document.getElementById('div_upload_status').style.display = "block";	
						  //$('#new_filename').html(file_berita.value+" , "+file_gambar.value);
					  }
					  
					  var url;
					  if(save_method == 'add') 
					  {
						  url = "<?php echo site_url('proyek/data_save_add_berita/')?>";
					  }
					  else
					  {
						url = "<?php echo site_url('proyek/data_save_edit_berita')?>";
					  }
																																				
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
							url : url,
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
									 data_add_berita();
									reload_table_berita();
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
					
					function detail_berita(id_berita)
					{										
					  //Ajax Load data from ajax
					  $.ajax({			  		
							url : "<?php echo site_url('proyek/detail_berita/')?>/" + id_berita,
							type: "GET",
							dataType: "JSON",
							success: function(data)
							{				   
								var mydate = new Date(data['list'].tanggal_submit);
								var myMonth = monthNames[mydate.getMonth()];												
								$('#news_judul').html(data['list'].judul);
								$('#news_tanggal').html(mydate.getDate()+' '+myMonth+' '+mydate.getFullYear());
								$('#news_user').html(data['list'].nama);
								
								if(data['list'].proyek_id == "0"){
									$('#news_status').html('Berita Umum');
								}else{
									$('#news_status').html('Berita Khusus');
								}
								
								$('#news_pic').html('');
								if(data['picture'] != ""){							
									$('#news_pic').html('<div class="bthumb"><img src="'+data['picture']+'" alt="" class="img-responsive" /></div>');
								}
														 
								$('#news_isi').html(data['list'].isi); 
								$('#news_file').html(data['download']); 
										
								$('#modalNewsForm').modal('show'); // show bootstrap modal when complete loaded
								$('.modal-title').text('Detail Berita'); // Set title to Bootstrap modal title					
							},
							error: function (jqXHR, textStatus, errorThrown)
							{
								alert('Error get data from ajax');
							}
						});
					}																															
			  </script>	
			 
			 <!-- Modal BEGIN:DETAIL BERITA-->
		<div id="modalNewsForm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<!-- Form starts.  -->					
					  <div class="modal-header">			                        
						<h4 class="modal-title">Detail Berita</h4>
					  </div>
					  <div class="modal-body">
					  		<div class="content blog">							  
									<div class="posts">
										
										<div class="entry">
											 <h2 id="news_judul"></h2>
											 
											 <!-- Meta details -->
											 <div class="meta">
												<i class="fa fa-calendar"></i> <label id="news_tanggal"></label> &nbsp;<i class="fa fa-user"></i> <label id="news_user"></label> &nbsp;<i class="fa fa-folder-open"></i> <label id="news_status"></label>
											 </div>
											 
											 <!-- Thumbnail -->
											 <div id="news_pic">
											 </div>											 
											 
											 <div id="news_isi">
											 </div>											 											 								
										  </div>
											
											<div id="news_file">
											 </div>		
									</div><!--END class="posts"-->								 
							</div><!--END class="content blog"-->					  					  									  		 																		
							
					  </div>	<!--END modal-body-->
					  <div class="modal-footer">										
						<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>							
					  </div>				  
				</div>	<!--END modal-content-->
			</div>	<!--END modal-dialog-->
		</div>
		<!-- Modal END:DETAIL BERITA-->
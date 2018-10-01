				<div id="page_message"></div>
				<div class="widget" id="div_input_laporan">
					<!-- Widget title -->
					<div class="widget-head" >
						<div class="pull-left">Rekap Laporan Kegiatan</div>
						<div class="widget-icons pull-right" style="display:none;">							 
						</div>  
						<div class="clearfix"></div>
					</div>							
					<div class="widget-content referrer" >
						<!-- Widget content -->												
						<br  />	
						<form class="form-horizontal" role="form" id="add_form_note" >				  								
							<div class="form-group">
							  <label class="col-lg-2 control-label" for="posisi">Posisi</label>
							  <div class="col-lg-9">
									<select name="users_posisi_id" id="users_posisi_id" class="form-control" style="max-width:90%;">
										<option value="0" >-- Pilih --</option>
									</select>
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
						</form>
					  <hr  />
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
									</tr>
								</thead>													
							</table>						
							<div class="clearfix"></div>									
						</div>
					</div>
					<!-- Table Page -->										  																	
						 <!-- END Widget content -->
					</div>								
				</div> <!-- END Widget -->								
				
				
				
				<script type="text/javascript">					
					var pdf_file;
					
					//function load select filter
					function load_select_posisi(){
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
								"url": "<?php echo site_url('proyek/data_list_note_rekap')?>",
								"type": "POST",						
								"data": function ( d ) {
										d.proyek_id = document.getElementById('proyek_id').value;	
										var item_selectbox = document.getElementById('users_posisi_id');
										d.users_posisi_id = item_selectbox.options[item_selectbox.selectedIndex].value;																		
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
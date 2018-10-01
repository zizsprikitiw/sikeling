				<div id="page_message"></div>
				<div class="widget">
					<!-- Widget title -->
					<div class="widget-head">
						<div class="pull-left">Personil Program</div>
						<div class="widget-icons pull-right" style="display:none;">							 
						</div>  
						<div class="clearfix"></div>
					</div>							
					<div class="widget-content referrer" >
						<!-- Widget content -->
						<div id="modal_message"></div>
						<!-- Form starts.  -->
						<br />
						
						<!-- Table Page -->
						<div class="page-tables">
							<!-- Table -->
							<div class="table-responsive">
								<table class="table-hover table-bordered" cellpadding="0" cellspacing="0" border="0" id="tablePersonil" width="100%">
									<thead style="background-color:#006699; color:#FFFFFF;">
										<tr>
										  <th width="60px" style="max-width:60px"><b>No</b></th>
										  <th ><b>Nama</b></th>
										  <th width="60%" ><b>Posisi</b></th>										  																				 
										</tr>
									</thead>													
								</table>						
																
							</div>
						</div>
						<!-- Table Page -->	
																			  																	
						 <!-- END Widget content -->
					</div>								
				</div> <!-- END Widget -->																
				
				<script type="text/javascript">																		
										
					$(document).ready(function() {																																									
						//load data table leader														
						tablePersonil = $('#tablePersonil').DataTable({ 			
							"processing": true, //Feature control the processing indicator.
							"serverSide": true, //Feature control DataTables' server-side processing mode.								
							"paging": false,							
							"ordering": false,					
							// Load data for the table's content from an Ajax source
							"ajax": {
								"url": "<?php echo site_url('proyek/data_list_personil/')?>",
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
						
					});//end document																																																											
																																				
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
		
									<div class="row" >
										<div class="col-lg-18" >
											<div id="viewer_read" class="pdf-viewer" data-url=""></div>									
										</div>
									</div>														
							  </div>	<!--END modal-body-->
							  <div class="modal-footer">										
								<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>														
							  </div>
						  
						</div>	<!--END modal-content-->
					</div>	<!--END modal-dialog-->
				</div>
				<!-- Modal END:BACA LAPORAN-->
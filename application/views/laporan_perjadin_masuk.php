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
					<div class="col-sm-12 form-horizontal" align="left">
												
							<div class="form-group" style="vertical-align:middle">
								<label class="col-sm-2 " style="max-width:150px; padding-left:40px; vertical-align:middle;"><b>
								   Tahun:</b>
								</label>								
								<div class="col-sm-3" align="left" style="text-align:left; padding-left:0px;">                               
									<select name="filter_tahun" id="filter_tahun" class="form-control" style="max-width:110px;" >
										<option value="0" >-- Pilih --</option>
									</select> 
								</div>
								
								<label class="col-sm-2 checkbox-inline" style="max-width:100px; padding-left:0px; vertical-align:middle;"><b>
								  <input type="checkbox" name="chkSearch[]" id="chkSearch[]" value="bulan"> Bulan:</b>
								</label>							
								<div class="col-sm-2" align="left" style="text-align:left; padding-left:0px; ">                               
									<select name="filter_bulan" id="filter_bulan" class="form-control" style="max-width:160px;">
										<option value="0" >-- Pilih --</option>
									</select> 
								</div>
								
								<div class="col-sm-1">
									<button type="button" class="btn btn-primary" id="btnSearch" onClick="data_search()"><i class="fa fa-search"></i> Cari</button>										
								</div>
						  	</div>										  						
																											
							<div class="form-group" style="vertical-align:middle">
								<label class="col-sm-2 checkbox-inline" style="max-width:150px; padding-left:40px; vertical-align:middle;"><b>
								  <input type="checkbox" name="chkSearch[]" id="chkSearch[]" value="nama"> Pengirim:</b>
								</label>
								<div class="col-sm-3" align="left" style="text-align:left;  padding-left:0px; ">                               
									<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Pengirim" style="max-width:200px">
								</div>																							
							</div>														
							
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
								  <th style="min-width:180px"><b>Pengirim</b></th>
								  <th ><b>Nama file</b></th>
								  <th ><b>Jenis</b></th>
								  <th style="max-width:130px"><b>Tgl Laporan</b></th>
								  <th style="max-width:175px"><b>Tgl Kirim</b></th>
								  <th style="max-width:175px;" align="center"><b>Tgl Diterima</b></th>								  								  
								  <th style="max-width:60px" align="center">Unduh</th>								  
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
					url : "<?php echo site_url('laporan_perjadin_masuk/data_init/')?>" ,
					type: "POST",
					dataType: "JSON",
					success: function(data)
					{
						var item_sel=["filter_tahun", "filter_bulan"];
						var item_select = {"filter_tahun":-1, "filter_bulan":-1};															
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
				
				$("#modalApproval").bind("hidden.bs.modal", function(e){
					var form_data = {
						pdf_file: pdf_file					
					};
					
					$.ajax({
							url : "<?php echo site_url('laporan_perjadin_masuk/show_laporan_delete')?>",
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
				
				$("#modalLaporan").bind("hidden.bs.modal", function(e){
					var form_data = {
						pdf_file: pdf_file					
					};
					
					$.ajax({
							url : "<?php echo site_url('laporan_perjadin_masuk/show_laporan_delete')?>",
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
					"ordering": false,
					"paging": true,
					"dom": 'Blrtip',
					"buttons": ['pdf', 'csv', 'excel', 'print'],
					
					// Load data for the table's content from an Ajax source
					"ajax": {
						"url": "<?php echo site_url('laporan_perjadin_masuk/data_list')?>",
						"type": "POST",						
						"data": function ( d ) {
								var chkSearch = [];
								$.each($("input[name='chkSearch[]']:checked"), function(){
									chkSearch.push($(this).val());
								});
								
								d.chkSearch = chkSearch;
								d.nama = document.getElementById('nama').value;
								var item_selectbox = document.getElementById('filter_tahun');
								d.filter_tahun = item_selectbox.options[item_selectbox.selectedIndex].value;	
								var item_selectbox = document.getElementById('filter_bulan');
								d.filter_bulan = item_selectbox.options[item_selectbox.selectedIndex].value;																																															
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
			
			function data_search()			
			{							
				table.ajax.reload(null,false); //reload datatable ajax 
			}
			
			function show_laporan(logbook_id)
			{				 				
				var form_data = {
						logbook_id: logbook_id					
					};
					
				$.ajax({
					url : "<?php echo site_url('laporan_perjadin_masuk/show_laporan')?>",
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
			
			function approval_laporan(id, nama, judul, logbook_id)
			{
				if(id != ''){
					//show modal confirmation
					$('#approve_form')[0].reset(); // reset form on modals
					$('#modal_approval_message').html('');  //reset message
					
					$('[name="id_logbook_inbox"]').val(id);
					$('#approval_text').html('<b >Laporan dari ' + nama + '</b><br />Dengan judul: <br />' + judul);
					
					var form_data = {
						logbook_id: logbook_id					
					};
					
					$.ajax({
							url : "<?php echo site_url('laporan_perjadin_masuk/show_laporan')?>",
							type: "POST",
							dataType: "JSON",
							data: form_data,
							success: function(data)
							{	
								pdf_file = 	data['logbook_filename_path'];																						
								document.getElementById('pdf_frame').src = data['logbook_filename_url'];																										
								$('#modalApproval').modal('show'); // show bootstrap modal when complete loaded									
							},
							error: function (jqXHR, textStatus, errorThrown)
							{						
								alert('Error adding / update data');									
							}
						});																							
				}else{					
					// ajax hapus data to database
					$.ajax({
						url : "<?php echo site_url('laporan_perjadin_masuk/approval_laporan')?>/",
						type: "POST",
						data: $('#approve_form').serialize(),
						dataType: "JSON",
						success: function(data)
						{
						   //if success close modal and reload ajax table
						   if(data['status'] == true){
								//berhasil simpan							
								 $('#modalApproval').modal('hide');					   		
								 $('#page_message').html('<div class="alert alert-info">Laporan telah diterima.</div>');
								reload_table();
						   }else{
								//form validation
								$('#modal_approval_message').html('<div class="alert alert-info">' + data['status'] + '</div>');							
						   }					   					  
						},
						error: function (jqXHR, textStatus, errorThrown)
						{						
							alert('Error adding / update data');									
						}
					});					
				}
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
		

		 <!-- Modal BEGIN:APPROVE LAPORAN-->										
		<div id="modalApproval" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content" >														
					  <div class="modal-header">			                        
						<h4 class="modal-title">Approval</h4>							
					  </div>
					  <div class="modal-body" >
					  		<div class="form-group" align="right">
								<div class="col-lg-12">
									<button type="button" id="btnDelete" onClick="approval_laporan('','','')" class="btn btn-sm btn-success">Approve</button>																 									<button type="button" class="btn btn-default" data-dismiss="modal"  aria-hidden="true">Close</button>	
								</div>																							
							</div> 		
							
					  	<!-- Form starts.  -->	
						<form class="form-horizontal" role="form" id="approve_form" action="#">
					  		<input type="hidden" value="" name="id_logbook_inbox"/> 									
							<div class="form-group" align="center">
								<div class="col-lg-12">
									<div id="approval_text"></div>																	  
								</div>																							
							</div> 																					
							 <div id="modal_approval_message"></div>
						</form>												
						<iframe id="pdf_frame" src="" width="100%" height="700px" frameborder="0"></iframe>																						
								
					  </div>	<!--END modal-body-->
					  <div class="modal-footer">																												
					  </div>
				  
				</div>	<!--END modal-content-->
			</div>	<!--END modal-dialog-->
		</div>
		<!-- Modal END:APPROVE LAPORAN-->	
														
    </div><!-- Mainbar ends -->   
   <div class="clearfix"></div>

</div>

<!-- Content ends -->	
<!-- End CONTENT ===============================================-->
<?PHP $this->load->view('footer'); ?>	
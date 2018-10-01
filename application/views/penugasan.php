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
								  <input type="checkbox" name="chkSearch[]" id="chkSearch[]" value="search_pengirim"> Pengirim:</b>
								</label>
								<div class="col-sm-3" align="left" style="text-align:left;  padding-left:0px; ">                               
									<input type="text" class="form-control" id="search_nama_pengirim" name="search_nama_pengirim" placeholder="Nama Pengirim" style="max-width:200px">
								</div>																							
							</div>
							
							<div class="form-group" style="vertical-align:middle">
								<label class="col-sm-2 checkbox-inline" style="max-width:150px; padding-left:40px; vertical-align:middle;"><b>
								  <input type="checkbox" name="chkSearch[]" id="chkSearch[]" value="search_penerima"> Penerima:</b>
								</label>
								<div class="col-sm-3" align="left" style="text-align:left;  padding-left:0px; ">                               
									<input type="text" class="form-control" id="search_nama_penerima" name="search_nama_penerima" placeholder="Nama Penerima" style="max-width:200px">
								</div>																							
							</div>														
													
					</div>				 																
				</div>
				
				<div class="row">
					<div class="col-md-6">
						<!-- Button to trigger modal -->
						<button class="btn btn-success" onClick="data_add()"><i class="fa fa-plus"></i> Input Tugas</button>											  												
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
								  <th style="min-width:180px"><b>Penerima</b></th>
								  <th ><b>Perihal</b></th>
								  <th width="130px" style="max-width:130px"><b>Tgl Laporan</b></th>
								  <th width="175px" style="max-width:175px"><b>Tgl Proses</b></th>
								  <th width="175px" style="max-width:175px"><b>Tgl Selesai</b></th>								  								  
								  <th width="60px" style="max-width:60px" align="center">Unduh</th>								  
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
			var save_method;
			//var pdf_file;							 
				  
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
						url : "<?php echo site_url('penugasan/data_init/')?>" ,
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
				 				
				//filter tahun on change	
				$('#filter_tahun').on('change',function(){					
					reload_table();
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
						"url": "<?php echo site_url('penugasan/data_list')?>",
						"type": "POST",						
						"data": function ( d ) {								
								var chkSearch = [];
								$.each($("input[name='chkSearch[]']:checked"), function(){
									chkSearch.push($(this).val());
								});
								
								d.chkSearch = chkSearch; 
								d.search_nama_pengirim = document.getElementById('search_nama_pengirim').value;
								d.search_nama_penerima = document.getElementById('search_nama_penerima').value; 
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
				
				table_user = $('#table_user').DataTable({ 			
					"processing": true, //Feature control the processing indicator.
					"serverSide": true, //Feature control DataTables' server-side processing mode.
					"deferLoading": 0, // here												
					"ordering": false,					
					
					// Load data for the table's content from an Ajax source
					"ajax": {
						"url": "<?php echo site_url('penugasan/data_list_users')?>",
						"type": "POST",
						"data": function ( d ) {
							//alert(is_where);							
							d.is_where = is_where;
							
							var nama_user_penerima = document.getElementById('nama_penerima').value;
							if(nama_user_penerima == ''){
								d.nama_user_penerima = '-';
							}else{
								d.nama_user_penerima = nama_user_penerima;
							}
														
							is_where = '';
						}
					},
			
					//Set column definition initialisation properties.
					"columnDefs": [
						{ 
						  "targets": [ -1, -2 ], //last column
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
			
			function data_search_nama()
			{			 	  			  			  
			  is_where = 'true';
			  document.getElementById('div_search_nama').style.display = "block";			  
			  table_user.ajax.reload(null,false); //reload datatable ajax 							  			  
			}	
				
			function data_pick(id_user_penerima, nama_user_penerima)
			{
				$('[name="nama_penerima"]').val(nama_user_penerima); 						
				$('[name="user_id_penerima"]').val(id_user_penerima);
			}		
			
			function data_add()
			{			  				  
			  save_method = 'add';
			  $('#add_form')[0].reset(); // reset form on modals		  
		      $('#modal_message').html('');  //reset message
			  $('[name="user_id"]').val('0');
			  
			  document.getElementById('div_upload_status').style.display = "none";	
			  document.getElementById('div_search_nama').style.display = "none";
			  			  			  			  
			  $('#modalAddForm').modal('show'); // show bootstrap modal
			  $('.modal-title').text('Tambah Data'); // Set Title to Bootstrap modal title						
			}						
			
			function data_save_validation()
			{						
			  var form = document.getElementById('add_form');					  
			  var form_data = new FormData(form);				
			  form_data.append("perihal_tugas", document.getElementById('perihal_tugas').value);	
			  form_data.append("user_id_penerima", document.getElementById('user_id_penerima').value);	
			  form_data.append("nama_penerima", document.getElementById('nama_penerima').value);				  
			  var fileInput = document.getElementById('file_tugas');
			  var file = fileInput.files[0];					
			  form_data.append("file_tugas", file);						  				  					  				  					  
					
			   // ajax adding data to database
				  $.ajax({
					url : "<?php echo site_url('penugasan/data_save_validation/')?>",
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
							data_save_tugas(data['new_file_name']);						
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
						
			function data_save_tugas(new_file_name)
			{					 								  
			  var form = document.getElementById('add_form');					  
			  var form_data = new FormData(form);					  
			  form_data.append("new_file_name", new_file_name);				  	
			  form_data.append("perihal_tugas", document.getElementById('perihal_tugas').value);	
			  form_data.append("user_id_penerima", document.getElementById('user_id_penerima').value);	
			  form_data.append("nama_penerima", document.getElementById('nama_penerima').value);
			  
			  var fileInput = document.getElementById('file_tugas');
			  var file = fileInput.files[0];					
			  form_data.append("file_tugas", file);	
			  
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
					url : "<?php echo site_url('penugasan/data_save_tugas/')?>",
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
			
			function proses_tugas(id_tugas, status_tugas, perihal)
			{
				if(id_tugas != ''){
					//show modal confirmation
					$('#tugas_form')[0].reset(); // reset form on modals
					$('#modal_tugas_message').html('');  //reset message
					
					$('[name="id_tugas"]').val(id_tugas);
					$('[name="status_tugas"]').val(status_tugas);
					
					if(status_tugas == '1'){
						//proses tugas
						$('.modal-title').text('Proses Tugas'); // Set Title to Bootstrap modal title	
						$('#tugas_text').html('<b >Mulai proses Tugas <br> ' + perihal + '</b>');	
						
					}else{
						//selesai tugas
						$('.modal-title').text('Selesai Tugas'); // Set Title to Bootstrap modal title	
						$('#tugas_text').html('<b >Selesai Tugas <br> ' + perihal + '</b>');	
					}
										
					$('#modalTugasForm').modal('show'); // show bootstrap modal when complete loaded					
				}else{
					//lakukan hapus data
					// ajax hapus data to database
					$.ajax({
						url : "<?php echo site_url('penugasan/proses_tugas')?>/",
						type: "POST",
						data: $('#tugas_form').serialize(),
						dataType: "JSON",
						success: function(data)
						{
						   //if success close modal and reload ajax table
						   if(data['status'] == true){
								//berhasil simpan							
								 $('#modalTugasForm').modal('hide');					   		
								 $('#page_message').html('<div class="alert alert-info">Data berhasil di simpan.</div>');
								reload_table();
						   }else{
								//form validation
								$('#modal_tugas_message').html('<div class="alert alert-info">' + data['status'] + '</div>');							
						   }					   					  
						},
						error: function (jqXHR, textStatus, errorThrown)
						{						
							alert('Error adding / update data');									
						}
					});					
				}				
			}
			/* function show_laporan(logbook_id)
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
			} */
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
							  <label class="col-lg-3 control-label" for="perihal_tugas">Perihal</label>
							  <div class="col-lg-9">
								<input type="text" class="form-control" id="perihal_tugas" name="perihal_tugas" placeholder="Perihal Tugas">
							  </div>							  
							</div>
														
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="file_laporan">File Tugas</label>
							  <div class="col-lg-9">
									<span class="btn btn-default btn-file" style="width:100%">
										<input type="file" class="form-control"  id="file_tugas" name="file_tugas" placeholder="File Tugas" >
									</span>									
							  </div>							  
							</div>	
							
							<div class="form-group" id="div_upload_status" style="display:none">
							  <label class="col-lg-3 control-label" for="file_tugas"></label>
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
							
							<div class="form-group" id="div_nama" >
							  <label class="col-lg-2 control-label" for="nama" id="lbl_nama">Ditujukan kepada</label>
							  <div class="col-lg-3" >
								<input type="text" class="form-control" id="nama_penerima" name="nama_penerima" placeholder="Nama" >
								<input type="hidden" value="" name="user_id_penerima" id="user_id_penerima"/>								
							  </div>
							  <div class="col-lg-2">
									<button type="button" id="btnSearchNama" onClick="data_search_nama()" class="btn btn-sm btn-primary">Cari</button>		  
							  </div>							  
							</div>	
							
							 <div id="div_search_nama" style="display:none">
								<!-- Table -->
									<div class="table-responsive">
										<table class="table-hover" cellpadding="0" cellspacing="0" border="0" id="table_user" width="100%">
											<thead style="background-color:#006699; color:#FFFFFF; text-align:center">
												<tr>
												  <th width="60px" style="max-width:60px"><b>No</b></th>
												  <th ><b>Nama</b></th>
												  <th ><b>NIP</b></th>												 
												  <th width="80px" style="max-width:80px"><b>Aksi</b></th>
												</tr>
											</thead>													
										</table>						
										<div class="clearfix"></div>																		
									</div>
								<!-- Table Page -->	
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
		
		<!-- Modal BEGIN:PROSES TUGAS-->										
		<div id="modalTugasForm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">									
					<!-- Form starts.  -->	
					<form class="form-horizontal" role="form" id="tugas_form" action="#">
					  <div class="modal-header">			                        
						<h4 class="modal-title" id="title_tugas">Proses</h4>
					  </div>
					  <div class="modal-body">
					  		<input type="hidden" value="" name="id_tugas" id="id_tugas"/> 
							<input type="hidden" value="" name="status_tugas" id="status_tugas"/> 
							<div class="form-group" align="center">
								<div class="col-lg-12">
									<div id="tugas_text"></div>																	  
								</div>																							
							</div> 
							
							 <div id="modal_tugas_message"></div>
					  </div>	<!--END modal-body-->
					  <div class="modal-footer">										
						<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>	
						<button type="button" id="btnTugas" onClick="proses_tugas('','','')" class="btn btn-sm btn-success">Simpan</button>								
					  </div>
				  </form>
				</div>	<!--END modal-content-->
			</div>	<!--END modal-dialog-->
		</div>
		<!-- Modal END:PROSES TUGAS-->
		
    </div><!-- Mainbar ends -->   
   <div class="clearfix"></div>

</div>

<!-- Content ends -->	
<!-- End CONTENT ===============================================-->
<?PHP $this->load->view('footer'); ?>	
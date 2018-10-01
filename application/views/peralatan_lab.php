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
								<label class="col-sm-2 checkbox-inline" style="max-width:150px; padding-left:40px; vertical-align:middle;"><b>
								  <input type="checkbox" name="chkSearch[]" id="chkSearch[]" value="tahun"> Tahun Dokumen:</b>
								</label>								
								<div class="col-sm-4" align="left" style="text-align:left; padding-left:0px; max-width:110px;">                               
									<select name="filter_tahun" id="filter_tahun" class="form-control">
										<option value="" >-- Pilih --</option>
									</select> 
								</div>
								<label class="col-sm-2 checkbox-inline" style="max-width:150px; padding-left:40px; vertical-align:middle;"><b>
								  <input type="checkbox" name="chkSearch[]" id="chkSearch[]" value="lab"> Lab:</b>
								</label>								
								<div class="col-sm-4" align="left" style="text-align:left; padding-left:0px; max-width:110px;">                               
									<select name="filter_lab" id="filter_lab" class="form-control">
										<option value="" >-- Pilih --</option>
									</select> 
								</div>
						  	</div>				
							
							<div class="form-group" style="vertical-align:middle">
								<label class="col-sm-2 checkbox-inline" style="max-width:150px; padding-left:40px; vertical-align:middle;"><b>
								  <input type="checkbox" name="chkSearch[]" id="chkSearch[]" value="nama"> Nama Alat:</b>
								</label>
								<div class="col-sm-3" align="left" style="text-align:left;  padding-left:0px;">                               
									<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Alat">
								</div>
								<div class="col-sm-1">
									<button type="button" class="btn btn-primary" id="btnSearch" onClick="data_search()"><i class="fa fa-search"></i> Cari</button>	
									
								</div>
							</div>
						
					</div>				 																
				</div>
				<hr style="border-top: 1px solid #ccc;border-bottom: 1px solid #fff;" />
				<!-- Button to trigger modal -->			
				<?php if($is_admin){ ?> <button class="btn btn-success" onClick="data_add()"><i class="fa fa-plus"></i> Tambah Alat</button><br /><br /> <?php } ?>
				<!-- Table Page -->
				<div class="page-tables">
					<!-- Table -->
					<div class="table-responsive">
						<table class="table-hover table-bordered" cellpadding="0" cellspacing="0" border="0" id="table" width="100%">
							<thead style="background-color:#006699; color:#FFFFFF;">
								<tr>
								  <th width="70px"><b>No</b></th>
								  <th ><b>Tahun</b></th>
								  <th ><b>Jenis</b></th>
								  <th ><b>Nama Alat</b></th>
								  <th ><b>Lab</b></th>
								  <th ><b>File</b></th>
								  <th width="90px"><b>Aksi</b></th>
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
			var save_method; //for save method string
			var table;
			
			$(document).ready(function() {			
				load_select_filter();
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
						"url": "<?php echo site_url('peralatan_lab/data_list')?>",
						"type": "POST",
						"data": function ( d ) {
								var chkSearch = [];
								$.each($("input[name='chkSearch[]']:checked"), function(){
									chkSearch.push($(this).val());
								});
								
								d.chkSearch = chkSearch;
								var item_selectbox = document.getElementById('filter_tahun');
								d.filter_tahun = item_selectbox.options[item_selectbox.selectedIndex].value;	
								var item_selectbox = document.getElementById('filter_lab');
								d.filter_lab = item_selectbox.options[item_selectbox.selectedIndex].value;								
								d.nama = document.getElementById('nama').value;								
						}
					},
			
					//Set column definition initialisation properties.
					"columnDefs": [
						{ 
						  "targets": [ -1 ], //last column
						  "searchable": false,
						  "orderable": false, //set not orderable
						},
						{
						  "targets": [ 0,1,6 ], // your case first column
						  "className": "text-center",
						},
					],
		
		 		});
		  	});	

			//function load select filter
			function load_select_filter(){
				 $.ajax({
						url : "<?php echo site_url('peralatan_lab/data_init/')?>" ,
						type: "POST",
						dataType: "JSON",
						success: function(data)
						{
							var d = new Date();
							var item_sel=["filter_tahun", "filter_lab"];
							var item_select = {"filter_tahun":d.getFullYear(), "filter_lab":-1};			
							select_box(data,item_select, item_sel);	
							reload_table();								
						},
						error: function (jqXHR, textStatus, errorThrown)
						{
							alert('Error get data from ajax');
						}
					});	
			}

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
		
			function data_add()
			{
			  save_method = 'add';
			  $('#add_form')[0].reset(); // reset form on modals		  
		      $('#modal_message').html('');  //reset message	
			  $.ajax({
					url : "<?php echo site_url('peralatan_lab/load_select/')?>" ,
					type: "POST",
					dataType: "JSON",
					success: function(data)
					{																			
						var d = new Date();
						var item_sel=["id_lab", "tahun"];
						var item_select = {"id_lab":-1, "tahun":d.getFullYear()};															
						select_box(data,item_select, item_sel);	
															
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						alert('Error get data from ajax');
					}
				});	
			  $('#modalAddForm').modal('show'); // show bootstrap modal
			  $('.modal-title').text('Tambah Data'); // Set Title to Bootstrap modal title					
			}
		
			function data_edit(id)
			{
			  save_method = 'update';
			  $('#add_form')[0].reset(); // reset form on modals
			  $('#modal_message').html('');  //reset message	
						
			  //Ajax Load data from ajax
			  $.ajax({			  		
					url : "<?php echo site_url('cms_konfigurasi/data_edit/')?>/" + id,
					type: "GET",
					dataType: "JSON",
					success: function(data)
					{				   
						$('[name="id"]').val(data['list'].id);  
						$('[name="parameter_name"]').val(data['list'].parameter_name);
						$('[name="parameter_value"]').val(data['list'].parameter_value);
						document.getElementById('parameter_name').disabled = true;
								
						$('#modalAddForm').modal('show'); // show bootstrap modal when complete loaded
						$('.modal-title').text('Edit Data'); // Set title to Bootstrap modal title					
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						alert('Error get data from ajax');
					}
				});
			}
		
			function reload_table()
			{
			  table.ajax.reload(null,false); //reload datatable ajax 
			}												
			
			function data_search()			
			{							
				table.ajax.reload(null,false); //reload datatable ajax 
			}
			
			
			function show_laporan(peralatan_id)
			{				 				
				var form_data = {
					peralatan_id: peralatan_id					
				};
					
				$.ajax({
						url : "<?php echo site_url('peralatan_lab/show_laporan')?>",
						type: "POST",
						dataType: "JSON",
						data: form_data,
						success: function(data)
						{															    														
							$('#judul_laporan').html('<b>' + data['laporan_title'] + '</b>');					    														
							pdf_file = 	data['laporan_filename_path'];																						
							document.getElementById('pdf_frame_laporan').src = data['laporan_filename_url'];																																																
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
				  //disable_input(0);					 								  
				  var form = document.getElementById('add_form');					  
				  var form_data = new FormData(form);								  
				  form_data.append("jenis_alat", document.getElementById('jenis_alat').value);
				  form_data.append("id_lab", document.getElementById('id_lab').value);						  
				  form_data.append("nama_alat", document.getElementById('nama_alat').value);						  
				  form_data.append("tahun", document.getElementById('tahun').value);						     						  		
				  var fileInput = document.getElementById('file_spek');
				  var file = fileInput.files[0];					
				  form_data.append("file_spek", file);						  				  					  				  					  
							
				   // ajax adding data to database
					  $.ajax({
						url : "<?php echo site_url('peralatan_lab/data_save_validation/')?>",
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
								data_save_peralatan(data['new_file_name']);	
								
								if(document.getElementById('status').value == 'revisi'){
									disable_input(1);
								}																																										
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
			
			function data_save_peralatan(new_file_name)
			{					 								  
				$('#form_add_message').html('');
				$('#modal_message').html('');
				document.getElementById('div_upload_status').style.display = "none";
				//disable_input(0);					 								  
				var form = document.getElementById('add_form');					  
				var form_data = new FormData(form);		
				//form_data.append("id", document.getElementById('id').value);	
				form_data.append("jenis_alat", document.getElementById('jenis_alat').value);
				form_data.append("id_lab", document.getElementById('id_lab').value);						  
				form_data.append("nama_alat", document.getElementById('nama_alat').value);	
				form_data.append("resume", document.getElementById('resume').value);				
				form_data.append("tahun", document.getElementById('tahun').value);								
				form_data.append("new_file_name", new_file_name);						     						  		
				var fileInput = document.getElementById('file_spek');
				var file = fileInput.files[0];					
				form_data.append("file_spek", file);						  
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
					url : "<?php echo site_url('peralatan_lab/data_save_peralatan/')?>",
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
							$('#modalAddForm').modal('hide');							 
							$('#modal_message').html('<div class="alert alert-info">Data berhasil di simpan.</div>');
							reload_table();
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
		
			function data_save()
			{
			  var url;
			  document.getElementById('parameter_name').disabled = false;
			  if(save_method == 'add') 
			  {
				  url = "<?php echo site_url('cms_konfigurasi/data_save_add')?>";
			  }
			  else
			  {
				url = "<?php echo site_url('cms_konfigurasi/data_save_edit')?>";
			  }
		
			   // ajax adding data to database
				  $.ajax({
					url : url,
					type: "POST",
					data: $('#add_form').serialize(),
					dataType: "JSON",
					success: function(data)
					{
					   //if success close modal and reload ajax table
					   if(data['status'] == true){
					   		//berhasil simpan							
							 $('#modalAddForm').modal('hide');					   		
							 $('#page_message').html('<div class="alert alert-info">Data berhasil di simpan.</div>');
							reload_table();
					   }else{
					   		//form validation
							if(save_method == 'update'){
								document.getElementById('parameter_name').disabled = true;
							}
							$('#modal_message').html('<div class="alert alert-info">' + data['status'] + '</div>');							
					   }					   					  
					},
					error: function (jqXHR, textStatus, errorThrown)
					{						
						alert('Error adding / update data');									
					}
				});
			}
			
			function data_delete(id, nama_id)
			{
				if(id != ''){
					//show modal confirmation
					$('#delete_form')[0].reset(); // reset form on modals
					$('#modal_delete_message').html('');  //reset message
					
					$('[name="id_delete_data"]').val(id);
					$('#delete_text').html('<b >Hapus data ' + nama_id + '</b>');	
					$('#modalDeleteForm').modal('show'); // show bootstrap modal when complete loaded
					$('.modal-title').text('Hapus Data'); // Set Title to Bootstrap modal title	
				}else{
					//lakukan hapus data
					// ajax hapus data to database
					$.ajax({
						url : "<?php echo site_url('peralatan_lab/data_delete')?>/",
						type: "POST",
						data: $('#delete_form').serialize(),
						dataType: "JSON",
						success: function(data)
						{
						   //if success close modal and reload ajax table
						   if(data['status'] == true){
								//berhasil hapus							
								 $('#modalDeleteForm').modal('hide');					   		
								 $('#page_message').html('<div class="alert alert-info">Data berhasil di hapus.</div>');
								reload_table();
						   }else{
								//form validation
								$('#modal_delete_message').html('<div class="alert alert-info">' + data['status'] + '</div>');							
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
	  
	  <!-- Modal BEGIN:ADD DATA-->
		<div id="modalAddForm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<!-- Form starts.  -->
					<form class="form-horizontal" role="form" id="add_form" action="#" autocomplete="nope">
					  <div class="modal-header">			                        
						<h4 class="modal-title">Tambah</h4>
					  </div>
					  <div class="modal-body">			
							<input type="hidden" value="baru" name="status" id="status"/>   	
							<input type="hidden" value="" name="id"/> 
							<div class="form-group" id="div_jenis_alat">
							  <label class="col-lg-3 control-label" for="jenis_alat">Jenis</label>
							  <div class="col-lg-4">
								<select name="jenis_alat" id="jenis_alat" class="form-control" style="max-width:90%;">											
										<option value="1" >Alat Ukur</option>
										<option value="2" >Perangkat Radio</option>
										<option value="3" >ATK</option>
									</select>
							  </div>							  
							</div>	
							
							<div class="form-group" id="div_id_lab">
							  <label class="col-lg-3 control-label" for="id_lab">Nama Lab</label>
							  <div class="col-lg-4">
								<select name="id_lab" id="id_lab" class="form-control" style="max-width:90%;">											
										<option value="0" >-- Pilih --</option>	
									</select>
							  </div>							  
							</div>
								
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="nama_alat">Nama Alat</label>
							  <div class="col-lg-5">
								<input type="text" class="form-control" id="nama_alat" name="nama_alat" placeholder="Nama Alat">
							  </div>
							</div>	
							
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="resume">Abstrak</label>
							  <div class="col-lg-7 text-area" >
								 <textarea class="cleditor" name="resume" id="resume" ></textarea>									  
							  </div>							  
							</div>
							
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="tahun">Tahun</label>
							  <div class="col-lg-3">
									<select name="tahun" id="tahun" class="form-control" style="max-width:90%;">
										<option value="0" >-- Pilih --</option>											
									</select>
							  </div>								  							  
							</div> 
							
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="file_spek">File Spek</label>
							  <div class="col-lg-8" >								  													
									<input type="file"  id="file_spek" name="file_spek" placeholder=""  style="width:450px; vertical-align:middle">																		
							  </div>							  
							</div>			
							<!--Download status -->					
							<div class="form-group" id="div_upload_status" style="display:none">
							  <label class="col-lg-2 control-label" for="file_spek"></label>
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
				
		<!-- Modal BEGIN:DELETE DATA-->										
		<div id="modalDeleteForm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">									
					<!-- Form starts.  -->	
					<form class="form-horizontal" role="form" id="delete_form" action="#">
					  <div class="modal-header">			                        
						<h4 class="modal-title">Hapus</h4>
					  </div>
					  <div class="modal-body">
					  		<input type="hidden" value="" name="id_delete_data"/> 																					
							<div class="form-group" align="center">
								<div class="col-lg-12">
									<div id="delete_text"></div>																	  
								</div>																							
							</div> 
							<div class="form-group" align="center">
								<div class="col-lg-12">
									<b >Anda yakin ?!</b>	
								</div>	
							</div> 
							 <div id="modal_delete_message"></div>
					  </div>	<!--END modal-body-->
					  <div class="modal-footer">										
						<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>	
						<button type="button" id="btnDelete" onClick="data_delete('','')" class="btn btn-sm btn-success">Hapus</button>								
					  </div>
				  </form>
				</div>	<!--END modal-content-->
			</div>	<!--END modal-dialog-->
		</div>
		<!-- Modal END:DELETE DATA-->		
														
    </div><!-- Mainbar ends -->   
   <div class="clearfix"></div>

</div>
<!-- Content ends -->	
<!-- End CONTENT ===============================================-->
<?PHP $this->load->view('footer'); ?>	
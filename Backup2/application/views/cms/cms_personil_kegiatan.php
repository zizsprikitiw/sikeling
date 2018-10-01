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
									 
				<form class="form-horizontal" id="form_pencarian" name="form_pencarian" >
					<div class="row">
						<div class="col-md-1" style="text-align:right; padding-right:0px">
							<label class="control-label ">Tahun:</label>
						</div>						
						<div class="col-md-2" style="padding-left:5px">															
							<select name="filter_tahun" id="filter_tahun" class="form-control">
								<option value="" >-- Pilih --</option>
							</select> 							
						</div>
						<div class="col-md-1" style="text-align:right; padding-right:0px">
							<label class="control-label ">Pusat:</label>
						</div>	
						<div class="col-md-4" style="padding-left:5px">								                            
							<select name="filter_pusat" id="filter_pusat" class="form-control">
								<option value="" >-- Pilih --</option>
							</select> 
						</div>
					</div>
					<br />
					<div class="row">
						<div class="col-md-1" style="text-align:right; padding-right:0px">
							<label class="control-label ">Judul:</label>
						</div>
						<div class="col-md-8" style="padding-left:5px">                          
							<select name="filter_judul" id="filter_judul" class="form-control">
								<option value="" >-- Pilih --</option>
							</select> 								
						</div>
						<div class="col-md-3" >
							&nbsp;
						</div>
					</div>
				</form>																				
				<hr style="border-top: 1px solid #ccc;border-bottom: 1px solid #fff;" />
				
				<div class="row">
					<div class="col-md-12" >
						<h3 class="pull-left" id="judul_proyek"></h3>					
					</div>
				</div>
											
				<!--PERSONIL KEGIATAN-->
				<div class="row">
					<div class="col-md-12" >
					
						<div class="widget">
							<!-- Widget title -->
							<div class="widget-head">
								<div class="pull-left">Personil Kegiatan</div>
								<div class="widget-icons pull-right">
									<button type="button" class="btn btn-sm btn-success" onClick="data_add()" id="btnAddPersonil"><i class="fa fa-plus"></i> Tambah Personil</button>
								</div>  
								<div class="clearfix"></div>
							</div>
							
							<div class="widget-content referrer" style="padding-top:2px; padding-bottom:0px; padding-left:2px; padding-right:2px;">
								<!-- Widget content -->
							  	
								<!-- Table Page -->
								<div class="page-tables">
									<!-- Table -->
									<div class="table-responsive">
										<table class="table-hover table-bordered" cellpadding="0" cellspacing="0" border="0" id="tablePersonil" width="100%">
											<thead style="background-color:#006699; color:#FFFFFF;">
												<tr>
												  <th style="max-width:70px"><b>No</b></th>
												  <th ><b>Nama</b></th>
												  <th width="40%" ><b>Posisi</b></th>										  										
												  <th style="max-width:95px"><b>Aksi</b></th>
												</tr>
											</thead>													
										</table>						
																		
									</div>
								</div>
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
			var is_where;	
			
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
			
			//function get judul
			function get_judul_proyek(){
				var item_selectbox = document.getElementById('filter_judul');
				var filter_judul = item_selectbox.options[item_selectbox.selectedIndex].value;				
				
				if(filter_judul == 0){
					 document.getElementById('btnAddPersonil').disabled = true;							
				}else{
					document.getElementById('btnAddPersonil').disabled = false;
				}
				
				var form_data = {
					filter_judul: filter_judul					
				};
				
				$.ajax({
						url : "<?php echo site_url('cms_personil_kegiatan/get_judul_proyek/')?>" ,
						type: "POST",
						data: form_data,
						dataType: "JSON",						
						success: function(data)
						{
							$('#judul_proyek').html(data['nama']);
						},
						error: function (jqXHR, textStatus, errorThrown)
						{
							alert('Error get data from ajax');
						}
					});	
			}
			
			//function load select filter
			function load_select_filter(){
				 $.ajax({
						url : "<?php echo site_url('cms_personil_kegiatan/data_init/')?>" ,
						type: "POST",
						dataType: "JSON",
						success: function(data)
						{
							var item_sel=["filter_pusat","filter_tahun"];
							var item_select = {"filter_pusat":-1,"filter_tahun":-1};															
							select_box(data,item_select, item_sel);			
							load_filter_judul();																								
						},
						error: function (jqXHR, textStatus, errorThrown)
						{
							alert('Error get data from ajax');
						}
					});	
			}
			
			//fungtion select judul
			function load_filter_judul(){
				var item_selectbox = document.getElementById('filter_tahun');
				var select_tahun = item_selectbox.options[item_selectbox.selectedIndex].value;
				item_selectbox = document.getElementById('filter_pusat');
				var select_pusat = item_selectbox.options[item_selectbox.selectedIndex].value;
				
				var form_data = {
					filter_tahun: select_tahun,
					filter_pusat: select_pusat					
				};
				
				if((select_tahun == '--Pilih--') | (select_tahun == '')){
					$('#ref_id').html('<Option value="--Pilih--">--Pilih--</option>');						
				}else{
					//load data judul
					$.ajax({
						url : "<?php echo site_url('cms_personil_kegiatan/select_sub_judul/')?>" ,
						type: "POST",
						dataType: "JSON",
						data: form_data,
						success: function(data)
						{
							var item_sel=["filter_judul"];
							var item_select = {"filter_judul":-1};															
							select_box(data,item_select, item_sel);	
							reload_table();																														
						},
						error: function (jqXHR, textStatus, errorThrown)
						{
							alert('Error get data from ajax');
						}
					});	
				}
			}			
			
			//fungtion select posisi struktural/posisi jabatan kegiatan (tambah data)
			function load_filter_posisi(posIndex, posIndexGlLd){
				var item_selectbox = document.getElementById('id_posisi_as');
				var id_posisi_as = item_selectbox.options[item_selectbox.selectedIndex].value;																
				
				var form_data = {
					id_posisi_as: id_posisi_as															
				};
								
				//load data
				$.ajax({
					url : "<?php echo site_url('cms_personil_kegiatan/select_posisi_kegiatan/')?>" ,
					type: "POST",
					dataType: "JSON",
					data: form_data,
					success: function(data)
					{
						var item_sel=["id_posisi_kegiatan"];
						var item_select = {"id_posisi_kegiatan":posIndex};															
						select_box(data,item_select, item_sel);	
						load_filter_ld_gl(posIndexGlLd);																													
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						alert('Error get data from ajax');
					}
				});	
				
			}	
			
			function load_filter_ld_gl(posIndex){						
				var item_selectbox = document.getElementById('id_posisi_as');
				var id_posisi_as = item_selectbox.options[item_selectbox.selectedIndex].value;	
																
				document.getElementById('div_posisi_gl_ld').style.display = "none";	
				document.getElementById('div_nama').style.display = "none";
				$('#modal_message').html('');  //reset message
				
				if(id_posisi_as == 1){
					//bukan struktural															
					item_selectbox = document.getElementById('id_posisi_kegiatan');
					var id_posisi_kegiatan = item_selectbox.options[item_selectbox.selectedIndex].value;	
					
					item_selectbox = document.getElementById('filter_judul');
					var filter_judul = item_selectbox.options[item_selectbox.selectedIndex].value;
					var user_id = document.getElementById('user_id').value;
					
					var form_data = {
						id_posisi_kegiatan: id_posisi_kegiatan,
						filter_judul: filter_judul,
						user_id:user_id																											
					};
					
					//load data
					$.ajax({
						url : "<?php echo site_url('cms_personil_kegiatan/select_posisi_ld_gl/')?>" ,
						type: "POST",
						dataType: "JSON",
						data: form_data,
						success: function(data)
						{
							var item_sel=["id_posisi_gl_ld"];
							var item_select = {"id_posisi_gl_ld":posIndex};															
							select_box(data,item_select, item_sel);
									
							if(data['have_sub'] == 'false'){
								//tidak memiliki sub	
								document.getElementById('div_nama').style.display = "block";																	
							}else{
								//memiliki sub
								if(data['is_empty'] == 'false'){																	
									document.getElementById('div_posisi_gl_ld').style.display = "block";	
									document.getElementById('div_nama').style.display = "block";																	
								}else{
									//leader/group leader empty, tampilkan info tambahakan leader/group leader																															
									$('#modal_message').html('<div class="alert alert-info">' + data['is_empty'] + ' masih kosong atau telah memiliki personil</div>');	
								}
							}
																																		
						},
						error: function (jqXHR, textStatus, errorThrown)
						{
							alert('Error get data from ajax');
						}
					});
				}else{
					//struktural					
					document.getElementById('div_nama').style.display = "block";
					/*
					var form_data = {
						id_posisi_kegiatan: id_posisi_kegiatan,
						filter_tahun: filter_tahun,
						filter_pusat: filter_pusat																											
					};
					
					//load data
					$.ajax({
						url : "< ?php echo site_url('cms_personil_kegiatan/get_nama_struktural/')?>" ,
						type: "POST",
						dataType: "JSON",
						data: form_data,
						success: function(data)
						{								
							
							$('[name="nama"]').val(data['list'][0].nama_user); 
							$('[name="user_id"]').val(data['list'][0].user_id);																																																
						},
						error: function (jqXHR, textStatus, errorThrown)
						{
							alert('Error get data from ajax');
						}
					});	*/				
				}				
			}	
			
			
			$(document).ready(function() {		
				//Ajax Load data tahun dan pusat
				load_select_filter();				 			
				
				//load sub judul by tahun
				$('#filter_tahun').on('change',function(){					
						load_filter_judul();											
				});		
				
				$('#filter_pusat').on('change',function(){					
						load_filter_judul();											
				});
				
				$('#filter_judul').on('change',function(){					
						reload_table();											
				});
				 	
				$('#id_posisi_as').on('change',function(){					
						load_filter_posisi(-1, -1);											
				});				
									
				$('#id_posisi_kegiatan').on('change',function(){					
						load_filter_ld_gl(-1);											
				});
								
				//load data table leader														
				tablePersonil = $('#tablePersonil').DataTable({ 			
					"processing": true, //Feature control the processing indicator.
					"serverSide": true, //Feature control DataTables' server-side processing mode.
					"deferLoading": 0, // here		
					"paging": false,							
					"ordering": false,					
					// Load data for the table's content from an Ajax source
					"ajax": {
						"url": "<?php echo site_url('cms_personil_kegiatan/data_list_personil/')?>",
						"type": "POST",						
						"data": function ( d ) {
								var item_selectbox = document.getElementById('filter_judul');
								d.filter_judul = item_selectbox.options[item_selectbox.selectedIndex].value;																								
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
						"url": "<?php echo site_url('cms_personil_kegiatan/data_list_users')?>",
						"type": "POST",
						"data": function ( d ) {
							//alert(is_where);							
							d.is_where = is_where;
							
							var nama_user = document.getElementById('nama').value;
							if(nama_user == ''){
								d.nama_user = '-';
							}else{
								d.nama_user = nama_user;
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
		
		 		});
		  	});//end document																
				
			function reload_table()
			{		
				get_judul_proyek();					  
				tablePersonil.ajax.reload(null,false); //reload datatable ajax 
			}															
					
			function data_add()
			{
			  save_method = 'add';
			  $('#add_form')[0].reset(); // reset form on modals		  
			  $('#modal_message').html('');  //reset message			  			  			  
			  $('[name="user_id"]').val('0');
			  
			  document.getElementById('id_posisi_as').disabled = false;
			  document.getElementById('id_posisi_kegiatan').disabled = false;
			  document.getElementById('id_posisi_gl_ld').disabled = false;	
						
			  var item_selectbox = document.getElementById('filter_judul');
			  $('[name="proyek_id"]').val(item_selectbox.options[item_selectbox.selectedIndex].value);			  
			  
			  document.getElementById('div_search_nama').style.display = "none";
			  load_filter_posisi(-1);	
							  
			  $('#modalAddForm').modal('show'); // show bootstrap modal
			  $('.modal-title').text('Tambah Data'); // Set Title to Bootstrap modal title				 		  				 			
			}
						
			function data_edit(id_users_posisi)
			{
			  save_method = 'update';
			  $('#add_form')[0].reset(); // reset form on modals
			  $('#modal_message').html('');  //reset message		
			  
			  var item_selectbox = document.getElementById('filter_judul');
			  $('[name="proyek_id"]').val(item_selectbox.options[item_selectbox.selectedIndex].value);			  		  			 				 			  				 			  document.getElementById('div_search_nama').style.display = "none";
			  			
			  var form_data = {
					id_users_posisi: id_users_posisi			
				};
			  			
			  //Ajax Load data from ajax
			  $.ajax({			  		
					url : "<?php echo site_url('cms_personil_kegiatan/data_edit/')?>",
					type: "POST",
					dataType: "JSON",
					data: form_data,
					success: function(data)
					{				   							
						document.getElementById('div_nama').style.display = "block";
								
						$('[name="id"]').val(data['list'].id);  
						$('[name="nama"]').val(data['list'].nama_user); 						
						$('[name="user_id"]').val(data['list'].user_id);	 						
						
						if((data['list'].struktural_id == null) || (data['list'].struktural_id == '')){
							document.getElementById('id_posisi_as').selectedIndex = "1";																					
							
							if(data['list'].groups_leader_id != null){
								posIndexGlLd = data['list'].groups_leader_id;
							}else if(data['list'].leader_id != null){
								posIndexGlLd = data['list'].leader_id;									
							}
							
							load_filter_posisi(data['list'].posisi_id, posIndexGlLd);
						}else{
							//struktural
							document.getElementById('id_posisi_as').selectedIndex = "0";
							load_filter_posisi(data['list'].struktural_id);
						}
						
						document.getElementById('id_posisi_as').disabled = true;
						document.getElementById('id_posisi_kegiatan').disabled = true;
						document.getElementById('id_posisi_gl_ld').disabled = true;												
																		
						$('#modalAddForm').modal('show'); // show bootstrap modal when complete loaded
						$('.modal-title').text('Edit Data'); // Set title to Bootstrap modal title					
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						alert('Error get data from ajax');
					}
				});
			}	
					
			function data_search()
			{			 	  			  			  
			  is_where = 'true';
			  document.getElementById('div_search_nama').style.display = "block";			  
			  table_user.ajax.reload(null,false); //reload datatable ajax 							  			  
			}	
				
			function data_pick(id_users, nama_user)
			{
				$('[name="nama"]').val(nama_user); 						
				$('[name="user_id"]').val(id_users);
			}			
		
			function data_save()
			{
			  var url;		
			  document.getElementById('id_posisi_as').disabled = false;
			  document.getElementById('id_posisi_kegiatan').disabled = false;
			  document.getElementById('id_posisi_gl_ld').disabled = false;	
			  	  			  			  
			  if(save_method == 'add') 
			  {
				  url = "<?php echo site_url('cms_personil_kegiatan/data_save_add')?>";
			  }else{
				  url = "<?php echo site_url('cms_personil_kegiatan/data_save_edit')?>";
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
					
					var item_selectbox = document.getElementById('filter_judul');
			  		$('[name="proyek_id_del"]').val(item_selectbox.options[item_selectbox.selectedIndex].value);	
										
					$('#delete_text').html('<b >Hapus data ' + nama_id + '</b>');	
					$('#modalDeleteForm').modal('show'); // show bootstrap modal when complete loaded
					$('.modal-title').text('Hapus Data'); // Set Title to Bootstrap modal title	
				}else{
					//lakukan hapus data
					// ajax hapus data to database
					$.ajax({
						url : "<?php echo site_url('cms_personil_kegiatan/data_delete')?>/",
						type: "POST",
						data: $('#delete_form').serialize(),
						dataType: "JSON",
						success: function(data)
						{
						   //if success close modal and reload ajax table
						   if(data['status'] == true){
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
					  <div class="modal-header">			                        
						<h4 class="modal-title">Tambah</h4>
					  </div>
					  <div class="modal-body">	
					  	<!-- Form starts.  -->
						<form class="form-horizontal" role="form" id="add_form" action="#" autocomplete="nope">								  		 											
							<input type="hidden" value="" name="id" id="id"/> 											
							<input type="hidden" value="" name="proyek_id" id="proyek_id"/>	
							<div class="form-group" >
							  <label class="col-lg-2 control-label" for="id_posisi_as">Posisi</label>
							  <div class="col-lg-4">
									<select name="id_posisi_as" id="id_posisi_as" class="form-control" >
										<option value="0" >Struktural</option>
										<option value="1" >Kegiatan</option>
									</select>
							  </div>
							</div>
							
							<div class="form-group" >
							  <label class="col-lg-2 control-label"  for="id_posisi_kegiatan"></label>
							  <div class="col-lg-8">
									<select name="id_posisi_kegiatan" id="id_posisi_kegiatan" class="form-control" >
										<option value="0" >-- Pilih --</option>
									</select>
							  </div>
							</div>	
							
							<div class="form-group" id="div_posisi_gl_ld" style="display:none">
							  <label class="col-lg-2 control-label"  for="id_posisi_gl_ld"></label>
							  <div class="col-lg-8">
									<select name="id_posisi_gl_ld" id="id_posisi_gl_ld" class="form-control" >
										<option value="0" >-- Pilih --</option>
									</select>
							  </div>
							</div>															
														
							<div class="form-group" id="div_nama" style="display:none">
							  <label class="col-lg-2 control-label" for="nama" id="lbl_nama">Nama</label>
							  <div class="col-lg-8">
								<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama">
								<input type="hidden" value="" name="user_id" id="user_id"/>								
							  </div>
							  <div class="col-lg-2">
									<button type="button" id="btnSearch" onClick="data_search()" class="btn btn-sm btn-primary">Cari</button>		  
							  </div>							  
							</div>																									
							
							<div id="modal_message"></div>
							
							<div class="form-group" >
								<div class="col-lg-3">
								</div>
								<div class="col-lg-8"  align="right">
									<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>	
									<button type="button" id="btnSave" onClick="data_save()" class="btn btn-sm btn-success">Simpan</button>	
								</div>
							</div>
						 </form>
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
							
					  </div>	<!--END modal-body-->
					  <div class="modal-footer">																							
					  </div>
				 
				</div>	<!--END modal-content-->
			</div>	<!--END modal-dialog-->
		</div>
		<!-- Modal END:ADD DATA-->
		
		
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
							<input type="hidden" value="" name="proyek_id_del" id="proyek_id_del"/> 																					
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
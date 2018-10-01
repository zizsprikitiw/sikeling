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
								
				<!-- Button to trigger modal -->			
				<button class="btn btn-success" onClick="data_add()"><i class="fa fa-plus"></i> Tambah User</button>
				<br />
				<br />				
				
				<!-- Table Page -->
				<div class="page-tables">
					<!-- Table -->
					<div class="table-responsive">
						<table class="table-hover table-bordered" cellpadding="0" cellspacing="0" border="0" id="table" width="100%">
							<thead style="background-color:#006699; color:#FFFFFF; text-align:center">
								<tr>
								  <th width="60px" style="max-width:60px"><b>No</b></th>
								  <th ><b>Nama</b></th>
								  <th width="170px"><b>NIP</b></th>
								  <th width="170px" style="max-width:170px"><b>Username</b></th>							  
								  <th width="100px" style="max-width:100px"><b>Login</b></th>	
								  <th width="80px" style="max-width:80px"><b>Status</b></th>
								  <th width="180px" style="max-width:180px"><b>Aksi</b></th>
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
			var save_method_fungsional;
			var save_method_struktural;
			var save_method_bidang;
			var table;
			
			$(document).ready(function() {	
				//load data users															
			 	table = $('#table').DataTable({ 			
					"processing": true, //Feature control the processing indicator.
					"serverSide": true, //Feature control DataTables' server-side processing mode.
					
					// Load data for the table's content from an Ajax source
					"ajax": {
						"url": "<?php echo site_url('cms_users/data_list')?>",
						"type": "POST"
					},
			
					//Set column definition initialisation properties.
					"columnDefs": [
						{ 
						  "targets": [ -1, -2 ,-3], //last column
						  "searchable": false,
						  "orderable": false, //set not orderable
						},
					],
		
		 		});
				
				//load data fungsional														
				table_fungsional = $('#table_fungsional').DataTable({ 			
					"processing": true, //Feature control the processing indicator.
					"serverSide": true, //Feature control DataTables' server-side processing mode.												
					"deferLoading": 0, // here	
					"paging": false,						
					"ordering": false,
					"bFilter": false,
					// Load data for the table's content from an Ajax source
					"ajax": {
						"url": "<?php echo site_url('cms_users/data_list_fungsional/')?>",
						"type": "POST",
						"data":function ( d ) {
								var item_tab = document.getElementById('id_user_for_fungsional');
								d.id_user_for_fungsional = item_tab.value;			//document.getElementById('id_tabs').value					
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
				
				//load data struktural														
				table_struktural = $('#table_struktural').DataTable({ 			
					"processing": true, //Feature control the processing indicator.
					"serverSide": true, //Feature control DataTables' server-side processing mode.												
					"deferLoading": 0, // here	
					"paging": false,						
					"ordering": false,
					"bFilter": false,
					// Load data for the table's content from an Ajax source
					"ajax": {
						"url": "<?php echo site_url('cms_users/data_list_struktural/')?>",
						"type": "POST",
						"data":function ( d ) {
								var item_tab = document.getElementById('id_user_for_struktural');
								d.id_user_for_struktural = item_tab.value;			//document.getElementById('id_tabs').value					
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
				
				//load data struktural														
				table_bidang = $('#table_bidang').DataTable({ 			
					"processing": true, //Feature control the processing indicator.
					"serverSide": true, //Feature control DataTables' server-side processing mode.												
					"deferLoading": 0, // here	
					"paging": false,						
					"ordering": false,
					"bFilter": false,
					// Load data for the table's content from an Ajax source
					"ajax": {
						"url": "<?php echo site_url('cms_users/data_list_bidang/')?>",
						"type": "POST",
						"data":function ( d ) {
								var item_tab = document.getElementById('id_user_for_bidang');
								d.id_user_for_bidang = item_tab.value;			//document.getElementById('id_tabs').value					
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
			  document.getElementById('users_groups').disabled = false;			  
			  //document.getElementById('txt_password').disabled = false;
			  //document.getElementById('txt_password_confirm').disabled = false;
			  
			  //Ajax Load data from ajax
			  $.ajax({
					url : "<?php echo site_url('cms_users/data_add/')?>" ,
					type: "POST",
					dataType: "JSON",
					success: function(data)
					{
						var item_sel=["users_groups"];
						var item_select = {"users_groups":-1};															
						select_box(data,item_select, item_sel);						
											
						$('#modalAddForm').modal('show'); // show bootstrap modal
						$('.modal-title').text('Tambah Data'); // Set Title to Bootstrap modal title					
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						alert('Error get data from ajax');
					}
				});			 
			}
		
			function data_edit(id)
			{
			  save_method = 'update';
			  $('#add_form')[0].reset(); // reset form on modals
			  $('#modal_message').html('');  //reset message	
			  document.getElementById('users_groups').disabled = true;
						
			  //Ajax Load data from ajax
			  $.ajax({			  		
					url : "<?php echo site_url('cms_users/data_edit/')?>/" + id,
					type: "GET",
					dataType: "JSON",
					success: function(data)
					{				   
						$('[name="id"]').val(data['user'].id);  
						$('[name="nama"]').val(data['user'].nama);
						$('[name="nip"]').val(data['user'].nip);
						$('[name="txt_email"]').val(data['user'].email);
						$('[name="txt_username"]').val(data['user'].username);						
														
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
		
			function data_save()
			{
			  var url;
			  if(save_method == 'add') 
			  {
				  url = "<?php echo site_url('cms_users/data_save_add')?>";
			  }
			  else
			  {
				url = "<?php echo site_url('cms_users/data_save_edit')?>";
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
			
			function data_delete(id, nama_user)
			{
				if(id != ''){
					//show modal confirmation
					$('#delete_form')[0].reset(); // reset form on modals
					$('#modal_delete_message').html('');  //reset message
					
					$('[name="id_delete_user"]').val(id);
					$('#delete_text').html('<b >Hapus data ' + nama_user + '</b>');	
					$('#modalDeleteForm').modal('show'); // show bootstrap modal when complete loaded
					$('.modal-title').text('Hapus Data'); // Set Title to Bootstrap modal title	
				}else{
					//lakukan hapus data
					// ajax hapus data to database
					$.ajax({
						url : "<?php echo site_url('cms_users/data_delete')?>/",
						type: "POST",
						data: $('#delete_form').serialize(),
						dataType: "JSON",
						success: function(data)
						{
						   //if success close modal and reload ajax table
						   if(data['status'] == true){
								//berhasil simpan							
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
			
			function data_edit_status(id)
			{
				$.ajax({
						url : "<?php echo site_url('cms_users/data_edit_status')?>/"+id,
						type: "POST",
						dataType: "JSON",
						success: function(data)
						{
						   //if success close modal and reload ajax table
						   if(data['status'] == true){
								//berhasil simpan							
								 $('#page_message').html('<div class="alert alert-info">Berhasil update status user.</div>');
								reload_table();
						   }else{
								$('#page_message').html('<div class="alert alert-info">Gagal update status user.</div>');						
						   }					   					  
						},
						error: function (jqXHR, textStatus, errorThrown)
						{						
							alert('Error adding / update data');									
						}
					});	
			}		
			
			// USERS GROUP
			//========================================================
			function data_edit_user_group(user_id, nama_user)			
			{
				if(user_id != ''){
					//show modal confirmation
					$('#modal_users_group_message').html('');  //reset message					
					$('[name="id_user_for_ug"]').val(user_id);
					$('#users_group_text').html('<h4><b >Nama: ' + nama_user + '</b></h4>');	
															
					var form_data = {
							user_id: user_id
						};
					
					$.ajax({
						url : "<?php echo site_url('cms_users/data_get_users_group')?>",
						type: "POST",
						dataType: "JSON",
						data:form_data,
						success: function(data)
						{
							$('#select_user_group').html(data['users_groups']);
							$('#modalUsersGroup').modal('show'); // show bootstrap modal when complete loaded											   					
						},
						error: function (jqXHR, textStatus, errorThrown)
						{						
							alert('Error adding / update data');									
						}
					});	//end ajax					
				}else{
					//Simpan users group					
					$.ajax({
						url : "<?php echo site_url('cms_users/data_set_users_group')?>",
						type: "POST",
						dataType: "JSON",
						data: $('#users_group_form').serialize(),
						success: function(data)
						{
							$('#modalUsersGroup').modal('hide'); 
						   //if success close modal and reload ajax table
						   if(data['status'] == true){
								//berhasil simpan							
								 $('#page_message').html('<div class="alert alert-info">Berhasil update group user.</div>');
								//reload_table();
						   }else{
								$('#page_message').html('<div class="alert alert-info">Gagal update group user.</div>');						
						   }					   					  
						},
						error: function (jqXHR, textStatus, errorThrown)
						{						
							alert('Error adding / update data');									
						}
					});
				}
			}				
			
			// USERS FUNGSIONAL
			//========================================================			
			function reload_table_fungsional()
			{
			  table_fungsional.ajax.reload(null,false); //reload datatable ajax 
			}		
			
			function users_fungsional(user_id, nama_user)			
			{				
				//show modal confirmation
				$('#modal_fungsional_message').html('');  //reset message					
				$('[name="id_user_for_fungsional"]').val(user_id);
				$('#fungsional_text').html('<h4><b >Nama: ' + nama_user + '</b></h4>');						
														
				$.ajax({
						url : "<?php echo site_url('cms_users/data_get_fungsional')?>",
						type: "POST",
						dataType: "JSON",						
						success: function(data)
						{
							var item_sel=["fungsional_name", "fungsional_tahun_awal", "fungsional_tahun_akhir"];
							var item_select = {"fungsional_name":-1, "fungsional_tahun_awal":-1, "fungsional_tahun_akhir":-1};															
							select_box(data,item_select, item_sel);	
						
							reload_table_fungsional();
							show_add_fungsional(false, '', '');	
							$('#modalFungsional').modal('show'); // show bootstrap modal when complete loaded											   					
						},
						error: function (jqXHR, textStatus, errorThrown)
						{						
							alert('Error adding / update data');									
						}
					});	//end ajax																				
			}
			
			function show_add_fungsional(stat, method, id_users_fungsional)
			{
				$('#page_message_fungsional').html('');
				if(stat == true){
					save_method_fungsional = method;	//save_method = 'update';	
					if(method == 'add'){
						$('#fungsional_name').prop('selectedIndex',0);
						$('#fungsional_tahun_awal').prop('selectedIndex',0);
						$('#fungsional_tahun_akhir').prop('selectedIndex',0);
					}else{
						//update
						$('[name="id_users_fungsional"]').val(id_users_fungsional);
						
						var form_data = {
							id_users_fungsional: id_users_fungsional
						};
						
						$.ajax({
								url : "<?php echo site_url('cms_users/data_get_users_fungsional')?>",
								type: "POST",
								dataType: "JSON",
								data:form_data,
								success: function(data)
								{
									var item_sel=["fungsional_name", "fungsional_tahun_awal", "fungsional_tahun_akhir"];
									var item_select = {"fungsional_name":data['users_fungsional'].fungsional_id, "fungsional_tahun_awal":data['users_fungsional'].tahun_awal, "fungsional_tahun_akhir":data['users_fungsional'].tahun_akhir};															
									select_box(data,item_select, item_sel);	
								
									reload_table_fungsional();
									$('#modalFungsional').modal('show'); // show bootstrap modal when complete loaded											   					
								},
								error: function (jqXHR, textStatus, errorThrown)
								{						
									alert('Error adding / update data');									
								}
							});	//end ajax	
					}									
					
					document.getElementById('btnAddFungsional').style.visibility = "hidden";
					document.getElementById('div_AddFungsional').style.display = "block";
				}else{
					//stat==false
					document.getElementById('btnAddFungsional').style.visibility = "visible";
					document.getElementById('div_AddFungsional').style.display = "none";
				}
			}
									
			function data_save_fungsional()
			{
			  var url;
			  if(save_method_fungsional == 'add') 
			  {
				  url = "<?php echo site_url('cms_users/data_save_add_fungsional')?>";
			  }
			  else
			  {
				url = "<?php echo site_url('cms_users/data_save_edit_fungsional')?>";
			  }
		
			   // ajax adding data to database
				  $.ajax({
					url : url,
					type: "POST",
					data: $('#fungsional_form').serialize(),
					dataType: "JSON",
					success: function(data)
					{
					   //if success close modal and reload ajax table
					   if(data['status'] == true){
					   		//berhasil simpan				
							show_add_fungsional(false, '', '');										
							$('#page_message_fungsional').html('<div class="alert alert-info">Data berhasil di simpan.</div>');
							reload_table_fungsional();
					   }else{
					   		//form validation
							$('#modal_fungsional_message').html('<div class="alert alert-info">' + data['status'] + '</div>');							
					   }					   					  
					},
					error: function (jqXHR, textStatus, errorThrown)
					{						
						alert('Error adding / update data');									
					}
				});
			}
			
			function data_delete_fungsional(id_users_fungsional)
			{									
				var form_data = {
					id_users_fungsional: id_users_fungsional
				};
			
				//lakukan hapus data
				// ajax hapus data to database
				$.ajax({
					url : "<?php echo site_url('cms_users/data_delete_fungsional')?>",
					type: "POST",
					data: form_data,
					dataType: "JSON",
					success: function(data)
					{
					   //if success close modal and reload ajax table
					   if(data['status'] == true){
							//berhasil simpan		
							show_add_fungsional(false, '', '');													 				   		
							 $('#page_message_fungsional').html('<div class="alert alert-info">Data berhasil di hapus.</div>');
							reload_table_fungsional();
					   }else{
							//form validation
							show_add_fungsional(false, '', '');	
							$('#page_message_fungsional').html('<div class="alert alert-info">' + data['status'] + '</div>');							
					   }					   					  
					},
					error: function (jqXHR, textStatus, errorThrown)
					{						
						alert('Error adding / update data');									
					}
				});											
			}
			
			// USERS STRUKTURAL
			//========================================================			
			function reload_table_struktural()
			{
			  table_struktural.ajax.reload(null,false); //reload datatable ajax 
			}		
			
			function users_struktural(user_id, nama_user)			
			{				
				//show modal confirmation
				$('#modal_struktural_message').html('');  //reset message					
				$('[name="id_user_for_struktural"]').val(user_id);
				$('#struktural_text').html('<h4><b >Nama: ' + nama_user + '</b></h4>');						
														
				$.ajax({
						url : "<?php echo site_url('cms_users/data_get_struktural')?>",
						type: "POST",
						dataType: "JSON",						
						success: function(data)
						{
							var item_sel=["struktural_name", "struktural_tahun_awal", "struktural_tahun_akhir"];
							var item_select = {"struktural_name":-1, "struktural_tahun_awal":-1, "struktural_tahun_akhir":-1};															
							select_box(data,item_select, item_sel);	
						
							reload_table_struktural();
							show_add_struktural(false, '', '');	
							$('#modalStruktural').modal('show'); // show bootstrap modal when complete loaded											   					
						},
						error: function (jqXHR, textStatus, errorThrown)
						{						
							alert('Error adding / update data');									
						}
					});	//end ajax																				
			}
			
			function show_add_struktural(stat, method, id_users_struktural)
			{
				$('#page_message_struktural').html('');
				if(stat == true){
					save_method_struktural = method;	//save_method = 'update';	
					if(method == 'add'){
						$('#struktural_name').prop('selectedIndex',0);
						$('#struktural_tahun_awal').prop('selectedIndex',0);
						$('#struktural_tahun_akhir').prop('selectedIndex',0);
					}else{
						//update
						$('[name="id_users_struktural"]').val(id_users_struktural);
						
						var form_data = {
							id_users_struktural: id_users_struktural
						};
						
						$.ajax({
								url : "<?php echo site_url('cms_users/data_get_users_struktural')?>",
								type: "POST",
								dataType: "JSON",
								data:form_data,
								success: function(data)
								{
									var item_sel=["struktural_name", "struktural_tahun_awal", "struktural_tahun_akhir"];
									var item_select = {"struktural_name":data['users_struktural'].struktural_id, "struktural_tahun_awal":data['users_struktural'].tahun_awal, "struktural_tahun_akhir":data['users_struktural'].tahun_akhir};															
									select_box(data,item_select, item_sel);	
								
									reload_table_struktural();
									$('#modalStruktural').modal('show'); // show bootstrap modal when complete loaded											   					
								},
								error: function (jqXHR, textStatus, errorThrown)
								{						
									alert('Error adding / update data');									
								}
							});	//end ajax	
					}									
					
					document.getElementById('btnAddStruktural').style.visibility = "hidden";
					document.getElementById('div_AddStruktural').style.display = "block";
				}else{
					//stat==false
					document.getElementById('btnAddStruktural').style.visibility = "visible";
					document.getElementById('div_AddStruktural').style.display = "none";
				}
			}
									
			function data_save_struktural()
			{
			  var url;
			  if(save_method_struktural == 'add') 
			  {
				  url = "<?php echo site_url('cms_users/data_save_add_struktural')?>";
			  }
			  else
			  {
				url = "<?php echo site_url('cms_users/data_save_edit_struktural')?>";
			  }
		
			   // ajax adding data to database
				  $.ajax({
					url : url,
					type: "POST",
					data: $('#struktural_form').serialize(),
					dataType: "JSON",
					success: function(data)
					{
					   //if success close modal and reload ajax table
					   if(data['status'] == true){
					   		//berhasil simpan				
							show_add_struktural(false, '', '');										
							$('#page_message_struktural').html('<div class="alert alert-info">Data berhasil di simpan.</div>');
							reload_table_struktural();
					   }else{
					   		//form validation
							$('#modal_struktural_message').html('<div class="alert alert-info">' + data['status'] + '</div>');							
					   }					   					  
					},
					error: function (jqXHR, textStatus, errorThrown)
					{						
						alert('Error adding / update data');									
					}
				});
			}
			
			function data_delete_struktural(id_users_struktural)
			{									
				var form_data = {
					id_users_struktural: id_users_struktural
				};
			
				//lakukan hapus data
				// ajax hapus data to database
				$.ajax({
					url : "<?php echo site_url('cms_users/data_delete_struktural')?>",
					type: "POST",
					data: form_data,
					dataType: "JSON",
					success: function(data)
					{
					   //if success close modal and reload ajax table
					   if(data['status'] == true){
							//berhasil simpan		
							show_add_struktural(false, '', '');													 				   		
							 $('#page_message_struktural').html('<div class="alert alert-info">Data berhasil di hapus.</div>');
							reload_table_struktural();
					   }else{
							//form validation
							show_add_struktural(false, '', '');	
							$('#page_message_struktural').html('<div class="alert alert-info">' + data['status'] + '</div>');							
					   }					   					  
					},
					error: function (jqXHR, textStatus, errorThrown)
					{						
						alert('Error adding / update data');									
					}
				});											
			}
			
			// USERS BIDANG
			//========================================================						
			function reload_table_bidang()
			{
			  table_bidang.ajax.reload(null,false); //reload datatable ajax 
			}		
			
			function users_bidang(user_id, nama_user)			
			{				
				//show modal confirmation
				$('#modal_bidang_message').html('');  //reset message					
				$('[name="id_user_for_bidang"]').val(user_id);
				$('#bidang_text').html('<h4><b >Nama: ' + nama_user + '</b></h4>');						
														
				$.ajax({
						url : "<?php echo site_url('cms_users/data_get_bidang')?>",
						type: "POST",
						dataType: "JSON",						
						success: function(data)
						{
							var item_sel=["bidang_name", "bidang_tahun_awal", "bidang_tahun_akhir"];
							var item_select = {"bidang_name":-1, "bidang_tahun_awal":-1, "bidang_tahun_akhir":-1};															
							select_box(data,item_select, item_sel);	
						
							reload_table_bidang();
							show_add_bidang(false, '', '');	
							$('#modalBidang').modal('show'); // show bootstrap modal when complete loaded											   					
						},
						error: function (jqXHR, textStatus, errorThrown)
						{						
							alert('Error adding / update data');									
						}
					});	//end ajax																				
			}
			
			function show_add_bidang(stat, method, id_users_bidang)
			{
				$('#page_message_bidang').html('');
				if(stat == true){
					save_method_bidang = method;	//save_method = 'update';	
					if(method == 'add'){
						$('#bidang_name').prop('selectedIndex',0);
						$('#bidang_tahun_awal').prop('selectedIndex',0);
						$('#bidang_tahun_akhir').prop('selectedIndex',0);
					}else{
						//update
						$('[name="id_users_bidang"]').val(id_users_bidang);
						
						var form_data = {
							id_users_bidang: id_users_bidang
						};
						
						$.ajax({
								url : "<?php echo site_url('cms_users/data_get_users_bidang')?>",
								type: "POST",
								dataType: "JSON",
								data:form_data,
								success: function(data)
								{
									var item_sel=["bidang_name", "bidang_tahun_awal", "bidang_tahun_akhir"];
									var item_select = {"bidang_name":data['users_bidang'].bidang_id, "bidang_tahun_awal":data['users_bidang'].tahun_awal, "bidang_tahun_akhir":data['users_bidang'].tahun_akhir};															
									select_box(data,item_select, item_sel);	
								
									reload_table_bidang();
									$('#modalBidang').modal('show'); // show bootstrap modal when complete loaded											   					
								},
								error: function (jqXHR, textStatus, errorThrown)
								{						
									alert('Error adding / update data');									
								}
							});	//end ajax	
					}									
					
					document.getElementById('btnAddBidang').style.visibility = "hidden";
					document.getElementById('div_AddBidang').style.display = "block";
				}else{
					//stat==false
					document.getElementById('btnAddBidang').style.visibility = "visible";
					document.getElementById('div_AddBidang').style.display = "none";
				}
			}
									
			function data_save_bidang()
			{
			  var url;
			  if(save_method_bidang == 'add') 
			  {
				  url = "<?php echo site_url('cms_users/data_save_add_bidang')?>";
			  }
			  else
			  {
				url = "<?php echo site_url('cms_users/data_save_edit_bidang')?>";
			  }
		
			   // ajax adding data to database
				  $.ajax({
					url : url,
					type: "POST",
					data: $('#bidang_form').serialize(),
					dataType: "JSON",
					success: function(data)
					{
					   //if success close modal and reload ajax table
					   if(data['status'] == true){
					   		//berhasil simpan				
							show_add_bidang(false, '', '');										
							$('#page_message_bidang').html('<div class="alert alert-info">Data berhasil di simpan.</div>');
							reload_table_bidang();
					   }else{
					   		//form validation
							$('#modal_bidang_message').html('<div class="alert alert-info">' + data['status'] + '</div>');							
					   }					   					  
					},
					error: function (jqXHR, textStatus, errorThrown)
					{						
						alert('Error adding / update data');									
					}
				});
			}
			
			function data_delete_bidang(id_users_bidang)
			{									
				var form_data = {
					id_users_bidang: id_users_bidang
				};
			
				//lakukan hapus data
				// ajax hapus data to database
				$.ajax({
					url : "<?php echo site_url('cms_users/data_delete_bidang')?>",
					type: "POST",
					data: form_data,
					dataType: "JSON",
					success: function(data)
					{
					   //if success close modal and reload ajax table
					   if(data['status'] == true){
							//berhasil simpan		
							show_add_bidang(false, '', '');													 				   		
							 $('#page_message_bidang').html('<div class="alert alert-info">Data berhasil di hapus.</div>');
							reload_table_bidang();
					   }else{
							//form validation
							show_add_bidang(false, '', '');	
							$('#page_message_bidang').html('<div class="alert alert-info">' + data['status'] + '</div>');							
					   }					   					  
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
					<form class="form-horizontal" role="form" id="add_form" action="#" autocomplete="nope" >
					  <div class="modal-header">			                        
						<h4 class="modal-title">Tambah User</h4>
					  </div>
					  <div class="modal-body">									  		 											
							<input type="hidden" value="" name="id"/> 
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="nama">Nama</label>
							  <div class="col-lg-6">
								<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Lengkap">
							  </div>
							</div> 
							
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="nip">NIP</label>
							  <div class="col-lg-6">
								<input type="text" class="form-control" placeholder="Nomor Induk Pegawai" id="nip" name="nip">
							  </div>
							</div> 
							
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="email">Email</label>
							  <div class="col-lg-6">
								<input type="text" class="form-control" id="txt_email" name="txt_email" placeholder="Email">
							  </div>
							</div> 
							
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="username">Username</label>
							  <div class="col-lg-6">
								<input type="text" class="form-control" id="txt_username" name="txt_username" placeholder="Username" >
							  </div>
							</div> 
							
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="password">Password</label>
							  <div class="col-lg-6">
								<input type="password" class="form-control" id="txt_password" name="txt_password" placeholder="Password" >
							  </div>
							</div> 		
							
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="password_confirm">Ulangi Password</label>
							  <div class="col-lg-6">
								<input type="password" class="form-control" id="txt_password_confirm" name="txt_password_confirm" placeholder="Password">
							  </div>
							</div> 	
																					
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="users_groups">User Group</label>
							  <div class="col-lg-6">
									<select name="users_groups" id="users_groups" class="form-control">
										<option value="0" >--Pilih--</option>
									</select>
							  </div>
							</div>
							
							<div id="modal_message"></div>
					  </div>	<!--END modal-body-->
					  <div class="modal-footer">										
						<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>	
						<button type="button" id="btnSave" onClick="data_save()" class="btn btn-sm btn-success">Simpan</button>								
					  </div>
				  </form>
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
						<h4 class="modal-title">Hapus Data</h4>
					  </div>
					  <div class="modal-body">
					  		<input type="hidden" value="" name="id_delete_user"/> 																					
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
		
		<!-- Modal BEGIN:USERS GROUP-->										
		<div id="modalUsersGroup" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">									
					<!-- Form starts.  -->	
					<form class="form-horizontal" role="form" id="users_group_form" action="#">
					  <div class="modal-header">			                        
						<h4 class="modal-titlef">Group User</h4>
					  </div>
					  <div class="modal-body">
					  		<input type="hidden" value="" name="id_user_for_ug" id="id_user_for_ug"/> 																					
							<div class="form-group" align="left">
								<div class="col-lg-12">
									<div id="users_group_text"></div>																	  
								</div>																							
							</div> 
							
							<div class="form-group" align="center">
								<div class="col-lg-12">
									<div id="select_user_group" align="left"></div>									
								</div>	
							</div> 
							 <div id="modal_users_group_message"></div>
					  </div>	<!--END modal-body-->
					  <div class="modal-footer">										
						<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>	
						<button type="button" id="btnSimpanUg" onClick="data_edit_user_group('','')" class="btn btn-sm btn-success">Simpan</button>								
					  </div>
				  </form>
				</div>	<!--END modal-content-->
			</div>	<!--END modal-dialog-->
		</div>
		<!-- Modal END:USERS GROUP-->
		
		<!-- Modal BEGIN:FUNGSIONAL-->										
		<div id="modalFungsional" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">	
					<div class="modal-header">			                        
						<h4 class="modal-title-fungsional">Fungsional</h4>
					  </div>
					  <div class="modal-body">		
					  	<div class="row" >
							<div class="col-lg-12">
								<div id="fungsional_text" align="left"></div>
							</div>
						</div>
						<div id="page_message_fungsional"></div>
						<br  />								
						<div class="row" id="div_AddFungsional" style="display:none">
							<div class="col-lg-12">
								<!-- Form starts.  -->
								<form class="form-horizontal" role="form"  id="fungsional_form" action="#">						  							  		 											
									<input type="hidden" value="" name="id_user_for_fungsional" id="id_user_for_fungsional"/> 
									<input type="hidden" value="" name="id_users_fungsional" id="id_users_fungsional"/>
										
									<div class="form-group">
									  <label class="col-lg-3 control-label" for="fungsional_name">Fungsional</label>
									  <div class="col-lg-6">
											<select name="fungsional_name" id="fungsional_name" class="form-control">
												<option value="-1" >-- Pilih --</option>
											</select>
									  </div>
									</div> 
									
									<div class="form-group">
									  <label class="col-lg-3 control-label" for="fungsional_tahun_awal">Tahun Awal</label>
									  <div class="col-lg-4">
										 <select name="fungsional_tahun_awal" id="fungsional_tahun_awal" class="form-control">
												<option value="-1" >-- Pilih --</option>
											</select>
									  </div>
									</div> 
									
									<div class="form-group">
									  <label class="col-lg-3 control-label" for="fungsional_tahun_akhir">Tahun Akhir</label>
									  <div class="col-lg-4">
										 <select name="fungsional_tahun_akhir" id="fungsional_tahun_akhir" class="form-control">
												<option value="-1" >-- Pilih --</option>
											</select>
									  </div>
									</div> 
									 <div id="modal_fungsional_message"></div>
									 <label class="col-lg-3 control-label" ></label>
									 <div class="form-group">							  
									  <div class="col-lg-4">
									  		<button type="button" onClick="show_add_fungsional(false,'','')" class="btn btn-sm btn-success">Batal</button>	
											<button type="button" onClick="data_save_fungsional()" class="btn btn-sm btn-success">Simpan</button>	
									  </div>
									</div> 
								</form>	
							</div>
						</div>		
						 						
						<button type="button" id="btnAddFungsional" onClick="show_add_fungsional(true,'add','')" class="btn btn-sm btn-success" >Tambah Fungsional</button>	
						<br />
						<br />
						<div class="row">
							<div class="col-lg-12">
								<!-- Table Page -->
								<div class="page-tables">
									<!-- Table -->
									<div class="table-responsive">
										<table class="table-hover table-bordered" cellpadding="0" cellspacing="0" border="0" id="table_fungsional" width="100%">
											<thead style="background-color:#006699; color:#FFFFFF;" align="center">
												<tr>
												  <th style="max-width:60px"><b>No</b></th>
												  <th ><b>Fungsional</b></th>
												  <th style="max-width:140px"><b>Tahun Awal</b></th>
												  <th style="max-width:140px"><b>Tahun Akhir</b></th>
												  <th style="max-width:100px"><b>Aksi</b></th>
												</tr>
											</thead>													
										</table>						
										<div class="clearfix"></div>									
									</div>
								</div>
								<!-- Table Page -->
							</div>					
						</div>
						
					  </div>	<!--END modal-body-->
					  <div class="modal-footer">										
						<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>														
					  </div>
				  			  
				</div>	<!--END modal-content-->
			</div>	<!--END modal-dialog-->
		</div>
		<!-- Modal END:FUNGSIONAL-->
		
		<!-- Modal BEGIN:STRUKTURAL-->										
		<div id="modalStruktural" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">	
					<div class="modal-header">			                        
						<h4 class="modal-title-struktural">Struktural</h4>
					  </div>
					  <div class="modal-body">		
					  	<div class="row" >
							<div class="col-lg-12">
								<div id="struktural_text" align="left"></div>
							</div>
						</div>
						<div id="page_message_struktural"></div>
						<br  />								
						<div class="row" id="div_AddStruktural" style="display:none">
							<div class="col-lg-12">
								<!-- Form starts.  -->
								<form class="form-horizontal" role="form"  id="struktural_form" action="#">						  							  		 											
									<input type="hidden" value="" name="id_user_for_struktural" id="id_user_for_struktural"/> 
									<input type="hidden" value="" name="id_users_struktural" id="id_users_struktural"/>
										
									<div class="form-group">
									  <label class="col-lg-3 control-label" for="struktural_name">Struktural</label>
									  <div class="col-lg-6">
											<select name="struktural_name" id="struktural_name" class="form-control">
												<option value="-1" >-- Pilih --</option>
											</select>
									  </div>
									</div> 
									
									<div class="form-group">
									  <label class="col-lg-3 control-label" for="struktural_tahun_awal">Tahun Awal</label>
									  <div class="col-lg-4">
										 <select name="struktural_tahun_awal" id="struktural_tahun_awal" class="form-control">
												<option value="-1" >-- Pilih --</option>
											</select>
									  </div>
									</div> 
									
									<div class="form-group">
									  <label class="col-lg-3 control-label" for="struktural_tahun_akhir">Tahun Akhir</label>
									  <div class="col-lg-4">
										 <select name="struktural_tahun_akhir" id="struktural_tahun_akhir" class="form-control">
												<option value="-1" >-- Pilih --</option>
											</select>
									  </div>
									</div> 
									 <div id="modal_struktural_message"></div>
									 <label class="col-lg-3 control-label" ></label>
									 <div class="form-group">							  
									  <div class="col-lg-4">
									  		<button type="button" onClick="show_add_struktural(false,'','')" class="btn btn-sm btn-success">Batal</button>	
											<button type="button" onClick="data_save_struktural()" class="btn btn-sm btn-success">Simpan</button>	
									  </div>
									</div> 
								</form>	
							</div>
						</div>		
						 						
						<button type="button" id="btnAddStruktural" onClick="show_add_struktural(true,'add','')" class="btn btn-sm btn-success" >Tambah Struktural</button>	
						<br />
						<br />
						<div class="row">
							<div class="col-lg-12">
								<!-- Table Page -->
								<div class="page-tables">
									<!-- Table -->
									<div class="table-responsive">
										<table class="table-hover table-bordered" cellpadding="0" cellspacing="0" border="0" id="table_struktural" width="100%">
											<thead style="background-color:#006699; color:#FFFFFF;" align="center">
												<tr>
												  <th style="max-width:60px"><b>No</b></th>
												  <th ><b>Struktural</b></th>
												  <th style="max-width:140px"><b>Tahun Awal</b></th>
												  <th style="max-width:140px"><b>Tahun Akhir</b></th>
												  <th style="max-width:100px"><b>Aksi</b></th>
												</tr>
											</thead>													
										</table>						
										<div class="clearfix"></div>									
									</div>
								</div>
								<!-- Table Page -->
							</div>					
						</div>
						
					  </div>	<!--END modal-body-->
					  <div class="modal-footer">										
						<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>														
					  </div>
				  			  
				</div>	<!--END modal-content-->
			</div>	<!--END modal-dialog-->
		</div>
		<!-- Modal END:STRUKTURAL-->
		
		<!-- Modal BEGIN:BIDANG-->										
		<div id="modalBidang" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">	
					<div class="modal-header">			                        
						<h4 class="modal-title-bidang">Bidang</h4>
					  </div>
					  <div class="modal-body">		
					  	<div class="row" >
							<div class="col-lg-12">
								<div id="bidang_text" align="left"></div>
							</div>
						</div>
						<div id="page_message_bidang"></div>
						<br  />								
						<div class="row" id="div_AddBidang" style="display:none">
							<div class="col-lg-12">
								<!-- Form starts.  -->
								<form class="form-horizontal" role="form"  id="bidang_form" action="#">						  							  		 											
									<input type="hidden" value="" name="id_user_for_bidang" id="id_user_for_bidang"/> 
									<input type="hidden" value="" name="id_users_bidang" id="id_users_bidang"/>
										
									<div class="form-group">
									  <label class="col-lg-3 control-label" for="bidang_name">Bidang</label>
									  <div class="col-lg-6">
											<select name="bidang_name" id="bidang_name" class="form-control">
												<option value="-1" >-- Pilih --</option>
											</select>
									  </div>
									</div> 
									
									<div class="form-group">
									  <label class="col-lg-3 control-label" for="bidang_tahun_awal">Tahun Awal</label>
									  <div class="col-lg-4">
										 <select name="bidang_tahun_awal" id="bidang_tahun_awal" class="form-control">
												<option value="-1" >-- Pilih --</option>
											</select>
									  </div>
									</div> 
									
									<div class="form-group">
									  <label class="col-lg-3 control-label" for="bidang_tahun_akhir">Tahun Akhir</label>
									  <div class="col-lg-4">
										 <select name="bidang_tahun_akhir" id="bidang_tahun_akhir" class="form-control">
												<option value="-1" >-- Pilih --</option>
											</select>
									  </div>
									</div> 
									 <div id="modal_bidang_message"></div>
									 <label class="col-lg-3 control-label" ></label>
									 <div class="form-group">							  
									  <div class="col-lg-4">
									  		<button type="button" onClick="show_add_bidang(false,'','')" class="btn btn-sm btn-success">Batal</button>	
											<button type="button" onClick="data_save_bidang()" class="btn btn-sm btn-success">Simpan</button>	
									  </div>
									</div> 
								</form>	
							</div>
						</div>		
						 						
						<button type="button" id="btnAddBidang" onClick="show_add_bidang(true,'add','')" class="btn btn-sm btn-success" >Tambah Bidang</button>	
						<br />
						<br />
						<div class="row">
							<div class="col-lg-12">
								<!-- Table Page -->
								<div class="page-tables">
									<!-- Table -->
									<div class="table-responsive">
										<table class="table-hover table-bordered" cellpadding="0" cellspacing="0" border="0" id="table_bidang" width="100%">
											<thead style="background-color:#006699; color:#FFFFFF;" align="center">
												<tr>
												  <th style="max-width:60px"><b>No</b></th>
												  <th ><b>Bidang</b></th>
												  <th style="max-width:140px"><b>Tahun Awal</b></th>
												  <th style="max-width:140px"><b>Tahun Akhir</b></th>
												  <th style="max-width:100px"><b>Aksi</b></th>
												</tr>
											</thead>													
										</table>						
										<div class="clearfix"></div>									
									</div>
								</div>
								<!-- Table Page -->
							</div>					
						</div>
						
					  </div>	<!--END modal-body-->
					  <div class="modal-footer">										
						<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>														
					  </div>
				  			  
				</div>	<!--END modal-content-->
			</div>	<!--END modal-dialog-->
		</div>
		<!-- Modal END:BIDANG-->
														
    </div><!-- Mainbar ends -->   
   <div class="clearfix"></div>

</div>
<!-- Content ends -->	
<!-- End CONTENT ===============================================-->
<?PHP $this->load->view('footer'); ?>	
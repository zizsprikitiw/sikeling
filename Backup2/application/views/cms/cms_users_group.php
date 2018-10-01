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
				<button class="btn btn-success" onClick="data_add()"><i class="fa fa-plus"></i> Tambah User Group</button>
				<br />
				<br />
				<!-- Table Page -->
				<div class="page-tables">
					<!-- Table -->
					<div class="table-responsive">
						<table class="table-hover table-bordered" cellpadding="0" cellspacing="0" border="0" id="table" width="100%">
							<thead style="background-color:#006699; color:#FFFFFF;">
								<tr>
								  <th width="70px" style="max-width:70px"><b>No</b></th>
								  <th width="200px"><b>User Group</b></th>
								  <th ><b>Keterangan</b></th>								  								  							  							  							      <th width="120px" style="max-width:120px"><b>Aksi</b></th>
								</tr>
							</thead>													
						</table>						
						<div class="clearfix"></div>									
					</div>
				</div>
				<!-- Table Page -->								
				
				<hr style="border-top: 1px solid #ccc;border-bottom: 1px solid #fff;" />
				
				<div class="row">
					<div class="col-md-7">
						<div class="form-group">
							<label class="control-label col-lg-3">User Group:</label>
							<div class="col-lg-8">                               
								<select name="filter_user_group" id="filter_user_group" class="form-control">
									<option value="0" >-- Pilih --</option>
								</select> 
							</div>
					  </div>										  												
					</div>
					
					<div class="col-md-2" align="right">
						<!-- Button to trigger modal -->
						<button class="btn btn-success" onClick="data_modal_personil()"><i class="fa fa-plus"></i> Tambah Personil</button>	
					</div>									
				</div>
				
				<!-- Table Page -->
				<div class="page-tables" >
					<!-- Table -->
					<div class="table-responsive">
						<table class="table-hover table-bordered " cellpadding="0" cellspacing="0" border="0" id="table_group_personil" width="100%">
							<thead style="background-color:#006699; color:#FFFFFF;">
								<tr>
								  <th style="max-width:70px"><b>No</b></th>
								  <th ><b>Nama</b></th>								  								  
								  <th style="max-width:60px"></th>								  
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
						url : "<?php echo site_url('cms_users_group/data_init/')?>" ,
						type: "POST",
						dataType: "JSON",
						success: function(data)
						{
							var item_sel=["filter_user_group"];
							var item_select = {"filter_user_group":-1};															
							select_box(data,item_select, item_sel);			
							reload_table_group_personil();																	
						},
						error: function (jqXHR, textStatus, errorThrown)
						{
							alert('Error get data from ajax');
						}
					});	
			}
			
			
			$(document).ready(function() {	
				load_select_filter();
				
				$('#filter_user_group').on('change',function(){					
					reload_table_group_personil();
				});		
					
				//load data table																
			 	table = $('#table').DataTable({ 			
					"processing": true, //Feature control the processing indicator.
					"serverSide": true, //Feature control DataTables' server-side processing mode.
					
					// Load data for the table's content from an Ajax source
					"ajax": {
						"url": "<?php echo site_url('cms_users_group/data_list')?>",
						"type": "POST"
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
				
				table_group_personil = $('#table_group_personil').DataTable({ 			
					"processing": true, //Feature control the processing indicator.
					"serverSide": true, //Feature control DataTables' server-side processing mode.
					
					// Load data for the table's content from an Ajax source
					"ajax": {
						"url": "<?php echo site_url('cms_users_group/data_list_group_personil')?>",
						"type": "POST",						
						"data": function ( d ) {
								var item_selectbox = document.getElementById('filter_user_group');
								d.filter_user_group = item_selectbox.options[item_selectbox.selectedIndex].value;								
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
								
				table_personil = $('#table_personil').DataTable({ 			
					"processing": true, //Feature control the processing indicator.
					"serverSide": true, //Feature control DataTables' server-side processing mode.
					
					
					// Load data for the table's content from an Ajax source
					"ajax": {
						"url": "<?php echo site_url('cms_users_group/data_list_personil')?>",
						"type": "POST",
						"data":function ( d ) {
								var item_tab = document.getElementById('id_user_groups_personil');
								d.id_user_groups = item_tab.value;
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
				
				//load data table														
				table_menu = $('#table_menus').DataTable({ 			
					"processing": true, //Feature control the processing indicator.
					"serverSide": true, //Feature control DataTables' server-side processing mode.												
					"deferLoading": 0, // here	
					"paging": false,						
					"ordering": false,
					"bFilter": false,
					// Load data for the table's content from an Ajax source
					"ajax": {
						"url": "<?php echo site_url('cms_users_group/menu_list/')?>",
						"type": "POST",
						"data":function ( d ) {
								var item_tab = document.getElementById('id_user_groups');
								d.id_user_groups = item_tab.value;			//document.getElementById('id_tabs').value					
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
		
			function data_add()
			{
			  save_method = 'add';
			  $('#add_form')[0].reset(); // reset form on modals		  
		      $('#modal_message').html('');  //reset message	
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
					url : "<?php echo site_url('cms_users_group/data_edit/')?>/" + id,
					type: "GET",
					dataType: "JSON",
					success: function(data)
					{				   
						$('[name="id"]').val(data['list'].id);  
						$('[name="name"]').val(data['list'].name);
						$('[name="description"]').val(data['list'].description);																	
								
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
						
			function reload_table_group_personil()
			{
			  table_group_personil.ajax.reload(null,false); //reload datatable ajax 
			}
			
			function reload_table_personil()
			{
			  table_personil.ajax.reload(null,false); //reload datatable ajax 
			}
			
			function data_save()
			{
			  var url;
			  if(save_method == 'add') 
			  {
				  url = "<?php echo site_url('cms_users_group/data_save_add')?>";
			  }
			  else
			  {
				url = "<?php echo site_url('cms_users_group/data_save_edit')?>";
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
					$('#delete_text').html('<b >Hapus data ' + nama_id + '</b>');	
					$('#modalDeleteForm').modal('show'); // show bootstrap modal when complete loaded
					$('.modal-title').text('Hapus Data'); // Set Title to Bootstrap modal title	
				}else{
					//lakukan hapus data
					// ajax hapus data to database
					$.ajax({
						url : "<?php echo site_url('cms_users_group/data_delete')?>/",
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
			
			function reload_table_menu()
			{
			  table_menu.ajax.reload(null,false); //reload datatable ajax 
			} 			
			
			function groups_menu(groups_id, groups_name)
			{
				document.getElementById('id_user_groups').value = groups_id;
				reload_table_menu(); 
				$('#modalMenus').modal('show'); // show bootstrap modal when complete loaded
				$('.modal-title2').text('Menu '+groups_name); // Set Title to Bootstrap modal title					
			}		
						
			function data_edit_status(menu_id, groups_menu_id)
			{
				var form_data = {
						id_user_groups: document.getElementById('id_user_groups').value,
						id_menu: menu_id,
						id_groups_menu: groups_menu_id					
					};
					
				$.ajax({
						url : "<?php echo site_url('cms_users_group/data_edit_status')?>",
						type: "POST",
						dataType: "JSON",
						data: form_data,
						success: function(data)
						{
						   //if success close modal and reload ajax table
						   if(data['status'] == true){
								//berhasil simpan							
								 $('#modal_menu_message').html('<div class="alert alert-info">Berhasil ubah status.</div>');
								reload_table_menu();
						   }else{
								$('#modal_menu_message').html('<div class="alert alert-info">Gagal ubah status.</div>');						
						   }					   					  
						},
						error: function (jqXHR, textStatus, errorThrown)
						{						
							alert('Error adding / update data');									
						}
					});	
			}							
						
			function data_modal_personil()
			{							  
				var item_selectbox = document.getElementById('filter_user_group');								
				document.getElementById('id_user_groups_personil').value = item_selectbox.options[item_selectbox.selectedIndex].value;
				//reload_table_personil(); 
				$('#modalPersonil').modal('show'); // show bootstrap modal when complete loaded
			}
						
			function data_add_personil(user_id, user_groups_id)
			{				
				var form_data = {
						user_id: user_id,
						user_groups_id: user_groups_id					
					};
					
				$.ajax({
						url : "<?php echo site_url('cms_users_group/data_tambah_personil_group')?>",
						type: "POST",
						dataType: "JSON",
						data: form_data,
						success: function(data)
						{
						   //if success close modal and reload ajax table
						   if(data['status'] == true){
								//berhasil simpan							
								 //$('#modal_menu_message').html('<div class="alert alert-info">Berhasil ubah status.</div>');
								 $('#modalPersonil').modal('hide');									 
								reload_table_group_personil();
						   }else{
								//$('#modal_menu_message').html('<div class="alert alert-info">Gagal ubah status.</div>');						
						   }					   					  
						},
						error: function (jqXHR, textStatus, errorThrown)
						{						
							alert('Error adding / update data');									
						}
					});	
			}	
						
			function data_delete_personil(id, nama_id)
			{				
				if(id != ''){
					//show modal confirmation
					$('#delete_form_personil')[0].reset(); // reset form on modals
					$('#modal_delete_message_personil').html('');  //reset message
					
					$('[name="id_delete_data_personil"]').val(id);
					$('#delete_text_personil').html('<b >Hapus data ' + nama_id + '</b>');	
					$('#modalDeletePersonil').modal('show'); // show bootstrap modal when complete loaded
					//$('.modal-title').text('Hapus Data'); // Set Title to Bootstrap modal title	
				}else{					
					//lakukan hapus data
					// ajax hapus data to database
					$.ajax({
						url : "<?php echo site_url('cms_users_group/data_delete_personil')?>",
						type: "POST",
						data: $('#delete_form_personil').serialize(),
						dataType: "JSON",
						success: function(data)
						{
						   //if success close modal and reload ajax table
						   if(data['status'] == true){
								//berhasil simpan							
								 $('#modalDeletePersonil').modal('hide');					   		
								 $('#page_message').html('<div class="alert alert-info">Data berhasil di hapus.</div>');
								reload_table_group_personil();
						   }else{
								//form validation
								$('#modal_delete_message_personil').html('<div class="alert alert-info">' + data['status'] + '</div>');							
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
							<input type="hidden" value="" name="id"/> 
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="name">User Group</label>
							  <div class="col-lg-6">
								<input type="text" class="form-control" id="name" name="name" placeholder="Nama User Group">
							  </div>
							</div> 
							
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="description">Keterangan</label>
							  <div class="col-lg-6">
								<input type="text" class="form-control" placeholder="Keterangan User Group" id="description" name="description">
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
		
		<!-- Modal BEGIN:MENUS-->
		<div id="modalMenus" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">					
									
					  <div class="modal-header">			                        
						<h4 class="modal-title2">Menu</h4>
					  </div>
					  <div class="modal-body">
					  		 <div id="modal_menu_message"></div>
					  		<input type="hidden" value="" name="id_user_groups" id="id_user_groups"/> 									  		 											
							<!-- Table Page -->
							<div class="page-tables">
								<!-- Table -->
								<div class="table-responsive">
									<table class="table-hover table-bordered" cellpadding="0" cellspacing="0" border="0" id="table_menus" width="100%">
										<thead style="background-color:#006699; color:#FFFFFF;">
											<tr>
											  <th width="60px" style="max-width:60px"><b>No</b></th>
											  <th ><b>Menu</b></th>
											  <th width="80px" style="max-width:80px"><b>Pilih</b></th>
											</tr>
										</thead>													
									</table>						
									<div class="clearfix"></div>									
								</div>
							</div>
							<!-- Table Page -->
																					
					  </div>	<!--END modal-body-->
					  <div class="modal-footer">										
						<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>	
					  </div>
					  
				</div>	<!--END modal-content-->
			</div>	<!--END modal-dialog-->
		</div>
		<!-- Modal END:MENUS-->
		
		<!-- Modal BEGIN:PERSONIL-->
		<div id="modalPersonil" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">					
									
					  <div class="modal-header">			                        
						<h4 class="modal-title3">Personil</h4>
					  </div>
					  <div class="modal-body">
					  		 <div id="modal_menu_message"></div>
					  		<input type="hidden" value="" name="id_user_groups_personil" id="id_user_groups_personil"/> 									  		 											
							<!-- Table Page -->
							<div class="page-tables">
								<!-- Table -->
								<div class="table-responsive">
									<table class="table-hover table-bordered" cellpadding="0" cellspacing="0" border="0" id="table_personil" width="100%">
										<thead style="background-color:#006699; color:#FFFFFF;">
											<tr>
											  <th width="60px" style="max-width:60px"><b>No</b></th>
											  <th ><b>Nama</b></th>
											  <th width="80px" style="max-width:80px"><b>Pilih</b></th>
											</tr>
										</thead>													
									</table>						
									<div class="clearfix"></div>									
								</div>
							</div>
							<!-- Table Page -->
																					
					  </div>	<!--END modal-body-->
					  <div class="modal-footer">										
						<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>	
					  </div>
					  
				</div>	<!--END modal-content-->
			</div>	<!--END modal-dialog-->
		</div>
		<!-- Modal END:PERSONIL-->
		
		<!-- Modal BEGIN:DELETE DATA-->										
		<div id="modalDeletePersonil" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">									
					<!-- Form starts.  -->	
					<form class="form-horizontal" role="form" id="delete_form_personil" action="#">
					  <div class="modal-header">			                        
						<h4 class="modal-title6">Hapus Data</h4>
					  </div>
					  <div class="modal-body">
					  		<input type="hidden" value="" name="id_delete_data_personil"/> 																					
							<div class="form-group" align="center">
								<div class="col-lg-12">
									<div id="delete_text_personil"></div>																	  
								</div>																							
							</div> 
							<div class="form-group" align="center">
								<div class="col-lg-12">
									<b >Anda yakin ?!</b>	
								</div>	
							</div> 
							 <div id="modal_delete_message_personil"></div>
					  </div>	<!--END modal-body-->
					  <div class="modal-footer">										
						<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>	
						<button type="button" id="btnDelete" onClick="data_delete_personil('','')" class="btn btn-sm btn-success">Hapus</button>								
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
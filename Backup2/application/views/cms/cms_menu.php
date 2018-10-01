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
					<div class="col-md-2" >
						<!-- Button to trigger modal -->
						<button class="btn btn-success" onClick="data_add()"><i class="fa fa-plus"></i> Tambah Menu</button>	
					</div>									
				</div>
				<hr />
				
				<!-- Table Page -->
				<div class="page-tables">
					<!-- Table -->
					<div class="table-responsive">
						<table class="table-hover table-bordered" cellpadding="0" cellspacing="0" border="0" id="table" width="100%">
							<thead style="background-color:#006699; color:#FFFFFF;" >
								<tr>
								  <th width="60px"><b>No</b></th>
								  <th ><b>Menu</b></th>
								  <th ><b>Link</b></th>
								  <th width="80px"><b>Tampil</b></th>
								  <th width="160px"><b>Aksi</b></th>
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
			var id_tab_icon;	
			
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
						
						htmlString = htmlString+ "<Option value="+data[item_sel[i]][j].id_item+" "+selected_str+">"+data[item_sel[i]][j].nama_item+"</option>";																			
					}
					sel.html(htmlString);	
				}	
			}
			
			//function create lecect box
			function radio_box(data,item_select,item_sel,is_button)
			{					
				//insert select item
				//item_sel = nama div				
				var len_item = item_sel.length;
				select_val = -1;
				for(var i=0; i<len_item; i++){
					//get id selected					
					for(var key in item_select){
						if(key == item_sel[i]){
							select_val = item_select[key];
						} 
					}
										
					var sel = $("#div_"+item_sel[i]);						
					sel.empty();					
					var len_sub = data[item_sel[i]].length;
					htmlString = "";				
					for(var j=0; j<len_sub; j++){
						if((select_val == -1) & (j==0)){
							selected_str = 'checked="checked"';
						}else if(data[item_sel[i]][j].id_item == select_val){
							selected_str = 'checked="checked"';
						}else{
							selected_str = "";
						}
						
						if(is_button == true){
							//button menu
							htmlString = htmlString+ '<div class="radio"><label class="btn btn-xs '+data[item_sel[i]][j].class+'"><input type="radio" name="'+item_sel[i]+'" id="'+item_sel[i]+'" value="'+data[item_sel[i]][j].id_item+'" '+selected_str+'>'+data[item_sel[i]][j].nama_item+'</label></div>';
						}else{
							htmlString = htmlString+ '<div class="radio"><label><input type="radio" name="'+item_sel[i]+'" id="'+item_sel[i]+'" value="'+data[item_sel[i]][j].id_item+'" '+selected_str+'>'+data[item_sel[i]][j].nama_item+'</label></div>';
						}													
					}
					sel.html(htmlString);	//print item
					
				}//end for: item	
			}
						
			$(document).ready(function() {											 										
				//load data table														
				table = $('#table').DataTable({ 			
					"processing": true, //Feature control the processing indicator.
					"serverSide": true, //Feature control DataTables' server-side processing mode.							
					"paging": false,	//"deferLoading": 0, // here		
					"ordering": false,
					"bFilter": false,
					// Load data for the table's content from an Ajax source
					"ajax": {
						"url": "<?php echo site_url('cms_menu/data_list/')?>",
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
				
				//load data table														
				table_icons = $('#table_icons').DataTable({ 			
					"processing": true, //Feature control the processing indicator.
					"serverSide": true, //Feature control DataTables' server-side processing mode.												
					"deferLoading": 0, // here							
					"ordering": false,
					"bFilter": false,
					// Load data for the table's content from an Ajax source
					"ajax": {
						"url": "<?php echo site_url('cms_menu/icon_list/')?>",
						"type": "POST",
						"data":function ( d ) {
								var item_tab = document.getElementById('id_tabs');
								d.id_tabs = item_tab.value;			//document.getElementById('id_tabs').value					
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
												
					
			function data_add()
			{
			  save_method = 'add';
			  $('#add_form')[0].reset(); // reset form on modals		  
			  $('#modal_message').html('');  //reset message					 			  
			  document.getElementById('ref_id').disabled = false;
			  document.getElementById('url').disabled = false;
			  
			  //Ajax Load data from ajax
			  $.ajax({
					url : "<?php echo site_url('cms_menu/data_add/')?>" ,
					type: "POST",
					dataType: "JSON",
					success: function(data)
					{
						var item_sel=["ref_id"];
						var item_select = {"ref_id":-1};															
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
			  document.getElementById('ref_id').disabled = true;
			  document.getElementById('url').disabled = false;
			  			  			
			  //Ajax Load data from ajax
			  $.ajax({			  		
					url : "<?php echo site_url('cms_menu/data_edit/')?>/" + id,
					type: "GET",
					dataType: "JSON",
					success: function(data)
					{				   
						$('[name="id"]').val(data['list'].id);  
						$('[name="nama"]').val(data['list'].nama);
						$('[name="halaman"]').val(data['list'].halaman);
						$('[name="url"]').val(data['list'].url);
						$('[name="icon"]').val(data['list'].icon);
						$('[name="direct_url"]').val(data['list'].direct_url);
						
						//halaman index dan halaman proyek tidak bisa edit url
						if((data['list'].url == "index") | (data['list'].url == "proyek")){
							document.getElementById('url').disabled = true;
						}
																		
						if(data['list'].ref_id == null){
							//judul utama							
							var item_sel=["ref_id"];
							var item_select = {"ref_id":-1};															
							select_box(data,item_select, item_sel);								
						}else{
							//sub judul dari							
							var item_sel=["ref_id"];
							var item_select = {"ref_id":data['list'].ref_id};															
							select_box(data,item_select, item_sel);	
						}

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
			  document.getElementById('ref_id').disabled = false;	
			  document.getElementById('url').disabled = false;
			  
			  if(save_method == 'add') 
			  {
				  url = "<?php echo site_url('cms_menu/data_save_add')?>";
			  }
			  else
			  {
				url = "<?php echo site_url('cms_menu/data_save_edit')?>";
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
						url : "<?php echo site_url('cms_menu/data_delete')?>/",
						type: "POST",
						data: $('#delete_form').serialize(),
						dataType: "JSON",
						success: function(data)
						{
						   //if success close modal and reload ajax table
						   if(data['status'] == true){
								//berhasil simpan
								//alert(data['row']);							
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
			
			function data_edit_posisi(id, pos)
			{
				var form_data = {
						id: id,
						pos: pos					
					};
					
				$.ajax({
						url : "<?php echo site_url('cms_menu/data_edit_posisi')?>",
						type: "POST",
						dataType: "JSON",
						data: form_data,
						success: function(data)
						{
						   //if success close modal and reload ajax table
						   if(data['status'] == true){
								//berhasil simpan							
								 $('#page_message').html('<div class="alert alert-info">Berhasil ubah posisi.</div>');
								reload_table();
						   }else{
								$('#page_message').html('<div class="alert alert-info">Gagal ubah posisi.</div>');						
						   }					   					  
						},
						error: function (jqXHR, textStatus, errorThrown)
						{						
							alert('Error adding / update data');									
						}
					});	
			}	
			
			function data_edit_status(id)
			{
				$.ajax({
					url : "<?php echo site_url('cms_menu/data_edit_status')?>/"+id,
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
			
			function load_icon_list(id)
			{
				document.getElementById('id_tabs').value = id;
				table_icons.ajax.reload(null,false); //reload datatable ajax 
			}
			
			function show_icon()
			{
				$.ajax({
					url : "<?php echo site_url('cms_menu/load_tab_icon')?>",
					type: "POST",
					dataType: "JSON",
					success: function(data)
					{																		
						var sel = $("#myTab");						
						sel.empty();					
						var len_sub = data['icon_tabs'].length;
						htmlString = "";					
						for(var j=0; j<len_sub; j++){
							if((j==0)){
								selected_str = "class='active'";							
							}else{
								selected_str = "";
							}
							
							htmlString = htmlString+ '<li '+selected_str+'><a href="javascript:void()" title="'+data['icon_tabs'][j].nama_item+'" data-toggle="tab" onclick="load_icon_list('+"'"+data['icon_tabs'][j].id_item+"'"+')">'+data['icon_tabs'][j].nama_item+'</a></li>'
						}
						sel.html(htmlString);	
						
						load_icon_list('12');
												
						$('#modalIcons').modal('show'); // show bootstrap modal when complete loaded					   				   					  
					},
					error: function (jqXHR, textStatus, errorThrown)
					{						
						alert('Error adding / update data');									
					}
				});					

			}
			
			function add_sub_proyek(id)
			{
				save_method = 'add';
			  	$('#add_form_sub_proyek')[0].reset(); // reset form on modals		  
			  	$('#modal_message_sub').html('');  //reset message		
				$('[name="ref_id"]').val(id); 			 			  
			  
				//Ajax Load data from ajax
				  $.ajax({
						url : "<?php echo site_url('cms_menu/data_add_sub_proyek/')?>" ,
						type: "POST",
						dataType: "JSON",
						success: function(data)
						{
							var item_sel=["button_id"];
							var item_select = {"button_id":-1};															
							radio_box(data,item_select, item_sel, true);		
																			
							$('#modalAddSubProyek').modal('show'); // show bootstrap modal
							$('.modal-title').text('Tambah Menu Sub Proyek'); // Set Title to Bootstrap modal title					
						},
						error: function (jqXHR, textStatus, errorThrown)
						{
							alert('Error get data from ajax');
						}
					});		
			}
			
			function edit_sub_proyek(id)
			{
			  save_method = 'update';
			  $('#add_form_sub_proyek')[0].reset(); // reset form on modals
			  $('#modal_message_sub').html('');  //reset message				 
			  			  			
			  //Ajax Load data from ajax
			  $.ajax({			  		
					url : "<?php echo site_url('cms_menu/data_edit_sub_proyek/')?>/" + id,
					type: "GET",
					dataType: "JSON",
					success: function(data)
					{				   
						$('[name="id_sub_proyek"]').val(data['list'].id);  
						$('[name="nama"]').val(data['list'].nama);
						$('[name="halaman"]').val(data['list'].halaman);
						$('[name="url"]').val(data['list'].url);
						$('[name="icon"]').val(data['list'].icon);
						
						var item_sel=["button_id"];
						var item_select = {"button_id":data['list'].button_id};															
						radio_box(data,item_select, item_sel, true);																			

						$('#modalAddSubProyek').modal('show'); // show bootstrap modal when complete loaded
						$('.modal-title').text('Edit Menu Sub Proyek'); // Set title to Bootstrap modal title					
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						alert('Error get data from ajax');
					}
				});
			}
			
			function data_save_sub_proyek()
			{
			  var url;		
			  //button_id = $('input:radio[name=button_id]:checked').val();

			  if(save_method == 'add') 
			  {
				  url = "<?php echo site_url('cms_menu/data_save_add_sub_proyek')?>";
			  }
			  else
			  {
				url = "<?php echo site_url('cms_menu/data_save_edit_sub_proyek')?>";
			  }
					
			   // ajax adding data to database
				  $.ajax({
					url : url,
					type: "POST",
					data: $('#add_form_sub_proyek').serialize(),
					dataType: "JSON",
					success: function(data)
					{
					   //if success close modal and reload ajax table
					   if(data['status'] == true){
					   		//berhasil simpan													
							 $('#modalAddSubProyek').modal('hide');					   		
							 $('#page_message').html('<div class="alert alert-info">Data berhasil di simpan.</div>');
							reload_table();
					   }else{
					   		//form validation
							$('#modal_message_sub').html('<div class="alert alert-info">' + data['status'] + '</div>');							
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
					<form class="form-horizontal" role="form" id="add_form" action="#" autocomplete="nope">
					  <div class="modal-header">			                        
						<h4 class="modal-title">Tambah</h4>
					  </div>
					  <div class="modal-body">									  		 											
							<input type="hidden" value="" name="id"/> 							
							
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="nama">Menu</label>
							  <div class="col-lg-9">
								<input type="text" class="form-control" id="nama" name="nama" placeholder="Menu">
							  </div>							  
							</div>
							
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="singkatan">Halaman</label>
							  <div class="col-lg-9">
								<input type="text" class="form-control" id="halaman" name="halaman" placeholder="Judul Halaman">
							  </div>							  
							</div> 
							
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="url">Link Halaman</label>
							  <div class="col-lg-9">
								<input type="text" class="form-control" id="url" name="url" placeholder="Link Halaman (tanpa diawali dan diakhiri tanda /)">
							  </div>							  
							</div> 
							
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="ref_id">Sub Menu Dari</label>							  		
								<div class="col-lg-9">
									<select name="ref_id" id="ref_id" class="form-control">
										<option value="" >--Pilih--</option>
									</select>
								</div>							  										  
							</div>																	
							
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="icon">Icon</label>
							  <div class="col-lg-5">
								<input type="text" class="form-control" id="icon" name="icon" placeholder="Icon Menu Utama">
							  </div>
							  <div class="col-lg-4">
							  		<button type="button" id="btnIcon" onClick="show_icon()" class="btn btn-sm btn-success">Referensi</button>
							  </div>	  
							</div> 
							
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="direct_url">Direct URL</label>
							  <div class="col-lg-9">
								<input type="text" class="form-control" id="direct_url" name="direct_url" placeholder="Link Halaman Lengkap">
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
		
		<!-- Modal BEGIN:ADD DATA SUB PROYEK-->
		<div id="modalAddSubProyek" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<!-- Form starts.  -->
					<form class="form-horizontal" role="form" id="add_form_sub_proyek" action="#" autocomplete="nope">
					  <div class="modal-header">			                        
						<h4 class="modal-title">Tambah</h4>
					  </div>
					  <div class="modal-body">
					  		<input type="hidden" value="" name="ref_id"/>							  		 											
							<input type="hidden" value="" name="id_sub_proyek"/> 							
							
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="nama">Menu</label>
							  <div class="col-lg-9">
								<input type="text" class="form-control" id="nama" name="nama" placeholder="Menu">
							  </div>							  
							</div>
							
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="singkatan">Halaman</label>
							  <div class="col-lg-9">
								<input type="text" class="form-control" id="halaman" name="halaman" placeholder="Judul Halaman">
							  </div>							  
							</div> 
							
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="url">Link Halaman</label>
							  <div class="col-lg-9">
								<input type="text" class="form-control" id="url" name="url" placeholder="Link Halaman (tanpa diawali dan diakhiri tanda /)">
							  </div>							  
							</div> 
							
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="ref_id">Tombol Menu</label>							  		
								<div class="col-lg-9">
									<div id="div_button_id">
									</div>									
								</div>							  										  
							</div>																	
							
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="icon">Icon</label>
							  <div class="col-lg-5">
								<input type="text" class="form-control" id="icon" name="icon" placeholder="Icon Tombol">
							  </div>
							  <div class="col-lg-4">
							  		<button type="button" id="btnIcon" onClick="show_icon()" class="btn btn-sm btn-success">Referensi</button>
							  </div>	  
							</div> 														 
														
							<div id="modal_message_sub"></div>
					  </div>	<!--END modal-body-->
					  <div class="modal-footer">										
						<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>	
						<button type="button" id="btnSave" onClick="data_save_sub_proyek()" class="btn btn-sm btn-success">Simpan</button>								
					  </div>
				  </form>
				</div>	<!--END modal-content-->
			</div>	<!--END modal-dialog-->
		</div>
		<!-- Modal END:ADD DATA SUB PROYEK-->
		
		<!-- Modal BEGIN:ICONS-->
		<div id="modalIcons" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">					
									
					  <div class="modal-header">			                        
						<h4 class="modal-title2">Referensi Icon</h4>
					  </div>
					  <div class="modal-body">
					  		<input type="hidden" value="" name="id_tabs" id="id_tabs"/> 									  		 											
							<ul id="myTab" class="nav nav-tabs">
							  <li class="active"><a href="#home" data-toggle="tab">Home</a></li>
							  <li><a href="#profile" data-toggle="tab">Profile</a></li>
							  <li><a href="#cont" data-toggle="tab">Content</a></li>
							</ul>
							<div id="myTabContent" class="tab-content">
							  <div class="tab-pane active" >
								<!-- Table Page -->
								<div class="page-tables">
									<!-- Table -->
									<div class="table-responsive">
										<table class="table-hover table-bordered" cellpadding="0" cellspacing="0" border="0" id="table_icons" width="100%">
											<thead style="background-color:#006699; color:#FFFFFF;">
												<tr>
												  <th width="60px"><b>No</b></th>
												  <th ><b>Nama</b></th>
												  <th width="80px"><b>Icon</b></th>												  
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
		<!-- Modal END:ICONS-->
		
		
														
    </div><!-- Mainbar ends -->   
   <div class="clearfix"></div>

</div>
<!-- Content ends -->	
<!-- End CONTENT ===============================================-->
<?PHP $this->load->view('footer'); ?>	
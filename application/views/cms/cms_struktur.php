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
				
				<!--GROUP LEADER-->
				<div class="row">
					<div class="col-md-8" >
					
						<div class="widget">
							<!-- Widget title -->
							<div class="widget-head">
								<div class="pull-left">Group Leader</div>
								<div class="widget-icons pull-right">
									<button type="button" class="btn btn-sm btn-success" onClick="data_add('groups_leader')" id="btnAddGroupLeader"><i class="fa fa-plus"></i> Tambah Group Leader</button>
								</div>  
								<div class="clearfix"></div>
							</div>
							
							<div class="widget-content referrer" style="padding-top:5px; padding-bottom:0px; padding-left:2px; padding-right:2px;">
								<!-- Widget content -->
								
							  		<!-- Table Page -->
									<div class="page-tables">
										<!-- Table -->
										<div class="table-responsive">
											<table class="table-hover table-bordered" cellpadding="0" cellspacing="0" border="0" id="tableGroupLeader" width="100%">
												<thead style="background-color:#006699; color:#FFFFFF;">
													<tr>
													  <th width="60px"><b>No</b></th>
													  <th ><b>Nama</b></th>										  										 
													  <th width="100px"><b>Aksi</b></th>
													</tr>
												</thead>													
											</table>						
																			
										</div>
									</div>
									<!-- Table Page -->	
							 																 
							</div> <!-- END Widget content -->
										
						</div> <!-- END Widget -->
																														
					</div> <!-- col -->
				</div> <!-- row -->
				
				<hr style="border-top: 1px solid #ccc;border-bottom: 1px solid #fff;" />
				
				<!--LEADER-->
				<div class="row">
					<div class="col-md-12" >
					
						<div class="widget">
							<!-- Widget title -->
							<div class="widget-head">
								<div class="pull-left">Leader</div>
								<div class="widget-icons pull-right">
									<button type="button" class="btn btn-sm btn-success" onClick="data_add('leader')" id="btnAddLeader"><i class="fa fa-plus"></i> Tambah Leader</button>
								</div>  
								<div class="clearfix"></div>
							</div>
							
							<div class="widget-content referrer" style="padding-top:2px; padding-bottom:0px; padding-left:2px; padding-right:2px;">
								<!-- Widget content -->
							  	
								<!-- Table Page -->
								<div class="page-tables">
									<!-- Table -->
									<div class="table-responsive">
										<table class="table-hover table-bordered" cellpadding="0" cellspacing="0" border="0" id="tableLeader" width="100%">
											<thead style="background-color:#006699; color:#FFFFFF;">
												<tr>
												  <th width="60px"><b>No</b></th>
												  <th ><b>Nama</b></th>
												  <th width="40%" ><b>Group Leader</b></th>										  										
												  <th width="100px"><b>Aksi</b></th>
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
					 document.getElementById('btnAddGroupLeader').disabled = true;	
					 document.getElementById('btnAddLeader').disabled = true;							
				}else{
					document.getElementById('btnAddGroupLeader').disabled = false;
					document.getElementById('btnAddLeader').disabled = false;	
				}
				
				var form_data = {
					filter_judul: filter_judul					
				};
				
				$.ajax({
						url : "<?php echo site_url('cms_struktur/get_judul_proyek/')?>" ,
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
						url : "<?php echo site_url('cms_struktur/data_init/')?>" ,
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
						url : "<?php echo site_url('cms_struktur/select_sub_judul/')?>" ,
						type: "POST",
						dataType: "JSON",
						data: form_data,
						success: function(data)
						{
							var item_sel=["filter_judul"];
							var item_select = {"filter_judul":-1};															
							select_box(data,item_select, item_sel);	
							reload_table_group_leader();																														
						},
						error: function (jqXHR, textStatus, errorThrown)
						{
							alert('Error get data from ajax');
						}
					});	
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
						reload_table_group_leader();											
				});
				 										
				//load data table group leader														
				tableGroupLeader = $('#tableGroupLeader').DataTable({ 			
					"processing": true, //Feature control the processing indicator.
					"serverSide": true, //Feature control DataTables' server-side processing mode.
					"deferLoading": 0, // here	
					"paging": false,								
					"ordering": false,
					"bFilter": false,
					// Load data for the table's content from an Ajax source
					"ajax": {
						"url": "<?php echo site_url('cms_struktur/data_list_group_leader/')?>",
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
				
				//load data table leader														
				tableLeader = $('#tableLeader').DataTable({ 			
					"processing": true, //Feature control the processing indicator.
					"serverSide": true, //Feature control DataTables' server-side processing mode.
					"deferLoading": 0, // here									
					"ordering": false,
					"bFilter": false,
					// Load data for the table's content from an Ajax source
					"ajax": {
						"url": "<?php echo site_url('cms_struktur/data_list_leader/')?>",
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
				
		  	});//end document																
												
					
			function data_add(tbl_name)
			{
				  save_method = 'add';
				  $('#add_form')[0].reset(); // reset form on modals		  
				  $('#modal_message').html('');  //reset message
				  
				  var item_selectbox = document.getElementById('filter_judul');
				  project_id = item_selectbox.options[item_selectbox.selectedIndex].value;
						
				  document.getElementById('tbl_name').value = tbl_name;
				  document.getElementById('project_id').value = project_id;
				  
				  
				  if(tbl_name == 'groups_leader'){
						//group leader
						$('#lbl_nama').text('Nama Groups Leader');
						document.getElementById('nama').placeholder = 'Nama Groups Leader';	
						document.getElementById('div_groups_leader').style.display = "none";		
						
						$('#modalAddForm').modal('show'); // show bootstrap modal
						$('.modal-title').text('Tambah Data'); // Set Title to Bootstrap modal title												
				  }else{
						//leader
						$('#lbl_nama').text('Nama Leader');
						document.getElementById('nama').placeholder = 'Nama Leader';
						document.getElementById('div_groups_leader').style.display = "block";												
							
						var form_data = {
							project_id: project_id					
						};
										
						//Ajax Load data from ajax
					  $.ajax({
							url : "<?php echo site_url('cms_struktur/data_add_leader/')?>" ,
							type: "POST",
							dataType: "JSON",
							data: form_data,
							success: function(data)
							{
								var item_sel=["id_groups_leader"];
								var item_select = {"id_groups_leader":-1};															
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
			}
		
			function data_edit(id, tbl_name)
			{
			  save_method = 'update';
			  $('#add_form')[0].reset(); // reset form on modals
			  $('#modal_message').html('');  //reset message	
			  
			  var item_selectbox = document.getElementById('filter_judul');
			  project_id = item_selectbox.options[item_selectbox.selectedIndex].value;
					
			  document.getElementById('tbl_name').value = tbl_name;
			  document.getElementById('project_id').value = project_id;						 			  
				  
			   if(tbl_name == 'groups_leader'){
			   		//group leader
					$('#lbl_nama').text('Nama Groups Leader');
					document.getElementById('nama').placeholder = 'Nama Groups Leader';	
					document.getElementById('div_groups_leader').style.display = "none";
			   }else{
			   		//leader
					$('#lbl_nama').text('Nama Leader');
					document.getElementById('nama').placeholder = 'Nama Leader';
					document.getElementById('div_groups_leader').style.display = "block";
			   }			  			 
						
			  var form_data = {
					id: id,
					table_name:tbl_name,
					project_id: project_id					
				};
			  			
			  //Ajax Load data from ajax
			  $.ajax({			  		
					url : "<?php echo site_url('cms_struktur/data_edit/')?>",
					type: "POST",
					dataType: "JSON",
					data: form_data,
					success: function(data)
					{				   
						$('[name="id"]').val(data['list'].id);  
						$('[name="nama"]').val(data['list'].nama);
						$('[name="singkatan"]').val(data['list'].singkatan);
						
						if(tbl_name == 'leader'){
							var item_sel=["id_groups_leader"];
							var item_select = {"id_groups_leader":data['list'].id_groups_leader};															
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
		
			function reload_table_group_leader()
			{		
				get_judul_proyek();					  
				tableGroupLeader.ajax.reload(null,false); //reload datatable ajax 
				tableLeader.ajax.reload(null,false); //reload datatable ajax 
			}					
		
			function data_save()
			{
			  var url;
			  tbl_name = document.getElementById('tbl_name').value;
			  			  
			  if(save_method == 'add') 
			  {
				  url = "<?php echo site_url('cms_struktur/data_save_add')?>";
			  }
			  else
			  {
				url = "<?php echo site_url('cms_struktur/data_save_edit')?>";
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
							 
							 if(data['table_name'] == 'groups_leader'){
							 	tableGroupLeader.ajax.reload(null,false); //reload datatable ajax 				
							 }else{
							 	tableLeader.ajax.reload(null,false); //reload datatable ajax 
							 }							 							
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
			
			function data_delete(id, nama_id, tbl_name)
			{
				if(id != ''){
					//show modal confirmation
					$('#delete_form')[0].reset(); // reset form on modals
					$('#modal_delete_message').html('');  //reset message
										
					$('[name="id_delete_data"]').val(id);
					$('[name="tbl_name_del"]').val(tbl_name);
					$('#delete_text').html('<b >Hapus data ' + nama_id + '</b>');	
					$('#modalDeleteForm').modal('show'); // show bootstrap modal when complete loaded
					$('.modal-title').text('Hapus Data'); // Set Title to Bootstrap modal title	
				}else{
					//lakukan hapus data
					// ajax hapus data to database
					$.ajax({
						url : "<?php echo site_url('cms_struktur/data_delete')?>/",
						type: "POST",
						data: $('#delete_form').serialize(),
						dataType: "JSON",
						success: function(data)
						{
						   //if success close modal and reload ajax table
						   if(data['status'] == true){
								 $('#modalDeleteForm').modal('hide');					   		
								 $('#page_message').html('<div class="alert alert-info">Data berhasil di hapus.</div>');
								
								if(data['table_name'] == 'groups_leader'){
							 		tableGroupLeader.ajax.reload(null,false); //reload datatable ajax 				
								 }else{
									tableLeader.ajax.reload(null,false); //reload datatable ajax 
								 }	
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
							<input type="hidden" value="" name="id" id="id"/> 
							<input type="hidden" value="" name="tbl_name" id="tbl_name"/> 		
							<input type="hidden" value="" name="project_id" id="project_id"/> 					
							
							<div class="form-group" id="div_groups_leader" >
							  <label class="col-lg-3 control-label" for="pusat">Nama Groups Leader</label>
							  <div class="col-lg-9">
									<select name="id_groups_leader" id="id_groups_leader" class="form-control" >
										<option value="" >-- Pilih --</option>
									</select>
							  </div>
							</div>	
														
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="nama" id="lbl_nama">Nama</label>
							  <div class="col-lg-9">
								<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama">
							  </div>							  
							</div>
							
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="singkatan">Nama WBS</label>
							  <div class="col-lg-4">
								<input type="text" class="form-control" id="singkatan" name="singkatan" placeholder="Singkatan">
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
							<input type="hidden" value="" name="tbl_name_del" id="tbl_name_del"/> 																					
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
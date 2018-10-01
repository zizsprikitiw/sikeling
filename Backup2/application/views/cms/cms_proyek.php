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
				 	<form class="form-horizontal" id="form_pencarian" name="form_pencarian" >
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label col-lg-2">Tahun:</label>
								<div class="col-lg-5">                               
									<select name="filter_tahun" id="filter_tahun" class="form-control">
										<option value="" >-- Pilih --</option>
									</select> 
								</div>
						  </div>										  						
							
						</div>
						
						<div class="col-md-4">
							<div class="form-group">
									<label class="control-label col-lg-2">Pusat:</label>
									<div class="col-lg-8">                               
										<select name="filter_pusat" id="filter_pusat" class="form-control">
											<option value="" >-- Pilih --</option>
										</select> 
									</div>
							  </div>
						</div>	
					</form>	
						<div class="col-md-2" align="right">
							<!-- Button to trigger modal -->
							<button class="btn btn-success" onClick="data_add()"><i class="fa fa-plus"></i> Tambah Judul Proyek</button>	
						</div>									
				</div>
				<hr style="border-top: 1px solid #ccc;border-bottom: 1px solid #fff;" />
				
				<!-- Table Page -->
				<div class="page-tables">
					<!-- Table -->
					<div class="table-responsive">
						<table class="table-hover table-bordered" cellpadding="0" cellspacing="0" border="0" id="table" width="100%">
							<thead style="background-color:#006699; color:#FFFFFF;">
								<tr>
								  <th width="60px"><b>No</b></th>
								  <th ><b>Judul Proyek</b></th>
								  <th width="220px"><b>Singkatan</b></th>
								  <th width="80px"><b>Tahun</b></th>
								  <th width="135px"><b>Aksi</b></th>
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
						url : "<?php echo site_url('cms_proyek/data_init/')?>" ,
						type: "POST",
						dataType: "JSON",
						success: function(data)
						{
							var item_sel=["filter_pusat","filter_tahun"];
							var item_select = {"filter_pusat":-1,"filter_tahun":-1};															
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
				
				//load sub judul by tahun
				 $('#select_tahun').on('change',function(){					
					var item_selectbox = document.getElementById('select_tahun');
					var select_tahun = item_selectbox.options[item_selectbox.selectedIndex].value;
					item_selectbox = document.getElementById('pusat');
					var select_pusat = item_selectbox.options[item_selectbox.selectedIndex].value;
					
					var form_data = {
						select_tahun: select_tahun,
						select_pusat: select_pusat					
					};
					
					if((select_tahun == '--Pilih--') | (select_tahun == '')){
						$('#ref_id').html('<Option value="--Pilih--">--Pilih--</option>');						
					}else{
						//load data judul
						$.ajax({
							url : "<?php echo site_url('cms_proyek/select_sub_judul/')?>" ,
							type: "POST",
							dataType: "JSON",
							data: form_data,
							success: function(data)
							{
								var item_sel=["ref_id"];
								var item_select = {"ref_id":-1};															
								select_box(data,item_select, item_sel);																															
							},
							error: function (jqXHR, textStatus, errorThrown)
							{
								alert('Error get data from ajax');
							}
						});	
					}
										
				});		
				 										
				//load data table														
				table = $('#table').DataTable({ 			
					"processing": true, //Feature control the processing indicator.
					"serverSide": true, //Feature control DataTables' server-side processing mode.
					"deferLoading": 0, // here				
					"paging": false,
					"ordering": false,
					"bFilter": false,
					// Load data for the table's content from an Ajax source
					"ajax": {
						"url": "<?php echo site_url('cms_proyek/data_list/')?>",
						"type": "POST",						
						"data": function ( d ) {
								var item_selectbox = document.getElementById('filter_tahun');
								d.filter_tahun = item_selectbox.options[item_selectbox.selectedIndex].value;
								
								var item_selectbox = document.getElementById('filter_pusat');
								d.filter_pusat = item_selectbox.options[item_selectbox.selectedIndex].value;
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
				  $('#ref_id').html('<Option value="--Pilih--">--Pilih--</option>');
				  document.getElementById('pusat').disabled = false;
				  document.getElementById('select_tahun').disabled = false;
				  document.getElementById('ref_id').disabled = false;				  
				  
				  //Ajax Load data from ajax
				  $.ajax({
						url : "<?php echo site_url('cms_proyek/data_add/')?>" ,
						type: "POST",
						dataType: "JSON",
						success: function(data)
						{
							var item_sel=["pusat", "select_tahun"];
							var item_select = {"pusat":-1,"select_tahun":-1};															
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
			  document.getElementById('pusat').disabled = true;
			  document.getElementById('select_tahun').disabled = true;
			  document.getElementById('ref_id').disabled = true;	
			  $('#ref_id').html('<Option value="--Pilih--">--Pilih--</option>');
			  			
			  //Ajax Load data from ajax
			  $.ajax({			  		
					url : "<?php echo site_url('cms_proyek/data_edit/')?>/" + id,
					type: "GET",
					dataType: "JSON",
					success: function(data)
					{				   
						$('[name="id"]').val(data['list'].id);  
						$('[name="nama"]').val(data['list'].nama);
						$('[name="singkatan"]').val(data['list'].singkatan);
						$('[name="tahun"]').val(data['list'].tahun);
						
						var item_sel=["pusat"];
						var item_select = {"pusat":data['list'].pusat_id};															
						select_box(data,item_select, item_sel);	
						
						if(data['list'].ref_id == null){
							//judul utama							
							var item_sel=["select_tahun"];
							var item_select = {"select_tahun":-1};															
							select_box(data,item_select, item_sel);	
						}else{
							//sub judul dari							
							var item_sel=["select_tahun", "ref_id"];
							var item_select = {"select_tahun":data['ref_id_tahun'],"ref_id":data['list'].ref_id};															
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
			  //alert(filter_tahun_value);
			  table.ajax.reload(null,false); //reload datatable ajax 
			}
		
			function data_save()
			{
			  var url;
			  document.getElementById('pusat').disabled = false;
			  document.getElementById('select_tahun').disabled = false;
			  document.getElementById('ref_id').disabled = false;	
			  
			  if(save_method == 'add') 
			  {
				  url = "<?php echo site_url('cms_proyek/data_save_add')?>";
			  }
			  else
			  {
				url = "<?php echo site_url('cms_proyek/data_save_edit')?>";
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
							load_select_filter();
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
						url : "<?php echo site_url('cms_proyek/data_delete')?>/",
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
						url : "<?php echo site_url('cms_proyek/data_edit_posisi')?>",
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
							  <label class="col-lg-3 control-label" for="pusat">Pusat</label>
							  <div class="col-lg-6">
									<select name="pusat" id="pusat" class="form-control" >
										<option value="" >-- Pilih --</option>
									</select>
							  </div>
							</div>	
							
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="nama">Judul</label>
							  <div class="col-lg-9">
								<input type="text" class="form-control" id="nama" name="nama" placeholder="Judul Proyek">
							  </div>							  
							</div>
							
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="singkatan">Singkatan/Menu</label>
							  <div class="col-lg-9">
								<input type="text" class="form-control" id="singkatan" name="singkatan" placeholder="Singkatan Kegiatan">
							  </div>							  
							</div> 
							
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="tahun">Tahun</label>
							  <div class="col-lg-3">
									<input type="text" class="form-control" id="tahun" name="tahun" placeholder="YYYY" maxlength="4" >
							  </div>
							</div>
							
							<div class="form-group">
							  <label class="col-lg-3 control-label" for="ref_id">Sub Judul Dari</label>
							  <div class="col-lg-9" align="left">
							  		<div class="col-lg-3">
										<select name="select_tahun" id="select_tahun" class="form-control" >
											<option value="" >--Pilih--</option>
										</select>
									</div>
									<div class="col-lg-9">
										<select name="ref_id" id="ref_id" class="form-control">
											<option value="" >--Pilih--</option>
										</select>
									</div>							  			
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
														
    </div><!-- Mainbar ends -->   
   <div class="clearfix"></div>

</div>
<!-- Content ends -->	
<!-- End CONTENT ===============================================-->
<?PHP $this->load->view('footer'); ?>	
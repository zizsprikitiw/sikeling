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
						<div class="col-md-7" align="left">
							<div class="form-group">
								<label class="control-label col-lg-3">Tipe Approval:</label>
								<div class="col-lg-5">                               
									<select name="filter_approval" id="filter_approval" class="form-control">
										<option value="" >-- Pilih --</option>
									</select> 
								</div>
						  	</div>										  													
						</div>																	
						
						<div class="col-md-2">
							<!-- Button to trigger modal -->			
							<button class="btn btn-success" onClick="data_add()"><i class="fa fa-plus"></i> Tambah Approval</button>	
						</div>															
				</div>
								
				<hr style="border-top: 1px solid #ccc;border-bottom: 1px solid #fff;" />															
				<!-- Table Page -->
				<div class="page-tables">
					<!-- Table -->
					<div class="table-responsive">
						<table class="table-hover table-bordered table-striped" cellpadding="0" cellspacing="0" border="0" id="table" width="100%">
							<thead style="background-color:#006699; color:#FFFFFF;">
								<tr>
								  <th width="70px" style="max-width:70px"><b>No</b></th>
								  <th ><b>Pengirim</b></th>
								  <th ><b>Penerima</b></th>
								  <th ><b>Konfirmasi Level</b></th>
								  <th width="140px" style="max-width:140px"><b>Aksi</b></th>
								  <th >bgcolor</th>								  
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
						url : "<?php echo site_url('cms_approval/data_init/')?>" ,
						type: "POST",
						dataType: "JSON",
						success: function(data)
						{
							var item_sel=["filter_approval"];
							var item_select = {"filter_approval":-1};															
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
				 				
				$('#filter_approval').on('change',function(){					
					reload_table();
				});
													
				//load data table														
			 	table = $('#table').DataTable({ 			
					"processing": true, //Feature control the processing indicator.
					"serverSide": true, //Feature control DataTables' server-side processing mode.
					"deferLoading": 0, // here	
					"paging": false,						
					"ordering": false,
					"bFilter": false,
					"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
						if ( aData[5] == "info" )
						{
							$('td', nRow).css('background-color', '#fcfcfc');
						}
						else if ( aData[5] == "default" )
						{
							$('td', nRow).css('background-color', '#d9edf7');
						}
					},
					
					// Load data for the table's content from an Ajax source
					"ajax": {
						"url": "<?php echo site_url('cms_approval/data_list')?>",
						"type": "POST",						
						"data": function ( d ) {
								var item_selectbox = document.getElementById('filter_approval');
								d.approval_type_id = item_selectbox.options[item_selectbox.selectedIndex].value;								
							}
					},
			
					//Set column definition initialisation properties.
					"columnDefs": [
						{ 
						  "targets": [ 4 ], //last column
						  "searchable": false,
						  "orderable": false, //set not orderable
						},
						{ 
						  "targets": [ 5 ], //last column
						  "searchable": false,
						  "orderable": false, //set not orderable
						  "visible": false
						}
					],
					"rowsGroup": [// Always the array (!) of the column-selectors in specified order to which rows groupping is applied
								// (column-selector could be any of specified in https://datatables.net/reference/type/column-selector)
						1
					],
		
		 		});//end load data table								
				
		  	});																
			
			function reload_table()
			{
			   table.ajax.reload(null,false); //reload datatable ajax 
			}
			
			function disableSelectBox(opsi, status)
			{
				document.getElementById('select_struktural_'+opsi).disabled = status;
				document.getElementById('select_posisi_'+opsi).disabled = status;
				document.getElementById('select_fungsional_'+opsi).disabled = status;
				document.getElementById('select_group_'+opsi).disabled = status;				
			}
			
			function optRadio_Click(name, opsi)
			{
				var category = document.getElementsByName(name);
				var check1 = 0;
					for(i=0;i<category.length;i++){
						if(category[i].checked){
							disableSelectBox(opsi, true);
							var nilai = category[i].value;
							document.getElementById('select_'+nilai+'_'+opsi).disabled = false;				
							break;
					}
				}	
			}
			
			function data_add()
			{			  				  
			  save_method = 'add';
			  $('#add_form')[0].reset(); // reset form on modals		  
		      $('#modal_message').html('');  //reset message
						
			  //Ajax Load data from ajax
			  $.ajax({			  		
					url : "<?php echo site_url('cms_approval/data_add/')?>" ,
					type: "POST",
					dataType: "JSON",
					success: function(data)
					{				   
						//pengirim
						var item_sel=["select_struktural_out","select_posisi_out","select_fungsional_out","select_group_out"];
						var item_select = {"select_struktural_out":-1,"select_posisi_out":-1,"select_fungsional_out":-1,"select_group_out":-1};															
						select_box(data,item_select, item_sel);
						//penerima
						var item_sel=["select_struktural_in","select_posisi_in","select_fungsional_in","select_group_in"];
						var item_select = {"select_struktural_in":-1,"select_posisi_in":-1,"select_fungsional_in":-1,"select_group_in":-1};															
						select_box(data,item_select, item_sel);																			
						
						disableSelectBox('out', true);						
						document.getElementById('option_struktural_out').checked = true;
						document.getElementById('select_struktural_out').disabled = false;	
						
						disableSelectBox('in', true);						
						document.getElementById('option_struktural_in').checked = true;
						document.getElementById('select_struktural_in').disabled = false;	
								
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
						
			  //Ajax Load data from ajax
			  $.ajax({			  		
					url : "<?php echo site_url('cms_approval/data_edit/')?>/" + id,
					type: "GET",
					dataType: "JSON",
					success: function(data)
					{				   
						//pengirim
						$('[name="id"]').val(data['list'].id);  
						$('[name="wait_before"]').val(data['list'].wait_before);
						
						var item_sel=["select_struktural_out","select_posisi_out","select_fungsional_out","select_group_out"];
						var item_select = {"select_struktural_out":data['list'].out_struktural_id,"select_posisi_out":data['list'].out_posisi_id,"select_fungsional_out":data['list'].out_fungsional_id,"select_group_out":data['list'].out_group_id};															
						select_box(data,item_select, item_sel);
						//penerima
						var item_sel=["select_struktural_in","select_posisi_in","select_fungsional_in","select_group_in"];
						var item_select = {"select_struktural_in":data['list'].in_struktural_id,"select_posisi_in":data['list'].in_posisi_id,"select_fungsional_in":data['list'].in_fungsional_id,"select_group_in":data['list'].in_group_id};															
						select_box(data,item_select, item_sel);																			
						
						if(data['list'].out_struktural_id != null){
							optNameOut = "struktural";
						}else if(data['list'].out_posisi_id != null){
							optNameOut = "posisi";
						}else if(data['list'].out_fungsional_id != null){
							optNameOut = "fungsional";
						}else{
							optNameOut = "group";
						}
						
						if(data['list'].in_struktural_id != null){
							optNameIn = "struktural";
						}else if(data['list'].in_posisi_id != null){
							optNameIn = "posisi";
						}else if(data['list'].in_fungsional_id != null){
							optNameIn = "fungsional";
						}else{
							optNameIn = "group";
						}
						
						disableSelectBox('out', true);						
						document.getElementById('option_'+optNameOut+'_out').checked = true;
						document.getElementById('select_'+optNameOut+'_out').disabled = false;	
						
						disableSelectBox('in', true);						
						document.getElementById('option_'+optNameIn+'_in').checked = true;
						document.getElementById('select_'+optNameIn+'_in').disabled = false;																																
								
						$('#modalAddForm').modal('show'); // show bootstrap modal when complete loaded
						$('.modal-title').text('Edit Data'); // Set title to Bootstrap modal title					
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						alert('Error get data from ajax');
					}
				});
			}					
			
			function data_save()
			{
			  var url;
			  var form = document.getElementById('add_form');					  
 			  var form_data = new FormData(form);	
				
			  var item_selectbox = document.getElementById('filter_approval');															  
			  form_data.append("approval_type_id", item_selectbox.options[item_selectbox.selectedIndex].value);
				
			  if(save_method == 'add') 
			  {
				  url = "<?php echo site_url('cms_approval/data_save_add')?>";
			  }
			  else
			  {
				url = "<?php echo site_url('cms_approval/data_save_edit')?>";
			  }				
					  
			   // ajax adding data to database
				  $.ajax({
					url : url,
					type: "POST",
					data: form_data,					
					processData: false,
					contentType: false,
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
						url : "<?php echo site_url('cms_approval/data_delete')?>/",
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
									
			function data_edit_posisi(id, pos)
			{
				var form_data = {
						id: id,
						pos: pos					
					};
					
				$.ajax({
						url : "<?php echo site_url('cms_approval/data_edit_posisi')?>",
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
							<!--PENGIRIM-->
							<div class="form-group">
							  <label class="col-lg-2 control-label" for="select_struktural_out">Pengirim</label>
							  <div class="col-lg-10">
							  	<div class="col-lg-4">
									<div class="radio">
                                      <label>
                                        <input type="radio" name="optPengirim" id="option_struktural_out" value="struktural" onclick="optRadio_Click('optPengirim','out')">
                                        Struktural
                                      </label>
                                    </div>
								</div>
								<div class="col-lg-8">
									<select name="select_struktural_out" id="select_struktural_out" class="form-control" >
										<option value="" >--Pilih--</option>
									</select>
								</div>									
							  </div>
							</div> 
							
							<div class="form-group">
							  <label class="col-lg-2 control-label" for="select_posisi_out"></label>
							  <div class="col-lg-10">
							  	<div class="col-lg-4">
									<div class="radio">
                                      <label>
                                        <input type="radio" name="optPengirim" id="option_posisi_out" value="posisi" onclick="optRadio_Click('optPengirim','out')">
                                        Posisi Kegiatan
                                      </label>
                                    </div>
								</div>
								<div class="col-lg-8">
									<select name="select_posisi_out" id="select_posisi_out" class="form-control" >
										<option value="" >--Pilih--</option>
									</select>
								</div>									
							  </div>
							</div> 
							
							<div class="form-group">
							  <label class="col-lg-2 control-label" for="select_fungsional_out"></label>
							  <div class="col-lg-10">
							  	<div class="col-lg-4">
									<div class="radio">
                                      <label>
                                        <input type="radio" name="optPengirim" id="option_fungsional_out" value="fungsional" onclick="optRadio_Click('optPengirim','out')">
                                        Fungsional
                                      </label>
                                    </div>
								</div>
								<div class="col-lg-8">
									<select name="select_fungsional_out" id="select_fungsional_out" class="form-control" >
										<option value="" >--Pilih--</option>
									</select>
								</div>									
							  </div>
							</div> 
							
							<div class="form-group">
							  <label class="col-lg-2 control-label" for="select_group_out"></label>
							  <div class="col-lg-10">
							  	<div class="col-lg-4">
									<div class="radio">
                                      <label>
                                        <input type="radio" name="optPengirim" id="option_group_out" value="group" onclick="optRadio_Click('optPengirim','out')" >
                                        Group User
                                      </label>
                                    </div>
								</div>
								<div class="col-lg-8">
									<select name="select_group_out" id="select_group_out" class="form-control" >
										<option value="" >--Pilih--</option>
									</select>
								</div>									
							  </div>
							</div> 
							<!--END PENGIRIM-->
							
							<hr style="border-top: 1px solid #ccc;border-bottom: 1px solid #fff;" />								
							<!--PENERIMA-->
							<div class="form-group">
							  <label class="col-lg-2 control-label" for="select_struktural_in">Penerima</label>
							  <div class="col-lg-10">
							  	<div class="col-lg-4">
									<div class="radio">
                                      <label>
                                        <input type="radio" name="optPenerima" id="option_struktural_in" value="struktural" onclick="optRadio_Click('optPenerima','in')">
                                        Struktural
                                      </label>
                                    </div>
								</div>
								<div class="col-lg-8">
									<select name="select_struktural_in" id="select_struktural_in" class="form-control" >
										<option value="" >--Pilih--</option>
									</select>
								</div>									
							  </div>
							</div> 
							
							<div class="form-group">
							  <label class="col-lg-2 control-label" for="select_posisi_in"></label>
							  <div class="col-lg-10">
							  	<div class="col-lg-4">
									<div class="radio">
                                      <label>
                                        <input type="radio" name="optPenerima" id="option_posisi_in" value="posisi" onclick="optRadio_Click('optPenerima','in')">
                                        Posisi Kegiatan
                                      </label>
                                    </div>
								</div>
								<div class="col-lg-8">
									<select name="select_posisi_in" id="select_posisi_in" class="form-control" >
										<option value="" >--Pilih--</option>
									</select>
								</div>									
							  </div>
							</div> 
							
							<div class="form-group">
							  <label class="col-lg-2 control-label" for="select_fungsional_in"></label>
							  <div class="col-lg-10">
							  	<div class="col-lg-4">
									<div class="radio">
                                      <label>
                                        <input type="radio" name="optPenerima" id="option_fungsional_in" value="fungsional" onclick="optRadio_Click('optPenerima','in')">
                                        Fungsional
                                      </label>
                                    </div>
								</div>
								<div class="col-lg-8">
									<select name="select_fungsional_in" id="select_fungsional_in" class="form-control" >
										<option value="" >--Pilih--</option>
									</select>
								</div>									
							  </div>
							</div> 
							
							<div class="form-group">
							  <label class="col-lg-2 control-label" for="select_group_in"></label>
							  <div class="col-lg-10">
							  	<div class="col-lg-4">
									<div class="radio">
                                      <label>
                                        <input type="radio" name="optPenerima" id="option_group_in" value="group" onclick="optRadio_Click('optPenerima','in')">
                                        Group User
                                      </label>
                                    </div>
								</div>
								<div class="col-lg-8">
									<select name="select_group_in" id="select_group_in" class="form-control" >
										<option value="" >--Pilih--</option>
									</select>
								</div>									
							  </div>
							</div> 
							<!--END PENERIMA-->
							<hr style="border-top: 1px solid #ccc;border-bottom: 1px solid #fff;" />	
							<div class="form-group">
							  <label class="col-lg-2 control-label" for="wait_before">Konfirmasi</label>
							  <div class="col-lg-3">
								<input type="text" class="form-control" placeholder="Konfirmasi level" id="wait_before" name="wait_before">
							  </div>
							  <div class="col-lg-7">
							  	*Biarkan kosong: Tanpa konfirmasi<br />
								 0: Tunggu konfirmasi level dibawahnya<br />
								 1, 2, dst: : Tunggu konfirmasi n level dibawahnya
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
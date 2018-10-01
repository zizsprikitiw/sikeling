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
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label col-lg-2">Tahun:</label>
							<div class="col-lg-5">                               
								<select name="filter_tahun" id="filter_tahun" class="form-control">
									<option value="" >-- Pilih --</option>
								</select> 
							</div>
					  </div>										  												
					</div>
					
					<div class="col-md-2" align="right">
						<!-- Button to trigger modal -->
						<button class="btn btn-success" onClick="data_add()"><i class="fa fa-plus"></i> Input Berita Umum</button>	
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
								  <th ><b>Judul Berita</b></th>
								  <th width="150px" style="max-width:150px"><b>Tgl Berita</b></th>
								  <th width="100px" style="max-width:100px"><b>Jenis</b></th>								  
								  <th width="60px" style="max-width:60px"></th>								  
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
			var monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];				
			var delete_file_gambar;
			var delete_file_berita;
			var save_method;
				  
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
						url : "<?php echo site_url('rekap_berita/data_init/')?>" ,
						type: "POST",
						dataType: "JSON",
						success: function(data)
						{
							var item_sel=["filter_tahun"];
							var item_select = {"filter_tahun":-1};															
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
					
					// Load data for the table's content from an Ajax source
					"ajax": {
						"url": "<?php echo site_url('rekap_berita/data_list')?>",
						"type": "POST",						
						"data": function ( d ) {
								var item_selectbox = document.getElementById('filter_tahun');
								d.filter_tahun = item_selectbox.options[item_selectbox.selectedIndex].value;								
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
			
			function data_add()
			{			  				  
			  save_method = 'add';
			  $('#add_form')[0].reset(); // reset form on modals		  
		      $('#modal_message').html('');  //reset message
			  $('#nama_file_gambar').html('');
			  $('#nama_file_berita').html('');
			  document.getElementById('div_upload_status').style.display = "none";	
			  			  
			  $('#modalAddForm').modal('show'); // show bootstrap modal
			  $('.modal-title').text('Tambah Data'); // Set Title to Bootstrap modal title						
			}
			
			function data_edit(id)
			{			  				  
			  save_method = 'update';
			  delete_file_gambar = 'false';
			  delete_file_berita = 'false';
			  $('#add_form')[0].reset(); // reset form on modals		  
		      $('#modal_message').html('');  //reset message
			  $('#nama_file_gambar').html('');
			  $('#nama_file_berita').html('');
			  document.getElementById('div_upload_status').style.display = "none";	
			  			  
			  //Ajax Load data from ajax
			  $.ajax({			  		
					url : "<?php echo site_url('rekap_berita/data_edit/')?>/" + id,
					type: "GET",
					dataType: "JSON",
					success: function(data)
					{				   
						$('[name="id"]').val(data['list'].id);  
						$('[name="judul"]').val(data['list'].judul);
						$('[name="isi"]').val(data['list'].isi);
						
						if(data['list'].filegambar != ""){
							$('#nama_file_gambar').html(data['list'].filegambar+' <button class="btn btn-xs btn-danger" onClick="file_delete('+ "'gambar','true'" +')"><i class="fa fa-times"></i></button>');
						}
						
						if(data['list'].filename != ""){
							$('#nama_file_berita').html(data['list'].filename+' <button class="btn btn-xs btn-danger" onClick="file_delete('+ "'berita','true'" +')"><i class="fa fa-times"></i></button>');
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
			
			function file_delete(param, value)
			{
				if(param == "gambar"){
					if(value == "true"){
						delete_file_gambar = 'true';
						$('#nama_file_gambar').html('');
					}else{
						delete_file_gambar = 'false';
					}
				}
				
				if(param == "berita"){
					if(value == "true"){
						delete_file_berita = 'true';
						$('#nama_file_berita').html('');
					}else{
						delete_file_berita = 'false';
					}
				}				
			}
			
			function data_save_validation()
			{					 								  
			  var form = document.getElementById('add_form');					  
			  var form_data = new FormData(form);					  
			  var file_berita = document.getElementById('file_berita');
			  var file = file_berita.files[0];					
			  form_data.append("file_berita", file);			  
			  var file_gambar = document.getElementById('file_gambar');
			  var file = file_gambar.files[0];					
			  form_data.append("file_gambar", file);
			  						  				  					  				  					  
						
			   // ajax adding data to database
				  $.ajax({
					url : "<?php echo site_url('rekap_berita/data_save_validation/')?>",
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
							data_save_berita(data['new_file_berita'], data['new_file_gambar']);																																
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
						
			function data_save_berita(new_file_berita, new_file_gambar)
			{					 								  
			  var form = document.getElementById('add_form');					  
			  var form_data = new FormData(form);					  
			  form_data.append("new_file_berita", new_file_berita);	
			  form_data.append("new_file_gambar", new_file_gambar);				  
			  form_data.append("delete_file_gambar", delete_file_gambar);	
			  form_data.append("delete_file_berita", delete_file_berita);	

			  var file_berita = document.getElementById('file_berita');
			  var file = file_berita.files[0];					
			  form_data.append("file_berita", file);			  
			  var file_gambar = document.getElementById('file_gambar');
			  var file = file_gambar.files[0];					
			  form_data.append("file_gambar", file);	
			  
			  if((new_file_berita != "") | (new_file_gambar != "")){
			  		document.getElementById('div_upload_status').style.display = "block";	
				  $('#new_filename').html(file_berita.value+" , "+file_gambar.value);
			  }
			  
			  var url;
			  if(save_method == 'add') 
			  {
				  url = "<?php echo site_url('rekap_berita/data_save_add_berita/')?>";
			  }
			  else
			  {
				url = "<?php echo site_url('rekap_berita/data_save_edit_berita')?>";
			  }
			  			  					  				  					  				  					  					
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
					   		setTimeout(function(){
							},2000);																																						
							 $('#modalAddForm').modal('hide');					   		
							 $('#modal_message').html('');
							 $('#page_message').html('<div class="alert alert-info">Data berhasil di simpan.</div>');
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
			
			function detail_berita(id_berita)
			{										
			  //Ajax Load data from ajax
			  $.ajax({			  		
					url : "<?php echo site_url('rekap_berita/detail_berita/')?>/" + id_berita,
					type: "GET",
					dataType: "JSON",
					success: function(data)
					{				   
						var mydate = new Date(data['list'].tanggal_submit);
						var myMonth = monthNames[mydate.getMonth()];												
						$('#news_judul').html(data['list'].judul);
						$('#news_tanggal').html(mydate.getDate()+' '+myMonth+' '+mydate.getFullYear());
						$('#news_user').html(data['list'].nama);
						
						if(data['list'].proyek_id == "0"){
							$('#news_status').html('Berita Umum');
						}else{
							$('#news_status').html('Berita Khusus');
						}
						
						$('#news_pic').html('');
						if(data['picture'] != ""){							
							$('#news_pic').html('<div class="bthumb"><img src="'+data['picture']+'" alt="" class="img-responsive" /></div>');
						}
												 
						$('#news_isi').html(data['list'].isi); 
						$('#news_file').html(data['download']); 
								
						$('#modalNewsForm').modal('show'); // show bootstrap modal when complete loaded
						$('.modal-title').text('Detail Berita'); // Set title to Bootstrap modal title					
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						alert('Error get data from ajax');
					}
				});
			}
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
							  <label class="col-lg-2 control-label" for="judul">Judul</label>
							  <div class="col-lg-10">
								<input type="text" class="form-control" id="judul" name="judul" placeholder="Judul Laporan">
							  </div>							  
							</div>															
							
							<div class="form-group">
							  <label class="col-lg-2 control-label" for="isi">Isi</label>
							  <div class="col-lg-10 text-area" >
								 <textarea class="cleditor" name="isi" id="isi"></textarea>									  
							  </div>							  
							</div>
							
							<div class="form-group">
							  <label class="col-lg-2 control-label" for="file_gambar">Gambar</label>
							  <div class="col-lg-9">
									<span class="btn btn-default btn-file">
										<input type="file"  id="file_gambar" name="file_gambar" placeholder=""  style="width:400px">
									</span>
									<label id="nama_file_gambar"></label> 							
							  </div>							  
							</div>
							
							<div class="form-group">
							  <label class="col-lg-2 control-label" for="file_berita">File</label>
							  <div class="col-lg-9">
									<span class="btn btn-default btn-file">
										<input type="file"  id="file_berita" name="file_berita" placeholder=""  style="width:400px">
									</span>
									<label id="nama_file_berita"></label>									
							  </div>							  
							</div>	
							
							<div class="form-group" id="div_upload_status" style="display:none">
							  <label class="col-lg-2 control-label" ></label>
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
		
		<!-- Modal BEGIN:DETAIL BERITA-->
		<div id="modalNewsForm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<!-- Form starts.  -->					
					  <div class="modal-header">			                        
						<h4 class="modal-title">Detail Berita</h4>
					  </div>
					  <div class="modal-body">
					  		<div class="content blog">							  
									<div class="posts">
										
										<div class="entry">
											 <h2 id="news_judul">Sed justo scelerisque ut consectetur</h2>
											 
											 <!-- Meta details -->
											 <div class="meta">
												<i class="fa fa-calendar"></i> <label id="news_tanggal"></label> &nbsp;<i class="fa fa-user"></i> <label id="news_user"></label> &nbsp;<i class="fa fa-folder-open"></i> <label id="news_status"></label>
											 </div>
											 
											 <!-- Thumbnail -->
											 <div id="news_pic">
											 </div>											 
											 
											 <div id="news_isi">
											 </div>											 											 								
										  </div>
											
											<div id="news_file">
											 </div>		
									</div><!--END class="posts"-->								 
							</div><!--END class="content blog"-->					  					  									  		 																		
							
					  </div>	<!--END modal-body-->
					  <div class="modal-footer">										
						<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>							
					  </div>				  
				</div>	<!--END modal-content-->
			</div>	<!--END modal-dialog-->
		</div>
		<!-- Modal END:DETAIL BERITA-->
		
														
    </div><!-- Mainbar ends -->   
   <div class="clearfix"></div>

</div>

<!-- Content ends -->	
<!-- End CONTENT ===============================================-->
<?PHP $this->load->view('footer'); ?>	
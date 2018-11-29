<?PHP $this->load->view('header'); ?>	
<!-- Begin CONTENT ===============================================-->
	<!-- Main bar -->
	
  	<div class="mainbar">
      
	    <!-- Page heading -->
	    <div class="page-head">
	       <h3 class="pull-left" ><i class="title_page_icon <?php echo $user_menu['page_icon'] ?>"></i><?php echo $user_menu['page_title'] ?></h3>

        <!-- Breadcrumb -->              	
			<div class="clearfix"></div>
	    </div>
	    <!-- Page heading ends -->
  
		<!-- Matter -->
	    <div class="matter">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<ul class="nav nav-tabs nav-justified" id="myTab" role="tablist" style="margin-top:10px;">
							<li class="nav-item active">
								<a class="nav-link active" id="list_baru-tab" data-toggle="tab" href="#tab_list_baru" role="tab" aria-controls="tab_list_baru" aria-selected="true">Pengajuan Baru</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="list_sdm-tab" data-toggle="tab" href="#tab_list_sdm" role="tab" aria-controls="tab_list_sdm" aria-selected="false">Proses SDM</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="list_sdm_orkum-tab" data-toggle="tab" href="#tab_list_sdm_orkum" role="tab" aria-controls="tab_list_sdm_orkum" aria-selected="false">Proses SDM Orkum</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="list_arsip-tab" data-toggle="tab" href="#tab_list_arsip" role="tab" aria-controls="tab_list_arsip" aria-selected="false">Proses Arsip</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="list_final-tab" data-toggle="tab" href="#tab_list_final" role="tab" aria-controls="tab_list_final" aria-selected="false">Final</a>
							</li>
						</ul>
						<div class="tab-content" id="myTabContent">
							<div class="tab-pane fade in active" id="tab_list_baru" role="tabpanel" aria-labelledby="list_baru-tab">
								<!-- Table Page -->
								<div class="page-tables">
									<!-- Table -->
									<div class="table-responsive">
										<table class="table-hover table-bordered" cellpadding="0" cellspacing="0" border="0" id="table_list_baru" width="100%">
											<thead style="background-color:#006699; color:#FFFFFF;">
												<tr>
												  <th width="70px"><b>No</b></th>
												  <th ><b>No Surat</b></th>
												  <th ><b>Nama</b></th>
												  <th ><b>Jenis Usulan</b></th>
												  <th ><b>Durasi</b></th>
												  <th ><b>Keterangan</b></th>
												  <th width="90px"><b>Aksi</b></th>
												</tr>
											</thead>													
										</table>						
										<div class="clearfix"></div>									
									</div>
								</div>
								<!-- Table Page -->	
							</div>
							<div class="tab-pane fade" id="tab_list_sdm" role="tabpanel" aria-labelledby="list_sdm-tab">
								<!-- Table Page -->
								<div class="page-tables">
									<!-- Table -->
									<div class="table-responsive">
										<table class="table-hover table-bordered" cellpadding="0" cellspacing="0" border="0" id="table_list_sdm" width="100%">
											<thead style="background-color:#006699; color:#FFFFFF;">
												<tr>
												  <th width="70px"><b>No</b></th>
												  <th ><b>No Surat</b></th>
												  <th ><b>Nama</b></th>
												  <th ><b>Jenis Usulan</b></th>
												  <th ><b>Durasi</b></th>
												  <th ><b>Keterangan</b></th>
												  <th width="90px"><b>Aksi</b></th>
												</tr>
											</thead>													
										</table>						
										<div class="clearfix"></div>									
									</div>
								</div>
								<!-- Table Page -->	
							</div>
							<div class="tab-pane fade" id="tab_list_sdm_orkum" role="tabpanel" aria-labelledby="list_sdm_orkum-tab">
								<!-- Table Page -->
								<div class="page-tables">
									<!-- Table -->
									<div class="table-responsive">
										<table class="table-hover table-bordered" cellpadding="0" cellspacing="0" border="0" id="table_list_sdm_orkum" width="100%">
											<thead style="background-color:#006699; color:#FFFFFF;">
												<tr>
												  <th width="70px"><b>No</b></th>
												  <th ><b>No Surat</b></th>
												  <th ><b>Nama</b></th>
												  <th ><b>Jenis Usulan</b></th>
												  <th ><b>Durasi</b></th>
												  <th ><b>Keterangan</b></th>
												  <th width="90px"><b>Aksi</b></th>
												</tr>
											</thead>													
										</table>						
										<div class="clearfix"></div>									
									</div>
								</div>
								<!-- Table Page -->	
							</div>
							<div class="tab-pane fade" id="tab_list_arsip" role="tabpanel" aria-labelledby="list_arsip-tab">
								<!-- Table Page -->
								<div class="page-tables">
									<!-- Table -->
									<div class="table-responsive">
										<table class="table-hover table-bordered" cellpadding="0" cellspacing="0" border="0" id="table_list_arsip" width="100%">
											<thead style="background-color:#006699; color:#FFFFFF;">
												<tr>
												  <th width="70px"><b>No</b></th>
												  <th ><b>No Surat</b></th>
												  <th ><b>Nama</b></th>
												  <th ><b>Jenis Usulan</b></th>
												  <th ><b>Durasi</b></th>
												  <th ><b>Keterangan</b></th>
												  <th width="90px"><b>Aksi</b></th>
												</tr>
											</thead>													
										</table>						
										<div class="clearfix"></div>									
									</div>
								</div>
								<!-- Table Page -->	
							</div>
							<div class="tab-pane fade" id="tab_list_final" role="tabpanel" aria-labelledby="list_final-tab">
								<!-- Table Page -->
								<div class="page-tables">
									<!-- Table -->
									<div class="table-responsive">
										<table class="table-hover table-bordered" cellpadding="0" cellspacing="0" border="0" id="table_list_final" width="100%">
											<thead style="background-color:#006699; color:#FFFFFF;">
												<tr>
												  <th width="70px"><b>No</b></th>
												  <th ><b>No Surat</b></th>
												  <th ><b>Nama</b></th>
												  <th ><b>Jenis Usulan</b></th>
												  <th ><b>Durasi</b></th>
												  <th ><b>Keterangan</b></th>
												  <th width="90px"><b>Aksi</b></th>
												</tr>
											</thead>													
										</table>						
										<div class="clearfix"></div>									
									</div>
								</div>
								<!-- Table Page -->	
							</div>
						</div>
					</div>
				</div>
			</div><!-- Containers ends -->
		 </div><!-- Matter ends -->
		
    </div>
	
	<!-- Modal BEGIN:ADD DATA-->
	<div id="modalAddForm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<!-- Form starts.  -->
				<form class="form-horizontal" role="form" id="add_form" action="#" autocomplete="nope" enctype="multipart/form-data">
				  <div class="modal-header">	
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Tambah</h4>
				  </div>
				  <div class="modal-body">									  		 											
						<input type="hidden" value="" name="id"/> 
						<input type="hidden" value="" name="save_method"/> 
						
						<div class="form-group">
						  <label class="col-lg-3 control-label" for="id_jenis_usulan">Jenis Usulan</label>
						  <div class="col-lg-7" id="div_jenisusulan"></div>							  
						</div>	
								
						<div class="form-group">
						  <label class="col-lg-3 control-label" for="id_user">Staff</label>
						  <div class="col-lg-7" id="div_user"></div>				  
						</div>	
								
						<div class="form-group">
						  <label class="col-lg-3 control-label" for="no_surat">No Surat</label>
						  <div class="col-lg-7">
							<input type="text" class="form-control" id="no_surat" name="no_surat" placeholder="No Surat">
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
		
	<!-- Modal -->
	<div id="keteranganModal" class="modal fade" role="dialog">
		<div class="modal-dialog modal-md">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="keteranganModalTitle">Keterangan</h4>
				</div>
				<div class="modal-body" id="keteranganModalBody">
					<form class="form-horizontal" role="form" id="keterangan_form" action="#" autocomplete="nope" enctype="multipart/form-data">
						<input type="hidden" value="" name="id_history"/> 
						<div class="row">
							<div class="col-md-12">
								<textarea name="keterangan" style="width:100%; height: 150px;"></textarea>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>	
					<button type="button" id="btnSave" onClick="data_save_keterangan()" class="btn btn-sm btn-success">Simpan</button>			
				</div>
			</div>
		</div>
	</div>
		
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
		
	<!-- Modal BEGIN:UBAH POSISI-->										
	<div id="modalPosisiForm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">									
				<!-- Form starts.  -->	
				<form class="form-horizontal" role="form" id="posisi_form" action="#">
				  <div class="modal-header">			                        
					<h4 class="modal-title">Ubah Posisi</h4>
				  </div>
				  <div class="modal-body">
						<input type="hidden" value="" name="id"/> 																					
						<input type="hidden" value="" name="id_posisi"/> 																					
						<div class="form-group" align="center">
							<div class="col-lg-12">
								<div id="posisi_text"></div>																	  
							</div>																							
						</div> 
						<div class="form-group" align="center">
							<div class="col-lg-12">
								<b >Anda yakin ?!</b>	
							</div>	
						</div> 
						 <div id="modal_posisi_message"></div>
				  </div>	<!--END modal-body-->
				  <div class="modal-footer">										
					<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>	
					<button type="button" id="btnDelete" onClick="ubah_posisi('','','')" class="btn btn-sm btn-success">Ubah</button>								
				  </div>
			  </form>
			</div>	<!--END modal-content-->
		</div>	<!--END modal-dialog-->
	</div>
	<!-- Modal END:UBAH POSISI-->	
	
   <!-- Mainbar ends -->
   <div class="clearfix"></div>
</div>
<!-- Content ends -->	
<!-- End CONTENT ===============================================-->
<?PHP $this->load->view('footer'); ?>	

<script type="text/javascript">
	var table_list_baru;
	var table_list_sdm;
	var table_list_sdm_orkum;
	var table_list_arsip;
	var table_list_final;
	
  $(document).ready(function() {
    /* initialize the calendar
    -----------------------------------------------------------------*/
	
	table_list_baru = $('#table_list_baru').DataTable({ 			
		"processing": true, //Feature control the processing indicator.
		"serverSide": true, //Feature control DataTables' server-side processing mode.
		//"deferLoading": 0, // here	
		"ordering": false,
		"bAutoWidth": false,
		"paging": true,
		 "dom": '<l<B>r<t>ip>',
		  //"dom": '<lf<t>ip>',
		//"buttons": [],
		"buttons": [
			{
				className: 'btn btn-success',
				text: '<i class="fa fa-plus"></i> Pengajuan',
				init: function( api, node, config) {
					   $(node).removeClass('dt-button')
				},
				action: function ( e, dt, node, config ) {
					data_add();
				}
			}
		],
		"searching": true,
		// Load data for the table's content from an Ajax source
		"ajax": {
			"url": "<?php echo site_url('status_kepegawaian/data_list_baru/')?>",
			"type": "POST",
			"data": function ( d ) {
					var chkSearch = [];					
			},
			"beforeSend": function() {
				cleardurasi();
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
			  "targets": [ 0,4,6 ], // your case first column
			  "className": "text-center",
			},
			{
			  "width": "8%",
			  "targets": [ 4 ],
			},
			{
			  "width": "3%",
			  "targets": [ 0 ],
			},
			{
			  "width": "12%",
			  "targets": [ 1 ],
			},
			{
			  "width": "15%",
			  "targets": [ 3,5 ],
			},
			{
			  "width": "13%",
			  "targets": [ 6 ],
			},
		],
	});
	
	table_list_sdm = $('#table_list_sdm').DataTable({ 			
		"processing": true, //Feature control the processing indicator.
		"serverSide": true, //Feature control DataTables' server-side processing mode.
		//"deferLoading": 0, // here	
		"ordering": false,
		"bAutoWidth": false,
		"paging": true,
		 "dom": '<l<B>r<t>ip>',
		  //"dom": '<lf<t>ip>',
		"buttons": [],
		"searching": true,
		// Load data for the table's content from an Ajax source
		"ajax": {
			"url": "<?php echo site_url('status_kepegawaian/data_list_sdm/')?>",
			"type": "POST",
			"data": function ( d ) {
					var chkSearch = [];					
			},
			"beforeSend": function() {
				cleardurasi();
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
			  "targets": [ 0,4,6 ], // your case first column
			  "className": "text-center",
			},
			{
			  "width": "8%",
			  "targets": [ 4 ],
			},
			{
			  "width": "3%",
			  "targets": [ 0 ],
			},
			{
			  "width": "12%",
			  "targets": [ 1 ],
			},
			{
			  "width": "15%",
			  "targets": [ 3,5 ],
			},
			{
			  "width": "13%",
			  "targets": [ 6 ],
			},
		],

	});
	
	table_list_sdm_orkum = $('#table_list_sdm_orkum').DataTable({ 			
		"processing": true, //Feature control the processing indicator.
		"serverSide": true, //Feature control DataTables' server-side processing mode.
		//"deferLoading": 0, // here	
		"ordering": false,
		"bAutoWidth": false,
		"paging": true,
		 "dom": '<l<B>r<t>ip>',
		  //"dom": '<lf<t>ip>',
		"buttons": [],
		"searching": true,
		// Load data for the table's content from an Ajax source
		"ajax": {
			"url": "<?php echo site_url('status_kepegawaian/data_list_sdm_orkum/')?>",
			"type": "POST",
			"data": function ( d ) {
					var chkSearch = [];					
			},
			"beforeSend": function() {
				cleardurasi();
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
			  "targets": [ 0,4,6 ], // your case first column
			  "className": "text-center",
			},
			{
			  "width": "8%",
			  "targets": [ 4 ],
			},
			{
			  "width": "3%",
			  "targets": [ 0 ],
			},
			{
			  "width": "12%",
			  "targets": [ 1 ],
			},
			{
			  "width": "15%",
			  "targets": [ 3,5 ],
			},
			{
			  "width": "13%",
			  "targets": [ 6 ],
			},
		],

	});
	
	table_list_arsip = $('#table_list_arsip').DataTable({ 			
		"processing": true, //Feature control the processing indicator.
		"serverSide": true, //Feature control DataTables' server-side processing mode.
		//"deferLoading": 0, // here	
		"ordering": false,
		"bAutoWidth": false,
		"paging": true,
		 "dom": '<l<B>r<t>ip>',
		  //"dom": '<lf<t>ip>',
		"buttons": [],
		"searching": true,
		// Load data for the table's content from an Ajax source
		"ajax": {
			"url": "<?php echo site_url('status_kepegawaian/data_list_arsip/')?>",
			"type": "POST",
			"data": function ( d ) {
					var chkSearch = [];					
			},
			"beforeSend": function() {
				cleardurasi();
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
			  "targets": [ 0,4,6 ], // your case first column
			  "className": "text-center",
			},
			{
			  "width": "8%",
			  "targets": [ 4 ],
			},
			{
			  "width": "3%",
			  "targets": [ 0 ],
			},
			{
			  "width": "12%",
			  "targets": [ 1 ],
			},
			{
			  "width": "15%",
			  "targets": [ 3,5 ],
			},
			{
			  "width": "13%",
			  "targets": [ 6 ],
			},
		],

	});
	
	table_list_final = $('#table_list_final').DataTable({ 			
		"processing": true, //Feature control the processing indicator.
		"serverSide": true, //Feature control DataTables' server-side processing mode.
		//"deferLoading": 0, // here	
		"ordering": false,
		"bAutoWidth": false,
		"paging": true,
		 "dom": '<l<B>r<t>ip>',
		  //"dom": '<lf<t>ip>',
		"buttons": [],
		"searching": true,
		// Load data for the table's content from an Ajax source
		"ajax": {
			"url": "<?php echo site_url('status_kepegawaian/data_list_final/')?>",
			"type": "POST",
			"data": function ( d ) {
					var chkSearch = [];					
			},
			"beforeSend": function() {
				cleardurasi();
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
			  "targets": [ 0,4,6 ], // your case first column
			  "className": "text-center",
			},
			{
			  "width": "8%",
			  "targets": [ 4 ],
			},
			{
			  "width": "3%",
			  "targets": [ 0 ],
			},
			{
			  "width": "12%",
			  "targets": [ 1 ],
			},
			{
			  "width": "15%",
			  "targets": [ 3,5 ],
			},
			{
			  "width": "13%",
			  "targets": [ 6 ],
			},
		],

	});
	
	$('#jenis_kegiatan').on('change', function(){
		select_jenis_kegiatan();
	});
	
	$("select#id_penanggungjawab").select2({
		tags: true,
		tokenSeparators: [',']
	});

  });
		
	function reload_table()
	{
		table_list_baru.ajax.reload(null,false); //reload datatable ajax 
		table_list_sdm.ajax.reload(null,false); //reload datatable ajax 
		table_list_sdm_orkum.ajax.reload(null,false); //reload datatable ajax 
		table_list_arsip.ajax.reload(null,false); //reload datatable ajax 
		table_list_final.ajax.reload(null,false); //reload datatable ajax 
	}		
			
	function data_add()
	{			  				  
		save_method = 'add';
		$('#add_form')[0].reset(); // reset form on modals		  
		$('#modal_message').html('');  //reset message
		$('[name="save_method"]').val(save_method);  
				  
		$('#modalAddForm').modal('show'); // show bootstrap modal
		$('.modal-title').text('Tambah Data'); // Set Title to Bootstrap modal title		
		select_jenis_usulan();
		select_user();
	}	
	
	function data_edit(id)
	{
	  save_method = 'update';
	  $('#add_form')[0].reset(); // reset form on modals
	  $('#modal_message').html('');  //reset message	
	  $('[name="save_method"]').val(save_method);  
				
	  //Ajax Load data from ajax
	  $.ajax({			  		
			url : "<?php echo site_url('status_kepegawaian/data_edit/')?>/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(data)
			{				   
				$('[name="id"]').val(data['list_status_kepegawaian'].id_status_kepegawaian);  
				$('[name="no_surat"]').val(data['list_status_kepegawaian'].no_surat);
				select_update_jenis_usulan(data['list_status_kepegawaian'].id_jenis_usulan);
				select_update_user(data['list_status_kepegawaian'].list_user);
						
				$('#modalAddForm').modal('show'); // show bootstrap modal when complete loaded
				$('.modal-title').text('Edit Data'); // Set title to Bootstrap modal title					
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				alert('Error get data from ajax');
			}
		});
	}
	
	function data_edit_keterangan(id_history)
	{
	  $('#keterangan_form')[0].reset(); // reset form on modals
	  $('#modal_message').html('');  //reset message	 
				
	  //Ajax Load data from ajax
	  $.ajax({			  		
			url : "<?php echo site_url('status_kepegawaian/data_edit_keterangan/')?>/" + id_history,
			type: "GET",
			dataType: "JSON",
			success: function(data)
			{				   
				$('[name="id_history"]').val(id_history);  
				$('[name="keterangan"]').val(data.keterangan);
						
				$('#keteranganModal').modal('show'); // show bootstrap modal when complete loaded
				$('.modal-title').text('Edit Keterangan'); // Set title to Bootstrap modal title					
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				alert('Error get data from ajax');
			}
		});
	}
	
	function data_save_validation()
	{
		$('#form_add_message').html('');
		$('#modal_message').html('');
		//document.getElementById('div_upload_status').style.display = "none";
		  //disable_input(0);					 								  
		  var form = document.getElementById('add_form');					  
		  var form_data = new FormData(form);			
					
		   // ajax adding data to database
			  $.ajax({
				url : "<?php echo site_url('status_kepegawaian/data_save_validation/')?>",
				type: "POST",
				data: form_data,
				processData: false,
				contentType: false,
				dataType: "JSON",
				success: function(data)
				{
				   //if success close modal and reload ajax table
				   if(data['status'] == true){
						//saved data																
						$('#modalAddForm').modal('hide');							 
						$('#modal_message').html('<div class="alert alert-info">' + data['message'] + '.</div>');
						reload_table();																																									
				   }else{							
						$('#modal_message').html('<div class="alert alert-info">' + data['message'] + '</div>');							
				   }					   					  
				},
				error: function (jqXHR, textStatus, errorThrown)
				{						
					alert('Error adding / update data');									
				}
			});
	}
	
	function data_save_keterangan()
	{
		$('#modal_message').html('');
		//document.getElementById('div_upload_status').style.display = "none";
		  //disable_input(0);					 								  
		  var form = document.getElementById('keterangan_form');					  
		  var form_data = new FormData(form);			
					
		   // ajax adding data to database
			  $.ajax({
				url : "<?php echo site_url('status_kepegawaian/data_save_keterangan/')?>",
				type: "POST",
				data: form_data,
				processData: false,
				contentType: false,
				dataType: "JSON",
				success: function(data)
				{
				   //if success close modal and reload ajax table
				   if(data['status'] == true){
						//saved data																
						$('#keteranganModal').modal('hide');							 
						$('#modal_message').html('<div class="alert alert-info">' + data['message'] + '.</div>');
						reload_table();																																									
				   }else{							
						$('#modal_message').html('<div class="alert alert-info">' + data['message'] + '</div>');							
				   }					   					  
				},
				error: function (jqXHR, textStatus, errorThrown)
				{						
					alert('Error adding / update data');									
				}
			});
	}

	function select_jenis_usulan() {
		$.ajax({
			type: 'post',
			url: "<?php echo site_url('status_kepegawaian/select_jenis_usulan/')?>",
			dataType : 'json',
			success: function(data){
				div = '';
				div += '<select name="id_jenis_usulan" id="id_jenis_usulan" class="form-control" style="max-width:60%;">';
				jQuery.each(data.filter_usulan, function(index, item) {
					div += '<option value="'+item.id_jenis_usulan+'">'+item.nama_jenis_usulan+'</option>';
				});
				div += '</select>';
				$("#div_jenisusulan").html(div);
			},
			error: function (jqXHR, exception) {
				  var msgerror = ''; 
				  if (jqXHR.status === 0) {
					  msgerror = 'jaringan tidak terkoneksi.';
				  } else if (jqXHR.status == 404) {
					  msgerror = 'Halamam tidak ditemukan. [404]';
				  } else if (jqXHR.status == 500) {
					  msgerror = 'Internal Server Error [500].';
				  } else if (exception === 'parsererror') {
					  msgerror = 'Requested JSON parse gagal.';
				  } else if (exception === 'timeout') {
					  msgerror = 'RTO.';
				  } else if (exception === 'abort') {
					  msgerror = 'Gagal request ajax.';
				  } else {
					  msgerror = 'Error.\n' + jqXHR.responseText;
				  }
				  swal("Error System", msgerror, 'error');
			}										
		});	 
	}
	
	
	function select_update_jenis_usulan(id_jenis_usulan) {
		$.ajax({
			type: 'post',
			url: "<?php echo site_url('status_kepegawaian/select_jenis_usulan/')?>",
			dataType : 'json',
			success: function(data){
				div = '';
				div += '<select name="id_jenis_usulan" id="id_jenis_usulan" class="form-control" style="max-width:60%;">';
				jQuery.each(data.filter_usulan, function(index, item) {
					div += '<option value="'+item.id_jenis_usulan+'">'+item.nama_jenis_usulan+'</option>';
				});
				div += '</select>';
				$("#div_jenisusulan").html(div);
				$('[name="id_jenis_usulan"]').val(id_jenis_usulan).trigger("chosen:updated");
			},
			error: function (jqXHR, exception) {
				  var msgerror = ''; 
				  if (jqXHR.status === 0) {
					  msgerror = 'jaringan tidak terkoneksi.';
				  } else if (jqXHR.status == 404) {
					  msgerror = 'Halamam tidak ditemukan. [404]';
				  } else if (jqXHR.status == 500) {
					  msgerror = 'Internal Server Error [500].';
				  } else if (exception === 'parsererror') {
					  msgerror = 'Requested JSON parse gagal.';
				  } else if (exception === 'timeout') {
					  msgerror = 'RTO.';
				  } else if (exception === 'abort') {
					  msgerror = 'Gagal request ajax.';
				  } else {
					  msgerror = 'Error.\n' + jqXHR.responseText;
				  }
				  swal("Error System", msgerror, 'error');
			}										
		});	 
	}
	
	function select_user() {
		$.ajax({
			type: 'post',
			url: "<?php echo site_url('status_kepegawaian/select_usulan_user/')?>",
			dataType : 'json',
			success: function(data){
				div = '';
				div += '<select name="id_user_usulan[]" id="id_user_usulan" multiple="multiple">';
				var no = 0;
				jQuery.each(data.filter_user, function(index, item) {
					div += '<option value="'+item.id+'">'+item.nama+'</option>';
				});
				div += '</select>';
				$("#div_user").html(div);
				$("select#id_user_usulan").select2({
					tags: true,
					width: '100%',
					tokenSeparators: [',', ' ']
				})
			},
			error: function (jqXHR, exception) {
				  var msgerror = ''; 
				  if (jqXHR.status === 0) {
					  msgerror = 'jaringan tidak terkoneksi.';
				  } else if (jqXHR.status == 404) {
					  msgerror = 'Halamam tidak ditemukan. [404]';
				  } else if (jqXHR.status == 500) {
					  msgerror = 'Internal Server Error [500].';
				  } else if (exception === 'parsererror') {
					  msgerror = 'Requested JSON parse gagal.';
				  } else if (exception === 'timeout') {
					  msgerror = 'RTO.';
				  } else if (exception === 'abort') {
					  msgerror = 'Gagal request ajax.';
				  } else {
					  msgerror = 'Error.\n' + jqXHR.responseText;
				  }
				  swal("Error System", msgerror, 'error');
			}										
		});	 
	}
	
	function select_update_user(list_user) {
		$.ajax({
			type: 'post',
			url: "<?php echo site_url('status_kepegawaian/select_usulan_user/')?>",
			dataType : 'json',
			success: function(data){
				div = '';
				div += '<select name="id_user_usulan[]" id="id_user_usulan" multiple="multiple">';
				var no = 0;
				jQuery.each(data.filter_user, function(index, item) {
					div += '<option value="'+item.id+'">'+item.nama+'</option>';
				});
				div += '</select>';
				$("#div_user").html(div);
				$select_user = $("select#id_user_usulan").select2({
					tags: true,
					width: '100%',
					tokenSeparators: [',', ' ']
				});
				
				var array_selected = [];
				jQuery.each(list_user, function(index, item) {					
					array_selected.push(item.user_id);
				});
				$select_user.val(array_selected).trigger('change');
			},
			error: function (jqXHR, exception) {
				  var msgerror = ''; 
				  if (jqXHR.status === 0) {
					  msgerror = 'jaringan tidak terkoneksi.';
				  } else if (jqXHR.status == 404) {
					  msgerror = 'Halamam tidak ditemukan. [404]';
				  } else if (jqXHR.status == 500) {
					  msgerror = 'Internal Server Error [500].';
				  } else if (exception === 'parsererror') {
					  msgerror = 'Requested JSON parse gagal.';
				  } else if (exception === 'timeout') {
					  msgerror = 'RTO.';
				  } else if (exception === 'abort') {
					  msgerror = 'Gagal request ajax.';
				  } else {
					  msgerror = 'Error.\n' + jqXHR.responseText;
				  }
				  swal("Error System", msgerror, 'error');
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
				url : "<?php echo site_url('status_kepegawaian/data_delete')?>/",
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
	
	function ubah_posisi(id, id_posisi, nama_posisi)
	{
		if(id != ''){
			//show modal confirmation
			$('#posisi_form')[0].reset(); // reset form on modals
			$('#modal_approve_message').html('');  //reset message
			
			$('[name="id"]').val(id);
			$('[name="id_posisi"]').val(id_posisi);
			$('#posisi_text').html('Mengubah posisi ke <b>"' + nama_posisi + '"</b>');	
			$('#modalPosisiForm').modal('show'); // show bootstrap modal when complete loaded
			$('.modal-title').text('Ubah Posisi'); // Set Title to Bootstrap modal title	
		}else{
			//lakukan approve data
			// ajax approve data to database
			$.ajax({
				url : "<?php echo site_url('status_kepegawaian/ubah_posisi')?>/",
				type: "POST",
				data: $('#posisi_form').serialize(),
				dataType: "JSON",
				success: function(data)
				{
				   //if success close modal and reload ajax table
				   if(data['status'] == true){
						//berhasil approve							
						$('#modalPosisiForm').modal('hide');					   		
						$('#page_message').html('<div class="alert alert-info">' + data['message'] + '.</div>');
						reload_table();
				   }else{
						//form validation
						$('#modal_posisi_message').html('<div class="alert alert-info">' + data['message'] + '</div>');							
				   }					   					  
				},
				error: function (jqXHR, textStatus, errorThrown)
				{						
					alert('Error adding / update data');									
				}
			});					
		}				
	}	
	
	function durasi(id,waktu) {
		var myinterval = setInterval(function() {
			var timespan = countdown(new Date(waktu).getTime(), new Date().getTime());
			var div = document.getElementById('durasi'+id);
			if(timespan.years>0){
				div.innerHTML = timespan.years + " tahun";
			} else if (timespan.months>0){
				div.innerHTML = timespan.months + " bulan, " + timespan.days + " hari";
			} else if (timespan.days>0){
				div.innerHTML = timespan.days + " hari";
			} else if (timespan.hours>0){
				div.innerHTML = timespan.hours + " jam, " + timespan.minutes + " menit";
			} else if (timespan.minutes>0){
				div.innerHTML = timespan.minutes + " menit";
			} else if (timespan.seconds>0){
				div.innerHTML = timespan.seconds + " detik";
			} 
		}, 1000);
	}
	
	function cleardurasi() {
		for(var i=0; i<10000; i++) {
			window.clearInterval(i);
		}
	}

</script>
<style>
	.nav-tabs > li.active > a, .nav-tabs > li.active > a:focus, .nav-tabs > li.active > a:hover {
		background-color: rgb(221, 221, 221);
	}
	div.dt-buttons{
		margin-left: 15px;
		margin-top: 3px;
	}
</style>
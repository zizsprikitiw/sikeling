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
					<div class="col-sm-12 form-horizontal" align="left">					
						<div class="form-group" style="vertical-align:middle">
							<label class="col-sm-2 checkbox-inline" style="max-width:80px; padding-left:40px; vertical-align:middle;"><b>
							  <input type="checkbox" name="chkSearch[]" id="chkSearch[]" value="tahun"> Tahun:</b>
							</label>								
							<div class="col-sm-4" align="left" style="text-align:left; padding-left:0px; max-width:110px;">                               
								<select name="filter_tahun" id="filter_tahun" class="form-control">
									<option value="" >-- Pilih --</option>
								</select> 
							</div>
							<label class="col-sm-2 checkbox-inline" style="max-width:80px; padding-left:40px; vertical-align:middle;"><b>
							  <input type="checkbox" name="chkSearch[]" id="chkSearch[]" value="bulan"> Bulan:</b>
							</label>								
							<div class="col-sm-4" align="left" style="text-align:left; padding-left:0px; max-width:150px;">                               
								<select name="filter_bulan" id="filter_bulan" class="form-control">
									<option value="" >-- Pilih --</option>
								</select> 
							</div>
							<label class="col-sm-2 checkbox-inline" style="max-width:100px; padding-left:40px; vertical-align:middle;"><b>
							  <input type="checkbox" name="chkSearch[]" id="chkSearch[]" value="ruangan"> Ruangan:</b>
							</label>								
							<div class="col-sm-4" align="left" style="text-align:left; padding-left:0px; max-width:200px;">                               
								<select name="filter_ruangan" id="filter_ruangan" class="form-control">
									<option value="" >-- Pilih --</option>
								</select> 
							</div>
							<label class="col-sm-2 checkbox-inline" style="max-width:130px; padding-left:40px; vertical-align:middle;"><b>
							  <input type="checkbox" name="chkSearch[]" id="chkSearch[]" value="nama"> Nama Acara:</b>
							</label>
							<div class="col-sm-3" align="left" style="text-align:left; max-width:120px; padding-left:0px;">                               
								<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Acara">
							</div>
							<div class="col-sm-1">
								<button type="button" class="btn btn-primary" id="btnSearch" onClick="data_search()"><i class="fa fa-search"></i> Cari</button>	
							</div>
						</div>	
					</div>				 																
				</div>
				<hr style="border-top: 1px solid #ccc;border-bottom: 1px solid #fff;margin-top: 0px;" />
				<div class="row">
					<div class="col-md-12">
						<!-- Table Page -->
						<div class="page-tables">
							<!-- Table -->
							<div class="table-responsive">
								<table class="table-hover table-bordered" cellpadding="0" cellspacing="0" border="0" id="table" width="100%">
									<thead style="background-color:#006699; color:#FFFFFF;">
										<tr>
										  <th width="70px"><b>No</b></th>
										  <th ><b>Tgl Kegiatan</b></th>
										  <th ><b>Agenda</b></th>
										  <th ><b>Keterangan</b></th>
										  <th ><b>Tgl Kirim</b></th>
										  <th ><b>Tgl Disetujui</b></th>
										  <th ><b>Aksi</b></th>
										</tr>
									</thead>													
								</table>						
								<div class="clearfix"></div>									
							</div>
						</div>
						<!-- Table Page -->	
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
								
						<div class="form-group" id="div_jenis_kegiatan">
						  <label class="col-lg-3 control-label" for="jenis_kegiatan">Kegiatan</label>
						  <div class="col-lg-7">
							<select name="jenis_kegiatan" id="jenis_kegiatan" class="form-control" style="max-width:50%;">											
									<option value="1" >Dalam Kantor</option>
									<option value="2" >Luar Kantor</option>
								</select>
						  </div>							  
						</div>	
								
						<div class="form-group">
						  <label class="col-lg-3 control-label" for="jenis_kegiatan">Ruangan/ Lokasi</label>
						  <div class="col-lg-7" id="div_ruangan"></div>							  
						</div>	
								
						<div class="form-group">
						  <label class="col-lg-3 control-label" for="penanggungjawab">Penanggungjawab</label>
						  <div class="col-lg-7" id="div_penanggungjawab"></div>				  
						</div>	
								
						<div class="form-group">
						  <label class="col-lg-3 control-label" for="acara">Acara</label>
						  <div class="col-lg-7">
							<input type="text" class="form-control" id="acara" name="acara" placeholder="Nama Acara">
						  </div>							  
						</div>	
						<div class="form-group">
							<label class="col-lg-3 control-label" for="tanggal">Tanggal</label>
							<div class="col-lg-4">
								<div class='input-group date' id='first_date'>
									<input type='text' name="first_date" class="form-control" />
									<span class="input-group-addon">
										<span class="fa fa-calendar"></span>
									</span>
								</div>
							</div>
							<div class="col-lg-4">
								<div class='input-group date' id='last_date'>
									<input type='text' name="last_date" class="form-control" />
									<span class="input-group-addon">
										<span class="fa fa-calendar"></span>
									</span>
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
	
   <!-- Mainbar ends -->
   <div class="clearfix"></div>
</div>
<!-- Content ends -->	
<!-- End CONTENT ===============================================-->
<?PHP $this->load->view('footer'); ?>	

<script type="text/javascript">
	var table;
	
  $(document).ready(function() {
    /* initialize the calendar
    -----------------------------------------------------------------*/
	load_select_filter();
	load_datetimepicker();
	
	table = $('#table').DataTable({ 			
		"processing": true, //Feature control the processing indicator.
		"serverSide": true, //Feature control DataTables' server-side processing mode.
		"deferLoading": 0, // here	
		"ordering": false,
		"bAutoWidth": false,
		"paging": true,
		"dom": 'Blrtip',
		"buttons": ['pdf', 'csv', 'excel', 'print'],
		
		// Load data for the table's content from an Ajax source
		"ajax": {
			"url": "agenda/data_list_approved",
			"url": "<?php echo site_url('agenda/data_list_approved/')?>",
			"type": "POST",
			"data": function ( d ) {
					var chkSearch = [];
					$.each($("input[name='chkSearch[]']:checked"), function(){
						chkSearch.push($(this).val());
					});
					
					d.chkSearch = chkSearch;
					var item_selectbox = document.getElementById('filter_tahun');
					d.filter_tahun = item_selectbox.options[item_selectbox.selectedIndex].value;	
					var item_selectbox = document.getElementById('filter_bulan');
					d.filter_bulan = item_selectbox.options[item_selectbox.selectedIndex].value;	
					var item_selectbox = document.getElementById('filter_ruangan');
					d.filter_ruangan = item_selectbox.options[item_selectbox.selectedIndex].value;									
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
			  "targets": [ 0,4,5,6 ], // your case first column
			  "className": "text-center",
			},
			{
			  "width": "8%",
			  "targets": [ 4,5 ],
			},
			{
			  "width": "3%",
			  "targets": [ 0 ],
			},
			{
			  "width": "12%",
			  "targets": [ 1,6 ],
			},
			{
			  "width": "17%",
			  "targets": [ 3 ],
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
  
	//function load select filter
	function load_select_filter(){
		 $.ajax({
				url : "<?php echo site_url('agenda/data_init/')?>" ,
				type: "POST",
				dataType: "JSON",
				success: function(data)
				{
					var d = new Date();
					var item_sel=["filter_tahun", "filter_bulan", "filter_ruangan"];
					var item_select = {"filter_tahun":d.getFullYear(), "filter_bulan":d.getMonth()+1, "filter_ruangan":-1};			
					select_box(data,item_select, item_sel);	
					reload_table();								
				},
				error: function (jqXHR, textStatus, errorThrown)
				{
					alert('Error get data from ajax');
				}
			});	
	}
	
	//function load datetimepicker
	function load_datetimepicker(){
		$('#first_date').datetimepicker({format: 'YYYY-MM-D HH:mm'});
		$('#last_date').datetimepicker({
			format: 'YYYY-MM-D HH:mm',
			useCurrent: false //Important! See issue #1075
		});
		$("#first_date").on("dp.change", function (e) {
			$('#last_date').data("DateTimePicker").minDate(e.date);
		});
		$("#last_date").on("dp.change", function (e) {
			$('#first_date').data("DateTimePicker").maxDate(e.date);
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
		
	function reload_table()
	{
	  table.ajax.reload(null,false); //reload datatable ajax 
	}												
	
	function data_search()			
	{							
		table.ajax.reload(null,false); //reload datatable ajax 
	}
	
	function data_edit(id)
	{
	  save_method = 'update';
	  $('#add_form')[0].reset(); // reset form on modals
	  $('#modal_message').html('');  //reset message	
	  $('[name="save_method"]').val(save_method);  
				
	  //Ajax Load data from ajax
	  $.ajax({			  		
			url : "<?php echo site_url('agenda/data_edit/')?>/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(data)
			{				   
				$('[name="id"]').val(data['list_agenda'].id);  
				$('[name="acara"]').val(data['list_agenda'].acara);
				$('[name="first_date"]').val(data['list_agenda'].first_date);
				$('[name="last_date"]').val(data['list_agenda'].last_date);
				$('[name="jenis_kegiatan"]').val(data['list_agenda'].id_klasifikasi_agenda).trigger("chosen:updated");
				select_update_jenis_kegiatan(data['list_agenda'].id_klasifikasi_agenda,data['list_agenda'].id_ruangan);
				select_update_agenda_user(data['list_agenda'].list_penanggungjawab);
				document.getElementById('jenis_kegiatan').disabled = true;
						
				$('#modalAddForm').modal('show'); // show bootstrap modal when complete loaded
				$('.modal-title').text('Edit Data'); // Set title to Bootstrap modal title					
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
		  form_data.append("jenis_kegiatan", document.getElementById('jenis_kegiatan').value);
					
		   // ajax adding data to database
			  $.ajax({
				url : "<?php echo site_url('agenda/data_save_validation/')?>",
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

	function select_jenis_kegiatan() {
		id_jenis_kegiatan = $("#div_jenis_kegiatan #jenis_kegiatan").val();
		if(id_jenis_kegiatan==1) {
			$.ajax({
				type: 'post',
				url: "<?php echo site_url('agenda/select_agenda_ruangan/')?>",
				dataType : 'json',
				success: function(data){
					div = '';
					div += '<select name="id_ruangan" id="id_ruangan" class="form-control" style="max-width:60%;">';
					jQuery.each(data.filter_ruangan, function(index, item) {
						div += '<option value="'+item.id_ruangan+'">'+item.nama_ruangan+'</option>';
					});
					div += '</select>';
					$("#div_ruangan").html(div);
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
		} else {
			div = '<input type="text" class="form-control" id="id_ruangan" name="id_ruangan" placeholder="Nama Lokasi">';
			$("#div_ruangan").html(div);
		}
	}
	
	
	function select_update_jenis_kegiatan(id,id_ruangan) {
		id_jenis_kegiatan = id;
		if(id_jenis_kegiatan==1) {
			$.ajax({
				type: 'post',
				url: "<?php echo site_url('agenda/select_agenda_ruangan/')?>",
				dataType : 'json',
				success: function(data){
					div = '';
					div += '<select name="id_ruangan" id="id_ruangan" class="form-control" style="max-width:60%;">';
					jQuery.each(data.filter_ruangan, function(index, item) {
						div += '<option value="'+item.id_ruangan+'">'+item.nama_ruangan+'</option>';
					});
					div += '</select>';
					$("#div_ruangan").html(div);
					$('[name="id_ruangan"]').val(id_ruangan).trigger("chosen:updated");
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
		} else {
			div = '<input type="text" class="form-control" id="id_ruangan" name="id_ruangan" placeholder="Nama Lokasi">';
			$("#div_ruangan").html(div);
			$('[name="id_ruangan"]').val(id_ruangan);
		}
	}
	
	function select_agenda_user() {
		$.ajax({
			type: 'post',
			url: "<?php echo site_url('agenda/select_agenda_user/')?>",
			dataType : 'json',
			success: function(data){
				div = '';
				div += '<select name="id_penanggungjawab[]" id="id_penanggungjawab" multiple="multiple">';
				var no = 0;
				jQuery.each(data.filter_agenda_user, function(index, item) {
					div += '<optgroup label="'+index+'">';
					jQuery.each(item, function(index2, item2) {
						div += '<option value="'+item2.kategori_id+'.'+item2.id+'.'+item2.group_id+'">'+item2.nama+' ['+item2.nama_group+']</option>';
					});
					div += '</optgroup>';
				});
				div += '</select>';
				$("#div_penanggungjawab").html(div);
				$("select#id_penanggungjawab").select2({
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
	
	function select_update_agenda_user(list_penanggungjawab) {
		$.ajax({
			type: 'post',
			url: "<?php echo site_url('agenda/select_agenda_user/')?>",
			dataType : 'json',
			success: function(data){
				div = '';
				div += '<select name="id_penanggungjawab[]" id="id_penanggungjawab" multiple="multiple">';
				var no = 0;
				jQuery.each(data.filter_agenda_user, function(index, item) {
					div += '<optgroup label="'+index+'">';
					jQuery.each(item, function(index2, item2) {
						div += '<option value="'+item2.kategori_id+'.'+item2.id+'.'+item2.group_id+'">'+item2.nama+' ['+item2.nama_group+']</option>';
					});
					div += '</optgroup>';
				});
				div += '</select>';
				$("#div_penanggungjawab").html(div);
				$select_penanggungjawab = $("select#id_penanggungjawab").select2({
					tags: true,
					width: '100%',
					tokenSeparators: [',', ' ']
				});
				
				var array_selected = [];
				jQuery.each(list_penanggungjawab, function(index, item) {					
					if(item.kategori_id==3){
						var $option = $("<option></option>").val(item.nama).text(item.nama);
						$('select#id_penanggungjawab').append($option).trigger('change');
						array_selected.push(item.nama);
					} else {
						array_selected.push(item.kategori_id+'.'+item.id+'.'+item.group_id);
					}
				});
				$select_penanggungjawab.val(array_selected).trigger('change');
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
			
</script>
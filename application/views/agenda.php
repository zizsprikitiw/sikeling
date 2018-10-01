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
						<button class="btn btn-success" onClick="data_add()"><i class="fa fa-plus"></i> Input Agenda</button>
					</div>				 																
				</div>
				<hr style="border-top: 1px solid #ccc;border-bottom: 1px solid #fff;" />
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
										  <th ><b>Status</b></th>
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
		
	<!-- Modal -->
	<div id="chatModal" class="modal fade" role="dialog">
		<div class="modal-dialog modal-sm">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="chatModalTitle">Percakapan</h4>
				</div>
				<div class="modal-body" id="chatModalBody">
					<div id="chatpanel" class="row"></div>
					<br>
					<form class="form-horizontal" role="form" id="chat_form" action="#" autocomplete="nope" enctype="multipart/form-data">
						<input type="hidden" value="" name="id_agenda"/> 
						<div class="row">
							<div class="col-md-12">
								<textarea name="chatmessage" style="width:100%; height: 80px;"></textarea>
							</div>
							<div class="col-md-12">
								<button type="button" id="btnSave" onClick="data_sendchat()" class="btn btn-sm btn-success right">Kirim</button>	
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
		
	<!-- Modal BEGIN:APPROVE AGENDA-->										
	<div id="modalApproveForm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">									
				<!-- Form starts.  -->	
				<form class="form-horizontal" role="form" id="approve_form" action="#">
				  <div class="modal-header">			                        
					<h4 class="modal-title">Approve</h4>
				  </div>
				  <div class="modal-body">
						<input type="hidden" value="" name="id_approve_data"/> 																					
						<div class="form-group" align="center">
							<div class="col-lg-12">
								<div id="approve_text"></div>																	  
							</div>																							
						</div> 
						<div class="form-group" align="center">
							<div class="col-lg-12">
								<b >Anda yakin ?!</b>	
							</div>	
						</div> 
						 <div id="modal_approve_message"></div>
				  </div>	<!--END modal-body-->
				  <div class="modal-footer">										
					<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>	
					<button type="button" id="btnDelete" onClick="data_approve('','')" class="btn btn-sm btn-success">Approve</button>								
				  </div>
			  </form>
			</div>	<!--END modal-content-->
		</div>	<!--END modal-dialog-->
	</div>
	<!-- Modal END:APPROVE DATA-->	
	
	<!-- Modal BEGIN:REJECT AGENDA-->										
	<div id="modalRejectForm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">									
				<!-- Form starts.  -->	
				<form class="form-horizontal" role="form" id="reject_form" action="#">
				  <div class="modal-header">			                        
					<h4 class="modal-title">Tolak</h4>
				  </div>
				  <div class="modal-body">
						<input type="hidden" value="" name="id_reject_data"/> 																					
						<div class="form-group" align="center">
							<div class="col-lg-12">
								<div id="reject_text"></div>																	  
							</div>																							
						</div> 
						<div class="form-group" align="center">
							<div class="col-lg-12">
								<b >Anda yakin ?!</b>	
							</div>	
						</div> 
						 <div id="modal_reject_message"></div>
				  </div>	<!--END modal-body-->
				  <div class="modal-footer">										
					<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>	
					<button type="button" id="btnDelete" onClick="data_reject('','')" class="btn btn-sm btn-success">Tolak</button>								
				  </div>
			  </form>
			</div>	<!--END modal-content-->
		</div>	<!--END modal-dialog-->
	</div>
	<!-- Modal END:APPROVE DATA-->		

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
			"url": "<?php echo site_url('agenda/data_list/')?>",
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
	
	$('#agenda').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek'
      },
	  timeFormat: 'H:mm{ - H:mm}',
	  theme: true,
      defaultDate: Date.now(),
      editable: false,
	  navLinks: true, // can click day/week names to navigate views
      eventLimit: true, // allow "more" link when too many events
	  events: function(start, end, callback) {
            $.ajax({
                type: 'POST',
                url: 'agenda/data_json',
                dataType:'json',
                crossDomain: true,
                data: {
                    // our hypothetical feed requires UNIX timestamps
                    start: Math.round(start.getTime() / 1000),
                    end: Math.round(end.getTime() / 1000),                  
                    'acc':'2',                      
                },
                success: function(data) {                            
                    var events = [];
                    var allday = null; //Workaround
					$.each(data.event, function(i, item) {
						if($(this).attr('allDay') == "false") //Workaround 
                                allday = false; //Workaround 
                        if($(this).attr('allDay') == "true") //Workaround 
                                allday = true; //Workaround                     

                        events.push({
                            id: item.id,
                            title: item.title,
                            tip: item.tip,
                            start: item.start,
                            end: item.end,                       
                            allDay: allday,
							color: item.color,
							description: item.title
                        });             
					});              
                    callback(events);
                }
            });     
        }
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
			
	function data_add()
	{			  				  
		save_method = 'add';
		$('#add_form')[0].reset(); // reset form on modals		  
		$('#modal_message').html('');  //reset message
		document.getElementById('jenis_kegiatan').disabled = false;
		$('[name="save_method"]').val(save_method);  
				  
		$('#modalAddForm').modal('show'); // show bootstrap modal
		$('.modal-title').text('Tambah Data'); // Set Title to Bootstrap modal title		
		select_jenis_kegiatan();
		select_agenda_user();
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
				url : "<?php echo site_url('agenda/data_delete')?>/",
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
	
	function data_approve(id, nama_id)
	{
		if(id != ''){
			//show modal confirmation
			$('#approve_form')[0].reset(); // reset form on modals
			$('#modal_approve_message').html('');  //reset message
			
			$('[name="id_approve_data"]').val(id);
			$('#approve_text').html('<b >' + nama_id + '</b>');	
			$('#modalApproveForm').modal('show'); // show bootstrap modal when complete loaded
			$('.modal-title').text('Approve Agenda'); // Set Title to Bootstrap modal title	
		}else{
			//lakukan approve data
			// ajax approve data to database
			$.ajax({
				url : "<?php echo site_url('agenda/data_approve')?>/",
				type: "POST",
				data: $('#approve_form').serialize(),
				dataType: "JSON",
				success: function(data)
				{
				   //if success close modal and reload ajax table
				   if(data['status'] == true){
						//berhasil approve							
						 $('#modalApproveForm').modal('hide');					   		
						 $('#page_message').html('<div class="alert alert-info">' + data['message'] + '.</div>');
						reload_table();
				   }else{
						//form validation
						$('#modal_approve_message').html('<div class="alert alert-info">' + data['message'] + '</div>');							
				   }					   					  
				},
				error: function (jqXHR, textStatus, errorThrown)
				{						
					alert('Error adding / update data');									
				}
			});					
		}				
	}	
	
	function data_reject(id, nama_id)
	{
		if(id != ''){
			//show modal confirmation
			$('#reject_form')[0].reset(); // reset form on modals
			$('#modal_reject_message').html('');  //reset message
			
			$('[name="id_reject_data"]').val(id);
			$('#reject_text').html('<b >' + nama_id + '</b>');	
			$('#modalRejectForm').modal('show'); // show bootstrap modal when complete loaded
			$('.modal-title').text('Tolak Agenda'); // Set Title to Bootstrap modal title	
		}else{
			//lakukan approve data
			// ajax approve data to database
			$.ajax({
				url : "<?php echo site_url('agenda/data_reject')?>/",
				type: "POST",
				data: $('#reject_form').serialize(),
				dataType: "JSON",
				success: function(data)
				{
				   //if success close modal and reload ajax table
				   if(data['status'] == true){
						//berhasil approve							
						 $('#modalRejectForm').modal('hide');					   		
						 $('#page_message').html('<div class="alert alert-info">' + data['message'] + '.</div>');
						reload_table();
				   }else{
						//form validation
						$('#modal_approve_message').html('<div class="alert alert-info">' + data['message'] + '</div>');							
				   }					   					  
				},
				error: function (jqXHR, textStatus, errorThrown)
				{						
					alert('Error adding / update data');									
				}
			});					
		}				
	}	
	
	function data_read(id)
	{
		//lakukan read data
		// ajax read data to database
		$.ajax({
			url : "<?php echo site_url('agenda/data_read')?>/",
			type: "POST",
			data: {"id":id},
			dataType: "JSON",
			success: function(data)
			{
			   //if success close modal and reload ajax table
			   if(data['status'] == true){
					//berhasil baca										   		
					 $('#page_message').html('<div class="alert alert-info">' + data['message'] + '.</div>');
					reload_table();
			   }else{
					//gagal baca
					$('#page_message').html('<div class="alert alert-info">' + data['message'] + '</div>');							
			   }					   					  
			},
			error: function (jqXHR, textStatus, errorThrown)
			{						
				alert('Error adding / update data');									
			}
		});			
	}

	function data_chatmodal(id)
	{
		//show modal confirmation
		$('#chat_form')[0].reset(); // reset form on modals
		$('#chatModalBody #chatpanel').html('');  //reset message
		$('#chatModal').modal('show'); // show bootstrap modal when complete loaded
		$('#chatModal .modal-title').text('Percakapan'); // Set Title to Bootstrap modal title	
		$('[name="id_agenda"]').val(id);  
		
		$.ajax({
			url : "<?php echo site_url('agenda/data_chatmodal')?>/",
			type: "POST",
			data: {"id":id},
			dataType: "JSON",
			success: function(data)
			{
				div = '';
				if(data.list_chat.length>0) {
					jQuery.each(data.list_chat, function(index, item) {
						div += '<div class="col-md-12 '+(item.status==1?'text-right':'text-left bold')+'">';
						div += '<div style="font-size:7pt;">'+item.submit_date+'</div>';
						div += item.message;
						div += '<hr style="border-top: 1px solid #ccc;border-bottom: 1px solid #fff;" /></div>';
					});
				} else {
					div += '<div class="col-md-12">Tidak ada percakapan</div>';
				}
				$("#chatpanel").html(div);
				
				scrollToBottomChat();
			},
			error: function (jqXHR, textStatus, errorThrown)
			{						
				alert('Error adding / update data');									
			}
		});				
	}	
	
	function data_sendchat()
	{				 								  
		var form = document.getElementById('chat_form');					  
		var form_data = new FormData(form);	
			
		if($('[name="chatmessage"]').val().length>0) {			
			// ajax adding data to database
			$.ajax({
				url : "<?php echo site_url('agenda/data_sendchat/')?>",
				type: "POST",
				data: form_data,
				processData: false,
				contentType: false,
				dataType: "JSON",
				success: function(data)
				{
					if(data.list_chat.id_percakapan>0) {
						div += '<div class="col-md-12 '+(data.list_chat.status==1?'text-right':'text-left bold')+'">';
						div += '<div style="font-size:7pt;">'+data.list_chat.submit_date+'</div>';
						div += data.list_chat.message;
						div += '<hr style="border-top: 1px solid #ccc;border-bottom: 1px solid #fff;" /></div>';
					}   
					$('#chatModalBody #chatpanel').html('');  //reset message
					$("#chatpanel").append(div);
					scrollToBottomChat();
					$('[name="chatmessage"]').val('');  
				},
				error: function (jqXHR, textStatus, errorThrown)
				{						
					alert('Error adding / update data');									
				}
			});
		}
	}

	function scrollToBottomChat() {
		var relative = document.getElementById("chatpanel");
		relative.scrollTop = relative.scrollHeight;
	}
</script>
<style>
	#chatpanel {
		position: relative;
		overflow-y: auto;
		max-height: 200px;
	}

  #external-events {
    float: left;
    width: 150px;
    padding: 0 10px;
    border: 1px solid #ccc;
    background: #eee;
    text-align: left;
  }

  #external-events h4 {
    font-size: 16px;
    margin-top: 0;
    padding-top: 1em;
  }

  #external-events .fc-event {
    margin: 10px 0;
    cursor: pointer;
  }

  #external-events p {
    margin: 1.5em 0;
    font-size: 11px;
    color: #666;
  }
  #external-events p input {
    margin: 0;
    vertical-align: middle;
  }

</style>
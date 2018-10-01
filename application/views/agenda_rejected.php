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
										  <th ><b>Tgl Rejected</b></th>
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
			"url": "<?php echo site_url('agenda/data_list_rejected/')?>",
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
			  "targets": [ 0,4,5 ], // your case first column
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
			  "targets": [ 1 ],
			},
			{
			  "width": "17%",
			  "targets": [ 3 ],
			},
		],

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
	
</script>
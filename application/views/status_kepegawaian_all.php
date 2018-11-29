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
							<label class="col-sm-2 checkbox-inline" style="max-width:90px; padding-left:40px; vertical-align:middle;"><b>
							  <input type="checkbox" name="chkSearch[]" id="chkSearch[]" value="tahun"> Tahun:</b>
							</label>								
							<div class="col-sm-4" align="left" style="text-align:left; padding-left:0px; max-width:100px;">                               
								<select name="filter_tahun" id="filter_tahun" class="form-control">
									<option value="" >-- Pilih --</option>
								</select> 
							</div>
							<label class="col-sm-2 checkbox-inline" style="max-width:135px; padding-left:40px; vertical-align:middle;"><b>
							  <input type="checkbox" name="chkSearch[]" id="chkSearch[]" value="jenis_usulan"> Jenis Usulan:</b>
							</label>								
							<div class="col-sm-4" align="left" style="text-align:left; padding-left:0px; max-width:200px;">                               
								<select name="filter_jenisusulan" id="filter_jenisusulan" class="form-control">
									<option value="" >-- Pilih --</option>
								</select> 
							</div>
							<label class="col-sm-2 checkbox-inline" style="max-width:110px; padding-left:40px; vertical-align:middle;"><b>
							  <input type="checkbox" name="chkSearch[]" id="chkSearch[]" value="no_surat"> No Surat:</b>
							</label>
							<div class="col-sm-3" align="left" style="text-align:left; max-width:130px; padding-left:0px;">                               
								<input type="text" class="form-control" id="no_surat" name="no_surat" placeholder="No Surat">
							</div>
							<label class="col-sm-2 checkbox-inline" style="max-width:90px; padding-left:40px; vertical-align:middle;"><b>
							  <input type="checkbox" name="chkSearch[]" id="chkSearch[]" value="nama"> Nama:</b>
							</label>
							<div class="col-sm-3" align="left" style="text-align:left; max-width:150px; padding-left:0px;">                               
								<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama">
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
										  <th ><b>No Surat</b></th>
										  <th ><b>Nama</b></th>
										  <th ><b>Usulan</b></th>
										  <th ><b>History</b></th>
										  <th ><b>Keterangan</b></th>
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
			"url": "<?php echo site_url('status_kepegawaian/data_list_all/')?>",
			"type": "POST",
			"data": function ( d ) {
					var chkSearch = [];
					$.each($("input[name='chkSearch[]']:checked"), function(){
						chkSearch.push($(this).val());
					});
					
					d.chkSearch = chkSearch;
					var item_selectbox = document.getElementById('filter_tahun');
					d.filter_tahun = item_selectbox.options[item_selectbox.selectedIndex].value;	
					var item_selectbox = document.getElementById('filter_jenisusulan');
					d.filter_jenisusulan = item_selectbox.options[item_selectbox.selectedIndex].value;								
					d.no_surat = document.getElementById('no_surat').value;							
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
			  "targets": [ 0 ], // your case first column
			  "className": "text-center",
			},
			{
			  "width": "15%",
			  "targets": [ 3,5 ],
			},
			{
			  "width": "3%",
			  "targets": [ 0 ],
			},
			{
			  "width": "10%",
			  "targets": [ 1 ],
			},
			{
			  "width": "20%",
			  "targets": [ 4 ],
			},
		],
		"drawCallback": function( settings ) {
			$(".m-more-less-content .m-show-more").click(function(){
				$(this).parent().addClass("m-display-more");
			});
			$(".m-more-less-content .m-show-less").click(function(){
				$(this).parent().removeClass("m-display-more");
			});
			
			$(".m-more-less-content").each(function (i, e){
				var html = $(e).html();
				var contentArray = html.split("<!--more-->");
				//console.log(contentArray);
				if (contentArray.length == 2) {
					html = contentArray[0] + '<span class="m-show-more"></span><span class="m-more-text">' + contentArray[1] + '</span><span class="m-show-less"></span>';
					$(e).html(html);
					$(e).find(".m-show-more").click(function(){
						$(this).parent().addClass("m-display-more");
					});
					$(e).find(".m-show-less").click(function(){
						$(this).parent().removeClass("m-display-more");
					});
				}
			});
		}

	});

  });
  
	//function load select filter
	function load_select_filter(){
		 $.ajax({
				url : "<?php echo site_url('status_kepegawaian/data_init/')?>" ,
				type: "POST",
				dataType: "JSON",
				success: function(data)
				{
					var d = new Date();
					var item_sel=["filter_tahun", "filter_jenisusulan"];
					var item_select = {"filter_tahun":d.getFullYear(), "filter_jenisusulan":-1};			
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
<style>
	.m-more-less-content span {
		display: inline;
	}
	.m-more-less-content .m-show-more, .m-show-less {
		color: blue;
		cursor: pointer;
		font-style: italic;
	}
	.m-more-less-content .m-show-more:before {
		content: " ... selengkapnya";
	}
	.m-more-less-content .m-more-text {
		overflow: hidden;
		display: none;
	}
	.m-more-less-content .m-show-less {
		display: none;
	}
	.m-more-less-content .m-show-less:before {
		content: " sembunyikan";
	}
	.m-more-less-content.m-display-more .m-show-more {
		display: none;
	}
	.m-more-less-content.m-display-more .m-more-text {
		display: inline;
	}
	.m-more-less-content.m-display-more .m-show-less {
		display: inline;
	}
</style>
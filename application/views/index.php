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
					<div class="col-md-8"> 
						<div class="row">
							<div class="col-md-12">
								<h2> Selamat <span class="color bold">Datang</span>,</h2>
						<p class="meta">di <span class="color bold" style="color:#FF6600">Sistem Manajemen</span> <span class="color bold" style="color:#0000FF">Proyek Pusat Teknologi Penerbangan</span>, Lembaga Penerbangan dan Antariksa Nasional (LAPAN).</p>	
							</div> 							
						</div>	
						<ul class="nav nav-tabs" id="myTab" role="tablist" style="margin-top:10px;">
							<li class="nav-item active">
								<a class="nav-link active" id="kalender-tab" data-toggle="tab" href="#tab_kalender" role="tab" aria-controls="tab_kalender" aria-selected="true">Kalender Agenda</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="tabel-tab" data-toggle="tab" href="#tab_tabel" role="tab" aria-controls="tab_tabel" aria-selected="false">Detail Agenda</a>
							</li>
						</ul>
						<div class="tab-content" id="myTabContent">
							<div class="tab-pane fade in active" id="tab_kalender" role="tabpanel" aria-labelledby="kalender-tab">
								<div id="agenda2" style="margin-top:10px; width:100%"></div>
							</div>
							<div class="tab-pane fade" id="tab_tabel" role="tabpanel" aria-labelledby="tabel-tab">
								<!-- Table Page -->
								<div class="page-tables" style="margin-top:10px;">
									<!-- Table -->
									<div class="table-responsive">
										<table class="table-hover table-bordered" cellpadding="0" cellspacing="0" border="0" id="table">
											<thead style="background-color:#006699; color:#FFFFFF;">
												<tr>
												  <th><b>No</b></th>
												  <th><b>Tanggal</b></th>
												  <th><b>Agenda</b></th>
												  <th><b>Keterangan</b></th>
												</tr>
											</thead>													
										</table>						
										<div class="clearfix"></div>									
									</div>
								</div>
								<!-- Table Page -->	
							</div>
						</div>
						<hr />
						<br />		
						<div class="row" id="row_list_proyek2">		
							<div class="col-md-12">
								<table class="table" id="list_proyek2">                   
								<tr >							
								  <td style="vertical-align:middle"></td>							 
								</tr> 						                                                        
							  </table> 
							</div>														
						</div>																																				
					</div>	<!--End Col-->
					
					<div class="col-md-4">
						<!-- Widget -->
						<div class="widget" id="row_list_proyek">
						<!-- Widget head -->
							<div class="widget-head">
								<div class="pull-left">Program</div>						    
								<div class="clearfix"></div>
							</div>              		
							<!-- Widget content -->
							<div class="widget-content">
								<div class="row">		
									<div class="col-md-12">
										<table class="table" id="list_proyek">                   
										<tr >							
										  <td style="vertical-align:middle"></td>							 
										</tr> 						                                                        
									  </table> 
									</div>														
								</div>	
							</div>
						</div>					
						<!-- Widget ends -->
						
						<!-- Widget -->
						<div class="widget">
						<!-- Widget head -->
							<div class="widget-head">
								<div class="pull-left">Berita</div>						    
								<div class="clearfix"></div>
							</div>              		
							<!-- Widget content -->
							<div class="widget-content">
								<div class="padd" id="div_list_berita">								 														
			
								</div>
							</div>
						</div>					
						<!-- Widget ends -->
					</div>					
				</div>	<!--End Row-->				
								 																
			<div class="clearfix">&nbsp;</div>		  												
			</div><!-- Containers ends -->
		 </div><!-- Matter ends -->
		
		<script type="text/javascript">
			var monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
			var tahun_proyek=[];
			var start_year;
			var end_year;
			var modaltable;
			var myIndex = 0;
			var timerCarousel;
			var checkCarousel = 0;
			
			function cetak_td(i, j, tahun, temp_list)
			{
				var len_tahun_proyek = tahun_proyek.length;
				
				td = '<tr><td width="150px" rowspan="' + i + '" style="padding-top:5px; border-top :none !important">';
				td = td + '<div class="well" align="center" style="display:inline-block; width:100%; height:110%; ">';															
					
				td = td + '	<div class="row" style="height:68%; ">';
				td = td + '		<div class="col-md-12" style="vertical-align:middle; padding-bottom:5px; padding-top:5px;">';
				td = td + '			<h1><span class="color bold" style="color:#FF6600">' + tahun + '</span></h1>';
				td = td + '		</div>';										
				td = td + '	</div>';				
														
				td = td + '</div>';
				td = td + '</td>';								
				td = td + temp_list[0] + '</tr>';							
		        
				//cetak baris lainnya
				for(var k=1; k<i; k++){
					td = td + '<tr>' + temp_list[k] + '</tr>';							 									
				}
				
				td = td + '<tr ><td colspan="2" style="border-width:2px; border-bottom-color:#66FF33" ></td></tr>';
				
				return td;
			}
			
			function load_proyek()
			{			
				var form_data = {
						start_year: start_year,
						end_year: end_year		
					};
					
				$.ajax({
					url : "<?php echo site_url('index/load_proyek/')?>" ,
					type: "POST",
					dataType: "JSON",
					data: form_data,
					success: function(data)
					{												
						var sel = $("#list_proyek");						
						sel.empty();
												
						var len_sub = data['list_proyek'].length;
						htmlString = "";					
						var tahun = data['list_proyek'][0].tahun;
						var i=0;
						var temp_list=[];
							
						for(var j=0; j<len_sub; j++){
							if(data['list_proyek'][j].tahun == tahun){
								//insert array
								temp_list[i] = 	'<td style="vertical-align:middle"><b>'+data['list_proyek'][j].nama_proyek+'</b><p>'+data['list_proyek'][j].posisi+'</p></td>';
								i++;
								
								if(j == len_sub-1){
									htmlString = htmlString + cetak_td(i, j, tahun, temp_list);
								}
							}else{
								//cetak td																
								htmlString = htmlString + cetak_td(i, j, tahun, temp_list);
								
								//RESET array
								tahun = data['list_proyek'][j].tahun;
								i=0;
								var temp_list=[];
								temp_list[i] = 	'<td style="vertical-align:middle"><b>'+data['list_proyek'][j].nama_proyek+'</b><p>'+data['list_proyek'][j].posisi+'</p></td>';
								i++;
							}							
						}//end loop j
						
						sel.html(htmlString); 
																											
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						alert('Error get data from ajax');
					}
				});	
			}
			
			function init_tahun_proyek()
			{
				$.ajax({
					url : "<?php echo site_url('index/init_tahun_proyek/')?>" ,
					type: "POST",
					dataType: "JSON",					
					success: function(data)
					{						
						var len_sub = data['tahun_proyek'].length;						
						for(var j=0; j<len_sub; j++){
							tahun_proyek[j] = data['tahun_proyek'][j].tahun;
							//alert('tahun '+tahun_proyek[j]);
						}
						
						if(len_sub > 0){
							if(len_sub == 1){
								start_year = tahun_proyek[0];
								end_year = tahun_proyek[0];
							}else{								
								end_year = tahun_proyek[0];
								start_year = tahun_proyek[1];
							}										
							
							load_proyek();
						}else{
							//hidden div proyek
							document.getElementById('row_list_proyek').style.display = "none";							
						}												
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						alert('Error get data from ajax');
					}
				});	
			}
			
			function load_berita()
			{
				$.ajax({
					url : "<?php echo site_url('index/load_berita/')?>" ,
					type: "POST",
					dataType: "JSON",					
					success: function(data)
					{	
						var len_berita = data['list_berita'].length;																		
						var sel = $("#div_list_berita");
						sel.empty(); 						
						
						if(len_berita >0){
							html_string = "";							
							for(var j=0; j<len_berita; j++){								
								var mydate = new Date(data['list_berita'][j].tanggal_submit);
								var myMonth = monthNames[mydate.getMonth()];
								html_string = html_string + '<div class="testi-two">';
								html_string = html_string + '   <div class="test"><a href="javascript:void()" title="Detail Berita" onclick="detail_berita('+data['list_berita'][j].id+')">'+data['list_berita'][j].judul+'</a></div>';						  
								html_string = html_string + '   <div class="test-arrow"></div>';
								html_string = html_string + '   <div class="tauth"><i class="fa fa-user"></i> '+data['list_berita'][j].nama+', <span class="color">'+mydate.getDate()+' '+myMonth+' '+mydate.getFullYear()+'</span></div>';
								html_string = html_string + '</div><hr />';
							}
						}						
							
						sel.html(html_string);														  										
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						alert('Error get data from ajax');
					}
				});	
			}
			
			function detail_berita(id_berita)
			{										
			  //Ajax Load data from ajax
			  $.ajax({			  		
					url : "<?php echo site_url('index/detail_berita/')?>/" + id_berita,
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
			
			function agenda_tabel()
			{
				table = $('#table').DataTable({ 			
					"processing": true, //Feature control the processing indicator.
					"serverSide": true, //Feature control DataTables' server-side processing mode.
					"ordering": false,
					"paging": true,
					"bAutoWidth": false,
					"dom": 'Blrtip',
					//"buttons": [],
					buttons: [
						{
							className: 'btn btn-success',
							text: '<i class="fa fa-expand"></i> Fullscreen',
							init: function( api, node, config) {
								   $(node).removeClass('dt-button')
							},
							action: function ( e, dt, node, config ) {
								layar_modal();
							}
						}
					],
					
					// Load data for the table's content from an Ajax source
					"ajax": {
						"url": "agenda/data_agenda_tabel",
						"type": "POST",
					},
					"aoColumns": [
						{ "sWidth": "2%", "className": "text-center" }, // 1st column width 
						{ "sWidth": "18%" }, // 2nd column width 
						{ "sWidth": "null" }, // 3rd column width
						{ "sWidth": "25%" } // 4th column width 
                    ],
				});
			}

			function layar_modaltabel()
			{
				modaltableagenda = $('#modaltableagenda').DataTable({ 			
					"processing": true, //Feature control the processing indicator.
					"serverSide": true, //Feature control DataTables' server-side processing mode.
					"ordering": false,
					"paging": false,
					"bAutoWidth": false,
					"lengthChange": false,
					"searching": false,
					"bInfo": false,
					"dom": 'Blrtip',
					"buttons": [],
					
					// Load data for the table's content from an Ajax source
					"ajax": {
						"url": "agenda/data_agenda_modal",
						"type": "POST",
					},
					"aoColumns": [
						{ "sWidth": "2%", "className": "text-center" }, // 1st column width 
						{ "sWidth": "16%" }, // 2nd column width 
						{ "sWidth": "null" }, // 3rd column width
						{ "sWidth": "28%" } // 4th column width 
                    ]
				});
				
				modaltablestatuskepegawaian = $('#modaltablestatuskepegawaian').DataTable({ 			
					"processing": true, //Feature control the processing indicator.
					"serverSide": true, //Feature control DataTables' server-side processing mode.
					"ordering": false,
					"paging": false,
					"bAutoWidth": false,
					"lengthChange": false,
					"searching": false,
					"bInfo": false,
					"dom": 'Blrtip',
					"buttons": [],
					
					// Load data for the table's content from an Ajax source
					"ajax": {
						"url": "status_kepegawaian/data_list_modal",
						"type": "POST",
					},
					"aoColumns": [
						{ "sWidth": "2%", "className": "text-center" }, // 1st column width 
						{ "sWidth": "null" }, // 3rd column width
						{ "sWidth": "20%" }, // 4rd column width
						{ "sWidth": "10%" }, // 5rd column width
						{ "sWidth": "10%" }, // 6th column width 
						{ "sWidth": "20%" } // 7th column width 
                    ]
				});
			}
			
			function agenda_calendar()
			{
				var currentYear = new Date().getFullYear();
				var currentMonth = new Date().getMonth();
				var currentDate = new Date().getDate();
				var currentDateTime = new Date(currentYear, currentMonth, currentDate).getTime();
				
				$('#agenda2').calendar({ 
					enableContextMenu: true,
					enableRangeSelection: true,
					language: 'id',
					mouseOnDay: function(e) {
						if(e.events.length > 0) {
							var content = '';
							var no = 1;
							for(var i in e.events) {
								content += '<div class="event-tooltip-content">'
												+ '<div class="event-name bold" style="color:' + e.events[i].color + '">' + no + '. ' + e.events[i].name + '</div>'
												+ '<div class="event-location">' + e.events[i].keterangan + '</div>'
											+ '</div>';
								no++;
							}
						
							$(e.element).popover({ 
								trigger: 'manual',
								container: 'body',
								html:true,
								content: content
							});
							
							$(e.element).popover('show');
						}
					},
					mouseOutDay: function(e) {
						if(e.events.length > 0) {
							$(e.element).popover('hide');
						}
					},
					dayContextMenu: function(e) {
						$(e.element).popover('hide');
					},
					//dataSource: events,
					customDayRenderer: function(element, date) {
						if(date.getTime() == currentDateTime) {
							$(element).css('background-color', 'rgba(255, 102, 33, 0.6)');
							$(element).css('color', 'white');
							$(element).css('border-radius', '15px');
						}
					},
					yearChanged: function(e) {
						e.preventRendering = true;

						$(e.target).append('<div style="text-align:center">Loading...</div>');
						
						$.ajax({
							type: 'POST',
							url: 'agenda/data_json',
							dataType:'json',
							data: {'year':e.currentYear},
							crossDomain: true,
							success: function(data) {   
								var events = [];
								$.each(data.event, function(i, item) {         
									events.push({
										name: item.title,
										startDate: new Date(e.currentYear, new Date(item.start).getMonth(), new Date(item.start).getDate()),
										endDate: new Date(e.currentYear, new Date(item.end).getMonth(), new Date(item.end).getDate()),  
										//color: item.color,
										keterangan: item.keterangan
									});  
								}); 
								$(e.target).data('calendar').setDataSource(events);
							} 
						});
					}
				});
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
				
				if(checkCarousel==0) {
					window.setTimeout(layar_carousel, 10000);
				} else {
					window.clearTimeout(timerCarousel);
				}
			}
			
			
			function refresh_layartabel()
			{
				cleardurasi();
				modaltableagenda.ajax.reload(null,false); //reload datatable ajax 
				modaltablestatuskepegawaian.ajax.reload(null,false); //reload datatable ajax 
				text_berjalan();
			}
			
			function layar_modal()
			{			  		
				$('#layarModal').modal('show'); // show bootstrap modal
				$('.modal-title').text('Informasi Pustekbang'); // Set Title to Bootstrap modal title
				layar_carousel();
				refresh_layartabel();
				text_berjalan();
				//layar_modaltabel();
			}	
			
			function layar_carousel() {
				var i;
				var x = document.getElementsByClassName("mySlides");
				for (i = 0; i < x.length; i++) {
				   x[i].style.display = "none";  
				}
				myIndex++;
				if (myIndex > x.length) {myIndex = 1}   
				x[myIndex-1].style.display = "block";   
				timerCarousel = window.setTimeout(layar_carousel, 10000); // Change image every 2 seconds
			}
			
			function pause_layar_carousel() {
				if(checkCarousel==0) {
					window.clearTimeout(timerCarousel);
					checkCarousel=1;
					$('#btn_pause_carousel').html('<i class="fa fa-play"></i>');
				} else {
					window.setTimeout(layar_carousel, 10000);
					checkCarousel=0;
					$('#btn_pause_carousel').html('<i class="fa fa-pause"></i>');
				}
			}
			
			function text_berjalan() {
				$.ajax({
					url : "<?php echo site_url('status_kepegawaian/data_text_berjalan/')?>" ,
					type: "POST",
					dataType: "JSON",					
					success: function(data)
					{																		
						var sel = $("#layarModal #modalTitle");
						sel.empty(); 							
							
						sel.html('<marquee scrolldelay="150"><h3 class="modal-title text-center">'+data.text_berjalan+'</h3></marquee>');														  										
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						alert('Error get data from ajax');
					}
				});	
			}

			$(document).ready(function() {	
				init_tahun_proyek();
				load_berita();
				agenda_calendar();
				agenda_tabel();
				layar_modaltabel();
			});    
				
									
		</script>
		
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
		
		<!-- Modal -->
		<div id="layarModal" class="modal fade" role="dialog">
			<div class="modal-dialog modal-lg">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<div width="100%" id="modalTitle"><marquee scrolldelay="100"><h3 class="modal-title text-center">Modal Header</h3></marquee></div>
					</div>
					<div class="modal-body" id="modalBody">
						<div class="w3-content w3-section">
							<div class="mySlides">
								<!-- Table Page -->
								<div class="page-tables">
									<!-- Table -->
									<div class="table-responsive">
										<table class="table-hover table-bordered" cellpadding="0" cellspacing="0" border="0" id="modaltableagenda">
											<thead style="background-color:#006699; color:#FFFFFF;">
												<tr>
												  <th style="width:10px"><b>No</b></th>
												  <th><b>Tanggal</b></th>
												  <th><b>Agenda Pustekbang</b></th>
												  <th><b>Keterangan</b></th>
												</tr>
											</thead>													
										</table>						
										<div class="clearfix"></div>									
									</div>
								</div>
								<!-- Table Page -->	
							</div>
							<div class="mySlides">
								<!-- Table Page -->
								<div class="page-tables">
									<!-- Table -->
									<div class="table-responsive">
										<table class="table-hover table-bordered" cellpadding="0" cellspacing="0" border="0" id="modaltablestatuskepegawaian">
											<thead style="background-color:#006699; color:#FFFFFF;">
												<tr>
												  <th style="width:10px"><b>No</b></th>
												  <th><b>Nama</b></th>
												  <th><b>Usulan</b></th>
												  <th><b>Posisi</b></th>
												  <th><b>Durasi</b></th>
												  <th><b>Keterangan</b></th>
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
					<div class="modal-footer">
						<button type="button" onClick="refresh_layartabel()" class="btn btn-sm btn-success"><i class="fa fa-refresh"></i></button>	
						<button type="button" onClick="pause_layar_carousel()" class="btn btn-sm btn-primary" id="btn_pause_carousel"><i class="fa fa-pause"></i></button>	
						<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>	
					 </div>
				</div>

			</div>
		</div>
		
    </div>

   <!-- Mainbar ends -->
   <div class="clearfix"></div>
</div>
<!-- Content ends -->	
<!-- End CONTENT ===============================================-->
<?PHP $this->load->view('footer'); ?>	
<style>
	.calendar .month-container {
		height: 240px;
	}
	#layarModal .modal-dialog {
	  width: 100%;
	  padding: 0;
	  margin: 0;
	}
	#layarModal .modal-content {
	  border-radius: 0;
	}
	#layarModal .modal-body {
	  min-height: 850px;
	}
	#layarModal{
		font-family: Arial, Helvetica, sans-serif;
		font-size: 13pt;
		line-height: 20px;
	}
	#layarModal tabel .label {
		font-size: 8pt;
		line-height: 20px;
	}
	div.dt-buttons{
		position:relative;
		float:right;
	}
	#modaltableagenda .label#today,#modaltableagenda .label#yesterday {
		opacity: 0;
		animation:myfirst 1s;
		-moz-animation:myfirst 1s infinite; /* Firefox */
		-webkit-animation:myfirst 1s infinite; /* Safari and Chrome */
	}
	@-moz-keyframes myfirst /* Firefox */ {
		0% {opacity: 0;}
		50% {opacity: 1;}
		100% {opacity: 0;}
	}
	@-webkit-keyframes myfirst /* Safari and Chrome */ {
		0% {opacity: 0;}
		50% {opacity: 1;}
		100% {opacity: 0;}
	}
	.nav-tabs > li.active > a, .nav-tabs > li.active > a:focus, .nav-tabs > li.active > a:hover {
		background-color: rgb(221, 221, 221);
	}
</style>
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
						<hr />
						<br />		
						<div class="row" id="row_list_proyek">								
							<div class="col-md-12">
								<table class="table" id="list_proyek">                   
								<tr >							
								  <td style="vertical-align:middle"></td>							 
								</tr> 						                                                        
							  </table> 
							</div>														
						</div>																																				
					</div>	<!--End Col-->
					
					<div class="col-md-4">
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
						<!-- Widget ends -->
					</div>					
				</div>	<!--End Row-->				
								 																
			<div class="clearfix">&nbsp;<br /><br /><br /><br /><br /><br /></div>		  												
			</div><!-- Containers ends -->
		 </div><!-- Matter ends -->
		
		<script type="text/javascript">
			var monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
			var tahun_proyek=[];
			var start_year;
			var end_year;
			
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
			
			$(document).ready(function() {	
				init_tahun_proyek();
				load_berita();
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
		
    </div>

   <!-- Mainbar ends -->
   <div class="clearfix"></div>
</div>
<!-- Content ends -->	
<!-- End CONTENT ===============================================-->
<?PHP $this->load->view('footer'); ?>	
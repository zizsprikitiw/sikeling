<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proyek extends CI_Controller {
	
	//Global variable
	var $table_note = 'note';
	var $table_proyek = 'proyek';
	var $table_note_view = 'v_users_note';
	var $table_users_posisi_view = 'v_users_posisi';
	var $approval_var = 'laporan_proyek';
	var $table_name_berita = 'berita';
	var $table_name_view_berita = 'v_berita';
	
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(array('ion_auth','form_validation'));
		$this->load->helper(array('url','language'));	
		$this->load->helper('download');		
		$this->load->helper('file');	

		$this->lang->load('auth');					
		$this->load->model('cms_model');						
		
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('login', 'refresh');			
		}		
	}
	
	public function index()
	{					
		$this->load->helper('date');				
		
		$this->data['user_menu'] = $this->cms_model->get_user_menu($this->uri->rsegment(1));
		$this->data['user'] = $this->ion_auth->user()->row();
				
		$tahun = mdate("%Y", time());				
		$this->data['tahun_proyek'] = $tahun;			
		
		$this->_render_page('proyek', $this->data);				
	}	
			
	public function data_init()
	{		
		$data['filter_tahun'] = $this->cms_model->get_tahun_proyek($this->table_proyek);  		
		echo json_encode($data);
	}
	
	public function year($tahun)
	{
		// set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		$this->data['user_menu'] = $this->cms_model->get_user_menu($this->uri->rsegment(1));
		$this->data['user'] = $this->ion_auth->user()->row();
		
		$tahun = $this->uri->rsegment(3);			
		$this->data['tahun_proyek'] = $tahun;			
		
		$this->_render_page('proyek', $this->data);		
	}
	
	public function data_list()
	{		
		$cont_name = $this->uri->rsegment(1);
		$pusat_id = $this->session->userdata('pusat_id');
		if($pusat_id == ''){$pusat_id=0;}
		$user_id = $this->session->userdata('user_id');
		
		$datatable_name = $this->table_proyek;
		$search_column = array('nama');
		$search_order = array('nama' => 'asc');
		$where =  array('tahun' => $_POST['filter_tahun'], 'pusat_id' => $pusat_id);
		$order_by = 'pusat_id asc, tahun asc, ref_id asc, no_urut asc';			
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];
		
		$main = array();
		$sub = array();
		$i = 0;
		$ref = 0;
		
		foreach ($list as $list_item) {
			if(trim($list_item->ref_id) == ''){
				$main[] = $list_item;
			}else{
				if($ref != $list_item->ref_id){
					//create new
					$i++;
					$j=1;
				}
				$ref = $list_item->ref_id;				
				$sub[$i][$j] = $list_item;				
				$j++;
			}
		}
		
		$sub_len = count($sub);
		$main_len = count($main);
		$i = -1;	//index array data		
		foreach($main as $main_item){
			$i++;
			$parent_id = $i;
			$has_sub = false;
			$data[$parent_id] = '';
	
			//cek sub menu
			for($j=1; $j<=$sub_len; $j++){
				if($main_item->id == $sub[$j][1]->ref_id){
					//sub main terkait
					//$k = 1;
					$has_sub = true;
					$sub_sub_len = count($sub[$j]);
					foreach($sub[$j] as $sub_item){
						$list_button = $this->cms_model->get_proyek_button($user_id, $sub_item->id);
						$i++;
						$row = array();						
						$str_item = '<div class="user-details" style="padding-left:50px;">
									<h4><b>'.$sub_item->nama.'</b></h4>';
						if(count($list_button) > 0){
							foreach($list_button as $button_item){
								$str_item = $str_item.'
									<a href="'.site_url($cont_name.'/proyek_detail/'.$sub_item->id.'/'.$button_item->functions_id).'" class="btn '.$button_item->button_class.' btn-xs" ><i class="'.$button_item->icon.'"></i> '.$button_item->nama.'</a>';
							}
						}
						
						$str_item = $str_item.'</div><div class="clearfix"></div>';																										
						$row[] = $str_item;												
						$data[$i] = $row;
					}
				}
			}//end loop sub menu
			
			//insert parent judul
			
			if($has_sub == true){
				$str_echo = '<div class="user-details">
								<h4><b>'.$main_item->nama.'</b></h4>									
						    </div>
							<div class="clearfix"></div>';		
			}else{
				$list_button = $this->cms_model->get_proyek_button($user_id, $main_item->id);
				$str_echo = '<div class="user-details">
								<h4><b>'.$main_item->nama.'</b></h4>';
				
				if(count($list_button) > 0){
					foreach($list_button as $button_item){						
						$str_echo = $str_echo.'
							<a href="'.site_url($cont_name.'/proyek_detail/'.$main_item->id.'/'.$button_item->functions_id).'" class="btn '.$button_item->button_class.' btn-xs" ><i class="'.$button_item->icon.'"></i> '.$button_item->nama.'</a>';
					}
				}		
				
				$str_echo = $str_echo.'</div><div class="clearfix"></div>';										
			}
			
			$row = array();
			$row[] = $str_echo;
			$data[$parent_id] = $row;
		}
						
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->cms_model->count_all_where($datatable_name, $where),
			"recordsFiltered" => $this->cms_model->count_filtered_where($datatable_name, $search_column, $search_order, $where, $order_by),
			"data" => $data,
				);
		//output to json format
		echo json_encode($output);		
	}

//BEGIN: PROYEK DETAIL 
//===========================================================================	

	public function proyek_detail()
	{								
		// set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		$this->data['user_menu'] = $this->cms_model->get_user_menu($this->uri->rsegment(1));
		$this->data['user'] = $this->ion_auth->user()->row();
		
		$cont_name = $this->uri->rsegment(1);
		$user_id = $this->session->userdata('user_id');
		$proyek_id = $this->uri->rsegment(3);
		$functions_id = $this->uri->rsegment(4);
		$functions_url = $this->cms_model->row_get_by_id($functions_id, 'functions')->url;	
		
		$proyek_item = $this->cms_model->row_get_by_id($proyek_id, $this->table_proyek);				
		$list_button = $this->cms_model->get_proyek_button($user_id, $proyek_item->id);
		//cetak info proyek
		$str_echo = '<div class="user-details">
						<h4><b>'.$proyek_item->nama.' ['.$proyek_item->tahun.']</b></h4>';
		
		if(count($list_button) > 0){
			foreach($list_button as $button_item){
				$str_echo = $str_echo.'
					<a href="'.site_url($cont_name.'/proyek_detail/'.$proyek_id.'/'.$button_item->functions_id).'" class="btn '.$button_item->button_class.' btn-xs"><i class="'.$button_item->icon.'"></i> '.$button_item->nama.'</a>';
			}
		}		
				
		$str_echo = $str_echo.'</div><div class="clearfix"></div>';	
		$this->data['proyek_detail'] = $str_echo;
		$this->data['button_url'] = $functions_url;	
		$this->data['proyek_id'] = $proyek_id;
		$this->data['proyek_tahun'] = $proyek_item->tahun;					
		
		$this->_render_page('proyek_detail', $this->data);	
	}
				
	public function load_select_posisi()
	{
		$user_id = $this->session->userdata('user_id');
		$proyek_id = $this->input->post('proyek_id');
		$datatable_name = $this->table_users_posisi_view;
		$search_column = "";
		$search_order = "";
		$where =  array('user_id' => $user_id, 'proyek_id' => $proyek_id);
		$order_by = 'eselon_struktural asc, no_urut_posisi asc';			
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);									
		$data = array();
		$data[] = array('id_item' => '0', 'nama_item' => '-- Pilih --');
		
		foreach($list as $list_item){
			$row = array();
			$row['id_item'] = $list_item->id;
			
			if(! is_null($list_item->struktural_id)){
				//struktural
				$row['nama_item'] = $list_item->nama_struktural;
			}else{
				//posisi kegiatan
				if((is_null($list_item->wp)) || ($list_item->wp == '')){
					$row['nama_item'] = $list_item->nama_posisi;
				}else{
					//group leader, leader, enginering staff
					if(! is_null($list_item->groups_leader_id)){
						$row['nama_item'] = $list_item->nama_posisi.' '.$list_item->groups_leader_nama.' ('.$list_item->wp.')';
					}elseif(! is_null($list_item->leader_id)){
						$row['nama_item'] = $list_item->nama_posisi.' '.$list_item->leader_nama.' ('.$list_item->wp.')';
					}else{
						$row['nama_item'] = $list_item->nama_posisi.' '.$list_item->leader_nama.' ('.$list_item->wp.')';	//enginering staff
					}
					
				}
			}									
			
			$data[] = $row;
		}		
		
		$bulan_laporan = $this->cms_model->get_list_bulan();  
		array_unshift($bulan_laporan, array('id_item' => '0', 'nama_item' => '-- Pilih --'));
		
		$output = array("users_posisi_id" => $data, "bulan_laporan" => $bulan_laporan);						
		echo json_encode($output);
	}
	
	public function data_list_note()
	{	
		$users_posisi_id = $this->input->post('users_posisi_id');
		$jenis_laporan = $this->input->post('jenis_laporan');
		$bulan_laporan = $this->input->post('bulan_laporan');				
		$where['proyek_id'] = $this->input->post('proyek_id');
		$where['active_flag'] = '1';	

		if($users_posisi_id != "0"){
			$where['users_posisi_id'] =  $users_posisi_id;	
			
			if($jenis_laporan != "0"){
				$where['status'] =  $jenis_laporan;					
			}	
			if($bulan_laporan != "0"){
				$where['bulan'] =  $bulan_laporan;					
			}			
		}else{
			$where['id'] = '0';	
		}								
						
		$datatable_name = $this->table_note_view;	
		$search_column = array('lower(judul)');
		$search_order = array('judul' => 'asc');
		$order_by = 'tanggal desc';								
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $list_item) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $list_item->judul;
			$row[] = '<a href="javascript:void()" onclick="show_laporan('."'".$list_item->id."'".')" >'.$list_item->filename.'</a>';																							
			$row[] = date_format(date_create($list_item->tahun."-".$list_item->bulan."-01"),"M Y");
			$row[] = date_format(date_create($list_item->tanggal),"j M Y, \J\a\m G:i");
			$row[] = '<a class="btn btn-xs btn-warning" href="javascript:void()" title="Revisi" onclick="data_revisi('."'".$list_item->id."'".')"><i class="fa fa-pencil"></i> Revisi</a>';
			$row[] = '<a href="'.site_url("/".$this->router->class."/download_file/".$list_item->id).'" class="btn btn-xs btn-success" title="Download" ><i class="fa fa-download"></i></a>';					
																						
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->cms_model->count_all_where($datatable_name, $where),
						"recordsFiltered" => $this->cms_model->count_filtered_where($datatable_name, $search_column, $search_order, $where, $order_by),
						"data" => $data,						
				);
		//output to json format
		echo json_encode($output);			
	}
	
	public function data_revisi()
	{			
		$data['bulan_laporan'] = $this->cms_model->get_list_bulan();  
		array_unshift($data['bulan_laporan'], array('id_item' => '0', 'nama_item' => '-- Pilih --'));	
		$data['note'] = $this->cms_model->row_get_by_id($this->input->post('note_id'), $this->table_note_view);		
		echo json_encode($data);
	}	
	
	public function data_save_validation()
	{								      				
		//set validation rules
		$this->form_validation->set_rules('users_posisi_id', 'Posisi', "callback_check_posisi_lap"); 
		$this->form_validation->set_rules('judul', 'Judul', 'required');
		$this->form_validation->set_rules('bulan_laporan', 'Bulan', "callback_check_bulan_lap"); 		
		$this->form_validation->set_rules('file_laporan', 'File Laporan', 'callback_check_file_lap');
						
		if ($this->form_validation->run() == FALSE)
        {            
			//validation fails
			echo json_encode(array("status" => validation_errors()));            
        }
        else
        {    									
			$list = $this->cms_model->row_get_by_id($this->input->post('users_posisi_id'), 'users_posisi');	
			$user_id = $list->user_id;					
						
			if(! is_null($list->posisi_id)){
				$posisi_id = $list->posisi_id;
			}else{
				$posisi_id = $list->struktural_id.'_struktural';
			}
			
			//UPLOAD FILE			
			$upload_path = $this->cms_model->set_folder_laporan($list->proyek_id, $posisi_id, $user_id);
			
			if(!is_dir($upload_path)){
				mkdir($upload_path,0777);
			}
			
			$file_name = $_FILES["file_laporan"]["name"];
			$file_name = preg_replace("/ /", '_', $file_name);
			$file_name = preg_replace("/&/", '_', $file_name);
			$file_name = preg_replace("/{/", '_', $file_name);
			$file_name = preg_replace("/}/", '_', $file_name);
			$upload_file = $upload_path.$file_name;
						
			if(is_file($upload_file)){
				$ext = pathinfo($_FILES['file_laporan']['name'], PATHINFO_EXTENSION);
				$new_filename = str_replace('.'.$ext, '', $file_name);				
				$file_name = $new_filename.'_'.mdate("%Y%m%d-%H%i%s", $_SERVER['REQUEST_TIME']).'.'.$ext;
				$upload_file = $upload_path.$file_name;				
			}
			
			echo json_encode(array("status" => TRUE, "new_file_name" => $file_name));			
        }//END FORM VALIDATION TRUE		
	}
	
	function data_save_note(){						
		$status = $this->input->post('status');
		$users_posisi_id = $this->input->post('users_posisi_id');				
		$new_file_name = $this->input->post('new_file_name');	
				
		$list = $this->cms_model->row_get_by_id($users_posisi_id, 'users_posisi');	
		$user_id = $list->user_id;					
					
		if(! is_null($list->posisi_id)){
			$posisi_id = $list->posisi_id;
		}else{
			$posisi_id = $list->struktural_id.'_struktural';
		}
		
		//UPLOAD FILE			
		$upload_path = $this->cms_model->set_folder_laporan($list->proyek_id, $posisi_id, $user_id);																		
		$upload_file = $upload_path.$new_file_name;
		
		move_uploaded_file($_FILES["file_laporan"]["tmp_name"],$upload_file);	//UPLOAD THE FILE
		
		if(is_file($upload_file))
		{						
			if($status == "baru" ){
				//baru
				$where = array('users_posisi_id' => $users_posisi_id);
				$no_urut = $this->cms_model->get_max_no_urut_note($users_posisi_id, 'nomor', 'max_nomor', $where);    
				$no_urut = $no_urut + 1;
				
				//simpan db	
				$additional_data = array(
					'users_posisi_id' => $users_posisi_id,
					'judul' => $this->input->post('judul'),
					'resume' => $this->input->post('resume'),
					'filename' => $_FILES["file_laporan"]["name"],
					'filename_server' => $new_file_name,
					'bulan' => $this->input->post('bulan_laporan'),
					'tahun' => $this->input->post('proyek_tahun'),
					'nomor' => $no_urut,
					'status' => $this->input->post('jenis_laporan'),
					'active_flag' => '1',
					'file_status' => '0'
					);		
			}else{
				//revisi
				//laporan revisi
				$list = $this->cms_model->row_get_by_id($this->input->post('id'), $this->table_note);					
				$where = array('users_posisi_id' => $users_posisi_id, 'nomor' => $list->nomor);
				$no_urut = $this->cms_model->get_max_no_urut_note($users_posisi_id, 'nomor_ref', 'max_nomor_ref', $where);    
				$no_urut = $no_urut + 1;
				
				//update flag
				$update= $this->cms_model->update(array("id" => $this->input->post('id')), array('active_flag' => NULL), $this->table_note);
					
				//simpan db						
				$additional_data = array(
						'users_posisi_id' => $users_posisi_id,
						'judul' => $this->input->post('judul'),
						'resume' => $this->input->post('resume'),
						'filename' => $_FILES["file_laporan"]["name"],
						'filename_server' => $new_file_name,
						'bulan' => $this->input->post('bulan_laporan'),
						'tahun' => $this->input->post('proyek_tahun'),
						'nomor' => $list->nomor,
						'nomor_ref' => $no_urut,
						'status' => $this->input->post('jenis_laporan'),
						'active_flag' => '1',
						'file_status' => '0'
						);	
			}
									
			//SIMPAN note
			$note_id = $this->cms_model->save($additional_data, $this->table_note);	
			
			//SIMPAN PENGIRIM
			//simpan approval hanya untuk tahun berjalan
			$tahun = mdate("%Y", time());  
			if($this->input->post('proyek_tahun') >= $tahun){
				$approval_type = $this->cms_model->query_get_by_criteria('approval_type', array('nama' => $this->approval_var), '');		
				
				if(count($approval_type) > 0){
					$note_approval = $this->cms_model->query_get_by_criteria('v_note_approval', array('note_id' => $note_id, 'approval_type_id' => $approval_type[0]->id),'');				
					if(count($note_approval) > 0){
						//ditemukan approval
						$row_posisi_pengirim = $this->cms_model->row_get_by_id($users_posisi_id, $this->table_users_posisi_view);	//get detail posisi users pengirim
						
						foreach($note_approval as $note_approval_item){
							$note_id = $note_approval_item->note_id;
							$users_posisi_id = $note_approval_item->users_posisi_id;
							$proyek_id = $note_approval_item->proyek_id;
							//cek apakah struktural atau posisi kegiatan
							if(! is_null($note_approval_item->in_struktural_id)){
								//cari struktural di users_posisi
								$struktural_in_posisi = $this->cms_model->query_get_by_criteria($this->table_users_posisi_view, array('proyek_id' => $proyek_id, 'struktural_id' => $note_approval_item->in_struktural_id) ,'');
								
								if(count($struktural_in_posisi) > 0){
									//struktural dalam users posisi
									foreach($struktural_in_posisi as $struktural_item){
										//INSERT struktural
										$additional_data = array(
											'note_id' => $note_id,
											'users_posisi_id_pengirim' => $users_posisi_id,
											'user_id_penerima' => $struktural_item->user_id,
											'users_posisi_id_penerima' => $struktural_item->id
											);	
										$inbox_id = $this->cms_model->save($additional_data, 'note_inbox_outbox');
									}//end foreach($struktural_in_posisi
								}else{
									//cek dalam struktural
									$row_proyek = $this->cms_model->row_get_by_id($proyek_id, 'proyek');									
									$struktural_in_struktural = $this->cms_model->query_get_by_criteria('v_users_struktural', array('struktural_id' => $note_approval_item->in_struktural_id, 'pusat_id' => $row_proyek->pusat_id, 'tahun_akhir' => '0') ,'');
									
									if(count($struktural_in_struktural) > 0){
										foreach($struktural_in_struktural as $struktural_item){
											//INSERT struktural
											$additional_data = array(
												'note_id' => $note_id,
												'users_posisi_id_pengirim' => $users_posisi_id,
												'user_id_penerima' => $struktural_item->users_id,
												'users_struktural_id_penerima' => $struktural_item->id
												);	
											$inbox_id = $this->cms_model->save($additional_data, 'note_inbox_outbox');
										}//end foreach($struktural_in_struktural
									}//end if(count($struktural_in_struktural
								}																								
							}else{
								//posisi kegiatan, //cari posisi di users_posisi								
								$posisi_in_posisi = $this->cms_model->query_get_by_criteria($this->table_users_posisi_view, array('proyek_id' => $proyek_id, 'posisi_id' => $note_approval_item->in_posisi_id) ,'');
								
								if(count($posisi_in_posisi) > 0){
									foreach($posisi_in_posisi as $posisi_item){
										//cek wp
										if($posisi_item->wp == ''){
											//INSERT posisi
											$additional_data = array(
												'note_id' => $note_id,
												'users_posisi_id_pengirim' => $users_posisi_id,
												'user_id_penerima' => $posisi_item->user_id,
												'users_posisi_id_penerima' => $posisi_item->id
												);	
											$inbox_id = $this->cms_model->save($additional_data, 'note_inbox_outbox');											
										}else{
											//ledaer/group leader
											if(! is_null($posisi_item->groups_leader_id)){
												//groups leader
												if($posisi_item->groups_leader_id == $row_posisi_pengirim->leader_groups_leader_id){
													//INISERT
													$additional_data = array(
														'note_id' => $note_id,
														'users_posisi_id_pengirim' => $users_posisi_id,
														'user_id_penerima' => $posisi_item->user_id,
														'users_posisi_id_penerima' => $posisi_item->id
														);	
													$inbox_id = $this->cms_model->save($additional_data, 'note_inbox_outbox');
												}
											}else if(! is_null($posisi_item->leader_id)){
												//leader
												if($posisi_item->leader_id == $row_posisi_pengirim->leader_id){
													//INISERT
													$additional_data = array(
														'note_id' => $note_id,
														'users_posisi_id_pengirim' => $users_posisi_id,
														'user_id_penerima' => $posisi_item->user_id,
														'users_posisi_id_penerima' => $posisi_item->id
														);	
													$inbox_id = $this->cms_model->save($additional_data, 'note_inbox_outbox');
												}
											}
																						
										}//end if($posisi_item->wp
									}//end foreach($posisi_in_posisi
									
									//$row_posisi_pengirim->
								}//if(count($struktural_in_struktural
							}//end if(! is_null($note_approval_item->in_struktural_id														
							
						}//end foreach($note_approval											
					}//end note_approval
				}//end approval_type		
			}//end simpan pengirim					
			
			echo json_encode(array("status" => TRUE));
		}else{
			//File gagal upload				
			echo json_encode(array("status" => "File gagal upload ! <br />Ulangi beberapa saat lagi."));
		}
	}	
	
	//custom validation function for dropdown input
    function check_posisi_lap()
    {				
		if($this->input->post('users_posisi_id') == '0'){
			$this->form_validation->set_message('check_posisi_lap', 'Kolom %s belum dipilih');
			return FALSE;											
		}else{
			return TRUE;
		}				      
    }
	
	function check_bulan_lap()
    {		
		if($this->input->post('bulan_laporan') == '0'){
			$this->form_validation->set_message('check_bulan_lap', 'Kolom %s belum dipilih');
			return FALSE;											
		}else{
			return TRUE;
		}				      
    }
	
	function check_file_lap()
	{			
		if (!isset($_FILES['file_laporan']) || empty($_FILES['file_laporan']['name']) || ($_FILES['file_laporan']['name'] == ''))
		{
			$this->form_validation->set_message('check_file_lap', 'Kolom %s belum diisi');
			return FALSE;	
		}else{
			$max_file_size = $this->cms_model->get_config_value('MAX_FILE_UPLOAD');
			if($_FILES['file_laporan']['size'] > $max_file_size){
				//cek ukuran file
				$this->form_validation->set_message('check_file_lap', 'Ukuran file lebih dari '.$this->cms_model->bytesToSize($max_file_size));
				return FALSE;	
			}else{
				//cek file type				
				$file_type = $this->cms_model->get_config_value('TYPE_FILE_UPLOAD');
				$ext = strtolower(pathinfo($_FILES['file_laporan']['name'], PATHINFO_EXTENSION));
				
				if($ext == ""){
					//tidak punya ekstensi file
					$this->form_validation->set_message('check_file_lap', 'Format file hanya '.$file_type);
					return FALSE;
				}else{
					$str_pos = strpos($file_type, $ext);

					if ($str_pos !== FALSE) {
						return TRUE;	
					}else{
						$this->form_validation->set_message('check_file_lap', 'Format file hanya '.$file_type);
						return FALSE;					
					}	
				}
							
			}			
		}
	}
	
	function show_laporan()
	{
		$note_id = $this->input->post('note_id');	
		$note_row = $this->cms_model->row_get_by_id($note_id, $this->table_note_view);				
		$note_file = $note_row->filename_server;						
		$folder_note = $this->cms_model->get_folder_laporan($note_row->proyek_id, $note_row->posisi_id, $note_row->user_id);
		$data['note_title'] = "File Laporan: ".$note_row->filename."<br />Tanggal Laporan: ".date_format(date_create($note_row->tahun."-".$note_row->bulan."-01"),"M Y");
				
		$file_template = FCPATH."assets\angular_pdf\pdf_loader.php";				
		$file_content = file_get_contents($file_template);			
		$vars = array("{pdf_url}" => $folder_note.$note_file);
		$file_content = strtr($file_content, $vars);
		$new_filename = mdate("%Y%m%d-%H%i%s", $_SERVER['REQUEST_TIME']).'.php';		
		$file_template = FCPATH."assets\angular_pdf\pdf_loader_".$new_filename;
		$result = write_file($file_template, $file_content);	
							
		$data['note_filename_url'] = base_url()."assets/angular_pdf/pdf_loader_".$new_filename;
		$data['note_filename_path'] = $file_template;		
		echo json_encode($data);	
	}		
	
	function show_laporan_delete()
	{
		$pdf_file = $this->input->post('pdf_file');			
		if($pdf_file != ""){
			unlink($pdf_file);
		}	
		echo json_encode(array("status" => TRUE));	
	}	
	
	public function download_file($note_id)
	{		
		$note_row = $this->cms_model->row_get_by_id($note_id, $this->table_note_view);				
		$user_id = $note_row->user_id;
		$note_file = $note_row->filename;
		$note_file_server = $note_row->filename_server;		
		$folder_note = $this->cms_model->get_folder_laporan($note_row->proyek_id, $note_row->posisi_id, $note_row->user_id);
			
		ob_clean(); 		
		$data = $folder_note.$note_file_server;
		$data = file_get_contents($data); //assuming my file is on localhost
		force_download($note_file ,$data); 
		exit();
	}
		
	public function data_list_personil()
	{
		$datatable_name = $this->table_users_posisi_view;
		$search_column = array('lower(nama_user)');
		$search_order = '';
		$where =  array('proyek_id' => $_POST['proyek_id']);
		$order_by = '';				
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = 0;//$_POST['start'];
		foreach ($list as $list_item) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $list_item->nama_user;
			
			$posisi = '';
			if($list_item->leader_nama != ''){
				//cetak leader dan enginering staff
				$posisi = $list_item->nama_posisi.' '.$list_item->leader_nama.' ('.$list_item->wp.')';
			}elseif($list_item->groups_leader_nama != ''){
				$posisi = $list_item->nama_posisi.' '.$list_item->groups_leader_nama.' ('.$list_item->wp.')';
			}elseif($list_item->nama_posisi != ''){
				$posisi = $list_item->nama_posisi;
			}else{
				$posisi = $list_item->nama_struktural;
			}						
			
			$row[] = $posisi;																																	
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->cms_model->count_all_where($datatable_name, $where),
						"recordsFiltered" => $this->cms_model->count_filtered_where($datatable_name, $search_column, $search_order, $where, $order_by),
						"data" => $data,						
				);
		//output to json format
		echo json_encode($output);
	}
	
	public function data_list_note_rekap()
	{	
		$users_posisi_id = $this->input->post('users_posisi_id');
		$where['status'] =  "1";	//jenis_laporan 
		$bulan_laporan = $this->input->post('bulan_laporan');				
		$where['proyek_id'] = $this->input->post('proyek_id');
		$where['active_flag'] = '1';	

		if($users_posisi_id != "0"){
			$where['users_posisi_id'] =  $users_posisi_id;	
			
			if($bulan_laporan != "0"){
				$where['bulan'] =  $bulan_laporan;					
			}			
		}else{
			$where['id'] = '0';	
		}								
						
		$datatable_name = $this->table_note_view;	
		$search_column = array('lower(judul)');
		$search_order = array('judul' => 'asc');
		$order_by = 'tanggal desc';								
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $list_item) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $list_item->judul;
			$row[] = '<a href="javascript:void()" onclick="show_laporan('."'".$list_item->id."'".')" >'.$list_item->filename.'</a>';																							
			$row[] = date_format(date_create($list_item->tahun."-".$list_item->bulan."-01"),"M Y");										
																						
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->cms_model->count_all_where($datatable_name, $where),
						"recordsFiltered" => $this->cms_model->count_filtered_where($datatable_name, $search_column, $search_order, $where, $order_by),
						"data" => $data,						
				);
		//output to json format
		echo json_encode($output);			
	}
		
//BEGIN: BERITA DETAIL 
//===========================================================================	
	
	public function data_list_berita()
	{				
		$search_column = array('lower(judul)');
		$search_order = array('judul' => 'asc');
		$where =  array('proyek_id' => $_POST['proyek_id']);  //laporan logbook (status=1)
		$order_by = 'tanggal_submit desc';								
		$user_id = $this->session->userdata('user_id');
		$list = $this->cms_model->get_datatables_where($this->table_name_view_berita, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $list_item) {
			$no++;
			$row = array();
			$row[] = $no;						
			$row[] = '<a href="javascript:void()" title="Detail Berita" onclick="detail_berita('.$list_item->id.')">'.$list_item->judul.'</a>';																							
			$row[] = date_format(date_create($list_item->tanggal_submit),"j M Y");
						
			if($list_item->users_id == $user_id){
				$row[] = '<a class="btn btn-xs btn-warning" href="javascript:void()" title="Edit" onclick="data_edit_berita('."'".$list_item->id."'".')"><i class="fa fa-pencil"></i></a>';
			}else{
				$row[] = "";
			}
									
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->cms_model->count_all_where($this->table_name_view_berita, $where),
						"recordsFiltered" => $this->cms_model->count_filtered_where($this->table_name_view_berita, $search_column, $search_order, $where, $order_by),
						"data" => $data,						
				);
		//output to json format
		echo json_encode($output);	
	}		
	
	public function data_edit_berita($id)
	{						
		$data['list'] = $this->cms_model->row_get_by_id($id, $this->table_name_berita);
		echo json_encode($data);
	}
	
	public function data_save_validation_berita()
	{								      				
		//set validation rules
		$this->form_validation->set_rules('judul', 'Judul Berita', "required"); 
		$this->form_validation->set_rules('isi', 'Isi Berita', "required"); 
		$this->form_validation->set_rules('file_berita', 'File Berita', 'callback_check_file_berita');
						
		if ($this->form_validation->run() == FALSE)
        {            
			//validation fails
			echo json_encode(array("status" => validation_errors()));            
        }
        else
        {    									
			$proyek_id = $_POST['proyek_id'];
			$file_berita = "";
			$file_gambar = "";
			
			//UPLOAD FILE
			$upload_path = $this->cms_model->set_folder_berita($proyek_id);
			
			if(!is_dir($upload_path)){
				mkdir($upload_path,0777);
			}
			
			if($_FILES['file_berita']['name'] != '')
			{
				$file_berita = $_FILES["file_berita"]["name"];
				$file_berita = preg_replace("/ /", '_', $file_berita);
				$file_berita = preg_replace("/&/", '_', $file_berita);
				$file_berita = preg_replace("/{/", '_', $file_berita);
				$file_berita = preg_replace("/}/", '_', $file_berita);
				$upload_file = $upload_path.$file_berita;
							
				if(is_file($upload_file)){
					$ext = pathinfo($_FILES['file_berita']['name'], PATHINFO_EXTENSION);
					$new_filename = str_replace('.'.$ext, '', $file_berita);				
					$file_berita = $new_filename.'_'.mdate("%Y%m%d-%H%i%s", $_SERVER['REQUEST_TIME']).'.'.$ext;
					$upload_file = $upload_path.$file_berita;				
				}
			}			

			if($_FILES['file_gambar']['name'] != '')
			{
				$file_gambar = $_FILES["file_gambar"]["name"];
				$file_gambar = preg_replace("/ /", '_', $file_gambar);
				$file_gambar = preg_replace("/&/", '_', $file_gambar);
				$file_gambar = preg_replace("/{/", '_', $file_gambar);
				$file_gambar = preg_replace("/}/", '_', $file_gambar);
				$upload_file = $upload_path.$file_gambar;
							
				if(is_file($upload_file)){
					$ext = pathinfo($_FILES['file_gambar']['name'], PATHINFO_EXTENSION);
					$new_filename = str_replace('.'.$ext, '', $file_gambar);				
					$file_gambar = $new_filename.'_'.mdate("%Y%m%d-%H%i%s", $_SERVER['REQUEST_TIME']).'.'.$ext;
					$upload_file = $upload_path.$file_gambar;				
				}
			}
						
			echo json_encode(array("status" => TRUE, "new_file_berita" => $file_berita, "new_file_gambar" => $file_gambar));			
        }//END FORM VALIDATION TRUE		
	}
	
	function data_save_add_berita(){
		$proyek_id = $_POST['proyek_id'];			
		$user_id = $this->session->userdata('user_id');
		$new_file_berita = $this->input->post('new_file_berita');
		$new_file_gambar = $this->input->post('new_file_gambar');														
		$upload_path = $this->cms_model->set_folder_berita($proyek_id);
		$upload_file_berita = $upload_path.$new_file_berita;
		$upload_file_gambar = $upload_path.$new_file_gambar;
		$is_berita = false;
		$is_gambar = false;
		//cek up;oad file berita		
		if($new_file_berita != ""){
			move_uploaded_file($_FILES["file_berita"]["tmp_name"],$upload_file_berita);	//UPLOAD THE FILE
			
			if(is_file($upload_file_berita)){
				$is_berita = true;
			}
		}else{
			$is_berita = true;
		}
		//cek upload file gambar
		if($new_file_gambar != ""){
			move_uploaded_file($_FILES["file_gambar"]["tmp_name"],$upload_file_gambar);	//UPLOAD THE FILE
			
			if(is_file($upload_file_gambar)){
				$is_gambar = true;
			}
		}else{
			$is_gambar = true;
		}
								
		if($is_berita && $is_gambar)
		{
			//simpan db						
			$additional_data = array(
					'judul' => $this->input->post('judul'),
					'isi' => $this->input->post('isi'),
					'users_id' => $user_id,					
					'status' => "1",
					'proyek_id' => $proyek_id,
					'filegambar'  => $new_file_gambar,
					'filename' => $_FILES["file_berita"]["name"],
					'filename_server' => $new_file_berita
					);	
			
			//SIMPAN berita
			$berita_id = $this->cms_model->save($additional_data, $this->table_name_berita);	
			
			echo json_encode(array("status" => TRUE));
		}else{
			//File gagal upload				
			echo json_encode(array("status" => "File gagal upload ! <br />Ulangi beberapa saat lagi."));
		}
	}
	
	function data_save_edit_berita(){					
		$id_berita = $this->input->post('id');
		$user_id = $this->session->userdata('user_id');
		$new_file_berita = $this->input->post('new_file_berita');
		$new_file_gambar = $this->input->post('new_file_gambar');			
		$delete_file_gambar = $this->input->post('delete_file_gambar');
		$delete_file_berita = $this->input->post('delete_file_berita');	
		
		$row_berita = $this->cms_model->row_get_by_id($id_berita, $this->table_name_berita);
		$proyek_id = $row_berita->proyek_id;
		$file_gambar = $row_berita->filegambar;
		$file_berita = $row_berita->filename;
		$file_berita_server = $row_berita->filename_server;
			  													
		$upload_path = $this->cms_model->set_folder_berita($proyek_id);
		$upload_file_berita = $upload_path.$new_file_berita;
		$upload_file_gambar = $upload_path.$new_file_gambar;
		$is_berita = false;
		$is_gambar = false;				
		
		//cek up;oad file berita		
		if($new_file_berita != ""){
			move_uploaded_file($_FILES["file_berita"]["tmp_name"],$upload_file_berita);	//UPLOAD THE FILE
			$file_berita = $_FILES["file_berita"]["name"];
			
			if(is_file($upload_file_berita)){
				$is_berita = true;
			}
			
			if($file_berita_server != ""){
				//cek jika sebelumnya ada file hapus
				unlink($upload_path.$file_berita_server);
			}						
		}else{
			$is_berita = true;
			$new_file_berita = $file_berita_server;
			 			
			if($delete_file_berita == "true"){
				//cek jika sebelumnya ada file hapus
				unlink($upload_path.$file_berita_server);
				$file_berita = "";
				$new_file_berita = "";
			}
		}
		
		//cek upload file gambar
		if($new_file_gambar != ""){
			move_uploaded_file($_FILES["file_gambar"]["tmp_name"],$upload_file_gambar);	//UPLOAD THE FILE
			
			if(is_file($upload_file_gambar)){
				$is_gambar = true;
			}
			
			if($file_gambar != ""){
				//cek jika sebelumnya ada file hapus
				unlink($upload_path.$file_gambar);
			}
		}else{
			$is_gambar = true;
			$new_file_gambar = $file_gambar;
			
			if($delete_file_gambar == "true"){
				//cek jika sebelumnya ada file hapus
				unlink($upload_path.$file_gambar);
				$new_file_gambar = "";
			}
		}
		
		//simpan berita						
		if($is_berita && $is_gambar)
		{
			//simpan db						
			$additional_data = array(
					'judul' => $this->input->post('judul'),
					'isi' => $this->input->post('isi'),
					'users_id' => $user_id,					
					'status' => "1",
					'proyek_id' => $proyek_id,
					'filegambar'  => $new_file_gambar,
					'filename' => $file_berita,
					'filename_server' => $new_file_berita,
					'tanggal_submit' => 'now()'
					);	
			
			//SIMPAN berita
			if($this->cms_model->update(array("id" => $id_berita), $additional_data, $this->table_name_berita))
			{
				//berhasil update, lakukan update lainnya pada tabel user group dan user bidang								
				echo json_encode(array("status" => TRUE));
			}else{
				//gagal update
				echo json_encode(array("status" => "Gagal update data"));
			}						
		}else{
			//File gagal upload				
			echo json_encode(array("status" => "File gagal upload ! <br />Ulangi beberapa saat lagi."));
		}
	}		
	
	//custom validation function for dropdown input
   function check_file_berita()
	{			
		if($_FILES['file_berita']['name'] != '')
		{	
			$max_file_size = $this->cms_model->get_config_value('MAX_FILE_UPLOAD');
			if($_FILES['file_berita']['size'] > $max_file_size){
				//cek ukuran file
				$this->form_validation->set_message('check_file_berita', 'Ukuran file lebih dari '.$this->cms_model->bytesToSize($max_file_size));
				return FALSE;	
			}else{				
				return TRUE;							
			}			
		}
	}
	
	public function download_file_berita($berita_id)
	{				
		$berita_row = $this->cms_model->row_get_by_id($berita_id, "berita");				
		$proyek_id = $berita_row->proyek_id;
		$berita_file = $berita_row->filename;
		$berita_file_server = $berita_row->filename_server;		
		$folder_berita = $this->cms_model->get_folder_berita($proyek_id);	
			
		ob_clean(); 		
		$data = $folder_berita.$berita_file_server;
		$data = file_get_contents($data); //assuming my file is on localhost
		force_download($berita_file ,$data); 
		exit();
	}		
	
	public function detail_berita($id)
	{						
		$data['list'] = $this->cms_model->row_get_by_id($id, $this->table_name_view_berita);
		$data['download'] = "";
		$data['picture'] = "";
		
		if($data['list']->filegambar != ""){
			$folder_berita = $this->cms_model->get_folder_berita($data['list']->proyek_id);
			$data['picture'] = $folder_berita.$data['list']->filegambar;
		}
		
		if($data['list']->filename != ""){
			$data['download'] = '<a href="'.site_url("/".$this->router->class."/download_file_berita/".$data['list']->id).'" class="btn btn-xs btn-success" title="Download" ><i class="fa fa-download"></i></a> '.$data['list']->filename;
		}
		
		echo json_encode($data);
	}	
		
	function _render_page($view, $data=null, $returnhtml=false)//I think this makes more sense
	{
		$this->viewdata = (empty($data)) ? $this->data: $data;
		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);
		if ($returnhtml) return $view_html;//This will return html on 3rd argument being true
	}
}
/*	
	public function data_save_note()
	{								      						
		//SIMPAN NOTE
		$note_id = $this->cms_model->save($additional_data, 'note');	
		
		//SIMPAN inbox outbox
		$penerima_list =  $this->cms_model->get_user_id_penerima_note($posisi_id,$project_id, $wp, $this->input->post('proyek_tahun'));
		//$personil_list = explode(",", $personil_select);
		foreach($penerima_list as $personil_item){	
			$user_id_penerima = '';
			if($personil_item->in_struktural_id != ''){
				$user_id_penerima = $personil_item->users_id_struktural;
				//echo 'struk='.$personil_item->users_id_struktural;
			}elseif($personil_item->in_posisi_id != ''){						
				$user_id_penerima = $personil_item->user_id;
				//echo 'posi='.$personil_item->user_id;												
			}
			
			
			if($user_id_penerima != ''){
				$additional_data_in = array(
					'user_posisi_id_pengirim' => $user_id,
					'user_posisi_id_penerima' => $user_id_penerima,
					'note_id' => $note_id
					);
				//SIMPAN INBOX
				$inbox_id = $this->cms_model->save($additional_data_in, 'note_inbox_outbox');		
			}														
		}
		
		echo json_encode(array("status" => TRUE));
		
	}
	
	
		*/
/*
	public function load_no_laporan()
	{
		$proyek_id = $this->input->post('proyek_id');
		$users_posisi_id = $this->input->post('users_posisi_id');		
		$list = $this->cms_model->get_users_note_by_posisi_id($users_posisi_id, $proyek_id); 

		$data = array();
		$data[] = array('id_item' => '0', 'nama_item' => '-- Pilih --');
		
		foreach($list as $list_item){
			$posisi_id = $list_item->posisi_id;
			$wp = $list_item->wp;
			$wp_len = strlen($wp);
			$jenis_dokumen = $list_item->jenis_dokumen;
			
			if($posisi_id == 4){									
				if($wp_len == 5){
					$wp_str = $list_item->posisi_ket.substr($wp,2, 1).'.'.substr($wp,3, 1).'.'.substr($wp,4, 1);
				}
				else if($wp_len == 4){
					$wp_str = $list_item->posisi_ket.substr($wp,2, 1).'.'.substr($wp,3, 1);									
				}	
				else if($wp_len == 3){
					$wp_str = $list_item->posisi_ket.substr($wp,2, 1);									
				}															
			}else if($posisi_id == 3){					
				if($wp_len == 4){
					$wp_str = $list_item->posisi_ket.substr($wp,2, 1).'.'.substr($wp,3, 1);
				}else{
					$wp_str = $list_item->posisi_ket.substr($wp,2, 1);									
				}									
			}else if($posisi_id == 2){
				$wp_str = $list_item->posisi_ket.substr($wp,3, 1);					
			}else{
				$wp_str = '';
			}
			
			$row = array();
			$row['id_item'] = $list_item->note_id;
			$row['nama_item'] = $list_item->nama_proyek.' '.$list_item->jenis_dokumen.$list_item->nomor.' '.$wp_str.' '.$list_item->bulan.'-'.$list_item->tahun;
			$data[] = $row;
		}				
		
		$output = array("no_laporan" => $data);							
		echo json_encode($output);
	}*/
		
	
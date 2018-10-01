<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_tambahan extends CI_Controller {
	
	//Global variable
	var $table_name = 'log_book';
	var $jenis_laporan = '3';	//laporan tambahan
	var $approval_var = 'laporan_tambahan';
	
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
		// set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		$this->data['user_menu'] = $this->cms_model->get_user_menu($this->uri->rsegment(1));
		$this->data['user'] = $this->ion_auth->user()->row();
				
		$this->_render_page('laporan_tambahan', $this->data);				
	}		
	
	public function data_init()
	{
		$data['filter_tahun'] = $this->cms_model->get_tahun_logbook($this->jenis_laporan, $this->session->userdata('user_id')); 
		array_unshift($data['filter_tahun'],array('id_item' => '0', 'nama_item' => '--Pilih--')); 		
		echo json_encode($data);
	}
	
	public function data_list()
	{				
		$search_column = array('lower(filename)');
		$search_order = array('filename' => 'asc');
		$where =  array('user_id' => $this->session->userdata('user_id'), 'status' => $this->jenis_laporan, 'EXTRACT(year FROM "tgl_dokumen")=' => $_POST['filter_tahun']);  
		$order_by = 'submit_date desc';				
		
		$folder_logbook = $this->cms_model->get_folder_logbook($this->session->userdata('user_id'));
		
		$list = $this->cms_model->get_datatables_where($this->table_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $list_item) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = '<a href="javascript:void()" onclick="show_laporan('."'".$list_item->id."'".')" >'.$list_item->filename.'</a>';																							
			$row[] = date_format(date_create($list_item->tgl_dokumen),"j M Y");
			$row[] = date_format(date_create($list_item->submit_date),"j M Y, \J\a\m G:i");
			$row[] = '<a href="'.site_url("/".$this->router->class."/download_file/".$list_item->id).'" class="btn btn-xs btn-success" title="Download" ><i class="fa fa-download"></i></a>';	
																						
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->cms_model->count_all_where($this->table_name, $where),
						"recordsFiltered" => $this->cms_model->count_filtered_where($this->table_name, $search_column, $search_order, $where, $order_by),
						"data" => $data,						
				);
		//output to json format
		echo json_encode($output);	
	}		
	
	public function data_save_validation()
	{								      				
		//set validation rules
		$this->form_validation->set_rules('tgl_dokumen', 'Tanggal Laporan', "required"); 
		$this->form_validation->set_rules('file_laporan', 'File Laporan', 'callback_check_file_lap');
						
		if ($this->form_validation->run() == FALSE)
        {            
			//validation fails
			echo json_encode(array("status" => validation_errors()));            
        }
        else
        {    						
			$user_id = $this->session->userdata('user_id');					
			
			//UPLOAD FILE
			$upload_path = $this->cms_model->set_folder_logbook($user_id);
			//$upload_url = $this->cms_model->get_folder_logbook($user_id);
			
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
	
	function data_save_logbook(){
		$user_id = $this->session->userdata('user_id');			
		$new_file_name = $this->input->post('new_file_name');														
		$upload_path = $this->cms_model->set_folder_logbook($user_id);
		$upload_file = $upload_path.$new_file_name;
		
		move_uploaded_file($_FILES["file_laporan"]["tmp_name"],$upload_file);	//UPLOAD THE FILE
		
		if(is_file($upload_file))
		{
			//simpan db
			$tgl_dokumen = $this->input->post('tgl_dokumen');				
			$tgl_split = explode("-", $tgl_dokumen);
			$tgl_lap = $tgl_split[1]."/".$tgl_split[0]."/".$tgl_split[2];
			$bulan = $tgl_split[1];
			$tahun = $tgl_split[2];			
			
			$additional_data = array(
					'user_id' => $user_id,
					'tgl_dokumen' => $tgl_lap,
					'status' => $this->jenis_laporan,
					'filename' => $_FILES["file_laporan"]["name"],
					'filename_server' => $new_file_name,
					'bulan' => $bulan,
					'tahun' => $tahun
					);	
			
			//SIMPAN logbook
			$laporan_id = $this->cms_model->save($additional_data, $this->table_name);	
			
			//SIMPAN approval
			/////////////////////////////////////						
			//LOAD approval
			$approval_type = $this->cms_model->query_get_by_criteria('approval_type', array('nama' => $this->approval_var), '');	
			$approval_type_id = $approval_type[0]->id;
			$approval_level = $this->cms_model->query_get_by_criteria('approval_level', array('approval_type_id' => $approval_type_id), '');	

			if(count($approval_level) > 0){
				//search di v_users_approval dengan users_id dan $approval_type		
				$query = $this->db->query("select * from v_users_approval_out where approval_type_id=".$approval_type_id." and (out_user_id_group=".$user_id." or out_user_id_fungsional=".$user_id." or out_user_id_struktural=".$user_id." or out_user_id_posisi=".$user_id.")");		

				if ($query->num_rows > 0){				
					$user_approval = $query->result();																
					foreach($user_approval as $user_approval_item){
						//Get user out
						$user_out_param = "";
						$user_out_val = "";
						if(trim($user_approval_item->out_group_id) != ''){
							$user_out_param = 'user_group_id_pengirim';
							$list_users_group_penerima = $this->cms_model->query_get_by_criteria('users_groups', array('group_id' => $user_approval_item->out_group_id, 'user_id' => $user_id), '');
							
							$user_out_val = $list_users_group_penerima[0]->id;
						}elseif(trim($user_approval_item->out_struktural_id) != ''){
							$user_out_param = 'user_struktural_id_pengirim';
							$list_users_struktural_penerima = $this->cms_model->query_get_by_criteria('users_struktural', array('struktural_id' => $user_approval_item->out_struktural_id, 'users_id' => $user_id,'tahun_akhir' => '0'), '');
							
							$user_out_val = $list_users_struktural_penerima[0]->id;
						}elseif(trim($user_approval_item->out_fungsional_id) != ''){
							$user_out_param = 'user_fungsional_id_pengirim';
							$list_users_fungsional_penerima = $this->cms_model->query_get_by_criteria('users_fungsional', array('fungsional_id' => $user_approval_item->out_struktural_id, 'users_id' => $user_id,'tahun_akhir' => '0'), '');
							
							$user_out_val = $list_users_fungsional_penerima[0]->id;
						}elseif(trim($user_approval_item->out_posisi_id) != ''){
							$user_out_param = 'user_posisi_id_pengirim';
							$user_out_val = $user_approval_item->out_posisi_id;
						}
										
						if(trim($user_approval_item->in_group_id) != ''){
							//GROUP OUT
							$list_users_group = $this->cms_model->query_get_by_criteria('users_groups', array('group_id' => $user_approval_item->in_group_id), '');	
							if(count($list_users_group) > 0){
								//kirim jika ada users list
								foreach($list_users_group as $list_users_group_item){
									//INSERT GROUP
									$additional_data = array(
										'log_book_id' => $laporan_id,
										'user_id_penerima' => $list_users_group_item->user_id,
										'user_group_id_penerima' => $list_users_group_item->id,										
										$user_out_param => $user_out_val																				
										);	
									$inbox_id = $this->cms_model->save($additional_data, 'log_book_inbox_outbox');
								}
							}
						}elseif(trim($user_approval_item->in_struktural_id) != ''){
							//STRUKTURAL OUT
							$list_users_struktural = $this->cms_model->query_get_by_criteria('users_struktural', array('struktural_id' => $user_approval_item->in_struktural_id, 'tahun_akhir' => '0'), '');	
							if(count($list_users_struktural) > 0){
								foreach($list_users_struktural as $list_users_struktural_item){
									//INSERT GROUP
									$additional_data = array(
										'log_book_id' => $laporan_id,
										'user_id_penerima' => $list_users_struktural_item->users_id,
										'user_struktural_id_penerima' => $list_users_struktural_item->id,										
										$user_out_param => $user_out_val																				
										);	
									$inbox_id = $this->cms_model->save($additional_data, 'log_book_inbox_outbox');
								}
							}
						}elseif(trim($user_approval_item->in_fungsional_id) != ''){
							//FUNGSIONAL OUT
							$list_users_fungsional = $this->cms_model->query_get_by_criteria('users_fungsional', array('fungsional_id' => $user_approval_item->in_fungsional_id, 'tahun_akhir' => '0'), '');	
							if(count($list_users_fungsional) > 0){
								foreach($list_users_fungsional as $list_users_fungsional_item){
									//INSERT GROUP
									$additional_data = array(
										'log_book_id' => $laporan_id,
										'user_id_penerima' => $list_users_fungsional_item->users_id,
										'user_fungsional_id_penerima' => $list_users_fungsional_item->id,										
										$user_out_param => $user_out_val																				
										);	
									$inbox_id = $this->cms_model->save($additional_data, 'log_book_inbox_outbox');
								}
							}							
						}elseif(trim($user_approval_item->in_posisi_id) != ''){	
						
						}
					}
				}																
				
			}//END if(count($approval_type) > 0)			
			//////////////////////////////////////
			
			echo json_encode(array("status" => TRUE));
		}else{
			//File gagal upload				
			echo json_encode(array("status" => "File gagal upload ! <br />Ulangi beberapa saat lagi."));
		}
	}
	
	//custom validation function for dropdown input
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
	
	public function download_file($logbook_id)
	{				
		$logbook_row = $this->cms_model->row_get_by_id($logbook_id, $this->table_name);				
		$user_id = $logbook_row->user_id;
		$logbook_file = $logbook_row->filename;
		$logbook_file_server = $logbook_row->filename_server;		
		$folder_logbook = $this->cms_model->get_folder_logbook($user_id);	
			
		ob_clean(); 		
		$data = $folder_logbook.$logbook_file_server;
		//$data = file_get_contents($data); //assuming my file is on localhost
		$data = $this->_url_get_contents($data); 
		force_download($logbook_file ,$data); 
		exit();
	}
	
	function _url_get_contents ($Url) {
	    if (!function_exists('curl_init')){ 
	        die('CURL is not installed!');
	    }
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $Url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $output = curl_exec($ch);
	    curl_close($ch);
	    return $output;
	}
	
	function show_laporan()
	{
		$logbook_id = $this->input->post('logbook_id');	
		$logbook_row = $this->cms_model->row_get_by_id($logbook_id, $this->table_name);				
		$logbook_file = $logbook_row->filename_server;								
		$folder_logbook = $this->cms_model->get_folder_logbook($logbook_row->user_id);
		$data['logbook_title'] = "File Laporan: ".$logbook_row->filename."<br />Tanggal Laporan: ".date_format(date_create($logbook_row->tgl_dokumen),"j M Y");
				
		$file_template = FCPATH."assets\angular_pdf\pdf_loader.php";		
		$file_content = file_get_contents($file_template);		
		$vars = array("{pdf_url}" => $folder_logbook.$logbook_file);
		$file_content = strtr($file_content, $vars);
		$new_filename = mdate("%Y%m%d-%H%i%s", $_SERVER['REQUEST_TIME']).'.php';		
		$file_template = FCPATH."assets\angular_pdf\pdf_loader_".$new_filename;
		$result = write_file($file_template, $file_content);	
							
		$data['logbook_filename_url'] = base_url()."assets/angular_pdf/pdf_loader_".$new_filename;
		$data['logbook_filename_path'] = $file_template;		
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
	
	function _render_page($view, $data=null, $returnhtml=false)//I think this makes more sense
	{

		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);

		if ($returnhtml) return $view_html;//This will return html on 3rd argument being true
	}
}


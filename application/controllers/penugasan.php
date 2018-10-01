<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penugasan extends CI_Controller {
	
	//Global variable
	var $table_name = 'tugas_new';	
	var $table_name_view = 'v_tugas';	
	
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(array('ion_auth','form_validation'));
		$this->load->helper(array('url','language'));
		$this->load->helper('download');
		$this->load->helper('file');
		$this->load->helper('date');

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
				
		$this->_render_page('penugasan', $this->data);				
	}		
	
	public function data_init()
	{
		$data['filter_tahun'] = $this->cms_model->get_tahun_tugas($this->session->userdata('user_id')); 
		if (empty($data['filter_tahun'])){
			array_unshift($data['filter_tahun'],array('id_item' => '0', 'nama_item' => '--Pilih--'));
		}
			
		$data['filter_bulan'] = $this->cms_model->get_list_bulan(); 	
		array_unshift($data['filter_bulan'],array('id_item' => '0', 'nama_item' => '--Pilih--'));
		echo json_encode($data);
	}
	
	public function data_list_users()
	{
		$datatable_name = "users";
		$search_column = array('lower(nama)');
		$search_order = array('nama' => 'asc');		
		$is_where = $_POST['is_where'];
		
		if($is_where == 'true'){
			if($_POST['nama_user_penerima'] != ""){
				$where =  array("nama ~*" => ".*".$_POST['nama_user_penerima'].".*");
			}else{
				$where =  "";
			}			
		}else{
			$where =  "";
		}
		
		$order_by = "nama asc";				
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $list_item) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $list_item->nama;
			$row[] = $list_item->nip;										
			
			//add html for action
			$row[] = '<a class="btn btn-xs btn-primary" href="javascript:void()" title="Pilih" onclick="data_pick('."'".$list_item->id."','".$list_item->nama."'".')">Pilih</a>';
																			
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
	
	public function data_save_validation()
	{								      				
		//set validation rules
		$this->form_validation->set_rules('perihal_tugas', 'Perihal Tugas', "required"); 
		$this->form_validation->set_rules('file_tugas', 'File Tugas', 'callback_check_file_tugas');		
		$this->form_validation->set_rules('nama_penerima', 'Nama Penerima', 'trim|required|xss_clean|callback_check_personil');					
						
		if ($this->form_validation->run() == FALSE)
        {            
			//validation fails
			echo json_encode(array("status" => validation_errors()));            
        }
        else
        {    						
			$user_id = $this->session->userdata('user_id');					
			
			//UPLOAD FILE
			$upload_path = $this->cms_model->set_folder_tugas($user_id);
			//$upload_url = $this->cms_model->get_folder_logbook($user_id);
			
			if(!is_dir($upload_path)){
				mkdir($upload_path,0777);
			}
			
			$file_name = $_FILES["file_tugas"]["name"];
			$file_name = preg_replace("/ /", '_', $file_name);
			$file_name = preg_replace("/&/", '_', $file_name);
			$file_name = preg_replace("/{/", '_', $file_name);
			$file_name = preg_replace("/}/", '_', $file_name);
			$upload_file = $upload_path.$file_name;
						
			if(is_file($upload_file)){
				$ext = pathinfo($_FILES['file_tugas']['name'], PATHINFO_EXTENSION);
				$new_filename = str_replace('.'.$ext, '', $file_name);				
				$file_name = $new_filename.'_'.mdate("%Y%m%d-%H%i%s", $_SERVER['REQUEST_TIME']).'.'.$ext;
				$upload_file = $upload_path.$file_name;				
			}
			
			echo json_encode(array("status" => TRUE, "new_file_name" => $file_name));			
        }//END FORM VALIDATION TRUE		
	}
	
	function data_save_tugas(){
		$user_id = $this->session->userdata('user_id');			
		$new_file_name = $this->input->post('new_file_name');														
		$upload_path = $this->cms_model->set_folder_tugas($user_id);
		$upload_file = $upload_path.$new_file_name;
		
		move_uploaded_file($_FILES["file_tugas"]["tmp_name"],$upload_file);	//UPLOAD THE FILE
		
		if(is_file($upload_file))
		{
			//simpan db										
			$additional_data = array(
					'user_id_pengirim' => $user_id,
					'user_id_penerima' => $this->input->post('user_id_penerima'),
					'perihal' => $this->input->post('perihal_tugas'),															
					'filename' => $_FILES["file_tugas"]["name"],
					'filename_server' => $new_file_name					
					);	
			
			//SIMPAN tugas//
			$tugas_id = $this->cms_model->save($additional_data, $this->table_name);				
			
			echo json_encode(array("status" => TRUE));
		}else{
			//File gagal upload				
			echo json_encode(array("status" => "File gagal upload ! <br />Ulangi beberapa saat lagi."));
		}
	}		
	
	public function proses_tugas()
	{			       
		$id_tugas = $this->input->post('id_tugas');		
		$status_tugas = $this->input->post('status_tugas');		
		$user_id = $this->session->userdata('user_id');		
		
		if($status_tugas == '1'){
			//proses tugas
			$additional_data = array(
				'status_tugas' => '1',
				'tanggal_proses' => 'now()'
			);
		}else{
			//selesai tugas
			$data_list = $this->cms_model->row_get_by_id($id_tugas, $this->table_name);
			
			if($data_list->user_id_pengirim == $user_id){
				//pengirim tugas
				if($data_list->tanggal_proses == ''){
					$additional_data = array(	
						'status_tugas' => '2',
						'tanggal_proses' => 'now()',
						'tanggal_selesai' => 'now()'
					);
				}else{
					$additional_data = array(	
						'status_tugas' => '2',
						'tanggal_selesai' => 'now()'
					);
				}
			}elseif($data_list->user_id_penerima == $user_id){
				//penerima tugas
				$additional_data = array(	
						'status_tugas' => '2'
					);
			}									
		}				
			
		if($this->cms_model->update(array("id" => $id_tugas), $additional_data, $this->table_name))
		{			
			echo json_encode(array("status" => TRUE));
		}else{
			//gagal update
			echo json_encode(array("status" => "Gagal update data"));
		}							
	}
	
	public function data_list()
	{	
		$user_id = $this->session->userdata('user_id');	
		$chkSearch = $this->input->post('chkSearch');
		$where['tahun'] =  $this->input->post('filter_tahun');
		$where['user_id'] = $user_id;			
						
		if($chkSearch[0] != "")
		{			
			foreach($chkSearch as $chkSearch_item)						
			{				
				if($chkSearch_item == "bulan"){
					$where['bulan'] =  $this->input->post('filter_bulan');	
				}
				if($chkSearch_item == "search_pengirim"){
					$where['nama_user_pengirim ~*'] = strtolower($this->input->post('search_nama_pengirim'));
				}		
				if($chkSearch_item == "search_penerima"){
					$where['nama_user_penerima ~*'] = strtolower($this->input->post('search_nama_penerima'));
				}	
			}
		}
			
		//print_r($where);		
		$datatable_name = $this->table_name_view;	
		$search_column = '';
		$search_order = '';
		$order_by = 'tanggal_tugas desc';								
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $list_item) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $list_item->nama_user_pengirim;	
			$row[] = $list_item->nama_user_penerima;
			$row[] = $list_item->perihal;
			$row[] = date_format(date_create($list_item->tanggal_tugas),"j M Y, \J\a\m G:i");	
			
			if(is_null($list_item->tanggal_proses) || $list_item->tanggal_proses == ""){
				if($list_item->user_id_penerima == $user_id){
					$row[] = '<a class="btn btn-xs btn-warning" href="javascript:void()" title="Proses" onclick="proses_tugas('."'".$list_item->id."','1','".$list_item->perihal."'".')"><i class="fa fa-check"></i> Proses</a>';			
				}else{
					$row[] = "";
				}
			}else{
				$row[] = date_format(date_create($list_item->tanggal_proses),"j M Y, \J\a\m G:i");			
			}
			
			if(is_null($list_item->tanggal_selesai) || $list_item->tanggal_selesai == ""){
				if($list_item->user_id_pengirim == $user_id){
					//pengirim
					if($list_item->status_tugas == '0'){
						$row[] = "";	//tugas baru
					}elseif($list_item->status_tugas == '1'){
						$row[] = '<label class="label label-xs label-danger" style="font-size:8pt;">Waiting </label>';
					}elseif($list_item->status_tugas == '2'){
						$row[] = '<label class="label label-xs label-success" style="font-size:8pt;">Waiting </label> </br> <a class="btn btn-xs btn-warning" href="javascript:void()" title="Selesai" onclick="proses_tugas('."'".$list_item->id."','2','".$list_item->perihal."'".')"><i class="fa fa-check"></i> Selesai</a>';
					}
					
					//$row[] = '<a class="btn btn-xs btn-warning" href="javascript:void()" title="Selesai" onclick="proses_tugas('."'".$list_item->id."','2','".$list_item->perihal."'".')"><i class="fa fa-check"></i> Selesai</a>';			
				}elseif($list_item->user_id_penerima == $user_id){
					//penerima
					if($list_item->status_tugas == '1'){
						$row[] = '<a class="btn btn-xs btn-warning" href="javascript:void()" title="Selesai" onclick="proses_tugas('."'".$list_item->id."','2','".$list_item->perihal."'".')"><i class="fa fa-check"></i> Selesai</a>';
					}else{
						$row[] = "";
					}					
				}
			}else{
				$row[] = date_format(date_create($list_item->tanggal_selesai),"j M Y, \J\a\m G:i");			
			}			
			
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
	
	//custom validation function for dropdown input
    function check_file_tugas()
	{			
		if (!isset($_FILES['file_tugas']) || empty($_FILES['file_tugas']['name']) || ($_FILES['file_tugas']['name'] == ''))
		{
			$this->form_validation->set_message('check_file_tugas', 'Kolom %s belum diisi');
			return FALSE;	
		}else{
			$max_file_size = $this->cms_model->get_config_value('MAX_FILE_UPLOAD');
			if($_FILES['file_tugas']['size'] > $max_file_size){
				//cek ukuran file
				$this->form_validation->set_message('check_file_tugas', 'Ukuran file lebih dari '.$this->cms_model->bytesToSize($max_file_size));
				return FALSE;	
			}else{
				//cek file type				
				$file_type = $this->cms_model->get_config_value('TYPE_FILE_UPLOAD');
				$ext = strtolower(pathinfo($_FILES['file_tugas']['name'], PATHINFO_EXTENSION));
				
				if($ext == ""){
					//tidak punya ekstensi file
					$this->form_validation->set_message('check_file_tugas', 'Format file hanya '.$file_type);
					return FALSE;
				}else{
					$str_pos = strpos($file_type, $ext);

					if ($str_pos !== FALSE) {
						return TRUE;	
					}else{
						$this->form_validation->set_message('check_file_tugas', 'Format file hanya '.$file_type);
						return FALSE;					
					}	
				}							
			}			
		}
	}
	
	//custom validation function for dropdown input
    function check_personil()
    {		
		$user_id = $this->input->post('user_id_penerima');
		$nama = $this->input->post('nama_penerima');
				
		if(($nama == '') || ($user_id == '0') || ($user_id == '')){
			$this->form_validation->set_message('check_personil', 'Pilih %s dari tabel user');
			return FALSE;		
		}else{
			$row_user = $this->cms_model->row_get_by_criteria("users", "id = ".$user_id." AND nama = '".$nama."'");
			if(count($row_user) > 0){
				return TRUE;
			}else{
				//tidak ditemukan
				$this->form_validation->set_message('check_personil', 'Pilih %s sesuai tabel user');
				return FALSE;		
			}					
		}				      
    }
	
	public function download_file($tugas_id)
	{			
		$tugas_row = $this->cms_model->row_get_by_id($tugas_id, $this->table_name);			
		$user_id = $tugas_row->user_id_pengirim;
		$tugas_file = $tugas_row->filename;
		$tugas_file_server = $tugas_row->filename_server;		
		$folder_tugas = $this->cms_model->get_folder_tugas($user_id);	
		$data = $folder_tugas.$tugas_file_server;	
		
		ob_clean(); 		
		$data = $folder_tugas.$tugas_file_server;				
		//$data = file_get_contents($data); //assuming my file is on localhost
		$data = $this->_url_get_contents($data); 		
		force_download($tugas_file ,$data); 
		exit();
		
		/* ob_clean(); 		
		$data = $folder_tugas.$tugas_file_server;				
		$data = file_get_contents($data); //assuming my file is on localhost
		//$data = $this->_url_get_contents($data); 		
		force_download($tugas_file ,$data); 
		exit();  */
		/*$tmp = explode(".",$data);
		switch ($tmp[count($tmp)-1]) {
		  case "pdf": $ctype="application/pdf"; break;
		  case "exe": $ctype="application/octet-stream"; break;
		  case "zip": $ctype="application/zip"; break;
		  case "docx":
		  case "doc": $ctype="application/msword"; break;
		  case "csv":
		  case "xls":
		  case "xlsx": $ctype="application/vnd.ms-excel"; break;
		  case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
		  case "gif": $ctype="image/gif"; break;
		  case "png": $ctype="image/png"; break;
		  case "jpeg":
		  case "jpg": $ctype="image/jpg"; break;
		  case "tif":
		  case "tiff": $ctype="image/tiff"; break;
		  case "psd": $ctype="image/psd"; break;
		  case "bmp": $ctype="image/bmp"; break;
		  case "ico": $ctype="image/vnd.microsoft.icon"; break;
		  default: $ctype="application/force-download";
		}

		header("Pragma: public"); // required
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false); // required for certain browsers
		header("Content-Type: $ctype");
		header("Content-Disposition: attachment; filename=\"".$tugas_file."\";" );
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: ".filesize($data) );
		ob_clean();
		flush();
		readfile( $data );*/
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
	
	/* function show_laporan()
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
	} */	
	
	/* function show_laporan_delete()
	{
		$pdf_file = $this->input->post('pdf_file');			
		if($pdf_file != ""){
			unlink($pdf_file);
		}	
		echo json_encode(array("status" => TRUE));	
	}	 */
	
	function _render_page($view, $data=null, $returnhtml=false)//I think this makes more sense
	{

		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);

		if ($returnhtml) return $view_html;//This will return html on 3rd argument being true
	}
}


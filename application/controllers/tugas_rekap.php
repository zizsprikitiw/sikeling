<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class tugas_rekap extends CI_Controller {
	
	//Global variable
	var $table_name = 'tugas';
	var $table_name_view = 'tugas';
	
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
				
		$this->_render_page('tugas_rekap', $this->data);				
	}		
	
	public function data_init()
	{
		$data['filter_tahun'] = $this->cms_model->get_query_rows($this->table_name, "true", 'EXTRACT(year FROM "tanggal_submit") as id_item, EXTRACT(year FROM "tanggal_submit") as nama_item', "", "", "", "nama_item desc", "", "");  
		//array_unshift($data['filter_tahun'],array('id_item' => '0', 'nama_item' => '--Pilih--'));		
		echo json_encode($data);
	}
	
	public function data_list()
	{				
		$search_column = array('lower(judul)');
		$search_order = array('judul' => 'asc');
		$where =  array('EXTRACT(year FROM "tanggal_submit")=' => $_POST['filter_tahun']);  //laporan logbook (status=1)
		$order_by = 'tanggal_submit desc';								
		$user_id = $this->session->userdata('user_id');
		$list = $this->cms_model->get_datatables_where($this->table_name_view, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $list_item) {
			$no++;
			$row = array();
			$row[] = $no;						
			$row[] = '<a href="javascript:void()" title="Detail tugas" onclick="detail_tugas('.$list_item->id.')">'.$list_item->judul.'</a>';																							
			$row[] = date_format(date_create($list_item->tanggal_submit),"j M Y");
			
			if($list_item->proyek_id == "0"){
				$row[] = "Umum";
			}else{
				$row[] = "Kegiatan";
			}
			
			if($list_item->users_id == $user_id){
				$row[] = '<a class="btn btn-xs btn-warning" href="javascript:void()" title="Edit" onclick="data_edit('."'".$list_item->id."'".')"><i class="fa fa-pencil"></i></a>';
			}else{
				$row[] = "";
			}
									
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->cms_model->count_all_where($this->table_name_view, $where),
						"recordsFiltered" => $this->cms_model->count_filtered_where($this->table_name_view, $search_column, $search_order, $where, $order_by),
						"data" => $data,						
				);
		//output to json format
		echo json_encode($output);	
	}		
	
	public function data_edit($id)
	{						
		$data['list'] = $this->cms_model->row_get_by_id($id, $this->table_name);
		echo json_encode($data);
	}
	
	public function data_save_validation()
	{								      				
		//set validation rules
		$this->form_validation->set_rules('judul', 'Judul tugas', "required"); 
		$this->form_validation->set_rules('isi', 'Isi tugas', "required"); 
		$this->form_validation->set_rules('file_tugas', 'File tugas', 'callback_check_file_tugas');
						
		if ($this->form_validation->run() == FALSE)
        {            
			//validation fails
			echo json_encode(array("status" => validation_errors()));            
        }
        else
        {    									
			$proyek_id = "0";
			$file_tugas = "";
			$file_gambar = "";
			
			//UPLOAD FILE
			$upload_path = $this->cms_model->set_folder_tugas($proyek_id);
			
			if(!is_dir($upload_path)){
				mkdir($upload_path,0777);
			}
			
			if($_FILES['file_tugas']['name'] != '')
			{
				$file_tugas = $_FILES["file_tugas"]["name"];
				$file_tugas = preg_replace("/ /", '_', $file_tugas);
				$file_tugas = preg_replace("/&/", '_', $file_tugas);
				$file_tugas = preg_replace("/{/", '_', $file_tugas);
				$file_tugas = preg_replace("/}/", '_', $file_tugas);
				$upload_file = $upload_path.$file_tugas;
							
				if(is_file($upload_file)){
					$ext = pathinfo($_FILES['file_tugas']['name'], PATHINFO_EXTENSION);
					$new_filename = str_replace('.'.$ext, '', $file_tugas);				
					$file_tugas = $new_filename.'_'.mdate("%Y%m%d-%H%i%s", $_SERVER['REQUEST_TIME']).'.'.$ext;
					$upload_file = $upload_path.$file_tugas;				
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
						
			echo json_encode(array("status" => TRUE, "new_file_tugas" => $file_tugas, "new_file_gambar" => $file_gambar));			
        }//END FORM VALIDATION TRUE		
	}
	
	function data_save_add_tugas(){
		$proyek_id = "0";			
		$user_id = $this->session->userdata('user_id');
		$new_file_tugas = $this->input->post('new_file_tugas');
		$new_file_gambar = $this->input->post('new_file_gambar');														
		$upload_path = $this->cms_model->set_folder_tugas($proyek_id);
		$upload_file_tugas = $upload_path.$new_file_tugas;
		$upload_file_gambar = $upload_path.$new_file_gambar;
		$is_tugas = false;
		$is_gambar = false;
		//cek up;oad file tugas		
		if($new_file_tugas != ""){
			move_uploaded_file($_FILES["file_tugas"]["tmp_name"],$upload_file_tugas);	//UPLOAD THE FILE
			
			if(is_file($upload_file_tugas)){
				$is_tugas = true;
			}
		}else{
			$is_tugas = true;
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
								
		if($is_tugas && $is_gambar)
		{
			//simpan db						
			$additional_data = array(
					'judul' => $this->input->post('judul'),
					'isi' => $this->input->post('isi'),
					'users_id' => $user_id,					
					'status' => "1",
					'proyek_id' => $proyek_id,
					'filegambar'  => $new_file_gambar,
					'filename' => $_FILES["file_tugas"]["name"],
					'filename_server' => $new_file_tugas
					);	
			
			//SIMPAN tugas
			$tugas_id = $this->cms_model->save($additional_data, $this->table_name);	
			
			echo json_encode(array("status" => TRUE));
		}else{
			//File gagal upload				
			echo json_encode(array("status" => "File gagal upload ! <br />Ulangi beberapa saat lagi."));
		}
	}
	
	function data_save_edit_tugas(){					
		$id_tugas = $this->input->post('id');
		$user_id = $this->session->userdata('user_id');
		$new_file_tugas = $this->input->post('new_file_tugas');
		$new_file_gambar = $this->input->post('new_file_gambar');			
		$delete_file_gambar = $this->input->post('delete_file_gambar');
		$delete_file_tugas = $this->input->post('delete_file_tugas');	
		
		$row_tugas = $this->cms_model->row_get_by_id($id_tugas, $this->table_name);
		$proyek_id = $row_tugas->proyek_id;
		$file_gambar = $row_tugas->filegambar;
		$file_tugas = $row_tugas->filename;
		$file_tugas_server = $row_tugas->filename_server;
			  													
		$upload_path = $this->cms_model->set_folder_tugas($proyek_id);
		$upload_file_tugas = $upload_path.$new_file_tugas;
		$upload_file_gambar = $upload_path.$new_file_gambar;
		$is_tugas = false;
		$is_gambar = false;				
		
		//cek up;oad file tugas		
		if($new_file_tugas != ""){
			move_uploaded_file($_FILES["file_tugas"]["tmp_name"],$upload_file_tugas);	//UPLOAD THE FILE
			$file_tugas = $_FILES["file_tugas"]["name"];
			
			if(is_file($upload_file_tugas)){
				$is_tugas = true;
			}
			
			if($file_tugas_server != ""){
				//cek jika sebelumnya ada file hapus
				unlink($upload_path.$file_tugas_server);
			}						
		}else{
			$is_tugas = true;
			$new_file_tugas = $file_tugas_server;
			 			
			if($delete_file_tugas == "true"){
				//cek jika sebelumnya ada file hapus
				unlink($upload_path.$file_tugas_server);
				$file_tugas = "";
				$new_file_tugas = "";
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
		
		//simpan tugas						
		if($is_tugas && $is_gambar)
		{
			//simpan db						
			$additional_data = array(
					'judul' => $this->input->post('judul'),
					'isi' => $this->input->post('isi'),
					'users_id' => $user_id,					
					'status' => "1",
					'proyek_id' => $proyek_id,
					'filegambar'  => $new_file_gambar,
					'filename' => $file_tugas,
					'filename_server' => $new_file_tugas,
					'tanggal_submit' => 'now()'
					);	
			
			//SIMPAN tugas
			if($this->cms_model->update(array("id" => $id_tugas), $additional_data, $this->table_name))
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
   function check_file_tugas()
	{			
		if($_FILES['file_tugas']['name'] != '')
		{	
			$max_file_size = $this->cms_model->get_config_value('MAX_FILE_UPLOAD');
			if($_FILES['file_tugas']['size'] > $max_file_size){
				//cek ukuran file
				$this->form_validation->set_message('check_file_tugas', 'Ukuran file lebih dari '.$this->cms_model->bytesToSize($max_file_size));
				return FALSE;	
			}else{				
				return TRUE;							
			}			
		}
	}
	
	public function download_file($tugas_id)
	{				
		$tugas_row = $this->cms_model->row_get_by_id($tugas_id, "tugas");				
		$proyek_id = $tugas_row->proyek_id;
		$tugas_file = $tugas_row->filename;
		$tugas_file_server = $tugas_row->filename_server;		
		$folder_tugas = $this->cms_model->get_folder_tugas($proyek_id);	
			
		ob_clean(); 		
		$data = $folder_tugas.$tugas_file_server;
		//$data = file_get_contents($data); //assuming my file is on localhost
		$data = $this->_url_get_contents($data); 
		force_download($tugas_file ,$data); 
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
	
	public function detail_tugas($id)
	{						
		$data['list'] = $this->cms_model->row_get_by_id($id, $this->table_name_view);
		$data['download'] = "";
		$data['picture'] = "";
		
		if($data['list']->filegambar != ""){
			$folder_tugas = $this->cms_model->get_folder_tugas($data['list']->proyek_id);
			$data['picture'] = $folder_tugas.$data['list']->filegambar;
		}
		
		if($data['list']->filename != ""){
			$data['download'] = '<a href="'.site_url("/".$this->router->class."/download_file/".$data['list']->id).'" class="btn btn-xs btn-success" title="Download" ><i class="fa fa-download"></i></a> '.$data['list']->filename;
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


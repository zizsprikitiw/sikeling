<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Peralatan_lab extends CI_Controller {
	
	//Global variable
	var $table_name = 'peralatan_lab';
	var $table_name_view = 'v_peralatan_lab';
	var $table_lab = 'posisi_lab';
	var $document_root = 'DOCUMENT_ROOT_PROJECT';
	var $document_url = 'DOCUMENT_URL_PROJECT';
	var $folder_laporan;
	
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
		
		$this->folder_laporan = base_url().'pustekbang_file_archive';
	}
	
	public function index()
	{																
		// set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		$this->data['user_menu'] = $this->cms_model->get_user_menu($this->uri->rsegment(1));
		$this->data['user'] = $this->ion_auth->user()->row();
		$this->data['is_admin'] = $this->cms_model->user_is_admin();
				
		$this->_render_page('peralatan_lab', $this->data);				
	}		
	
	public function data_list()
	{		
		$is_admin = $this->cms_model->user_is_admin();
		$chkSearch = $this->input->post('chkSearch');
		
		$where = array();
				
		if($chkSearch[0] != "")
		{			
			foreach($chkSearch as $chkSearch_item)						
			{
				if($chkSearch_item == "tahun"){
					$where['tahun'] =  $this->input->post('filter_tahun');					
				}
				if($chkSearch_item == "lab"){
					$where['id_lab'] =  $this->input->post('filter_lab');					
				}
				if($chkSearch_item == "nama"){
					$where['nama_alat ~*'] = strtolower($this->input->post('nama'));
				}				
			}
		}	

		$datatable_name = $this->table_name_view;	
		$search_column = array('lower(nama_alat)','lower(filename)');
		$search_order = array('nama_alat' => 'asc' ,'filename' => 'asc');
		$order_by = 'submit_date desc';	
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $list_item) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $list_item->tahun;
			$row[] = $list_item->jenis_alat;
			$row[] = $list_item->nama_alat;
			$row[] = $list_item->nama_lab;		
			$row[] = '<a href="javascript:void()" onclick="show_laporan('."'".$list_item->id_alat."'".')" >'.$list_item->filename.'</a>';				
			
			//add html for action
			if($is_admin){
				//punya akses administrator
				$row[] = '<a href="'.site_url("/".$this->router->class."/download_file/".$list_item->id_alat).'" class="btn btn-xs btn-success" title="Download" ><i class="fa fa-download"></i></a>
							<a class="btn btn-xs btn-danger" href="javascript:void()" title="Hapus" onclick="data_delete('."'".$list_item->id_alat."','".$list_item->nama_alat."'".')"><i class="fa fa-times"></i></a>';
			}else{
				$row[] = '<a href="'.site_url("/".$this->router->class."/download_file/".$list_item->id_alat).'" class="btn btn-xs btn-success" title="Download" ><i class="fa fa-download"></i></a>';
			}	
			
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->cms_model->count_all($this->table_name_view),
						"recordsFiltered" => $this->cms_model->count_filtered($this->table_name_view, $search_column, $search_order),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);		
	}
	
	public function data_init()
	{		
		$list_lab = array();
		$list = $this->cms_model->query_get_all($this->table_lab);  
		
		foreach ($list as $list_item) {
			$list_lab[] = array("id_item" => $list_item->id_lab, "nama_item" => $list_item->nama_lab);
		}
		$data['filter_lab'] = $list_lab;		
		
		$data['filter_tahun'] = $this->cms_model->get_select_year(); 
			
		echo json_encode($data);
	}
	
	public function download_file($peralatan_id)
	{		
		$peralatan_row = $this->cms_model->row_get_by_criteria($this->table_name_view, array("id_alat" => $peralatan_id));				
		$peralatan_file = $peralatan_row->filename;						
		$peralatan_file_server = $peralatan_row->filename_server;						
		$folder_laporan = $this->cms_model->get_config_value($this->document_url);
			
		ob_clean(); 		
		$data = $this->folder_laporan.'/'.$peralatan_file_server;
		//$data = file_get_contents($data); //assuming my file is on localhost
		$data = $this->_url_get_contents($data);
		force_download($peralatan_file ,$data); 
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
		
	public function data_save_validation()
	{								      				
		//set validation rules
		$this->form_validation->set_rules('jenis_alat', 'Jenis Alat', 'trim|required');
		$this->form_validation->set_rules('id_lab', 'Nama Lab', 'trim|required');
		$this->form_validation->set_rules('nama_alat', 'Nama Alat', 'trim|required');
		$this->form_validation->set_rules('tahun', 'Tahun', 'trim|required');	
		$this->form_validation->set_rules('file_spek', 'File Spek', 'callback_check_file_spek');
						
		if ($this->form_validation->run() == FALSE)
        {            
			//validation fails
			echo json_encode(array("status" => validation_errors()));            
        }
        else
        {    						
			//UPLOAD FILE			
			$upload_path = $this->cms_model->get_config_value($this->document_root);
			
			if(!is_dir($upload_path)){
				mkdir($upload_path,0777, TRUE);
				//mkdir("C:\\Bitnami\\wappstack-5.6.27-0\\apache2\\htdocs\\pustekbang_file_archive\\50\\4\\21\\",0777);
				//echo json_encode($upload_path);
				//mkdir("C:\Bitnami\wappstack-5.6.27-0\apache2\htdocs\pustekbang_file_archive\coba");//agung
			}
			
			//$ext   = pathinfo($_FILES["file_spek"]["name"], PATHINFO_EXTENSION);
			//$file_name = basename($_FILES["file_spek"]["name"], ".$ext") . '_' .uniqid(). '.' . $ext;
			$file_name = $_FILES["file_spek"]["name"];
			$file_name = preg_replace("/ /", '_', $file_name);
			$file_name = preg_replace("/&/", '_', $file_name);
			$file_name = preg_replace("/{/", '_', $file_name);
			$file_name = preg_replace("/}/", '_', $file_name);
			$upload_file = $upload_path.'/'.$file_name;
						
			if(is_file($upload_file)){
				$ext = pathinfo($_FILES['file_spek']['name'], PATHINFO_EXTENSION);
				$new_filename = str_replace('.'.$ext, '', $file_name);				
				$file_name = $new_filename.'_'.mdate("%Y%m%d-%H%i%s", $_SERVER['REQUEST_TIME']).'.'.$ext;
				$upload_file = $upload_path.'/'.$file_name;				
			}
			
			echo json_encode(array("status" => TRUE, "new_file_name" => $file_name));			
        }//END FORM VALIDATION TRUE		
	}
	
	function data_save_peralatan(){						
		$status = $this->input->post('status');			
		$new_file_name = $this->input->post('new_file_name');	
		$user_id = $this->session->userdata('user_id');
				
		//UPLOAD FILE			
		$upload_path = $this->cms_model->get_config_value($this->document_root);																	
		$upload_file = $upload_path."\\".$new_file_name;
		
		move_uploaded_file($_FILES["file_spek"]["tmp_name"],$upload_file);	//UPLOAD THE FILE
		
		if(is_file($upload_file))
		{						
			if($status == "baru" ){
				//simpan db	
				$additional_data = array(
					'submit_user' => $user_id,
					'update_user' => $user_id,
					'status' => $this->input->post('jenis_alat'),
					'id_lab' => $this->input->post('id_lab'),
					'nama_alat' => $this->input->post('nama_alat'),
					'resume' => $this->input->post('resume'),
					'filename' => $_FILES["file_spek"]["name"],
					'filename_server' => $new_file_name,
					'tahun' => $this->input->post('tahun')
					);
						echo "baru"; //agung
			}else{
				//revisi
				$additional_data = array(
					'submit_user' => $user_id,
					'update_user' => $user_id,
					'status' => $this->input->post('jenis_alat'),
					'id_lab' => $this->input->post('id_lab'),
					'nama_alat' => $this->input->post('nama_alat'),
					'resume' => $this->input->post('resume'),
					'filename' => $_FILES["file_spek"]["name"],
					'filename_server' => $new_file_name,
					'tahun' => $this->input->post('tahun')
					);
			}
			echo "revisi";
									
			//SIMPAN Peralatan Lab
			$peralatan_id = $this->cms_model->save($additional_data, $this->table_name);		
			
			echo json_encode(array("status" => TRUE));
		}else{
			//File gagal upload				
			echo json_encode(array("status" => "File gagal upload ! <br />Ulangi beberapa saat lagi."));
		}
	}
	
	function check_file_spek()
	{			
		if (!isset($_FILES['file_spek']) || empty($_FILES['file_spek']['name']) || ($_FILES['file_spek']['name'] == ''))
		{
			$this->form_validation->set_message('check_file_spek', 'Kolom %s belum diisi');
			return FALSE;	
		}else{
			$max_file_size = $this->cms_model->get_config_value('MAX_FILE_UPLOAD');
			if($_FILES['file_spek']['size'] > $max_file_size){
				//cek ukuran file
				$this->form_validation->set_message('check_file_spek', 'Ukuran file lebih dari '.$this->cms_model->bytesToSize($max_file_size));
				return FALSE;	
			}else{
				//cek file type				
				$file_type = $this->cms_model->get_config_value('TYPE_FILE_UPLOAD');
				$ext = strtolower(pathinfo($_FILES['file_spek']['name'], PATHINFO_EXTENSION));
				
				if($ext == ""){
					//tidak punya ekstensi file
					$this->form_validation->set_message('check_file_spek', 'Format file hanya '.$file_type);
					return FALSE;
				}else{
					$str_pos = strpos($file_type, $ext);

					if ($str_pos !== FALSE) {
						return TRUE;	
					}else{
						$this->form_validation->set_message('check_file_spek', 'Format file hanya '.$file_type);
						return FALSE;					
					}	
				}
							
			}			
		}
	}
	
	function show_laporan()
	{
		$peralatan_id = $this->input->post('peralatan_id');	
		$peralatan_row = $this->cms_model->row_get_by_criteria($this->table_name_view, array("id_alat" => $peralatan_id));				
		$peralatan_file = $peralatan_row->filename_server;						
		$folder_laporan = $this->cms_model->get_config_value($this->document_url);
		$data['laporan_title'] = "File Dokumen: ".$peralatan_row->filename."<br />Tahun Dokumen: ".date_format(date_create($peralatan_row->tahun),"Y");
		
		$file_template = FCPATH."assets\angular_pdf\pdf_loader.php";		
		$file_content = file_get_contents($file_template);		
		$vars = array("{pdf_url}" => $this->folder_laporan.'/'.$peralatan_file);
		$file_content = strtr($file_content, $vars);
		$new_filename = mdate("%Y%m%d-%H%i%s", $_SERVER['REQUEST_TIME']).'.php';		
		$file_template = FCPATH."assets\angular_pdf\pdf_loader_".$new_filename;
		$result = write_file($file_template, $file_content);	
							
		$data['laporan_filename_url'] = base_url()."assets/angular_pdf/pdf_loader_".$new_filename;
		$data['laporan_filename_path'] = $file_template;		
		echo json_encode($data);	
	}	
	
	public function data_delete()
	{			
        $laporan_id = $this->input->post('id_delete_data');						
		$laporan_row = $this->cms_model->row_get_by_criteria($this->table_name, array("id_alat" => $laporan_id));
		
		//hapus file
		if($laporan_row->filename_server != ""){				
			$upload_path = $this->cms_model->get_config_value($this->document_root);		
			$upload_file = $upload_path.'/'.$laporan_row->filename_server;
			
			if(is_file($upload_file)){
				unlink($upload_file);
			}						
		}
		
		//hapus laporan
		$this->cms_model->delete($this->table_name, array('id_alat' => $laporan_id));		
		
		echo json_encode(array("status" => TRUE));
	}		
	
	function _render_page($view, $data=null, $returnhtml=false)//I think this makes more sense
	{

		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);

		if ($returnhtml) return $view_html;//This will return html on 3rd argument being true
	}
	
	public function load_select()
	{	
		$list_lab = array();
		$list = $this->cms_model->query_get_all($this->table_lab);  
		
		foreach ($list as $list_item) {
			$list_lab[] = array("id_item" => $list_item->id_lab, "nama_item" => $list_item->nama_lab);
		}
		
		$tahun = $this->cms_model->get_select_year();  
		
		$output = array("tahun" => $tahun, "id_lab" => $list_lab);						
		echo json_encode($output);
	}
}


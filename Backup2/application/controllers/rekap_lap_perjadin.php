<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rekap_lap_perjadin extends CI_Controller {
	
	//Global variable
	var $table_name = 'log_book';
	var $table_name_view = 'v_users_log_book';
	var $jenis_laporan = '4';	//laporan logbook
	
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
				
		$this->_render_page('rekap_lap_perjadin', $this->data);				
	}		
	
	public function data_init()
	{
		$data['filter_tahun'] = $this->cms_model->get_tahun_logbook($this->jenis_laporan, "");  
		array_unshift($data['filter_tahun'],array('id_item' => '0', 'nama_item' => '--Pilih--'));	
		$data['filter_bulan'] = $this->cms_model->get_list_bulan();  
		array_unshift($data['filter_bulan'],array('id_item' => '0', 'nama_item' => '--Pilih--'));
			
		echo json_encode($data);
	}
	
	public function data_list()
	{	
		$is_admin = $this->cms_model->user_is_admin();
		$chkSearch = $this->input->post('chkSearch');		
		$where['status'] =  $this->jenis_laporan;		
				
		if($chkSearch[0] != "")
		{			
			foreach($chkSearch as $chkSearch_item)						
			{
				if($chkSearch_item == "tahun"){
					$where['EXTRACT(year FROM "tgl_dokumen")='] =  $this->input->post('filter_tahun');					
				}
				if($chkSearch_item == "bulan"){
					$where['EXTRACT(month FROM "tgl_dokumen")='] =  $this->input->post('filter_bulan');	
				}
				if($chkSearch_item == "nama"){
					$where['nama_user ~*'] = strtolower($this->input->post('nama'));
				}				
			}
		}else{
			$where['id'] = '0';			
		}	

		$datatable_name = $this->table_name_view;	
		$search_column = array('lower(nama_user)','lower(filename)');
		$search_order = array('nama_user' => 'asc' ,'filename' => 'asc');
		$order_by = 'submit_date desc';				
		
		//$folder_logbook = $this->cms_model->get_folder_logbook("");
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $list_item) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $list_item->nama_user;
			$row[] = '<a href="javascript:void()" onclick="show_laporan('."'".$list_item->id."'".')" >'.$list_item->filename.'</a>';																							
			$row[] = date_format(date_create($list_item->tgl_dokumen),"j M Y");
			$row[] = date_format(date_create($list_item->submit_date),"j M Y, \J\a\m G:i");
			
			if($is_admin){
				//punya akses administrator
				$row[] = '<a href="'.site_url("/".$this->router->class."/download_file/".$list_item->id).'" class="btn btn-xs btn-success" title="Download" ><i class="fa fa-download"></i></a>
						<a class="btn btn-xs btn-danger" href="javascript:void()" title="Hapus" onclick="data_delete('."'".$list_item->id."','".$list_item->filename."'".')"><i class="fa fa-times"></i></a>';	
			}else{
				$row[] = '<a href="'.site_url("/".$this->router->class."/download_file/".$list_item->id).'" class="btn btn-xs btn-success" title="Download" ><i class="fa fa-download"></i></a>';
			}					
																						
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
	
	public function data_delete()
	{			
        $laporan_id = $this->input->post('id_delete_data');						
		$laporan_row = $this->cms_model->row_get_by_id($laporan_id, $this->table_name);
		
		//hapus table note_inbox_outbox		
		$this->cms_model->delete('log_book_inbox_outbox', array('log_book_id' => $laporan_id));	
		
		//hapus file
		if($laporan_row->filename_server != ""){		
			$user_id = $laporan_row->user_id;					
			$upload_path = $this->cms_model->set_folder_logbook($user_id);			
			$upload_file = $upload_path.$laporan_row->filename_server;
			
			if(is_file($upload_file)){
				unlink($upload_file);
			}						
		}
		
		//hapus laporan
		$this->cms_model->delete($this->table_name, array('id' => $laporan_id));		
		
		echo json_encode(array("status" => TRUE));
	}
	
	function _render_page($view, $data=null, $returnhtml=false)//I think this makes more sense
	{

		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);

		if ($returnhtml) return $view_html;//This will return html on 3rd argument being true
	}
}


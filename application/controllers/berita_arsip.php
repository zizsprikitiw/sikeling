<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Berita_arsip extends CI_Controller {
	
	//Global variable
	var $table_name = 'berita';
	var $table_name_view = 'v_berita';
	
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
				
		$this->_render_page('berita_arsip', $this->data);				
	}		
	
	public function data_init()
	{
		$data['filter_tahun'] = $this->cms_model->get_query_rows($this->table_name, "true", 'EXTRACT(year FROM "tanggal_submit") as id_item, EXTRACT(year FROM "tanggal_submit") as nama_item', "", "", "", "nama_item desc", "", "");  
		echo json_encode($data);
	}
	
	public function data_list()
	{				
		//load proyek id untuk user
		$list_proyek_id = $this->cms_model->get_query_rows("users_posisi", "true", "proyek_id", "user_id = '".$this->session->userdata('user_id')."'", "", "", "", "", "");
		$proyek_ids = "0";
		if(count($list_proyek_id) > 0){			
			foreach($list_proyek_id as $proyek_id_item){
				$proyek_ids = $proyek_ids.",".$proyek_id_item->proyek_id;								
			}
		}				
		
		$search_column = array('lower(judul)');
		$search_order = array('judul' => 'asc');
		$where =  array('EXTRACT(year FROM "tanggal_submit")=' => $_POST['filter_tahun'], 'proyek_id IN ('.$proyek_ids.')' => null);  
		$order_by = 'tanggal_submit desc';								

		$list = $this->cms_model->get_datatables_where($this->table_name_view, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $list_item) {
			$no++;
			$row = array();
			$row[] = $no;						
			$row[] = '<a href="javascript:void()" title="Detail Berita" onclick="detail_berita('.$list_item->id.')">'.$list_item->judul.'</a>';																							
			$row[] = date_format(date_create($list_item->tanggal_submit),"j M Y");
			
			if($list_item->proyek_id == "0"){
				$row[] = "Umum";
			}else{
				$row[] = "Kegiatan";
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
	
	public function download_file($berita_id)
	{				
		$berita_row = $this->cms_model->row_get_by_id($berita_id, "berita");				
		$proyek_id = $berita_row->proyek_id;
		$berita_file = $berita_row->filename;
		$berita_file_server = $berita_row->filename_server;		
		$folder_berita = $this->cms_model->get_folder_berita($proyek_id);	
			
		ob_clean(); 		
		$data = $folder_berita.$berita_file_server;
		//$data = file_get_contents($data); //assuming my file is on localhost
		$data = $this->_url_get_contents($data); 
		force_download($berita_file ,$data); 
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
	
	public function detail_berita($id)
	{						
		$data['list'] = $this->cms_model->row_get_by_id($id, $this->table_name_view);
		$data['download'] = "";
		$data['picture'] = "";
		
		if($data['list']->filegambar != ""){
			$folder_berita = $this->cms_model->get_folder_berita($data['list']->proyek_id);
			$data['picture'] = $folder_berita.$data['list']->filegambar;
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


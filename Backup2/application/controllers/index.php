<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends CI_Controller {
	function __construct()
	{
			parent::__construct();
			$this->load->database();
			$this->load->library(array('ion_auth','form_validation'));
			$this->load->helper(array('url','language'));
			$this->load->helper('download');
			
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
		$this->data['title'] = "Beranda";;
		$this->data['menu_id'] = '1';				
		
		
		// set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		$this->data['user_menu'] = $this->cms_model->get_user_menu($this->uri->rsegment(1));
		$this->data['user'] = $this->ion_auth->user()->row();						
		
		$this->_render_page('index', $this->data);				
	}
	
	public function init_tahun_proyek()
	{		
		$data['tahun_proyek'] = $this->cms_model->get_year_proyek_by_user($this->session->userdata('user_id')); 		
		echo json_encode($data);
	}
	
	public function load_proyek()
	{
		$start_year = $this->input->post('start_year');
		$end_year = $this->input->post('end_year');
		
		$list = $this->cms_model->get_proyek_beranda($this->session->userdata('user_id'), $start_year, $end_year);
		$data = array();
		
		foreach($list as $list_item){
			$row = array();
			$row['id'] = $list_item->proyek_id;
			$row['nama_proyek'] = $list_item->nama_proyek;
			$row['tahun'] = $list_item->tahun;					
			$row['posisi'] = $list_item->nama_posisi.' '.$list_item->nama_gl.' '.$list_item->nama_ld;

			$data[] = $row;
		}
		
		$output = array("list_proyek" => $data);
		echo json_encode($output);
	}
	
	public function load_berita()
	{
		//load proyek id untuk user
		$list_proyek_id = $this->cms_model->get_query_rows("users_posisi", "true", "proyek_id", "user_id = '".$this->session->userdata('user_id')."'", "", "", "", "", "");
		$proyek_ids = array();
		if(count($list_proyek_id) > 0){			
			foreach($list_proyek_id as $proyek_id_item){				
				$proyek_ids[] = $proyek_id_item->proyek_id;								
			}
		}		
		$proyek_ids[] = "0";
		
		//load berita dengan proyek_id=0 => berita global untuk semua orang
		$data['list_berita'] = $this->cms_model->get_query_rows("v_berita", "false", "", "", "proyek_id",$proyek_ids, "tanggal_submit desc", "", 5);						
		echo json_encode($data);
	}
		
	public function detail_berita($id)
	{						
		$data['list'] = $this->cms_model->row_get_by_id($id, "v_berita");
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

	function _render_page($view, $data=null, $returnhtml=false)//I think this makes more sense
	{

		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);

		if ($returnhtml) return $view_html;//This will return html on 3rd argument being true
	}
}

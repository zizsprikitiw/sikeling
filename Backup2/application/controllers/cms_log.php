<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cms_log extends CI_Controller {
	
	//Global variable
	var $table_name = 'log';
	var $table_name_view = 'v_users_log';
	
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(array('ion_auth','form_validation'));
		$this->load->helper(array('url','language'));

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
				
		$this->_render_page('cms/cms_log', $this->data);				
	}		
	
	public function data_init()
	{
		$data['filter_tahun'] = $this->cms_model->get_tahun_log();  
		array_unshift($data['filter_tahun'],array('id_item' => '0', 'nama_item' => '--Pilih--'));	
		$data['filter_bulan'] = $this->cms_model->get_list_bulan();  
		array_unshift($data['filter_bulan'],array('id_item' => '0', 'nama_item' => '--Pilih--'));
			
		echo json_encode($data);
	}
	
	public function data_list()
	{	
		$chkSearch = $this->input->post('chkSearch');		
		//$where['status'] =  $this->jenis_laporan;		
				
		if($chkSearch[0] != "")
		{			
			foreach($chkSearch as $chkSearch_item)						
			{
				if($chkSearch_item == "tahun"){
					$where['EXTRACT(year FROM "waktu")='] =  $this->input->post('filter_tahun');					
				}
				if($chkSearch_item == "bulan"){
					$where['EXTRACT(month FROM "waktu")='] =  $this->input->post('filter_bulan');	
				}
				if($chkSearch_item == "nama"){
					$where['nama ~*'] = strtolower($this->input->post('nama'));
				}
				if($chkSearch_item == "keterangan"){
					$where['address ~*'] = strtolower($this->input->post('keterangan'));
				}				
			}
		}else{
			$where['id'] = '0';			
		}	

		$datatable_name = $this->table_name_view;	
		$search_column = array('lower(nama)', 'lower(address)');
		$search_order = array('nama' => 'asc', 'address' => 'asc');
		$order_by = 'waktu desc';					
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $list_item) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = date_format(date_create($list_item->waktu),"j M Y, \J\a\m G:i");
			$row[] = $list_item->user_id;
			$row[] = $list_item->nama;
			$row[] = $list_item->address;							
																						
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
	
	function _render_page($view, $data=null, $returnhtml=false)//I think this makes more sense
	{

		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);

		if ($returnhtml) return $view_html;//This will return html on 3rd argument being true
	}
}


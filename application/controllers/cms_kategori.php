<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cms_kategori extends CI_Controller {
	
	//Global variable
	var $table_name = 'approval_level';
	var $table_name_view = 'v_approval_level';
	
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
				
		$this->_render_page('cms/cms_kategori', $this->data);				
	}		
	
	public function data_init()
	{
		$data['filter_kategori'] = array(array("id_item" => '3', "nama_item" => 'Text Berjalan'),array("id_item" => '1', "nama_item" => 'Ruangan Agenda'),array("id_item" => '2', "nama_item" => 'Jenis Usulan - Stat Kepegawaian')); 		
		echo json_encode($data);
	}
	
	public function data_list()
	{						
		$filter_kategori = $_POST['filter_kategori'];
		$database_name = "";
		$search_column = "";
		$search_order = "";
		$where =  "";
		$order_by = '';		
		
		if($filter_kategori==1){
			$database_name = "agenda_ruangan";
			$order_by = 'id_ruangan ASC';	
		} else if ($filter_kategori==2){
			$database_name = "status_kepegawaian_jenisusulan";
			$order_by = 'id_jenis_usulan ASC';
		} else if ($filter_kategori==3){
			$database_name = "text_berjalan";
			$order_by = 'id_text ASC';
		}
		
		$list = $this->cms_model->get_datatables_where($database_name, $search_column, $search_order, $where, $order_by);		
		
		$data = array();
		$no = 0;//$_POST['start'];		
		
		foreach ($list as $list_item) {
			$nama_kategori = "";
			$aksi = "";
			
			if($filter_kategori==1){
				$nama_kategori = $list_item->nama_ruangan;
				$aksi .= '<a class="btn btn-xs btn-warning" href="javascript:void()" title="Edit" onclick="data_edit('."'".$list_item->id_ruangan."','1'".')"><i class="fa fa-pencil"></i></a>';
			} else if ($filter_kategori==2){
				$nama_kategori = $list_item->nama_jenis_usulan;
				$aksi .= '<a class="btn btn-xs btn-warning" href="javascript:void()" title="Edit" onclick="data_edit('."'".$list_item->id_jenis_usulan."','2'".')"><i class="fa fa-pencil"></i></a>';
			} else if ($filter_kategori==3){
				$nama_kategori = $list_item->deskripsi;
				$aksi .= '<a class="btn btn-xs btn-warning" href="javascript:void()" title="Edit" onclick="data_edit('."'".$list_item->id_text."','3'".')"><i class="fa fa-pencil"></i></a>';
			}
			
			$no++;
			$row = array();
			$row[] = $no;	
			$row[] = $nama_kategori;
			$row[] = $aksi;
			$data[] = $row;
		}
		
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->cms_model->count_all_where($database_name, $where),
			"recordsFiltered" => $this->cms_model->count_filtered_where($database_name, $search_column, $search_order, $where, $order_by),
			"data" => $data,
				);
		//output to json format
		echo json_encode($output);		
	}	
	
	public function data_add()
	{
		$data['id_kategori'] = array(array("id_item" => '3', "nama_item" => 'Text Berjalan'),array("id_item" => '1', "nama_item" => 'Ruangan Agenda'),array("id_item" => '2', "nama_item" => 'Jenis Usulan - Stat Kepegawaian')); 			
		echo json_encode($data);
	}
		
	public function data_edit()
	{							
		$id = $_GET['id'];
		$filter_kategori = $_GET['id_kategori'];
		$id_tabel = "";
		$nama_kategori = "";
		
		if($filter_kategori==1){
			$database_name = "agenda_ruangan";
			$id_tabel = "id_ruangan";
		} else if ($filter_kategori==2){
			$database_name = "status_kepegawaian_jenisusulan";
			$id_tabel = "id_jenis_usulan";
		} else if ($filter_kategori==3){
			$database_name = "text_berjalan";
			$id_tabel = "id_text";
		}
		
		$data_tabel = $this->cms_model->row_get_by_criteria($database_name, array($id_tabel => $id));
		
		if($filter_kategori==1){
			$nama_kategori = $data_tabel->nama_ruangan;
		} else if ($filter_kategori==2){
			$nama_kategori = $data_tabel->nama_jenis_usulan;
		} else if ($filter_kategori==3){
			$nama_kategori = $data_tabel->deskripsi;
		}
		
		$data['id'] = $id;
		$data['nama_kategori'] = $nama_kategori;
		$data['filter_kategori'] = $filter_kategori;
		$data['id_kategori'] = array(array("id_item" => '3', "nama_item" => 'Text Berjalan'),array("id_item" => '1', "nama_item" => 'Ruangan Agenda'),array("id_item" => '2', "nama_item" => 'Jenis Usulan - Stat Kepegawaian')); 						
		
		echo json_encode($data);
	}
	
	public function data_save_add()
	{					
        //set validation rules		
		$this->form_validation->set_rules('nama_kategori', 'Nama Kategori', 'trim|required');		
			
		if ($this->form_validation->run() == FALSE) {            
			//validation fails
			echo json_encode(array("status" => validation_errors()));            
        } else  {    
			$filter_kategori = $this->input->post('id_kategori');
			$nama_kategori = $this->input->post('nama_kategori');		
			$user_id = $this->session->userdata('user_id');
			$database_name = "";
			$additional_data = array();
			
			if($filter_kategori==1){
				$database_name = "agenda_ruangan";
				$additional_data['nama_ruangan'] = $nama_kategori;
			} else if ($filter_kategori==2){
				$database_name = "status_kepegawaian_jenisusulan";
				$additional_data['nama_jenis_usulan'] = $nama_kategori;
				$additional_data['submit_user'] = $user_id;
			} else if ($filter_kategori==3){
				$database_name = "text_berjalan";
				$additional_data['deskripsi'] = $nama_kategori;
				$additional_data['submit_user'] = $user_id;
			}
			
			$insert = $this->cms_model->save($additional_data, $database_name);									
			echo json_encode(array("status" => TRUE));
        }		
	}
	
	public function data_save_edit()
	{								
        //set validation rules		
		$this->form_validation->set_rules('nama_kategori', 'Nama Kategori', 'trim|required');	
		
		if ($this->form_validation->run() == FALSE)
        {            
			//validation fails
			echo json_encode(array("status" => validation_errors()));            
        }
        else
        {    
			$id = $this->input->post('id');
			$filter_kategori = $this->input->post('id_kategori');
			$nama_kategori = $this->input->post('nama_kategori');		
			$user_id = $this->session->userdata('user_id');
			$database_name = "";
			$id_tabel = "";
			$where = array();
			$additional_data = array();
			
			if($filter_kategori==1){
				$database_name = "agenda_ruangan";
				$id_tabel = "id_ruangan";
				$where['id_ruangan'] = $id;
				$additional_data['nama_ruangan'] = $nama_kategori;
			} else if ($filter_kategori==2){
				$database_name = "status_kepegawaian_jenisusulan";
				$id_tabel = "id_jenis_usulan";
				$where['id_jenis_usulan'] = $id;
				$additional_data['nama_jenis_usulan'] = $nama_kategori;
				$additional_data['update_user'] = $user_id;
			} else if ($filter_kategori==3){
				$database_name = "text_berjalan";
				$id_tabel = "id_text";
				$where['id_text'] = $id;
				$additional_data['deskripsi'] = $nama_kategori;
				$additional_data['update_user'] = $user_id;
			}

			$update = $this->cms_model->update($where, $additional_data, $database_name);
			echo json_encode(array("status" => TRUE));            	
        }		
	}
	
	public function data_delete()
	{			
        $id = $this->input->post('id_delete_data');				
		$data_list = $this->cms_model->row_get_by_id($id, $this->table_name);
		
		$no_level = $data_list->level;		
		//$approval_type_id = $data_list->approval_type_id;	
		$where["approval_type_id"] =  $data_list->approval_type_id;
		$where["level >"] =  $no_level;
		
		if(! is_null($data_list->out_struktural_id)){
			$where["out_struktural_id"] = $data_list->out_struktural_id;
		}else if(! is_null($data_list->out_posisi_id)){
			$where["out_posisi_id"] = $data_list->out_posisi_id;
		}else if(! is_null($data_list->out_fungsional_id)){
			$where["out_fungsional_id"] = $data_list->out_fungsional_id;
		}else{
			$where["out_group_id"] = $data_list->out_group_id;
		}		
			
		$update = $this->cms_model->update_using_set($where, array("level" => "level - 1"), $this->table_name);
		$this->cms_model->delete($this->table_name, array('id' => $id));		//hapus data
		echo json_encode(array("status" => TRUE));					
	}	
	
	public function data_edit_posisi()
	{
		$id = $this->input->post('id');
		$pos = $this->input->post('pos');
		$data_list = $this->cms_model->row_get_by_id($id, $this->table_name);
		
		$no_level = $data_list->level;				
		$where["approval_type_id"] =  $data_list->approval_type_id;
		
		if(! is_null($data_list->out_struktural_id)){
			$where["out_struktural_id"] = $data_list->out_struktural_id;
		}else if(! is_null($data_list->out_posisi_id)){
			$where["out_posisi_id"] = $data_list->out_posisi_id;
		}else if(! is_null($data_list->out_fungsional_id)){
			$where["out_fungsional_id"] = $data_list->out_fungsional_id;
		}else{
			$where["out_group_id"] = $data_list->out_group_id;
		}
				
		if($pos == 'up'){
			//naikan no urut n-1 menjadi n
			$where["level"] = $no_level-1;			
			$update = $this->cms_model->update($where, array("level" => $no_level), $this->table_name);
			
			//update no urut n menjadi n-1			
			$update = $this->cms_model->update(array("id" => $id), array("level" => $no_level-1), $this->table_name);
		}else{
			//down		
			$where["level"] = $no_level+1;		
			$update = $this->cms_model->update($where, array("level" => $no_level), $this->table_name);
			
			//update no urut n menjadi n+1
			$update = $this->cms_model->update(array("id" => $id), array("level" => $no_level+1), $this->table_name);
		}
		
		echo json_encode(array("status" => TRUE));
	}	
	
	function check_select_out($opsiId)
    {			
		$option_out = $this->input->post('optPengirim');
		$select_out = $this->input->post('select_'.$option_out.'_out');
		
		if(($select_out == '') || ($select_out == '0')){
			$this->form_validation->set_message('check_select_out', 'Kolom %s pengirim belum dipilih');
			return FALSE;		
		}else{
			return TRUE;		
		}				      
    }
	
	function check_select_in($opsiId)
    {			
		$option_in = $this->input->post('optPenerima');
		$select_in = $this->input->post('select_'.$option_in.'_in');
		
		if(($select_in == '') || ($select_in == '0')){
			$this->form_validation->set_message('check_select_in', 'Kolom %s penerima belum dipilih');
			return FALSE;		
		}else{
			return TRUE;		
		}				      
    }	
	
	function _render_page($view, $data=null, $returnhtml=false)//I think this makes more sense
	{

		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);

		if ($returnhtml) return $view_html;//This will return html on 3rd argument being true
	}
}


<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Status_kepegawaian extends CI_Controller {
	
	//Global variable
	var $table_name = 'status_kepegawaian';
	var $table_name_view = 'v_status_kepegawaian';
	var $table_proyek_view = 'v_users_proyek';	
	
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
				
		$this->_render_page('status_kepegawaian', $this->data);				
	}
	
	public function all()
	{						
		// set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		$this->data['user_menu'] = $this->cms_model->get_user_menu($this->uri->rsegment(1));
		$this->data['user'] = $this->ion_auth->user()->row();
				
		$this->_render_page('status_kepegawaian_all', $this->data);				
	}

	public function data_list_baru()
	{		
		$is_admin = $this->cms_model->user_is_admin();
		$user_id = $this->session->userdata('user_id');
		$chkSearch = $this->input->post('chkSearch');
		
		$where = "id_posisi = 1 ";
		
		$datatable_name = $this->table_name_view;	
		$search_column = array('lower(no_surat)','lower(nama_jenis_usulan)');
		$search_order = array();
		$order_by = 'history_date asc';		
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];
		setlocale(LC_ALL, "IND");
		foreach ($list as $list_item) {
			$staff = "-";
			$aksi = "";
			
			$data_staff = $this->cms_model->query_get_by_criteria('v_status_kepegawaian_staff', array("id_status_kepegawaian" => $list_item->id_status_kepegawaian), 'nama ASC');	
			$array_s = array();
			foreach($data_staff as $ds){
				$array_s[] = '[<b>'.$ds->nama.'</b>]';
			}
			$staff = join(' dan ', array_filter(array_merge(array(join(', ', array_slice($array_s, 0, -1))), array_slice($array_s, -1)), 'strlen'));
			
			//add html for action
			if($is_admin){
				//punya akses administrator
				//$aksi .= '<a class="btn btn-xs btn-success" href="javascript:void()" title="Approve" onclick="data_approve('."'".$list_item->id_status_kepegawaian."','".$list_item->no_surat."'".')"><i class="fa fa-check"></i></a>';
				$aksi .= '<div class="btn-group">
							<a class="btn btn-xs btn-success dropdown-toggle" data-toggle="dropdown" href="#">
								Next
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu pull-right">
								<li><a href="javascript:void()" onclick="ubah_posisi('."'".$list_item->id_status_kepegawaian."','2','Proses SDM'".')">Proses SDM</a></li>
							</ul>
						</div>';
				$aksi .= '<a class="btn btn-xs btn-primary" href="javascript:void()" title="Edit Keterangan" onclick="data_edit_keterangan('."'".$list_item->id_history."'".')"><i class="fa fa-pencil-square-o"></i></a>';
				$aksi .= '<a class="btn btn-xs btn-warning" href="javascript:void()" title="Edit" onclick="data_edit('."'".$list_item->id_status_kepegawaian."'".')"><i class="fa fa-pencil"></i></a>';
				$aksi .= '<a class="btn btn-xs btn-danger" href="javascript:void()" title="Hapus" onclick="data_delete('."'".$list_item->id_status_kepegawaian."','".$list_item->no_surat."'".')"><i class="fa fa-times"></i></a>';
			}
			
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $list_item->no_surat;		
			$row[] = $staff;
			$row[] = '<b>'.$list_item->nama_jenis_usulan.'</b>';
			$row[] = '<script type="text/javascript">durasi("'.$list_item->id_status_kepegawaian.'","'.$list_item->history_date.'");</script><p id="durasi'.$list_item->id_status_kepegawaian.'"></p>';			
			$row[] = $list_item->keterangan;		
			$row[] = $aksi;		
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

	public function data_list_sdm()
	{		
		$is_admin = $this->cms_model->user_is_admin();
		$user_id = $this->session->userdata('user_id');
		$chkSearch = $this->input->post('chkSearch');
		
		$where = "id_posisi = 2 ";
		
		$datatable_name = $this->table_name_view;	
		$search_column = array('lower(no_surat)','lower(nama_jenis_usulan)');
		$search_order = array();
		$order_by = 'history_date asc';		
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];
		setlocale(LC_ALL, "IND");
		foreach ($list as $list_item) {
			$staff = "-";
			$aksi = "";
			
			$data_staff = $this->cms_model->query_get_by_criteria('v_status_kepegawaian_staff', array("id_status_kepegawaian" => $list_item->id_status_kepegawaian), 'nama ASC');	
			$array_s = array();
			foreach($data_staff as $ds){
				$array_s[] = '[<b>'.$ds->nama.'</b>]';
			}
			$staff = join(' dan ', array_filter(array_merge(array(join(', ', array_slice($array_s, 0, -1))), array_slice($array_s, -1)), 'strlen'));
			
			//add html for action
			if($is_admin){
				//punya akses administrator
				//$aksi .= '<a class="btn btn-xs btn-success" href="javascript:void()" title="Approve" onclick="data_approve('."'".$list_item->id_status_kepegawaian."','".$list_item->no_surat."'".')"><i class="fa fa-check"></i></a>';
				$aksi .= '<div class="btn-group">
							<a class="btn btn-xs btn-success dropdown-toggle" data-toggle="dropdown" href="#">
								Next
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu pull-right">
								<li><a href="javascript:void()" onclick="ubah_posisi('."'".$list_item->id_status_kepegawaian."','3','Proses SDM Orkum'".')">Proses SDM Orkum</a></li>
								<li><a href="javascript:void()" onclick="ubah_posisi('."'".$list_item->id_status_kepegawaian."','4','Proses Arsip'".')">Proses Arsip</a></li>
								<li><a href="javascript:void()" onclick="ubah_posisi('."'".$list_item->id_status_kepegawaian."','10','Final'".')">Final</a></li>
							</ul>
						</div>';
				$aksi .= '<a class="btn btn-xs btn-primary" href="javascript:void()" title="Edit Keterangan" onclick="data_edit_keterangan('."'".$list_item->id_history."'".')"><i class="fa fa-pencil-square-o"></i></a>';
				$aksi .= '<a class="btn btn-xs btn-warning" href="javascript:void()" title="Edit" onclick="data_edit('."'".$list_item->id_status_kepegawaian."'".')"><i class="fa fa-pencil"></i></a>';
			}	
			
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $list_item->no_surat;		
			$row[] = $staff;
			$row[] = '<b>'.$list_item->nama_jenis_usulan.'</b>';
			$row[] = '<script type="text/javascript">durasi("'.$list_item->id_status_kepegawaian.'","'.$list_item->history_date.'");</script><p id="durasi'.$list_item->id_status_kepegawaian.'"></p>';			
			$row[] = $list_item->keterangan;		
			$row[] = $aksi;		
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

	public function data_list_sdm_orkum()
	{		
		$is_admin = $this->cms_model->user_is_admin();
		$user_id = $this->session->userdata('user_id');
		$chkSearch = $this->input->post('chkSearch');
		
		$where = "id_posisi = 3 ";
		
		$datatable_name = $this->table_name_view;	
		$search_column = array('lower(no_surat)','lower(nama_jenis_usulan)');
		$search_order = array();
		$order_by = 'history_date asc';		
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];
		setlocale(LC_ALL, "IND");
		foreach ($list as $list_item) {
			$staff = "-";
			$aksi = "";
			
			$data_staff = $this->cms_model->query_get_by_criteria('v_status_kepegawaian_staff', array("id_status_kepegawaian" => $list_item->id_status_kepegawaian), 'nama ASC');	
			$array_s = array();
			foreach($data_staff as $ds){
				$array_s[] = '[<b>'.$ds->nama.'</b>]';
			}
			$staff = join(' dan ', array_filter(array_merge(array(join(', ', array_slice($array_s, 0, -1))), array_slice($array_s, -1)), 'strlen'));
			
			//add html for action
			if($is_admin){
				//punya akses administrator
				//$aksi .= '<a class="btn btn-xs btn-success" href="javascript:void()" title="Approve" onclick="data_approve('."'".$list_item->id_status_kepegawaian."','".$list_item->no_surat."'".')"><i class="fa fa-check"></i></a>';
				$aksi .= '<div class="btn-group">
							<a class="btn btn-xs btn-success dropdown-toggle" data-toggle="dropdown" href="#">
								Next
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu pull-right">
								<li><a href="javascript:void()" onclick="ubah_posisi('."'".$list_item->id_status_kepegawaian."','2','Proses SDM'".')">Proses SDM</a></li>
								<li><a href="javascript:void()" onclick="ubah_posisi('."'".$list_item->id_status_kepegawaian."','10','Final'".')">Final</a></li>
							</ul>
						</div>';
				$aksi .= '<a class="btn btn-xs btn-primary" href="javascript:void()" title="Edit Keterangan" onclick="data_edit_keterangan('."'".$list_item->id_history."'".')"><i class="fa fa-pencil-square-o"></i></a>';
				$aksi .= '<a class="btn btn-xs btn-warning" href="javascript:void()" title="Edit" onclick="data_edit('."'".$list_item->id_status_kepegawaian."'".')"><i class="fa fa-pencil"></i></a>';
			}
			
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $list_item->no_surat;		
			$row[] = $staff;
			$row[] = '<b>'.$list_item->nama_jenis_usulan.'</b>';
			$row[] = '<script type="text/javascript">durasi("'.$list_item->id_status_kepegawaian.'","'.$list_item->history_date.'");</script><p id="durasi'.$list_item->id_status_kepegawaian.'"></p>';			
			$row[] = $list_item->keterangan;		
			$row[] = $aksi;		
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

	public function data_list_arsip()
	{		
		$is_admin = $this->cms_model->user_is_admin();
		$user_id = $this->session->userdata('user_id');
		$chkSearch = $this->input->post('chkSearch');
		
		$where = "id_posisi = 4 ";
		
		$datatable_name = $this->table_name_view;	
		$search_column = array('lower(no_surat)','lower(nama_jenis_usulan)');
		$search_order = array();
		$order_by = 'history_date asc';		
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];
		setlocale(LC_ALL, "IND");
		foreach ($list as $list_item) {
			$staff = "-";
			$aksi = "";
			
			$data_staff = $this->cms_model->query_get_by_criteria('v_status_kepegawaian_staff', array("id_status_kepegawaian" => $list_item->id_status_kepegawaian), 'nama ASC');	
			$array_s = array();
			foreach($data_staff as $ds){
				$array_s[] = '[<b>'.$ds->nama.'</b>]';
			}
			$staff = join(' dan ', array_filter(array_merge(array(join(', ', array_slice($array_s, 0, -1))), array_slice($array_s, -1)), 'strlen'));
			
			//add html for action
			if($is_admin){
				//punya akses administrator
				//$aksi .= '<a class="btn btn-xs btn-success" href="javascript:void()" title="Approve" onclick="data_approve('."'".$list_item->id_status_kepegawaian."','".$list_item->no_surat."'".')"><i class="fa fa-check"></i></a>';
				$aksi .= '<div class="btn-group">
							<a class="btn btn-xs btn-success dropdown-toggle" data-toggle="dropdown" href="#">
								Next
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu pull-right">
								<li><a href="javascript:void()" onclick="ubah_posisi('."'".$list_item->id_status_kepegawaian."','2','Proses SDM'".')">Proses SDM</a></li>
								<li><a href="javascript:void()" onclick="ubah_posisi('."'".$list_item->id_status_kepegawaian."','10','Final'".')">Final</a></li>
							</ul>
						</div>';
				$aksi .= '<a class="btn btn-xs btn-primary" href="javascript:void()" title="Edit Keterangan" onclick="data_edit_keterangan('."'".$list_item->id_history."'".')"><i class="fa fa-pencil-square-o"></i></a>';
				$aksi .= '<a class="btn btn-xs btn-warning" href="javascript:void()" title="Edit" onclick="data_edit('."'".$list_item->id_status_kepegawaian."'".')"><i class="fa fa-pencil"></i></a>';
			}
			
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $list_item->no_surat;		
			$row[] = $staff;
			$row[] = '<b>'.$list_item->nama_jenis_usulan.'</b>';
			$row[] = '<script type="text/javascript">durasi("'.$list_item->id_status_kepegawaian.'","'.$list_item->history_date.'");</script><p id="durasi'.$list_item->id_status_kepegawaian.'"></p>';			
			$row[] = $list_item->keterangan;		
			$row[] = $aksi;		
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

	public function data_list_final()
	{		
		$is_admin = $this->cms_model->user_is_admin();
		$user_id = $this->session->userdata('user_id');
		$chkSearch = $this->input->post('chkSearch');
		
		$where = "id_posisi = 10 AND (history_date + INTERVAL '1 DAY') > NOW() ";
		
		$datatable_name = $this->table_name_view;	
		$search_column = array('lower(no_surat)','lower(nama_jenis_usulan)');
		$search_order = array();
		$order_by = 'history_date asc';		
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];
		setlocale(LC_ALL, "IND");
		foreach ($list as $list_item) {
			$staff = "-";
			$aksi = "";
			
			$data_staff = $this->cms_model->query_get_by_criteria('v_status_kepegawaian_staff', array("id_status_kepegawaian" => $list_item->id_status_kepegawaian), 'nama ASC');	
			$array_s = array();
			foreach($data_staff as $ds){
				$array_s[] = '[<b>'.$ds->nama.'</b>]';
			}
			$staff = join(' dan ', array_filter(array_merge(array(join(', ', array_slice($array_s, 0, -1))), array_slice($array_s, -1)), 'strlen'));
			
			//add html for action
			if($is_admin){
				//punya akses administrator
				$aksi .= '<a class="btn btn-xs btn-primary" href="javascript:void()" title="Edit Keterangan" onclick="data_edit_keterangan('."'".$list_item->id_history."'".')"><i class="fa fa-pencil-square-o"></i></a>';
			}
			
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $list_item->no_surat;		
			$row[] = $staff;
			$row[] = '<b>'.$list_item->nama_jenis_usulan.'</b>';
			$row[] = '<script type="text/javascript">durasi("'.$list_item->id_status_kepegawaian.'","'.$list_item->history_date.'");</script><p id="durasi'.$list_item->id_status_kepegawaian.'"></p>';			
			$row[] = $list_item->keterangan;		
			$row[] = $aksi;		
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

	public function data_list_modal()
	{		
		$is_admin = $this->cms_model->user_is_admin();
		$user_id = $this->session->userdata('user_id');
		$chkSearch = $this->input->post('chkSearch');
		
		$where = "id_posisi IN (1,2,3,4) OR (id_posisi = 10 AND (history_date + INTERVAL '1 DAY') > NOW()) ";
		
		$datatable_name = $this->table_name_view;	
		$search_column = array();
		$search_order = array();
		$order_by = 'history_date asc';		
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];
		setlocale(LC_ALL, "IND");
		foreach ($list as $list_item) {
			$staff = "-";
			$posisi = 'primary';
			
			if($list_item->id_posisi==2) {
				$posisi = 'warning';
			} else if($list_item->id_posisi==3) {
				$posisi = 'danger';
			} else if($list_item->id_posisi==4) {
				$posisi = 'default';
			} else if($list_item->id_posisi==10) {
				$posisi = 'success';
			}
			
			$data_staff = $this->cms_model->query_get_by_criteria('v_status_kepegawaian_staff', array("id_status_kepegawaian" => $list_item->id_status_kepegawaian), 'nama ASC');	
			$array_s = array();
			foreach($data_staff as $ds){
				$array_s[] = '[<b>'.$ds->nama.'</b>]';
			}
			$staff = join(' dan ', array_filter(array_merge(array(join(', ', array_slice($array_s, 0, -1))), array_slice($array_s, -1)), 'strlen'));
			
			$no++;
			$row = array();
			$row[] = $no;	
			$row[] = $staff;
			$row[] = '<b>'.$list_item->nama_jenis_usulan.'</b>';
			$row[] = '<span class="label label-'.$posisi.'">'.$list_item->nama_posisi.'</span>';
			$row[] = '<script type="text/javascript">durasi("'.$list_item->id_status_kepegawaian.'","'.$list_item->history_date.'");</script><p style="font-size:11pt;" id="durasi'.$list_item->id_status_kepegawaian.'"></p>';			
			$row[] = '<div style="font-size:11pt;">'.$list_item->keterangan.'</div>';
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

	public function data_list_all()
	{		
		$is_admin = $this->cms_model->user_is_admin();
		$user_id = $this->session->userdata('user_id');
		$chkSearch = $this->input->post('chkSearch');
		
		$where = "id_status_kepegawaian IS NOT NULL ";
		if(!$is_admin){
			if($where != ''){
				$where .= " AND '".$user_id."' = ANY(id_pengusul) ";  
			}else{
				$where .= "'".$user_id."' = ANY(id_pengusul) ";    
			}
		}
		
		if($chkSearch[0] != "")
		{			
			foreach($chkSearch as $chkSearch_item)						
			{
				if($chkSearch_item == "tahun"){
					if($where != ''){
						$where .= " AND date_part('year', submit_date) = ".$this->input->post('filter_tahun')." ";   
					}else{
						$where .= "date_part('year', submit_date) = ".$this->input->post('filter_tahun')." ";   
					}
				}	
				if($chkSearch_item == "jenis_usulan"){
					if($where != ''){
						$where .= " AND id_jenis_usulan = ".$this->input->post('filter_jenisusulan')." ";  
					}else{
						$where .= "id_jenis_usulan = ".$this->input->post('filter_jenisusulan')." ";   
					}
				}		
				if($chkSearch_item == "no_surat"){
					if($where != ''){
						$where .= " AND no_surat ~* '".strtolower($this->input->post('no_surat'))."' ";  
					}else{
						$where .= "no_surat ~* '".strtolower($this->input->post('no_surat'))."' ";   
					}
				}	
				if($chkSearch_item == "nama"){
					if($where != ''){
						$where .= " AND array_to_string(nama_pengusul, ', ') ~* '".strtolower($this->input->post('nama'))."' ";  
					}else{
						$where .= "array_to_string(nama_pengusul, ', ') ~* '".strtolower($this->input->post('nama'))."' ";   
					}
				}			
			}
		}
		
		$datatable_name = $this->table_name_view;	
		$search_column = array();
		$search_order = array();
		$order_by = 'history_date asc';		
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];
		setlocale(LC_ALL, "IND");
		foreach ($list as $list_item) {
			$staff = "-";
			$posisi = '';
			
			$data_staff = $this->cms_model->query_get_by_criteria('v_status_kepegawaian_staff', array("id_status_kepegawaian" => $list_item->id_status_kepegawaian), 'nama ASC');	
			$array_s = array();
			foreach($data_staff as $ds){
				$array_s[] = '[<b>'.$ds->nama.'</b>]';
			}
			$staff = join(' dan ', array_filter(array_merge(array(join(', ', array_slice($array_s, 0, -1))), array_slice($array_s, -1)), 'strlen'));
			
			$data_history = $this->cms_model->query_get_by_criteria('v_status_kepegawaian_history', array("id_status_kepegawaian" => $list_item->id_status_kepegawaian), 'history_date DESC');	
			$array_h = array();
			foreach($data_history as $dh){
				$array_h[] = '- <b>'.$dh->nama_posisi.'</b>, <i>'.strftime("%d %b %Y",strtotime($dh->history_date)).'</i>';
			}
			$posisi = join('<br>', array_filter(array_merge(array(join('<br>', array_slice($array_h, 0, -1))), array_slice($array_h, -1)), 'strlen'));
			
			$text_original = $posisi;
			$number_words = 3;
			if(substr_count($text_original, "<br>") > 3) {
				$text_to_add = "<!--more-->";
				$arr = explode("<br>",$text_original,$number_words+1);
				$last = array_pop($arr);
				$posisi = implode("<br>",$arr)." ".$text_to_add." ".$last;
			}
			
			$no++;
			$row = array();
			$row[] = $no;	
			$row[] = $list_item->no_surat;
			$row[] = $staff;
			$row[] = '<b>'.$list_item->nama_jenis_usulan.'</b>';
			$row[] = '<div class="m-more-less-content">'.$posisi.'</div>';
			$row[] = $list_item->keterangan;
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
	
	public function data_init()
	{
		$list_jenisusulan = array();
		$list = $this->cms_model->query_get_all('status_kepegawaian_jenisusulan');  
		
		foreach ($list as $list_item) {
			$list_jenisusulan[] = array("id_item" => $list_item->id_jenis_usulan, "nama_item" => $list_item->nama_jenis_usulan);
		}
		$data['filter_jenisusulan'] = $list_jenisusulan;	
		$data['filter_tahun'] = $this->cms_model->get_select_year(); 		
		echo json_encode($data);
	}
	
	public function data_save_validation()
	{								      				
		//set validation rules
		$this->form_validation->set_rules('id_jenis_usulan', 'Jenis Usulan', 'trim|required');
		//$this->form_validation->set_rules('id_user', 'Staff', 'trim|required');
		//$this->form_validation->set_rules('no_surat', 'No Surat', 'trim|required');	
		
		$status = FALSE;
		$message = 'Error saving data';
		
		if ($this->form_validation->run() == FALSE)
        {            
			//validation fails
			$status = FALSE;
			$message =  validation_errors();
        }
        else
        {    
			$save_method = $this->input->post('save_method');
			$id_status_kepegawaian = $this->input->post('id');
			$id_jenis_usulan = $this->input->post('id_jenis_usulan');
			$id_user_usulan = $this->input->post('id_user_usulan');
			$no_surat = $this->input->post('no_surat');
			$user_id = $this->session->userdata('user_id');
			
			$additional_data = array(
				'update_user' => $user_id,
				'id_jenis_usulan' => $id_jenis_usulan,
				'no_surat' => $no_surat
			);
			
			if($save_method == "update" ){
				$additional_data['update_date'] = gmdate("Y-m-d H:i:s", time()+60*60*7);
			}else{
				$additional_data['submit_user'] = $user_id;
			}
			
			try { 
				$where = "";
				if($save_method == "update" ){
					try {
						if($id_user_usulan!=''){
							$this->cms_model->update(array('id_status_kepegawaian' => $id_status_kepegawaian),$additional_data,$this->table_name);	
							try {
								$this->cms_model->delete('status_kepegawaian_staff', array('id_status_kepegawaian' => $id_status_kepegawaian));	
							} catch (Exception $e) {
								$e->getMessage();
							}	
							$status = TRUE;
							$message = "Status kepegawian berhasil diubah";
						} else {
							$status = FALSE;
							$message = "Pengusul harus diisi minimal 1";
						}
					} catch (Exception $e) {
						$status = FALSE;
						$message = "Status kepegawian gagal diubah";
					}
				} else {
					try {
						if($id_user_usulan!=''){
							$id_status_kepegawaian = $this->cms_model->save($additional_data, $this->table_name);
							try {
								$this->cms_model->delete('status_kepegawaian_staff', array('id_status_kepegawaian' => $id_status_kepegawaian));	
							} catch (Exception $e) {
								$e->getMessage();
							}	
						
							if($id_user_usulan!=''){
								$usulanhistory_data = array(
									'id_status_kepegawaian' => $id_status_kepegawaian,
									'submit_user' => $user_id,
									'update_user' => $user_id,
									'id_posisi' => 1
								);
								$id_usulan_history = $this->cms_model->save($usulanhistory_data, "status_kepegawaian_history");
							}
							
							$status = TRUE;
							$message = "Status kepegawian berhasil disimpan";
						} else {
							$status = FALSE;
							$message = "Pengusul harus diisi minimal 1";
						}
					} catch (Exception $e) {
						$status = FALSE;
						$message = "Status kepegawian gagal disimpan";
					}
				}
				
				if($id_user_usulan!=''){
					foreach($id_user_usulan as $iuu) {
						$user_data = array(
							'id_status_kepegawaian' => $id_status_kepegawaian,
							'user_id' => $iuu
						);
						$user_usulan = $this->cms_model->save($user_data, "status_kepegawaian_staff");
					}
				}
			} catch (Exception $e) {
				//alert the user then kill the process
				$status = FALSE;
				$message =  $e->getMessage();
			}	
			
        }//END FORM VALIDATION TRUE		
		echo json_encode(array("status" => $status, "message" => $message));			
	}
	
	public function data_save_keterangan()
	{								      						
		$status = FALSE;
		$message = 'Error saving data';
		
		$database_name = 'status_kepegawaian_history';
		$id_history = $this->input->post('id_history');
		$keterangan = $this->input->post('keterangan');
		$user_id = $this->session->userdata('user_id');
		
		$additional_data = array(
			'update_user' => $user_id,
			'update_date' => gmdate("Y-m-d H:i:s", time()+60*60*7),
			'keterangan' => $keterangan
		);
		
		try {
			$this->cms_model->update(array('id_history' => $id_history),$additional_data,$database_name);	
			$status = TRUE;
			$message = "Keterangan berhasil diubah";
		} catch (Exception $e) {
			$status = FALSE;
			$message = "Keterangan gagal diubah";
		}
		echo json_encode(array("status" => $status, "message" => $message));			
	}

	public function select_jenis_usulan()
	{	
		$data['filter_usulan'] = $this->cms_model->get_jenis_usulan();
		echo json_encode($data);
	}
	
	public function select_usulan_user()
	{	
		$list = $this->cms_model->query_get_all('v_status_kepegawaian_user');
		$array_p = array();
		foreach($list as $list_item)						
		{
			array_push($array_p,
				array(
					"id"=>$list_item->id,
					"nama"=>$list_item->nama
				)
			);
		}
		
		$data['filter_user'] = $array_p;
		echo json_encode($data);
	}
		
	public function ubah_posisi()
	{			
        $id_status_kepegawaian = $this->input->post('id');
        $id_posisi = $this->input->post('id_posisi');
		$user_id = $this->session->userdata('user_id');
		$status = FALSE;
		$message = "";
		
		$additional_data = array(
			'id_status_kepegawaian' => $id_status_kepegawaian,
			'submit_user' => $user_id,
			'update_user' => $user_id,
			'id_posisi' => $id_posisi
		);
		
		try {
			$data_kepegawaian = $this->cms_model->row_get_by_criteria($this->table_name, array("id_status_kepegawaian" => $id_status_kepegawaian));
			if(isset($data_kepegawaian)){
				try {
					$id_status_kepegawaian = $this->cms_model->save($additional_data, 'status_kepegawaian_history');
					$status = TRUE;
					$message = "Posisi berhasil diubah";
				} catch (Exception $e) {
					$status = FALSE;
					$message = "Posisi gagal diubah";
				}
			}
		} catch (Exception $e) {
			$status = FALSE;
			$message = "Posisi gagal diubah";
		}
		echo json_encode(array("status" => $status, "message" => $message));
	}	
			
	public function data_edit($id)
	{						
		$row = array();
		$array_u = array();
		$list_item = $this->cms_model->row_get_by_criteria($this->table_name, array("id_status_kepegawaian" => $id));
		if(isset($list_item)){
			$list_user = $this->cms_model->query_get_by_criteria('v_status_kepegawaian_staff', array("id_status_kepegawaian" => $list_item->id_status_kepegawaian), 'nama ASC');	
			foreach($list_user as $lu){
				array_push($array_u,
					array(
						"user_id"=>$lu->user_id,
						"nama"=>$lu->nama
					)
				);
			}
			$row['id_status_kepegawaian'] = $list_item->id_status_kepegawaian;
			$row['no_surat'] = $list_item->no_surat;
			$row['id_jenis_usulan'] = $list_item->id_jenis_usulan;
			$row['list_user'] = $array_u;
		}
		$data['list_status_kepegawaian']=$row;
		echo json_encode($data);
	}
	
	public function data_edit_keterangan($id_history)
	{						
		$data = array();
		$list_item = $this->cms_model->row_get_by_criteria("status_kepegawaian_history", array("id_history" => $id_history));
		if(isset($list_item)){
			$data['keterangan'] = $list_item->keterangan;
		}
		echo json_encode($data);
	}
		
	public function data_delete()
	{			
        $id_status_kepegawaian = $this->input->post('id_delete_data');
		try {
			//hapus status kepegawaian
			$this->cms_model->delete($this->table_name, array('id_status_kepegawaian' => $id_status_kepegawaian));		
			try {
				$this->cms_model->delete('status_kepegawaian_history', array('id_status_kepegawaian' => $id_status_kepegawaian));	
				$this->cms_model->delete('status_kepegawaian_staff', array('id_status_kepegawaian' => $id_status_kepegawaian));	
			} catch (Exception $e) {
				$e->getMessage();
			}	
			echo json_encode(array("status" => TRUE));
		} catch (Exception $e) {
			echo json_encode(array("status" => FALSE));
		}
	}	
	
	public function data_text_berjalan()
	{	
		$data = array();
		$data_text = $this->cms_model->query_get_by_criteria('text_berjalan', array(), 'id_text ASC');	
		$array_t = array();
		foreach($data_text as $dt){
			$array_t[] = $dt->deskripsi;
		}
		$data['text_berjalan'] = join(' || ', array_filter(array_merge(array(join(' || ', array_slice($array_t, 0, -1))), array_slice($array_t, -1)), 'strlen'));

		echo json_encode($data);
	}
	
	function _render_page($view, $data=null, $returnhtml=false)//I think this makes more sense
	{

		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);

		if ($returnhtml) return $view_html;//This will return html on 3rd argument being true
	}
}


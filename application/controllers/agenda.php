<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agenda extends CI_Controller {
	
	//Global variable
	var $table_name = 'agenda';
	var $table_name_view = 'v_agenda';
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
				
		$this->_render_page('agenda', $this->data);				
	}
	
	public function approved()
	{						
		// set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		$this->data['user_menu'] = $this->cms_model->get_user_menu($this->uri->rsegment(1));
		$this->data['user'] = $this->ion_auth->user()->row();
				
		$this->_render_page('agenda_approved', $this->data);				
	}
	
	public function rejected()
	{						
		// set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		$this->data['user_menu'] = $this->cms_model->get_user_menu($this->uri->rsegment(1));
		$this->data['user'] = $this->ion_auth->user()->row();
				
		$this->_render_page('agenda_rejected', $this->data);				
	}	

	public function data_list()
	{		
		$is_admin = $this->cms_model->user_is_admin();
		$user_id = $this->session->userdata('user_id');
		$chkSearch = $this->input->post('chkSearch');
		
		$where = "";
		if($is_admin){
			$where = "agenda_status = 0 ";
		} else {
			$where = "agenda_status = 0 AND user_id = '".$user_id."' ";
		}
		if($chkSearch[0] != "")
		{			
			foreach($chkSearch as $chkSearch_item)						
			{
				if($chkSearch_item == "tahun"){
					if($where != ''){
						$where .= " AND (".$this->input->post('filter_tahun')." BETWEEN date_part('year', first_date) AND date_part('year', last_date)) ";  
					}else{
						$where .= "(".$this->input->post('filter_tahun')." BETWEEN date_part('year', first_date) AND date_part('year', last_date)) ";    
					}
				}
				if($chkSearch_item == "bulan"){
					if($where != ''){
						$where .= " AND (".$this->input->post('filter_bulan')." BETWEEN date_part('month', first_date) AND date_part('month', last_date)) ";  
					}else{
						$where .= "(".$this->input->post('filter_bulan')." BETWEEN date_part('month', first_date) AND date_part('month', last_date)) ";   
					}
				}		
				if($chkSearch_item == "ruangan"){
					if($where != ''){
						$where .= " AND id_ruangan = ".$this->input->post('filter_ruangan')." ";  
					}else{
						$where .= "id_ruangan = ".$this->input->post('filter_ruangan')." ";   
					}
				}		
				if($chkSearch_item == "nama"){
					if($where != ''){
						$where .= " AND event_name ~* '".strtolower($this->input->post('nama'))."' ";  
					}else{
						$where .= "event_name ~* '".strtolower($this->input->post('nama'))."' ";   
					}
				}			
			}
		}

		$datatable_name = $this->table_name_view;	
		$search_column = array();
		$search_order = array('first_date' => 'desc');
		$order_by = '(CASE WHEN CURRENT_DATE BETWEEN first_date AND last_date THEN CURRENT_DATE WHEN CURRENT_DATE < first_date THEN first_date ELSE last_date END) < CURRENT_DATE,
					ABS((select CASE WHEN CURRENT_DATE BETWEEN first_date AND last_date THEN CURRENT_DATE WHEN CURRENT_DATE < first_date THEN first_date ELSE last_date END) - CURRENT_DATE),
					first_time, last_time';		
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];
		setlocale(LC_ALL, "IND");
		foreach ($list as $list_item) {
			$color = "";
			$date = "";
			$status = "";
			$keterangan = "";
			$aksi = "";
			
			if($list_item->first_date==$list_item->last_date){
				$date = strftime("%A<br> %d %b %Y",strtotime($list_item->first_date));
			} else {
				if(date_format(date_create($list_item->first_date),"m")==date_format(date_create($list_item->last_date),"m")){
					$date = strftime("%a",strtotime($list_item->first_date)).'-'.strftime("%a",strtotime($list_item->last_date)).'<br>'
					.strftime("%d",strtotime($list_item->first_date)).'-'.strftime("%d",strtotime($list_item->last_date)).' '
					.strftime("%b %Y",strtotime($list_item->first_date));
				} else {
					if(date_format(date_create($list_item->first_date),"y")==date_format(date_create($list_item->last_date),"y")){
						$date = strftime("%a",strtotime($list_item->first_date)).'-'.strftime("%a",strtotime($list_item->last_date)).', '
						.strftime("%d",strtotime($list_item->first_date)).'-'.strftime("%d",strtotime($list_item->last_date)).'<br>'
						.strftime("%b",strtotime($list_item->first_date)).'-'.strftime("%b",strtotime($list_item->last_date)).' '
						.strftime("%Y",strtotime($list_item->first_date));
					} else {
						$date = strftime("%a, %d %b %Y",strtotime($list_item->first_date)).' -<br>'.strftime("%a, %d %b %Y",strtotime($list_item->last_date));
					}
				}
			}
			
			$data_penanggungjawab = $this->cms_model->query_get_by_criteria('v_agenda_penanggungjawab', array("id_agenda" => $list_item->id_agenda), 'id_kategori ASC');	
			$array_p = array();
			foreach($data_penanggungjawab as $dp){
				$array_p[] = $dp->nama_user;
			}
			//$penanggungjawab = implode(', ', $array_p);
			$penanggungjawab = join(' dan ', array_filter(array_merge(array(join(', ', array_slice($array_p, 0, -1))), array_slice($array_p, -1)), 'strlen'));
			
			if($list_item->first_time==$list_item->last_time){
				$keterangan = '<b>'.$list_item->nama_ruangan.'</b><br>Jam '.strftime("%R",strtotime($list_item->first_time)).' WIB<br>'.$penanggungjawab;
			} else {
				$keterangan = '<b>'.$list_item->nama_ruangan.'</b><br>Jam '.strftime("%R",strtotime($list_item->first_time)).' - '.strftime("%R",strtotime($list_item->last_time)).' WIB<br>'.$penanggungjawab;
			}
			
			$datenow = gmdate("Y-m-d", time()+60*60*7);
			if($list_item->first_date<=$datenow&&$list_item->last_date>=$datenow){
				$color = '#32CD32';
			} else if ($list_item->first_date>=$datenow&&$list_item->last_date>=$datenow) {
				$color = '#FFA500';
			} else {
				$color = '#FF0000';
			}
			
			$btn_baca = '';
			if(is_null($list_item->read_date)){
				if($is_admin) {
					$btn_baca = '<a class="btn btn-xs btn-default" title="Belum dibaca" href="javascript:void()" onclick="data_read('."'".$list_item->id_agenda."'".')"><i class="fa fa-envelope-o"></i></a>';
				} else {
					$btn_baca = '<a class="btn btn-xs btn-default" title="Belum dibaca"><i class="fa fa-envelope"></i></a>';
				}
			} else {
				$btn_baca = '<a class="btn btn-xs btn-primary" title="Sudah dibaca"><i class="fa fa-envelope"></i></a>';
			}
			
			$btn_percakapan = '';
			if($list_item->jml_percakapan>0){
				$btn_percakapan = '<a class="btn btn-xs btn-success" title="'.$list_item->jml_percakapan.' percakapan" href="javascript:void()" onclick="data_chatmodal('."'".$list_item->id_agenda."'".')"><i class="fa fa fa-comments-o"></i></a>';
			} else {
				$btn_percakapan = '<a class="btn btn-xs btn-default" title="Belum ada percakapan" href="javascript:void()" onclick="data_chatmodal('."'".$list_item->id_agenda."'".')"><i class="fa fa fa-comment-o"></i></a>';
			}
			
			if($is_admin||$user_id==$list_item->user_id){
				$status = $btn_baca.$btn_percakapan;
			}
			
			//add html for action
			if($is_admin){
				//punya akses administrator
				$aksi .= '<a class="btn btn-xs btn-success" href="javascript:void()" title="Approve" onclick="data_approve('."'".$list_item->id_agenda."','".$list_item->event_name."'".')"><i class="fa fa-check"></i></a>';
				$aksi .= '<a class="btn btn-xs btn-primary" href="javascript:void()" title="Tolak" onclick="data_reject('."'".$list_item->id_agenda."','".$list_item->event_name."'".')"><i class="fa fa-ban"></i></a>';
				$aksi .= '<a class="btn btn-xs btn-warning" href="javascript:void()" title="Edit" onclick="data_edit('."'".$list_item->id_agenda."'".')"><i class="fa fa-pencil"></i></a>';
				$aksi .= '<a class="btn btn-xs btn-danger" href="javascript:void()" title="Hapus" onclick="data_delete('."'".$list_item->id_agenda."','".$list_item->event_name."'".')"><i class="fa fa-times"></i></a>';
			}else if($user_id==$list_item->user_id){
				$aksi .= '<a class="btn btn-xs btn-warning" href="javascript:void()" title="Edit" onclick="data_edit('."'".$list_item->id_agenda."'".')"><i class="fa fa-pencil"></i></a>';
				$aksi .= '<a class="btn btn-xs btn-danger" href="javascript:void()" title="Hapus" onclick="data_delete('."'".$list_item->id_agenda."','".$list_item->event_name."'".')"><i class="fa fa-times"></i></a>';
			}else{
				$aksi = '';
			}	
			
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $date;		
			$row[] = '<div style="color:'.$color.';"><b>'.$list_item->event_name.'</b></div>';
			$row[] = $keterangan;			
			$row[] = date_format(date_create($list_item->submit_date),"j M Y, \J\a\m G:i").'. <i>'.$list_item->pengirim.'</i>';			
			$row[] = $status;		
			$row[] = $aksi;		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->cms_model->count_all_where($datatable_name, $where),
						//"recordsFiltered" => $this->cms_model->count_filtered($this->table_name_view, $search_column, $search_order),
						"recordsFiltered" => $this->cms_model->count_filtered_where($this->table_name_view, $search_column, $search_order, $where, $order_by),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);		
	}

	public function data_list_approved()
	{		
		$is_admin = $this->cms_model->user_is_admin();
		$user_id = $this->session->userdata('user_id');
		$chkSearch = $this->input->post('chkSearch');
		
		$where = "";
		if($is_admin){
			$where = "agenda_status = 1 ";
		} else {
			$where = "agenda_status = 1 AND user_id = '".$user_id."' ";
		}
		if($chkSearch[0] != "")
		{			
			foreach($chkSearch as $chkSearch_item)						
			{
				if($chkSearch_item == "tahun"){
					if($where != ''){
						$where .= " AND (".$this->input->post('filter_tahun')." BETWEEN date_part('year', first_date) AND date_part('year', last_date)) ";  
					}else{
						$where .= "(".$this->input->post('filter_tahun')." BETWEEN date_part('year', first_date) AND date_part('year', last_date)) ";    
					}
				}
				if($chkSearch_item == "bulan"){
					if($where != ''){
						$where .= " AND (".$this->input->post('filter_bulan')." BETWEEN date_part('month', first_date) AND date_part('month', last_date)) ";  
					}else{
						$where .= "(".$this->input->post('filter_bulan')." BETWEEN date_part('month', first_date) AND date_part('month', last_date)) ";   
					}
				}		
				if($chkSearch_item == "ruangan"){
					if($where != ''){
						$where .= " AND id_ruangan = ".$this->input->post('filter_ruangan')." ";  
					}else{
						$where .= "id_ruangan = ".$this->input->post('filter_ruangan')." ";   
					}
				}		
				if($chkSearch_item == "nama"){
					if($where != ''){
						$where .= " AND event_name ~* '".strtolower($this->input->post('nama'))."' ";  
					}else{
						$where .= "event_name ~* '".strtolower($this->input->post('nama'))."' ";   
					}
				}			
			}
		}

		$datatable_name = $this->table_name_view;	
		$search_column = array();
		$search_order = array('first_date' => 'desc');
		$order_by = '(CASE WHEN CURRENT_DATE BETWEEN first_date AND last_date THEN CURRENT_DATE WHEN CURRENT_DATE < first_date THEN first_date ELSE last_date END) < CURRENT_DATE,
					ABS((select CASE WHEN CURRENT_DATE BETWEEN first_date AND last_date THEN CURRENT_DATE WHEN CURRENT_DATE < first_date THEN first_date ELSE last_date END) - CURRENT_DATE),
					first_time, last_time';		
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];
		setlocale(LC_ALL, "IND");
		foreach ($list as $list_item) {
			$color = "";
			$date = "";
			$keterangan = "";
			$aksi = "";
			
			if($list_item->first_date==$list_item->last_date){
				$date = strftime("%A<br> %d %b %Y",strtotime($list_item->first_date));
			} else {
				if(date_format(date_create($list_item->first_date),"m")==date_format(date_create($list_item->last_date),"m")){
					$date = strftime("%a",strtotime($list_item->first_date)).'-'.strftime("%a",strtotime($list_item->last_date)).'<br>'
					.strftime("%d",strtotime($list_item->first_date)).'-'.strftime("%d",strtotime($list_item->last_date)).' '
					.strftime("%b %Y",strtotime($list_item->first_date));
				} else {
					if(date_format(date_create($list_item->first_date),"y")==date_format(date_create($list_item->last_date),"y")){
						$date = strftime("%a",strtotime($list_item->first_date)).'-'.strftime("%a",strtotime($list_item->last_date)).', '
						.strftime("%d",strtotime($list_item->first_date)).'-'.strftime("%d",strtotime($list_item->last_date)).'<br>'
						.strftime("%b",strtotime($list_item->first_date)).'-'.strftime("%b",strtotime($list_item->last_date)).' '
						.strftime("%Y",strtotime($list_item->first_date));
					} else {
						$date = strftime("%a, %d %b %Y",strtotime($list_item->first_date)).' -<br>'.strftime("%a, %d %b %Y",strtotime($list_item->last_date));
					}
				}
			}
			
			$data_penanggungjawab = $this->cms_model->query_get_by_criteria('v_agenda_penanggungjawab', array("id_agenda" => $list_item->id_agenda), 'id_kategori ASC');	
			$array_p = array();
			foreach($data_penanggungjawab as $dp){
				$array_p[] = $dp->nama_user;
			}
			//$penanggungjawab = implode(', ', $array_p);
			$penanggungjawab = join(' dan ', array_filter(array_merge(array(join(', ', array_slice($array_p, 0, -1))), array_slice($array_p, -1)), 'strlen'));
			
			if($list_item->first_time==$list_item->last_time){
				$keterangan = '<b>'.$list_item->nama_ruangan.'</b><br>Jam '.strftime("%R",strtotime($list_item->first_time)).' WIB<br>'.$penanggungjawab;
			} else {
				$keterangan = '<b>'.$list_item->nama_ruangan.'</b><br>Jam '.strftime("%R",strtotime($list_item->first_time)).' - '.strftime("%R",strtotime($list_item->last_time)).' WIB<br>'.$penanggungjawab;
			}
			
			$datenow = gmdate("Y-m-d", time()+60*60*7);
			if($list_item->first_date<=$datenow&&$list_item->last_date>=$datenow){
				$color = '#32CD32';
			} else if ($list_item->first_date>=$datenow&&$list_item->last_date>=$datenow) {
				$color = '#FFA500';
			} else {
				$color = '#FF0000';
			}
			
			//add html for action
			if($is_admin&&$list_item->first_date>=$datenow){
				//punya akses administrator
				$aksi .= '<a class="btn btn-xs btn-warning" href="javascript:void()" title="Edit" onclick="data_edit('."'".$list_item->id_agenda."'".')"><i class="fa fa-pencil"></i></a>';
			}else{
				$aksi = '';
			}	
			
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $date;		
			$row[] = '<div style="color:'.$color.';"><b>'.$list_item->event_name.'</b></div>';
			$row[] = $keterangan;			
			$row[] = date_format(date_create($list_item->submit_date),"j M Y, \J\a\m G:i").'. <i>'.$list_item->pengirim.'</i>';				
			$row[] = date_format(date_create($list_item->approve_date),"j M Y, \J\a\m G:i").'. <i>'.$list_item->user_approve.'</i>';					
			$row[] = $aksi;		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->cms_model->count_all_where($datatable_name, $where),
						//"recordsFiltered" => $this->cms_model->count_filtered($this->table_name_view, $search_column, $search_order),
						"recordsFiltered" => $this->cms_model->count_filtered_where($this->table_name_view, $search_column, $search_order, $where, $order_by),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);		
	}

	public function data_list_rejected()
	{		
		$is_admin = $this->cms_model->user_is_admin();
		$user_id = $this->session->userdata('user_id');
		$chkSearch = $this->input->post('chkSearch');
		
		$where = "";
		if($is_admin){
			$where = "agenda_status = 2 ";
		} else {
			$where = "agenda_status = 2 AND user_id = '".$user_id."' ";
		}
		if($chkSearch[0] != "")
		{			
			foreach($chkSearch as $chkSearch_item)						
			{
				if($chkSearch_item == "tahun"){
					if($where != ''){
						$where .= " AND (".$this->input->post('filter_tahun')." BETWEEN date_part('year', first_date) AND date_part('year', last_date)) ";  
					}else{
						$where .= "(".$this->input->post('filter_tahun')." BETWEEN date_part('year', first_date) AND date_part('year', last_date)) ";    
					}
				}
				if($chkSearch_item == "bulan"){
					if($where != ''){
						$where .= " AND (".$this->input->post('filter_bulan')." BETWEEN date_part('month', first_date) AND date_part('month', last_date)) ";  
					}else{
						$where .= "(".$this->input->post('filter_bulan')." BETWEEN date_part('month', first_date) AND date_part('month', last_date)) ";   
					}
				}		
				if($chkSearch_item == "ruangan"){
					if($where != ''){
						$where .= " AND id_ruangan = ".$this->input->post('filter_ruangan')." ";  
					}else{
						$where .= "id_ruangan = ".$this->input->post('filter_ruangan')." ";   
					}
				}		
				if($chkSearch_item == "nama"){
					if($where != ''){
						$where .= " AND event_name ~* '".strtolower($this->input->post('nama'))."' ";  
					}else{
						$where .= "event_name ~* '".strtolower($this->input->post('nama'))."' ";   
					}
				}			
			}
		}

		$datatable_name = $this->table_name_view;	
		$search_column = array();
		$search_order = array('first_date' => 'desc');
		$order_by = '(CASE WHEN CURRENT_DATE BETWEEN first_date AND last_date THEN CURRENT_DATE WHEN CURRENT_DATE < first_date THEN first_date ELSE last_date END) < CURRENT_DATE,
					ABS((select CASE WHEN CURRENT_DATE BETWEEN first_date AND last_date THEN CURRENT_DATE WHEN CURRENT_DATE < first_date THEN first_date ELSE last_date END) - CURRENT_DATE),
					first_time, last_time';		
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];
		setlocale(LC_ALL, "IND");
		foreach ($list as $list_item) {
			$color = "";
			$date = "";
			$keterangan = "";
			
			if($list_item->first_date==$list_item->last_date){
				$date = strftime("%A<br> %d %b %Y",strtotime($list_item->first_date));
			} else {
				if(date_format(date_create($list_item->first_date),"m")==date_format(date_create($list_item->last_date),"m")){
					$date = strftime("%a",strtotime($list_item->first_date)).'-'.strftime("%a",strtotime($list_item->last_date)).'<br>'
					.strftime("%d",strtotime($list_item->first_date)).'-'.strftime("%d",strtotime($list_item->last_date)).' '
					.strftime("%b %Y",strtotime($list_item->first_date));
				} else {
					if(date_format(date_create($list_item->first_date),"y")==date_format(date_create($list_item->last_date),"y")){
						$date = strftime("%a",strtotime($list_item->first_date)).'-'.strftime("%a",strtotime($list_item->last_date)).', '
						.strftime("%d",strtotime($list_item->first_date)).'-'.strftime("%d",strtotime($list_item->last_date)).'<br>'
						.strftime("%b",strtotime($list_item->first_date)).'-'.strftime("%b",strtotime($list_item->last_date)).' '
						.strftime("%Y",strtotime($list_item->first_date));
					} else {
						$date = strftime("%a, %d %b %Y",strtotime($list_item->first_date)).' -<br>'.strftime("%a, %d %b %Y",strtotime($list_item->last_date));
					}
				}
			}
			
			$data_penanggungjawab = $this->cms_model->query_get_by_criteria('v_agenda_penanggungjawab', array("id_agenda" => $list_item->id_agenda), 'id_kategori ASC');	
			$array_p = array();
			foreach($data_penanggungjawab as $dp){
				$array_p[] = $dp->nama_user;
			}
			//$penanggungjawab = implode(', ', $array_p);
			$penanggungjawab = join(' dan ', array_filter(array_merge(array(join(', ', array_slice($array_p, 0, -1))), array_slice($array_p, -1)), 'strlen'));
			
			if($list_item->first_time==$list_item->last_time){
				$keterangan = '<b>'.$list_item->nama_ruangan.'</b><br>Jam '.strftime("%R",strtotime($list_item->first_time)).' WIB<br>'.$penanggungjawab;
			} else {
				$keterangan = '<b>'.$list_item->nama_ruangan.'</b><br>Jam '.strftime("%R",strtotime($list_item->first_time)).' - '.strftime("%R",strtotime($list_item->last_time)).' WIB<br>'.$penanggungjawab;
			}
			
			$datenow = gmdate("Y-m-d", time()+60*60*7);
			if($list_item->first_date<=$datenow&&$list_item->last_date>=$datenow){
				$color = '#32CD32';
			} else if ($list_item->first_date>=$datenow&&$list_item->last_date>=$datenow) {
				$color = '#FFA500';
			} else {
				$color = '#FF0000';
			}
			
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $date;		
			$row[] = '<div style="color:'.$color.';"><b>'.$list_item->event_name.'</b></div>';
			$row[] = $keterangan;			
			$row[] = date_format(date_create($list_item->submit_date),"j M Y, \J\a\m G:i").'. <i>'.$list_item->pengirim.'</i>';			
			$row[] = date_format(date_create($list_item->reject_date),"j M Y, \J\a\m G:i").'. <i>'.$list_item->user_reject.'</i>';	
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->cms_model->count_all_where($datatable_name, $where),
						//"recordsFiltered" => $this->cms_model->count_filtered($this->table_name_view, $search_column, $search_order),
						"recordsFiltered" => $this->cms_model->count_filtered_where($this->table_name_view, $search_column, $search_order, $where, $order_by),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);		
	}

	public function data_agenda_tabel()
	{		
		$is_admin = $this->cms_model->user_is_admin();
		$user_id = $this->session->userdata('user_id');
		
		$where = "agenda_status = 1 AND ('".date('Y-m')."' BETWEEN to_char(first_date, 'YYYY-MM') AND to_char(last_date, 'YYYY-MM')) "; 

		$datatable_name = $this->table_name_view;	
		$search_column = array();
		$search_order = array('first_date' => 'desc');
		$order_by = '(CASE WHEN CURRENT_DATE BETWEEN first_date AND last_date THEN CURRENT_DATE WHEN CURRENT_DATE < first_date THEN first_date ELSE last_date END) < CURRENT_DATE,
					ABS((select CASE WHEN CURRENT_DATE BETWEEN first_date AND last_date THEN CURRENT_DATE WHEN CURRENT_DATE < first_date THEN first_date ELSE last_date END) - CURRENT_DATE),
					first_time, last_time';	
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];
		setlocale(LC_ALL, "IND");
		foreach ($list as $list_item) {
			$color = "";
			$date = "";
			$keterangan = "";
			$today = "";
			
			if($list_item->first_date==$list_item->last_date){
				$date = strftime("%A<br> %d %b %Y",strtotime($list_item->first_date));
			} else {
				if(date_format(date_create($list_item->first_date),"m")==date_format(date_create($list_item->last_date),"m")){
					$date = strftime("%a",strtotime($list_item->first_date)).'-'.strftime("%a",strtotime($list_item->last_date)).'<br>'
					.strftime("%d",strtotime($list_item->first_date)).'-'.strftime("%d",strtotime($list_item->last_date)).' '
					.strftime("%b %Y",strtotime($list_item->first_date));
				} else {
					if(date_format(date_create($list_item->first_date),"y")==date_format(date_create($list_item->last_date),"y")){
						$date = strftime("%a",strtotime($list_item->first_date)).'-'.strftime("%a",strtotime($list_item->last_date)).', '
						.strftime("%d",strtotime($list_item->first_date)).'-'.strftime("%d",strtotime($list_item->last_date)).'<br>'
						.strftime("%b",strtotime($list_item->first_date)).'-'.strftime("%b",strtotime($list_item->last_date)).' '
						.strftime("%Y",strtotime($list_item->first_date));
					} else {
						$date = strftime("%a, %d %b %Y",strtotime($list_item->first_date)).' -<br>'.strftime("%a, %d %b %Y",strtotime($list_item->last_date));
					}
				}
			}
			
			$data_penanggungjawab = $this->cms_model->query_get_by_criteria('v_agenda_penanggungjawab', array("id_agenda" => $list_item->id_agenda), 'id_kategori ASC');	
			$array_p = array();
			foreach($data_penanggungjawab as $dp){
				$array_p[] = $dp->nama_user;
			}
			$penanggungjawab = join(' dan ', array_filter(array_merge(array(join(', ', array_slice($array_p, 0, -1))), array_slice($array_p, -1)), 'strlen'));
			
			if($list_item->first_time==$list_item->last_time){
				$keterangan = '<b>'.$list_item->nama_ruangan.'</b><br>Jam '.strftime("%R",strtotime($list_item->first_time)).' WIB<br>'.$penanggungjawab;
			} else {
				$keterangan = '<b>'.$list_item->nama_ruangan.'</b><br>Jam '.strftime("%R",strtotime($list_item->first_time)).' - '.strftime("%R",strtotime($list_item->last_time)).' WIB<br>'.$penanggungjawab;
			}
			
			$datenow = gmdate("Y-m-d", time()+60*60*7);
			if($list_item->first_date<=$datenow&&$list_item->last_date>=$datenow){
				$color = '#32CD32';
			} else if ($list_item->first_date>=$datenow&&$list_item->last_date>=$datenow) {
				$color = '#FFA500';
			} else {
				$color = '#FF0000';
			}
						
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $date;		
			$row[] = '<div style="color:'.$color.';"><b>'.$list_item->event_name.'</b></div>';
			$row[] = $keterangan;	
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->cms_model->count_all_where($datatable_name, $where),
						"recordsFiltered" => $this->cms_model->count_filtered_where($this->table_name_view, $search_column, $search_order, $where, $order_by),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);		
	}
	
	public function data_agenda_modal()
	{		
		$is_admin = $this->cms_model->user_is_admin();
		$user_id = $this->session->userdata('user_id');
		
		/*$where = " agenda_status = 1 AND ((CASE WHEN CURRENT_DATE BETWEEN first_date AND last_date THEN CURRENT_DATE
				WHEN CURRENT_DATE < first_date THEN first_date ELSE last_date END) BETWEEN (CURRENT_DATE - INTERVAL '1 DAY') AND (CURRENT_DATE + INTERVAL '3 DAY')
				OR EXTRACT(WEEK from (CASE WHEN CURRENT_DATE BETWEEN first_date AND last_date THEN CURRENT_DATE
				WHEN CURRENT_DATE < first_date THEN first_date ELSE last_date END)) = EXTRACT(WEEK from CURRENT_DATE)) ";*/
		
		$where = "agenda_status = 1 AND 
				((CASE WHEN CURRENT_DATE BETWEEN first_date AND last_date THEN CURRENT_DATE
				WHEN CURRENT_DATE < first_date THEN first_date ELSE last_date END) BETWEEN (date_trunc('week', CURRENT_DATE))::date AND (date_trunc('week', CURRENT_DATE) + '7 days'::interval)::date
				AND (EXTRACT(WEEK from (CASE WHEN CURRENT_DATE BETWEEN first_date AND last_date THEN CURRENT_DATE
				WHEN CURRENT_DATE < first_date THEN first_date ELSE last_date END)) = EXTRACT(WEEK from CURRENT_DATE)
				OR (CASE WHEN CURRENT_DATE BETWEEN first_date AND last_date THEN CURRENT_DATE
				WHEN CURRENT_DATE < first_date THEN first_date ELSE last_date END) BETWEEN (CURRENT_DATE - INTERVAL '1 DAY') AND (CURRENT_DATE + INTERVAL '3 DAY')))
				AND (CASE WHEN CURRENT_DATE BETWEEN first_date AND last_date THEN CURRENT_DATE
				WHEN CURRENT_DATE < first_date THEN first_date ELSE last_date END) >= (CURRENT_DATE - INTERVAL '1 DAY') ";

		$datatable_name = $this->table_name_view;	
		$search_column = array();
		$search_order = array('first_date' => 'desc');
		$order_by = '(CASE WHEN CURRENT_DATE BETWEEN first_date AND last_date THEN CURRENT_DATE WHEN CURRENT_DATE < first_date THEN first_date ELSE last_date END),
					first_time, last_time';	
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];
		setlocale(LC_ALL, "IND");
		foreach ($list as $list_item) {
			$cat_ruang = "";
			$color = "";
			$ket_color = "";
			$date = "";
			$keterangan = "";
			$today = "";
			
			if($list_item->first_date==$list_item->last_date){
				$date = strftime("%A<br> %d %B %Y",strtotime($list_item->first_date));
			} else {
				if(date_format(date_create($list_item->first_date),"m")==date_format(date_create($list_item->last_date),"m")){
					$date = strftime("%A",strtotime($list_item->first_date)).'-'.strftime("%A",strtotime($list_item->last_date)).'<br>'
					.strftime("%d",strtotime($list_item->first_date)).'-'.strftime("%d",strtotime($list_item->last_date)).' '
					.strftime("%B %Y",strtotime($list_item->first_date));
				} else {
					if(date_format(date_create($list_item->first_date),"y")==date_format(date_create($list_item->last_date),"y")){
						$date = strftime("%A",strtotime($list_item->first_date)).'-'.strftime("%A",strtotime($list_item->last_date)).', '
						.strftime("%d",strtotime($list_item->first_date)).'-'.strftime("%d",strtotime($list_item->last_date)).'<br>'
						.strftime("%B",strtotime($list_item->first_date)).'-'.strftime("%B",strtotime($list_item->last_date)).' '
						.strftime("%Y",strtotime($list_item->first_date));
					} else {
						$date = strftime("%A, %d %b %Y",strtotime($list_item->first_date)).' -<br>'.strftime("%A, %d %b %Y",strtotime($list_item->last_date));
					}
				}
			}
			
			$data_penanggungjawab = $this->cms_model->query_get_by_criteria('v_agenda_penanggungjawab', array("id_agenda" => $list_item->id_agenda), 'id_kategori ASC');	
			$array_p = array();
			foreach($data_penanggungjawab as $dp){
				$array_p[] = $dp->nama_user;
			}
			$penanggungjawab = join(' dan ', array_filter(array_merge(array(join(', ', array_slice($array_p, 0, -1))), array_slice($array_p, -1)), 'strlen'));
			
			$datenow = gmdate("Y-m-d", time()+60*60*7);
			if($list_item->first_date<=$datenow&&$list_item->last_date>=$datenow){
				$today = '<span class="label label-primary" id="today">hari ini</span>';
				$color = '#32CD32';
			} else if ($list_item->first_date>=$datenow&&$list_item->last_date>=$datenow) {
				$color = '#FFA500';
			} else {
				$color = '#A4A4A4';
				$ket_color = '#A4A4A4';
				$today = '<span class="label label-danger" id="yesterday">kemarin</span>';
			}
			
			if($list_item->id_klasifikasi_agenda==1){
				$cat_ruang = '<span class="label label-success" style="background-color:'.$ket_color.'">pustekbang</span>';
			} else {
				$cat_ruang = '<span class="label label-warning" style="background-color:'.$ket_color.'">luar</span>';
			}
			
			if($list_item->first_time==$list_item->last_time){
				$keterangan = '<b>'.$list_item->nama_ruangan.'</b> '.$cat_ruang.'<br>Jam '.strftime("%R",strtotime($list_item->first_time)).' WIB<br>'.$penanggungjawab;
			} else {
				$keterangan = '<b>'.$list_item->nama_ruangan.'</b> '.$cat_ruang.'<br>Jam '.strftime("%R",strtotime($list_item->first_time)).' - '.strftime("%R",strtotime($list_item->last_time)).' WIB<br>'.$penanggungjawab;
			}
						
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = '<div style="color:'.$color.';"><b>'.$date.'</b></div>';		
			$row[] = '<div style="color:'.$color.';"><b>'.$list_item->event_name.' '.$today.'</b></div>';
			$row[] = '<div style="color:'.$ket_color.'; font-size:11pt;">'.$keterangan.'</div>';	
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->cms_model->count_all_where($datatable_name, $where),
						"recordsFiltered" => $this->cms_model->count_filtered_where($this->table_name_view, $search_column, $search_order, $where, $order_by),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);		
	}
	
	public function data_json()
	{
		$datatable_name = "v_agenda";	
		$search_column = '';
		$search_order = array('event_name' => 'asc');
		$order_by = 'first_time asc';			
		$where = "agenda_status = 1 AND ('".$this->input->post('year')."' BETWEEN to_char(first_date, 'YYYY') AND to_char(last_date, 'YYYY'))";
		$keterangan = "";
		$start = "";
		$end = "";
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		
		$data['event'] = array();
		foreach ($list as $list_item) {
			setlocale(LC_ALL, "IND");
			$data_penanggungjawab = $this->cms_model->query_get_by_criteria('v_agenda_penanggungjawab', array("id_agenda" => $list_item->id_agenda), 'id_kategori ASC');	
			$array_p = array();
			foreach($data_penanggungjawab as $dp){
				$array_p[] = $dp->nama_user;
			}
			$penanggungjawab = join(' dan ', array_filter(array_merge(array(join(', ', array_slice($array_p, 0, -1))), array_slice($array_p, -1)), 'strlen'));
			
			if($list_item->first_time==$list_item->last_time){
				$keterangan = '<b>'.$list_item->nama_ruangan.'</b><br>Jam '.strftime("%R",strtotime($list_item->first_time)).' WIB<br>'.$penanggungjawab;
			} else {
				$keterangan = '<b>'.$list_item->nama_ruangan.'</b><br>Jam '.strftime("%R",strtotime($list_item->first_time)).' - '.strftime("%R",strtotime($list_item->last_time)).' WIB<br>'.$penanggungjawab;
			}
			
			$start = date("Y", strtotime($list_item->first_date))==$this->input->post('year')?$list_item->first_date:$this->input->post('year').'-01-01';
			$end = date("Y", strtotime($list_item->last_date))==$this->input->post('year')?$list_item->last_date:$this->input->post('year').'-12-31';
			
			$row = array();
			$row['id'] = $list_item->id_agenda;
			$row['title'] = $list_item->event_name;
			$row['tip'] = 'Aziz';
			$row['start'] = $start.' '.$list_item->first_time;																							
			$row['end'] = $end.' '.$list_item->last_time;
			$row['color'] = $list_item->id_klasifikasi_agenda==1?'#378006':'#ff6621';			
			$row['keterangan'] = $keterangan;			
						
			array_push($data['event'],$row);
			
		}
		echo json_encode($data);
	}
	
	public function data_init()
	{
		$list_ruangan = array();
		$list = $this->cms_model->query_get_all('agenda_ruangan');  
		
		foreach ($list as $list_item) {
			$list_ruangan[] = array("id_item" => $list_item->id_ruangan, "nama_item" => $list_item->nama_ruangan);
		}
		$data['filter_ruangan'] = $list_ruangan;	
		$data['filter_tahun'] = $this->cms_model->get_select_year(); 							
		$data['filter_bulan'] = $this->cms_model->get_list_bulan();  		
		echo json_encode($data);
	}
	
	public function data_save_validation()
	{								      				
		//set validation rules
		$this->form_validation->set_rules('jenis_kegiatan', 'Jenis Kegiatan', 'trim|required');
		$this->form_validation->set_rules('id_ruangan', 'Nama Ruangan/ Lokasi', 'trim|required');
		$this->form_validation->set_rules('acara', 'Nama Acara', 'trim|required');	
		$this->form_validation->set_rules('first_date', 'Tanggal Awal', 'trim|required');	
		$this->form_validation->set_rules('last_date', 'Tanggal Akhir', 'trim|required');	
		
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
			$jenis_kegiatan = $this->input->post('jenis_kegiatan');
			$agenda_id = $this->input->post('id');
			$id_ruangan = $this->input->post('id_ruangan');
			$id_penanggungjawab = $this->input->post('id_penanggungjawab');
			$acara = $this->input->post('acara');
			$first_date = $this->input->post('first_date');
			$first_date = new DateTime($first_date);
			$last_date = $this->input->post('last_date');
			$last_date = new DateTime($last_date);
			$user_id = $this->session->userdata('user_id');
			
			$additional_data = array(
				'update_user' => $user_id,
				'id_klasifikasi_agenda' => $jenis_kegiatan,
				'event_name' => $acara,
				'first_date' => $first_date->format('Y-m-d'),
				'last_date' => $last_date->format('Y-m-d'),
				'first_time' => $first_date->format('H:i:s'),
				'last_time' => $last_date->format('H:i:s')
			);
					
			if($jenis_kegiatan==1){
				$additional_data['id_ruangan'] = $this->input->post('id_ruangan');
			} else {
				$additional_data['lokasi_acara'] = $this->input->post('id_ruangan');
			}
			
			if($save_method == "update" ){
				$additional_data['update_date'] = gmdate("Y-m-d H:i:s", time()+60*60*7);
			}else{
				$additional_data['user_id'] = $user_id;
			}
			
			try { 
				$where = "";
				if($jenis_kegiatan==1){
					if($save_method == "update" ){
						$where = "agenda_status = 1 AND id_ruangan = ".$id_ruangan." AND id_agenda<>".$agenda_id."
							AND (('".$first_date->format('Y-m-d H:i:s')."' BETWEEN (first_date + first_time)::timestamp AND (last_date + last_time)::timestamp) 
							OR ('".$last_date->format('Y-m-d H:i:s')."' BETWEEN (first_date + first_time)::timestamp AND (last_date + last_time)::timestamp)) ";
					}else{
						$where = "agenda_status = 1 AND id_ruangan = ".$id_ruangan." 
							AND (('".$first_date->format('Y-m-d H:i:s')."' BETWEEN (first_date + first_time)::timestamp AND (last_date + last_time)::timestamp) 
							OR ('".$last_date->format('Y-m-d H:i:s')."' BETWEEN (first_date + first_time)::timestamp AND (last_date + last_time)::timestamp)) ";
					}
					
					$count_agenda =  $this->cms_model->count_all_where($this->table_name, $where);
					if($count_agenda>0){
						$status = FALSE;
						$message = "Ruangan sudah terjadwal";
					} else {
						if($save_method == "update" ){
							try {
								$this->cms_model->update(array('id_agenda' => $agenda_id),$additional_data,$this->table_name);	
								try {
									$this->cms_model->delete('agenda_penanggungjawab', array('id_agenda' => $agenda_id));	
								} catch (Exception $e) {
									$e->getMessage();
								}	
								$status = TRUE;
								$message = "Agenda berhasil diubah";
							} catch (Exception $e) {
								$status = FALSE;
								$message = "Agenda gagal diubah";
							}
						} else {
							try {
								$agenda_id = $this->cms_model->save($additional_data, $this->table_name);
								try {
									$this->cms_model->delete('agenda_penanggungjawab', array('id_agenda' => $agenda_id));	
								} catch (Exception $e) {
									$e->getMessage();
								}	
								$status = TRUE;
								$message = "Agenda berhasil disimpan";
							} catch (Exception $e) {
								$status = FALSE;
								$message = "Agenda gagal disimpan";
							}
						}
						
						if($id_penanggungjawab!=''){
							foreach($id_penanggungjawab as $ip) {
								$parts  = explode('.', $ip);
								$user_data = array();
								if(count($parts)==3){
									$user_data = array(
									'id_agenda' => $agenda_id,
									'id_kategori' => $parts[0],
									'id_user' => $parts[1],
									'id_group' => $parts[2]
									);
								} else {
									$user_data = array(
									'id_agenda' => $agenda_id,
									'id_kategori' => 3,
									'nama_user' => $ip
									);
								}
								$user_penanggungjawab = $this->cms_model->save($user_data, "agenda_penanggungjawab");
							}
						}
					} 
				} else {
					if($save_method == "update" ){
						try {
							$this->cms_model->update(array('id_agenda' => $agenda_id),$additional_data,$this->table_name);	
							try {
								$this->cms_model->delete('agenda_penanggungjawab', array('id_agenda' => $agenda_id));	
							} catch (Exception $e) {
								$e->getMessage();
							}	
							$status = TRUE;
							$message = "Agenda berhasil diubah";
						} catch (Exception $e) {
							$status = FALSE;
							$message = "Agenda gagal diubah";
						}
					} else {
						try {
							$agenda_id = $this->cms_model->save($additional_data, $this->table_name);
							try {
								$this->cms_model->delete('agenda_penanggungjawab', array('id_agenda' => $agenda_id));	
							} catch (Exception $e) {
								$e->getMessage();
							}	
							$status = TRUE;
							$message = "Agenda berhasil disimpan";
						} catch (Exception $e) {
							$status = FALSE;
							$message = "Agenda gagal disimpan";
						}
					}
					
					if($id_penanggungjawab!=''){
						foreach($id_penanggungjawab as $ip) {
							$parts  = explode('.', $ip);
							$user_data = array();
							if(count($parts)==3){
								$user_data = array(
								'id_agenda' => $agenda_id,
								'id_kategori' => $parts[0],
								'id_user' => $parts[1],
								'id_group' => $parts[2]
								);
							} else {
								$user_data = array(
								'id_agenda' => $agenda_id,
								'id_kategori' => 3,
								'nama_user' => $ip
								);
							}
							$user_penanggungjawab = $this->cms_model->save($user_data, "agenda_penanggungjawab");
						}
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
	
	public function data_save_validation2()
	{								      				
		//set validation rules
		$this->form_validation->set_rules('jenis_kegiatan', 'Jenis Kegiatan', 'trim|required');
		$this->form_validation->set_rules('id_ruangan', 'Nama Ruangan/ Lokasi', 'trim|required');
		$this->form_validation->set_rules('acara', 'Nama Acara', 'trim|required');	
		$this->form_validation->set_rules('first_date', 'Tanggal Awal', 'trim|required');	
		$this->form_validation->set_rules('last_date', 'Tanggal Akhir', 'trim|required');	
		
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
			$jenis_kegiatan = $this->input->post('jenis_kegiatan');
			$id_ruangan = $this->input->post('id_ruangan');
			$id_penanggungjawab = $this->input->post('id_penanggungjawab');
			$acara = $this->input->post('acara');
			$first_date = $this->input->post('first_date');
			$first_date = new DateTime($first_date);
			$last_date = $this->input->post('last_date');
			$last_date = new DateTime($last_date);
			$user_id = $this->session->userdata('user_id');
			
			if($save_method == "update" ){
				//simpan db	
				$additional_data = array(
					'user_id' => $user_id,
					'update_user' => $user_id,
					'id_klasifikasi_agenda' => $this->input->post('jenis_kegiatan'),
					'event_name' => $this->input->post('acara'),
					'first_date' => $first_date->format('Y-m-d'),
					'last_date' => $last_date->format('Y-m-d'),
					'first_time' => $first_date->format('H:i:s'),
					'last_time' => $last_date->format('H:i:s')
					);
					
				$status = FALSE;
				$message = "Agenda gagal diubah";
			}else{
				$additional_data = array(
					'user_id' => $user_id,
					'update_user' => $user_id,
					'id_klasifikasi_agenda' => $this->input->post('jenis_kegiatan'),
					'event_name' => $this->input->post('acara'),
					'first_date' => $first_date->format('Y-m-d'),
					'last_date' => $last_date->format('Y-m-d'),
					'first_time' => $first_date->format('H:i:s'),
					'last_time' => $last_date->format('H:i:s')
					);
					
				if($jenis_kegiatan==1){
					$additional_data['id_ruangan'] = $this->input->post('id_ruangan');
				} else {
					$additional_data['lokasi_acara'] = $this->input->post('id_ruangan');
				}
				
				try { 
					$where = "";
					if($jenis_kegiatan==1){
						$where = "agenda_status = 1 AND id_ruangan = ".$id_ruangan." 
								AND (('".$first_date->format('Y-m-d H:i:s')."' BETWEEN (first_date + first_time)::timestamp AND (last_date + last_time)::timestamp) 
								OR ('".$last_date->format('Y-m-d H:i:s')."' BETWEEN (first_date + first_time)::timestamp AND (last_date + last_time)::timestamp)) ";
						
						$count_agenda =  $this->cms_model->count_all_where($this->table_name, $where);
						if($count_agenda>0){
							$status = FALSE;
							$message = "Ruangan sudah terjadwal";
						} else {
							try {
								$agenda_id = $this->cms_model->save($additional_data, $this->table_name);
								if($id_penanggungjawab!=''){
									foreach($id_penanggungjawab as $ip) {
										$parts  = explode('.', $ip);
										$user_data = array();
										if(count($parts)==3){
											$user_data = array(
											'id_agenda' => $agenda_id,
											'id_kategori' => $parts[0],
											'id_user' => $parts[1],
											'id_group' => $parts[2]
											);
										} else {
											$user_data = array(
											'id_agenda' => $agenda_id,
											'id_kategori' => 3,
											'nama_user' => $ip
											);
										}
										//var_dump(count($parts));die();
										$user_penanggungjawab = $this->cms_model->save($user_data, "agenda_penanggungjawab");
									}
								}
								$status = TRUE;
								$message = "Agenda berhasil disimpan";
							} catch (Exception $e) {
								$status = FALSE;
								$message = "Agenda gagal disimpan";
							}
						}
					} else {
						try {
							$agenda_id = $this->cms_model->save($additional_data, $this->table_name);
							if($id_penanggungjawab!=''){
								foreach($id_penanggungjawab as $ip) {
									$parts  = explode('.', $ip);
									$user_data = array();
									if(count($parts)==3){
										$user_data = array(
										'id_agenda' => $agenda_id,
										'id_kategori' => $parts[0],
										'id_user' => $parts[1],
										'id_group' => $parts[2]
										);
									} else {
										$user_data = array(
										'id_agenda' => $agenda_id,
										'id_kategori' => 3,
										'nama_user' => $ip
										);
									}
									//var_dump(count($parts));die();
									$user_penanggungjawab = $this->cms_model->save($user_data, "agenda_penanggungjawab");
								}
							}
							$status = TRUE;
							$message = "Agenda berhasil disimpan";
						} catch (Exception $e) {
							$status = FALSE;
							$message = "Agenda gagal disimpan";
						}
					}
				} catch (Exception $e) {
					//alert the user then kill the process
					$status = FALSE;
					$message =  $e->getMessage();
				}	
			}
			
        }//END FORM VALIDATION TRUE		
		echo json_encode(array("status" => $status, "message" => $message));			
	}
	
	public function select_agenda_ruangan()
	{	
		$data['filter_ruangan'] = $this->cms_model->get_agenda_ruangan();
		echo json_encode($data);
	}
	
	public function select_agenda_user()
	{	
		$list = $this->cms_model->query_get_all('v_agenda_user');
		$data_user['Struktural'] = array();
		$data_user['Staff'] = array();
		foreach($list as $list_item)						
		{
			array_push($data_user[$list_item->nama_kategori],
				array(
					"kategori_id"=>$list_item->kategori_id,
					"id"=>$list_item->id,
					"nama"=>$list_item->nama,
					"group_id"=>$list_item->group_id,
					"nama_group"=>$list_item->nama_group
				)
			);
		}
		
		$data['filter_agenda_user'] = $data_user;
		echo json_encode($data);
	}
		
	public function data_approve()
	{			
        $agenda_id = $this->input->post('id_approve_data');
		$user_id = $this->session->userdata('user_id');
		$status = FALSE;
		$message = "";
		
		try {
			$data_agenda = $this->cms_model->row_get_by_criteria($this->table_name, array("id_agenda" => $agenda_id));
			if(isset($data_agenda)){
				$where = "";
				if($data_agenda->id_klasifikasi_agenda==1){
					$where = "agenda_status = 1 AND id_ruangan = ".$data_agenda->id_ruangan." 
							AND (('".$data_agenda->first_date." ".$data_agenda->first_time."' BETWEEN (first_date + first_time)::timestamp AND (last_date + last_time)::timestamp) 
							OR ('".$data_agenda->last_date." ".$data_agenda->last_time."' BETWEEN (first_date + first_time)::timestamp AND (last_date + last_time)::timestamp)) ";
					
					$count_agenda =  $this->cms_model->count_all_where($this->table_name, $where);
					if($count_agenda>0){
						$status = FALSE;
						$message = "Ruangan sudah terjadwal";
					} else {
						try {
							$this->cms_model->update(array('id_agenda' => $agenda_id),array('agenda_status' => 1,'approve_user' => $user_id,'approve_date' => gmdate("Y-m-d H:i:s", time()+60*60*7)),$this->table_name);	
							$status = TRUE;
							$message = "Agenda berhasil disetujui";
						} catch (Exception $e) {
							$status = FALSE;
							$message = "Agenda gagal disetujui";
						}
					}
				} else {
					try {
						$this->cms_model->update(array('id_agenda' => $agenda_id),array('agenda_status' => 1,'approve_user' => $user_id,'approve_date' => gmdate("Y-m-d H:i:s", time()+60*60*7)),$this->table_name);	
						$status = TRUE;
						$message = "Agenda berhasil disetujui";
					} catch (Exception $e) {
						$status = FALSE;
						$message = "Agenda gagal disetujui";
					}
				}
				
			}
		} catch (Exception $e) {
			$status = FALSE;
			$message = "Agenda gagal disetujui";
		}
		echo json_encode(array("status" => $status, "message" => $message));
	}	
		
	public function data_reject()
	{			
        $agenda_id = $this->input->post('id_reject_data');
		$user_id = $this->session->userdata('user_id');
		$status = FALSE;
		$message = "";
		
		try {
			$this->cms_model->update(array('id_agenda' => $agenda_id),array('agenda_status' => 2,'reject_user' => $user_id,'reject_date' => gmdate("Y-m-d H:i:s", time()+60*60*7)),$this->table_name);	
			$status = TRUE;
			$message = "Agenda berhasil ditolak";
		} catch (Exception $e) {
			$status = FALSE;
			$message = "Agenda gagal ditolak";
		}	
		echo json_encode(array("status" => $status, "message" => $message));
	}	
		
	public function data_read()
	{			
        $agenda_id = $this->input->post('id');
		$user_id = $this->session->userdata('user_id');
		$status = FALSE;
		$message = "";
		
		try {
			$this->cms_model->update(array('id_agenda' => $agenda_id),array('read_user' => $user_id,'read_date' => gmdate("Y-m-d H:i:s", time()+60*60*7)),$this->table_name);	
			$status = TRUE;
			$message = "Agenda berhasil dibaca";
		} catch (Exception $e) {
			$status = FALSE;
			$message = "Agenda gagal dibaca";
		}	
		echo json_encode(array("status" => $status, "message" => $message));
	}	
	
	public function data_edit($id)
	{						
		$row = array();
		$array_p = array();
		$list_item = $this->cms_model->row_get_by_criteria($this->table_name, array("id_agenda" => $id));
		if(isset($list_item)){
			$list_penanggungjawab = $this->cms_model->query_get_by_criteria('agenda_penanggungjawab', array("id_agenda" => $list_item->id_agenda), 'id_kategori ASC');	
			foreach($list_penanggungjawab as $lp){
				array_push($array_p,
					array(
						"kategori_id"=>$lp->id_kategori,
						"id"=>$lp->id_user,
						"nama"=>$lp->nama_user,
						"group_id"=>$lp->id_group
					)
				);
			}
			$row['id_klasifikasi_agenda'] = $list_item->id_klasifikasi_agenda;
			$row['id'] = $list_item->id_agenda;
			$row['acara'] = $list_item->event_name;
			$row['id_ruangan'] = $list_item->id_klasifikasi_agenda==1?$list_item->id_ruangan:$list_item->lokasi_acara;
			$row['first_date'] = $list_item->first_date.' '.$list_item->first_time;																							
			$row['last_date'] = $list_item->last_date.' '.$list_item->last_time;
			$row['list_penanggungjawab'] = $array_p;
		}
		$data['list_agenda']=$row;
		echo json_encode($data);
	}
		
	public function data_delete()
	{			
        $agenda_id = $this->input->post('id_delete_data');
		try {
			//hapus agenda
			$this->cms_model->delete($this->table_name, array('id_agenda' => $agenda_id));		
			try {
				$this->cms_model->delete('agenda_penanggungjawab', array('id_agenda' => $agenda_id));	
				$this->cms_model->delete('agenda_percakapan', array('id_agenda' => $agenda_id));	
			} catch (Exception $e) {
				$e->getMessage();
			}	
			echo json_encode(array("status" => TRUE));
		} catch (Exception $e) {
			echo json_encode(array("status" => FALSE));
		}
	}	
	
	public function data_chatmodal()
	{			
        $agenda_id = $this->input->post('id');
		$user_id = $this->session->userdata('user_id');
		$array_c = array();
		$list_chat = $this->cms_model->query_get_by_criteria('agenda_percakapan', array("id_agenda" => $agenda_id), 'submit_date ASC');	
		foreach($list_chat as $lc){
			array_push($array_c,
				array(
					"id_percakapan"=>$lc->id_percakapan,
					"id_agenda"=>$lc->id_agenda,
					"id_user"=>$lc->id_user,
					"submit_date"=>date_format(date_create($lc->submit_date),"j M Y, \J\a\m G:i"),
					"message"=>$lc->message,
					"status"=>($lc->id_user==$user_id?1:0)
				)
			);
		}
		$data['list_chat']=$array_c;
		echo json_encode($data);
	}
	
	public function data_sendchat()
	{			
        $agenda_id = $this->input->post('id_agenda');
		$user_id = $this->session->userdata('user_id');
		$array_c = array();
		
		$additional_data = array(
			'id_agenda' => $agenda_id,
			'id_user' => $user_id,
			'message' => $this->input->post('chatmessage')
		);
		
		try {
			$percakapan_id = $this->cms_model->save($additional_data, 'agenda_percakapan');
			$list_chat = $this->cms_model->row_get_by_criteria('agenda_percakapan', array("id_percakapan" => $percakapan_id));
			
			$array_c['id_percakapan'] = $list_chat->id_percakapan;
			$array_c['id_agenda'] = $list_chat->id_agenda;
			$array_c['id_user'] = $list_chat->id_user;
			$array_c['submit_date'] = date_format(date_create($list_chat->submit_date),"j M Y, \J\a\m G:i");
			$array_c['message'] = $list_chat->message;
			$array_c['status'] = ($list_chat->id_user==$user_id?1:0);
		} catch (Exception $e) {
			$e->getMessage();
		}	
		$data['list_chat']=$array_c;
		echo json_encode($data);
	}
	
	function _render_page($view, $data=null, $returnhtml=false)//I think this makes more sense
	{

		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);

		if ($returnhtml) return $view_html;//This will return html on 3rd argument being true
	}
}


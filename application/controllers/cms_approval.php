<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cms_approval extends CI_Controller {
	
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
				
		$this->_render_page('cms/cms_approval', $this->data);				
	}		
	
	public function data_init()
	{
		$data['filter_approval'] = $this->cms_model->get_approval_type();  		
		echo json_encode($data);
	}
	
	public function data_list()
	{						
		$datatable_name = $this->table_name_view;
		$search_column = "";
		$search_order = "";
		$where =  array('approval_type_id' => $_POST['approval_type_id']);
		$order_by = '';				
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);		
		
		$data = array();
		$no = 0;//$_POST['start'];		
		$nama_temp = "";
		$btn = "info";
		$temp_data = array();	
		
		foreach ($list as $list_item) {
			//pengirim
			$nama_pengirim = "";
			if(! is_null($list_item->nama_posisi_out)){
				$nama_pengirim = $list_item->nama_posisi_out;	//poisisi
			}else if(! is_null($list_item->nama_struktural_out)){
				$nama_pengirim = $list_item->nama_struktural_out;	//struktural
			}else if(! is_null($list_item->nama_fungsional_out)){
				$nama_pengirim = $list_item->nama_fungsional_out;	//fungsional
			}else{
				$nama_pengirim = $list_item->nama_group_out;	//user groups
			}			
			
			//penerima
			$nama_penerima = "";
			if(! is_null($list_item->nama_posisi_in)){
				$nama_penerima = $list_item->nama_posisi_in;
			}else if(! is_null($list_item->nama_struktural_in)){
				$nama_penerima = $list_item->nama_struktural_in;
			}else if(! is_null($list_item->nama_fungsional_in)){
				$nama_penerima = $list_item->nama_fungsional_in;
			}else{
				$nama_penerima = $list_item->nama_group_in;
			}							
			
			//wait_before
			$wait_before = "";
			if(! is_null($list_item->wait_before)){
				if(strval($list_item->wait_before) == 0){
					$wait_before = "Tingkat dibawah";
				}else if(strval($list_item->wait_before) > 0){
					$wait_before = $list_item->wait_before." Tingkat dibawah";
				}
			}						
			
			if($nama_pengirim != $nama_temp){
				$nama_temp = $nama_pengirim;
				
				if($no != 1){
					$temp_data_len = count($temp_data);			
					foreach($temp_data as $temp_data_item){
						$rowin = array();
						$rowin[] = $temp_data_item[0];	//no
						$rowin[] = $temp_data_item[1];	//pengirim
						$rowin[] = $temp_data_item[2]; 	//penerima
						$rowin[] = $temp_data_item[3];	//wait before
						$level = $temp_data_item[4];	//level
						$id = $temp_data_item[5];	//level
						
						//add html for action  
						$str_aksi = '<div align="right">';
						if($temp_data_len > 1){
							if($level == 1){
								//urutan 1
								$str_aksi = $str_aksi.'<a class="btn btn-xs btn-'.$btn.'" href="javascript:void()" title="Turun posisi" onclick="data_edit_posisi('."'".$id."','down'".')"><i class="fa fa-chevron-down"></i></a>';
							}elseif($level == $temp_data_len){
								//urutan akhir
								$str_aksi = $str_aksi.'<a class="btn btn-xs btn-'.$btn.'" href="javascript:void()" title="Naik posisi" onclick="data_edit_posisi('."'".$id."','up'".')"><i class="fa fa-chevron-up"></i></a>';
							}else{
								//urutan diantara
								$str_aksi = $str_aksi.'<a class="btn btn-xs btn-'.$btn.'" href="javascript:void()" title="Naik posisi" onclick="data_edit_posisi('."'".$id."','up'".')"><i class="fa fa-chevron-up"></i></a>
								<a class="btn btn-xs btn-'.$btn.'" href="javascript:void()" title="Turun posisi" onclick="data_edit_posisi('."'".$id."','down'".')"><i class="fa fa-chevron-down"></i></a>';
							}
						}						
						
						$rowin[] = $str_aksi.'
								<a class="btn btn-xs btn-warning" href="javascript:void()" title="Edit" onclick="data_edit('."'".$id."'".')"><i class="fa fa-pencil"></i></a>
							  <a class="btn btn-xs btn-danger" href="javascript:void()" title="Hapus" onclick="data_delete('."'".$id."','".$temp_data_item[1]." oleh ".$temp_data_item[2]."'".')"><i class="fa fa-times"></i></a></div>';
						
						$rowin[] = $btn;
						
						$data[] = $rowin;												
					}//end foreach
					
					//RESET VARIABEL										
					$temp_data = array();
					if($btn == "info"){
						$btn = "default";
					}else{
						$btn = "info";
					}								
				}//end no=1				
			}//end nama pengirim
			
			//insert
			$no++;
			$row = array();
			$row[] = $no;												
			$row[] = $nama_pengirim;
			$row[] = $nama_penerima;
			$row[] = $wait_before;								
			$row[] = $list_item->level;	
			$row[] = $list_item->id;			
			$temp_data[] = $row;										
		}//end foreach
		
		//insert last group item
		$temp_data_len = count($temp_data);			
		foreach($temp_data as $temp_data_item){
			$rowin = array();
			$rowin[] = $temp_data_item[0];	//no
			$rowin[] = $temp_data_item[1];	//pengirim
			$rowin[] = $temp_data_item[2]; 	//penerima
			$rowin[] = $temp_data_item[3];	//wait before
			$level = $temp_data_item[4];	//level
			$id = $temp_data_item[5];	//level
			
			//add html for action  
			$str_aksi = '<div align="right">';
			if($temp_data_len > 1){
				if($level == 1){
					//urutan 1
					$str_aksi = $str_aksi.'<a class="btn btn-xs btn-'.$btn.'" href="javascript:void()" title="Turun posisi" onclick="data_edit_posisi('."'".$id."','down'".')"><i class="fa fa-chevron-down"></i></a>';
				}elseif($level == $temp_data_len){
					//urutan akhir
					$str_aksi = $str_aksi.'<a class="btn btn-xs btn-'.$btn.'" href="javascript:void()" title="Naik posisi" onclick="data_edit_posisi('."'".$id."','up'".')"><i class="fa fa-chevron-up"></i></a>';
				}else{
					//urutan diantara
					$str_aksi = $str_aksi.'<a class="btn btn-xs btn-'.$btn.'" href="javascript:void()" title="Naik posisi" onclick="data_edit_posisi('."'".$id."','up'".')"><i class="fa fa-chevron-up"></i></a>
					<a class="btn btn-xs btn-'.$btn.'" href="javascript:void()" title="Turun posisi" onclick="data_edit_posisi('."'".$id."','down'".')"><i class="fa fa-chevron-down"></i></a>';
				}
			}
			//$i++;
			
			$rowin[] = $str_aksi.'
					<a class="btn btn-xs btn-warning" href="javascript:void()" title="Edit" onclick="data_edit('."'".$id."'".')"><i class="fa fa-pencil"></i></a>
				  <a class="btn btn-xs btn-danger" href="javascript:void()" title="Hapus" onclick="data_delete('."'".$id."','".$temp_data_item[1]." oleh ".$temp_data_item[2]."'".')"><i class="fa fa-times"></i></a></div>';
			$rowin[] = $btn;
			
			$data[] = $rowin;												
		}//end foreach
		
				
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->cms_model->count_all_where($datatable_name, $where),
			"recordsFiltered" => $this->cms_model->count_filtered_where($datatable_name, $search_column, $search_order, $where, $order_by),
			"data" => $data,
				);
		//output to json format
		echo json_encode($output);		
	}	
	
	public function data_add()
	{
		$data['select_struktural_out'] = $this->cms_model->get_struktural();
		array_unshift($data['select_struktural_out'],array('id_item' => '0', 'nama_item' => '--Pilih--'));
		$data['select_posisi_out'] = $this->cms_model->get_posisi();
		array_unshift($data['select_posisi_out'],array('id_item' => '0', 'nama_item' => '--Pilih--'));
		$data['select_fungsional_out'] = $this->cms_model->get_fungsional();
		array_unshift($data['select_fungsional_out'],array('id_item' => '0', 'nama_item' => '--Pilih--'));
		$data['select_group_out'] = $this->cms_model->get_users_groups();
		array_unshift($data['select_group_out'],array('id_item' => '0', 'nama_item' => '--Pilih--'));
		
		$data['select_struktural_in'] = $this->cms_model->get_struktural();
		array_unshift($data['select_struktural_in'],array('id_item' => '0', 'nama_item' => '--Pilih--'));
		$data['select_posisi_in'] = $this->cms_model->get_posisi();
		array_unshift($data['select_posisi_in'],array('id_item' => '0', 'nama_item' => '--Pilih--'));
		$data['select_fungsional_in'] = $this->cms_model->get_fungsional();
		array_unshift($data['select_fungsional_in'],array('id_item' => '0', 'nama_item' => '--Pilih--'));
		$data['select_group_in'] = $this->cms_model->get_users_groups();
		array_unshift($data['select_group_in'],array('id_item' => '0', 'nama_item' => '--Pilih--'));
		
		echo json_encode($data);
	}
		
	public function data_edit($id)
	{							
		$data['list'] = $this->cms_model->row_get_by_id($id, $this->table_name);
		
		$data['select_struktural_out'] = $this->cms_model->get_struktural();
		array_unshift($data['select_struktural_out'],array('id_item' => '0', 'nama_item' => '--Pilih--'));
		$data['select_posisi_out'] = $this->cms_model->get_posisi();
		array_unshift($data['select_posisi_out'],array('id_item' => '0', 'nama_item' => '--Pilih--'));
		$data['select_fungsional_out'] = $this->cms_model->get_fungsional();
		array_unshift($data['select_fungsional_out'],array('id_item' => '0', 'nama_item' => '--Pilih--'));
		$data['select_group_out'] = $this->cms_model->get_users_groups();
		array_unshift($data['select_group_out'],array('id_item' => '0', 'nama_item' => '--Pilih--'));
		
		$data['select_struktural_in'] = $this->cms_model->get_struktural();
		array_unshift($data['select_struktural_in'],array('id_item' => '0', 'nama_item' => '--Pilih--'));
		$data['select_posisi_in'] = $this->cms_model->get_posisi();
		array_unshift($data['select_posisi_in'],array('id_item' => '0', 'nama_item' => '--Pilih--'));
		$data['select_fungsional_in'] = $this->cms_model->get_fungsional();
		array_unshift($data['select_fungsional_in'],array('id_item' => '0', 'nama_item' => '--Pilih--'));
		$data['select_group_in'] = $this->cms_model->get_users_groups();
		array_unshift($data['select_group_in'],array('id_item' => '0', 'nama_item' => '--Pilih--'));					
		
		echo json_encode($data);
	}
	
	public function data_save_add()
	{					
        //set validation rules		
		$option_out = $this->input->post('optPengirim');		
		$this->form_validation->set_rules('select_'.$option_out.'_out', $option_out, "callback_check_select_out");					
		$option_in = $this->input->post('optPenerima');		
		$this->form_validation->set_rules('select_'.$option_in.'_in', $option_in, "callback_check_select_in");
		$this->form_validation->set_rules('wait_before', 'Konfirmasi', 'is_numeric');
			
		if ($this->form_validation->run() == FALSE)
        {            
			//validation fails
			echo json_encode(array("status" => validation_errors()));            
        }
        else
        {    
			$approval_type_id = $this->input->post('approval_type_id');
			$select_out = $this->input->post('select_'.$option_out.'_out');
			$select_in = $this->input->post('select_'.$option_in.'_in');
			$wait_before = $this->input->post('wait_before');
			
			if($wait_before == ""){
				$wait_before = NULL;
			}
			
			$no_level = $this->cms_model->get_max_level_approval($approval_type_id, $option_out, $select_out);
			$no_level = $no_level + 1;
			
            //pass validation			
			$additional_data = array(				
				'out_'.$option_out.'_id' => $select_out,
				'in_'.$option_in.'_id'  => $select_in,
				'approval_type_id' => $approval_type_id,
				'wait_before'  => $wait_before,
				'level' => $no_level
			);
			
			$insert = $this->cms_model->save($additional_data, $this->table_name);									
			echo json_encode(array("status" => TRUE));
        }		
	}
	
	public function data_save_edit()
	{								
        //set validation rules		
		$option_out = $this->input->post('optPengirim');		
		$this->form_validation->set_rules('select_'.$option_out.'_out', $option_out, "callback_check_select_out");					
		$option_in = $this->input->post('optPenerima');		
		$this->form_validation->set_rules('select_'.$option_in.'_in', $option_in, "callback_check_select_in");
		$this->form_validation->set_rules('wait_before', 'Konfirmasi', 'is_numeric');
		
		if ($this->form_validation->run() == FALSE)
        {            
			//validation fails
			echo json_encode(array("status" => validation_errors()));            
        }
        else
        {    
			$id = $this->input->post('id');
			$data_list = $this->cms_model->row_get_by_id($id, $this->table_name);		//get user data sebelumnya
		
			$approval_type_id = $this->input->post('approval_type_id');
			$select_out = $this->input->post('select_'.$option_out.'_out');
			$select_in = $this->input->post('select_'.$option_in.'_in');
			$wait_before = $this->input->post('wait_before');
			
			if($wait_before == ""){
				$wait_before = NULL;
			}
			
			$no_level = $data_list->level;
			
            //pass validation			
			$additional_data = array(				
				'out_'.$option_out.'_id' => $select_out,
				'in_'.$option_in.'_id'  => $select_in,
				'approval_type_id' => $approval_type_id,
				'wait_before'  => $wait_before,
				'level' => $no_level
			);
			
			$this->cms_model->delete($this->table_name, array('id' => $id));		//hapus data
			$insert = $this->cms_model->save($additional_data, $this->table_name);	
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


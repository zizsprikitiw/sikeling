<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cms_users extends CI_Controller {
	//Global variable
	var $table_name = 'users';
	
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(array('ion_auth','form_validation'));
		$this->load->helper(array('url','language'));

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

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
				
		$this->_render_page('cms/cms_users', $this->data);				
	}		
	
	public function data_list()
	{		
		$search_column = array('lower(nama)','nip','email','username');
		$search_order = array('nama' => 'asc');
		
		$list = $this->cms_model->get_datatables($this->table_name, $search_column, $search_order);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $list_item) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $list_item->nama;
			$row[] = $list_item->nip;
			$row[] = $list_item->username;
			$row[] = date("d-m-Y H:i",$list_item->last_login);						
			
			//add status aktif
			if ($list_item->active == 1) {									
				$row[] = '<button class="btn btn-xs btn-success" onclick="data_edit_status('."'".$list_item->id."'".')">Active</button>';
			}else{
				$row[] = '<button class="btn btn-xs btn-danger" onclick="data_edit_status('."'".$list_item->id."'".')">Not Active</button>';				
				}	
			
			//add html for action
			$row[] = '<a class="btn btn-xs btn-info" href="javascript:void()" title="Bidang" onclick="users_bidang('."'".$list_item->id."','".$list_item->nama."'".')"><i class="fa fa-home"></i></a>
			<a class="btn btn-xs btn-info" href="javascript:void()" title="Struktural" onclick="users_struktural('."'".$list_item->id."','".$list_item->nama."'".')"><i class="fa fa-legal"></i></a>
			<a class="btn btn-xs btn-info" href="javascript:void()" title="Fungsional" onclick="users_fungsional('."'".$list_item->id."','".$list_item->nama."'".')"><i class="fa fa-level-up"></i></a>
			<a class="btn btn-xs btn-info" href="javascript:void()" title="Group User" onclick="data_edit_user_group('."'".$list_item->id."','".$list_item->nama."'".')"><i class="fa fa-group"></i></a>
			<a class="btn btn-xs btn-warning" href="javascript:void()" title="Edit" onclick="data_edit('."'".$list_item->id."'".')"><i class="fa fa-pencil"></i></a>
				  <a class="btn btn-xs btn-danger" href="javascript:void()" title="Hapus" onclick="data_delete('."'".$list_item->id."','".$list_item->nama."'".')"><i class="fa fa-times"></i></a>';
																			
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->cms_model->count_all($this->table_name),
						"recordsFiltered" => $this->cms_model->count_filtered($this->table_name, $search_column, $search_order),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);		
	}
	
	public function data_add()
	{
		//fetch data from department and designation tables
        $data['users_groups'] = $this->cms_model->get_users_groups();	
		array_unshift($data['users_groups'],array('id_item' => '0', 'nama_item' => '--Pilih--'));				
				
		echo json_encode($data);
	}
	
	public function data_edit($id)
	{		
		$data['user'] = $this->cms_model->row_get_by_id($id, $this->table_name);
		echo json_encode($data);
	}
	
	public function data_save_add()
	{
		$tables = $this->config->item('tables','ion_auth');
        $identity_column = $this->config->item('identity','ion_auth');
        $this->data['identity_column'] = $identity_column;
			
        //set validation rules
		$this->form_validation->set_rules('nama', 'Nama Lengkap', 'trim|required|xss_clean');
		$this->form_validation->set_rules('nip', 'Nomor Induk Pegawai', 'trim|required');
		$this->form_validation->set_rules('txt_email', 'Alamat Email', 'trim|valid_email');				
		$this->form_validation->set_rules('txt_username','Username','trim|required|is_unique[' . $tables['users'] . '.username]');        		
		$this->form_validation->set_rules('txt_password', 'Password', 'trim|required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[txt_password_confirm]');
        $this->form_validation->set_rules('txt_password_confirm', 'Ulangi Password', 'required');
		
        $this->form_validation->set_rules('users_groups', 'Users Group', 'required|callback_combo_groups');	
		
		if ($this->form_validation->run() == FALSE)
        {            
			//validation fails
			echo json_encode(array("status" => validation_errors()));            
        }
        else
        {    
            //pass validation
			$email    = strtolower($this->input->post('txt_email'));
			$identity = $this->input->post('txt_username');
			$password = $this->input->post('txt_password');

			$additional_data = array(				
				'nama' => $this->input->post('nama'),
				'nip'  => $this->input->post('nip')
			);
			
			$group_ids = array($this->input->post('users_groups'));												
			$user_id = $this->ion_auth->register($identity, $password, $email, $additional_data, $group_ids);					
						
			echo json_encode(array("status" => TRUE));
        }		
	}
	
	public function data_save_edit()
	{
		$tables = $this->config->item('tables','ion_auth');
        $identity_column = $this->config->item('identity','ion_auth');
        $this->data['identity_column'] = $identity_column;
		
		$user_id = $this->input->post('id');
		$username = $this->input->post('txt_username');
		$user = $this->ion_auth->user($user_id)->row();		//get user data sebelumnya
		
        //set validation rules
		$this->form_validation->set_rules('nama', 'Nama Lengkap', 'trim|required|xss_clean');
		$this->form_validation->set_rules('nip', 'Nomor Induk Pegawai', 'trim|required');
		$this->form_validation->set_rules('txt_email', 'Alamat Email', 'trim|valid_email');	

		//cek cek validasi jika berbeda 
		if($user->username != $username){
			$this->form_validation->set_rules('txt_username','Username','trim|required|is_unique[' . $tables['users'] . '.username]');
		}
		        				
		// update the password if it was posted
		if ($this->input->post('txt_password'))
		{
			$this->form_validation->set_rules('txt_password', 'Password', 'trim|required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[txt_password_confirm]');
			$this->form_validation->set_rules('txt_password_confirm', 'Ulangi Password', 'required');
		}								
		
		if ($this->form_validation->run() == FALSE)
        {            
			//validation fails
			echo json_encode(array("status" => validation_errors()));            
        }
        else
        {    
            //pass validation									
			$additional_data = array(				
				'nama' => $this->input->post('nama'),
				'nip'  => $this->input->post('nip'),
				'email' => strtolower($this->input->post('txt_email')),
				'username' => $username,
			);
			
			// update the password if it was posted
			if ($this->input->post('txt_password'))
			{
				$additional_data['password'] = $this->input->post('txt_password');
			}						
						
			if($this->ion_auth->update($user->user_id, $additional_data))
			{								
				echo json_encode(array("status" => TRUE));
			}else{
				//gagal update
				echo json_encode(array("status" => "Gagal update data"));
			}		
        }		
	}
	
	public function data_delete()
	{	
        $user_id = $this->input->post('id_delete_user');				
		
		//hapus user bidang
		$this->cms_model->delete('users_bidang', array('user_id' => $user_id));
		
		//hapus user groups
		$this->cms_model->delete('users_groups', array('user_id' => $user_id));
		
		//hapus user
		$this->cms_model->delete('users', array('id' => $user_id));		
		
		echo json_encode(array("status" => TRUE));
	}
	
	public function data_edit_status($user_id)
	{	
		$user = $this->ion_auth->user($user_id)->row();		//get user data sebelumnya
		//rubah status
		
		if($user->active == false){
			$status = 1;
		}else{
			$status = 0;
		}				
		
		if($this->ion_auth->update($user_id, array('active' => $status)))
		{
			//berhasil update
			echo json_encode(array("status" => TRUE));
		}else{
			//gagal
			echo json_encode(array("status" => FALSE));
		}	
	}
	
	//custom validation function for dropdown input
    function combo_groups($str)
    {
        if ($str == '0'){
            $this->form_validation->set_message('combo_groups', 'Kolom %s harus dipilih');
            return FALSE;
        }else{
            return TRUE;
        }
    }
	
	//custom validation function to accept only alpha and space input
    function alpha_only_space($str)
    {
        if (!preg_match("/^([-a-z ])+$/i", $str)){
            $this->form_validation->set_message('alpha_only_space', 'Kolom %s harus mengandung huruh dan spasi saja');
            return FALSE;
        }else{
            return TRUE;
        }
    }
	
	// USERS GROUP
	//========================================================
	function data_get_users_group()
	{
		 $user_id = $this->input->post('user_id');
		 $list_users_groups = $this->cms_model->get_users_groups();
		 $list_group_for_user = $this->cms_model->get_groups_for_user($user_id)  ;
		 $str_checkbox = '';
		 
		 foreach($list_users_groups as $group_item){
		 	$selected = '';
		 	foreach($list_group_for_user as $user_item){
				if($group_item->id_item == $user_item->group_id){
					//ceteak dengan tanda centang
					$selected = 'checked="checked"';
				}
			}
		 	
			//cetak select
			$str_checkbox = $str_checkbox.'<label class="checkbox"><input type="checkbox" id="chk_users_groups[]" name="chk_users_groups[]" value="'.$group_item->id_item.'" style="cursor:hand;" '.$selected.'> '.$group_item->nama_item.'</label>';
		 }
		 
		 $data['users_groups'] = $str_checkbox;
		 echo json_encode($data);		 
	}
	
	function data_set_users_group()
	{
		$user_id = $this->input->post('id_user_for_ug');
		$chk_users_groups = $this->input->post('chk_users_groups');
		
		$this->cms_model->delete('users_groups', array('user_id' => $user_id));
		foreach($chk_users_groups as $item){
			$data_users = array(
						'user_id' => $user_id,
						'group_id' => $item
					);
			$insert = $this->cms_model->save($data_users, 'users_groups');						
		}
		
		echo json_encode(array("status" => TRUE));		
	}
	
	// USERS FUNGSIONAL
	//========================================================	
	function data_get_fungsional()
	{		 
		 $data['fungsional_name'] = $this->cms_model->get_fungsional();
		 array_unshift($data['fungsional_name'],array('id_item' => '-1', 'nama_item' => '--Pilih--'));	
		 
		 $data['fungsional_tahun_awal'] = $this->cms_model->get_select_tahun();
		 array_unshift($data['fungsional_tahun_awal'],array('id_item' => '-1', 'nama_item' => '--Pilih--'));	
		 		 		 
		 $data['fungsional_tahun_akhir'] = $this->cms_model->get_select_tahun();
		 array_unshift($data['fungsional_tahun_akhir'],array('id_item' => '-1', 'nama_item' => '--Pilih--'));
		 $data['fungsional_tahun_akhir'][] = array('id_item' => '0', 'nama_item' => 'Sekarang');
		 				 
		 echo json_encode($data);		 
	}
	
	public function data_list_fungsional()
	{
		$datatable_name = 'v_users_fungsional';
		$search_column = array('nama_fungsional');
		$search_order = array('tahun_akhir' => 'asc');
		$where =  array('users_id' => $_POST['id_user_for_fungsional']);
		$order_by = 'tahun_akhir asc';		
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $list_item) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $list_item->nama_fungsional;
			$row[] = $list_item->tahun_awal;
			if(($list_item->tahun_akhir == '') || ($list_item->tahun_akhir == '0')){
				$row[] = 'Sekarang';
			}else{
				$row[] = $list_item->tahun_akhir;
			}			
			
						
			//add html for action
			$row[] = '<a class="btn btn-xs btn-warning" href="javascript:void()" title="Edit" onclick="show_add_fungsional('."true,'update','".$list_item->id."'".')"><i class="fa fa-pencil"></i></a>
				  <a class="btn btn-xs btn-danger" href="javascript:void()" title="Hapus" onclick="data_delete_fungsional('."'".$list_item->id."'".')"><i class="fa fa-times"></i></a>';
																			
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
	
	function data_get_users_fungsional()
	{
		$id_users_fungsional = $this->input->post('id_users_fungsional');
		$data['users_fungsional'] = $this->cms_model->row_get_by_id($id_users_fungsional, 'users_fungsional');
		
		$data['fungsional_name'] = $this->cms_model->get_fungsional();
		 array_unshift($data['fungsional_name'],array('id_item' => '-1', 'nama_item' => '--Pilih--'));	
		 
		 $data['fungsional_tahun_awal'] = $this->cms_model->get_select_tahun();
		 array_unshift($data['fungsional_tahun_awal'],array('id_item' => '-1', 'nama_item' => '--Pilih--'));	
		 		 		 
		 $data['fungsional_tahun_akhir'] = $this->cms_model->get_select_tahun();
		 array_unshift($data['fungsional_tahun_akhir'],array('id_item' => '-1', 'nama_item' => '--Pilih--'));
		 $data['fungsional_tahun_akhir'][] = array('id_item' => '0', 'nama_item' => 'Sekarang');
		
		echo json_encode($data);
	}
	
	function data_save_add_fungsional()
	{
		$this->form_validation->set_rules('fungsional_name', 'Fungsional', 'required|callback_fungsional_name');	
		$this->form_validation->set_rules('fungsional_tahun_awal', 'Tahun awal', 'required|callback_fungsional_tahun_awal');
		$this->form_validation->set_rules('fungsional_tahun_akhir', 'Tahun akhir', 'required|callback_fungsional_tahun_akhir');
		
		if ($this->form_validation->run() == FALSE)
        {            
			//validation fails
			echo json_encode(array("status" => validation_errors()));            
        }
        else
        {    
            //pass validation			
			$additional_data = array(		
				'users_id' => $this->input->post('id_user_for_fungsional'),		
				'fungsional_id' => $this->input->post('fungsional_name'),
				'tahun_awal'  => $this->input->post('fungsional_tahun_awal'),
				'tahun_akhir'  => $this->input->post('fungsional_tahun_akhir')
			);
			
			$insert = $this->cms_model->save($additional_data, 'users_fungsional');										
			echo json_encode(array("status" => TRUE));
        }	
	}
		
	public function data_save_edit_fungsional()
	{										
        //set validation rules
		$this->form_validation->set_rules('fungsional_name', 'Fungsional', 'required|callback_fungsional_name');	
		$this->form_validation->set_rules('fungsional_tahun_awal', 'Tahun awal', 'required|callback_fungsional_tahun_awal');
		$this->form_validation->set_rules('fungsional_tahun_akhir', 'Tahun akhir', 'required|callback_fungsional_tahun_akhir');
		
		if ($this->form_validation->run() == FALSE)
        {            
			//validation fails
			echo json_encode(array("status" => validation_errors()));            
        }
        else
        {    
			$id = $this->input->post('id_users_fungsional');
			
            //pass validation			
			$additional_data = array(		
				'fungsional_id' => $this->input->post('fungsional_name'),
				'tahun_awal'  => $this->input->post('fungsional_tahun_awal'),
				'tahun_akhir'  => $this->input->post('fungsional_tahun_akhir')
			);
			
			if($this->cms_model->update(array("id" => $id), $additional_data, 'users_fungsional'))
			{
				//berhasil update, lakukan update lainnya pada tabel user group dan user bidang								
				echo json_encode(array("status" => TRUE));
			}else{
				//gagal update
				echo json_encode(array("status" => "Gagal update data"));
			}		
        }		
	}	
	
	public function data_delete_fungsional()
	{			
        $id = $this->input->post('id_users_fungsional');				
		
		//hapus users fungsional
		$this->cms_model->delete('users_fungsional', array('id' => $id));		
		
		echo json_encode(array("status" => TRUE));
	}
	
	//custom validation function for dropdown input
    function fungsional_name($str)
    {
        if ($str == '-1'){
            $this->form_validation->set_message('fungsional_name', 'Kolom %s harus dipilih');
            return FALSE;
        }else{
            return TRUE;
        }
    }
	
	function fungsional_tahun_awal($str)
    {
        if ($str == '-1'){
            $this->form_validation->set_message('fungsional_tahun_awal', 'Kolom %s harus dipilih');
            return FALSE;
        }else{
            return TRUE;
        }
    }
	
	function fungsional_tahun_akhir($str)
    {
        if ($str == '-1'){
            $this->form_validation->set_message('fungsional_tahun_akhir', 'Kolom %s harus dipilih');
            return FALSE;
        }else{
            return TRUE;
        }
    }
	
	// USERS STRUKTURAL
	//========================================================	
	function data_get_struktural()
	{		 
		 $data['struktural_name'] = $this->cms_model->get_struktural();
		 array_unshift($data['struktural_name'],array('id_item' => '-1', 'nama_item' => '--Pilih--'));	
		 
		 $data['struktural_tahun_awal'] = $this->cms_model->get_select_tahun();
		 array_unshift($data['struktural_tahun_awal'],array('id_item' => '-1', 'nama_item' => '--Pilih--'));	
		 		 		 
		 $data['struktural_tahun_akhir'] = $this->cms_model->get_select_tahun();
		 array_unshift($data['struktural_tahun_akhir'],array('id_item' => '-1', 'nama_item' => '--Pilih--'));
		 $data['struktural_tahun_akhir'][] = array('id_item' => '0', 'nama_item' => 'Sekarang');
		 				 
		 echo json_encode($data);		 
	}
	
	public function data_list_struktural()
	{
		$datatable_name = 'v_users_struktural';
		$search_column = array('nama_struktural');
		$search_order = array('tahun_akhir' => 'asc');
		$where =  array('users_id' => $_POST['id_user_for_struktural']);
		$order_by = 'tahun_akhir asc';		
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $list_item) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $list_item->nama_struktural;
			$row[] = $list_item->tahun_awal;
			if(($list_item->tahun_akhir == '') || ($list_item->tahun_akhir == '0')){
				$row[] = 'Sekarang';
			}else{
				$row[] = $list_item->tahun_akhir;
			}			
			
						
			//add html for action
			$row[] = '<a class="btn btn-xs btn-warning" href="javascript:void()" title="Edit" onclick="show_add_struktural('."true,'update','".$list_item->id."'".')"><i class="fa fa-pencil"></i></a>
				  <a class="btn btn-xs btn-danger" href="javascript:void()" title="Hapus" onclick="data_delete_struktural('."'".$list_item->id."'".')"><i class="fa fa-times"></i></a>';
																			
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
	
	function data_get_users_struktural()
	{
		$id_users_struktural = $this->input->post('id_users_struktural');
		$data['users_struktural'] = $this->cms_model->row_get_by_id($id_users_struktural, 'users_struktural');
		
		$data['struktural_name'] = $this->cms_model->get_struktural();
		 array_unshift($data['struktural_name'],array('id_item' => '-1', 'nama_item' => '--Pilih--'));	
		 
		 $data['struktural_tahun_awal'] = $this->cms_model->get_select_tahun();
		 array_unshift($data['struktural_tahun_awal'],array('id_item' => '-1', 'nama_item' => '--Pilih--'));	
		 		 		 
		 $data['struktural_tahun_akhir'] = $this->cms_model->get_select_tahun();
		 array_unshift($data['struktural_tahun_akhir'],array('id_item' => '-1', 'nama_item' => '--Pilih--'));
		 $data['struktural_tahun_akhir'][] = array('id_item' => '0', 'nama_item' => 'Sekarang');
		
		echo json_encode($data);
	}
	
	function data_save_add_struktural()
	{
		$this->form_validation->set_rules('struktural_name', 'Struktural', 'required|callback_struktural_name');	
		$this->form_validation->set_rules('struktural_tahun_awal', 'Tahun awal', 'required|callback_struktural_tahun_awal');
		$this->form_validation->set_rules('struktural_tahun_akhir', 'Tahun akhir', 'required|callback_struktural_tahun_akhir');
		
		if ($this->form_validation->run() == FALSE)
        {            
			//validation fails
			echo json_encode(array("status" => validation_errors()));            
        }
        else
        {    
            //pass validation			
			$additional_data = array(		
				'users_id' => $this->input->post('id_user_for_struktural'),		
				'struktural_id' => $this->input->post('struktural_name'),
				'tahun_awal'  => $this->input->post('struktural_tahun_awal'),
				'tahun_akhir'  => $this->input->post('struktural_tahun_akhir')
			);
			
			$insert = $this->cms_model->save($additional_data, 'users_struktural');										
			echo json_encode(array("status" => TRUE));
        }	
	}
		
	public function data_save_edit_struktural()
	{										
        //set validation rules
		$this->form_validation->set_rules('struktural_name', 'Struktural', 'required|callback_struktural_name');	
		$this->form_validation->set_rules('struktural_tahun_awal', 'Tahun awal', 'required|callback_struktural_tahun_awal');
		$this->form_validation->set_rules('struktural_tahun_akhir', 'Tahun akhir', 'required|callback_struktural_tahun_akhir');
		
		if ($this->form_validation->run() == FALSE)
        {            
			//validation fails
			echo json_encode(array("status" => validation_errors()));            
        }
        else
        {    
			$id = $this->input->post('id_users_struktural');
			
            //pass validation			
			$additional_data = array(		
				'struktural_id' => $this->input->post('struktural_name'),
				'tahun_awal'  => $this->input->post('struktural_tahun_awal'),
				'tahun_akhir'  => $this->input->post('struktural_tahun_akhir')
			);
			
			if($this->cms_model->update(array("id" => $id), $additional_data, 'users_struktural'))
			{
				//berhasil update, lakukan update lainnya pada tabel user group dan user bidang								
				echo json_encode(array("status" => TRUE));
			}else{
				//gagal update
				echo json_encode(array("status" => "Gagal update data"));
			}		
        }		
	}	
	
	public function data_delete_struktural()
	{			
        $id = $this->input->post('id_users_struktural');				
		
		//hapus users fungsional
		$this->cms_model->delete('users_struktural', array('id' => $id));		
		
		echo json_encode(array("status" => TRUE));
	}
	
	//custom validation function for dropdown input
    function struktural_name($str)
    {
        if ($str == '-1'){
            $this->form_validation->set_message('struktural_name', 'Kolom %s harus dipilih');
            return FALSE;
        }else{
            return TRUE;
        }
    }
	
	function struktural_tahun_awal($str)
    {
        if ($str == '-1'){
            $this->form_validation->set_message('struktural_tahun_awal', 'Kolom %s harus dipilih');
            return FALSE;
        }else{
            return TRUE;
        }
    }
	
	function struktural_tahun_akhir($str)
    {
        if ($str == '-1'){
            $this->form_validation->set_message('struktural_tahun_akhir', 'Kolom %s harus dipilih');
            return FALSE;
        }else{
            return TRUE;
        }
    }
	
	// USERS BIDANG
	//========================================================	
	function data_get_bidang()
	{		 
		 $data['bidang_name'] = $this->cms_model->get_bidang();
		 array_unshift($data['bidang_name'],array('id_item' => '-1', 'nama_item' => '--Pilih--'));	
		 
		 $data['bidang_tahun_awal'] = $this->cms_model->get_select_tahun();
		 array_unshift($data['bidang_tahun_awal'],array('id_item' => '-1', 'nama_item' => '--Pilih--'));	
		 		 		 
		 $data['bidang_tahun_akhir'] = $this->cms_model->get_select_tahun();
		 array_unshift($data['bidang_tahun_akhir'],array('id_item' => '-1', 'nama_item' => '--Pilih--'));
		 $data['bidang_tahun_akhir'][] = array('id_item' => '0', 'nama_item' => 'Sekarang');
		 				 
		 echo json_encode($data);		 
	}
	
	public function data_list_bidang()
	{
		$datatable_name = 'v_users_bidang';
		$search_column = array('nama_bidang');
		$search_order = array('tahun_akhir' => 'asc');
		$where =  array('user_id' => $_POST['id_user_for_bidang']);
		$order_by = 'tahun_akhir asc';		
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $list_item) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $list_item->nama_bidang.' ('.$list_item->nama_pusat.')';
			$row[] = $list_item->tahun_awal;
			if(($list_item->tahun_akhir == '') || ($list_item->tahun_akhir == '0')){
				$row[] = 'Sekarang';
			}else{
				$row[] = $list_item->tahun_akhir;
			}			
			
						
			//add html for action
			$row[] = '<a class="btn btn-xs btn-warning" href="javascript:void()" title="Edit" onclick="show_add_bidang('."true,'update','".$list_item->id."'".')"><i class="fa fa-pencil"></i></a>
				  <a class="btn btn-xs btn-danger" href="javascript:void()" title="Hapus" onclick="data_delete_bidang('."'".$list_item->id."'".')"><i class="fa fa-times"></i></a>';
																			
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
	
	function data_get_users_bidang()
	{
		$id_users_bidang = $this->input->post('id_users_bidang');
		$data['users_bidang'] = $this->cms_model->row_get_by_id($id_users_bidang, 'users_bidang');
		
		$data['bidang_name'] = $this->cms_model->get_bidang();
		 array_unshift($data['bidang_name'],array('id_item' => '-1', 'nama_item' => '--Pilih--'));	
		 
		 $data['bidang_tahun_awal'] = $this->cms_model->get_select_tahun();
		 array_unshift($data['bidang_tahun_awal'],array('id_item' => '-1', 'nama_item' => '--Pilih--'));	
		 		 		 
		 $data['bidang_tahun_akhir'] = $this->cms_model->get_select_tahun();
		 array_unshift($data['bidang_tahun_akhir'],array('id_item' => '-1', 'nama_item' => '--Pilih--'));
		 $data['bidang_tahun_akhir'][] = array('id_item' => '0', 'nama_item' => 'Sekarang');
		
		echo json_encode($data);
	}
	
	function data_save_add_bidang()
	{
		$this->form_validation->set_rules('bidang_name', 'bidang', 'required|callback_bidang_name');	
		$this->form_validation->set_rules('bidang_tahun_awal', 'Tahun awal', 'required|callback_bidang_tahun_awal');
		$this->form_validation->set_rules('bidang_tahun_akhir', 'Tahun akhir', 'required|callback_bidang_tahun_akhir');
		
		if ($this->form_validation->run() == FALSE)
        {            
			//validation fails
			echo json_encode(array("status" => validation_errors()));            
        }
        else
        {    
            //pass validation			
			$additional_data = array(		
				'user_id' => $this->input->post('id_user_for_bidang'),		
				'bidang_id' => $this->input->post('bidang_name'),
				'tahun_awal'  => $this->input->post('bidang_tahun_awal'),
				'tahun_akhir'  => $this->input->post('bidang_tahun_akhir')
			);
			
			$insert = $this->cms_model->save($additional_data, 'users_bidang');										
			echo json_encode(array("status" => TRUE));
        }	
	}
		
	public function data_save_edit_bidang()
	{										
        //set validation rules
		$this->form_validation->set_rules('bidang_name', 'bidang', 'required|callback_bidang_name');	
		$this->form_validation->set_rules('bidang_tahun_awal', 'Tahun awal', 'required|callback_bidang_tahun_awal');
		$this->form_validation->set_rules('bidang_tahun_akhir', 'Tahun akhir', 'required|callback_bidang_tahun_akhir');
		
		if ($this->form_validation->run() == FALSE)
        {            
			//validation fails
			echo json_encode(array("status" => validation_errors()));            
        }
        else
        {    
			$id = $this->input->post('id_users_bidang');
			
            //pass validation			
			$additional_data = array(		
				'bidang_id' => $this->input->post('bidang_name'),
				'tahun_awal'  => $this->input->post('bidang_tahun_awal'),
				'tahun_akhir'  => $this->input->post('bidang_tahun_akhir')
			);
			
			if($this->cms_model->update(array("id" => $id), $additional_data, 'users_bidang'))
			{
				//berhasil update, lakukan update lainnya pada tabel user group dan user bidang								
				echo json_encode(array("status" => TRUE));
			}else{
				//gagal update
				echo json_encode(array("status" => "Gagal update data"));
			}		
        }		
	}	
	
	public function data_delete_bidang()
	{			
        $id = $this->input->post('id_users_bidang');				
		
		//hapus users fungsional
		$this->cms_model->delete('users_bidang', array('id' => $id));		
		
		echo json_encode(array("status" => TRUE));
	}
	
	//custom validation function for dropdown input
    function bidang_name($str)
    {
        if ($str == '-1'){
            $this->form_validation->set_message('bidang_name', 'Kolom %s harus dipilih');
            return FALSE;
        }else{
            return TRUE;
        }
    }
	
	function bidang_tahun_awal($str)
    {
        if ($str == '-1'){
            $this->form_validation->set_message('bidang_tahun_awal', 'Kolom %s harus dipilih');
            return FALSE;
        }else{
            return TRUE;
        }
    }
	
	function bidang_tahun_akhir($str)
    {
        if ($str == '-1'){
            $this->form_validation->set_message('bidang_tahun_akhir', 'Kolom %s harus dipilih');
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

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cms_users_group extends CI_Controller {
	
	//Global variable
	var $table_users = 'users';
	var $table_name = 'groups';
	var $table_name_view = 'v_users';
	
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(array('ion_auth','form_validation'));
		$this->load->helper(array('url','language'));

		//$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

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
				
		$this->_render_page('cms/cms_users_group', $this->data);				
	}		
	
	public function data_init()
	{
		$data['filter_user_group'] = $this->cms_model->get_users_groups(); 
		//array_unshift($data['filter_tahun'],array('id_item' => '0', 'nama_item' => '--Pilih--'));		
		echo json_encode($data);
	}
	
	public function data_list()
	{		
		$search_column = array('name','description');
		$search_order = array('name' => 'desc');
		
		$list = $this->cms_model->get_datatables($this->table_name, $search_column, $search_order);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $list_item) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $list_item->name;
			$row[] = $list_item->description;								
			
			//add html for action
			$row[] = '<a class="btn btn-xs btn-primary" href="javascript:void()" title="Menu" onclick="groups_menu('."'".$list_item->id."','".$list_item->name."'".')"><i class="fa fa-sitemap"></i></a>
					<a class="btn btn-xs btn-warning" href="javascript:void()" title="Edit" onclick="data_edit('."'".$list_item->id."'".')"><i class="fa fa-pencil"></i></a>
				  <a class="btn btn-xs btn-danger" href="javascript:void()" title="Hapus" onclick="data_delete('."'".$list_item->id."','".$list_item->name."'".')"><i class="fa fa-times"></i></a>';
																			
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
		
	public function data_list_group_personil()
	{				
		$search_column = array('lower(nama)');
		$search_order = array('nama' => 'asc');
		$where =  array('group_id' => $_POST['filter_user_group']);  //laporan logbook (status=1)
		$order_by = 'nama desc';								
		//$user_id = $this->session->userdata('user_id');
		$list = $this->cms_model->get_datatables_where($this->table_name_view, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $list_item) {
			$no++;
			$row = array();
			$row[] = $no;						
			$row[] = $list_item->nama;		
			$row[] = '<button class="btn btn-xs btn-danger" title="Hapus" onclick="data_delete_personil('."'".$list_item->id."_".$list_item->group_id."','".$list_item->nama."'".')"><i class="fa fa-times"></i></button>';
																																							
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
	
	public function data_list_personil()
	{				
		$id_user_groups = $this->input->post('id_user_groups');	
		$search_column = array('lower(nama)');
		$search_order = array('nama' => 'asc');
		$order_by = 'nama desc';								
		$list = $this->cms_model->get_datatables($this->table_users, $search_column, $search_order);
		
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $list_item) {
			$no++;
			$row = array();
			$row[] = $no;						
			$row[] = $list_item->nama;	
			$row[] = '<button class="btn btn-xs btn-success" title="Tambah" onclick="data_add_personil('."'".$list_item->id."','".$id_user_groups."'".')"><i class="fa fa-plus"></i></button>';
																					
			$data[] = $row;
		}
		
		$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->cms_model->count_all($this->table_users),
				"recordsFiltered" => $this->cms_model->count_filtered($this->table_users, $search_column, $search_order),
				"data" => $data,
		);
						
		//output to json format
		echo json_encode($output);	
	}		
		
	public function data_edit($id)
	{							
		$data['list'] = $this->cms_model->row_get_by_id($id, $this->table_name);
		echo json_encode($data);
	}
	
	public function data_save_add()
	{					
        //set validation rules
		$this->form_validation->set_rules('name', 'User Group', 'trim|required|is_unique[' . $this->table_name . '.name]'); 
		$this->form_validation->set_rules('description', 'Keterangan', 'trim|required');
				
		if ($this->form_validation->run() == FALSE)
        {            
			//validation fails
			echo json_encode(array("status" => validation_errors()));            
        }
        else
        {    
            //pass validation			
			$additional_data = array(				
				'name' => $this->input->post('name'),
				'description'  => $this->input->post('description')
			);
			
			$insert = $this->cms_model->save($additional_data, $this->table_name);										
			echo json_encode(array("status" => TRUE));
        }		
	}
	
	public function data_save_edit()
	{				
		$id = $this->input->post('id');
		$name = $this->input->post('name');
		$data_list = $this->cms_model->row_get_by_id($id, $this->table_name);		//get user data sebelumnya
		
        //set validation rules
		//cek cek validasi jika berbeda 
		if($data_list->name != $name){
			$this->form_validation->set_rules('name', 'User Group', 'trim|required|is_unique[' . $this->table_name . '.name]'); 
		}		
		$this->form_validation->set_rules('description', 'Keterangan', 'trim|required');
		
		if ($this->form_validation->run() == FALSE)
        {            
			//validation fails
			echo json_encode(array("status" => validation_errors()));            
        }
        else
        {    
            //pass validation			
			$additional_data = array(				
				'name' => $this->input->post('name'),
				'description'  => $this->input->post('description')
			);
			
			if($this->cms_model->update(array("id" => $id), $additional_data, $this->table_name))
			{
				//berhasil update, lakukan update lainnya pada tabel user group dan user bidang								
				echo json_encode(array("status" => TRUE));
			}else{
				//gagal update
				echo json_encode(array("status" => "Gagal update data"));
			}		
        }		
	}
	
	public function data_delete()
	{			
        $id = $this->input->post('id_delete_data');				
		
		//hapus user
		$this->cms_model->delete($this->table_name, array('id' => $id));		
		
		echo json_encode(array("status" => TRUE));
	}		
		
	public function menu_list()
	{
		//get user group menu list
		$id_user_groups = $this->input->post('id_user_groups');	
		$user_group_menu = $this->cms_model->user_group_menu($id_user_groups);										

		//singkronisasi dengan menu
		$datatable_name = 'functions';
		$search_column = array('nama','halaman');
		$search_order = array('nama' => 'asc');
		$where =  '';
		$order_by = 'ref_id asc, no_urut asc';		
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];
		
		$main = array();
		$sub = array();
		$i = 0;
		$ref = 0;
		
		foreach ($list as $list_item) {
			if(trim($list_item->ref_id) == ''){
				$main[] = $list_item;
			}else{
				if($ref != $list_item->ref_id){
					//create new
					$i++;
					$j=1;
				}
				$ref = $list_item->ref_id;				
				$sub[$i][$j] = $list_item;				
				$j++;
			}
		}
		
		$sub_len = count($sub);
		$main_len = count($main);
		$i = 1;
		foreach($main as $main_item){
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = '<h3><i class="'.$main_item->icon.'">&nbsp;'.$main_item->nama.'</h3>';					
			
			$group_menu_id = $this->cms_model->menu_in_group($user_group_menu, $main_item->id);
			if($group_menu_id != 0){
				$row[] = '<button class="btn btn-xs btn-success" onclick="data_edit_status('."'".$main_item->id."','".$group_menu_id."'".')">On</button>';
			}else{
				$row[] = '<button class="btn btn-xs btn-danger" onclick="data_edit_status('."'".$main_item->id."','0'".')">Off</button>';
			}						
																			
			$data[] = $row;
						
			//cek sub menu
			//====================
			for($j=1; $j<=$sub_len; $j++){
				if($main_item->id == $sub[$j][1]->ref_id){
					//sub main terkait
					$k = 1;
					$sub_sub_len = count($sub[$j]);
					foreach($sub[$j] as $sub_item){
						$no++;
						$row = array();
						$row[] = $no;
						$row[] = '<div style="padding-left:30px;">--> '.$sub_item->nama.'</div>';						
						
						$group_menu_id = $this->cms_model->menu_in_group($user_group_menu, $sub_item->id);
						if($group_menu_id != 0){
							$row[] = '<button class="btn btn-xs btn-success" onclick="data_edit_status('."'".$sub_item->id."','".$group_menu_id."'".')">On</button>';
						}else{
							$row[] = '<button class="btn btn-xs btn-danger" onclick="data_edit_status('."'".$sub_item->id."','0'".')">Off</button>';
						}															
						
						$data[] = $row;
					}
				}
			}//end loop sub menu					
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
	
	public function data_edit_status()
	{
		$id_user_groups = $this->input->post('id_user_groups');	
		$id_menu = $this->input->post('id_menu');	
		$id_groups_menu = $this->input->post('id_groups_menu');
		
		if($id_groups_menu == 0){
			//tambahkan pada authority
			//pass validation			
			$additional_data = array(				
				'functions_id' => $id_menu,
				'groups_id'  => $id_user_groups
			);
			
			$insert = $this->cms_model->save($additional_data, 'authority');			
		}else{
			//delete item authority
			$this->cms_model->delete('authority', array('id' => $id_groups_menu));				
		}
		
		echo json_encode(array("status" => TRUE));
	}
	
	public function data_tambah_personil_group()
	{
		$user_id = $this->input->post('user_id');	
		$user_groups_id = $this->input->post('user_groups_id');
		
		//cek apakah sudah ada apa belum
		$list_item = $this->cms_model->query_get_by_criteria("users_groups", array('user_id' => $user_id, 'group_id' => $user_groups_id), "");		
		$n = count($list_item);
		
		if($n <= 0){
			//tambahkan data
			$data_users = array(
						'user_id' => $user_id,
						'group_id' => $user_groups_id
					);
			$insert = $this->cms_model->save($data_users, 'users_groups');
		}		
		
		echo json_encode(array("status" => TRUE));
	}	
		
	public function data_delete_personil()
	{			
        $id = $this->input->post('id_delete_data_personil');				
		$item = explode("_",$id);
		$user_id = $item[0];
		$group_id = $item[1];		
		$this->cms_model->delete("users_groups", array('user_id' => $user_id, 'group_id' => $group_id));		
		
		echo json_encode(array("status" => TRUE));
	}	
	
	function _render_page($view, $data=null, $returnhtml=false)//I think this makes more sense
	{

		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);

		if ($returnhtml) return $view_html;//This will return html on 3rd argument being true
	}
}


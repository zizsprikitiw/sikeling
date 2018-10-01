<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cms_struktur extends CI_Controller {
	
	//Global variable
	var $table_name = 'proyek';
	var $table_proyek = 'proyek';
	
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
				
		$this->_render_page('cms/cms_struktur', $this->data);				
	}		
	
	public function data_init()
	{
		$data['filter_pusat'] = $this->cms_model->get_pusat();  
		$data['filter_tahun'] = $this->cms_model->get_tahun_proyek($this->table_proyek);  
		
		echo json_encode($data);
	}
	
	public function select_sub_judul()
	{							
		$where =  array('tahun' => $_POST['filter_tahun'], 'pusat_id' => $_POST['filter_pusat']);
		$order_by = 'pusat_id asc, tahun asc, ref_id desc, no_urut asc';			
	    $list = $this->cms_model->get_menu_proyek_all($this->table_proyek, $where, $order_by);
		
		$menu_len = count($list);
		$dataList = array();		
		$n = -1;
		$n_parent = -1;
		for($i=0; $i<$menu_len; $i++){			
			if(trim($list[$i]->ref_id) == ''){
				//ditemukan parent menu											
				//cek if selected		
				$n++;				
				$parentId = $n;
				$row = array();
				$row['id_item'] = $list[$i]->id;
				$row['nama_item'] = $list[$i]->singkatan;						
				$dataList[$n] = $row;	
				$n_parent = $n;			
								
				for($j=0; $j<$menu_len; $j++){
					if($list[$j]->ref_id == $list[$i]->id){
						$dataList[$n_parent]['id_item'] = '0';
						//ditemukan sub menu, masukan ke array						
						//masukan sub menu
						$n++;						
						$row = array();
						$row['id_item'] = $list[$j]->id;
						$row['nama_item'] = '      -->'.$list[$j]->singkatan;											
						$dataList[$n] = $row;											
					}
				}//end for sub menu						
			}//end if			
		}//end for menu utama				

		$data['filter_judul'] = $dataList;
		echo json_encode($data);
	}
	
	public function data_list_group_leader()
	{
		$datatable_name = "groups_leader";
		$search_column = array('nama');
		$search_order = array('nama' => 'asc');
		$where =  array('proyek_id' => $_POST['filter_judul']);//$this->input->post('filter_pusat')
		$order_by = 'singkatan asc';				
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = 0;//$_POST['start'];
		foreach ($list as $list_item) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $list_item->nama." (".$list_item->singkatan.")";											
			
			//add html for action
			$row[] = '<a class="btn btn-xs btn-warning" href="javascript:void()" title="Edit" onclick="data_edit('."'".$list_item->id."','groups_leader'".')"><i class="fa fa-pencil"></i></a>
				  <a class="btn btn-xs btn-danger" href="javascript:void()" title="Hapus" onclick="data_delete('."'".$list_item->id."','".$list_item->nama."','groups_leader'".')"><i class="fa fa-times"></i></a>';
																			
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
		
	public function data_list_leader()
	{
		$datatable_name = "leader";
		$search_column = array('nama');
		$search_order = array('nama' => 'asc');
		$select = "leader.id as id, leader.nama as nama, leader.singkatan as singkatan, leader.id_groups_leader as id_groups_leader, groups_leader.nama as nama_gl";
		$where =  array('leader.proyek_id' => $_POST['filter_judul']);
		$order_by = 'leader.id_groups_leader asc, leader.singkatan asc';	
		$join_table	= "groups_leader";	
		$join_id = "leader.id_groups_leader=groups_leader.id";
		$group_by = array("leader.id","leader.id_groups_leader", "groups_leader.nama");
		
		$list = $this->cms_model->get_datatables_join($select, $datatable_name, $search_column, $search_order, $where, $order_by, $join_table, $join_id, $group_by);		
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $list_item) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $list_item->nama." (".$list_item->singkatan.")";	
			$row[] = $list_item->nama_gl;
			
			//add html for action
			$row[] = '<a class="btn btn-xs btn-warning" href="javascript:void()" title="Edit" onclick="data_edit('."'".$list_item->id."','leader'".')"><i class="fa fa-pencil"></i></a>
				  <a class="btn btn-xs btn-danger" href="javascript:void()" title="Hapus" onclick="data_delete('."'".$list_item->id."','".$list_item->nama."','leader'".')"><i class="fa fa-times"></i></a>';
																			
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->cms_model->count_all_join($datatable_name, $where, $join_table, $join_id),
						"recordsFiltered" => $this->cms_model->count_filtered_join($select, $datatable_name, $search_column, $search_order, $where, $order_by, $join_table, $join_id, $group_by),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}
	
	public function get_judul_proyek()
	{
		$judul_proyek = $this->cms_model->row_get_by_id($_POST['filter_judul'], $this->table_proyek);	
		echo json_encode($judul_proyek);
	}
	
	public function data_add_leader()
	{		
		$data['id_groups_leader'] = $this->cms_model->get_groups_leader($this->input->post('project_id')); 
		array_unshift($data['id_groups_leader'],array('id_item' => '--Pilih--', 'nama_item' => '--Pilih--'));
		echo json_encode($data);
	}
	
	public function data_edit()
	{				
		$data['id_groups_leader'] = $this->cms_model->get_groups_leader($this->input->post('project_id')); 
		array_unshift($data['id_groups_leader'],array('id_item' => '--Pilih--', 'nama_item' => '--Pilih--'));				
		
		$data['list'] = $this->cms_model->row_get_by_id($this->input->post('id'), $this->input->post('table_name'));						
		echo json_encode($data);
	}
			
	public function data_save_add()
	{						
        //set validation rules
		$table_name = $this->input->post('tbl_name');
		
		if($table_name == 'groups_leader'){
			$this->form_validation->set_rules('nama', 'Nama Group Leader', 'trim|required'); 			
		}else{
			$this->form_validation->set_rules('nama', 'Nama Leader', 'trim|required'); 
		}
		$this->form_validation->set_rules('singkatan', 'Singkatan', 'required');		
		
		if ($this->form_validation->run() == FALSE)
        {            
			//validation fails
			echo json_encode(array("status" => validation_errors()));            
        }
        else
        {    						
			if($table_name == 'groups_leader'){
				$additional_data = array(					
					'nama' => $this->input->post('nama'),
					'singkatan' => $this->input->post('singkatan'),
					'proyek_id' => $this->input->post('project_id')
					);
			}else{
				//leader
				$id_groups_leader = $this->input->post('id_groups_leader');	
				
				if($id_groups_leader != '--Pilih--'){									
					$additional_data = array(					
						'nama' => $this->input->post('nama'),
						'singkatan' => $this->input->post('singkatan'),
						'proyek_id' => $this->input->post('project_id'),
						'id_groups_leader' => $this->input->post('id_groups_leader')
						);		
				}else{								
					$additional_data = array(					
						'nama' => $this->input->post('nama'),
						'singkatan' => $this->input->post('singkatan'),
						'proyek_id' => $this->input->post('project_id'),
						'id_groups_leader' => '0'
					);
				}	
			}				           
			
			$insert = $this->cms_model->save($additional_data, $table_name);										
			echo json_encode(array("status" => TRUE, "table_name" => $table_name));
        }		
	}
	
	public function data_save_edit()
	{			
		//set validation rules
		$table_name = $this->input->post('tbl_name');
		
		if($table_name == 'groups_leader'){
			$this->form_validation->set_rules('nama', 'Nama Group Leader', 'trim|required'); 			
		}else{
			$this->form_validation->set_rules('nama', 'Nama Leader', 'trim|required'); 
		}
		$this->form_validation->set_rules('singkatan', 'Singkatan', 'required');
		
		
		if ($this->form_validation->run() == FALSE)
        {            
			//validation fails
			echo json_encode(array("status" => validation_errors()));            
        }
        else
        {    
			$id = $this->input->post('id');					
			
			if($table_name == 'groups_leader'){
				$additional_data = array(					
					'nama' => $this->input->post('nama'),
					'singkatan' => $this->input->post('singkatan'),
					'proyek_id' => $this->input->post('project_id')
					);
			}else{
				//leader
				$id_groups_leader = $this->input->post('id_groups_leader');	
				
				if($id_groups_leader != '--Pilih--'){									
					$additional_data = array(					
						'nama' => $this->input->post('nama'),
						'singkatan' => $this->input->post('singkatan'),
						'proyek_id' => $this->input->post('project_id'),
						'id_groups_leader' => $this->input->post('id_groups_leader')
						);		
				}else{								
					$additional_data = array(					
						'nama' => $this->input->post('nama'),
						'singkatan' => $this->input->post('singkatan'),
						'proyek_id' => $this->input->post('project_id'),
						'id_groups_leader' => '0'
					);
				}	
			}					
						
			if($this->cms_model->update(array("id" => $id), $additional_data, $table_name))
			{
				//berhasil update, lakukan update lainnya pada tabel user group dan user bidang								
				echo json_encode(array("status" => TRUE, "table_name" => $table_name));
			}else{
				//gagal update
				echo json_encode(array("status" => "Gagal update data"));
			}		
        }	
	}
	
	public function data_delete()
	{			
        $id = $this->input->post('id_delete_data');	
		$table_name = $this->input->post('tbl_name_del');	
		
		if($table_name == 'groups_leader'){
			$where =  array('id_groups_leader' => $id);
			$list_sub = $this->cms_model->query_get_by_criteria('leader', $where, '');	
			$n = count($list_sub);
		}else{
			//leader
			$n = 0;
		}
				
		if($n > 0){
			echo json_encode(array("status" => "Group Leader tidak bisa di hapus, memiliki Leader"));
		}else{	
			
			$this->cms_model->delete($table_name, array('id' => $id));		//hapus data
			echo json_encode(array("status" => TRUE, "table_name" => $table_name));																
		}
	}		
	/*
	public function data_edit_posisi()
	{
		$id = $this->input->post('id');
		$pos = $this->input->post('pos');
		$data['list'] = $this->cms_model->row_get_by_id($id, $this->table_name);
		
		$no_urut = $data['list']->no_urut;
		$ref_id = $data['list']->ref_id;
		if($ref_id == ''){
			$ref_id = NULL;
		}
		
		if($pos == 'up'){
			//naikan no urut n-1 menjadi n			
			$where =  array('tahun' => $data['list']->tahun,'pusat_id' => $data['list']->pusat_id,'ref_id' => $ref_id, "no_urut" => $no_urut-1);
			$update = $this->cms_model->update_using_set($where, array("no_urut" => $no_urut), $this->table_name);
			
			//update no urut n menjadi n-1			
			$update = $this->cms_model->update_using_set(array("id" => $id), array("no_urut" => "no_urut - 1"), $this->table_name);
		}else{
			//down			
			$where =  array('tahun' => $data['list']->tahun,'pusat_id' => $data['list']->pusat_id,'ref_id' => $ref_id, "no_urut" => $no_urut+1);
			$update = $this->cms_model->update_using_set($where, array("no_urut" => $no_urut), $this->table_name);
			
			//update no urut n menjadi n+1
			$update = $this->cms_model->update_using_set(array("id" => $id), array("no_urut" => "no_urut + 1"), $this->table_name);
		}
		
		echo json_encode(array("status" => TRUE));
	}
	
	//custom validation function for dropdown input
    function check_tahun()
    {		
		if($this->input->post('select_tahun') != '--Pilih--'){				
			if($this->input->post('select_tahun') != $this->input->post('tahun')){				
				$this->form_validation->set_message('check_tahun', 'Kolom %s tidak sama dengan kolom Tahun');
				return FALSE;
			}else{
				return TRUE;
			}
		}else{
			return TRUE;
		}		
		      
    }
	*/
	function _render_page($view, $data=null, $returnhtml=false)//I think this makes more sense
	{

		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);

		if ($returnhtml) return $view_html;//This will return html on 3rd argument being true
	}
}


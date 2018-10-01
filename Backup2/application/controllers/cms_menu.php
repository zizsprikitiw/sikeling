<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cms_menu extends CI_Controller {
	
	//Global variable
	var $table_name = 'functions';
	
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
		
		$this->_render_page('cms/cms_menu', $this->data);			
	}		
	
		
	public function data_list()
	{		
		$datatable_name = $this->table_name;
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
			
			if($main_item->direct_url == ''){
				$row[] = $main_item->url;
			}else{
				$row[] = $main_item->direct_url;
			}									
			
			//add status aktif
			if ($main_item->tampil == 1) {									
				$row[] = '<button class="btn btn-xs btn-success" onclick="data_edit_status('."'".$main_item->id."'".')">On</button>';
			}else{
				$row[] = '<button class="btn btn-xs btn-danger" onclick="data_edit_status('."'".$main_item->id."'".')">Off</button>';				
			}
						
			//add html for action  
			$str_aksi = '<div align="right">';
			
			//tambahkan tombol menu khusus untuk kegiatan, di kunci dari nama link
			if($main_item->url == 'proyek'){
				$str_aksi = $str_aksi.'<a class="btn btn-xs btn-primary" href="javascript:void()" title="Tambah Sub Menu" onclick="add_sub_proyek('."'".$main_item->id."'".')"><i class="fa fa-sitemap"></i></a>
				  ';
			}
			
			if($main_len > 1){
				if($i == 1){
					//urutan 1
					$str_aksi = $str_aksi.'<a class="btn btn-xs btn-default" href="javascript:void()" title="Turun posisi" onclick="data_edit_posisi('."'".$main_item->id."','down'".')"><i class="fa fa-chevron-down"></i></a>';
				}elseif($i == $main_len){
					//urutan akhir
					$str_aksi = $str_aksi.'<a class="btn btn-xs btn-default" href="javascript:void()" title="Naik posisi" onclick="data_edit_posisi('."'".$main_item->id."','up'".')"><i class="fa fa-chevron-up"></i></a>';
				}else{
					//urutan diantara
					$str_aksi = $str_aksi.'<a class="btn btn-xs btn-default" href="javascript:void()" title="Naik posisi" onclick="data_edit_posisi('."'".$main_item->id."','up'".')"><i class="fa fa-chevron-up"></i></a>
					<a class="btn btn-xs btn-default" href="javascript:void()" title="Turun posisi" onclick="data_edit_posisi('."'".$main_item->id."','down'".')"><i class="fa fa-chevron-down"></i></a>';
				}
			}
			$i++;
			
			$row[] = $str_aksi.'
					<a class="btn btn-xs btn-warning" href="javascript:void()" title="Edit" onclick="data_edit('."'".$main_item->id."'".')"><i class="fa fa-pencil"></i></a>
				  <a class="btn btn-xs btn-danger" href="javascript:void()" title="Hapus" onclick="data_delete('."'".$main_item->id."','".$main_item->nama."'".')"><i class="fa fa-times"></i></a></div>';
																			
			$data[] = $row;
						
			//cek sub menu
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
						
						if($sub_item->direct_url == ''){
							$row[] = $sub_item->url;
						}else{
							$row[] = $sub_item->direct_url;
						}	
						
						//add status aktif
						if ($sub_item->tampil == 1) {									
							$row[] = '<button class="btn btn-xs btn-success" onclick="data_edit_status('."'".$sub_item->id."'".')">On</button>';
						}else{
							$row[] = '<button class="btn btn-xs btn-danger" onclick="data_edit_status('."'".$sub_item->id."'".')">Off</button>';				
						}	
						
						//add html for action  						
						$str_aksi = '<div align="right">';
						
						if($sub_sub_len > 1){
							if($k == 1){
								//urutan 1
								$str_aksi = $str_aksi.'<a class="btn btn-xs btn-info" href="javascript:void()" title="Turun posisi" onclick="data_edit_posisi('."'".$sub_item->id."','down'".')"><i class="fa fa-chevron-down"></i></a>';
							}elseif($k == $sub_sub_len){
								//urutan akhir
								$str_aksi = $str_aksi.'<a class="btn btn-xs btn-info" href="javascript:void()" title="Naik posisi" onclick="data_edit_posisi('."'".$sub_item->id."','up'".')"><i class="fa fa-chevron-up"></i></a>';
							}else{
								//urutan diantara
								$str_aksi = $str_aksi.'<a class="btn btn-xs btn-info" href="javascript:void()" title="Naik posisi" onclick="data_edit_posisi('."'".$sub_item->id."','up'".')"><i class="fa fa-chevron-up"></i></a>
								<a class="btn btn-xs btn-info" href="javascript:void()" title="Turun posisi" onclick="data_edit_posisi('."'".$sub_item->id."','down'".')"><i class="fa fa-chevron-down"></i></a>';
							}
						}
						$k++;
						
						if($main_item->url == 'proyek'){
							$fungsi_edit = 'edit_sub_proyek';
						}else{
							$fungsi_edit = 'data_edit';
						}
						
						
						$row[] = $str_aksi.'
								<a class="btn btn-xs btn-warning" href="javascript:void()" title="Edit" onclick="'.$fungsi_edit.'('."'".$sub_item->id."'".')"><i class="fa fa-pencil"></i></a>
							  <a class="btn btn-xs btn-danger" href="javascript:void()" title="Hapus" onclick="data_delete('."'".$sub_item->id."','".$sub_item->nama."'".')"><i class="fa fa-times"></i></a></div>';
						
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
	
	public function data_add()
	{
		//fetch data from department and designation tables
        $data['ref_id'] = $this->cms_model->get_menu_parent($this->table_name);  
		array_unshift($data['ref_id'],array('id_item' => '--Pilih--', 'nama_item' => '--Pilih--'));		
				
		echo json_encode($data);
	}
	
	public function data_edit($id)
	{				
		$data['ref_id'] = $this->cms_model->get_menu_parent($this->table_name);  
		array_unshift($data['ref_id'],array('id_item' => '--Pilih--', 'nama_item' => '--Pilih--'));	
		
		$data['list'] = $this->cms_model->row_get_by_id($id, $this->table_name);
			
		echo json_encode($data);
	}
			
	public function data_save_add()
	{						
        //set validation rules
		$this->form_validation->set_rules('nama', 'Nama Menu', 'trim|required'); 
		$this->form_validation->set_rules('halaman', 'Judul Halaman', 'required');	
		//$this->form_validation->set_rules('url', 'Link Halaman', 'required');	
				
		if ($this->form_validation->run() == FALSE)
        {            
			//validation fails
			echo json_encode(array("status" => validation_errors()));            
        }
        else
        {    						
			if($this->input->post('ref_id') != '--Pilih--'){	
				$no_urut = $this->cms_model->get_max_no_urut($this->table_name, '', '', $this->input->post('ref_id'));   
				$no_urut = $no_urut + 1;
				
				$additional_data = array(					
					'nama' => $this->input->post('nama'),
					'halaman' => $this->input->post('halaman'),
					'url' => $this->input->post('url'),
					'no_urut' => $no_urut,
					'ref_id' => $this->input->post('ref_id'),
					'icon' => $this->input->post('icon'),
					'tampil' => '1',
					'direct_url' => $this->input->post('direct_url')					
					);		
			}else{
				$no_urut = $this->cms_model->get_max_no_urut($this->table_name, '', '', '');
				$no_urut = $no_urut + 1;
				
				$additional_data = array(					
					'nama' => $this->input->post('nama'),
					'halaman' => $this->input->post('halaman'),
					'url' => $this->input->post('url'),
					'tampil' => '1',
					'icon' => $this->input->post('icon'),
					'no_urut' => $no_urut,
					'direct_url' => $this->input->post('direct_url')
				);
			}			           
			
			$insert = $this->cms_model->save($additional_data, $this->table_name);										
			echo json_encode(array("status" => TRUE));
        }		
	}
	
	public function data_save_edit()
	{			
		//set validation rules				
		$this->form_validation->set_rules('nama', 'Nama Menu', 'trim|required'); 
		$this->form_validation->set_rules('halaman', 'Judul Halaman', 'required');			
		
		if ($this->form_validation->run() == FALSE)
        {            
			//validation fails
			echo json_encode(array("status" => validation_errors()));            
        }
        else
        {    
			$id = $this->input->post('id');											
			
			$additional_data = array(
				'nama' => $this->input->post('nama'),
				'halaman' => $this->input->post('halaman'),
				'url' => $this->input->post('url'),
				'icon' => $this->input->post('icon'),
				'direct_url' => $this->input->post('direct_url')
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
		$data['list'] = $this->cms_model->row_get_by_id($id, $this->table_name);
		
		$where =  array('ref_id' => $data['list']->id);
		$list_sub = $this->cms_model->query_get_by_criteria($this->table_name, $where, '');
		
		
		$n = count($list_sub);
		if($n > 0){
			echo json_encode(array("status" => "Menu tidak bisa di hapus, memiliki sub menu"));
		}elseif(($data['list']->url == "index") or ($data['list']->url == "proyek")){
			//menu index dan proyek tidak bisa dihapus, karena index halaman default, proyek halaman utama
			echo json_encode(array("status" => "Menu ".$data['list']->nama." tidak bisa di hapus, Halaman Utama"));
		}else{	
			$ref_id = $data['list']->ref_id;
			if($ref_id == ''){
				$ref_id = NULL;
			}
			
			$where =  array('ref_id' => $ref_id, "no_urut >" => $data['list']->no_urut);
			
			$update = $this->cms_model->update_using_set($where, array("no_urut" => "no_urut - 1"), $this->table_name);
			$this->cms_model->delete($this->table_name, array('id' => $id));		//hapus data
			echo json_encode(array("status" => TRUE));													
		}
							
	}		
	
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
			$where =  array('ref_id' => $ref_id, "no_urut" => $no_urut-1);
			$update = $this->cms_model->update_using_set($where, array("no_urut" => $no_urut), $this->table_name);
			
			//update no urut n menjadi n-1			
			$update = $this->cms_model->update_using_set(array("id" => $id), array("no_urut" => "no_urut - 1"), $this->table_name);
		}else{
			//down			
			$where =  array('ref_id' => $ref_id, "no_urut" => $no_urut+1);
			$update = $this->cms_model->update_using_set($where, array("no_urut" => $no_urut), $this->table_name);
			
			//update no urut n menjadi n+1
			$update = $this->cms_model->update_using_set(array("id" => $id), array("no_urut" => "no_urut + 1"), $this->table_name);
		}
		
		echo json_encode(array("status" => TRUE));
	}	
	
	public function data_edit_status($id)
	{			
		$menu = $this->cms_model->row_get_by_id($id, $this->table_name);
		//rubah status
		
		if($menu->tampil == 1){
			$status = 0;
		}else{
			$status = 1;
		}				
		
		if($this->cms_model->update(array("id" => $id), array('tampil' => $status), $this->table_name))
		{
			//berhasil update
			echo json_encode(array("status" => TRUE));
		}else{
			//gagal
			echo json_encode(array("status" => FALSE));
		}	
	}
	
	function load_tab_icon()
	{
		$data['icon_tabs'] = $this->cms_model->get_icon_parent();  		
				
		echo json_encode($data);
	}
	
	function icon_list()
	{
		$datatable_name = 'icon_list';
		$search_column = array('nama');
		$search_order = array('nama' => 'asc');
		$where =  array('ref_id' => $this->input->post('id_tabs'));
		$order_by = 'id asc';		
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];						
				
		foreach($list as $list_item){
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $list_item->nama;
			$row[] = '<i class="'.$list_item->nama.'" style="font-size:24px"></i>';			
								
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
	
	public function data_add_sub_proyek()
	{
		//fetch data from department and designation tables
        $data['button_id'] = $this->cms_model->get_button_menu();  		
				
		echo json_encode($data);
	}
	
	public function data_edit_sub_proyek($id)
	{							
		$data['button_id'] = $this->cms_model->get_button_menu(); 
		$data['list'] = $this->cms_model->row_get_by_id($id, $this->table_name);
			
		echo json_encode($data);
	}
	
	public function data_save_add_sub_proyek()
	{
		//set validation rules
		$this->form_validation->set_rules('nama', 'Nama Menu', 'trim|required'); 
		$this->form_validation->set_rules('halaman', 'Judul Halaman', 'required');			
				
		if ($this->form_validation->run() == FALSE)
        {            
			//validation fails
			echo json_encode(array("status" => validation_errors()));            
        }
        else
        {    									
			$no_urut = $this->cms_model->get_max_no_urut($this->table_name, '', '', $this->input->post('ref_id'));   
			$no_urut = $no_urut + 1;
			
			$additional_data = array(					
				'nama' => $this->input->post('nama'),
				'halaman' => $this->input->post('halaman'),
				'url' => $this->input->post('url'),
				'no_urut' => $no_urut,
				'ref_id' => $this->input->post('ref_id'),
				'icon' => $this->input->post('icon'),
				'tampil' => '1',
				'button_id' => $this->input->post('button_id')
				);		
				           
			
			$insert = $this->cms_model->save($additional_data, $this->table_name);										
			echo json_encode(array("status" => TRUE));
        }
	}
	
	public function data_save_edit_sub_proyek()
	{
		//set validation rules				
		$this->form_validation->set_rules('nama', 'Nama Menu', 'trim|required'); 
		$this->form_validation->set_rules('halaman', 'Judul Halaman', 'required');			
		
		if ($this->form_validation->run() == FALSE)
        {            
			//validation fails
			echo json_encode(array("status" => validation_errors()));            
        }
        else
        {    
			$id = $this->input->post('id_sub_proyek');											
			
			$additional_data = array(
				'nama' => $this->input->post('nama'),
				'halaman' => $this->input->post('halaman'),
				'url' => $this->input->post('url'),
				'icon' => $this->input->post('icon'),
				'button_id' => $this->input->post('button_id')
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
	
	function _render_page($view, $data=null, $returnhtml=false)//I think this makes more sense
	{

		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);

		if ($returnhtml) return $view_html;//This will return html on 3rd argument being true
	}
}


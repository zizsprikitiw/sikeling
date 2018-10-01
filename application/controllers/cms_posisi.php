<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cms_posisi extends CI_Controller {
	
	//Global variable
	var $table_name = 'posisi';
	
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
				
		$this->_render_page('cms/cms_posisi', $this->data);				
	}		
	
	public function data_list()
	{		
		//$search_column = array('nama','keterangan');
		//$search_order = array('nama' => 'desc');
		
		$datatable_name = $this->table_name;
		$search_column = array('nama','keterangan');
		$search_order = array('nama' => 'desc');
		$where =  '';
		$order_by = 'no_urut asc';		
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		
		//$list = $this->cms_model->get_datatables($this->table_name, $search_column, $search_order);
		$data = array();
		$no = $_POST['start'];
		$main_len = count($list);
		$i = 1;
		foreach ($list as $list_item) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $list_item->nama;
			$row[] = $list_item->keterangan;
			$row[] = $list_item->jenis_dokumen;								
			
			//add html for action  
			$str_aksi = '<div align="right">';
			if($main_len > 1){
				if($i == 1){
					//urutan 1
					$str_aksi = $str_aksi.'<a class="btn btn-xs btn-info" href="javascript:void()" title="Turun posisi" onclick="data_edit_posisi('."'".$list_item->id."','down'".')"><i class="fa fa-chevron-down"></i></a>';
				}elseif($i == $main_len){
					//urutan akhir
					$str_aksi = $str_aksi.'<a class="btn btn-xs btn-info" href="javascript:void()" title="Naik posisi" onclick="data_edit_posisi('."'".$list_item->id."','up'".')"><i class="fa fa-chevron-up"></i></a>';
				}else{
					//urutan diantara
					$str_aksi = $str_aksi.'<a class="btn btn-xs btn-info" href="javascript:void()" title="Naik posisi" onclick="data_edit_posisi('."'".$list_item->id."','up'".')"><i class="fa fa-chevron-up"></i></a>
					<a class="btn btn-xs btn-info" href="javascript:void()" title="Turun posisi" onclick="data_edit_posisi('."'".$list_item->id."','down'".')"><i class="fa fa-chevron-down"></i></a>';
				}
			}
			$i++;
			
			$row[] = $str_aksi.'
					<a class="btn btn-xs btn-primary" href="javascript:void()" title="Menu" onclick="posisi_menu('."'".$list_item->id."','".$list_item->nama."'".')"><i class="fa fa-sitemap"></i></a>
					<a class="btn btn-xs btn-warning" href="javascript:void()" title="Edit" onclick="data_edit('."'".$list_item->id."'".')"><i class="fa fa-pencil"></i></a>
				  <a class="btn btn-xs btn-danger" href="javascript:void()" title="Hapus" onclick="data_delete('."'".$list_item->id."','".$list_item->nama."'".')"><i class="fa fa-times"></i></a>';
																			
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
		
	public function data_edit($id)
	{							
		$data['list'] = $this->cms_model->row_get_by_id($id, $this->table_name);
		echo json_encode($data);
	}
	
	public function data_save_add()
	{					
        //set validation rules
		$this->form_validation->set_rules('nama', 'Nama Posisi', 'trim|required|is_unique[' . $this->table_name . '.nama]'); 
		$this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');
		$this->form_validation->set_rules('jenis_dokumen', 'Jenis Dokumen', 'trim');
				
		if ($this->form_validation->run() == FALSE)
        {            
			//validation fails
			echo json_encode(array("status" => validation_errors()));            
        }
        else
        {    
			$no_urut = $this->cms_model->get_max_no_urut_posisi();
			$no_urut = $no_urut + 1;
			
            //pass validation			
			$additional_data = array(				
				'nama' => $this->input->post('nama'),
				'keterangan'  => $this->input->post('keterangan'),
				'jenis_dokumen'  => $this->input->post('jenis_dokumen'),
				'no_urut' => $no_urut
			);
			
			$insert = $this->cms_model->save($additional_data, $this->table_name);										
			echo json_encode(array("status" => TRUE));
        }		
	}
	
	public function data_save_edit()
	{				
		$id = $this->input->post('id');
		$nama = $this->input->post('nama');
		$data_list = $this->cms_model->row_get_by_id($id, $this->table_name);		//get user data sebelumnya
		
        //set validation rules
		//cek cek validasi jika berbeda 
		if($data_list->nama != $nama){
			$this->form_validation->set_rules('nama', 'Nama Posisi', 'trim|required|is_unique[' . $this->table_name . '.nama]'); 
		}		
		$this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');
		$this->form_validation->set_rules('jenis_dokumen', 'Jenis Dokumen', 'trim');
		
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
				'keterangan'  => $this->input->post('keterangan'),
				'jenis_dokumen'  => $this->input->post('jenis_dokumen')
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
		
		$where =  array("no_urut >" => $data['list']->no_urut);
			
		$update = $this->cms_model->update_using_set($where, array("no_urut" => "no_urut - 1"), $this->table_name);
		$this->cms_model->delete($this->table_name, array('id' => $id));		//hapus data
		echo json_encode(array("status" => TRUE));					
	}		
	
	public function data_edit_posisi()
	{
		$id = $this->input->post('id');
		$pos = $this->input->post('pos');
		$data['list'] = $this->cms_model->row_get_by_id($id, $this->table_name);
		
		$no_urut = $data['list']->no_urut;		
		
		if($pos == 'up'){
			//naikan no urut n-1 menjadi n			
			$where =  array("no_urut" => $no_urut-1);
			$update = $this->cms_model->update_using_set($where, array("no_urut" => $no_urut), $this->table_name);
			
			//update no urut n menjadi n-1			
			$update = $this->cms_model->update_using_set(array("id" => $id), array("no_urut" => "no_urut - 1"), $this->table_name);
		}else{
			//down			
			$where =  array("no_urut" => $no_urut+1);
			$update = $this->cms_model->update_using_set($where, array("no_urut" => $no_urut), $this->table_name);
			
			//update no urut n menjadi n+1
			$update = $this->cms_model->update_using_set(array("id" => $id), array("no_urut" => "no_urut + 1"), $this->table_name);
		}
		
		echo json_encode(array("status" => TRUE));
	}
	
	public function menu_list()
	{
		//get user group menu list
		$id_posisi = $this->input->post('id_posisi');	
		$posisi_menu = $this->cms_model->posisi_menu($id_posisi);										

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
			
			$posisi_menu_id = $this->cms_model->menu_in_group($posisi_menu, $main_item->id);
			if($posisi_menu_id != 0){
				$row[] = '<button class="btn btn-xs btn-success" onclick="data_edit_status('."'".$main_item->id."','".$posisi_menu_id."'".')">On</button>';
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
						
						$posisi_menu_id = $this->cms_model->menu_in_group($posisi_menu, $sub_item->id);
						if($posisi_menu_id != 0){
							$row[] = '<button class="btn btn-xs btn-success" onclick="data_edit_status('."'".$sub_item->id."','".$posisi_menu_id."'".')">On</button>';
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
		$id_posisi = $this->input->post('id_posisi');	
		$id_menu = $this->input->post('id_menu');	
		$id_posisi_menu = $this->input->post('id_posisi_menu');
		
		if($id_posisi_menu == 0){
			//tambahkan pada authority
			//pass validation			
			$additional_data = array(				
				'functions_id' => $id_menu,
				'posisi_id'  => $id_posisi
			);
			
			$insert = $this->cms_model->save($additional_data, 'authority_project');			
		}else{
			//delete item authority
			$this->cms_model->delete('authority_project', array('id' => $id_posisi_menu));				
		}
		
		echo json_encode(array("status" => TRUE));
	}
	
	function _render_page($view, $data=null, $returnhtml=false)//I think this makes more sense
	{

		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);

		if ($returnhtml) return $view_html;//This will return html on 3rd argument being true
	}
}


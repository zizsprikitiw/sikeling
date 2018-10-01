<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cms_proyek extends CI_Controller {
	
	//Global variable
	var $table_name = 'proyek';
	
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
				
		$this->_render_page('cms/cms_proyek', $this->data);				
	}		
	
	public function data_init()
	{
		$data['filter_pusat'] = $this->cms_model->get_pusat();  
		$data['filter_tahun'] = $this->cms_model->get_tahun_proyek($this->table_name);  
		
		echo json_encode($data);
	}
	
	public function data_list()
	{		
		$datatable_name = $this->table_name;
		$search_column = array('nama','singkatan','tahun');
		$search_order = array('tahun' => 'asc');
		$where =  array('tahun' => $_POST['filter_tahun'], 'pusat_id' => $_POST['filter_pusat']);//$this->input->post('filter_pusat')
		$order_by = 'pusat_id asc, tahun asc, ref_id asc, no_urut asc';		
		
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
			$row[] = $main_item->nama;
			$row[] = $main_item->singkatan;
			$row[] = $main_item->tahun;									
			//add html for action  
			$str_aksi = '<div align="right">';
			if($main_len > 1){
				if($i == 1){
					//urutan 1
					$str_aksi = $str_aksi.'<a class="btn btn-xs btn-info" href="javascript:void()" title="Turun posisi" onclick="data_edit_posisi('."'".$main_item->id."','down'".')"><i class="fa fa-chevron-down"></i></a>';
				}elseif($i == $main_len){
					//urutan akhir
					$str_aksi = $str_aksi.'<a class="btn btn-xs btn-info" href="javascript:void()" title="Naik posisi" onclick="data_edit_posisi('."'".$main_item->id."','up'".')"><i class="fa fa-chevron-up"></i></a>';
				}else{
					//urutan diantara
					$str_aksi = $str_aksi.'<a class="btn btn-xs btn-info" href="javascript:void()" title="Naik posisi" onclick="data_edit_posisi('."'".$main_item->id."','up'".')"><i class="fa fa-chevron-up"></i></a>
					<a class="btn btn-xs btn-info" href="javascript:void()" title="Turun posisi" onclick="data_edit_posisi('."'".$main_item->id."','down'".')"><i class="fa fa-chevron-down"></i></a>';
				}
			}
			$i++;
			
			$row[] = $str_aksi.'
					<a class="btn btn-xs btn-warning" href="javascript:void()" title="Edit" onclick="data_edit('."'".$main_item->id."'".')"><i class="fa fa-pencil"></i></a>
				  <a class="btn btn-xs btn-danger" href="javascript:void()" title="Hapus" onclick="data_delete('."'".$main_item->id."','".$main_item->singkatan."'".')"><i class="fa fa-times"></i></a></div>';
																			
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
						$row[] = $sub_item->singkatan;
						$row[] = $sub_item->tahun;
						//add html for action  
						
						$str_aksi = '<div align="right">';
						
						if($sub_sub_len > 1){
							if($k == 1){
								//urutan 1
								$str_aksi = $str_aksi.'<a class="btn btn-xs btn-warning" href="javascript:void()" title="Turun posisi" onclick="data_edit_posisi('."'".$sub_item->id."','down'".')"><i class="fa fa-chevron-down"></i></a>';
							}elseif($k == $sub_sub_len){
								//urutan akhir
								$str_aksi = $str_aksi.'<a class="btn btn-xs btn-warning" href="javascript:void()" title="Naik posisi" onclick="data_edit_posisi('."'".$sub_item->id."','up'".')"><i class="fa fa-chevron-up"></i></a>';
							}else{
								//urutan diantara
								$str_aksi = $str_aksi.'<a class="btn btn-xs btn-warning" href="javascript:void()" title="Naik posisi" onclick="data_edit_posisi('."'".$sub_item->id."','up'".')"><i class="fa fa-chevron-up"></i></a>
								<a class="btn btn-xs btn-warning" href="javascript:void()" title="Turun posisi" onclick="data_edit_posisi('."'".$sub_item->id."','down'".')"><i class="fa fa-chevron-down"></i></a>';
							}
						}
						$k++;
						$row[] = $str_aksi.'
								<a class="btn btn-xs btn-warning" href="javascript:void()" title="Edit" onclick="data_edit('."'".$sub_item->id."'".')"><i class="fa fa-pencil"></i></a>
							  <a class="btn btn-xs btn-danger" href="javascript:void()" title="Hapus" onclick="data_delete('."'".$sub_item->id."','".$sub_item->singkatan."'".')"><i class="fa fa-times"></i></a></div>';
						
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
        $data['pusat'] = $this->cms_model->get_pusat(); 		
		$data['select_tahun'] = $this->cms_model->get_tahun_proyek($this->table_name);  
		array_unshift($data['select_tahun'],array('id_item' => '--Pilih--', 'nama_item' => '--Pilih--'));
		
		echo json_encode($data);
	}
	
	public function data_edit($id)
	{				
		$data['pusat'] = $this->cms_model->get_pusat(); 		
		$data['select_tahun'] = $this->cms_model->get_tahun_proyek($this->table_name);  
		array_unshift($data['select_tahun'],array('id_item' => '--Pilih--', 'nama_item' => '--Pilih--'));
		
		$data['list'] = $this->cms_model->row_get_by_id($id, $this->table_name);
		
		if($data['list']->ref_id != ''){			
     		$sub_data = $this->cms_model->row_get_by_id($data['list']->ref_id, $this->table_name);	//get parent judul
			$data['ref_id_tahun'] = $sub_data->tahun;			
			//sub judul
			$data['ref_id'] = $this->cms_model->get_singkatan_proyek($this->table_name, $sub_data->tahun, $sub_data->pusat_id);
		}
		
		echo json_encode($data);
	}
	
	public function select_sub_judul()
	{							
		$data['ref_id'] = $this->cms_model->get_singkatan_proyek($this->table_name, $_POST['select_tahun'], $_POST['select_pusat']);		
		echo json_encode($data);
	}
		
	public function data_save_add()
	{						
        //set validation rules
		$this->form_validation->set_rules('nama', 'Judul Proyek', 'trim|required'); 
		$this->form_validation->set_rules('singkatan', 'Singkatan Judul', 'required');	
		$this->form_validation->set_rules('tahun', 'Tahun', 'required|is_numeric');	
		$this->form_validation->set_rules('select_tahun', 'Tahun Sub Judul', 'callback_check_tahun');							
		
		if ($this->form_validation->run() == FALSE)
        {            
			//validation fails
			echo json_encode(array("status" => validation_errors()));            
        }
        else
        {    
			//$select_tahun = $this->input->post('select_tahun');			
			$select_tahun = $this->input->post('select_tahun');	
			
			if($select_tahun != '--Pilih--'){	
				$no_urut = $this->cms_model->get_max_no_urut($this->table_name, $select_tahun, $this->input->post('pusat'), $this->input->post('ref_id'));
				//$no_urut = $this->cms_model->get_max_urut_judul_proyek($this->table_name, $select_tahun , $this->input->post('pusat'),  $this->input->post('ref_id'));
				$no_urut = $no_urut + 1;
				
				$additional_data = array(
					'pusat_id' => $this->input->post('pusat'),
					'nama' => $this->input->post('nama'),
					'singkatan' => $this->input->post('singkatan'),
					'tahun' => $this->input->post('tahun'),
					'no_urut' => $no_urut,
					'ref_id' => $this->input->post('ref_id')
					);		
			}else{
				$no_urut = $this->cms_model->get_max_no_urut($this->table_name, $this->input->post('tahun'), $this->input->post('pusat'), '');
				//$no_urut = $this->cms_model->get_max_urut_judul_proyek($this->table_name, $this->input->post('tahun'), $this->input->post('pusat'), '');
				$no_urut = $no_urut + 1;
				
				$additional_data = array(
					'pusat_id' => $this->input->post('pusat'),
					'nama' => $this->input->post('nama'),
					'singkatan' => $this->input->post('singkatan'),
					'tahun' => $this->input->post('tahun'),
					'no_urut' => $no_urut
				);
			}			           
			
			$insert = $this->cms_model->save($additional_data, $this->table_name);										
			echo json_encode(array("status" => TRUE));
        }		
	}
	
	public function data_save_edit()
	{			
		//set validation rules
		$this->form_validation->set_rules('nama', 'Judul Proyek', 'trim|required'); 
		$this->form_validation->set_rules('singkatan', 'Singkatan Judul', 'required');	
		$this->form_validation->set_rules('tahun', 'Tahun', 'required|is_numeric');	
		$this->form_validation->set_rules('select_tahun', 'Tahun Sub Judul', 'callback_check_tahun');
		
		
		if ($this->form_validation->run() == FALSE)
        {            
			//validation fails
			echo json_encode(array("status" => validation_errors()));            
        }
        else
        {    
			$id = $this->input->post('id');					
			
			//cek jika tahun berubah, jika parent sub judul berubah, harus update no urut <<<<<<<========================TAMBAHKAN
			
			$additional_data = array(
				'nama' => $this->input->post('nama'),
				'singkatan' => $this->input->post('singkatan'),
				'tahun' => $this->input->post('tahun')
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
			echo json_encode(array("status" => "Judul proyek tidak bisa di hapus, memiliki sub judul"));
		}else{	
			$ref_id = $data['list']->ref_id;
			if($ref_id == ''){
				$ref_id = NULL;
			}
			
			$where =  array('tahun' => $data['list']->tahun,'pusat_id' => $data['list']->pusat_id,'ref_id' => $ref_id, "no_urut >" => $data['list']->no_urut);
			
			$update = $this->cms_model->update_using_set($where, array("no_urut" => "no_urut - 1"), $this->table_name);
			$this->cms_model->delete($this->table_name, array('id' => $id));		//hapus data
			echo json_encode(array("status" => TRUE));
				
			/*if($this->cms_model->update_using_set($where, array("no_urut" => "no_urut - 1"), $this->table_name))
			{				
				$this->cms_model->delete($this->table_name, array('id' => $id));		//hapus data
				echo json_encode(array("status" => TRUE));
			}else{
				//gagal update
				echo json_encode(array("status" => "Gagal hapus data", "urut" => "cou=".$n."++".$data['list']->no_urut."++"));
			}*/											
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
	
	function _render_page($view, $data=null, $returnhtml=false)//I think this makes more sense
	{

		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);

		if ($returnhtml) return $view_html;//This will return html on 3rd argument being true
	}
}


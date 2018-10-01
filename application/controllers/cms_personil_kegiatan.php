<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cms_personil_kegiatan extends CI_Controller {
	
	//Global variable
	var $table_name = 'users_posisi';
	var $table_name_view = 'v_users_posisi';
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
				
		$this->_render_page('cms/cms_personil_kegiatan', $this->data);				
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
	
	public function get_judul_proyek()
	{
		$judul_proyek = $this->cms_model->row_get_by_id($_POST['filter_judul'], $this->table_proyek);	
		echo json_encode($judul_proyek);
	}
	
	public function data_list_personil()
	{
		$datatable_name = $this->table_name_view;
		$search_column = array('lower(nama_user)');
		$search_order = '';
		$where =  array('proyek_id' => $_POST['filter_judul']);
		$order_by = '';				
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = 0;//$_POST['start'];
		foreach ($list as $list_item) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $list_item->nama_user;
			
			$posisi = '';
			if($list_item->leader_nama != ''){
				//cetak leader dan enginering staff
				$posisi = $list_item->nama_posisi.' '.$list_item->leader_nama.' ('.$list_item->wp.')';
			}elseif($list_item->groups_leader_nama != ''){
				$posisi = $list_item->nama_posisi.' '.$list_item->groups_leader_nama.' ('.$list_item->wp.')';
			}elseif($list_item->nama_posisi != ''){
				$posisi = $list_item->nama_posisi;
			}else{
				$posisi = $list_item->nama_struktural;
			}						
			
			$row[] = $posisi;											
			
			//add html for action
			$row[] = '<a class="btn btn-xs btn-warning" href="javascript:void()" title="Edit" onclick="data_edit('."'".$list_item->id."'".')"><i class="fa fa-pencil"></i></a>
				  <a class="btn btn-xs btn-danger" href="javascript:void()" title="Hapus" onclick="data_delete('."'".$list_item->id."','".$list_item->nama_user."','".$posisi."'".')"><i class="fa fa-times"></i></a>';
																			
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
		
	public function select_posisi_kegiatan()
	{
		$id_posisi_as = $this->input->post('id_posisi_as');
		
		if($id_posisi_as == 0){
			//posisi dalam kegiatan sebagai struktural
			$data['id_posisi_kegiatan'] = $this->cms_model->get_struktural();
		}else{
			//posisi dalam kegiatan sebagai posisi, id_posisi_as == 1
			$data['id_posisi_kegiatan'] = $this->cms_model->get_posisi();
		}					
		
		echo json_encode($data);
	}
	
	public function select_posisi_ld_gl()
	{
		$id_posisi_kegiatan = $this->input->post('id_posisi_kegiatan');
		$project_id = $this->input->post('filter_judul');
		$user_id = $this->input->post('user_id');
				
		//cek have sub atau tidak pada table posisi
		$posisi_all = $this->cms_model->row_get_by_id($id_posisi_kegiatan, 'posisi');
		
		if($posisi_all->have_sub != ''){
			$data['have_sub'] = 'true';
			
			if($posisi_all->have_sub_fix != ''){
				//cari jumlah item leader/group_leader pada tabel v_users_posisi
				$list_item = $this->cms_model->get_posisi_for_personil($posisi_all->have_sub, $project_id, $id_posisi_kegiatan, $user_id);
				$len_item =	count($list_item);	
				if($len_item > 0){
					$data['is_empty'] = 'false';
					$rows = array();
					
					foreach($list_item as $item){
						$rows[] = array('id_item' => $item->id_item, 'nama_item' => $item->nama_item);
					}
					$data['id_posisi_gl_ld'] = $rows;
				}else{
					$data['is_empty'] = str_replace("_"," ",$posisi_all->have_sub);
					$data['id_posisi_gl_ld'] = array('id_item' => '0', 'nama_item' => '--Pilih--');	
				}
			}else{				
				//selain leader dan group leader/enginering staff
				$list_item = $this->cms_model->query_get_by_criteria($posisi_all->have_sub, "proyek_id = ".$project_id, "singkatan asc");//.$project_id				
				$len_item =	count($list_item);			
				if($len_item > 0){
					$data['is_empty'] = 'false';					
					$rows = array();
					
					foreach($list_item as $item){
						$rows[] = array('id_item' => $item->id, 'nama_item' => $item->nama." (".$item->singkatan.")");
					}
					$data['id_posisi_gl_ld'] = $rows;	
				}else{
					//empty list item
					$data['is_empty'] = str_replace("_"," ",$posisi_all->have_sub);
					$data['id_posisi_gl_ld'] = array('id_item' => '0', 'nama_item' => '--Pilih--');					
				}
			}
		}else{
			//tidak mengacu pada table
			$data['have_sub'] = 'false';	
			$data['id_posisi_gl_ld'] = array('id_item' => '0', 'nama_item' => '--Pilih--');
			$data['is_empty'] = 'false';		
		}		
		
		echo json_encode($data);
	}
		
	public function data_list_users()
	{
		$datatable_name = "users";
		$search_column = array('lower(nama)');
		$search_order = array('nama' => 'asc');		
		$is_where = $_POST['is_where'];
		
		if($is_where == 'true'){
			$where =  array("nama ~*" => ".*".$_POST['nama_user'].".*");
		}else{
			$where =  "";
		}
		
		$order_by = "nama asc";				
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $list_item) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $list_item->nama;
			$row[] = $list_item->nip;										
			
			//add html for action
			$row[] = '<a class="btn btn-xs btn-primary" href="javascript:void()" title="Pilih" onclick="data_pick('."'".$list_item->id."','".$list_item->nama."'".')">Pilih</a>';
																			
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
				
	public function data_edit()
	{	
		$id_users_posisi = $this->input->post('id_users_posisi');			
		$data['list'] = $this->cms_model->row_get_by_id($id_users_posisi, $this->table_name_view);
		
		if($data['list']->struktural_id == ''){
			//posisi dalam kegiatan sebagai struktural
			$data['id_posisi_kegiatan'] = $this->cms_model->get_struktural();
		}else{
			//posisi dalam kegiatan sebagai posisi, id_posisi_as == 1
			$data['id_posisi_kegiatan'] = $this->cms_model->get_posisi();
		}		
							
		echo json_encode($data);
	}
				
	public function data_save_add()
	{						
        //set validation rules	
		$this->form_validation->set_rules('nama', 'Nama Personil', 'trim|required|xss_clean|callback_check_personil');											
		
		if ($this->form_validation->run() == FALSE)
        {            
			//validation fails
			echo json_encode(array("status" => validation_errors()));            
        }
        else
        {    	
			$id_posisi_as = $this->input->post('id_posisi_as');
			if($id_posisi_as == '0'){
				//struktural
				$additional_data = array(					
					'proyek_id' => $this->input->post('proyek_id'),
					'user_id' => $this->input->post('user_id'),
					'struktural_id' => $this->input->post('id_posisi_kegiatan')
				);	
			}else{	
				//posisi	
				$id_posisi_kegiatan = $this->input->post('id_posisi_kegiatan');		
				$id_posisi_gl_ld = $this->input->post('id_posisi_gl_ld');									
				$posisi_desc = $this->cms_model->row_get_by_id($id_posisi_kegiatan, 'posisi');
	
				if($posisi_desc->have_sub != ''){
					//group leader/leader/enginering staff
					if($posisi_desc->have_sub_fix != ''){						
						$list_item = $this->cms_model->row_get_by_id($id_posisi_gl_ld, $posisi_desc->have_sub);
						$wp = $list_item->singkatan;						
					}else{							
						//selain leader dan group leader/enginering staff
						$list_item = $this->cms_model->row_get_by_id($id_posisi_gl_ld, $posisi_desc->have_sub);
						$wp = $list_item->singkatan;
						$tbl_sub = $posisi_desc->have_sub;		//nama tabel leader atau group leader
						
						$row_posisi = $this->cms_model->query_get_by_criteria($this->table_name_view, "proyek_id = ".$this->input->post('proyek_id')." AND posisi_id = ".$id_posisi_kegiatan." AND ".$tbl_sub."_id = ".$id_posisi_gl_ld, "wp asc");
						
						$n_posisi = count($row_posisi) + 1;
						$wp = $wp.$n_posisi;						
					}
				}else{
					//tidak punya sub, //selain group leader/leader/enginering staff
					$wp = "";
				}
				
				$additional_data = array(					
					'proyek_id' => $this->input->post('proyek_id'),
					'user_id' => $this->input->post('user_id'),
					'posisi_id' => $this->input->post('id_posisi_kegiatan'),
					'wp' => $wp
				);		
								
				////END posisi		
			}
					           			
			$insert = $this->cms_model->save($additional_data, $this->table_name);										
			echo json_encode(array("status" => TRUE));
        }		
	}
	
	public function data_save_edit()
	{			
		//set validation rules	
		$this->form_validation->set_rules('nama', 'Nama Personil', 'trim|required|xss_clean|callback_check_personil');			
		
		if ($this->form_validation->run() == FALSE)
        {            
			//validation fails
			echo json_encode(array("status" => validation_errors()));            
        }
        else
        {    
			$id = $this->input->post('id');											
			$additional_data = array(					
				'user_id' => $this->input->post('user_id')
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
		$list_item = $this->cms_model->row_get_by_id($id, $this->table_name_view);		
		
		if($list_item->posisi_id != ""){
			//posisi, bukan struktural			
			$posisi_desc = $this->cms_model->row_get_by_id($list_item->posisi_id, "posisi");	
							
			if($posisi_desc->have_sub != ''){
				//group leader/leader/enginering staff
				if($posisi_desc->have_sub_fix == ''){
					//edit dulu					
					$tbl_sub = $posisi_desc->have_sub;		//nama tabel leader atau group leader
					$wp_ref = $list_item->wp;				//nama wp untuk referensi
					$str_obj = $tbl_sub."_id";
					$tbl_sub_id = $list_item->$str_obj; 	//id leader/group leader
					$str_obj = $tbl_sub."_singkatan";
					$tbl_sub_wp = $list_item->$str_obj; 	//singkatan leader atau group leader
					
					$row_posisi = $this->cms_model->query_get_by_criteria($this->table_name_view, "proyek_id = ".$this->input->post('proyek_id_del')." AND posisi_id = ".$list_item->posisi_id." AND ".$tbl_sub."_id = ".$tbl_sub_id, "wp asc");
																				
					if(count($row_posisi) > 1){
						//jika lebih dar 1 lakukan update urutan no wp
						$update = 'false';
						$test = "";
						foreach($row_posisi as $row_item){
							if($update == 'true'){								
								$str_no = str_replace($tbl_sub_wp,"",$row_item->wp);
								$no = strval($str_no)-1;
								$wp = $tbl_sub_wp.$no;									
								
								//lakukan update wp																		
								$additional_data = array(					
									'wp' => $wp
									);
								$this->cms_model->update(array("id" => $row_item->id), $additional_data, $this->table_name);
							}
							
							if($row_item->wp == $wp_ref){
								$update = 'true';								 
							}																					
						}
						
					}										
				}//END have_sub_fix
			}//END have_sub
		}//END posisi_id
																														
		$this->cms_model->delete($this->table_name, array('id' => $id));		//hapus data
		echo json_encode(array("status" => TRUE));																	
	}	
	
	//custom validation function for dropdown input
    function check_personil()
    {		
		$user_id = $this->input->post('user_id');
		$nama = $this->input->post('nama');
				
		if(($nama == '') || ($user_id == '0') || ($user_id == '')){
			$this->form_validation->set_message('check_personil', 'Pilih %s dari tabel user');
			return FALSE;		
		}else{
			$row_user = $this->cms_model->row_get_by_criteria("users", "id = ".$user_id." AND nama = '".$nama."'");
			if(count($row_user) > 0){
				return TRUE;
			}else{
				//tidak ditemukan
				$this->form_validation->set_message('check_personil', 'Pilih %s sesuai tabel user');
				return FALSE;		
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

/*			
	public function get_nama_struktural()
	{
		$struktural_id = $this->input->post('id_posisi_kegiatan');		
		$pusat_id = $this->input->post('filter_pusat');
		$tahun_awal = $this->input->post('filter_tahun');
		$tahun = mdate("%Y", time());
		
		if($tahun_awal == $tahun){
			$tahun_akhir = 0;
		}else{
			$tahun_akhir = $tahun_awal;
		}
				
		$data['list'] = $this->cms_model->get_nama_struktural($pusat_id, $struktural_id, $tahun_awal, $tahun_akhir);
		if(count($data['list']) == 0){
			$data['list'][] = array('user_id' => '', 'nama_user' => '');
		}
		
		echo json_encode($data);
	}*/
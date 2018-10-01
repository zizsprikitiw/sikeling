<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rekap_lap_kegiatan extends CI_Controller {
	
	//Global variable
	var $table_name = 'note';
	var $table_note_inbox_outbox = 'note_inbox_outbox';
	var $table_name_view = 'v_users_note';
	var $table_proyek = 'proyek';	
	
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
				
		$this->_render_page('rekap_lap_kegiatan', $this->data);				
	}		
	
	public function data_init()
	{
		$data['filter_pusat'] = $this->cms_model->get_singkatan_pusat();  
		$data['filter_tahun'] = $this->cms_model->get_tahun_proyek($this->table_proyek); 					
		$data['filter_bulan'] = $this->cms_model->get_list_bulan();  
		array_unshift($data['filter_bulan'],array('id_item' => '0', 'nama_item' => '--Pilih--'));
			
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

		$data['filter_kegiatan'] = $dataList;
		echo json_encode($data);
	}
	
	public function data_list()
	{	
		$is_admin = $this->cms_model->user_is_admin();		
		$chkSearch = $this->input->post('chkSearch');
		$where['active_flag'] = '1';					
						
		if($chkSearch[0] != "")
		{			
			foreach($chkSearch as $chkSearch_item)						
			{
				if($chkSearch_item == "tahun"){
					$where['tahun'] =  $this->input->post('filter_tahun');					
				}
				if($chkSearch_item == "bulan"){
					$where['bulan'] =  $this->input->post('filter_bulan');	
				}
				if($chkSearch_item == "nama"){
					$where['nama_user ~*'] = strtolower($this->input->post('nama'));
				}	
				if($chkSearch_item == "kegiatan"){
					$where['proyek_id'] = strtolower($this->input->post('filter_kegiatan'));
				}		
				if($chkSearch_item == "jenis"){
					$where['status'] = strtolower($this->input->post('jenis_laporan'));
				}		
			}
		}else{
			$where['id'] = '0';	
		}	
		
		//print_r($where);
		//echo "COUNT==".count($where);
		$datatable_name = $this->table_name_view;	
		$search_column = array('lower(nama_user)');
		$search_order = array('nama_user' => 'asc');
		$order_by = 'tanggal desc';								
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $list_item) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $list_item->nama_user;
			
			if($list_item->judul != ""){
				$title = $list_item->judul;
				$row[] = '<a href="javascript:void()" onclick="show_laporan('."'".$list_item->id."'".')" >'.$list_item->judul.'</a>';	
			}else{
				$title = $list_item->filename;
				$row[] = '<a href="javascript:void()" onclick="show_laporan('."'".$list_item->id."'".')" >'.$list_item->filename.'</a>';
			}						
																									
			$row[] = date_format(date_create($list_item->tahun."-".$list_item->bulan."-01"),"M Y");
			$row[] = date_format(date_create($list_item->tanggal),"j M Y, \J\a\m G:i");		
			
			if($is_admin){
				//punya akses administrator
				$row[] = '<a href="'.site_url("/".$this->router->class."/download_file/".$list_item->id).'" class="btn btn-xs btn-success" title="Download" ><i class="fa fa-download"></i></a>
						<a class="btn btn-xs btn-danger" href="javascript:void()" title="Hapus" onclick="data_delete('."'".$list_item->id."','".$title."'".')"><i class="fa fa-times"></i></a>';	
			}else{
				$row[] = '<a href="'.site_url("/".$this->router->class."/download_file/".$list_item->id).'" class="btn btn-xs btn-success" title="Download" ><i class="fa fa-download"></i></a>';
			}	
				
																						
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
	
	public function download_file($note_id)
	{				
		$note_row = $this->cms_model->row_get_by_id($note_id, $this->table_name_view);				
		$user_id = $note_row->user_id;
		$note_file = $note_row->filename;
		$note_file_server = $note_row->filename_server;		
		$folder_note = $this->cms_model->get_folder_laporan($note_row->proyek_id, $note_row->posisi_id, $note_row->user_id);
			
		ob_clean(); 		
		$data = $folder_note.$note_file_server;
		//$data = file_get_contents($data); //assuming my file is on localhost
		$data = $this->_url_get_contents($data);
		force_download($note_file ,$data); 
		exit();
	}		
	
	function _url_get_contents ($Url) {
	    if (!function_exists('curl_init')){ 
	        die('CURL is not installed!');
	    }
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $Url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $output = curl_exec($ch);
	    curl_close($ch);
	    return $output;
	}
	
	function show_laporan()
	{
		$note_id = $this->input->post('note_id');	
		$note_row = $this->cms_model->row_get_by_id($note_id, $this->table_name_view);				
		$note_file = $note_row->filename_server;						
		$folder_note = $this->cms_model->get_folder_laporan($note_row->proyek_id, $note_row->posisi_id, $note_row->user_id);
		$data['note_title'] = "File Laporan: ".$note_row->filename."<br />Tanggal Laporan: ".date_format(date_create($note_row->tahun."-".$note_row->bulan."-01"),"M Y");
				
		$file_template = FCPATH."assets\angular_pdf\pdf_loader.php";		
		$file_content = file_get_contents($file_template);		
		$vars = array("{pdf_url}" => $folder_note.$note_file);
		$file_content = strtr($file_content, $vars);
		$new_filename = mdate("%Y%m%d-%H%i%s", $_SERVER['REQUEST_TIME']).'.php';		
		$file_template = FCPATH."assets\angular_pdf\pdf_loader_".$new_filename;
		$result = write_file($file_template, $file_content);	
							
		$data['note_filename_url'] = base_url()."assets/angular_pdf/pdf_loader_".$new_filename;
		$data['note_filename_path'] = $file_template;		
		echo json_encode($data);	
	}	
	
	function show_laporan_delete()
	{
		$pdf_file = $this->input->post('pdf_file');			
		if($pdf_file != ""){
			unlink($pdf_file);
		}	
		echo json_encode(array("status" => TRUE));	
	}	
	
	public function data_delete()
	{			
        $note_id = $this->input->post('id_delete_data');						
		$note_row = $this->cms_model->row_get_by_id($note_id, $this->table_name);
		
		//hapus table note_inbox_outbox		
		$this->cms_model->delete($this->table_note_inbox_outbox, array('note_id' => $note_id));	
		
		//hapus file
		if($note_row->filename_server != ""){
			$users_posisi_id = $note_row->users_posisi_id;
			$list = $this->cms_model->row_get_by_id($users_posisi_id, 'users_posisi');	
			$user_id = $list->user_id;
			
			if(! is_null($list->posisi_id)){
			$posisi_id = $list->posisi_id;
			}else{
				$posisi_id = $list->struktural_id.'_struktural';
			}
			
			$upload_path = $this->cms_model->set_folder_laporan($list->proyek_id, $posisi_id, $user_id);																		
			$upload_file = $upload_path.$note_row->filename_server;
			
			if(is_file($upload_file)){
				unlink($upload_file);
			}						
		}
		
		//hapus note
		$this->cms_model->delete($this->table_name, array('id' => $note_id));		
		
		echo json_encode(array("status" => TRUE));
	}
	
	function _render_page($view, $data=null, $returnhtml=false)//I think this makes more sense
	{

		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);

		if ($returnhtml) return $view_html;//This will return html on 3rd argument being true
	}
}


<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_masuk extends CI_Controller {
	
	//Global variable
	var $table_name = 'v_note_inbox_outbox';
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
				
		$this->_render_page('laporan_masuk', $this->data);				
	}		
	
	public function data_init()
	{
		$data['filter_tahun'] = $this->cms_model->get_tahun_proyek($this->table_proyek); 					
		$data['filter_bulan'] = $this->cms_model->get_list_bulan();  
		array_unshift($data['filter_bulan'],array('id_item' => '0', 'nama_item' => '--Pilih--'));
			
		echo json_encode($data);
	}
		
	public function data_list()
	{	
		$chkSearch = $this->input->post('chkSearch');
		$where['tahun'] =  $this->input->post('filter_tahun');
		$where['user_id_penerima'] = $this->session->userdata('user_id');					
						
		if($chkSearch[0] != "")
		{			
			foreach($chkSearch as $chkSearch_item)						
			{				
				if($chkSearch_item == "bulan"){
					$where['bulan'] =  $this->input->post('filter_bulan');	
				}
				if($chkSearch_item == "nama"){
					$where['nama_user_pengirim ~*'] = strtolower($this->input->post('nama'));
				}							
			}
		}
			
		//print_r($where);		
		$datatable_name = $this->table_name;	
		$search_column = '';
		$search_order = '';
		$order_by = 'tgl_kirim desc';								
		
		$list = $this->cms_model->get_datatables_where($datatable_name, $search_column, $search_order, $where, $order_by);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $list_item) {
			$no++;
			$row = array();
			$row[] = $no;
						
			$pengirim = $list_item->nama_user_pengirim;				
			
			if(! is_null($list_item->nama_struktural_pengirim)){
				$jabatan = $list_item->nama_struktural_pengirim;
			}else{
				$jabatan = $list_item->nama_posisi_pengirim;
				
				if(! is_null($list_item->groups_leader_nama_pengirim)){
					$jabatan = $jabatan.' '.$list_item->groups_leader_nama_pengirim.' ('.$list_item->groups_leader_singkatan_pengirim.')';
				}elseif(! is_null($list_item->leader_nama_pengirim)){
					$jabatan = $jabatan.' '.$list_item->leader_nama_pengirim.' ('.$list_item->leader_singkatan_pengirim.')';
				}
			}				
			
			$row[] = $pengirim.'<br /> ['.$jabatan.']';
			
			if($list_item->judul == ''){
				$row[] = '<a href="javascript:void()" onclick="show_laporan('."'".$list_item->note_id."'".')" >'.$list_item->filename.'</a>';																								
			}else{
				$row[] = '<a href="javascript:void()" onclick="show_laporan('."'".$list_item->note_id."'".')" >'.$list_item->judul.'</a>';	
			}
						
			$row[] = date_format(date_create($list_item->tahun."-".$list_item->bulan."-01"),"M Y");
			$row[] = date_format(date_create($list_item->tgl_kirim),"j M Y, \J\a\m G:i");	
			
			if(is_null($list_item->approval_date)){
				$row[] = '<a class="btn btn-xs btn-warning" href="javascript:void()" title="Approval" onclick="approval_laporan('."'".$list_item->id."','".$pengirim."','".$list_item->judul."','".$list_item->note_id."'".')"><i class="fa fa-check"></i> Approve</a>';			
			}else{
				$row[] = date_format(date_create($list_item->approval_date),"j M Y, \J\a\m G:i");			
			}
			
			// if($list_item->file_akses == '1'){
				// $row[] = '<a href="'.site_url("/".$this->router->class."/download_file/".$list_item->note_id).'" class="btn btn-xs btn-success" title="Download" ><i class="fa fa-download"></i></a>';
			// }else{
				// $row[] = '';
			// }
			$row[] = '<a href="'.site_url("/".$this->router->class."/download_file/".$list_item->note_id).'" class="btn btn-xs btn-success" title="Download" ><i class="fa fa-download"></i></a>';
			
																														
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
	
	public function approval_laporan()
	{			
        $id = $this->input->post('id_note_inbox');								
		
		if($this->cms_model->update(array("id" => $id), array('approval_date'  => 'now()'), 'note_inbox_outbox'))
		{
			//berhasil update, 
			echo json_encode(array("status" => TRUE));
		}else{
			//gagal update
			echo json_encode(array("status" => "Gagal update data"));
		}					
	}	
	
	function _render_page($view, $data=null, $returnhtml=false)//I think this makes more sense
	{

		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);

		if ($returnhtml) return $view_html;//This will return html on 3rd argument being true
	}
}


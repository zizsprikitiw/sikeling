<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cms_model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
	}		
	
	function get_bidang()     
	{
		$query = $this->db->query("select id as id_item,CONCAT(nama,' (',nama_pusat,')') as nama_item from v_bidang order by nama_pusat asc, nama asc");
		
		if ($query->num_rows > 0){				
			return $query->result();						
		}
	}
	
	function get_users_groups()     
	{
		$query = $this->db->query("select id as id_item, name as nama_item from groups order by name asc");
		
		if ($query->num_rows > 0){				
			return $query->result();						
		}
	}		
	
	function get_fungsional()     
	{
		$query = $this->db->query("select id as id_item, nama as nama_item from fungsional order by nama asc");
		
		if ($query->num_rows > 0){				
			return $query->result();						
		}
	}	
	
	function get_struktural()     
	{
		$query = $this->db->query("select id as id_item, nama as nama_item from struktural order by eselon asc");
		
		if ($query->num_rows > 0){				
			return $query->result();						
		}
	}	
	
	function get_posisi()     
	{
		$query = $this->db->query("select id as id_item, nama as nama_item from posisi order by no_urut asc");
		
		if ($query->num_rows > 0){				
			return $query->result();						
		}
	}	
	
	function get_posisi_all()     
	{		
		$this->db->flush_cache();
		$this->db->from('posisi');
		$this->db->order_by('no_urut asc');
		$query = $this->db->get();								
		return $query->result();			
	}	
	
	function get_pusat()     
	{
		$query = $this->db->query("select id as id_item, nama as nama_item from pusat order by nama asc");
		
		if ($query->num_rows > 0){				
			return $query->result();						
		}
	}
	
	function get_singkatan_pusat()     
	{
		$query = $this->db->query("select id as id_item, singkatan as nama_item from pusat order by nama asc");
		
		if ($query->num_rows > 0){				
			return $query->result();						
		}
	}
	
	function get_approval_type()     
	{
		$query = $this->db->query("select id as id_item, nama as nama_item from approval_type");
		
		if ($query->num_rows > 0){				
			return $query->result();						
		}
	}	
	
	function get_tahun_proyek($table_name)     
	{
		$query = $this->db->query("select distinct tahun as id_item, tahun as nama_item from ".$table_name." order by tahun desc");
		
		if ($query->num_rows > 0){				
			return $query->result();						
		}
	}
	
	function get_singkatan_proyek($table_name, $tahun, $pusat_id)
	{		
		$query = $this->db->query("select id as id_item, singkatan as nama_item from ".$table_name." where pusat_id=".$pusat_id." and tahun=".$tahun." and ref_id IS NULL order by no_urut");
		
		if ($query->num_rows > 0){				
			return $query->result();						
		}
	}
	
	function get_klasifikasi_laporan()     
	{
		$query = $this->db->query("select id as id_item, nama as nama_item from klasifikasi_laporan");
		
		if ($query->num_rows > 0){				
			return $query->result();						
		}
	}
		
	function get_max_no_urut($table_name, $tahun, $pusat_id, $ref_id)     
	{		
		$this->db->flush_cache();
		$this->db->select_max('no_urut', 'max_no_urut');
		if($pusat_id != ''){$this->db->where('pusat_id', $pusat_id); }
		if($tahun != ''){$this->db->where('tahun', $tahun); }
		if($ref_id == ''){
			$this->db->where('ref_id IS NULL', null,false);
		}else{
			$this->db->where('ref_id', $ref_id);
		}
				
		$query = $this->db->get($table_name);							
		
		if ($query->num_rows > 0){	
			$hasil = $query->result();
							
			if($hasil[0]->max_no_urut == ''){
				return '0';
			}else{
				return $hasil[0]->max_no_urut;					
			}			
		}
	}
	
	function get_menu_parent($table_name)
	{
		$query = $this->db->query("select id as id_item, nama as nama_item from ".$table_name." where ref_id IS NULL and url<>'proyek' order by no_urut asc");
		
		if ($query->num_rows > 0){				
			return $query->result();						
		}
	}
	
	function get_icon_parent()
	{
		$query = $this->db->query("select id as id_item, nama as nama_item from icon_list where ref_id IS NULL order by nama asc");
		
		if ($query->num_rows > 0){				
			return $query->result();						
		}
	}
	
	function get_button_menu()
	{		
		$this->db->flush_cache();
		$this->db->select('id as id_item, name as nama_item, class');
		$this->db->from('button_menu');
		$this->db->order_by('id asc');
		$query = $this->db->get();
								
		return $query->result();
	}
	
	function user_group_menu($id)
	{
		$query = $this->db->query("select id, functions_id from authority where groups_id IN (".$id.")");
		return $query->result();																			
	}
	
	function posisi_menu($id)
	{		
		$this->db->select('id, functions_id');
		$this->db->from('authority_project');
		$this->db->where('posisi_id = '.$id);
		$query = $this->db->get();								
		return $query->result();								
	}
	
	function struktural_menu($id)
	{
		$this->db->select('id, functions_id');
		$this->db->from('authority_struktural');
		$this->db->where('struktural_id = '.$id);
		$query = $this->db->get();								
		return $query->result();								
	}
	
	function fungsional_menu($id)
	{
		$this->db->select('id, functions_id');
		$this->db->from('authority_fungsional');
		$this->db->where('fungsional_id = '.$id);
		$query = $this->db->get();								
		return $query->result();								
	}
	
	function get_groups_for_user($user_id)     
	{
		$this->db->select('id, group_id');
		$this->db->from('users_groups');
		$this->db->where('user_id = '.$user_id);
		$query = $this->db->get();								
		return $query->result();		
	}
	
	function get_groups_leader($id)
	{		
		$this->db->select("id as id_item, CONCAT(nama,' (',singkatan,')') as nama_item, singkatan", false);		
		$this->db->from('groups_leader');
		$this->db->where('proyek_id = '.$id);
		$this->db->order_by('singkatan asc');
		$query = $this->db->get();								
		return $query->result();								
	}	
	
	function get_year_proyek_by_user($id)
	{
		$this->db->distinct();	
		$this->db->select('tahun');	
		$this->db->from('users_posisi');		
		$this->db->join('proyek', 'users_posisi.proyek_id = proyek.id');
		$this->db->where('users_posisi.user_id = '.$id);
		$this->db->order_by('tahun desc');
		$query = $this->db->get();		
		return $query->result();
	}
	
	function get_proyek_beranda($id, $start_year, $end_year)
	{
		$query = $this->db->query("select up.proyek_id as proyek_id, p.nama as nama_posisi, pr.nama as nama_proyek, pr.tahun, up.wp, gl.nama as nama_gl, gl.singkatan as singkatan_gl, ld.nama as nama_ld, ld.singkatan as singkatan_ld from users_posisi up join proyek pr on up.proyek_id=pr.id left join posisi p on up.posisi_id=p.id left join groups_leader gl on up.wp=gl.singkatan and up.proyek_id=gl.proyek_id left join leader ld on (up.wp=ld.singkatan or substring(up.wp from 1 for 4)=ld.singkatan) and up.proyek_id=ld.proyek_id where up.user_id=".$id." and (pr.tahun>=".$start_year." and pr.tahun<=".$end_year.") order by pr.tahun desc, pr.no_urut asc, p.no_urut asc");
		
		if ($query->num_rows > 0){				
			return $query->result();						
		}
	}
	
	function get_proyek_button($id, $project_id)
	{
		$query = $this->db->query("select distinct ap.functions_id, fn.nama, fn.halaman, fn.url, fn.icon, fn.button_id, fn.no_urut, bm.name as button_name, bm.class as button_class from authority_project ap join functions fn on ap.functions_id=fn.id join button_menu bm on fn.button_id=bm.id where ap.posisi_id in (select up.posisi_id from users_posisi up where up.user_id=".$id." and up.proyek_id=".$project_id.") and fn.button_id is not null and fn.tampil='1' order by fn.no_urut");
		
		if ($query->num_rows > 0){				
			return $query->result();						
		}
	}	
	
	function get_no_urut_posisi_by_user_posisi($users_posisi_id)
	{
		$query = $this->db->query("select up.*, p.no_urut from users_posisi up join posisi p on up.posisi_id=p.id where up.id=".$users_posisi_id);				

		return $query->row();	
	}
	
	private function _get_datatables_query($table_name, $search_column, $search_order)
	{
		$this->db->from($table_name);

		$i = 0;
	
		foreach ($search_column as $item) 
		{
			if($_POST['search']['value'])
				($i==0) ? $this->db->like($item, strtolower($_POST['search']['value'])) : $this->db->or_like($item, strtolower($_POST['search']['value']));
			$column[$i] = $item;
			$i++;
		}
		
		if(isset($_POST['order']))
		{
			$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($search_order))
		{
			$order = $search_order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables($table_name, $search_column, $search_order)
	{
		$this->db->flush_cache();
		$this->_get_datatables_query($table_name, $search_column, $search_order);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered($table_name, $search_column, $search_order)
	{
		$this->_get_datatables_query($table_name, $search_column, $search_order);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($table_name)
	{
		$this->db->from($table_name);
		return $this->db->count_all_results();
	}

	public function query_get_all($table_name)
	{
		$this->db->flush_cache();
		$this->db->from($table_name);
		$query = $this->db->get();
		return $query->result();
	}

	public function row_get_by_id($id, $table_name)
	{
		$this->db->flush_cache();
		$this->db->from($table_name);
		$this->db->where('id',$id);
		$query = $this->db->get();

		return $query->row();
	}
	
	public function row_get_by_criteria($table_name, $where)
	{
		$this->db->flush_cache();
		$this->db->from($table_name);
		$this->db->where($where);
		$query = $this->db->get();

		return $query->row();
	}
	
	public function query_get_by_criteria($table_name, $where, $order)
	{
		$this->db->flush_cache();
		$this->db->from($table_name);
		if($where != ''){
			$this->db->where($where); 
		}
		
		if($order != ''){
			$this->db->order_by($order); 
		}
		
		$query = $this->db->get();		
		return $query->result();
	}
	
	public function save($data, $table_name)
	{
		$this->db->insert($table_name, $data);
		$id_insert = $this->db->insert_id();	
		
		$this->db->insert("log", array('user_id' => $this->session->userdata('user_id'),'waktu' => 'now()' ,'address' => 'INSERT '.$table_name.' id='.$id_insert));
		return $id_insert; /*$this->db->insert_id();			*/
	}

	public function update($where, $data, $table_name)
	{		
		$this->db->insert("log", array('user_id' => $this->session->userdata('user_id'),'waktu' => 'now()' , 'address' => 'UPDATE '.$table_name.' where '.http_build_query($where)));
		
		$this->db->update($table_name, $data, $where);				
		return $this->db->affected_rows();				
	}
	
	public function update_using_set($where, $data, $table_name)
	{
		$this->db->insert("log", array('user_id' => $this->session->userdata('user_id'),'waktu' => 'now()' , 'address' => 'UPDATE '.$table_name.' where '.http_build_query($where)));
		
		$this->db->set(key($data),$data[key($data)],FALSE);
		$this->db->where($where);
		$this->db->update($table_name);			
		return $this->db->affected_rows();			
	}

	public function delete($table_name, $where)
	{		
		$this->db->delete($table_name, $where);	
		$this->db->insert("log", array('user_id' => $this->session->userdata('user_id'),'waktu' => 'now()' , 'address' => 'DELETE '.$table_name.' where '.http_build_query($where)));
	}

	private function _get_datatables_query_where($table_name, $search_column, $search_order, $where, $order_by)
	{
		$this->db->from($table_name);
		if($where != ''){
			$this->db->where($where); 
		}
		
		$i = 0;
		
		if(isset($order_by) && ($order_by != '')){					
			//$order = $order_by;
			$this->db->order_by($order_by);			
		}
		
		if(isset($search_column) && ($search_column != ''))
		{			
			foreach($search_column as $item) 
			{
				if($_POST['search']['value'])
					($i==0) ? $this->db->like($item, strtolower($_POST['search']['value'])) : $this->db->or_like($item, strtolower($_POST['search']['value']));
				$column[$i] = $item;
				$i++;
			}
		}
		
		/*
		if(isset($_POST['order']))
		{
			$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($search_order))
		{
			$order = $search_order;
			$this->db->order_by(key($order), $order[key($order)]);
		}*/
	}
	
	function get_datatables_where($table_name, $search_column, $search_order, $where, $order_by)
	{
		$this->db->flush_cache();
		$this->_get_datatables_query_where($table_name, $search_column, $search_order, $where, $order_by);		
		
		if(isset($_POST['length'])){
			if($_POST['length'] != -1){
				$this->db->limit($_POST['length'], $_POST['start']);
			}
		}
				
		$query = $this->db->get();
		return $query->result();
	}
	
	function count_filtered_where($table_name, $search_column, $search_order, $where, $order_by)
	{
		$this->_get_datatables_query_where($table_name, $search_column, $search_order, $where, $order_by);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all_where($table_name, $where)
	{
		$this->db->from($table_name);
		if($where != ''){
			$this->db->where($where); 
		}
		
		return $this->db->count_all_results();
	}
	
	function get_user_menu($url_page)
	{
		//get user group menu
		$group_id = $this->session->userdata('group_id');
		$user_menu = $this->user_group_menu($group_id);		//get user group menu
		
		$menus = array();
		foreach($user_menu as $item){
			$menus[] = $item->functions_id;
		}
		
		//get struktural menu
		$struktural_id = $this->session->userdata('struktural_id');
		$user_menu = $this->struktural_menu($struktural_id);		//get user group menu
		
		//$menus = array();
		foreach($user_menu as $item){
			$menus[] = $item->functions_id;
		}
		
		//get fungsional menu
		$fungsional_id = $this->session->userdata('fungsional_id');
		$user_menu = $this->fungsional_menu($fungsional_id);		//get user group menu
		
		//$menus = array();
		foreach($user_menu as $item){
			$menus[] = $item->functions_id;
		}
		
		//get posisi menu
		$list_temp = explode(",", $this->session->userdata('posisi_id'));
		$posisi_list = array();		
		foreach($list_temp as $item){			
			$posisi_list[] = $item;
		}				
		$posisi_menu = $this->get_posisi_menu($posisi_list);
		
		foreach($posisi_menu as $item){
			$menus[] = $item->functions_id;
		}
			
		array_unique($menus);	//removes duplicates
		
		//get list menu by authority
		$this->db->distinct();		
		$this->db->from('functions');		
		$this->db->where('tampil', 1); 		
		$this->db->where_in('id', $menus);		
		$this->db->order_by('ref_id desc, no_urut asc'); 				
		$query = $this->db->get();		
		$list_menu = $query->result();
		
		$page_title = '';
		$page_icon = '';
		$menu_len = count($list_menu);
		$data = array();		
		$n = -1;
		for($i=0; $i<$menu_len; $i++){			
			if(trim($list_menu[$i]->ref_id) == ''){
				//ditemukan parent menu											
				//cek if selected		
				$n++;				
				$parentId = $n;
				$row = array();
				$row['id'] = $list_menu[$i]->id;
				$row['nama'] = $list_menu[$i]->nama;
				$row['halaman'] = $list_menu[$i]->halaman;
				
				if($list_menu[$i]->direct_url == ''){
					$row['url'] = site_url($list_menu[$i]->url);
				}else{
					$row['url'] = $list_menu[$i]->direct_url;
				}
				
				//$row['url'] = $list_menu[$i]->url;
				$row['ref_id'] = $list_menu[$i]->ref_id;
				$row['icon'] = $list_menu[$i]->icon;
				$row['open'] = 'false';	
				$row['has_sub'] = 'false';					
				$data[$n] = $row;
				
				if(trim($list_menu[$i]->url) == $url_page){					
					$data[$n]['open'] = 'true';
					$page_title = $list_menu[$i]->halaman;	
					$page_icon = $list_menu[$i]->icon;
				}
				
				//cek jika menu kegiatan
				if($list_menu[$i]->id == 2){
					//menu kegiatan
					$mnu_proyek = $this->get_tahun_proyek_menu('proyek');
					$mnu_proyek_len = count($mnu_proyek);									
					
					for($j=0; $j<$mnu_proyek_len; $j++){
						$n++;						
						$row = array();
						$row['id'] = '0';
						$row['nama'] = $mnu_proyek[$j]['nama_item'];
						$row['halaman'] = $list_menu[$i]->halaman;
						$row['url'] = site_url($list_menu[$i]->url)."/year/".$mnu_proyek[$j]['id_item'];
						$row['ref_id'] = $list_menu[$i]->id;
						$row['icon'] = $list_menu[$i]->icon;
						$row['open'] = 'false';		
						$row['has_sub'] = 'false';							
						$data[$n] = $row;
						
						$data[$parentId]['has_sub'] = 'true';
							
						if(trim($list_menu[$i]->url) == $url_page){
							$data[$parentId]['open'] = 'true';	
							$page_title = $list_menu[$i]->halaman;
							$page_icon = $list_menu[$i]->icon;
						}
					}										
				}else{
					//menu lainnya
					for($j=0; $j<$menu_len; $j++){
						if($list_menu[$j]->ref_id == $list_menu[$i]->id){
							//ditemukan sub menu, masukan ke array						
							//masukan sub menu
							$n++;						
							$row = array();
							$row['id'] = $list_menu[$j]->id;
							$row['nama'] = $list_menu[$j]->nama;
							$row['halaman'] = $list_menu[$j]->halaman;
							
							if($list_menu[$j]->direct_url == ''){
								$row['url'] = site_url($list_menu[$j]->url);
							}else{
								$row['url'] = $list_menu[$j]->direct_url;
							}
				
							//$row['url'] = $list_menu[$j]->url;
							$row['ref_id'] = $list_menu[$j]->ref_id;
							$row['icon'] = $list_menu[$j]->icon;
							$row['open'] = 'false';		
							$row['has_sub'] = 'false';							
							$data[$n] = $row;
							
							$data[$parentId]['has_sub'] = 'true';
							
							if(trim($list_menu[$j]->url) == $url_page){
								$data[$parentId]['open'] = 'true';	
								$page_title = $list_menu[$j]->halaman;
								$page_icon = $list_menu[$i]->icon;
							}
						}
					}//end for sub menu
				}
																		
			}//end if			
		}//end for menu utama	
		
		$output = array(
			"page_title" => $page_title,
			"page_icon" => $page_icon,
			"user_menu" => $data,
				);
		return $output;
	}
	
	function menu_in_group($user_group_menu, $menu_id)
	{				
		if(is_array($user_group_menu) && count($user_group_menu) > 0){
			//ada data			
			$findit = 0;
			foreach($user_group_menu as $item){
				if($item->functions_id == $menu_id){
					$findit = $item->id;
					break;
				}
			}
			
			return $findit;						
		}else{
			return 0;
		}
	}
	
	function get_posisi_menu($posisi_id)
	{
		$this->db->distinct();	
		$this->db->select('functions_id');	
		$this->db->from('authority_project');	
		$this->db->where_in('posisi_id', $posisi_id);			
		$query = $this->db->get();
		
		return $query->result();		
	}
	
	function get_tahun_proyek_menu($table_name)     
	{
		$query = $this->db->query("select distinct tahun from ".$table_name." order by tahun desc limit 3");
			
		$list = $query->result();
		$data = array();
		foreach($list as $list_item){
			$data[] = array('id_item' => $list_item->tahun,'nama_item' => $list_item->tahun);
		}
		
		$data[] = array('id_item' => 'all','nama_item' => 'Lihat Semua');				
		return $data;		
	}
		
	function get_menu_proyek_all($table_name, $where, $order_by)
	{						
		$this->db->from($table_name);		
		$this->db->where($where); 				
		$this->db->order_by($order_by); 				
		$query = $this->db->get();				
		
		return  $query->result();
	}	
	
	function get_query_rows($table_name, $is_distinct, $select, $where, $where_in_field, $where_in_array, $order_by, $group_by, $limit)
	{	
		$this->db->flush_cache();
		$this->db->select($select);				
		$this->db->from($table_name);	
		
		if($is_distinct == "true"){
			$this->db->distinct();
		}
		
		if($where != ''){
			$this->db->where($where); 	
		}
		
		if($where_in_field != ''){
			$this->db->where_in($where_in_field, $where_in_array); 	
		}	
		
		if($order_by != ''){
			$this->db->order_by($order_by); 
		}			
		
		if($group_by != ''){
			$this->db->group_by($group_by);	
		}
		
		if($limit != ''){
			$this->db->limit($limit);
		}		
					
		$query = $this->db->get();				
		
		return  $query->result();
	}
	
	private function _get_datatables_query_join($select, $table_name, $search_column, $search_order, $where, $order_by, $join_table, $join_id, $group_by)
	{		
		if($select != ''){
			$this->db->select($select);
		}
		
		$this->db->from($table_name);
		if($join_table != ''){
			$this->db->join($join_table, $join_id, 'left');
		}
		
		if($where != ''){
			$this->db->where($where); 
		}
		
		$i = 0;
		
		if(isset($order_by)){						
			$this->db->order_by($order_by);			
		}
		
		if(isset($group_by)){						
			$this->db->group_by($group_by);			
		}
		
		foreach ($search_column as $item) 
		{
			if($_POST['search']['value'])
				($i==0) ? $this->db->like($item, strtolower($_POST['search']['value'])) : $this->db->or_like($item, strtolower($_POST['search']['value']));
			$column[$i] = $item;
			$i++;
		}		
	}
	
	function get_datatables_join($select, $table_name, $search_column, $search_order, $where, $order_by, $join_table, $join_id, $group_by)
	{		
		$this->db->flush_cache();
		$this->_get_datatables_query_join($select, $table_name, $search_column, $search_order, $where, $order_by, $join_table, $join_id, $group_by);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}
	
	function count_filtered_join($select, $table_name, $search_column, $search_order, $where, $order_by, $join_table, $join_id, $group_by)
	{
		$this->_get_datatables_query_join($select, $table_name, $search_column, $search_order, $where, $order_by, $join_table, $join_id, $group_by);
		$query = $this->db->get();
		return $query->num_rows();
	}

	function count_all_join($table_name, $where, $join_table, $join_id)
	{
		$this->db->from($table_name);
		if($join_table != ''){
			$this->db->join($join_table, $join_id, 'left');
		}
		
		if($where != ''){
			$this->db->where($where); 
		}
		
		return $this->db->count_all_results();
	}
	
	function get_config_value($parameter_name)
	{
		$this->db->flush_cache();
		$this->db->from('config_proyek');
		$this->db->where("parameter_name = '".$parameter_name."'");
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result()[0]->parameter_value;								
	}
	
	function set_folder_laporan($project_id, $posisi_id, $user_id)
	{
		$document_root = $this->get_config_value('DOCUMENT_ROOT_PROJECT');
		$upload_path = $document_root."\\".$project_id."\\".$posisi_id."\\".$user_id."\\";		
		return $upload_path;
	}
	
	function get_folder_laporan($project_id, $posisi_id, $user_id)
	{
		$document_url = $this->get_config_value('DOCUMENT_URL_PROJECT');					
		$download_path = $document_url."/".$project_id."/".$posisi_id."/".$user_id."/";		
		return $download_path;
	}
	
	function set_folder_logbook($user_id)
	{
		$document_root = $this->get_config_value('DOCUMENT_ROOT_LOGBOOK');
		$upload_path = $document_root."/".$user_id."/";		
		return $upload_path;
	}
	
	function get_folder_logbook($user_id)
	{	
		$document_url = $this->get_config_value('DOCUMENT_URL_LOGBOOK');
		
		if($user_id == ""){
			$download_path = $document_url."/";
		}else{
			$download_path = $document_url."/".$user_id."/";		
		}		
		
		return $download_path;
	}
	
	function set_folder_berita($proyek_id)
	{
		$document_root = $this->get_config_value('DOCUMENT_ROOT_BERITA');
		
		if($proyek_id == "0"){
			$upload_path = $document_root."/";
		}else{
			$upload_path = $document_root."/".$proyek_id."/";	
		}
			
		return $upload_path;
	}
	
	function get_folder_berita($proyek_id)
	{	
		$document_url = $this->get_config_value('DOCUMENT_URL_BERITA');
		
		if($proyek_id == "0"){
			$download_path = $document_url."/";
		}else{
			$download_path = $document_url."/".$proyek_id."/";		
		}		
		
		return $download_path;
	}
	
	function set_folder_tugas($user_id)
	{
		$document_root = $this->get_config_value('DOCUMENT_ROOT_TUGAS');
		$upload_path = $document_root."/".$user_id."/";		
		return $upload_path;
	}
	
	function get_folder_tugas($user_id)
	{	
		$document_url = $this->get_config_value('DOCUMENT_URL_TUGAS');
		
		if($user_id == ""){
			$download_path = $document_url."/";
		}else{
			$download_path = $document_url."/".$user_id."/";		
		}		
		
		return $download_path;
	}
	
	function get_list_bulan()
	{
		$list_bulan = array(array("id_item" => '1', "nama_item" => 'Januari'),
					 array("id_item" => '2', "nama_item" => 'Februari'),
					 array("id_item" => '3', "nama_item" => 'Maret'),
					 array("id_item" => '4', "nama_item" => 'April'),
					 array("id_item" => '5', "nama_item" => 'Mei'),
					 array("id_item" => '6', "nama_item" => 'Juni'),
					 array("id_item" => '7', "nama_item" => 'Juli'),
					 array("id_item" => '8', "nama_item" => 'Agustus'),
					 array("id_item" => '9', "nama_item" => 'September'),
					 array("id_item" => '10', "nama_item" => 'Oktober'),
					 array("id_item" => '11', "nama_item" => 'Nopember'),
					 array("id_item" => '12', "nama_item" => 'Desember'));	
		return $list_bulan;
	}
	
	function bytesToSize($bytes, $precision = 2)
	{  
		$kilobyte = 1024;
		$megabyte = $kilobyte * 1024;
		$gigabyte = $megabyte * 1024;
		$terabyte = $gigabyte * 1024;
	   
		if (($bytes >= 0) && ($bytes < $kilobyte)) {
			return $bytes . ' B';
	 
		} elseif (($bytes >= $kilobyte) && ($bytes < $megabyte)) {
			return round($bytes / $kilobyte, $precision) . ' KB';
	 
		} elseif (($bytes >= $megabyte) && ($bytes < $gigabyte)) {
			return round($bytes / $megabyte, $precision) . ' MB';
	 
		} elseif (($bytes >= $gigabyte) && ($bytes < $terabyte)) {
			return round($bytes / $gigabyte, $precision) . ' GB';
	 
		} elseif ($bytes >= $terabyte) {
			return round($bytes / $terabyte, $precision) . ' TB';
		} else {
			return $bytes . ' B';
		}
	}
	
	function get_max_no_urut_note($users_posisi_id, $field_name, $field_name_as, $where)     
	{		
		$this->db->select_max($field_name, $field_name_as);				
		$this->db->where($where); 		
		$query = $this->db->get('note');							
		
		if ($query->num_rows > 0){	
			$hasil = $query->result();
							
			if($hasil[0]->$field_name_as == ''){
				return '0';
			}else{
				return $hasil[0]->$field_name_as;					
			}			
		}else{
			return '0';
		}
	}
	
	function get_max_no_urut_posisi()     
	{		
		$this->db->select_max('no_urut', 'max_no_urut');	
		$query = $this->db->get('posisi');							
		
		if ($query->num_rows > 0){	
			$hasil = $query->result();
							
			if($hasil[0]->max_no_urut == ''){
				return '0';
			}else{
				return $hasil[0]->max_no_urut;					
			}			
		}else{
			return '0';
		}
	}
	
	function get_max_level_approval($approval_type_id, $field_out_name, $field_out_val)     
	{		
		$this->db->select_max('level', 'max_level');
		$this->db->where(array('approval_type_id' => $approval_type_id,'out_'.$field_out_name.'_id' => $field_out_val)); 						
		$query = $this->db->get('approval_level');							
		
		if ($query->num_rows > 0){	
			$hasil = $query->result();
							
			if($hasil[0]->max_level == ''){
				return '0';
			}else{
				return $hasil[0]->max_level;					
			}			
		}else{
			return '0';
		}
	}
	
	function get_select_tahun()
	{
		$tahun = mdate("%Y", time());
		$list_tahun = array();
		for($i=2000; $i <= $tahun; $i++){
			$list_tahun[] = array("id_item" => $i, "nama_item" => $i);
			//$row[] = $i;
		}
		
		return $list_tahun;
	}
	
	function get_select_year()
	{
		$tahun = mdate("%Y", time());
		$list_tahun = array();
		for($i=$tahun; $i >= $tahun-5; $i--){
			$list_tahun[] = array("id_item" => $i, "nama_item" => $i);
			//$row[] = $i;
		}
		
		return $list_tahun;
	}
	
	function get_nama_struktural($pusat_id, $struktural_id, $tahun_awal, $tahun_akhir)
	{
		$query = $this->db->query("select * from v_users_struktural where pusat_id=".$pusat_id." and struktural_id=".$struktural_id." and (tahun_awal<=".$tahun_awal." and tahun_akhir>=".$tahun_akhir.")");
		return $query->result();
	}
	
	function get_posisi_for_personil($table_name, $project_id, $posisi_id, $user_id)
	{
		$query = $this->db->query("select id as id_item,CONCAT(nama,' (',singkatan,')') as nama_item from ".$table_name." where proyek_id=".$project_id." and id not in (select ".$table_name."_id from v_users_posisi where proyek_id=".$project_id." and posisi_id=".$posisi_id." and user_id<>".$user_id.") order by singkatan asc");
		return $query->result();																			
	}
	
	function get_tahun_logbook($jenis, $user_id)     
	{
		$where_user_id = "";
		if($user_id != ""){
			$where_user_id = " and user_id = ".$user_id;
		}
		
		$query = $this->db->query("select distinct tahun as id_item, tahun as nama_item from log_book where status=".$jenis.$where_user_id." order by tahun desc");
		
		//if ($query->num_rows > 0){				
			return $query->result();						
		//}
	}
	
	function get_tahun_tugas($user_id)     
	{
		$query = $this->db->query("select distinct tahun as id_item, tahun as nama_item from v_tugas where user_id=".$user_id." order by tahun desc");
		
		//if ($query->num_rows > 0){				
			return $query->result();						
		//}
	}
	
	function get_tahun_proyek_by_user($user_id)
	{
		$query = $this->db->query("select distinct tahun as id_item, tahun as nama_item from v_users_proyek where user_id=".$user_id." and tahun is not null order by tahun desc");
		
		if ($query->num_rows > 0){				
			return $query->result();						
		}				
	}
	
	function get_proyek_by_user($user_id, $tahun, $pusat_id)
	{
		$query = $this->db->query("select distinct proyek_id as id_item, judul_proyek as nama_item from v_users_proyek where user_id=".$user_id." and tahun=".$tahun." and pusat_id=".$pusat_id);
		
		if ($query->num_rows > 0){				
			return $query->result();						
		}				
	}
	
	function get_tahun_log()     
	{		
		$query = $this->db->query("select distinct extract(YEAR from waktu) as id_item, extract(YEAR from waktu) as nama_item from log order by extract(YEAR from waktu) desc");
		return $query->result();
	}
	
	function user_is_admin()
	{
		$is_admin = false;
		$groups_id = explode(",",$this->session->userdata('group_id'));
		foreach($groups_id as $id_item){	
			if(trim($id_item) == '1'){
				$is_admin = true;
			}					
		}	
		
		return $is_admin;
	}
	
	function get_agenda_ruangan()     
	{
		$this->db->select('*');
		$this->db->from('agenda_ruangan');
		$query = $this->db->get();								
		return $query->result();		
	}
	
	function get_jenis_usulan()     
	{
		$this->db->select('*');
		$this->db->from('status_kepegawaian_jenisusulan');
		$query = $this->db->get();								
		return $query->result();		
	}
	 
}

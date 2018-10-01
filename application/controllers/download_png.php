<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Download_png extends CI_Controller {
function __construct()
	{
		parent::__construct();
		$this->load->library(array('ion_auth','form_validation'));
		$this->load->helper(array('url','language'));
		$this->load->helper('date');

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
			if(!file_exists('application/views/download_png.php'))
			{
				echo "Sorry, file does not exist";
			}
			else
			{
				//echo "coba";
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');		
				$this->data['user_menu'] = $this->cms_model->get_user_menu($this->uri->rsegment(1));
				$this->data['user'] = $this->ion_auth->user()->row();
				//$this->load->view('download_png');
				$this->_render_page('download_png', $this->data);
			}
	}
function _render_page($view, $data=null, $returnhtml=false)//I think this makes more sense
	{

		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);

		if ($returnhtml) return $view_html;//This will return html on 3rd argument being true
	}

}
?>
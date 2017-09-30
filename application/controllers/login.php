<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class login extends CI_Controller {
	public function index(){
		$file['page']	= "dashboard/dashboard";
		$this->load->view('themes',$file);
	}
	
	public function login_ok(){
		$Username	= $this->input->post('Username');
		$Password	= $this->input->post('Password');
		if($Username==$Password && $Username=="admin"){
			$this->session->set_userdata('login','ok');
		}
		redirect('');
	}
	
	public function login_no(){
		$this->session->set_userdata('login','no');
		redirect('');
	}
}

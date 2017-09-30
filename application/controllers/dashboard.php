<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class dashboard extends CI_Controller {
	public function index(){
		$file['page']	= "dashboard/dashboard";
		$this->load->view('themes',$file);
	}
}

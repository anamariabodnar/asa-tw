<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Solutions extends CI_Controller {


	public function __construct()
	{
		parent:: __construct();
	}

	public function index()
	{
		$this->load->view('solutions');
	}
	public function login_to_continue()
	{
		$this->load->view('login_to_continue');
	}
}
?>
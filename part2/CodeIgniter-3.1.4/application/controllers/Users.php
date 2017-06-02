
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

	public function __construct()
	{
		parent:: __construct();
		$this->load->library('form_validation');
		$this->load->model('user');
	}

	public function login(){
		$data=array();
		if($this->session->userdata('success_msg')){
			$data['success_msg']=$this->session->userdata('success_msg');
			$this->session->unset_userdata('success_msg');
		}

		if($this->session->userdata('error_msg')){
			$data['error_msg']=$this->session->userdata('error_msg');
			$this->session->unset_userdata('error_msg');
		}

		if($this->input->post('loginSubmit')){
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
			$this->form_validation->set_rules('password', 'password', 'required');
			if($this->form_validation->run()==true){
				$con['returnType'] = 'single';
                $con['conditions'] = array(
                    'EMAIL'=>$this->input->post('email'),
                    'PASSWORD' => md5($this->input->post('password'))
                );
                $checkLogin = $this->user->getRows($con);
                if($checkLogin){
                    $this->session->set_userdata('isUserLoggedIn',TRUE);
                    $this->session->set_userdata('userId',$checkLogin['EMAIL']);
                    redirect('main/index');
                }
                else{
                	$data['error_msg'] = 'Wrong email or password, please try again.';
                }
			}

		}


		$this->load->view('pages/login', $data);
	}


	public function registration(){
        $data = array();
        $userData = array();
        if($this->input->post('regisSubmit')){
            $this->form_validation->set_rules('name', 'Name', 'trim|required|alpha_numeric|min_length[4]');
            $this->form_validation->set_rules('username', 'UserName', 'trim|required|alpha_numeric|min_length[4]|is_unique[USERS.USERNAME]', array('is_unique' => 'This username already exists. Please choose another one.'));
            $this->form_validation->set_rules('birthday', 'Birthday', 'required');
            $this->form_validation->set_rules('country', 'Country', 'required');
            $this->form_validation->set_rules('city', 'City', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[USERS.EMAIL]', array('is_unique' => 'This mail already exists. Please choose another one.'));
            $this->form_validation->set_rules('password', 'password', 'required');
            $this->form_validation->set_rules('conf_password', 'confirm password', 'required|matches[password]');

            $userData = array(
                'USERNAME' => strip_tags($this->input->post('username')),
                'NAME' => strip_tags($this->input->post('name')),
                'EMAIL' => strip_tags($this->input->post('email')),
                'BIRTHDAY' => strip_tags(date('d-M-Y', strtotime($this->input->post('birthday')))),
                'COUNTRY' => strip_tags($this->input->post('country')),
                'PASSWORD' => md5($this->input->post('password')),
                'CITY' => strip_tags($this->input->post('city'))
            );


            //ar trebui sa am true in acest if, insa oricum as introduce date in formular, nu-l valideaza ca si corect
            //de asemenea, nu se verifica nici daca este emailul si username-ul sunt unice, de aceea arunca o exceptie atunci cand se introduc date ce exista deja in baza de date
                if($this->form_validation->run() == false){
                $insert = $this->user->insert($userData);
                if($insert){
                    $this->session->set_userdata('success_msg', 'Your registration was successfully. Please login to your account.');
                    redirect('users/login');
                }else{
                    $data['error_msg'] = 'Some problems occured, please try again.';
                }
            }
        }
        $data['user'] = $userData;

        $this->load->view('pages/signup', $data);
    }

    public function logout(){
        $this->session->unset_userdata('isUserLoggedIn');
        $this->session->unset_userdata('userId');
        $this->session->sess_destroy();
        redirect('main/index');
    }


    public function email_check($str){
        $con['returnType'] = 'count';
        $con['conditions'] = array('EMAIL'=>$str);
        $checkEmail = $this->user->getRows($con);
        if($checkEmail > 0){
            $this->form_validation->set_message('email_check', 'The given email already exists.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

}

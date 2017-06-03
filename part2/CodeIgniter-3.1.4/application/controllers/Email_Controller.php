<?php
defined('BASEPATH') OR exit('No direct script access allowed');

   class Email_Controller extends CI_Controller { 

      function __construct() { 
         parent::__construct();
         $this->load->model('emailmodel'); 
      } 
		
	
      public function index() { 
      } 
  
      public function send_mail() { 
         $from_email = "asa.project.for.tw@gmail.com"; 
   		 
   		 $config=Array(
   		 	'protocol'=>'smtp',
   		 	'smtp_host'=>'ssl://smtp.gmail.com',
   		 	'smtp_port'=>465,
   		 	'smtp_user'=>'asa.project.for.tw',
   		 	'smtp_pass'=>'asa.project.for.tw1',
   		 	'mailtype'=>'text',
   		 	'charset'=>'iso-8859-1',
   		 	'wordwrap'=>TRUE
   		 	);
   		 $this->load->library('email', $config); 
   		 $this->email->set_newline("\r\n");
         
         $list_emails=$this->emailmodel->getRows();
         
         $number_email=count($list_emails);
       
         for($i=0;$i<$number_email;$i++) 
         {
         	$to_email= $list_emails[$i]['EMAIL'];
         	$this->email->from($from_email, 'ASA TW'); 
         	$this->email->to($to_email);
	        $this->email->subject('NEW EXPLOITS'); 
	        $this->email->message('New exploits are available. Check out our application to see more details.'); 
	        $this->email->send();
         }
      } 
   } 
?>
<?php

class Account extends CI_Controller {
     
    function __construct() {
    		// Call the Controller constructor
	    	parent::__construct();
	    	session_start();
    }
        
    public function _remap($method, $params = array()) {
	    	// enforce access control to protected functions	

    		$protected = array('updatePasswordForm','updatePassword','index','logout');
    		
    		if (in_array($method,$protected) && !isset($_SESSION['user']))
   			redirect('account/loginForm', 'refresh'); //Then we redirect to the index page again
 	    	
	    	return call_user_func_array(array($this, $method), $params);
    }
          
    
    function loginForm() {
    		$data['main_content'] = 'account/loginForm.php';
    		$this->load->view('main.php', $data);
    }
    
    function login() {

    		$this->load->library('form_validation');
    		$this->form_validation->set_rules('username', 'Username', 'required');
    		$this->form_validation->set_rules('password', 'Password', 'required');

    		if ($this->form_validation->run() == FALSE)
    		{
    			$data['main_content'] = 'account/loginForm.php';
    			$this->load->view('main.php', $data);
    		}
    		else
    		{
    			$login = $this->input->post('username');
    			$clearPassword = $this->input->post('password');
    			 
    			$this->load->model('user_model');
    		
    			$user = $this->user_model->get($login);
    			 
    			if (isset($user) && $user->comparePassword($clearPassword)) {
    				$_SESSION['user'] = $user;
    				$data['user']=$user;

    				$_SESSION['myturn'] = false;

    				$this->user_model->updateStatus($user->id, User::AVAILABLE);
    				
    				redirect('arcade/index', 'refresh'); //redirect to the main application page
    			}

	 			else {   			
					$data['errorMsg']='Incorrect username or password!';
					$data['main_content'] = 'account/loginForm.php';
	    			$this->load->view('main.php', $data);
	 			}
    		}
    }

    function logout() {
		$user = $_SESSION['user'];
    		$this->load->model('user_model');
	    	$this->user_model->updateStatus($user->id, User::OFFLINE);
    		session_destroy();
    		redirect('account/index', 'refresh'); //Then we redirect to the index page again
    }

    function newForm() {
    		$text = '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ';
    		$word = '';
    		$i = 0;
    		while ($i < 5){
    			$word .= $text[mt_rand(0, 61)];
    			$i++;
    		}

    		$vals = array (
    			'word' => $word,
    			'img_path' => './captcha/',
    			'img_url' => base_url().'captcha/',
    			'font_path' => './fonts/AnonymousClippings.ttf',
    			'img_width' => '150',
    			'img_height' => '32',
    			'expiration' => '3600'
    		);

    		$data['image'] = create_captcha($vals);
    		$_SESSION['captcha'] = $word;
    		$data['main_content'] = 'account/newForm.php';
	    	$this->load->view('main', $data);
    }
    
    function createNew() {
    		$this->load->library('form_validation');

    	    $this->form_validation->set_rules('username', 'Username', 'required|is_unique[user.login]');
	    	$this->form_validation->set_rules('password', 'Password', 'required');
	    	$this->form_validation->set_rules('passconf', 'Password Comfirmation', 'required|matches[password]');
	    	$this->form_validation->set_rules('first', 'First', "required");
	    	$this->form_validation->set_rules('last', 'last', "required");
	    	$this->form_validation->set_rules('email', 'Email', "required|is_unique[user.email]");
	    	$this->form_validation->set_rules('captcha', 'Captcha', "required|callback_captcha_check");
	    	
	    
	    	if ($this->form_validation->run() == FALSE)
	    	{	
	    		$text = '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ';
	    		$word = '';
	    		$i = 0;
	    		while ($i < 5){
	    			$word .= $text[mt_rand(0, 61)];
	    			$i++;
	    		}

	    		$vals = array (
	    			'word' => $word,
	    			'img_path' => './captcha/',
	    			'img_url' => base_url().'captcha/',
	    			'font_path' => './fonts/AnonymousClippings.ttf',
	    			'img_width' => '150',
	    			'img_height' => '32',
	    			'expiration' => '3600'
	    		);
	    		
	    		$data['image'] = create_captcha($vals);
    			$_SESSION['captcha'] = $word;

	    		$data['main_content'] = 'account/newForm.php';
    			$this->load->view('main.php', $data);
	    	}
	    	else  
	    	{
	    		$user = new User();
	    		 
	    		$user->login = $this->input->post('username');
	    		$user->first = $this->input->post('first');
	    		$user->last = $this->input->post('last');
	    		$clearPassword = $this->input->post('password');
	    		$user->encryptPassword($clearPassword);
	    		$user->email = $this->input->post('email');
	    		
	    		$this->load->model('user_model');
	    		 
	    		
	    		$error = $this->user_model->insert($user);
	    		
	    		$data['main_content'] = 'account/loginForm.php';
    			$this->load->view('main.php', $data);
	    	}
    }

    function captcha_check($value) {
    	if (strcasecmp($value, $_SESSION['captcha']) != 0) {
    		$this->form_validation->set_message('captcha_check', 'Please enter the correct letters.');
    		return false;
    	} else {
    		return true;
    	}
    }

    
    function updatePasswordForm() {
    		$data['main_content'] = 'account/updatePasswordForm';
	    	$this->load->view('main.php', $data);
    }
    
    function updatePassword() {
	    	$this->load->library('form_validation');
	    	$this->form_validation->set_rules('oldPassword', 'Old Password', 'required');
	    	$this->form_validation->set_rules('newPassword', 'New Password', 'required');
	    	$this->form_validation->set_rules('passconf', 'Password Comfirmation', 'required|matches[newPassword]');
	    	 
	    	 
	    	if ($this->form_validation->run() == FALSE)
	    	{
	    		$data['main_content'] = 'account/updatePasswordForm';
	    		$this->load->view('main.php', $data);
	    	}
	    	else
	    	{
	    		$user = $_SESSION['user'];
	    		
	    		$oldPassword = $this->input->post('oldPassword');
	    		$newPassword = $this->input->post('newPassword');
	    		 
	    		if ($user->comparePassword($oldPassword)) {
	    			$user->encryptPassword($newPassword);
	    			$this->load->model('user_model');
	    			$this->user_model->updatePassword($user);
	    			redirect('arcade/index', 'refresh'); //Then we redirect to the index page again
	    		}
	    		else {
	    			$data['errorMsg']="Incorrect password!";
	    			$data['main_content'] = 'account/updatePasswordForm';
	    			$this->load->view('main.php', $data);
	    		}
	    	}
    }
    
    function recoverPasswordForm() {
    		$data['main_content'] = 'account/recoverPasswordForm';
	    	$this->load->view('main.php', $data);
    }
    
    function recoverPassword() {
	    	$this->load->library('form_validation');
	    	$this->form_validation->set_rules('email', 'email', 'trim|required|valid_email');
	    	
	    	if ($this->form_validation->run() == FALSE)
	    	{
	    		$data['main_content'] = 'account/recoverPasswordForm';
	    		$this->load->view('main.php', $data);
	    	}
	    	else
	    	{ 
	    		$email = $this->input->post('email');
	    		$this->load->model('user_model');
	    		$user = $this->user_model->getFromEmail($email);

	    		if (isset($user)) {
	    			$newPassword = $user->initPassword();
	    			$this->user_model->updatePassword($user);
	    			
	    			$this->load->library('email');
	    		
	    			$config['protocol']    = 'smtp';
	    			$config['smtp_host']    = 'ssl://smtp.gmail.com';
	    			$config['smtp_port']    = '465';
	    			$config['smtp_timeout'] = '7';
	    			$config['smtp_user']    = 'your gmail user name';
	    			$config['smtp_pass']    = 'your gmail password';
	    			$config['charset']    = 'utf-8';
	    			$config['newline']    = "\r\n";
	    			$config['mailtype'] = 'text'; // or html
	    			$config['validation'] = TRUE; // bool whether to validate email or not
	    			
		    	  	$this->email->initialize($config);
	    			
	    			$this->email->from('csc309Login@cs.toronto.edu', 'Login App');
	    			$this->email->to($user->email);
	    			
	    			$this->email->subject('Password recovery');
	    			$this->email->message("Your new password is $newPassword");
	    			
	    			$result = $this->email->send();
	    			
	    			//$data['errorMsg'] = $this->email->print_debugger();	
	    			
	    			//$this->load->view('emailPage',$data);
	    			$this->load->view('account/emailPage');
	    			
	    		}
	    		else {
	    			$data['errorMsg']="No record exists for this email!";
	    			$data['main_content'] = 'account/recoverPasswordForm';
	    			$this->load->view('main.php', $data);
	    		}
	    	}
    }    
 }


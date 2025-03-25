<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
    public function __construct()
	{
		parent::__construct();
		$this->load->model('admin/Loginmodel');
	}
	public function index()
	{	//echo "hre";exit;
		$this->load->view('admin/login');
	}
    public function userlogin()
	{
        $this->form_validation->set_error_delimiters('', ''); 
		$this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('login[password]', 'Password', 'required');
		if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('admin/login');
            
        }
        else
        {
            //exit;
            $data=$this->Loginmodel->checkLogin(); //print_r($data);exit;
			if($data!="")
			{
				$loginName=$data[0]['Name'];
				$store_id=$data[0]['store_id']; //login user store id
				$loginusername=$data[0]['userName'];//Username of current login user
			 	$loginid=$data[0]['userid'];//Id of current login user
				$roleid=$data[0]['userroleid'];//Role of current login user
				//$rolename=$data[0]['rolename'];
				$this->session->set_userdata('loginName',$loginName);
				$this->session->set_userdata('loginName',$loginName);
				$this->session->set_userdata('logged_in_store_id',$store_id);
				$this->session->set_userdata('loginid',$loginid);
				$this->session->set_userdata('roleid',$roleid);
				//$this->session->set_userdata('rolename',$rolename);
				$this->session->set_userdata('login_status',true);
			//	$pswdstatus=$data[0]['pswdstatus'];
				$mail			=	$_POST['username'];
				$password		= 	$_POST['login']['password'];
				if(isset($_POST["remember"]))
				{								
					$hour = time() + 3600 * 24 * 30;
					setcookie('adminemail', $mail, $hour);
					setcookie('adminpassword', $password, $hour);
					setcookie('adminremember',$_POST["remember"], $hour);
				}
				if($data[0]['store_id']==0){   //if login user is admin redirected to admin dashboard otherwise owner dashboard
					redirect('admin/dashboard');
				}else{
					redirect('admin/dashboard');
				}

			}
			else
			{
				$this->session->set_flashdata('error','Invalid email or password');
				redirect('admin/login');
			}
        }
	}
	function forgotpassword()
	{
		if($_SERVER['REQUEST_METHOD']=='POST')
		{
		    $this->form_validation->set_error_delimiters('', ''); 
			$this->form_validation->set_rules('email', 'Email', 'required');
			if($this->form_validation->run()==TRUE)
			{
				$email=$this->input->post('email');
				$emailValidate=$this->Loginmodel->emailValidate($email);
				if($emailValidate!=false)
				{
					$row=$emailValidate;
					$user_id=$row->userid;
					$user_name=$row->userName;
					$user_email=$row->userEmail;
					$passwordGenerate=$this->Loginmodel->passwordGenerate();
					//Code for sending email to the client
					$to = $email;
					$from='support@makeoverapp.co.in';
					$this->load->library('email');
					$this->smtpprotocol='smtp';
					$this->smtp_host='mail.makeoverapp.co.in';
					$this->smtp_user='support@makeoverapp.co.in';
					$this->smtp_pass='makeover@123';
					$this->smtp_fromname='Deem';
					$config['protocol']    	= $this->smtpprotocol;
					$config['smtp_host']    = $this->smtp_host;
					$config['smtp_user']    = $this->smtp_user;
					$config['smtp_pass']    = $this->smtp_pass;
					$config['port']    		= 465;
					$config['charset']   	= 'utf-8';
					$config['newline']      = "\r\n";
					$config['mailtype']     = 'html'; // or html
					$config['smtp_timeout'] = '60'; //in seconds
					$this->email->initialize($config);
					$this->email->from($from, $this->smtp_fromname);
					$this->email->to($to); 
					$loginLink=base_url().'login/';
					$this->email->subject('Deem user Login credentials');
					$this->email->message('your username is '.$user_name.' and password is '.$passwordGenerate.' Go to the login page '.$loginLink);
					if($this->email->send())
					{
						$hashPasswordGenerate=md5($passwordGenerate);
						$data=array(
							'userPassword'=>$hashPasswordGenerate
							//'userAddress'=>$passwordGenerate
						);
						$updatePassword=$this->Loginmodel->updatePassword($data,$user_id);
						$this->session->set_flashdata('success','New password sent to your email');
						redirect('admin/login');
					}
					else
					{
						$this->session->set_flashdata('error','Email sending error');
						$this->load->view('admin/forgot_password');
					}
				}
				else
				{
					$this->session->set_flashdata('error','Invalid email');
					$this->load->view('admin/forgot_password');
				}
			}
			else
			{
				$this->load->view('admin/forgot_password');
			}
			
		}
		else
		{
			$this->load->view('admin/forgot_password');
		}

		
	}
	public function logout()
	{
	    $this->db->set('logout_time', date('Y-m-d H:i:s'))
        ->where('user_id', $this->session->userdata('loginid'))
        ->where('store_id', $this->session->userdata('logged_in_store_id'))
        ->update('user_login_logout');
		$this->session->sess_destroy();
		redirect('admin/login');
	}
	public function get_notification_count(){
		$login_user=$this->input->post('login_user');
		echo  $this->Loginmodel->get_notification_count($login_user);   
	}
}
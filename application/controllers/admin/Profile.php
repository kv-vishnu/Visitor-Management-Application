<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Profile extends CI_Controller {

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
		$this->load->model('Rolemodel');
		$this->load->model('Staffmodel');

		require('Common.php');
		if (!$this->session->userdata('login_status')) {
			redirect(login);
		}
	}
	public function index()
	{
        $data['details']=$this->Staffmodel->get($this->session->userdata('loginid'));
        $data['role']=$this->Rolemodel->get($this->session->userdata('roleid'));
        $this->load->view('includes/header');
		$this->load->view('staff/profileview',$data);
		$this->load->view('includes/footer');
    }

    public function checkemail()
	{
		   $email=$this->input->post('userEmail');
		   $data=$this->Staffmodel->checkemail($email);//echo 'dbpass'.$data['userPassword'];echo 'text'.$pass;die;
		   return $data;
	}
	public function checkph()
	{
		   $ph=$this->input->post('userph');
		   $data=$this->Staffmodel->checkph($ph);//echo 'dbpass'.$data['userPassword'];echo 'text'.$pass;die;
		   return $data;
	}
	public function updateprofile()
	{
		$staffdata = array(
			
			'Name' => $this->security->xss_clean($this->input->post('Name')),
			'gender' => $this->security->xss_clean($this->input->post('gender')),
			'userEmail' => $this->security->xss_clean($this->input->post('userEmail')),
			'userName' => $this->security->xss_clean($this->input->post('userName')),
			'UserPhoneNumber' => $this->security->xss_clean($this->input->post('UserPhoneNumber')),
			'userAddress' => $this->security->xss_clean($this->input->post('userAddress')),
			'updatedBy' => $this->session->userdata('loginid'),
			'dob' => $this->security->xss_clean(date('Y-m-d',strtotime($this->input->post('dob'))))

		);
		$userid=$this->session->userdata('loginid');
		$this->Staffmodel->update($userid,$staffdata);
		$this->session->set_flashdata('success','Profile details updated...');
		redirect('profile');
	}
	public function changepass()
	{
		    $staffdata = array(
			'userPassword' => md5($this->security->xss_clean($this->input->post('newpassword'))));
		     $userid=$this->security->xss_clean($this->input->post('userid'));
			$this->Staffmodel->update($userid,$staffdata);
			$this->session->set_flashdata('success','Password updated...');
			redirect('profile');

			

	}

   
}
?>
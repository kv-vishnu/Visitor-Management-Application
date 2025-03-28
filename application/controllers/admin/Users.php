<?php
class Users extends CI_Controller {
    public function __construct() {
        
        parent::__construct();
        $this->load->model('admin/Enquirymodel');
        $this->load->model('admin/Commonmodel');
        $this->load->library('pagination');
    }

    public function index()
	{
        $controller = $this->router->fetch_class(); // Gets the current controller name
		$method = $this->router->fetch_method();   // Gets the current method name
		$data['controller'] = $controller;
        $logged_store_id=$this->session->userdata('logged_in_store_id');
        $config['base_url'] = site_url('admin/Users/index');
        $config['total_rows'] = $this->Commonmodel->getUsersCount();
        $config['per_page'] = 2; // number of rows per page
        $config['uri_segment'] = 4; // which URI segment contains the page numberg
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['prev_link'] = '<span class="pagination-previous">Previous</span>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['next_link'] = '<span class="pagination-next">Next</span>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        // Custom icons for first and last links
        $config['first_link'] = '<span class="pagination-first">First</span>'; // First link icon
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = '<span class="pagination-last">Last</span>'; // Last link icon
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0; // Get the current page number
        $list_companies = $this->Commonmodel->list_companies();
        $data['list_users'] = $this->Commonmodel->list_users();
        $data['companies'] = array_slice($list_companies, $page, $config['per_page']);
        $data['pagination'] = $this->pagination->create_links();
        $date =date('Y-m-d');
        $data['date'] =$date;
        
        $data['store_disp_name'] = "Test";
        $data['store_address'] = "Test address";
        $data['support_no'] = "9841234567";
        $data['support_email'] ="test@.com";
		$data['store_logo'] = "logo image path";
		$this->load->view('admin/includes/header',$data);
        $this->load->view('admin/includes/owner-dashboard',$data);
        $this->load->view('admin/company/companies',$data);
		$this->load->view('admin/includes/footer');
	}
    public function save(){
        $company_id = $this->input->post('user_company_id');
        $this->load->library('form_validation'); 
        $this->form_validation->set_rules('user_name', 'Name', 'required');
        $this->form_validation->set_rules('user_email', 'Email', 'required'); 
        $this->form_validation->set_rules('user_address', 'Address', 'required');    
        $this->form_validation->set_rules('user_phoneno', 'Phone', 'required');   
        $this->form_validation->set_rules('user_username', 'username', 'required');   
        $this->form_validation->set_rules('user_password', 'password', 'required'); 
        $this->form_validation->set_rules('role', 'role', 'required');   

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, send errors back to the view
            $response = [
                'success' => false,
                'errors' => [
                    'user_name' => form_error('user_name'),
                    'user_email' => form_error('user_email'),
                    'user_address' => form_error('user_address'),
                    'user_phoneno' => form_error('user_phoneno'),
                    'user_username' => form_error('user_username'),
                    'user_password' => form_error('user_password'),
                    'role' => form_error('role'),
                   
                ]
            ];


            echo json_encode($response);
        } 

        else 
        {

            $data = array(
                'userroleid' => $this->input->post('role'),
                'company_id' =>$company_id ,
                'Name' => $this->input->post('user_name'),
                'userEmail' => $this->input->post('user_email'),
                'userName' => $this->input->post('user_username'),
                'userPassword' => $this->input->post('user_password'),
                'UserPhoneNumber' => $this->input->post('user_phoneno')?? null,
                'userAddress' => $this->input->post('user_address'),
                'is_active' => 1
            );
            $this->db->where('Name', $data['Name']);
            $this->db->or_where('userEmail', $data['userEmail']);
            $query = $this->db->get('users');
            if ($query->num_rows() > 0) 
            {
                echo json_encode(['success' => false, 'errors' => 'Username or Email already exists']);
            } else 
            {
                // Insert the user since no duplicate was found
                $this->Commonmodel->insert_users($data);
                echo json_encode(['success' => 'success']);
            }        
        }
    }

    public function getUserDetails()
    {
        $user_id = $this->input->post('edit_user_id');
        $user_details = $this->Commonmodel->getUserDetailsbyid($user_id); 
        if (!$user_details || !is_array($user_details)) 
        {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid enquiry_details data.'
            ]);
            return;
        }
        $result = [
                'userroleid' => $user_details['userroleid'] ?? null,
                'Name' => $user_details['Name'],
                'userEmail' => $user_details['userEmail'],
                'userName' => $user_details['userName'],
                'userPassword' => $user_details['userPassword'],
                'UserPhoneNumber' => $user_details['UserPhoneNumber']?? null,
                'userAddress' => $user_details['userAddress'],
        ];
        echo json_encode([
            'success' => true,
            'data' => $result
        ]);
    }

    public function updateUserdetails(){
        $userid = $this->input->post('edit_user_id');
        $data = array(
                'userroleid' => $this->input->post('role'),
                'Name' => $this->input->post('user_name'),
                'userEmail' => $this->input->post('user_email'),
                'userName' => $this->input->post('user_username'),
                'userPassword' => $this->input->post('user_password'),
                'UserPhoneNumber' => $this->input->post('user_phoneno')?? null,
                'userAddress' => $this->input->post('user_address'),
        );
        $this->Commonmodel->update_user_details($data,$userid);
        echo json_encode(['success' => 'success','data'=>$data]);
    }

    public function DeleteUser(){
        $id=$this->input->post('id');
        $this->Commonmodel->DeleteUser($id);
        $this->session->set_flashdata('error','User deleted successfully');
    }

    public function ChangePassword(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('password_changes', 'Password', 'required|min_length[4]|max_length[20]|callback_valid_password');
    
        if ($this->form_validation->run() === FALSE) {
            $response = [
                'success' => false,
                'errors' => [
                    'password_changes' => form_error('password_changes'),
                ]
            ];
            echo json_encode($response);
        } 
        else{
    
            $user_id= $this->input->post('user_password_change');
            $data=array(
                'userid'=> $user_id,
                'userPassword'=>md5(trim($this->input->post('password_changes')))
            );
            $passwordchanges= $this->Commonmodel->ChangePassword($data,$user_id);
            echo json_encode(['success' => 'success', 'message' => 'Success']);
        }
    }


}
?>
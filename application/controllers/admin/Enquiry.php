<?php
class Enquiry extends CI_Controller {

    //Load enquiry page list - index()
    //Load add enquiry page - add()
    //Load edit enquiry page - edit()
    //Save new enquiry - save()
    //Update enquiry - update()
    //Delete enquiry - delete()


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
        $config['base_url'] = site_url('admin/Enquiry/index');
        $config['total_rows'] = $this->Enquirymodel->getEnquiryCount();
        $config['per_page'] = 10; // number of rows per page
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
        //$data['products'] = $this->Enquirymodel->shopAssignedProductsbyPagination($config['per_page'], $page);
        $all_enquiries = $this->Enquirymodel->get_enquiries();
        $data['all_companies'] = $this->Commonmodel->list_companies();
        $data['purposes'] = $this->Commonmodel->list_purposes(); //print_r($data['purposes']);
        $data['enquiries'] = array_slice($all_enquiries, $page, $config['per_page']);
        $data['pagination'] = $this->pagination->create_links();
        $date =date('Y-m-d');
        $data['date'] =$date;
        
        $data['store_disp_name'] = "Test";
        $data['store_address'] = "Test address";
        $data['support_no'] = "9841234567";
        $data['support_email'] ="test@.com";
		$data['store_logo'] = "logo image path";
        
        
       // $data['currentStock'] =$this->Ordermodel->getCurrentStock( $product_ids_string,$date,$logged_store_id);
		$this->load->view('admin/includes/header',$data);
        $this->load->view('admin/includes/owner-dashboard',$data);
		$this->load->view('admin/enquiry/enquiries',$data);
		$this->load->view('admin/includes/footer');
	}

    public function delete(){
	    $this->Enquirymodel->delete_category($this->input->post('id'));
		$this->session->set_flashdata('error','Category deleted successfully');
	}

    public function save() {
        $this->load->library('form_validation'); 
        $this->form_validation->set_rules('purpose_of_visit', 'Purpose of visit', 'required'); 
        $this->form_validation->set_rules('contact_person', 'Contact person', 'required');    
        $this->form_validation->set_rules('visitor_name', 'Name', 'required');   
        $this->form_validation->set_rules('phone_number', 'Number', 'required');   
        $this->form_validation->set_rules('email', 'Email', 'required'); 
        $this->form_validation->set_rules('remarks', 'Remark', 'required');   
        $this->form_validation->set_rules('visitor_message', 'Message', 'required');     
                       
        if ($this->form_validation->run() == FALSE) {
            // If validation fails, send errors back to the view
            $response = [
                'success' => false,
                'errors' => [
                    'visitor_name' => form_error('visitor_name'),
                    'phone_number' => form_error('phone_number'),
                    'email' => form_error('email'),
                    'purpose_of_visit' => form_error('purpose_of_visit'),
                    'contact_person' => form_error('contact_person'),
                    'remarks' => form_error('remarks'),
                    'visitor_message' => form_error('visitor_message')
                ]
            ];
            echo json_encode($response);
        } 
        else 
        {

            $data = array(
                'visitor_name' => $this->input->post('visitor_name'),
                'phone_number' => $this->input->post('phone_number') ?? null,
                'email' => $this->input->post('email'),
                'company_id' => $this->input->post('company_id'),
                'purpose_of_visit' => $this->input->post('purpose_of_visit'),
                'contact_person' => $this->input->post('contact_person'),
                'remarks' => $this->input->post('remarks'),
                'visitor_message' => $this->input->post('visitor_message'),
                'is_read' => 0
            );

            if($this->Enquirymodel->insert_enquiry($data)){
                $response = (['success' => 'success']);
                echo json_encode($response);
            }
        }
    }


    public function add($id = null) {
            $data['selected_company'] = $id;
            $controller = $this->router->fetch_class(); // Gets the current controller name
            $method = $this->router->fetch_method();   // Gets the current method name
            $data['controller'] = $controller;
            $data['companies']=$this->Commonmodel->list_companies();
            $data['store_disp_name'] = "Test";
            $data['store_address'] = "Test address";
            $data['support_no'] = "9841234567";
            $data['support_email'] ="test@.com";
            $data['store_logo'] = "logo image path";
        
            $this->load->view('admin/includes/header',$data);
            $this->load->view('admin/includes/owner-dashboard',$data);
            $this->load->view('admin/enquiry/add-enquiry',$data);
            $this->load->view('admin/includes/footer');
    }


    public function getEnquiryDetails() 
    {
            $id = $this->input->post('id');
            $enquiry_details = $this->Enquirymodel->getEnquiryDetailsById($id); 
            
            // Check if enquiry_details is valid
            if (!$enquiry_details || !is_array($enquiry_details)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid enquiry_details data.'
                ]);
                return;
            }
            
            $result = [
                'visitor_name' => $enquiry_details['visitor_name'] ?? null,
                'phone_number' => $enquiry_details['phone_number'] ?? null,
                'email' => $enquiry_details['email'] ?? null,
                'company_id' => $enquiry_details['company_id'] ?? null,
                'purpose_of_visit' => $enquiry_details['purpose_of_visit'] ?? null,
                'contact_person' => $enquiry_details['contact_person'] ?? null,
                'remarks' => $enquiry_details['remarks'] ?? null,
                'visitor_message' => $enquiry_details['visitor_message'] ?? null,
            ];
            
            
            
            // Respond with success and data
            echo json_encode([
                'success' => true,
                'data' => $result
            ]);
    }
    public function updateEnquirydetails()
    {
            $productId = $this->input->post('enquiry_id_new');

                     $data = array(
                'visitor_name' => $this->input->post('visitor_name'),
                'phone_number' => $this->input->post('phone_number'),
                'email' => $this->input->post('email'),
                'company_id' => $this->input->post('company_id'),
                'purpose_of_visit' => $this->input->post('purpose_of_visit'),
                'contact_person' => $this->input->post('contact_person'),
                'remarks' => $this->input->post('remarks'),
                'visitor_message' => $this->input->post('visitor_message'),
                'is_read' => 0

            );

               $this->Enquirymodel->update_enquiry_details($data,$productId);
             $response = (['success' => 'success','data'=>$data]);
             echo json_encode($response);
    }
// Function to add a product with translations
public function categoryname_exists($country)
	{
		if ($this->Enquirymodel->check_categoryname_exists($country)) {
			$this->form_validation->set_message('categoryname_exists', 'The {field} is already taken.');
			return FALSE;
		} else {
			return TRUE;
		}
	}

}
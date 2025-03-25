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
        $this->form_validation->set_rules('company_id', 'Company', 'required');
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
                    'company_id' => form_error('company_id'),
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


    public function add() {  
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
            $descriptions = $this->Enquirymodel->getEnquiryDetailsById($id); 
            print_r($descriptions);exit;
            
            // Check if descriptions is valid
            if (!$descriptions || !is_array($descriptions)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid descriptions data.'
                ]);
                return;
            }
            
            $result = [
                'rate' => $descriptions['rate'] ?? null,
                'malayalam_name' => !empty($descriptions['store_product_name_ma']) 
                    ? $descriptions['store_product_name_ma'] 
                    : ($descriptions['product_name_ma'] ?? null),
                'english_name' => !empty($descriptions['store_product_name_en']) 
                    ? $descriptions['store_product_name_en'] 
                    : ($descriptions['product_name_en'] ?? null),
                'hindi_name' => !empty($descriptions['store_product_name_hi']) 
                    ? $descriptions['store_product_name_hi'] 
                    : ($descriptions['product_name_hi'] ?? null),
                'arabic_name' => !empty($descriptions['store_product_name_ar']) 
                    ? $descriptions['store_product_name_ar'] 
                    : ($descriptions['product_name_ar'] ?? null),
                'malayalam_desc' => !empty($descriptions['store_product_desc_ma']) 
                    ? $descriptions['store_product_desc_ma'] 
                    : ($descriptions['product_desc_ma'] ?? null),
                'english_desc' => !empty($descriptions['store_product_desc_en']) 
                    ? $descriptions['store_product_desc_en'] 
                    : ($descriptions['product_desc_en'] ?? null),
                'hindi_desc' => !empty($descriptions['store_product_desc_hi']) 
                    ? $descriptions['store_product_desc_hi'] 
                    : ($descriptions['product_desc_hi'] ?? null),
                'arabic_desc' => !empty($descriptions['store_product_desc_ar']) 
                    ? $descriptions['store_product_desc_ar'] 
                    : ($descriptions['product_desc_ar'] ?? null),
            ];
            
            
            
            // Respond with success and data
            echo json_encode([
                'success' => true,
                'data' => $result
            ]);
    }
    public function changeDescriptions()
    {
            $productId = $this->input->post('product_id');

            $rate = (float) $this->input->post('store_product_rate');
            $tax = 5; // Fixed tax percentage
            $tax_amount = ($rate * $tax) / 100;
            $total_amount = $rate + $tax_amount;

            $data = array(
                'store_product_name_ma' => $this->input->post('store_product_name_ma'),
                'store_product_name_en' => $this->input->post('store_product_name_en'),
                'store_product_name_hi' => $this->input->post('store_product_name_hi'),
                'store_product_name_ar' => $this->input->post('store_product_name_ar'),
                'store_product_desc_ma' => $this->input->post('description_malayalam'),
                'store_product_desc_en' => $this->input->post('description_english'),
                'store_product_desc_hi' => $this->input->post('description_hindi'),
                'store_product_desc_ar' => $this->input->post('description_arabic'),
                'rate' => $rate,
                'tax' => $tax,
                'tax_amount' => $tax_amount,
                'total_amount' => $total_amount
            );
            $this->Productmodel->update_product_description($data , $this->session->userdata('logged_in_store_id'), $productId);
            $response = (['success' => 'success']);
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
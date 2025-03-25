<?php
class Products extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('owner/Ordermodel');
        $this->load->model('website/Productmodel');
        $this->load->model('website/Homemodel');
        $this->load->library('session');
        $this->load->helper('url');
    }

    //Is whatsapp not enabled redirected to order listing page
    //Order type delivery and pickup checkout
    //Order type dining checkout


    //Order type dining checkout
    public function checkout() {
        //echo "hre";exit;
        $cartData = $this->session->userdata('cart');
        $store_token = $this->session->userdata('store_token'); //echo $store_token;exit;
        $store_details_from_token = $this->Homemodel->get_store_details_by_token($store_token); 
        $cartData = $this->session->userdata('cart');
        //print_r($product);exit;
        $orderType = 'D'; //Change ordertype here using qrcode
        $custName = '';
        $email = ''; //Change customer name here using qrcode
        $phoneNumber = ''; //Change phone number here using qrcode
        $location = ''; //Change location here using qrcode
        $order_no = $this->Productmodel->getOrderNo(); //Generate order numbet
        $day = date("d");   
        $month = date("m"); 
        $year = date("y");   
        $total_amount = 0;
        //$order_no_with_date = $order_no.$day.$month.$year; 
        if($this->session->userdata('order_no') != 0){
            $order_no_with_date = $this->session->userdata('order_no');
            $reorder =1;
        }else{
            $order_no_with_date = $order_no.$day.$month.$year; // Add today date and month year with generated token number(order number)
            $reorder = 0;
        }
        
        
      // Check stock availability

      $outOfStockProducts = [];
      if (!empty($cartData)) 
      {
          foreach ($cartData as $product) 
          {
              $date = date('Y-m-d');
              $productDetails = $this->Productmodel->get_store_wise_product_by_id($product['product_id']); 
              //print_r($productDetails);exit;
              
              // Check if the product is a combo
              if($productDetails[0]['category_id'] == 23) {
                  // Get combo components
                  $comboItems = $this->Productmodel->getComboItems($store_details_from_token->store_id,$product['product_id']);
                  foreach ($comboItems as $item) {
                      $availableStock = $this->Productmodel->getCurrentStock($item['item_id'], $date, $store_details_from_token->store_id);
                      
                      // Check if requested quantity of combo item exceeds available stock
                      if ($product['quantity'] * $item['quantity'] > $availableStock) {
                          $outOfStockProducts[] = [
                              'product_name' => $this->Ordermodel->getProductName($item['item_id']),
                              'requested_quantity' => $product['quantity'] * $item['quantity'],
                              'available_stock' => isset($availableStock) ? $availableStock : 0
                          ];
                      }
                  }
              } else {
                  $availableStock = $this->Productmodel->getCurrentStock($product['product_id'], $date, $store_details_from_token->store_id);

                  // Check if the requested quantity exceeds available stock
                  if ($product['quantity'] > $availableStock) {
                      $outOfStockProducts[] = [
                          'product_name' => $this->Ordermodel->getProductName($product['product_id']),
                          'requested_quantity' => $product['quantity'],
                          'available_stock' => isset($availableStock) ? $availableStock : 0
                      ];
                  }
              }
          }

          if (!empty($outOfStockProducts)) {
              // Return response with out-of-stock products
              $Response = [
                  'status' => 'error',
                  'message' => 'Some products are out of stock.',
                  'outOfStockProducts' => $outOfStockProducts
              ];
              header('Content-Type: application/json');
              echo json_encode($Response);
              return; // Stop further execution if there are out-of-stock products
          }
      }


     
    

        if (!empty($cartData)) {
            foreach ($cartData as $product) {

                $productDetails = $this->Productmodel->get_store_wise_product_by_id($product['product_id']); //print_r($productDetails);exit;

                $tax_amount = $product['quantity'] * $product['price'] * $productDetails[0]['tax'] / 100;
				$total_amount = $product['quantity'] * $product['price'] + $tax_amount;
				
                // Prepare data to insert for each product
                $data = [
                    'orderno' => $order_no_with_date,
                    'date' => date('Y-m-d'),
                    'store_id' => $store_details_from_token->store_id,
                    'product_id' => $productDetails[0]['store_product_id'],
                    'quantity' => $product['quantity'],
                    'vat_id' => $productDetails[0]['vat_id'],
                    'rate' => $product['price'],
                    'amount' => $product['quantity'] * $product['price'],
                    'tax' => $productDetails[0]['tax'],
                    'tax_amount' => $tax_amount,
                    'total_amount' => $total_amount,
                    'item_remarks' => $product['recipe'] ?? null,
                    'variant_id' => $product['variant_id'] ?? null,
                    'category_id' => $productDetails[0]['category_id'], // optional timestamp
                    'is_addon' => $productDetails[0]['is_addon'],
                    'is_customisable' => $productDetails[0]['is_customizable'],
                    'table_id' => $store_details_from_token->table_id,
                    'order_type' => $orderType,
                    'is_paid' => 0,
                    'is_reorder' => $reorder
                ];
                $this->db->insert('order_items', $data);
            }
            
                if($this->session->userdata('order_no') == 0){
                    $updateTotalAmountFromItems = $this->Productmodel->updateTotalAmountFromItems($order_no_with_date);

                    $order_data = [
                        'orderno' => $order_no_with_date,
                        'order_token' => $order_no,
                        'date' => date('Y-m-d'),
                        'store_id' => $store_details_from_token->store_id,
                        'amount' => $updateTotalAmountFromItems[0]['total_rate'],
                        'tax' => $productDetails[0]['tax'],
                        'tax_amount' => $updateTotalAmountFromItems[0]['total_tax'],
                        'total_amount' => $updateTotalAmountFromItems[0]['total_amount'],
                        'is_paid'   => 0,
                        'table_id' => $store_details_from_token->table_id,
                        'order_type' => $orderType,
                        'customer_name	' => $custName,
                        'contact_number' => $phoneNumber,
                        'location' => $location,
                        'modified_by'=> 0,
                        'modified_date'=> date('Y-m-d H:i:s')
                    ];
                    //print_r($order_data);exit;
                    $this->db->insert('order', $order_data);
                    $this->Productmodel->updateOrderNo($order_no);
                }else{
                     $updatedTotalAmt = $this->Productmodel->updateTotalAmount($this->session->userdata('order_no'));
                     $data = [
                        'amount' => $updatedTotalAmt[0]['total_rate'],
                        'tax_amount' => $updatedTotalAmt[0]['total_tax'],
                        'total_amount' => $updatedTotalAmt[0]['total_amount'],
                        'order_status' => 0
                    ];
                        $this->db->where('orderno', $this->session->userdata('order_no'));
                        $this->db->update('order', $data);
                }
        }
        //print_r($order_data);exit;
       
        //$this->session->unset_userdata('cart');
        $Response = array(
            'orderNo' => $order_no_with_date,
            'storeToken' => $store_token,
            'storeId' => $store_details_from_token->store_id
        );
        header('Content-Type: application/json');
        echo json_encode($Response);
    }

    //Order type delivery and pickup checkout
    public function typecheckout() {
        $name = $this->input->post('name');
        $phone = $this->input->post('phone');
        $address = $this->input->post('address');
        $type = $this->input->post('type');
        $store_id = $this->input->post('store_id1');
        $cartData = $this->session->userdata('cart');
        //print_r($product);exit;
        $orderType = $type; //Change ordertype here using qrcode
        $custName = $name;
        $phoneNumber = $phone; //Change phone number here using qrcode
        $location = $address; //Change location here using qrcode
        $order_no = $this->Productmodel->getOrderNo(); //Generate order numbet
        $day = date("d");   
        $month = date("m"); 
        $year = date("y");  
        $total_amount = 0;
        if($this->session->userdata('order_no') != 0){
            $order_no_with_date = $this->session->userdata('order_no');
        }else{
            $order_no_with_date = $order_no.$day.$month.$year; // Add today date and month year with generated token number(order number)
        }
        
        
                // Check stock availability

                $outOfStockProducts = [];
            if (!empty($cartData)) 
            {
                foreach ($cartData as $product) 
                {
                    $date = date('Y-m-d');
                    $productDetails = $this->Productmodel->get_store_wise_product_by_id($product['product_id']);
                    //print_r($productDetails);exit;
                    
                    // Check if the product is a combo
                     if($productDetails[0]['category_id'] == 23) {
                        // Get combo components
                        $comboItems = $this->Productmodel->getComboItems($store_id,$product['product_id']);
                        foreach ($comboItems as $item) {
                            $availableStock = $this->Ordermodel->getCurrentStock($item['item_id'], $date, $store_id);
                            
                            // Check if requested quantity of combo item exceeds available stock
                              if ($product['quantity'] * $item['quantity'] > $availableStock) {
                                  $outOfStockProducts[] = [
                                      'product_name' => $this->Ordermodel->getProductName($item['item_id']),
                                      'requested_quantity' => $product['quantity'] * $item['quantity'],
                                      'available_stock' => isset($availableStock) ? $availableStock : 0
                                  ];
                              }
                        }
                    } else {
                        $availableStock = $this->Ordermodel->getCurrentStock($product['product_id'], $date, $store_id);

                        // Check if the requested quantity exceeds available stock
                        if ($product['quantity'] > $availableStock) {
                            $outOfStockProducts[] = [
                                'product_name' => $this->Ordermodel->getProductName($product['product_id']),
                                'requested_quantity' => $product['quantity'],
                                'available_stock' => isset($availableStock) ? $availableStock : 0
                            ];
                        }
                    }
                }

                if (!empty($outOfStockProducts)) {
                    // Return response with out-of-stock products
                    $Response = [
                        'status' => 'error',
                        'message' => 'Some products are out of stock.',
                        'outOfStockProducts' => $outOfStockProducts
                    ];
                    header('Content-Type: application/json');
                    echo json_encode($Response);
                    return; // Stop further execution if there are out-of-stock products
                }
            }
    

        if (!empty($cartData)) {
            foreach ($cartData as $product) {
                //print_r($product);exit;

                $productDetails = $this->Productmodel->get_store_wise_product_by_id($product['product_id']);

                $tax_amount = $product['quantity'] * $product['price'] * $productDetails[0]['tax'] / 100;
				$total_amount = $product['quantity'] * $product['price'] + $tax_amount;
				
                // Prepare data to insert for each product
                $data = [
                    'orderno' => $order_no_with_date,
                    'date' => date('Y-m-d'),
                    'store_id' => $store_id,
                    'product_id' => $productDetails[0]['store_product_id'],
                    'quantity' => $product['quantity'],
                    'vat_id' => $productDetails[0]['vat_id'],
                    'rate' => $product['price'],
                    'amount' => $product['quantity'] * $product['price'],
                    'tax' => $productDetails[0]['tax'],
                    'tax_amount' => $tax_amount,
                    'total_amount' => $total_amount,
                    'item_remarks' => $product['recipe'] ?? null,
                    'variant_id' => $product['variant_id'] ?? null,
                    'category_id' => $productDetails[0]['category_id'], // optional timestamp
                    'is_addon' => $productDetails[0]['is_addon'],
                    'is_customisable' => $productDetails[0]['is_customizable'],
                    'table_id' => 0,
                    'order_type' => $orderType,
                    'is_paid' => 0
                ];
                $this->db->insert('order_items', $data);
            }
            if($this->session->userdata('order_no') == 0){
                //echo "h";exit;
                $updateTotalAmountFromItems = $this->Productmodel->updateTotalAmountFromItems($order_no_with_date);

                $order_data = [
                    'orderno' => $order_no_with_date,
                    'order_token' => $order_no,
                    'date' => date('Y-m-d'),
                    'store_id' => $store_id,
                    'amount' => $updateTotalAmountFromItems[0]['total_rate'],
                    'tax' => $productDetails[0]['tax'],
                    'tax_amount' => $updateTotalAmountFromItems[0]['total_tax'],
                    'total_amount' => $updateTotalAmountFromItems[0]['total_amount'],
                    'is_paid'   => 0,
                    'table_id' => 0,
                    'order_type' => $orderType,
                    'customer_name	' => $custName,
                    'contact_number' => $phoneNumber,
                    'location' => $location,
                ];    
                    $this->db->insert('order', $order_data);
                    $this->Productmodel->updateOrderNo($order_no);

        }else{
                    //echo $this->session->userdata('order_no');exit;
                    $updatedTotalAmt1 = $this->Productmodel->updateTotalAmount($this->session->userdata('order_no'));
                    $data = [
                        'amount' => $updatedTotalAmt1[0]['total_rate'],
                        'tax_amount' => $updatedTotalAmt1[0]['total_tax'],
                        'total_amount' => $updatedTotalAmt1[0]['total_amount'],
                        'order_status' => 0
                    ];
                        $this->db->where('orderno', $this->session->userdata('order_no'));
                        $this->db->update('order', $data);

        }
    }
    $Response = array(
        'orderNo' => $order_no_with_date,
        'order_type' => $orderType,
        'store_id' => $store_id
    );
    header('Content-Type: application/json');
    echo json_encode($Response);
    }

    //Is whatsapp not enabled redirected to order listing page
    public function orderListingcheckoutDining() {
        $cartData = $this->session->userdata('cart');
        $store_token = $this->session->userdata('store_token'); //echo $store_token;exit;
        $store_details_from_token = $this->Homemodel->get_store_details_by_token($store_token); 
        $cartData = $this->session->userdata('cart');
        //print_r($cartData);exit;
        $orderType = 'D'; //Change ordertype here using qrcode
        $custName = '';
        $email = ''; //Change customer name here using qrcode
        $phoneNumber = ''; //Change phone number here using qrcode
        $location = ''; //Change location here using qrcode
        $order_no = $this->Productmodel->getOrderNo(); //Generate order numbet
        $day = date("d");   
        $month = date("m"); 
        $year = date("y");   
        $total_amount = 0;
        //$order_no_with_date = $order_no.$day.$month.$year; 
        if($this->session->userdata('order_no') != 0){
            $order_no_with_date = $this->session->userdata('order_no');
            $reorder =1;
        }else{
            $order_no_with_date = $order_no.$day.$month.$year; // Add today date and month year with generated token number(order number)
            $reorder = 0;
        }
        
        
      // Check stock availability

      $outOfStockProducts = [];
      if (!empty($cartData)) 
      {
          foreach ($cartData as $product) 
          {
              $date = date('Y-m-d');
              $productDetails = $this->Productmodel->get_store_wise_product_by_id($product['product_id']); 
              //print_r($productDetails);exit;
              
              // Check if the product is a combo
              if($productDetails[0]['category_id'] == 23) {
                  // Get combo components
                  $comboItems = $this->Productmodel->getComboItems($store_details_from_token->store_id,$product['product_id']);
                  foreach ($comboItems as $item) {
                      $availableStock = $this->Productmodel->getCurrentStock($item['item_id'], $date, $store_details_from_token->store_id);
                      
                      // Check if requested quantity of combo item exceeds available stock
                      if ($product['quantity'] * $item['quantity'] > $availableStock) {
                          $outOfStockProducts[] = [
                              'product_name' => $this->Ordermodel->getProductName($item['item_id']),
                              'requested_quantity' => $product['quantity'] * $item['quantity'],
                              'available_stock' => isset($availableStock) ? $availableStock : 0
                          ];
                      }
                  }
              } else {
                  $availableStock = $this->Productmodel->getCurrentStock($product['product_id'], $date, $store_details_from_token->store_id);

                  // Check if the requested quantity exceeds available stock
                  if ($product['quantity'] > $availableStock) {
                      $outOfStockProducts[] = [
                          'product_name' => $this->Ordermodel->getProductName($product['product_id']),
                          'requested_quantity' => $product['quantity'],
                          'available_stock' => isset($availableStock) ? $availableStock : 0
                      ];
                  }
              }
          }

          if (!empty($outOfStockProducts)) {
              // Return response with out-of-stock products
              $Response = [
                  'status' => 'error',
                  'message' => 'Some products are out of stock.',
                  'outOfStockProducts' => $outOfStockProducts
              ];
              header('Content-Type: application/json');
              echo json_encode($Response);
              return; // Stop further execution if there are out-of-stock products
          }
      }


     
    

        if (!empty($cartData)) {
            foreach ($cartData as $product) {

                $productDetails = $this->Productmodel->get_store_wise_product_by_id($product['product_id']); //print_r($productDetails);exit;

                $tax_amount = $product['quantity'] * $product['price'] * $productDetails[0]['tax'] / 100;
				$total_amount = $product['quantity'] * $product['price'] + $tax_amount;
				
                // Prepare data to insert for each product
                $data = [
                    'orderno' => $order_no_with_date,
                    'date' => date('Y-m-d'),
                    'store_id' => $store_details_from_token->store_id,
                    'product_id' => $productDetails[0]['store_product_id'],
                    'quantity' => $product['quantity'],
                    'vat_id' => $productDetails[0]['vat_id'],
                    'rate' => $product['price'],
                    'amount' => $product['quantity'] * $product['price'],
                    'tax' => $productDetails[0]['tax'],
                    'tax_amount' => $tax_amount,
                    'total_amount' => $total_amount,
                    'item_remarks' => $product['recipe'] ?? null,
                    'variant_id' => $product['variant_id'] ?? null,
                    'category_id' => $productDetails[0]['category_id'], // optional timestamp
                    'is_addon' => $productDetails[0]['is_addon'],
                    'is_customisable' => $productDetails[0]['is_customizable'],
                    'table_id' => $store_details_from_token->table_id,
                    'order_type' => $orderType,
                    'is_paid' => 0,
                    'is_reorder' => $reorder
                ];
                $this->db->insert('order_items', $data);
            }
            
                if($this->session->userdata('order_no') == 0){
                    $updateTotalAmountFromItems = $this->Productmodel->updateTotalAmountFromItems($order_no_with_date);

                    $order_data = [
                        'orderno' => $order_no_with_date,
                        'order_token' => $order_no,
                        'date' => date('Y-m-d'),
                        'store_id' => $store_details_from_token->store_id,
                        'amount' => $updateTotalAmountFromItems[0]['total_rate'],
                        'tax' => $productDetails[0]['tax'],
                        'tax_amount' => $updateTotalAmountFromItems[0]['total_tax'],
                        'total_amount' => $updateTotalAmountFromItems[0]['total_amount'],
                        'is_paid'   => 0,
                        'table_id' => $store_details_from_token->table_id,
                        'order_type' => $orderType,
                        'customer_name	' => $custName,
                        'contact_number' => $phoneNumber,
                        'location' => $location,
                        'modified_by'=>0,
                        'modified_date'=> date('Y-m-d H:i:s')
                    ];
                    //print_r($order_data);exit;
                    $this->db->insert('order', $order_data);
                    $this->Productmodel->updateOrderNo($order_no);
                }else{
                     $updatedTotalAmt = $this->Productmodel->updateTotalAmount($this->session->userdata('order_no'));
                     $data = [
                        'amount' => $updatedTotalAmt[0]['total_rate'],
                        'tax_amount' => $updatedTotalAmt[0]['total_tax'],
                        'total_amount' => $updatedTotalAmt[0]['total_amount'],
                        'order_status' => 0
                    ];
                        $this->db->where('orderno', $this->session->userdata('order_no'));
                        $this->db->update('order', $data);
                }
        }
        
        $Response = array(
            'orderNo' => $order_no_with_date,
            'order_type' => $orderType,
            'store_id' => $store_details_from_token->store_id
        );
        header('Content-Type: application/json');
        echo json_encode($Response);
        
    }

    public function orderListing($order_no,$store_id) {
        //echo $store_id;exit;
        $store_details = $this->Homemodel->get_store_details_by_store_id($store_id);
        $store_token = $this->session->userdata('store_token');
        $data['tax_infr'] = $this->Homemodel->get_store_tax_by_store_id($store_details->gst_or_tax);
        $data['reorder_link']   = base_url('website/load_orders/'.$store_token.'/'.$order_no);
        $data['order_number'] = $order_no;
        $data['store_id'] = $store_id;
        $this->load->view('website/order_listing', $data);
    }

    public function load_site($token = NULL , $order_no = NULL) {
        $store_details_from_token = $this->Homemodel->get_store_details_by_token($token); 
        $store_id = $store_details_from_token->store_id; //echo $store_id;exit; //Get store id
        $secret_code = $store_details_from_token->secret_code; //Table secret code
        $is_whatsapp_enable = $this->Homemodel->isWhatsappEnableCheck($store_id); //echo $is_whatsapp_enable;exit;
        if(!$secret_code || $is_whatsapp_enable == 1 )
        {
            $this->load_orders($token,$order_no);
        }
        else
        {
            $data['secret_code'] = $secret_code;
            $data['token'] = $token;
            $this->load->view('website/verification', $data);
        }
    }

    public function load_orders($token = NULL , $order_no = NULL) {
        //echo $token;echo $order_no;exit;
        $this->session->set_userdata('store_token', $token);
        $this->session->set_userdata('order_no', $order_no);
        $this->session->set_userdata('delivery_type', 'D');
        $store_details_from_token = $this->Homemodel->get_store_details_by_token($token); //print_r($store_details_from_token);exit; //get store details from token.Token get from table qr code url
        $store_id = $store_details_from_token->store_id; //echo $store_id;exit; //Get store id
        $secret_code = $store_details_from_token->secret_code; //Table secret code
        $this->isStoreProductAvailable($store_id);
        $this->session->set_userdata('store_id', $store_id);
        
        $delivery_type_phone = $this->Homemodel->getDeliveryTypePhone($store_id,'D');
        $this->session->set_userdata('delivery_type_phone', $delivery_type_phone);
        $store_details = $this->Homemodel->get_store_details_by_store_id($store_id); //print_r($store_details); //Get store details
        $default_language = $store_details->store_language; //get store default language
        $data['table'] = $store_details_from_token->table_name;
        //echo $default_language;exit;
        $data['store_informations'] = $store_details; //print_r($data['store_informations']);exit;    
        $data['store_selected_languages'] = $store_details->store_selected_languages; //Selected languages for displaying website
        $data['store_phone'] = $store_details->store_phone; //Selected languages for displaying website
        $country = $store_details->store_country; //echo $country;exit;
        $currency = $this->getCurrency($country);
        //print_r($data['store_selected_languages']);exit;
        $this->load->helper('language');  // Load language helper

        if (isset($this->session->userdata['language'])) {
            $language = $this->session->userdata('language');
        } else {
            $this->session->set_userdata('language', $default_language);
            $language = $this->session->userdata('language');
        }
        //$language = $this->session->userdata('language') ?: 'en'; // Load the language based on session or default to English
        $this->lang->load('labels', $language);
        $data['store_id'] = $store_id;
        $data['language'] = $language;


        $data['categories'] = $this->Productmodel->get_categories_with_products('all',$store_id); //load all categories
        $data['subcategories'] = $this->Productmodel->get_subcategories(); //load all categories
        $cartData = $this->session->userdata('cart');
        $data['cartItems'] = $cartData; //print_r($data['cartItems']);
    
        $this->load->view('website/header', $data); //This is category wised data display
        $this->load->view('website/products_all', $data); //This is all products display
    }
    
    public function getCurrency($country){
        $query = $this->db->get_where('countries', ['country_id' => $country]);
        $result = $query->row_array();
        $this->session->set_userdata('currency_symbol', $result['currency']);
    }

    public function shop($store_id = null, $type = null , $order_no = NULL)
    {
        $data['type'] = $type;
        $store_id = $store_id;
        $this->session->set_userdata('delivery_type', $type);
        $this->session->set_userdata('store_id', $store_id);
        $this->session->set_userdata('order_no', $order_no);
        $delivery_type_phone = $this->Homemodel->getDeliveryTypePhone($store_id,$type);
        $this->session->set_userdata('delivery_type_phone', $delivery_type_phone); //Get phone number by delivery type Pickup or Delivery
        $store_details = $this->Homemodel->get_store_details_by_store_id($store_id); //print_r($store_details); //Get store details
        $default_language = $store_details->store_language; //get store default language
        //$data['table'] = $store_details_from_token->table_name;
        //echo $default_language;exit;
        $data['store_informations'] = $store_details; //print_r($data['store_informations']);exit;    
        $data['store_selected_languages'] = $store_details->store_selected_languages; //Selected languages for displaying website
        $data['store_phone'] = $store_details->store_phone; //Selected languages for displaying website
        //print_r($data['store_selected_languages']);exit;
        $this->load->helper('language');  // Load language helper
    
        $this->isStoreProductAvailable($store_id);
    
        if (isset($this->session->userdata['language'])) {
            $language = $this->session->userdata('language');
        } else {
            $this->session->set_userdata('language', $default_language);
            $language = $this->session->userdata('language');
        }
        //$language = $this->session->userdata('language') ?: 'en'; // Load the language based on session or default to English
        $this->lang->load('labels', $language);
        $data['store_id'] = $store_id;
        $data['language'] = $language;


        $data['categories'] = $this->Productmodel->get_categories_with_products('all',$store_id); //load all categories
        $data['subcategories'] = $this->Productmodel->get_subcategories(); //load all categories
        $cartData = $this->session->userdata('cart');
        $data['cartItems'] = $cartData; //print_r($data['cartItems']);
        $data['allproducts'] = $this->Productmodel->getAllProductsByStore($store_id);
        
        $this->load->view('website/header', $data); //This is category wised data display
        $this->load->view('website/shop', $data); //This is all products display
        //$this->load->view('website/products', $data); //This is category wised data display
    }



   public function loadProducts() {
        $type = '';
        $store_token = $this->session->userdata('store_token');
        $store_details_from_token = $this->Homemodel->get_store_details_by_token($store_token);
        $language = $this->session->userdata('language');
        if($store_token != ''){
            $store_id = $store_details_from_token->store_id;
        }else{
            $store_id = $this->session->userdata('store_id');
        }
        
        $products_by_category_active = [];
        $category_ids_order = $this->Productmodel->getAllCategoriesOrderByStore($store_id);
        foreach ($category_ids_order as $cat_order) {
               $category_id = $cat_order['category_id']; 
               $allproducts = $this->Productmodel->getAllProductsByStoreOrderByType($store_id, $category_id,$type);
               $products_by_category_active[$category_id] = $allproducts;
        }  
        $allproducts = array_merge_recursive($products_by_category_active);
        $inactiveProducts = [];
        $activeProducts = [];

        // Separate products by status
        foreach ($allproducts as $category_id => $products) {
            foreach ($products as $product) {
                if ($product['status'] == 0) {
                    $inactiveProducts[] = $product;
                } elseif ($product['status'] == 1) {
                    $activeProducts[] = $product;
                }
            }
        }

        // Merge the arrays
        $mergedProducts = array_merge($inactiveProducts, $activeProducts);         
 
        
        $cartData = $this->session->userdata('cart');
        $cartItems = $cartData; 
        ?>
<?php $key = 1; ?>

<?php if (!empty($mergedProducts)): ?>
<?php foreach ($mergedProducts as $key => $product): ?>
<div class="user-product-list__items">



    <?php
                    $quantity = 0;
                    if (!empty($cartItems)) {
                        foreach ($cartItems as $item) {
                            if ($item['product_id'] == $product['store_product_id'] && $item['is_addon'] == 0) {
                                $quantity = $item['quantity'];
                                // break;
                            }
                        }
                    }
                    ?>

    <?php 
                    $path = ($product['store_image'] != '') ? site_url() . "uploads/product/" . $product['store_image'] : site_url() . "uploads/product/" . $product['image']; 
                    $product_name = ($product['store_product_name_' . $language] != '') ? $product['store_product_name_' . $language] : $product['product_name_' . $language];
                    $product_desc = ($product['store_product_desc_' . $language] != '') ? $product['store_product_desc_' . $language] : $product['product_desc_' . $language];
                    
                    if ($product['is_customizable'] == 0) {
                        $productRate =  $product['rate'];
                    } else {
                        $productRate =  $this->Homemodel->getCustomizeProductDefaultPrice($product['store_product_id'], $store_id );
                    }
    
                    ?>


    <div class="product-grid">
        <!-- Left Column -->
        <div class="left-column">
            <?php if ($product['product_veg_nonveg'] == 'veg'){ ?>
            <img class="veg" width="10px" src="<?php echo base_url(); ?>/assets/website/images/veg.png">
            <?php }else{ ?>
            <img class="veg" width="10px" src="<?php echo base_url(); ?>/assets/website/images/nonveg.png">
            <?php } ?>
            <a href=""><span class="title"><?php echo $product_name; ?></span></a>
            <span
                class="current"><?php echo $this->session->userdata('currency_symbol'); ?><?php echo $productRate; ?></span>
            <div class="product-description mb-0"><?php echo substr($product_desc, 0, 60); ?>
                <span class="more-text" style="display: none;"><?php echo substr($product_desc, 60); ?></span>
                <span data-bs-toggle="modal" data-bs-target="#productDetails" class="seeMoreBtn pull-right"
                    data-rate="<?php echo $productRate; ?>" data-image="<?php echo $path; ?>"
                    data-desc="<?php echo $product_desc; ?>" data-name="<?php echo $product_name; ?>"
                    data-prodId="<?php echo $product['store_product_id']; ?>"
                    data-id="<?php echo $product['product_id']; ?>">See More</span>
            </div>


            <!-- <div class="product-rating">⭐⭐⭐⭐☆</div> -->
            <div class="price-area">
                <div class="previous text-center txt-red">
                </div>
            </div>
        </div>


        <!-- Right Column -->
        <div class="right-column">


            <?php if($product['is_customizable'] == 1){
    $css_class = ($product['status'] == 1) ? 'disabled-image' : ''; 
    $disabled = ($product['status'] == 1) ? 'disabled' : '';
    
    //If product inactive if remark show else available soon else case quantity > 0 show else ADD
    if ($product['status'] == 1) 
    {
        $btntext = !empty($product['remarks']) ? $product['remarks'] : 'Available Soon';
        $customize_product_quantity = $this->Productmodel->get_customize_product_quantity($cartData , $product['store_product_id'] );
        $quantity_show_class = ($customize_product_quantity > 0) ? 'add-button' : 'add-button';
    } 
    else 
    {
        $customize_product_quantity = $this->Productmodel->get_customize_product_quantity($cartData , $product['store_product_id'] );
        $btntext = ($customize_product_quantity > 0) ? $customize_product_quantity : 'ADD';
        $quantity_show_class = ($customize_product_quantity > 0) ? 'quantity_visible' : 'quantity_hide';
    }
    
    ?>
            <img src="<?php echo $path; ?>" data-bs-toggle="modal" data-bs-target="#productCustomize"
                data-prodId="<?php echo $product['store_product_id']; ?>" data-quantity="<?php echo $quantity; ?>"
                data-id="<?php echo $product['product_id']; ?>" alt="Product Image"
                class="product-image customize <?php echo $css_class; ?>">
            <!-- Add button -->
            <div class="product" data-id="<?php echo $product['product_id']; ?>"
                data-price="<?php echo $productRate; ?>">
                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                <button id="quantity_show_<?php echo $product['store_product_id']; ?>"
                    data-quantity="<?php echo $quantity; ?>" data-prodId="<?php echo $product['store_product_id']; ?>"
                    data-id="<?php echo $product['product_id']; ?>" class="<?php echo $quantity_show_class; ?>"
                    data-bs-toggle="modal" data-bs-target="#productCustomize"
                    <?php echo $disabled; ?>><?php echo $btntext; ?></button>
            </div>
            <?php }else{
        
        $css_class = ($product['status'] == 1) ? 'disabled-image' : ''; 
        $disabled = ($product['status'] == 1) ? 'disabled' : '';
        $btntext = ($product['status'] == 1) ? (!empty($product['remarks']) ? $product['remarks'] : 'Available Soon'): 'ADD'; ?>
            <img src="<?php echo $path; ?>" alt="Product Image" class="product-image <?php echo $css_class; ?>">
            <!-- Add button -->
            <div class="product" data-id="<?php echo $product['store_product_id']; ?>"
                data-price="<?php echo $productRate; ?>"
                data-quantity="<?php if(isset($quantity)){ echo $quantity;}else{ echo 1; } ?>">
                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">

                <button class="add-button add-to-cart" onclick="addToCart(this)"
                    <?php echo $disabled; ?>><?php echo $btntext; ?></button>

                <div class="quantityControls add-button5" style="display: none;display:inline-flex;">
                    <button class="decrement3">-</button>
                    <input type="number" class="prod_qty form-control1" value="1" min="0" readonly>
                    <!-- <span id="quantity" class="prod_qty form-control1">1</span> -->
                    <button class="increment3">+</button>
                </div>



            </div>
            <?php } ?>

            <?php if($product['is_customizable'] == 1){ ?>
            <span class="custom-text">Customisable</span>
            <?php } ?>















        </div>

    </div>
</div>
<?php endforeach; ?>
<?php else: ?>
<p>No products found.</p>
<?php endif; ?>
<!-- loop content end -->


<?php
                    
        
    }
    
    public function getVariantsAndAddons() {
        $product = $this->input->post('prod'); //echo $product;
        $quantity = $this->input->post('quantity'); //echo $store;
        $product_id = $this->input->post('product_id'); //echo $product_id;
        $date = date('Y-m-d');
        $store_id = $this->input->post('store_id'); //echo $store_id;
        $language = $this->input->post('language'); //echo $language;
        $product_details = $this->Productmodel->get_product_by_id($product_id);
        $product_image = $this->Productmodel->get_product_image_by_id($product_id , $store_id);//print_r($product_image);exit;
        $variantsList = $this->Productmodel->getVariants($product,$store_id); //print_r($variants);
        $addonsList = $this->Productmodel->getActiveAndInactiveAddons($product,$store_id); //print_r($addonsList);exit;
        $recipes = $this->Productmodel->getRecipies($product,$store_id);
        $product_name_field = 'product_name_' . $language;
        $product_desc_field = 'product_desc_' . $language;
        $path = 'assets/website/images/';
        $total_stock =  $this->Productmodel->getCurrentStock($product, $date, $store_id);


        // get selected addons
        if(isset($_SESSION['cart'])){
          // print_r($_SESSION['cart']);

            // Separate arrays for variants and addons
$variants = [];
$addons = [];


// Loop through session cart
foreach ($_SESSION['cart'] as $key => $item) {
    if ($item['is_addon'] == 1 && $item['prdParentId'] == $product) {
        // Add to addons array
        $addons[] = [
            'product_id' => $item['product_id'],
            'name' => $item['name'],
            'quantity' => $item['quantity'],
            'price' => $item['price'],
            'image' => $item['image']
        ];
    } elseif ($item['variant_id'] != 0 && $item['prdParentId'] == $product) {
        // Add to variants array
        $variants[] = [
            'variant_id' => $item['variant_id'],
            'name' => $item['name'],
            'quantity' => $item['quantity'],
            'price' => $item['price'],
            'image' => $item['image'],
            'recipe' => $item['recipe']
        ];
    }
}


$addon_total = 0;
$variant_total = 0;


// Calculate total addon amount
if (!empty($addons)) {
    foreach ($addons as $addon) {
       $addon_total += $addon['price'] * $addon['quantity'];
    }
}

// Calculate total variant amount
if (!empty($variants)) {
    foreach ($variants as $variant) {
        $variant_total += $variant['price'] * $variant['quantity'];
        $recipeVar = $variant['recipe'];
    }
}

$addon_variant_total = 0;

    $addon_variant_total = $addon_total + $variant_total;




// Extract product IDs for comparison
$variantIds = array_column($variants, 'variant_id');
$addonIds = array_column($addons, 'product_id');
//print_r($variantIds);print_r($addonIds);
           
        }
        
        ?>
<button type="button" class="btn-close emigo-close-btn" style="width:4rem; height:4rem;margin-right: 1rem;
    margin-top: .5rem;" data-bs-dismiss="modal" aria-label="Close"></button>
<div class="bg-white p-3 mb-3 rounded" style="padding:0;margin:0;">
    <img src="<?php echo site_url() . "uploads/product/" . $product_image; ?>" class="img-fluid rounded w-100"
        alt="Food Imageee">
    <h6 class="mt-2 mb-1"><?php echo $product_details->$product_name_field; ?></h6>
    <p class="mt-2 mb-1"><?php echo $product_details->$product_desc_field; ?></p>
</div>
<style>
.d-none {
    display: none;
}
</style>






<div class="bg-white p-3 mb-3 rounded product">
    <h6 class="mb-1">Choose your variant</h6>
    <div class="mb-3">
        <div class="container p-0">
            <div class="variants mt-3">
                <!-- variant loop start -->
                <?php foreach ($variantsList as $variant1){ ?>
                <div class="variant row" data-price="<?php echo $variant1['rate'];?>"
                    data-varID="<?php echo $variant1['variant_id']; ?>" data-prodId="<?php echo $product; ?>">
                    <div class="col-5 mt-3">
                        <label class="variantName"><?php echo $variant1['variant_name']; ?>
                            (<?php echo $this->session->userdata('currency_symbol'); ?><?php echo $variant1['rate'];?>)</label>
                    </div>

                    <!-- Quantity Control -->
                    <?php
   if(isset($_SESSION['cart'])){
       $currentQuantity = $this->Productmodel->getVariantQuantity($variant1['variant_id'], $_SESSION['cart'] , $product);
       if($currentQuantity > 0){
           $quantity = $currentQuantity;
       }else{
           $quantity = '';
       }
   }
   ?>
                    <div class="col-4">
                        <div class="quantity-control d-flex align-items-center add-button1 mt-1">
                            <button data-varValue="<?php echo $variant1['variant_value']; ?>"
                                class="add-btn">Add</button>
                            <div class="d-none quantity-group d-flex align-items-center">
                                <button data-prodId="<?php echo $product; ?>"
                                    data-varValue="<?php echo $variant1['variant_value']; ?>"
                                    data-varCode="<?php echo $variant1['code']; ?>" class=" btn-sm decrement">-</button>
                                <input type="text" class="form-control1 d-inline-block variant-qty"
                                    value="<?php echo $quantity; ?>" min="0">
                                <button data-prodId="<?php echo $product; ?>"
                                    data-varValue="<?php echo $variant1['variant_value']; ?>"
                                    data-varCode="<?php echo $variant1['code']; ?>" class="btn-sm increment">+</button>
                            </div>
                        </div>

                    </div>

                    <div class="col-3 text-center mt-3">
                        <span class="variant-total variantName"></span>
                        <?php 
    $quantity = (float) $quantity; // Ensure it's a number
    $variant_value = isset($variant1['variant_value']) ? (float) $variant1['variant_value'] : 0; // Ensure it's a number

    $total = $quantity * $variant_value; // Perform multiplication safely
    $total > 0 ? $total : 0;
    ?>
                        <input type="hidden" class="variant-total-hidden" value="<?= $total; ?>">
                        <input type="hidden" id="total_stock" value="<?= $total_stock; ?>">
                    </div>

                </div>
                <?php } ?>
                <!-- Variant loop end -->
            </div>
        </div>
    </div>
</div>






<?php if(!empty($addonsList)){ ?>
<div class="bg-white p-3 mb-3 rounded product">
    <h6 class="mb-1">Choose your addon</h6>
    <div class="mb-3">
        <div class="container p-0">
            <div class="variants mt-3">
                <!-- variant loop start -->
                <?php foreach ($addonsList as $addon){
                    
                    $css_class = ($addon['status'] == 1) ? 'disabled-image' : ''; 
                    $disabled = ($addon['status'] == 1) ? 'disabled' : '';
                    $btntext = ($addon['status'] == 1) ? (!empty($addon['remarks']) ? $addon['remarks'] : 'Available Soon'): 'ADD';
                    ?>
                <div class="addon row" data-id="<?php echo $addon['addon_item_id']; ?>"
                    data-price="<?php echo $addon['rate']; ?>" data-prodId="<?php echo $product; ?>">
                    <div class="col-2 mt-2">
                        <img src="<?php echo site_url() ?>uploads/product/<?php echo $addon['prod_image']; ?>"
                            style="width:50px;height:40px;" class="img-fluid rounded <?php echo $css_class; ?>"
                            alt="Food Image">
                    </div>
                    <div class="col-4 mt-2">
                        <label class="mt-1 variantName"><?php echo $addon['product_name_en']; ?>
                            (<?php echo $this->session->userdata('currency_symbol'); ?><?php echo $addon['rate']; ?>)</label>
                    </div>
                    <?php
   if(isset($_SESSION['cart'])){
       $currentQuantity = $this->Productmodel->getAddonQuantity($addon['addon_item_id'], $_SESSION['cart'] , $product);
       if($currentQuantity > 0){
           $quantity = $currentQuantity;
       }else{
           $quantity = '';
       }
   }
   ?>
                    <!-- Quantity Control -->
                    <div class="col-4">
                        <div class="quantity-control1 d-flex align-items-center add-button1 mt-1">
                            <button class="add-btn1" <?php echo $disabled; ?>>Add</button>
                            <div class="d-none quantity-group1 d-flex align-items-center">
                                <button class="btn-sm decrement1">-</button>
                                <input type="number" class="form-control1 d-inline-block addon-qty"
                                    value="<?php echo $quantity; ?>" min="0">
                                <button data-prodId="<?php echo $addon['addon_item_id']; ?>"
                                    class="btn-sm increment1">+</button>
                            </div>
                        </div>
                    </div>

                    <!-- Variant Total -->
                    <div class="col-2 text-center mt-3">
                        <span class="addon-total mt-1 variantName"></span>
                    </div>
                </div>
                <?php } ?>
                <!-- Variant loop end -->
            </div>
        </div>
    </div>
</div>
</div>
<?php } ?>



<div class="bg-white p-3 mb-3 rounded mb-50">
    <h6 class="mb-1">Add a cooking request (Optional)</h6>
    <div class="mb-3">
        <textarea id="output-textarea" class="textarea-cook-req mb-2" placeholder="Enter your text here..."><?php if (!empty($variants)) {
    foreach ($variants as $variant) {
         $recipeVar = $variant['recipe'];
    }
    echo $recipeVar;
} ?></textarea>
        <?php foreach ($recipes as $recipe): ?>
        <span onclick="copyToClipboard(this)" class="clickable-span recipe"><?php echo $recipe['name_en']; ?></span>
        <?php endforeach; ?>
    </div>
</div>

<div class="modal-button-container_add_to_cart">
    <div class="modal-footer modal-footer-fixed ">
        <button type="button" data-bs-dismiss="modal" data-bs-target="#productCustomize"
            data-id="<?php echo $product; ?>" class="btn btn-primary full-width-btn w-100 add-to-cart-popup">ADD ITEM -
            <span
                id="total-price">₹<?php if(isset($addon_variant_total)){   echo $addon_variant_total; }else{ echo 0;} ?></span></button>
    </div>
</div>
</div>





</div>
</div>
</div>


</div>

<?php

    }
    

    // Function to display a product in the selected language
    public function view($product_id) {
        // Get language from session or default to 'en'
        $language = $this->session->userdata('language') ?: 'en';

        // Get product and translation data
        $product = $this->Productmodel->get_product($product_id, $language);

        // Pass data to view
        $this->load->view('product_view', ['product' => $product]);
    }

    // Function to change the language
    public function set_language($language) {
        $this->session->set_userdata('language', $language);
        redirect($_SERVER['HTTP_REFERER']);
    }






    

    public function loadProductsCategoryFilter() { //Filter by veg and non-veg
        //$store_token = $this->session->userdata('store_token');
       // $store_details_from_token = $this->Homemodel->get_store_details_by_token($store_token);
        $language = $this->session->userdata('language');
        $store_id = $this->input->post('store_id');
        $type = $this->input->post('category'); //veg or nonveg
        $catname = $this->input->post('catname');
       // echo $type;exit;

        $products_by_category_active = [];
        
        $category_id = $type; 
        $allproducts = $this->Productmodel->getAllProductsByStoreOrderByCategory($store_id, $category_id);
        $inactiveProducts = [];
        $activeProducts = [];

        foreach ($allproducts as $category_id => $product) {
                    if ($product['status'] == 0) {
                        $inactiveProducts[] = $product;
                    } elseif ($product['status'] == 1) {
                        $activeProducts[] = $product;
                    }
        }


        // Merge the arrays
        $allproducts = array_merge($inactiveProducts, $activeProducts);
          
        $cartData = $this->session->userdata('cart');
        $cartItems = $cartData; 
        
        // if($catname == 'All'){
            
        // }
        ?>
<input type="hidden" name="category_id" id="hidden_category_id" value="<?php echo $type; ?>">
<?php $key = 1; ?>

<!-- loop content -->
<?php if (!empty($allproducts)): ?>
<?php foreach ($allproducts as $category_id => $product): ?>

<div class="user-product-list__items">



    <?php
                    $quantity = 0;
                    if (!empty($cartItems)) {
                        foreach ($cartItems as $item) {
                            if ($item['product_id'] == $product['store_product_id'] && $item['is_addon'] == 0) {
                                $quantity = $item['quantity'];
                                // break;
                            }
                        }
                    }
                    ?>

    <?php 
                    $path = ($product['store_image'] != '') ? site_url() . "uploads/product/" . $product['store_image'] : site_url() . "uploads/product/" . $product['image']; 
                    $product_name = ($product['store_product_name_' . $language] != '') ? $product['store_product_name_' . $language] : $product['product_name_' . $language];
                    $product_desc = ($product['store_product_desc_' . $language] != '') ? $product['store_product_desc_' . $language] : $product['product_desc_' . $language];
                    
                    if ($product['is_customizable'] == 0) {
                        $productRate =  $product['rate'];
                    } else {
                        $productRate =  $this->Homemodel->getCustomizeProductDefaultPrice($product['store_product_id'], $store_id );
                    }
    
                    ?>


    <div class="product-grid">
        <!-- Left Column -->
        <div class="left-column">
            <?php if ($product['product_veg_nonveg'] == 'veg'){ ?>
            <img class="veg" width="10px" src="<?php echo base_url(); ?>/assets/website/images/veg.png">
            <?php }else{ ?>
            <img class="veg" width="10px" src="<?php echo base_url(); ?>/assets/website/images/nonveg.png">
            <?php } ?>
            <a href=""><span class="title"><?php echo $product_name; ?></span></a>
            <span
                class="current"><?php echo $this->session->userdata('currency_symbol'); ?><?php echo $productRate; ?></span>
            <div class="product-description mb-0"><?php echo substr($product_desc, 0, 60); ?>
                <span class="more-text" style="display: none;"><?php echo substr($product_desc, 60); ?></span>
                <span data-bs-toggle="modal" data-bs-target="#productDetails" class="seeMoreBtn pull-right"
                    data-rate="<?php echo $productRate; ?>" data-image="<?php echo $path; ?>"
                    data-desc="<?php echo $product_desc; ?>" data-name="<?php echo $product_name; ?>"
                    data-prodId="<?php echo $product['store_product_id']; ?>"
                    data-id="<?php echo $product['product_id']; ?>">See More</span>
            </div>


            <!-- <div class="product-rating">⭐⭐⭐⭐☆</div> -->
            <div class="price-area">
                <div class="previous text-center txt-red">
                </div>
            </div>
        </div>


        <!-- Right Column -->
        <div class="right-column">

            <?php if($product['is_customizable'] == 1){
    $css_class = ($product['status'] == 1) ? 'disabled-image' : ''; 
    $disabled = ($product['status'] == 1) ? 'disabled' : '';
    //If product inactive if remark show else available soon else case quantity > 0 show else ADD
    if ($product['status'] == 1) 
    {
        $btntext = !empty($product['remarks']) ? $product['remarks'] : 'Available Soon';
        $customize_product_quantity = $this->Productmodel->get_customize_product_quantity($cartData , $product['store_product_id'] );
        $quantity_show_class = ($customize_product_quantity > 0) ? 'add-button' : 'add-button';
    } 
    else 
    {
        $customize_product_quantity = $this->Productmodel->get_customize_product_quantity($cartData , $product['store_product_id'] );
        $btntext = ($customize_product_quantity > 0) ? $customize_product_quantity : 'ADD';
        $quantity_show_class = ($customize_product_quantity > 0) ? 'quantity_visible' : 'quantity_hide';
    }
    ?>
            <img src="<?php echo $path; ?>" data-bs-toggle="modal" data-bs-target="#productCustomize"
                data-prodId="<?php echo $product['store_product_id']; ?>" data-quantity="<?php echo $quantity; ?>"
                data-id="<?php echo $product['product_id']; ?>" alt="Product Image"
                class="product-image customize <?php echo $css_class; ?>">
            <!-- Add button -->
            <div class="product" data-id="<?php echo $product['product_id']; ?>"
                data-price="<?php echo $productRate; ?>">
                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                <button data-quantity="<?php echo $quantity; ?>"
                    data-prodId="<?php echo $product['store_product_id']; ?>"
                    data-id="<?php echo $product['product_id']; ?>" class="<?php echo $quantity_show_class; ?>"
                    data-bs-toggle="modal" data-bs-target="#productCustomize"
                    <?php echo $disabled; ?>><?php echo $btntext; ?></button>
            </div>
            <?php }else{
        $css_class = ($product['status'] == 1) ? 'disabled-image' : ''; 
        $disabled = ($product['status'] == 1) ? 'disabled' : ''; 
        ?>
            <img src="<?php echo $path; ?>" alt="Product Image" class="product-image <?php echo $css_class; ?>">
            <!-- Add button -->
            <div class="product" data-id="<?php echo $product['store_product_id']; ?>"
                data-price="<?php echo $productRate; ?>"
                data-quantity="<?php if(isset($quantity)){ echo $quantity;}else{ echo 1; } ?>">
                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">

                <button class="add-button add-to-cart" onclick="addToCart(this)" <?php echo $disabled; ?>>ADD</button>

                <div class="quantityControls add-button5" style="display: none;display:inline-flex;">
                    <button class="decrement3">-</button>
                    <input type="number" class="prod_qty form-control1" value="1" min="0" readonly>
                    <!-- <span id="quantity">1</span> -->
                    <button class="increment3">+</button>
                </div>



            </div>
            <?php } ?>
            <?php if($product['is_customizable'] == 1){ ?>
            <!--<span class="custom-text">Customisable</span>-->
            <?php } ?>
        </div>

    </div>
</div>
<?php endforeach; ?>

<?php else: ?>
<p>No products found.</p>
<?php endif; ?>
<!-- loop content end -->


<?php
        
    }
    
    public function loadProductssubCategoryFilter() { //Filter by veg and non-veg
        //$store_token = $this->session->userdata('store_token');
       // $store_details_from_token = $this->Homemodel->get_store_details_by_token($store_token);
        $language = $this->session->userdata('language');
        $store_id = $this->input->post('store_id');
        $category = $this->input->post('category');
        $subcategory = $this->input->post('subcategory');
        $catname = $this->input->post('catname');
        $category_ids_order = $this->Productmodel->getAllCategoriesOrderByStore($store_id);//print_r($category_ids_order);
        $cartData = $this->session->userdata('cart');
        $cartItems = $cartData; 
        
        $products_by_category_active = [];
        $category_ids_order = $this->Productmodel->getAllCategoriesOrderByStore($store_id);
        foreach ($category_ids_order as $cat_order) {
               $category_id = $cat_order['category_id']; 
               $allproducts = $this->Productmodel->getAllProductsByStoreOrderByType($store_id, $category_id,$type);
               $products_by_category_active[$category_id] = $allproducts;
        }  
        $allproducts = array_merge_recursive($products_by_category_active);
        $inactiveProducts = [];           
        $activeProducts = [];

        // Separate products by status
        foreach ($allproducts as $category_id => $products) {
            foreach ($products as $product) {
                if ($product['status'] == 0) {
                    $inactiveProducts[] = $product;
                } elseif ($product['status'] == 1) {
                    $activeProducts[] = $product;
                }
            }
        }
        $mergedProducts = array_merge($inactiveProducts, $activeProducts);
        ?>

<input type="hidden" name="category_id" id="hidden_category_id" value="<?php echo $category; ?>">
<?php $key = 1; ?>

<!-- loop content -->
<?php foreach($category_ids_order as $cat_order){
                        $allproducts = $this->Productmodel->loadProductssubCategoryFilter($store_id , $category ,$subcategory, $cat_order['category_id']);
                        ?>
<?php if (!empty($allproducts)): ?>
<?php foreach ($allproducts as $product): ?>
<div class="user-product-list__items">



    <?php
                    $quantity = 0;
                    if (!empty($cartItems)) {
                        foreach ($cartItems as $item) {
                            if ($item['product_id'] == $product['store_product_id'] && $item['is_addon'] == 0) {
                                $quantity = $item['quantity'];
                                // break;
                            }
                        }
                    }
                    ?>


    <?php 
                    $path = ($product['store_image'] != '') ? site_url() . "uploads/product/" . $product['store_image'] : site_url() . "uploads/product/" . $product['image']; 
                    $product_name = ($product['store_product_name_' . $language] != '') ? $product['store_product_name_' . $language] : $product['product_name_' . $language];
                    $product_desc = ($product['store_product_desc_' . $language] != '') ? $product['store_product_desc_' . $language] : $product['product_desc_' . $language];
                    
                    if ($product['is_customizable'] == 0) {
                        $productRate =  $product['rate'];
                    } else {
                        $productRate =  $this->Homemodel->getCustomizeProductDefaultPrice($product['store_product_id'], $store_id );
                    }
    
                    ?>


    <div class="product-grid">
        <!-- Left Column -->
        <div class="left-column">
            <?php if ($product['product_veg_nonveg'] == 'veg'){ ?>
            <img class="veg" width="10px" src="<?php echo base_url(); ?>/assets/website/images/veg.png">
            <?php }else{ ?>
            <img class="veg" width="10px" src="<?php echo base_url(); ?>/assets/website/images/nonveg.png">
            <?php } ?>
            <a href=""><span class="title"><?php echo $product_name; ?></span></a>
            <span
                class="current"><?php echo $this->session->userdata('currency_symbol'); ?><?php echo $productRate; ?></span>
            <div class="product-description mb-0"><?php echo substr($product_desc, 0, 60); ?>
                <span class="more-text" style="display: none;"><?php echo substr($product_desc, 60); ?></span>
                <span data-bs-toggle="modal" data-bs-target="#productDetails" class="seeMoreBtn pull-right"
                    data-rate="<?php echo $productRate; ?>" data-image="<?php echo $path; ?>"
                    data-desc="<?php echo $product_desc; ?>" data-name="<?php echo $product_name; ?>"
                    data-prodId="<?php echo $product['store_product_id']; ?>"
                    data-id="<?php echo $product['product_id']; ?>">See More</span>
            </div>


            <!-- <div class="product-rating">⭐⭐⭐⭐☆</div> -->
            <div class="price-area">
                <div class="previous text-center txt-red">
                </div>
            </div>
        </div>


        <!-- Right Column -->
        <div class="right-column">

            <?php if($product['is_customizable'] == 1){
        $stock = $this->Ordermodel->getCurrentStock($product['store_product_id'], date('Y-m-d'), $store_id);
        $css_class = ($product['is_active'] == 1 || $stock <= 0) ? 'disabled-image' : ''; 
        $disabled = ($product['is_active'] == 1 || $stock <= 0) ? 'disabled' : ''; ?>
            <img src="<?php echo $path; ?>" data-bs-toggle="modal" data-bs-target="#productCustomize"
                data-prodId="<?php echo $product['store_product_id']; ?>" data-quantity="<?php echo $quantity; ?>"
                data-id="<?php echo $product['product_id']; ?>" alt="Product Image"
                class="product-image customize <?php echo $css_class; ?>">
            <!-- Add button -->
            <div class="product" data-id="<?php echo $product['product_id']; ?>"
                data-price="<?php echo $product['rate']; ?>">
                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                <button data-quantity="<?php echo $quantity; ?>"
                    data-prodId="<?php echo $product['store_product_id']; ?>"
                    data-id="<?php echo $product['product_id']; ?>" class="add-button" data-bs-toggle="modal"
                    data-bs-target="#productCustomize" <?php echo $disabled; ?>>ADD</button>
            </div>
            <?php }else{ ?>
            <img src="<?php echo $path; ?>" alt="Product Image" class="product-image <?php echo $css_class; ?>">
            <!-- Add button -->
            <div class="product" data-id="<?php echo $product['store_product_id']; ?>"
                data-price="<?php echo $product['rate']; ?>"
                data-quantity="<?php if(isset($quantity)){ echo $quantity;}else{ echo 1; } ?>">
                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">

                <button class="add-button add-to-cart" onclick="addToCart(this)" <?php echo $disabled; ?>>ADD</button>

                <div class="quantityControls add-button5" style="display: none;display:inline-flex;">
                    <button class="decrement3">-</button>
                    <input type="number" class="prod_qty form-control1" value="1" min="0" readonly>
                    <!-- <span id="quantity">1</span> -->
                    <button class="increment3">+</button>
                </div>


            </div>
            <?php } ?>
            <?php if($product['is_customizable'] == 1){ ?>
            <span class="custom-text">Customisable</span>
            <?php } ?>

        </div>

    </div>
</div>

<?php endforeach; ?>
<?php else: ?>
<!--<p>No products found.</p>-->
<?php endif; ?>
<!-- loop content end -->
<?php } ?>


<?php
        
    }

    
        
    public function loadProductsTypeFilter() { //Filter by veg and non-veg
        //$store_token = $this->session->userdata('store_token');
       // $store_details_from_token = $this->Homemodel->get_store_details_by_token($store_token);
        $language = $this->session->userdata('language');
        $hidden_category_id = $this->input->post('hidden_category_id');
        $store_id = $this->input->post('store_id');
        $type = $this->input->post('type');

        $products_by_category_active = [];
        $category_ids_order = $this->Productmodel->getAllCategoriesOrderByStore($store_id);
        foreach ($category_ids_order as $cat_order) {
               $category_id = $cat_order['category_id']; 
               $allproducts = $this->Productmodel->getAllProductsByStoreOrderByType($store_id, $category_id,$type);
               $products_by_category_active[$category_id] = $allproducts;
        }  
        $allproducts = array_merge_recursive($products_by_category_active);
        $inactiveProducts = [];
        $activeProducts = [];

        // Separate products by status
        foreach ($allproducts as $category_id => $products) {
            foreach ($products as $product) {
                if ($product['status'] == 0) {
                    $inactiveProducts[] = $product;
                } elseif ($product['status'] == 1) {
                    $activeProducts[] = $product;
                }
            }
        }
        $mergedProducts = array_merge($inactiveProducts, $activeProducts);

        $cartData = $this->session->userdata('cart');
        $cartItems = $cartData; 
        ?>
<?php $key = 1; ?>


<!-- loop content -->

<?php if (!empty($allproducts)): ?>
<?php foreach ($mergedProducts as $key => $product): ?>
<div class="user-product-list__items">



    <?php
                    $quantity = 0;
                    if (!empty($cartItems)) {
                        foreach ($cartItems as $item) {
                            if ($item['product_id'] == $product['store_product_id'] && $item['is_addon'] == 0) {
                                $quantity = $item['quantity'];
                                // break;
                            }
                        }
                    }
                    ?>

    <?php 
                    $path = ($product['store_image'] != '') ? site_url() . "uploads/product/" . $product['store_image'] : site_url() . "uploads/product/" . $product['image']; 
                    $product_name = ($product['store_product_name_' . $language] != '') ? $product['store_product_name_' . $language] : $product['product_name_' . $language];
                    $product_desc = ($product['store_product_desc_' . $language] != '') ? $product['store_product_desc_' . $language] : $product['product_desc_' . $language];
                    
                    if ($product['is_customizable'] == 0) {
                        $productRate =  $product['rate'];
                    } else {
                        $productRate =  $this->Homemodel->getCustomizeProductDefaultPrice($product['store_product_id'], $store_id );
                    }
    
                    ?>


    <div class="product-grid">
        <!-- Left Column -->
        <div class="left-column">
            <?php if ($product['product_veg_nonveg'] == 'veg'){ ?>
            <img class="veg" width="10px" src="<?php echo base_url(); ?>/assets/website/images/veg.png">
            <?php }else{ ?>
            <img class="veg" width="10px" src="<?php echo base_url(); ?>/assets/website/images/nonveg.png">
            <?php } ?>
            <a href=""><span class="title"><?php echo $product_name; ?></span></a>
            <span
                class="current"><?php echo $this->session->userdata('currency_symbol'); ?><?php echo $productRate; ?></span>
            <div class="product-description mb-0"><?php echo substr($product_desc, 0, 60); ?>
                <span class="more-text" style="display: none;"><?php echo substr($product_desc, 60); ?></span>
                <span data-bs-toggle="modal" data-bs-target="#productDetails" class="seeMoreBtn pull-right"
                    data-rate="<?php echo $productRate; ?>" data-image="<?php echo $path; ?>"
                    data-desc="<?php echo $product_desc; ?>" data-name="<?php echo $product_name; ?>"
                    data-prodId="<?php echo $product['store_product_id']; ?>"
                    data-id="<?php echo $product['product_id']; ?>">See More</span>
            </div>




        </div>


        <!-- Right Column -->
        <div class="right-column">

            <?php if($product['is_customizable'] == 1){
    $css_class = ($product['status'] == 1 ) ? 'disabled-image' : ''; 
    $disabled = ($product['status'] == 1) ? 'disabled' : '';
    //If product inactive if remark show else available soon else case quantity > 0 show else ADD
    if ($product['status'] == 1) 
    {
        $btntext = !empty($product['remarks']) ? $product['remarks'] : 'Available Soon';
        $customize_product_quantity = $this->Productmodel->get_customize_product_quantity($cartData , $product['store_product_id'] );
        $quantity_show_class = ($customize_product_quantity > 0) ? 'add-button' : 'add-button';
    } 
    else 
    {
        $customize_product_quantity = $this->Productmodel->get_customize_product_quantity($cartData , $product['store_product_id'] );
        $btntext = ($customize_product_quantity > 0) ? $customize_product_quantity : 'ADD';
        $quantity_show_class = ($customize_product_quantity > 0) ? 'quantity_visible' : 'quantity_hide';
    }
    ?>
            <img src="<?php echo $path; ?>" data-bs-toggle="modal" data-bs-target="#productCustomize"
                data-prodId="<?php echo $product['store_product_id']; ?>" data-quantity="<?php echo $quantity; ?>"
                data-id="<?php echo $product['product_id']; ?>" alt="Product Image"
                class="product-image customize <?php echo $css_class; ?>">
            <!-- Add button -->
            <div class="product" data-id="<?php echo $product['product_id']; ?>"
                data-price="<?php echo $productRate; ?>">
                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                <button data-quantity="<?php echo $quantity; ?>"
                    data-prodId="<?php echo $product['store_product_id']; ?>"
                    data-id="<?php echo $product['product_id']; ?>" class="<?php echo $quantity_show_class; ?>"
                    data-bs-toggle="modal" data-bs-target="#productCustomize"
                    <?php echo $disabled; ?>><?php echo $btntext; ?></button>
            </div>
            <?php }else{
        $css_class = ($product['status'] == 1 ) ? 'disabled-image' : ''; 
        $disabled = ($product['status'] == 1 ) ? 'disabled' : '';
        ?>
            <img src="<?php echo $path; ?>" alt="Product Image" class="product-image <?php echo $css_class; ?>">
            <!-- Add button -->
            <div class="product" data-id="<?php echo $product['store_product_id']; ?>"
                data-price="<?php echo $productRate; ?>"
                data-quantity="<?php if(isset($quantity)){ echo $quantity;}else{ echo 1; } ?>">
                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">

                <button class="add-button add-to-cart" onclick="addToCart(this)" <?php echo $disabled; ?>>ADD</button>

                <div class="quantityControls add-button5" style="display: none;display:inline-flex;">
                    <button class="decrement3">-</button>
                    <input type="number" class="prod_qty form-control1" value="1" min="0" readonly>
                    <!-- <span id="quantity">1</span> -->
                    <button class="increment3">+</button>
                </div>



            </div>
            <?php } ?>
            <?php if($product['is_customizable'] == 1){ ?>
            <!--<span class="custom-text">Customisable</span>-->
            <?php } ?>
        </div>

    </div>
</div>
<?php endforeach; ?>
<?php else: ?>
<p>No products found.</p>
<?php endif; ?>
<!-- loop content end -->


<?php
  
    }
    
    

    
    
     public function clear_session() {
        $this->session->sess_destroy();
    }

    public function isStoreProductAvailable($store_id) {
        $today = date('Y-m-d');
        $current_time = date('H:i:s');//exit;
        $this->db->select('store_opening_time,store_closing_time,today_opening_time,today_closing_time,is_order_close');
        $this->db->from('store');
        $this->db->where('store_id', $store_id);
        $query = $this->db->get();
        $row = $query->row();
        if (!empty($row->today_opening_time) && !empty($row->today_closing_time)) {
            $opening_time = $row->today_opening_time;
            $closing_time = $row->today_closing_time;
        } else {
            $opening_time = $row->store_opening_time;
            $closing_time = $row->store_closing_time;
        }

        $holiday = $this->Homemodel->isTodayHoliday($today);
        if (!empty($holiday)) {
            //echo "h";exit;
            $this->Homemodel->changeStoreProductStatusInactive($store_id,'1');
            return;
        }else{
            //echo "nh";exit;
            $this->Homemodel->changeStoreProductStatusInactive($store_id,'0');
        }
        
        
        // if($current_time >= $opening_time && $current_time <= $closing_time ) 
        if($current_time >= $opening_time && $row->is_order_close == 1) 
        {
           $this->Homemodel->changeStoreProductStatusInactive($store_id,'0');
        } 
        else 
        {
            //echo 'Store is closed';
            $this->Homemodel->changeStoreProductStatusInactive($store_id, '1');
        }

        
    }
    
}
?>
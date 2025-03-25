<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cartcontroller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('website/Productmodel');
        $this->load->model('website/Homemodel');
         $this->load->model('owner/Ordermodel');
        //session_start();
    }

    public function index() {
        $this->load->view('order_view');
    }

    public function add() {
        $product_id = $this->input->post('product_id');
        $prdParentId = $this->input->post('prdParentId');
        $quantity = $this->input->post('quantity');
        $price = $this->input->post('price');
        $recipe = $this->input->post('recipe');
        $variant_id = $this->input->post('variant_id');
        $is_addon = $this->input->post('addon');
        $product_price = $price; // Example price calculation 
        $product = $this->Productmodel->get_store_product_by_id($product_id);
        $product_name = $product[0]['product_name_en'];
        $product_image = base_url() . 'uploads/product/' . $product[0]['image'];
    
        
        // If the cart does not contain the product
        if (!isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] = [
                'prdParentId' => $prdParentId,
                'product_id' => $product_id,
                'quantity' => $quantity,
                'name' => $product_name,
                'image' => $product_image,
                'is_addon' => $is_addon,
                'recipe' => $recipe,
                'variant_id' => $variant_id,
                'variant_name' => $this->Productmodel->get_variant_name($variant_id),
                'variant_code' => $this->Productmodel->get_variant_code($variant_id),
                'variant_value' => $this->Productmodel->get_variant_value($variant_id,$product_id,$store_id),
                'price' => $product_price
            ];
        } else {
            // If the product is already in the cart
            if ($_SESSION['cart'][$product_id]['is_addon'] == $is_addon) {
                // If the is_addon value matches, update the quantity
                $_SESSION['cart'][$product_id]['quantity'] += $quantity;
            } else {
                // If the is_addon value does not match, create a new entry
                $_SESSION['cart'][$product_id . '_' . $is_addon] = [
                    'prdParentId' => $product_id,
                    'product_id' => $product_id,
                    'quantity' => $quantity,
                    'name' => $product_name,
                    'image' => $product_image,
                    'is_addon' => $is_addon,
                    'price' => $product_price
                ];
            }
        }
    }

    public function addvariant() {
        $variantIds = $this->input->post('variantIds');
        //$addonIds = $this->input->post('addonIds');
        $product_id = $this->input->post('product_id');
        $prdParentId = $this->input->post('prdParentId');
        $quantity = $this->input->post('quantity');
        $price = $this->input->post('price');
        $recipe = $this->input->post('recipe');
        $variant_id = $this->input->post('variant_id');
        $store_id = 41;
        $is_addon = $this->input->post('addon');
        $product_price = $price; // Example price calculation 
        $product = $this->Productmodel->get_store_product_by_id($product_id);
        $product_name = $product[0]['product_name_en'];
        $product_image = base_url() . 'uploads/product/' . $product[0]['image'];
        foreach ($variantIds as $key=> $variant) {
            $_SESSION['cart'][$product_id.$key] = [
                'prdParentId' => $prdParentId,
                'quantity' => $variant['qty'],
                'product_id' => $product_id,
                'name' => $product_name,
                'image' => $product_image,
                'is_addon' => $is_addon,
                'recipe' => $recipe,
                'variant_id' => $variant['id'],
                'variant_name' => $this->Productmodel->get_variant_name($variant['id']),
                'variant_code' => $this->Productmodel->get_variant_code($variant['id']),
                'variant_value' => $this->Productmodel->get_variant_value($variant['id'],$product_id,$store_id),
                'price' => $variant['price']
            ]; 
        }
    }

    public function addaddon() {
        //echo "herrrrrrrr";exit;
        $addonIds = $this->input->post('addonIds');
        //$addonIds = $this->input->post('addonIds');
        $product_id = $this->input->post('product_id');
        $prdParentId = $this->input->post('prdParentId');
        $quantity = $this->input->post('quantity');
        $price = $this->input->post('price');
        $recipe = $this->input->post('recipe');
        $variant_id = $this->input->post('variant_id');
        $is_addon = $this->input->post('addon');
        $product_price = $price; // Example price calculation 
        if(!empty($addonIds))
        {
            foreach ($addonIds as $key=> $addon) 
            {
                $product = $this->Productmodel->get_store_product_by_id($addon['id']);
                $product_name = $product[0]['product_name_en'];
                $product_image = base_url() . 'uploads/product/' . $product[0]['image'];
                $_SESSION['cart'][$product_id.$key.$key] = [
                    'prdParentId' => $prdParentId,
                    'quantity' => $addon['qty'],
                    'product_id' => $addon['id'],
                    'name' => $product_name,
                    'image' => $product_image,
                    'is_addon' => $is_addon,
                    'recipe' => '',
                    'variant_id' => 0,
                    'price' => $addon['price']
                ]; 
            }
            
            echo $customize_product_quantity = $this->Productmodel->get_customize_product_quantity($_SESSION['cart'] , $prdParentId );
            
        }
        else
        {
            echo $customize_product_quantity = $this->Productmodel->get_customize_product_quantity($_SESSION['cart'] , $prdParentId );
        }
    }


    public function updateQuantity() {
        if($this->input->post('store_productId'))
        {
            $store_productId = $this->input->post('store_productId');
        }
        else
        {
            $store_productId = $this->input->post('product_id'); 
        }
       
       
        $product_id = $this->input->post('product_id'); // edit row id
        $quantity = $this->input->post('quantity');
        $store_id = $this->input->post('store_id');

        // Check if the product stock qty available
        $productDetails = $this->Productmodel->get_store_wise_product_by_id($store_productId);
        
        $date = date('Y-m-d');
        //Check if the product is a combo
        $is_combo = $this->Productmodel->productIsCombo($store_productId);

        if($is_combo)
        {
            //echo 'combo';
            // Get combo components
            $comboItems = $this->Productmodel->getComboItems($store_id,$store_productId);
            foreach ($comboItems as $item) 
            {
                $availableStock = $this->Productmodel->getCurrentStock($item['item_id'], $date, $store_id);
                      
                // Check if requested quantity of combo item exceeds available stock
                if ($quantity * $item['quantity'] > $availableStock) 
                {
                    echo $this->Productmodel->getProductName($item['item_id']) .' is out of stock.';
                    return;
                }
                else
                {
                    echo 'success';
                }
            }
        }
        if($productDetails[0]['is_customizable'] == 1)
        {
           
            $variant_value = $this->input->post('variant_value');
            $variant_code = $this->input->post('variant_code');
            $availableStock = $this->Productmodel->getCurrentStock($store_productId, $date, $store_id); // stock count within base quantity (Quarter)
            $order_qty = (int)$quantity;

            if($order_qty > $availableStock) 
            {
                echo $this->Productmodel->getProductName($store_productId) .'(Q) is out of stock.';
                return true;
            }
            else
            {
                echo 'success';
            }
        }
        if($productDetails[0]['is_customizable'] == 0)
        {
            //echo 'product';
            $availableStock = $this->Productmodel->getCurrentStock($store_productId, $date, $store_id);

            // Check if the requested quantity exceeds available stock
            if ($quantity > $availableStock) 
            {
                echo $this->Productmodel->getProductName($store_productId) .' is out of stock.';
                return;
            }
            else
            {
                echo 'success';
            }
        }
              

        if (isset($_SESSION['cart'][$product_id])) 
        {
            $_SESSION['cart'][$product_id]['quantity'] = $quantity;

            if ($_SESSION['cart'][$product_id]['quantity'] <= 0) 
            {
                unset($_SESSION['cart'][$product_id]);
            }
        }
    }

    //In cart page decrement quantity
    public function updateCartQuantityOutOfStock() {
        $product_id = $this->input->post('product_id');
        $quantity = $this->input->post('quantity');
        $store_id = $this->input->post('store_id');
        if (isset($_SESSION['cart'][$product_id])) 
        {
            $_SESSION['cart'][$product_id]['quantity'] = $quantity;

            if ($_SESSION['cart'][$product_id]['quantity'] <= 0) 
            {
                unset($_SESSION['cart'][$product_id]);
            }
        }
    }

    public function deleteparent() {
        $product_id = $this->input->post('product_id');

        // Loop through the session cart array
foreach ($_SESSION['cart'] as $key => $item) {
    // Check if the item has the specified parent_id
    if (isset($item['prdParentId']) && $item['prdParentId'] == $product_id) {
        // Unset the item from the cart
        unset($_SESSION['cart'][$key]);
    }
}
    }

    public function delete() {
        $product_id = $this->input->post('product_id');

        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
        }
    }
    
   
    
    
    public function getpreviousorders() 
    {
        $orders = $this->Homemodel->get_prevous_orders_whatsapp($this->input->post('order_no') , $this->input->post('storeId'));
        echo json_encode($orders);
    }

    public function get() {
        echo json_encode($_SESSION['cart']);
    }
     public function view() {
         
         //print_r($store_details_from_token);
        
        if($this->session->userdata('store_token') != ''){
            $store_details_from_token = $this->Homemodel->get_store_details_by_token($this->session->userdata('store_token'));
            $store_id = $store_details_from_token->store_id;
            $data['table'] = $store_details_from_token->table_name;
        }else{
            $store_id = $this->session->userdata('store_id');
        }
        $data['is_whatsapp'] = $this->Homemodel->isWhatsappEnableCheck($store_id);
        $store_details = $this->Homemodel->get_store_details_by_store_id($store_id); //print_r($store_details); //Get store details
        $default_language = $store_details->store_language; //get store default language
        
        //echo $default_language;exit;
        $data['store_informations'] = $store_details;// print_r($data['store_informations']);exit;    
        $data['store_selected_languages'] = $store_details->store_selected_languages; //Selected languages for displaying website
        $data['store_phone'] = $store_details->store_phone; //Selected languages for displaying website
        //print_r($data['store_selected_languages']);exit;
        $this->load->helper('language');  // Load language helper
        
        $data['tax_infr'] = $this->Homemodel->get_store_tax_by_store_id($store_details->gst_or_tax); //print_r($data['tax_infr']);
        
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
        
        $this->load->view('website/header',$data);
        $this->load->view('website/cart');  // Load the view to show cart items
        //$this->load->view('website/footer');
    }
    public function viewcart($token,$orderno) {
        //echo "here";exit;
       

            $store_details_from_token = $this->Homemodel->get_store_details_by_token($token);
            $store_id = $store_details_from_token->store_id;
            $store_details = $this->Homemodel->get_store_details_by_store_id($store_id); //print_r($store_details); //Get store details
            $default_language = $store_details->store_language; //get store default language
            
            $data['token'] = $token;
            $data['orderno'] = $orderno;
    
            $data['prevous_orders'] = $this->Homemodel->get_prevous_orders($orderno , $store_id);//print_r($data['prevous_orders']);
            $data['order_summary'] = $this->Homemodel->get_prevous_order_summary($orderno , $store_id);

            //echo $default_language;exit;
            $data['store_informations'] = $store_details;// print_r($data['store_informations']);exit;    
            $data['store_selected_languages'] = $store_details->store_selected_languages; //Selected languages for displaying website
            $data['store_phone'] = $store_details->store_phone; //Selected languages for displaying website
            //print_r($data['store_selected_languages']);exit;
            $this->load->helper('language');  // Load language helper
            
            $data['tax_infr'] = $this->Homemodel->get_store_tax_by_store_id($store_details->gst_or_tax); //print_r($data['tax_infr']);
            
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
            
            $this->load->view('website/header',$data);
            $this->load->view('website/viewcart',$data);  // Load the view to show cart items
            //$this->load->view('website/footer');
            
    }
    public function checkout() {
        // Here you can handle order placement, save to database, send email, etc.
        $this->cart->destroy();  // Clear the cart after checkout
        $this->load->view('website/header');
        $this->load->view('website/checkout_success');  // Show success page
        $this->load->view('website/footer');
    }
}
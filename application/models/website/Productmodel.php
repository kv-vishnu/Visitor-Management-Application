<?php
class Productmodel extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    // Get product with translation in selected language
    public function get_product($product_id, $language) {
        // Get product details from products table
        $this->db->select('p.id, p.sku, p.price, pt.title, pt.description');
        $this->db->from('products p');
        $this->db->join('product_translations pt', 'p.id = pt.product_id');
        $this->db->where('p.id', $product_id);
        $this->db->where('pt.language', $language);
        
        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_variant_name($variant_id){
        $this->db->where('variant_id', $variant_id);
        $query = $this->db->get('variants');
        $row = $query->row_array();
        return $row['variant_name'];
    }

    public function updateTotalAmount($orderNo){
        $this->db->select('SUM(total_amount) as total_amount, SUM(amount) as total_rate, SUM(tax_amount) as total_tax');
        $this->db->where('orderno', $orderNo);
        $query = $this->db->get('order_items');
        $result = $query->result_array();
        return $result;  // Access the sum of total_amount
    }
    
     public function get_variant_code($variant_id){
        $this->db->where('variant_id', $variant_id);
        $query = $this->db->get('variants');
        $row = $query->row_array();
        return $row['code'];
    }

    public function updateTotalAmountFromItems($orderNo){
        $this->db->select('SUM(total_amount) as total_amount, SUM(amount) as total_rate, SUM(tax_amount) as total_tax');
        $this->db->where('orderno', $orderNo);
        $query = $this->db->get('order_items');
        $result = $query->result_array();
        return $result;  // Access the sum of total_amount
    }

    public function getOrderNo() {
        $this->db->select('token_id');
	    $this->db->from('token_generation');
		$this->db->where('id ', 1);
		$query = $this->db->get();
        $result = $query->result_array();
        return $token_id = $result[0]['token_id'];
    }
    
    public function get_subcategories(){
        $this->db->select('subcategory_id,subcategory_name_ma,subcategory_name_en,subcategory_name_hi,subcategory_name_ar');
        $this->db->from('subcategories');
        $query = $this->db->get();
        return $query->result_array(); 
    }

    public function updateOrderNo($order_no){
        $newOrderNumber = $order_no + 1;
        $this->db->set('token_id', $newOrderNumber);
        $this->db->where('id', 1);
        $this->db->update('token_generation');
    }

    // public function get_products() {
    //     $query = $this->db->get('products');  // Assuming a table named 'products'
    //     return $query->result();
    // }

    public function get_product_by_id($product_id) {
        $language = $this->session->userdata('language') ?: 'en';
        //$query = $this->db->get_where('products', array('id' => $id));
        // $this->db->select('p.id, p.sku, p.price, pt.title, pt.description');
        // $this->db->from('products p');
        // $this->db->join('product_translations pt', 'p.id = pt.product_id');
        // $this->db->where('p.id', $product_id);
        // $this->db->where('pt.language', $language);
        
        // $query = $this->db->get();

        $query = $this->db->get_where('product', array('product_id' => $product_id));

       // echo $this->db->last_query(); exit;
        return $query->row();
    }

    public function get_product_image_by_id($product_id , $store_id) {
        $this->db->select('image');
        $this->db->from('store_wise_product_assign');
        $this->db->where('product_id', $product_id);
        $this->db->where('store_id', $store_id);
        $query = $this->db->get();
        $row = $query->row();
        if ($row->image){
            return $row->image;
        }else{
            $this->db->select('image');
            $this->db->from('product');
            $this->db->where('product_id', $product_id);
            $query1 = $this->db->get();
            $row1 = $query1->row();
            return $row1->image;
        }
    }
    
    public function get_store_product_by_id($product_id){
        $this->db->select('product_id');
	    $this->db->from('store_wise_product_assign');
		$this->db->where('store_product_id ', $product_id);
		$query = $this->db->get();
        $result = $query->result_array();
        $product_id1 = $result[0]['product_id'];
        $this->db->select('*');
        $this->db->from('product');
        $this->db->where('product_id', $product_id1);
        $product_query = $this->db->get();

        return $row =  $product_query->result_array();
    }


    public function get_products_by_type($type) {
        
        $language = $this->session->userdata('language') ?: 'hi';

        // Join the products and product_translations tables to get product details in the selected language
        // $this->db->select('p.id, p.sku, p.price, pt.title, pt.description');
        // $this->db->from('products p');
        // $this->db->join('product_translations pt', 'p.id = pt.product_id');
        // $this->db->where('pt.language', $language);
        
        $this->db->where('product_veg_nonveg', $type);
        $query = $this->db->get('product');
        //echo $this->db->last_query();exit;
        return $query->result_array(); // Return the result as an array
    }

    public function get_products() {
        
        $language = $this->session->userdata('language') ?: 'hi';

        // Join the products and product_translations tables to get product details in the selected language
        // $this->db->select('p.id, p.sku, p.price, pt.title, pt.description');
        // $this->db->from('products p');
        // $this->db->join('product_translations pt', 'p.id = pt.product_id');
        // $this->db->where('pt.language', $language);
        
        // $query = $this->db->get();
        $query = $this->db->get('product');
        //echo $this->db->last_query();exit;
        return $query->result_array(); // Return the result as an array
    }
    public function get_categories_with_products($type, $store_id) {
        $this->db->distinct();
        $this->db->select('s.category_id, c.category_name_en,c.category_name_ma,c.category_name_hi,c.category_name_ar'); // Include necessary columns
        $this->db->from('store_wise_product_assign s');
        $this->db->join('categories c', 's.category_id = c.category_id'); // Adjust the join condition based on your table structure
        $this->db->where('s.store_id', $store_id);
        $this->db->order_by('order_index', 'ASC');
        // If you need to filter by type, uncomment the next line
        if($type != 'all'){
            $this->db->where('s.type', $type);
        }
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        return $query->result_array();
    }
    public function getProductsUnderCategoriesWithType($category_id, $store_id) {
        $this->db->select('s.product_id,s.store_product_id, p.product_name_en,p.product_name_ma,p.product_name_hi,p.product_name_ar,p.product_desc_en,p.product_desc_ma,p.product_desc_hi,p.product_desc_ar,s.rate,p.is_customizable,p.image,p.product_veg_nonveg'); // Include necessary columns
        $this->db->from('store_wise_product_assign s');
        $this->db->join('product p', 'p.product_id = s.product_id'); // Adjust the join condition based on your table structure
        $this->db->where('s.store_id', $store_id);
        $this->db->where('s.category_id', $category_id);
        // If you need to filter by type, uncomment the next line
        // $this->db->where('s.type', $type);

        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        return $query->result_array();
    }
    
    function getVariantQuantity($variant_id, $cart , $product_id) {
        foreach ($cart as $item) {
            if (isset($item['variant_id']) && $item['variant_id'] == $variant_id && $item['prdParentId'] == $product_id) {
                return $item['quantity']; // Return the quantity of the matching variant
            }
        }
        return 0; 
    }

    public function getAddonQuantity($addon_item_id, $cart , $product_id) {
        foreach ($cart as $item) {
            if (isset($item['product_id']) && $item['product_id'] == $addon_item_id && $item['is_addon']==1 && $item['prdParentId'] == $product_id) {
                return $item['quantity']; // Return the quantity of the matching variant
            }
        }
        return 0;
    }
    
    public function get_customize_product_quantity($cart, $product_id)
    {
        $total_quantity = 0;
        $cart = $cart ?? [];
        foreach ($cart as $item) 
        {
            if ($item['prdParentId'] == $product_id) 
            {
                $total_quantity += $item['quantity'];
            }
        }
        return $total_quantity;
    }
    
    public function getAllProductsByStore($store_id){
        $this->db->select('s.product_id,s.store_product_id, p.product_name_en,p.product_name_ma,p.product_name_hi,p.product_name_ar,p.product_desc_en,p.product_desc_ma,p.product_desc_hi,p.product_desc_ar,s.rate,p.is_customizable,p.image,p.product_veg_nonveg'); // Include necessary columns
        $this->db->from('store_wise_product_assign s');
        $this->db->join('product p', 'p.product_id = s.product_id'); // Adjust the join condition based on your table structure
        $this->db->where('s.store_id', $store_id);
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        return $query->result_array();
    }
    
    public function getStockoutProducts(){
        $this->db->select('product_id, SUM(pu_qty) - SUM(sl_qty) AS current_stock');
        $this->db->from('store_stock');
        $this->db->where('store_id', $this->session->userdata('logged_in_store_id'));
        $this->db->group_by('product_id');
        $this->db->having('SUM(pu_qty) - SUM(sl_qty) > 0'); // Raw SQL for HAVING clause
        $query = $this->db->get();
        $result = $query->result_array(); // Fetch the result as an array
    }


    // public function getAllProductsByStoreOrderByType($store_id , $category_id ,$isactive , $type){
    //     $this->db->select(
    //         's.product_id,
    //         s.store_product_id,
    //         s.is_active,
    //         s.remarks,
    //         s.image as store_image,
    //         s.store_product_desc_ma,
    //         s.store_product_desc_en,
    //         s.store_product_desc_hi,
    //         s.store_product_desc_ar,
    //         s.store_product_name_ma,
    //         s.store_product_name_en,
    //         s.store_product_name_hi,
    //         s.store_product_name_ar,
    //         p.product_name_en,
    //         p.product_name_ma,
    //         p.product_name_hi,
    //         p.product_name_ar,
    //         p.product_desc_en,
    //         p.product_desc_ma,
    //         p.product_desc_hi,
    //         p.product_desc_ar,
    //         s.rate,
    //         p.is_customizable,
    //         p.image,
    //         p.product_veg_nonveg'); 
    //     $this->db->from('store_wise_product_assign s');
    //     $this->db->join('product p', 'p.product_id = s.product_id'); // Adjust the join condition based on your table structure
    //     $this->db->where('s.store_id', $store_id);
    //     $this->db->where('s.category_id', $category_id);
    //     $this->db->where('s.is_active', $isactive);
    //     if($type != ''){
    //         $this->db->where('p.product_veg_nonveg', $type);
    //     }
    //     $query = $this->db->get();
    //     return $query->result_array();
    // }

    public function getAllProductsByStoreOrderByType($store_id, $category_id , $type) {
        $this->db->select(
            's.product_id,
             s.store_product_id,
             s.is_active,
             s.availability,
             s.remarks,
             s.image as store_image,
             s.store_product_desc_en,
             s.store_product_name_en,
             s.store_product_desc_ma,
             s.store_product_name_ma,
             s.store_product_desc_hi,
             s.store_product_name_hi,
             s.store_product_desc_ar,
             s.store_product_name_ar,
             p.product_name_en,
             p.product_desc_en,
             p.product_name_ma,
             p.product_desc_ma,
             p.product_name_hi,
             p.product_desc_hi,
             p.product_name_ar,
             p.product_desc_ar,
             s.rate,
             p.is_customizable,
             p.image,
             p.product_veg_nonveg,
             p.category_id'
        ); 
        $this->db->from('store_wise_product_assign s');
        $this->db->join('product p', 'p.product_id = s.product_id'); 
        $this->db->where('s.store_id', $store_id);
        $this->db->where('s.category_id', $category_id);
        if ($type != '') {
            $this->db->where('p.product_veg_nonveg', $type);
        }
    
        $query = $this->db->get();
        $products = $query->result_array();
    
        $result = [];
    
        foreach ($products as $product) {
            //print_r($product);exit;
            if ($product['category_id'] == 23) {
                // Check stock for combo products
                $combo_items = $this->getComboItems($store_id,$product['store_product_id']);
                $combo_available = true;
                
                $availability = $this->getCurrentProductAvailability($product['store_product_id'],$store_id);
    
                if (empty($combo_items) || $availability == 1) {
                    $combo_available = false;
                }
                else
                {
                    foreach ($combo_items as $item) 
                    {
                        $stock = $this->getCurrentStock($item['item_id'], date('Y-m-d'), $store_id);
                        $availability = $this->getCurrentProductAvailability($item['item_id'],$store_id);
                        if ($stock < $item['quantity'] || $availability == 1)
                        {
                            $combo_available = false;
                            break;
                        }
                    }
                }
    
               
    
                // $product['status'] = $combo_available ? '0' : '1';
                $product['status'] = ($combo_available && $product['is_active'] == 0) ? '0' : '1';
            } else {
                // Check stock for individual products
                $stock1 = $this->getCurrentStock($product['store_product_id'], date('Y-m-d'), $store_id); 
                //$product['status'] = $stock > 0 ? '0' : '1';
                $product['status'] = ($stock1 > 0 && $product['is_active'] == 0 && $product['availability'] == 0) ? '0' : '1';
            }
    
            $result[] = $product;
        }
    
        return $result;
    }

    public function getCurrentProductAvailability($store_product_id,$store_id){
        $this->db->select('availability');
        $this->db->from('store_wise_product_assign');
        $this->db->where('store_product_id', $store_product_id);
        $this->db->where('store_id', $store_id);
        $query = $this->db->get();
        return $query->row()->availability;
    }

    public function getAllProductsByStoreOrderByCategory($store_id, $category_id) {
        $this->db->select(
            's.product_id,
             s.store_product_id,
             s.is_active,
             s.availability,
             s.remarks,
             s.image as store_image,
             s.store_product_desc_en,
             s.store_product_name_en,
             s.store_product_desc_ma,
             s.store_product_name_ma,
             s.store_product_desc_hi,
             s.store_product_name_hi,
             s.store_product_desc_ar,
             s.store_product_name_ar,
             p.product_name_en,
             p.product_desc_en,
             p.product_name_ma,
             p.product_desc_ma,
             p.product_name_hi,
             p.product_desc_hi,
             p.product_name_ar,
             p.product_desc_ar,
             s.rate,
             p.is_customizable,
             p.image,
             p.product_veg_nonveg,
             p.category_id'
        ); 
        $this->db->from('store_wise_product_assign s');
        $this->db->join('product p', 'p.product_id = s.product_id'); 
        $this->db->where('s.store_id', $store_id);
        $this->db->where('s.category_id', $category_id);
    
        $query = $this->db->get();
        $products = $query->result_array();
    
        $result = [];
    
        foreach ($products as $product) {
            //print_r($product);exit;
            if ($product['category_id'] == 23) {
                // Check stock for combo products
                $combo_items = $this->getComboItems($store_id,$product['store_product_id']);
                $combo_available = true;
    
                $availability = $this->getCurrentProductAvailability($product['store_product_id'],$store_id);
    
                if (empty($combo_items) || $availability == 1) {
                    $combo_available = false;
                }
                else
                {
                    foreach ($combo_items as $item) 
                    {
                        $stock = $this->getCurrentStock($item['item_id'], date('Y-m-d'), $store_id);
                        $availability = $this->getCurrentProductAvailability($item['item_id'],$store_id);
                        if ($stock < $item['quantity'] || $availability == 1)
                        {
                            $combo_available = false;
                            break;
                        }
                    }
                }
    
                // $product['status'] = $combo_available ? '0' : '1';
                $product['status'] = ($combo_available && $product['is_active'] == 0) ? '0' : '1';
            } else {
                // Check stock for individual products
                $stock1 = $this->getCurrentStock($product['store_product_id'], date('Y-m-d'), $store_id); 
                //$product['status'] = $stock > 0 ? '0' : '1';
                $product['status'] = ($stock1 > 0 && $product['is_active'] == 0 && $product['availability'] == 0) ? '0' : '1';
            }
    
            $result[] = $product;
        }
    
        return $result;
    }

    public function getCurrentStock($product_id,$date,$store_id) {
        $this->db->select('(SUM(pu_qty) - SUM(sl_qty)) as bal_qty');
        $this->db->from('store_stock');
        $this->db->where('product_id', $product_id);
        //$this->db->where('tr_date', $date);
        $this->db->where('store_id', $store_id);
        $query = $this->db->get();
        $result = $query->result_array(); 
        return $result[0]['bal_qty'];
    }
    public function getProductName($product_id) {
        $this->db->select('product_id');
	    $this->db->from('store_wise_product_assign');
		$this->db->where('store_product_id ', $product_id);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
        $result = $query->result_array();
        $product_id1 = $result[0]['product_id'];
        $this->db->select('*');
        $this->db->from('product');
        $this->db->where('product_id', $product_id1);
        $product_query = $this->db->get();
        $row =  $product_query->result_array();
        return $row[0]['product_name_en'];
    }

    public function getComboItems($store_id,$productId) {      
        $this->db->select('*'); // Fetch all columns
        $this->db->from('combo_items'); // Specify the table
        $this->db->where('product_id', $productId); // Filter by product_id
        $this->db->where('store_id', $store_id); // Filter by store_id
        $query = $this->db->get(); // Execute the query
       // echo $this->db->last_query();exit;
        return $query->result_array(); // Return the result as an array
        
    }
    public function productIsCombo($product_id){
        $this->db->select('category_id');
        $this->db->from('store_wise_product_assign');
        $this->db->where('store_product_id', $product_id);
        $query = $this->db->get();
    
        if ($query->num_rows() > 0) {
            $result = $query->row();
            return ($result->category_id == 23); // Returns true if category_id is 23, false otherwise
        }
        return false;
    }

    public function getAllProductsByStoreOrderByFilter($store_id , $category_id ,$isactive , $type){
        //echo $store_id;echo $category_id;echo $isactive;echo $type;exit;
        $this->db->select('s.product_id,s.store_product_id,s.is_active,s.remarks, p.product_name_en,p.product_name_ma,p.product_name_hi,p.product_name_ar,p.product_desc_en,p.product_desc_ma,p.product_desc_hi,p.product_desc_ar,s.rate,p.is_customizable,p.image,p.product_veg_nonveg'); 
        $this->db->from('store_wise_product_assign s');
        $this->db->join('product p', 'p.product_id = s.product_id'); // Adjust the join condition based on your table structure
        $this->db->where('s.store_id', $store_id);
        $this->db->where('s.category_id', $category_id);
        $this->db->where('s.is_active', $isactive);
        
        $query = $this->db->get();
       // echo $this->db->last_query();exit;
        return $query->result_array();
    }

    
    public function getAllProductsByStoreOrderBy($store_id , $category_id){
        $this->db->select('s.product_id,s.store_product_id,s.is_active,s.remarks, p.product_name_en,p.product_name_ma,p.product_name_hi,p.product_name_ar,p.product_desc_en,p.product_desc_ma,p.product_desc_hi,p.product_desc_ar,s.rate,p.is_customizable,p.image,p.product_veg_nonveg'); // Include necessary columns
        $this->db->from('store_wise_product_assign s');
        $this->db->join('product p', 'p.product_id = s.product_id'); // Adjust the join condition based on your table structure
        $this->db->where('s.store_id', $store_id);
        $this->db->where('s.category_id', $category_id);
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        return $query->result_array();
    }

    public function getAllCategoriesOrderByStore($store_id){
        $this->db->select('category_id');
        $this->db->from('categories');
        $this->db->order_by('order_index', 'ASC');
        $query = $this->db->get();
        return $query->result_array(); 
    }
    public function getAllProductsByStoreFilter($store_id , $type , $category_id , $category_order ){
        $this->db->select('s.product_id,s.store_product_id,s.category_id,s.is_active,s.remarks, p.product_name_en,p.product_name_ma,p.product_name_hi,p.product_name_ar,p.product_desc_en,p.product_desc_ma,p.product_desc_hi,p.product_desc_ar,s.rate,p.is_customizable,p.image,p.product_veg_nonveg'); // Include necessary columns
        $this->db->from('store_wise_product_assign s');
        $this->db->join('product p', 'p.product_id = s.product_id'); // Adjust the join condition based on your table structure
        $this->db->where('s.store_id', $store_id);
        $this->db->where('s.category_id' , $category_order);
        $this->db->where('p.product_veg_nonveg' , $type);
        if($category_id != ''){
            $this->db->where('s.category_id' , $category_id);
        }
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        return $query->result_array();
    }
    public function loadProductsCategoryFilter($store_id , $category ,$category_order){
        $this->db->select('s.product_id,s.store_product_id,s.is_active,s.remarks, p.product_name_en,p.product_name_ma,p.product_name_hi,p.product_name_ar,p.product_desc_en,p.product_desc_ma,p.product_desc_hi,p.product_desc_ar,s.rate,p.is_customizable,p.image,p.product_veg_nonveg'); // Include necessary columns
        $this->db->from('store_wise_product_assign s');
        $this->db->join('product p', 'p.product_id = s.product_id'); // Adjust the join condition based on your table structure
        $this->db->where('s.store_id', $store_id);
        $this->db->where('s.category_id', $category_order);
        if($category != 'all'){
        $this->db->where('s.category_id', $category);
        }
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        return $query->result_array();
    }
    
     public function loadProductssubCategoryFilter($store_id , $category, $subcategory ,$category_order){
        $this->db->select('s.product_id,s.store_product_id,s.subcategory_id,s.is_active,s.remarks, p.product_name_en,p.product_name_ma,p.product_name_hi,p.product_name_ar,p.product_desc_en,p.product_desc_ma,p.product_desc_hi,p.product_desc_ar,s.rate,p.is_customizable,p.image,p.product_veg_nonveg'); // Include necessary columns
        $this->db->from('store_wise_product_assign s');
        $this->db->join('product p', 'p.product_id = s.product_id'); // Adjust the join condition based on your table structure
        $this->db->where('s.store_id', $store_id);
        $this->db->where('s.category_id', $category_order);
        $this->db->where('s.subcategory_id', $subcategory);
        if($category != 'all'){
        $this->db->where('s.category_id', $category);
        }
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        return $query->result_array();
    }

    public function getVariants($product_id,$store_id) {
        $this->db->select('v.*, sv.*');
        $this->db->from('store_variants sv');
        $this->db->join('variants v', 'v.variant_id = sv.variant_id');
        $this->db->where('sv.store_id', $store_id);
        $this->db->where('sv.store_product_id', $product_id);
        $this->db->where('sv.is_active', 1);
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        return $query->result_array();

    }

    public function get_store_wise_product_by_id($product_id) {
        $this->db->where('store_product_id', $product_id);
        $query = $this->db->get('store_wise_product_assign');
        return $query->result_array();
    }

    public function getAddons($product_id,$store_id) {
        $this->db->select('p.product_id,p.product_name_en,p.image as prod_image, pa.*,swpa.*');
        $this->db->from('products_addons pa');
        $this->db->join('store_wise_product_assign swpa', 'pa.addon_item_id = swpa.store_product_id');
        $this->db->join('product p', 'swpa.product_id = p.product_id');
        $this->db->where('pa.store_id', $store_id);
        $this->db->where('pa.store_product_id', $product_id);
         $this->db->where('pa.is_active', 1);
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        return $query->result_array();
    }

    public function getActiveAndInactiveAddons($product_id,$store_id) {
        $this->db->select('p.product_id,p.product_name_en,p.image as prod_image, pa.*,swpa.*');
        $this->db->from('products_addons pa');
        $this->db->join('store_wise_product_assign swpa', 'pa.addon_item_id = swpa.store_product_id');
        $this->db->join('product p', 'swpa.product_id = p.product_id');
        $this->db->where('pa.store_id', $store_id);
        $this->db->where('pa.store_product_id', $product_id);
         $this->db->where('pa.is_active', 1);
        $query = $this->db->get();
        $result = $query->result_array();
        $results = [];
        foreach ($result as $row) {
            $stock = $this->getCurrentStock($row['addon_item_id'], date('Y-m-d'), $store_id);
            $row['status'] = ($stock > 0 && $row['is_active'] == 0 && $row['availability'] == 0) ? '0' : '1';
            $results[] = $row;
        }
        return $results;
    }

    public function getRecipies($product_id,$store_id) {
        $this->db->select('sr.*, c.*');
        $this->db->from('store_recipe sr');
        $this->db->join('cookings c', 'sr.recipe_id = c.id');
        $this->db->where('sr.store_id', $store_id);
        $this->db->where('sr.store_product_id', $product_id);
        $this->db->where('sr.is_active', 1);
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        return $query->result_array();
    }
    
}
?>
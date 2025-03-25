<?php
class Enquirymodel extends CI_Model {
	
	public function getEnquiryCount() {
        $this->db->select('id'); // Select only the store_product_id
        $this->db->from('tbl_enquiry'); // Your table name
        return $this->db->count_all_results(); 
    }
	public function get_enquiries(){
		$this->db->select('*');
		$this->db->from('tbl_enquiry');
		$this->db->order_by("id", "desc");
		$query = $this->db->get();
		return $query->result_array();
	}

	public function insert_enquiry($data){
		$this->db->insert('tbl_enquiry', $data);
	}
/*************  ✨ Codeium Command ⭐  *************/
	/**
	 * Gets the product details from store_wise_product_assign and product tables
	 * by the given product ID.
	 *
	 * @param int $id The product ID
	 *
	 * @return array The product details
	 */
/******  745a5263-a7c9-4d48-8367-d18106d05b28  *******/

	public function getEnquiryDetailsById($id){
		$this->db->select('
    tblenq.*,
	tblcomp.*
');
$this->db->from('tbl_enquiry tblenq');
$this->db->join('tbl_company tblcomp', 'tblenq.company_id = tblcomp.n_id', 'left');
$this->db->where('tblenq.id', $id);
$query = $this->db->get();//echo $this->db->last_query();exit;
$row = $query->row_array(); //print_r($row);exit;
return $row;
	}





















	







    // Insert into products table
    public function insert_product_translation($data) {
        $this->db->insert('product', $data);
        return $this->db->insert_id(); // Return inserted product ID
    }

    // Insert into product_translations table
    public function insert_translation($data) {
        $this->db->insert('product_translations', $data);
    }

    public function listcategories() {
        $this->db->select('*');
		$this->db->from('categories');
		//$this->db->where('is_active', 1);
        //$this->db->where('language', 'en');
		$this->db->order_by("category_id", "desc");
		$query = $this->db->get();
		return $query->result_array();
    }

	// public function listproducts() {
    //     $this->db->select('*');
	// 	$this->db->from('product');
	// 	//$this->db->where('is_active', 1);
    //     //$this->db->where('language', 'en');
	// 	$this->db->order_by("product_id", "desc");
	// 	$query = $this->db->get();
	// 	return $query->result_array();
    // }

	public function listproducts() {
		$this->db->select('product.*, categories.category_name_en'); // Select all product fields and category name
		$this->db->from('product');
		$this->db->join('categories', 'product.category_id = categories.category_id', 'left'); // Left join with category
		$this->db->order_by("product.product_id", "desc");
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function getOrderNo() {
        $this->db->select('token_id');
	    $this->db->from('token_generation');
		$this->db->where('id ', 1);
		$query = $this->db->get();
        $result = $query->result_array();
        return $token_id = $result[0]['token_id'];
    }
	
	public function sublistcategories(){
		$this->db->select('*');
		$this->db->from('subcategories');
		//$this->db->where('is_active', 1);
        //$this->db->where('language', 'en');
		$this->db->order_by("subcategory_id", "desc");
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function insert_subcategories_translation($data) {
        $this->db->insert('subcategories', $data);
        return $this->db->insert_id();
    }
	
	
	public function get_sub_categories_by_id($id){
	    $this->db->select('*');
		$this->db->from('subcategories');
		$this->db->where('subcategory_id',$id );
		$query = $this->db->get();
		return $query->result_array();
	}
	

	public function listproducts_category_wise($category_id) {
		$this->db->select('product.*, categories.category_name_en'); // Select all product fields and category name
		$this->db->from('product');
		$this->db->join('categories', 'product.category_id = categories.category_id', 'left');
		$this->db->where('product.category_id', $category_id); // Left join with category
		$this->db->order_by("product.product_id", "desc");
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_store_vat_id($store_id) {
		$this->db->select('gst_or_tax');
		$this->db->from('store');
		$this->db->where('store_id', $store_id);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function get_store_tax_rate($vat_id) {
		$this->db->select('tax_rate');
		$this->db->from('tax');
		$this->db->where('tax_id', $vat_id);
		$query = $this->db->get();
		$result = $query->row();
		return $result ? $result->tax_rate : 0; // Return 0 if no result found
	}




	public function delete_update_assigned_products($store_id,$category_id,$selected_items){
		foreach ($selected_items as $product_id) {
			// echo $store_id;echo $product_id;
			$this->db->where('store_id', $store_id);
			$this->db->where('product_id', $product_id);
			$query = $this->db->get('store_wise_product_assign');
			if($query->num_rows() == 0){
					$product_details = $this->get_product_by_id($product_id);//print_r($product_details);exit;
					$vat_id = $this->get_store_vat_id($store_id);//exit;
					$tax_rate = $this->get_store_tax_rate($vat_id[0]['gst_or_tax']);
					$data = array(
					'store_id' => $store_id,
					'product_id' => $product_id,
					'subcategory_id' => $product_details[0]['subcategory_id'],
					'vat_id' => $vat_id[0]['gst_or_tax'],
					'type' => $product_details[0]['product_veg_nonveg'],
					'rate' => '',
					'tax' => $tax_rate,
					'tax_amount' => '',
					'total_amount' => '',
					'category_id' => $product_details[0]['category_id'],
					'is_addon' => $product_details[0]['is_addon'],
					'is_customizable' => $product_details[0]['is_customizable'],
					'image' => '',
					'is_admin'	=> 1, //when added by admin thiw will 1
					'date_created' => date('Y-m-d H:i:s'), // current date and time
					'created_by' => 'admin',
					'date_modified' => date('Y-m-d H:i:s'),
					'modified_by' => 'admin',
					'is_active' => 1
				);
			$this->db->insert('store_wise_product_assign', $data);
			}
		}
	}

	public function update_selected_products($store_product_id, $updateData){
		$this->db->where('store_product_id', $store_product_id);
        return $this->db->update('store_wise_product_assign', $updateData);
	}

	public function update_selected_variants($varient_id,$store_id,$store_product_id,$updateData){	
		$this->db->where('variant_id', $varient_id);
		$this->db->where('store_id', $store_id);
		$this->db->where('store_product_id', $store_product_id);
        return $this->db->update('store_variants', $updateData);
	}

	public function update_selected_recipe($recipe_id,$store_id,$store_product_id,$updateData){	
		$this->db->where('recipe_id', $recipe_id);
		$this->db->where('store_id', $store_id);
		$this->db->where('store_product_id', $store_product_id);
        return $this->db->update('store_recipe', $updateData);
	}

	public function insert_variant($data) {
		$this->db->insert('store_variants', $data);
	}
	public function insert_recipe($data) {
		$this->db->insert('store_recipe', $data);
	}
	public function insert_addons($data) {
		$this->db->insert('products_addons', $data);
	}

	public function insert_product_assign($data) {
		$this->db->insert('store_wise_product_assign', $data);
	}


    public function update_categories($id, $data) {
        $this->db->where('category_id', $id);
        $this->db->update('categories', $data);
        return true;
    }


public function update_subcategories($id, $data) {
        $this->db->where('subcategory_id', $id);
        $this->db->update('subcategories', $data);
        return true;
    }
	public function update_product($id, $data) {
        $this->db->where('product_id', $id);
        $this->db->update('product', $data);
        return true;
    }

    public function delete_category($id){
		$this->db->where('category_id', $id);
        return $this->db->delete('categories');
	}

    public function get_categories_by_id($id){
	    $this->db->select('*');
		$this->db->from('categories');
		$this->db->where('category_id',$id );
		$query = $this->db->get();
		return $query->result_array();
	}

    public function insert_categories_translation($data) {
        $this->db->insert('categories', $data);
        return $this->db->insert_id();
    }

    public function check_categorycode_exists($code)
	{
    	$this->db->where('category_code', $code);
    	$query = $this->db->get('categories'); // Assuming 'users' is your table name
		if ($query->num_rows() > 0) {
        	return TRUE;  // Username exists
    	} else {
        	return FALSE;  // Username does not exist
    	}
	}

	public function check_categoryname_exists($catname)
	{
		$catname = $this->db->escape($catname); // Escaping the variable to prevent SQL injection
		$store_id = $this->session->userdata('logged_in_store_id');
		$query = "SELECT `category_name_en` FROM `categories` WHERE `category_name_en` = $catname AND (`store_id` = $store_id OR `store_id` = 0)";
		$result = $this->db->query($query);
		// Fetch and display results
		if ($result->num_rows() > 0) {
        	return TRUE;  // Username exists
    	} else {
        	return FALSE;  // Username does not exist
    	}
	}
    public function check_categoryorder_exists($order)
	{
    	$this->db->where('order_index', $order);
    	$query = $this->db->get('categories'); // Assuming 'users' is your table name
		if ($query->num_rows() > 0) {
        	return TRUE;  // Username exists
    	} else {
        	return FALSE;  // Username does not exist
    	}
	}

public function updateAddonStatus($addon_id, $is_active)
	{
		$data = ['is_active' => $is_active];
		$this->db->where('addon_id', $addon_id);
		return $this->db->update('products_addons', $data); // Replace 'addons' with your actual table name
	}
	
	public function get_product_by_id($id){
	    $this->db->select('*');
		$this->db->from('product');
		$this->db->where('product_id',$id );
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function getStoreWiseProductproductById($product_id) {
		$this->db->select('*');
		$this->db->from('store_wise_product_assign');
		$this->db->where('store_product_id', $product_id);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_owner_product_by_id($id){
		$query = $this->db->query("SELECT p.*, swpa.rate, swpa.tax_amount, swpa.total_amount, p.image as product_image
    FROM product as p
    LEFT JOIN store_wise_product_assign as swpa ON p.product_id = swpa.product_id
    WHERE p.product_id = $id");

return $query->result_array();


	}
	public function updateStoreProductPrice($id,$dataUpdate,$store_id){
	$this->db->where('product_id', $id);
	$this->db->where('store_id', $store_id);
	$this->db->update('store_wise_product_assign', $dataUpdate );
}



	//INCLUDING SHOP OWNER DATAS
	public function liststorecategories($store_id, $logged_in_store_id) {
		$ids = [$store_id, $logged_in_store_id];
		$query = $this->db->where_in('store_id', $ids)->get('categories');
		return $query->result_array();
	}

	public function already_assigned_products_ids($store_id){ 
		$this->db->select('product_id');
		$this->db->from('store_wise_product_assign');
		$this->db->where('store_id',$store_id ); 
		$query = $this->db->get();
		$products = $query->result_array();
		return $product_ids = array_column($products, 'product_id');
	}

	public function already_assigned_variant_ids($store_id,$product_id){ 
		$this->db->select('variant_id');
		$this->db->from('store_variants');
		$this->db->where('store_product_id',$product_id );
		$this->db->where('store_id',$store_id ); 
		$query = $this->db->get();
		$products = $query->result_array();
		return $variant_ids = array_column($products, 'variant_id');
	}

	public function already_assigned_recipe_ids($store_id,$product_id){
		$this->db->select('recipe_id');
		$this->db->from('store_recipe');
		$this->db->where('store_product_id',$product_id );
		$this->db->where('store_id',$store_id ); 
		$query = $this->db->get();
		$products = $query->result_array();
		return $recipe_ids = array_column($products, 'recipe_id');
	}


	public function shopAssignedProducts() {
		$store_id = $this->session->userdata('logged_in_store_id');
		$this->db->select('
			swpa.*,
			p.product_id,
			p.product_name_en,
			p.is_addon AS product_is_addon,
			p.is_customizable AS product_is_customizable,
			p.product_veg_nonveg,
			p.image,
			c.category_name_en,
			c.category_id,
			ss.pu_qty,
			ss.minqty,
			(ss.pu_qty - ss.sl_qty) AS balance_stock
		');
		$this->db->from('store_wise_product_assign AS swpa');
		$this->db->join('product AS p', 'p.product_id = swpa.product_id', 'left');
		$this->db->join('categories AS c', 'c.category_id = swpa.category_id', 'left');
		$this->db->join('store_stock AS ss', 'ss.product_id = p.product_id', 'left');
		$this->db->where('swpa.store_id', $store_id);
		$this->db->group_by('swpa.store_product_id');
		$query = $this->db->get();
		return $query->result_array(); // Return results as an array of objects
	}

	public function shopAssignedProductsbyPagination() {
        $store_id = $this->session->userdata('logged_in_store_id');
		$type = '';
		$products_by_category_active = [];
        $category_ids_order = $this->getAllCategoriesOrderByStore($store_id);
        foreach ($category_ids_order as $cat_order) {
               $category_id = $cat_order['category_id']; 
               $allproducts = $this->getAllProductsByStoreOrderByType($store_id, $category_id,$type);
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
        $results = array_merge($inactiveProducts, $activeProducts);
        return $results;
    }

	public function getAllCategoriesOrderByStore($store_id){
        $this->db->select('category_id');
        $this->db->from('categories');
        $this->db->order_by('order_index', 'ASC');
        $query = $this->db->get();
        return $query->result_array(); 
    }

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

	// public function shopAssignedProductsbyPagination($limit, $offset) {
    //     $store_id = $this->session->userdata('logged_in_store_id');
    //     $this->db->select('
    //         swpa.*,
    //         p.product_id,
    //         p.product_name_en,
    //         p.is_addon AS product_is_addon,
    //         p.is_customizable AS product_is_customizable,
    //         p.product_veg_nonveg,
    //         p.image as product_image,
    //         c.category_name_en,
    //         c.category_id,
    //         ss.pu_qty,
    //         ss.minqty,
    //         (ss.pu_qty - ss.sl_qty) AS balance_stock
    //     ');
    //     $this->db->from('store_wise_product_assign AS swpa');
    //     $this->db->join('product AS p', 'p.product_id = swpa.product_id', 'left');
    //     $this->db->join('categories AS c', 'c.category_id = swpa.category_id', 'left');
    //     $this->db->join('store_stock AS ss', 'ss.product_id = p.product_id', 'left');
    //     $this->db->where('swpa.store_id', $store_id);
    //     $this->db->group_by('swpa.store_product_id');
    //     $this->db->limit($limit, $offset);
    //     $query = $this->db->get();
    //     $result = $query->result_array();
    //     return $result;
    // }

	public function shopAssignedActiveProducts() {
		$store_id = $this->session->userdata('logged_in_store_id');
$this->db->select('
    swpa.*,
    p.product_id,
    p.product_name_en,
    p.is_addon AS product_is_addon,
    p.is_customizable AS product_is_customizable,
    p.product_veg_nonveg,
    p.image,
    c.category_name_en,
    c.category_id,
    ss.pu_qty,
    ss.minqty,
    (ss.pu_qty - ss.sl_qty) AS balance_stock
');
$this->db->from('store_wise_product_assign AS swpa');
$this->db->join('product AS p', 'p.product_id = swpa.product_id', 'left');
$this->db->join('categories AS c', 'c.category_id = swpa.category_id', 'left');
$this->db->join('store_stock AS ss', 'ss.product_id = p.product_id', 'left');
$this->db->where('swpa.store_id', $store_id);
//$this->db->where('swpa.is_active', 0);
$this->db->group_by('swpa.store_product_id');
$query = $this->db->get();
//echo $this->db->last_query();exit;
$products = $query->result_array();
$available_products = [];
		foreach ($products as $product) 
		{
			if ($product['category_id'] == 23) 
			{
				$combo_items = $this->getComboItems($store_id,$product['store_product_id']);
				$combo_available = true;
				
				if (empty($combo_items)) {
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
				
				if ($combo_available) {
					$available_products[] = $product;
				}
			}
			else 
			{
				$stock = $this->getCurrentStock($product['store_product_id'], date('Y-m-d'), $store_id);
				if ($stock > 0 && $product['availability'] == 0) 
				{
					$available_products[] = $product;
				}
		}
	}
	return $available_products;
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
public function getCurrentProductAvailability($store_product_id,$store_id){
	$this->db->select('availability');
	$this->db->from('store_wise_product_assign');
	$this->db->where('store_product_id', $store_product_id);
	$this->db->where('store_id', $store_id);
	$query = $this->db->get();
	return $query->row()->availability;
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
	public function shopAssignedComboProducts() {
		$store_id = $this->session->userdata('logged_in_store_id');
		$this->db->select('
			swpa.*,
			p.product_id,
			p.product_name_en,
			p.is_addon AS product_is_addon,
			p.is_customizable AS product_is_customizable,
			p.product_veg_nonveg,
			p.image as product_image,
			c.category_name_en,
			c.category_id,
			ss.pu_qty,
			ss.minqty,
			(ss.pu_qty - ss.sl_qty) AS balance_stock
		');
		$this->db->from('store_wise_product_assign AS swpa');
		$this->db->join('product AS p', 'p.product_id = swpa.product_id', 'left');
		$this->db->join('categories AS c', 'c.category_id = swpa.category_id', 'left');
		$this->db->join('store_stock AS ss', 'ss.product_id = p.product_id', 'left');
		$this->db->where('swpa.store_id', $store_id);
		$this->db->where('swpa.category_id', 23 );
		$this->db->group_by('swpa.store_product_id');
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		return $query->result_array(); // Return results as an array of objects
	}
	
// search products
	public function shopAssignedProductsbysearch($search = null) {
		$store_id = $this->session->userdata('logged_in_store_id');
		$this->db->select('
    store_wise_product_assign.*,
    product.product_id,
    product.product_name_en,
    product.is_addon as product_is_addon,
    product.is_customizable as product_is_customizable,
    product.product_veg_nonveg,
    product.image,
    categories.category_name_en,
    categories.category_id,
    store_stock.pu_qty,
    store_stock.minqty,
    (store_stock.pu_qty - store_stock.sl_qty) as balance_stock
');
		$this->db->from('store_wise_product_assign');
		$this->db->join('product', 'product.product_id = store_wise_product_assign.product_id', 'left'); // Left join with product.id');
		$this->db->join('categories', 'categories.category_id = store_wise_product_assign.category_id', 'left'); // Left join with product.id');
		$this->db->join('store_stock', 'store_stock.product_id = store_wise_product_assign.store_product_id', 'left');
		$this->db->where('store_wise_product_assign.store_id', $store_id);
		$this->db->group_by('store_wise_product_assign.store_product_id');
		if (!empty($search)) {
			$this->db->like('product_name_en', $search); // Filter by product name
		}
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		return $query->result();
	}

	public function store_taxes($store_id){
		$this->db->select('store_country');
		$this->db->from('store');
		$this->db->where('store_id', $store_id);
		$query = $this->db->get();
		$row = $query->result_array();
		$country_id = $row[0]['store_country']; //Get current login store country id
		$this->db->select('tax_rate');  //Find gst and taxes values based on country id
		$this->db->from('tax');
		$this->db->where('country_id', $country_id);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function default_tax($store_id){
		$this->db->select('gst_or_tax');
		$this->db->from('store');
		$this->db->where('store_id', $store_id);
		$query = $this->db->get();
		$row = $query->result_array();
		$selected_gst_vat_id = $row[0]['gst_or_tax']; //Selected gst or tax value
		$this->db->select('tax_rate');  //Find gst and taxes values based on country id
		$this->db->from('tax');
		$this->db->where('tax_id', $selected_gst_vat_id);
		$query = $this->db->get();
		$row1 = $query->result_array();
		return $row1[0]['tax_rate'];
	}


	public function shopAssignedProductsByKeyUpSearch($search = null) {
        $store_id = $this->session->userdata('logged_in_store_id');
        $this->db->select('
    store_wise_product_assign.*,
    product.product_id,
    product.product_name_en,
    product.is_addon as product_is_addon,
    product.is_customizable as product_is_customizable,
    product.product_veg_nonveg,
    product.image as store_image,
    categories.category_name_en,
    categories.category_id,
    store_stock.pu_qty,
    store_stock.minqty,
    (store_stock.pu_qty - store_stock.sl_qty) as balance_stock
');
        $this->db->from('store_wise_product_assign');
        $this->db->join('product', 'product.product_id = store_wise_product_assign.product_id', 'left'); // Left join with product.id');
        $this->db->join('categories', 'categories.category_id = store_wise_product_assign.category_id', 'left'); // Left join with product.id');
        $this->db->join('store_stock', 'store_stock.product_id = store_wise_product_assign.store_product_id', 'left');
        $this->db->where('store_wise_product_assign.store_id', $store_id);

        if (!empty($search)) {
            $this->db->like('product_name_en', $search); // Filter by product name
			$this->db->or_like('store_product_name_en', $search);
        }
		$this->db->group_by('store_wise_product_assign.store_product_id');
        $query = $this->db->get();
        return $query->result();
    }

	public function ChangeProductStatus($store_product_id,$store_id,$is_active){ 
        $this->db->set('is_active', $is_active);
        $this->db->where('store_product_id', $store_product_id);
        $this->db->where('store_id', $store_id);
        return $this->db->update('store_wise_product_assign');
    }
	public function ChangeProductAvailability($store_product_id,$store_id,$is_active){ 
        $this->db->set('availability', $is_active);
        $this->db->where('store_product_id', $store_product_id);
        $this->db->where('store_id', $store_id);
        return $this->db->update('store_wise_product_assign');
    }

	public function get_variant_by_product_id($varient_id,$store_id,$store_product_id) {
		$this->db->where('variant_id', $varient_id);
		$this->db->where('store_id', $store_id);
		$this->db->where('store_product_id', $store_product_id);
		$query = $this->db->get('store_variants');
		return $query->num_rows();
	}
	
/*************  ✨ Codeium Command ⭐  *************/
/**
 * Retrieves the number of addon records for a specific product in a store.
 *
 * @param int $addon_id The ID of the addon item.
 * @param int $store_id The ID of the store.
 * @param int $store_product_id The ID of the store product.
 * @return int The number of addon records found for the specified criteria.
 */

/******  8de712ca-80df-4ed6-b42f-7f8462ade35a  *******/
	public function get_addon_by_product_id($addon_id,$store_id,$store_product_id) {
		$this->db->where('addon_item_id', $addon_id);
		$this->db->where('store_id', $store_id);
		$this->db->where('store_product_id', $store_product_id);
		$query = $this->db->get('products_addons');
		return $query->num_rows();
	}

	public function get_recipe_by_product_id($recipe_id,$store_id,$store_product_id) {
		$this->db->where('recipe_id', $recipe_id);
		$this->db->where('store_id', $store_id);
		$this->db->where('store_product_id', $store_product_id);
		$query = $this->db->get('store_recipe');
		return $query->num_rows();
	}

	public function list_all_addons() {
		$store_id = $this->session->userdata('logged_in_store_id');
		$this->db->select('swpa.*,p.*'); // Select all swpa fields and product name
		$this->db->from('store_wise_product_assign swpa');
		$this->db->join('product p', 'swpa.product_id = p.product_id'); // Correct join with product table
		$this->db->where('swpa.store_id', $store_id);
		$this->db->where('swpa.is_addon', 1);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		return $query->result_array();
	}
	public function already_assigned_addons($store_id , $store_product_id) {
		$this->db->select('products_addons.*, product.product_name_en'); // Select all product fields and category name
		$this->db->from('products_addons');
		$this->db->join('product', 'products_addons.addon_item_id = product.product_id', 'left'); // Left join with category
		$this->db->where('products_addons.store_id', $store_id);
		$this->db->where('products_addons.store_product_id', $store_product_id);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		return $query->result_array();
	}
	
	public function update_product_description($data , $store_id , $product_id){
		$this->db->where('store_id', $store_id);
		$this->db->where('store_product_id', $product_id);
		$this->db->update('store_wise_product_assign', $data);
	}

	// adds stocks 
	public function addStock($quantity, $store_id, $product_id, $date) 
	{
		$minqty = 0;
		$this->db->select('*');
		$this->db->from('store_stock');
		$this->db->where('store_id', $store_id);
		$this->db->where('product_id', $product_id);
		$this->db->where('tr_date', $date);
		$this->db->where('ttype', 'SK');
		$this->db->where('order_id', 0);
		$query = $this->db->get();
		$result = $query->row();

		if(empty($result))
		{
		$this->db->set('store_id', $store_id);
			$this->db->set('product_id', $product_id);
			$this->db->set('ttype', 'SK');
			$this->db->set('order_id', 0);
			$this->db->set('pu_qty', $quantity);
			$this->db->set('minqty', $minqty);
			$this->db->set('tr_date', $date);
			$this->db->set('created_by', $this->session->userdata('loginid'));
			$this->db->set('created_date', date('Y-m-d H:i:s'));
			$this->db->set('modified_by', $this->session->userdata('loginid'));
			$this->db->set('modified_date', date('Y-m-d H:i:s'));
			$this->db->insert('store_stock');
		}
		else
		{
			$current_quantity = (int)$result->pu_qty;
			$quantity = $current_quantity + (int)$quantity;
		$this->db->set('pu_qty', $quantity);
		$this->db->set('minqty', '2');
		$this->db->set('created_by', $this->session->userdata('loginid'));
		$this->db->set('created_date', date('Y-m-d H:i:s'));
		$this->db->set('modified_by', $this->session->userdata('loginid'));
		$this->db->set('modified_date', date('Y-m-d H:i:s'));
		$this->db->where('store_id', $store_id);
		$this->db->where('product_id', $product_id);
		$this->db->where('tr_date', $date);
		$this->db->where('ttype', 'SK');
		$this->db->where('order_id', 0);
		$this->db->where('id', $result->id);
		// $this->db->update('store_stock');    
		if (!$this->db->update('store_stock')) {
			log_message('error', 'Update failed: ' . $this->db->last_query());
		}
	} 
	$this->db->set('is_active', 0);
	$this->db->where('store_id', $store_id);
	$this->db->where('store_product_id', $product_id);
	$this->db->update('store_wise_product_assign');
    }

  // remove stocks

public function removeStock($quantity, $store_id, $product_id, $date){

	$minqty = 0;
	$this->db->select('*');
	$this->db->from('store_stock');
	$this->db->where('store_id', $store_id);
	$this->db->where('product_id', $product_id);
	$this->db->where('tr_date', $date);
	$this->db->where('ttype', 'SK');
	$this->db->where('order_id', 0);
	$query = $this->db->get();
	$result = $query->row();

	if(empty($result))
	{
		// echo "h";echo $quantity; exit;
		$this->db->set('store_id', $store_id);
		$this->db->set('product_id', $product_id);
		$this->db->set('ttype', 'SL');
		$this->db->set('order_id', 0);
		$this->db->set('sl_qty', $quantity);
		$this->db->set('minqty', $minqty);
		$this->db->set('tr_date', $date);
		$this->db->set('created_by', $this->session->userdata('loginid'));
		$this->db->set('created_date', date('Y-m-d H:i:s'));
		$this->db->set('modified_by', $this->session->userdata('loginid'));
		$this->db->set('modified_date', date('Y-m-d H:i:s'));
		$this->db->insert('store_stock');
	}
	else
	{
		$current_quantity = (int)$result->sl_qty;
		log_message('debug', "Current Quantity: $current_quantity, Input Quantity: $quantity");
		
		$quantity = $current_quantity + (int)$quantity;
		
		log_message('debug', "New Quantity: $quantity");
		// echo "e";echo $quantity;exit;
	$this->db->set('sl_qty', $quantity);
	$this->db->set('minqty', '2');
	$this->db->set('created_by', $this->session->userdata('loginid'));
	$this->db->set('created_date', date('Y-m-d H:i:s'));
	$this->db->set('modified_by', $this->session->userdata('loginid'));
	$this->db->set('modified_date', date('Y-m-d H:i:s'));
	$this->db->where('store_id', $store_id);
	$this->db->where('product_id', $product_id);
	$this->db->where('tr_date', $date);
	$this->db->where('ttype', 'SK');
	$this->db->where('order_id', 0);
	$this->db->where('id', $result->id);

	// $this->db->update('store_stock');    

	if (!$this->db->update('store_stock')) {
		log_message('error', 'Update failed: ' . $this->db->last_query());
	}

	$this->load->model('Ordermodel');


	// Call getCurrentStock to fetch the current stock
	$currentStock = $this->Ordermodel->getCurrentStock($product_id, $date, $store_id);
	// print_r($currentStock); exit;

	if($currentStock == 0){
		$this->db->set('is_active', 1);
		$this->db->where('store_id', $store_id);
	$this->db->where('store_product_id', $product_id);
	$this->db->update('store_wise_product_assign');	
	} 

} 


}
	  
	public function get_product_name($addon_item_id){
		$this->db->select('product_id');
	    $this->db->from('store_wise_product_assign');
		$this->db->where('store_product_id ', $addon_item_id);
		$query = $this->db->get();
        $result = $query->result_array();
        $product_id1 = $result[0]['product_id'];
        $this->db->select('*');
        $this->db->from('product');
        $this->db->where('product_id', $product_id1);
        $product_query = $this->db->get();
        $row =  $product_query->result_array();
        return $row[0]['product_name_en'];
	}
	public function getProductImages($product_id) {
		$this->db->select('product_id,image');
		$this->db->from('store_wise_product_assign');
		$this->db->where('store_product_id', $product_id);
		$query = $this->db->get();
		$row = $query->result_array();
		$product_id1 = $row[0]['product_id'];
		$product_image = $row[0]['image'];

		$this->db->select('image,image1,image2,image3,image4');
		$this->db->from('product');
		$this->db->where('product_id', $product_id1);
		$query = $this->db->get();
		$row = $query->row_array();

		$productImages[] = [
			'default_image' => $product_image ? $product_image : $row['image'],
			'image1' => $row['image1'],
			'image2' => $row['image2'],
			'image3' => $row['image3'],
			'image4' => $row['image4']
		]; 

		return $productImages;

		
	}

	public function set_default_image($store_product_id , $image) {
		$this->db->where('store_product_id', $store_product_id);
		$this->db->update('store_wise_product_assign', array('image' => $image));
	}
	public function update_order_index($category_id , $order_index) {
		$this->db->where('category_id', $category_id);
		$this->db->update('categories', array('order_index' => $order_index));
	}
	
}
?>
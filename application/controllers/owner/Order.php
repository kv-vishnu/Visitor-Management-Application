<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends CI_Controller {

	/**
	 * Index Page for this controller.
	 * Get pending orders by table id - getPendingOrdersByTableID()
	 * Get orders by type (Pickup or delivery) - getOrdersByType()
	 * Get completed orders by type - getCompletedOrdersByType()
	 * Get product rates after selecting product when add order in dining - getProductRatesWithIsCustomizeNewDiningOrder()
	 * Get product rates after selecting product when add order in pickup - getProductRatesWithIsCustomizeNewPickupOrder()
	 * Get product rates after selecting product when add order in delivery - getProductRatesWithIsCustomizeNewDeliveryOrder()
	 * Get product rates after selecting product when add order in customize product - getProductRatesWithIsCustomizeExistingOrder
	 * Return Order item - returnOrderItem()
	 * Get Order By date - getOrderByDate()
	 * Get pending Orders Count - get_Pending_Orders_Count()
	 * Display reports page - reports()
	 * Get sales report by store id - getSalesReportByStoreId()
	 * Get user report by store id - getUserReportByStoreId()
	 * Get delivery report By store id -getDeliveryReportByStoreId()
	 * Add new order from admin side first display order popup - newOrder()
	 * Add new Dining order - newDiningOrder()
	 * Add new Delivery order - newDeliveryOrder()
	 * Add new pickup order - newPickupOrder()
	 * Set table reserved - setTableReserved()
	 * Save confirm order - SaveConfirmOrder()
	 * Delete order item - deleteOrderItem()
	 * Delete order item and update remark when delete order item from listing - deleteOrderItemWithUpdateRemark()
	 * Delete order - deleteOrder()
	 * Change order status - changeOrderStatus()
	 * Change delivery boy - changeDeliveryBoy()
	 * Save new dining order - SaveNewDiningOrder()   
	 * Save new pickup order - SaveNewPickupOrder()
	 * Save new delivery order - SaveNewDeliveryOrder()
	 * Display table orders - tableOrders()
	 * Get pickup order details - pickupOrderDetails()
	 * Get completed orders - completedOrdersPKDL()
	 * Update order - update_order()
	 * Update order item - update_order_item()
	 * Pay order - pay_order()
	 * Save order with existing add order from add option - SaveOrderWIthExisting()
	 * Check product is combo - productIsCombo()
	 * Get orders by date - getOrderByDate()
	 * Get pickup order details - getPickupOrderDetails()
	 * Delete order item when decrement value == 0 - deleteOrderItemStockRemove()
	 * Get supplier sales report - getSupplierSalesReportByStoreId()
	 * Get supplier user report - getSupplierUserReportByStoreId()
	 * Change to dining status - dining_order()
	 */
	
	public function __construct()
	{
		parent::__construct();
		require('Common.php');
		$this->load->model('admin/Productmodel');
		$this->load->model('admin/Storemodel');
		$this->load->model('owner/Ordermodel');
		$this->load->model('owner/Settingsmodel');
		$this->load->model('owner/Stockmodel');
		$this->load->model('website/Homemodel');
		if (!$this->session->userdata('login_status')) {
			redirect(login);
		}
	}
	public function index()
	{
		$controller = $this->router->fetch_class(); // Gets the current controller name
		$method = $this->router->fetch_method();   // Gets the current method name
		$data['controller'] = $controller;

        $logged_in_store_id = $this->session->userdata('logged_in_store_id');//echo $logged_in_store_id;exit;
		$role_id = $this->session->userdata('roleid'); // Role id of logged in user
		$user_id = $this->session->userdata('loginid'); // Loged in user id
		$loginName = $this->session->userdata('loginName');
		$data['name'] = $loginName;
        
        $store_details = $this->Homemodel->get_store_details_by_store_id($logged_in_store_id);
        $support_details = $this->Homemodel->get_support_details_by_country_id($store_details->store_country);
        $data['store_disp_name'] = $store_details->store_disp_name;
        $data['store_address'] = $store_details->store_address;
        $data['support_no'] = $support_details->support_no;
        $data['support_email'] = $support_details->support_email;
		$data['store_logo'] = $store_details->store_logo_image;
        
        $this->load->model('admin/Tablemodel');
		

		if($role_id == 1 || $role_id == 2) { //Admin and Shop owner
			$data['tables']=$this->Tablemodel->getTablesByStoreId($logged_in_store_id); 
			$data['ready_orders']=$this->Ordermodel->getReadyOrders($logged_in_store_id,$role_id,$user_id); //ready orders display
			//Count display pending dining,delivery,pickup for admin and shop owner
			$data['pending_delivery_cooking']=$this->Tablemodel->pending_delivery_cooking($logged_in_store_id);
			$data['pending_delivery_ready']=$this->Tablemodel->pending_delivery_ready($logged_in_store_id);
			$data['pending_pickup_cooking']=$this->Tablemodel->pending_pickup_cooking($logged_in_store_id); 
			$data['pending_pickup_ready']=$this->Tablemodel->pending_pickup_ready($logged_in_store_id);
			$data['comp_pickup_count']=$this->Tablemodel->comp_pickup_count($logged_in_store_id); 
			$data['comp_delivery_count']=$this->Tablemodel->comp_delivery_count($logged_in_store_id); 
			$data['pending_pickup_count']=$this->Tablemodel->pending_pickup_count($logged_in_store_id); 
			$data['pending_delivery_count']=$this->Tablemodel->pending_delivery_count($logged_in_store_id);
			$this->load->view('owner/includes/header',$data);
			$this->load->view('owner/includes/owner-dashboard',$data);
			$this->load->view('owner/orderdashboard',$data);
			$this->load->view('owner/includes/footer');
		}
		if($role_id == 5) { // Supplier boy
			$data['tables']=$this->Tablemodel->getTablesAssignedByStoreId($logged_in_store_id,$user_id); //get orders assigned to loged in supplier user  
			$data['ready_orders']=$this->Ordermodel->getReadyOrders($logged_in_store_id,$role_id,$user_id); //ready orders display except
			$data['enable_delivery'] = $this->Settingsmodel->getEnableDelivery($logged_in_store_id,$user_id); // Fetch enable delivery
    		$data['enable_pickup'] = $this->Settingsmodel->getEnablePickup($logged_in_store_id,$user_id);     // Fetch enable pickup
			//Count display pending dining,delivery,pickup for supplier
			$data['pending_delivery_cooking']=$this->Tablemodel->pending_delivery_cooking($logged_in_store_id); // pending delivery order count
			$data['pending_delivery_ready']=$this->Tablemodel->pending_delivery_ready($logged_in_store_id);
			$data['pending_pickup_cooking']=$this->Tablemodel->pending_pickup_cooking($logged_in_store_id); 
			$data['pending_pickup_ready']=$this->Tablemodel->pending_pickup_ready($logged_in_store_id);
			$data['comp_pickup_count']=$this->Tablemodel->comp_pickup_count($logged_in_store_id); 
			$data['comp_delivery_count']=$this->Tablemodel->comp_delivery_count($logged_in_store_id); 
			$data['pending_pickup_count']=$this->Tablemodel->pending_pickup_count($logged_in_store_id); 
			$data['pending_delivery_count']=$this->Tablemodel->pending_delivery_count($logged_in_store_id);
			$this->load->view('owner/includes/header',$data);
			$this->load->view('owner/includes/supplier-dashboard',$data);
			$this->load->view('owner/supplier_orderdashboard',$data);
			$this->load->view('owner/includes/footer');
		}
		if($role_id == 6) { // Kitchen
			$data['approved_orders']=$this->Ordermodel->getApprovedOrders($logged_in_store_id); //approved orders
			$data['ready_orders']=$this->Ordermodel->getReadyOrdersKitchen($logged_in_store_id,$role_id,$user_id); //Ready orders in kitchen dashboard
			$data['delivered_orders']=$this->Ordermodel->getDeliveredOrders($logged_in_store_id); //Ready orders
			//Count display approved dining,delivery,pickup for supplier
			$data['pending_delivery_cooking']=$this->Tablemodel->pending_delivery_cooking($logged_in_store_id);
			$data['pending_delivery_ready']=$this->Tablemodel->pending_delivery_ready($logged_in_store_id);
			$data['pending_pickup_cooking']=$this->Tablemodel->pending_pickup_cooking($logged_in_store_id); 
			$data['pending_pickup_ready']=$this->Tablemodel->pending_pickup_ready($logged_in_store_id);
			$data['comp_pickup_count']=$this->Tablemodel->comp_pickup_count($logged_in_store_id); 
			$data['comp_delivery_count']=$this->Tablemodel->comp_delivery_count($logged_in_store_id); 
			$data['pending_pickup_count']=$this->Tablemodel->pending_pickup_count($logged_in_store_id); 
			$data['pending_delivery_count']=$this->Tablemodel->pending_delivery_count($logged_in_store_id);
			$this->load->view('owner/includes/header',$data);
			$this->load->view('owner/includes/kitchen-dashboard',$data);
			$this->load->view('owner/kitchen_orderdashboard',$data);
			$this->load->view('owner/includes/footer');
		}
	}
	
	public function get_Pending_Orders_Count() {
        $user_id = $this->session->userdata('loginid'); // Loged in user id
		$role_id = $this->session->userdata('roleid'); // Role id
		$logged_in_store_id = $this->session->userdata('logged_in_store_id');
        $dining_count = $this->Ordermodel->get_Pending_Orders_Count_db('D',$logged_in_store_id,$role_id,$user_id);
        $pickup_count = $this->Ordermodel->get_Pending_Orders_Count_db('PK',$logged_in_store_id,$role_id,$user_id);
        $delivery_count = $this->Ordermodel->get_Pending_Orders_Count_db('DL',$logged_in_store_id,$role_id,$user_id);
		$ready_order_count = $this->Ordermodel->get_Ready_Orders_Count_user_assigned($logged_in_store_id,$role_id,$user_id);
        $pending_order_table_ids = $this->Ordermodel->get_pending_order_table_ids();
        
        $data = array(
            'dining'   => $dining_count,
            'pickup'   => $pickup_count,
            'delivery' => $delivery_count,
			'ready_order' => $ready_order_count,
            'table_ids' => $pending_order_table_ids
        );
        
        echo json_encode($data);
    }



	public function reports(){
		$controller = $this->router->fetch_class(); // Gets the current controller name
		$method = $this->router->fetch_method();   // Gets the current method name
		$data['controller'] = $controller;
		$data['method'] = $method;
		$data['store_id'] = $this->session->userdata('logged_in_store_id');
		
		$this->load->model('website/Homemodel');
		$store_details = $this->Homemodel->get_store_details_by_store_id($data['store_id']);
        $support_details = $this->Homemodel->get_support_details_by_country_id($store_details->store_country);
        $data['store_disp_name'] = $store_details->store_disp_name;
        $data['store_address'] = $store_details->store_address;
        $data['support_no'] = $support_details->support_no;
        $data['support_email'] = $support_details->support_email;
		
		$this->load->view('owner/includes/header',$data);
		$this->load->view('owner/includes/owner-dashboard',$data);
		$this->load->view('owner/order/reports');
		$this->load->view('owner/includes/footer');
	}

	public function salesReport($store_id){
		$data['store_id'] = $store_id;  //In this case type return table id
		$this->load->view('owner/order/sales_report',$data);
	}
	public function supplierSalesReport($store_id){
		$data['store_id'] = $store_id;  //In this case type return table id
		$this->load->view('owner/order/supplier_sales_report',$data);
	}


	public function getSalesReportByStoreId() {
		$store_id = $this->input->post('store_id');	
		$date = $this->input->post('date');	
		$salesReports = $this->Ordermodel->getSalesReportByStoreId($store_id , $date);
		// Initialize the table structure
		$table = '';
		$table .= '<table class="table table-striped table-bordered table-hover" id="dataTables-example">';
		$table .= '<thead>';
		$table .= '<tr>';
		$table .= '<th>Date</th>';
		$table .= '<th>Dine In</th>';
		$table .= '<th>Pickup</th>';
		$table .= '<th>Delivery</th>';
		$table .= '</tr>';
		$table .= '</thead>';	
		$table .= '<tbody>';

		// Assume $salesReports is an array containing multiple rows of sales report data
		if (!empty($salesReports)) {
			foreach ($salesReports as $salesReport) {
				$table .= '<tr>';
				$table .= '<td>' . htmlspecialchars($salesReport['sale_date']) . '</td>';
				$table .= '<td>' . htmlspecialchars($salesReport['dinein_count']) . ' (' . htmlspecialchars($salesReport['dinein_total_amount']) . ')</td>';
				$table .= '<td>' . htmlspecialchars($salesReport['pickup_count']) . ' (' . htmlspecialchars($salesReport['pickup_total_amount']) . ')</td>';
				$table .= '<td>' . htmlspecialchars($salesReport['delivery_count']) . ' (' . htmlspecialchars($salesReport['delivery_total_amount']) . ')</td>';
				$table .= '</tr>';
			}
		} else {
			// Handle the case where there's no data
			$table .= '<tr>';
			$table .= '<td colspan="4" class="text-center">No sales data available.</td>';
			$table .= '</tr>';
		}

		// Close the table structure
		$table .= '</tbody>';
		$table .= '</table>';

		// Echo the table
		echo $table;
	}

	public function getSupplierSalesReportByStoreId() {
		$store_id = $this->input->post('store_id');	
		$date = $this->input->post('date');	
		$salesReports = $this->Ordermodel->getSupplierSalesReportByStoreId($store_id , $date);
		// Initialize the table structure
		$table = '';
		$table .= '<table class="table table-striped table-bordered table-hover" id="dataTables-example">';
		$table .= '<thead>';
		$table .= '<tr>';
		$table .= '<th>Date</th>';
		$table .= '<th>Dine In</th>';
		$table .= '<th>Pickup</th>';
		$table .= '<th>Delivery</th>';
		$table .= '</tr>';
		$table .= '</thead>';	
		$table .= '<tbody>';

		// Assume $salesReports is an array containing multiple rows of sales report data
		if (!empty($salesReports)) {
			foreach ($salesReports as $salesReport) {
				$table .= '<tr>';
				$table .= '<td>' . htmlspecialchars($salesReport['sale_date']) . '</td>';
				$table .= '<td>' . htmlspecialchars($salesReport['dinein_count']) . ' (' . htmlspecialchars($salesReport['dinein_total_amount']) . ')</td>';
				$table .= '<td>' . htmlspecialchars($salesReport['pickup_count']) . ' (' . htmlspecialchars($salesReport['pickup_total_amount']) . ')</td>';
				$table .= '<td>' . htmlspecialchars($salesReport['delivery_count']) . ' (' . htmlspecialchars($salesReport['delivery_total_amount']) . ')</td>';
				$table .= '</tr>';
			}
		} else {
			// Handle the case where there's no data
			$table .= '<tr>';
			$table .= '<td colspan="4" class="text-center">No sales data available.</td>';
			$table .= '</tr>';
		}

		// Close the table structure
		$table .= '</tbody>';
		$table .= '</table>';

		// Echo the table
		echo $table;
	}
	
	public function userReport($store_id){
		$data['store_id'] = $store_id;  //In this case type return table id
		$this->load->view('owner/order/user_report',$data);
	}
	public function SupplierUserReport($store_id){
		$data['store_id'] = $store_id;  //In this case type return table id
		$this->load->view('owner/order/supplier_user_report',$data);
	}

	public function getUserReportByStoreId() {
		$store_id = $this->input->post('store_id');	
		$date = $this->input->post('date');	
		$userReports = $this->Ordermodel->getUserReportByStoreId($store_id , $date);
		
		$table = '';
		$table .= '<table class="table table-striped table-bordered table-hover" id="dataTables-example">';
		$table .= '<thead>';
		$table .= '<tr>';
		$table .= '<th>Date</th>';
		$table .= '<th>User</th>';
		$table .= '<th>Login</th>';
		$table .= '<th>Logout</th>';
		$table .= '</tr>';
		$table .= '</thead>';	
		$table .= '<tbody>';

		// Assume $userReports is an array containing multiple rows of user report data
		if (!empty($userReports)) {
			foreach ($userReports as $userReport) {
				$table .= '<tr>';
				$table .= '<td>' . htmlspecialchars($userReport['date']) . '</td>';
				$table .= '<td>' . htmlspecialchars($userReport['Name']) . '</td>';
				$table .= '<td>' . htmlspecialchars($userReport['login_time']) . '</td>';
				$table .= '<td>' . (isset($userReport['logout_time']) && $userReport['logout_time'] ? htmlspecialchars($userReport['logout_time']) : 'Still logged in') . '</td>';

				$table .= '</tr>';
			}
		} else {
			// Handle the case where there's no data
			$table .= '<tr>';
			$table .= '<td colspan="4" class="text-center">No user login data available.</td>';
			$table .= '</tr>';
		}

		// Close the table structure
		$table .= '</tbody>';
		$table .= '</table>';

		// Echo the table
		echo $table;
	}

	public function getSupplierUserReportByStoreId() {
		$store_id = $this->input->post('store_id');	
		$date = $this->input->post('date');	
		$userReports = $this->Ordermodel->getSupplierUserReportByStoreId($store_id , $date);
		
		$table = '';
		$table .= '<table class="table table-striped table-bordered table-hover" id="dataTables-example">';
		$table .= '<thead>';
		$table .= '<tr>';
		$table .= '<th>Date</th>';
		$table .= '<th>User</th>';
		$table .= '<th>Login</th>';
		$table .= '<th>Logout</th>';
		$table .= '</tr>';
		$table .= '</thead>';	
		$table .= '<tbody>';

		// Assume $userReports is an array containing multiple rows of user report data
		if (!empty($userReports)) {
			foreach ($userReports as $userReport) {
				$table .= '<tr>';
				$table .= '<td>' . htmlspecialchars($userReport['date']) . '</td>';
				$table .= '<td>' . htmlspecialchars($userReport['Name']) . '</td>';
				$table .= '<td>' . htmlspecialchars($userReport['login_time']) . '</td>';
				$table .= '<td>' . (isset($userReport['logout_time']) && $userReport['logout_time'] ? htmlspecialchars($userReport['logout_time']) : 'Still logged in') . '</td>';

				$table .= '</tr>';
			}
		} else {
			// Handle the case where there's no data
			$table .= '<tr>';
			$table .= '<td colspan="4" class="text-center">No user login data available.</td>';
			$table .= '</tr>';
		}

		// Close the table structure
		$table .= '</tbody>';
		$table .= '</table>';

		// Echo the table
		echo $table;
	}

	public function deliveryReport($store_id){
		$data['store_id'] = $store_id;  //In this case type return table id
		$this->load->view('owner/order/delivery_report',$data);
	}
	public function getDeliveryReportByStoreId() {
		$store_id = $this->input->post('store_id');	
		$date = $this->input->post('date');	
		$deliveryReports = $this->Ordermodel->getDeliveryReportByStoreId($store_id , $date);
		// Initialize the table structure
		$table = '';
		$table .= '<table class="table table-striped table-bordered table-hover" id="dataTables-example">';
		$table .= '<thead>';
		$table .= '<tr>';
		$table .= '<th>ORDER NO.</th>';
		$table .= '<th>Customer Name</th>';
		$table .= '<th>Customer MOB</th>';
		$table .= '<th>Location</th>';
		$table .= '<th>Payment Mode</th>';
		$table .= '<th>Total Amount</th>';
		$table .= '<th>Status</th>';
		$table .= '<th>Out For Delivery Time</th>';
		$table .= '<th>Delivered Time</th>';
		$table .= '<th>Delivery Boy</th>';
		$table .= '</tr>';
		$table .= '</thead>';	
		$table .= '<tbody>';

		// Assume $deliveryReports is an array containing multiple rows of sales report data
		if (!empty($deliveryReports)) {
			foreach ($deliveryReports as $salesReport) {
				$table .= '<tr>';
				$table .= '<td>' .$salesReport['orderno'] . '</td>';
				$table .= '<td>' .$salesReport['customer_name'] . '</td>';
				$table .= '<td>' .$salesReport['contact_number'] . '</td>';
				$table .= '<td>' .$salesReport['location'] . '</td>';
				$table .= '<td>' .$salesReport['payment_mode'] . '</td>';
				$table .= '<td>' .$salesReport['total_amount'] . '</td>';
				$table .= '<td>' .$salesReport['order_status'] . '</td>';
				$table .= '<td>' .$salesReport['out_for_delivery_time'] . '</td>';
				$table .= '<td>' .$salesReport['delivered_time'] . '</td>';
				$table .= '<td>' .$this->Ordermodel->getDeliveryBoyName($salesReport['delivery_boy']) . '</td>';
				$table .= '</tr>';
			}
		} else {
			// Handle the case where there's no data
			$table .= '<tr>';
			$table .= '<td colspan="4" class="text-center">No sales data available.</td>';
			$table .= '</tr>';
		}

		// Close the table structure
		$table .= '</tbody>';
		$table .= '</table>';

		// Echo the table
		echo $table;
	}

/*************  ✨ Codeium Command ⭐  *************/
/**
 * Loads the view for creating a new order with the order number
 * and a list of products assigned to the shop.
 *
 * The order number is generated by appending the current day,
 * month, and year to a base order number fetched from the
 * Productmodel.
 */

/******  1b850f36-69c8-4792-ba6b-1b055e8e8358  *******/
public function newOrder(){
		$order_no = $this->Productmodel->getOrderNo(); //Generate order number
        $day = date("d");   
        $month = date("m"); 
        $year = date("y"); 
		$data['heading'] = "";  
		$order_no_with_date = $order_no.$day.$month.$year;
		$data['order_number'] = $order_no_with_date;
		$data['products']=$this->Productmodel->shopAssignedProducts();

		$controller = $this->router->fetch_class(); // Gets the current controller name
		$method = $this->router->fetch_method();   // Gets the current method name
		$data['controller'] = $controller;

		$logged_in_store_id = $this->session->userdata('logged_in_store_id');//echo $logged_in_store_id;exit;
		$role_id = $this->session->userdata('roleid'); // Role id of logged in user
		$user_id = $this->session->userdata('loginid'); // Loged in user id
		$loginName = $this->session->userdata('loginName');
		$data['name'] = $loginName;
        
        $store_details = $this->Homemodel->get_store_details_by_store_id($logged_in_store_id);
        $support_details = $this->Homemodel->get_support_details_by_country_id($store_details->store_country);
        $data['store_disp_name'] = $store_details->store_disp_name;
        $data['store_address'] = $store_details->store_address;
        $data['support_no'] = $support_details->support_no;
        $data['support_email'] = $support_details->support_email;
		$data['store_logo'] = $store_details->store_logo_image;
		
		$this->load->view('owner/includes/header',$data);
		$this->load->view('owner/includes/owner-dashboard',$data);
		$this->load->view('owner/order/newOrder',$data);
		$this->load->view('owner/includes/footer');
	}

	public function newDiningOrder($order_number){  
		$store_id = $this->session->userdata('logged_in_store_id');
		$data['order_number'] = $order_number;
		$data['activeTables']=$this->Ordermodel->getActiveTablesByStoreId($store_id);
		$data['products']=$this->Productmodel->shopAssignedActiveProducts();
		$data['heading'] = "Dining"; 
		$data['orderType'] = "D"; 
		$this->load->view('owner/order/newDiningOrder',$data);
	}

	public function newDeliveryOrder($order_number){
		$data['heading'] = "Delivery"; 
		$data['order_number'] = $order_number;
		$data['orderType'] = "DL"; 
		$data['products']=$this->Productmodel->shopAssignedActiveProducts();
		$this->load->view('owner/order/newDeliveryOrder',$data);
	}

	public function newPickupOrder($order_number){
		$data['heading'] = "Pickup";
		$data['order_number'] = $order_number;
		$data['orderType'] = "PK"; 
		$data['products']=$this->Productmodel->shopAssignedActiveProducts();
		$this->load->view('owner/order/newPickupOrder',$data);
	}

	public function setTableReserved(){
		$isReserved = $this->input->post('isReserved');
		$tableId = $this->input->post('tableId');
		$this->Ordermodel->setTableReserved($tableId,$isReserved);
		echo json_encode(array('status' => 'success'));	
	}

	public function SaveConfirmOrder() {
		$orderno = $this->Ordermodel->updateOrderNo($this->input->post('order_id'));
		echo json_encode(array('status' => 'success','orderno' => $orderno ));	
	}
	public function deleteOrderItem() {
		$orderId = $this->input->post('orderId');
		if ($this->Ordermodel->deleteOrderItem($orderId)) {
			echo json_encode(['success' => true]);
		}
	}

	public function deleteOrderItemWithUpdateRemark() {
		$orderId = $this->input->post('orderId');
		$delete_reason = $this->input->post('delete_reason');
		$is_delete = 1;  //1 for delete display orders using condition is_delete != 1
		if ($this->Ordermodel->deleteOrderItemWithUpdateRemark($orderId,$delete_reason,$is_delete)) {
			echo json_encode(['success' => true]);
		}
	}
	
	public function deleteOrder() {
		$orderId = $this->input->post('orderId');
		if ($this->Ordermodel->deleteOrder($orderId)) {
			echo json_encode(['success' => true]);
		}
	}

	public function changeOrderStatus(){
		$orderId = $this->input->post('orderId');
		$status = $this->input->post('status');
		$this->Ordermodel->changeOrderStatus($orderId,$status);
		echo json_encode(['status' => $status,'orderId' => $orderId]);
	}

	public function changeDeliveryBoy(){
		$orderId = $this->input->post('orderId');
		$delivery_boy = $this->input->post('delivery_boy');
		$this->Ordermodel->changeDeliveryBoy($orderId,$delivery_boy);
		echo json_encode(['status' => $delivery_boy]);
	}



	public function SaveNewDiningOrder() {
		$this->load->model('owner/Ordermodel');
		$order_id = $this->input->post('order_id');
		$store_id = $this->input->post('store_id');
		$product_id = $this->input->post('product_id');
		$tableId = $this->input->post('tableID');
		$qty = $this->input->post('qty');

		$date = date('Y-m-d');
		$productDetails = $this->Ordermodel->get_store_wise_product_by_id($product_id);
		$is_combo = $this->productIsCombo($product_id);
		if($is_combo)
		{
					$combo_items = $this->Ordermodel->getComboItems($store_id,$product_id);
					foreach ($combo_items as $item) 
					{
						$stock = $this->Ordermodel->getCurrentStock($item['item_id'], date('Y-m-d'), $store_id);
						if ($stock < ($qty * $item['quantity'])) {
							// echo json_encode(['status' => 'error', 'message' => 'Not enough stock for product: ' . $item['item_id']]);
							echo "<div class='alert alert-danger' role='alert'>". $qty .' '. $this->Ordermodel->getProductName($item['item_id']) . " Not available</div>";
							return;
						}          
					}
					$orderItems = [
						'orderno' => $order_id,
						'date' => date('Y-m-d'),
						'store_id' => $store_id,
						'product_id' => $product_id,
						'quantity' => $qty,
						'vat_id' => $productDetails[0]['vat_id'],
						'rate' => $this->input->post('rate'),
						'amount' => $qty * $this->input->post('rate'),
						'tax' => $this->input->post('tax'),
						'tax_amount' => $this->input->post('tax_amount'),
						'total_amount' => $this->input->post('total_amount'),
						'item_remarks' => $product['recipe'] ?? null,
						'variant_id' => $this->input->post('variant_id') ?? null,
						'category_id' => $productDetails[0]['category_id'], // optional timestamp
						'is_addon' => $productDetails[0]['is_addon'],
						'is_customisable' => $productDetails[0]['is_customizable'],
						'table_id' => $tableId,
						'order_type' => $this->input->post('orderType'),
						'is_paid' => 0,
						'is_reorder' => 0
					];
					
					$this->db->insert('order_items', $orderItems);

					$orderExists = $this->Ordermodel->isOrderExists($order_id);
					if($orderExists) 
					{
						//echo "here";exit;
						$updatedTotalAmt = $this->Ordermodel->updateTotalAmount($order_id);
								$order_data = [
									'amount' => $updatedTotalAmt[0]['total_rate'],
									'tax_amount' => $updatedTotalAmt[0]['total_tax'],
									'total_amount' => $updatedTotalAmt[0]['total_amount']
								];
									$this->db->where('orderno', $order_id);
									$this->db->update('order', $order_data);
					}
					else
					{
						$updateTotalAmountFromItems = $this->Ordermodel->updateTotalAmountFromItems($order_id);
						$order_data = [
							'orderno' => $order_id,
							'order_token' => $this->Productmodel->getOrderNo(),
							'date' => date('Y-m-d'),
							'store_id' => $store_id,
							'amount' => $updateTotalAmountFromItems[0]['total_rate'],
							'tax' => $productDetails[0]['tax'],
							'tax_amount' => $updateTotalAmountFromItems[0]['total_tax'],
							'total_amount' => $updateTotalAmountFromItems[0]['total_amount'],
							'is_paid'   => 0,
							'table_id' => $tableId,
							'order_type' => $this->input->post('orderType'),
							'customer_name	' => '',
							'contact_number' => '',
							'location' => '',
							'modified_by'=>0,
							'modified_date'=> date('Y-m-d H:i:s')
						];
						$this->db->insert('order', $order_data);
					}	

		}
		else
		{
				$availableStock = $this->Ordermodel->getCurrentStock($product_id , $date , $store_id);
				if ($availableStock < $qty) {
					echo "<div class='alert alert-danger' role='alert'>". $qty .' '. $this->Ordermodel->getProductName($product_id) . " Not available</div>";
				}
				
				else
				{

						$orderItems = [
							'orderno' => $order_id,
							'date' => date('Y-m-d'),
							'store_id' => $store_id,
							'product_id' => $product_id,
							'quantity' => $qty,
							'vat_id' => $productDetails[0]['vat_id'],
							'rate' => $this->input->post('rate'),
							'amount' => $qty * $this->input->post('rate'),
							'tax' => $this->input->post('tax'),
							'tax_amount' => $this->input->post('tax_amount'),
							'total_amount' => $this->input->post('total_amount'),
							'item_remarks' => $product['recipe'] ?? null,
							'variant_id' => $this->input->post('variant_id') ?? null,
							'category_id' => $productDetails[0]['category_id'], // optional timestamp
							'is_addon' => $productDetails[0]['is_addon'],
							'is_customisable' => $productDetails[0]['is_customizable'],
							'table_id' => $tableId,
							'order_type' => $this->input->post('orderType'),
							'is_paid' => 0,
							'is_reorder' => 0
						];
						
						$this->db->insert('order_items', $orderItems);

						$orderExists = $this->Ordermodel->isOrderExists($order_id);
						if($orderExists) 
						{
							//echo "here";exit;
							$updatedTotalAmt = $this->Ordermodel->updateTotalAmount($order_id);
									$order_data = [
										'amount' => $updatedTotalAmt[0]['total_rate'],
										'tax_amount' => $updatedTotalAmt[0]['total_tax'],
										'total_amount' => $updatedTotalAmt[0]['total_amount']
									];
										$this->db->where('orderno', $order_id);
										$this->db->update('order', $order_data);
						}
						else
						{
							$updateTotalAmountFromItems = $this->Ordermodel->updateTotalAmountFromItems($order_id);
							$order_data = [
								'orderno' => $order_id,
								'order_token' => $this->Productmodel->getOrderNo(),
								'date' => date('Y-m-d'),
								'store_id' => $store_id,
								'amount' => $updateTotalAmountFromItems[0]['total_rate'],
								'tax' => $productDetails[0]['tax'],
								'tax_amount' => $updateTotalAmountFromItems[0]['total_tax'],
								'total_amount' => $updateTotalAmountFromItems[0]['total_amount'],
								'is_paid'   => 0,
								'table_id' => $tableId,
								'order_type' => $this->input->post('orderType'),
								'customer_name	' => '',
								'contact_number' => '',
								'location' => '',
								'modified_by'=>0,
								'modified_date'=> date('Y-m-d H:i:s')
							];
							$this->db->insert('order', $order_data);
						}
			}
	}


		$orders = $this->Ordermodel->getOrderItems($order_id);
if (!empty($orders)) {
		$accordionHtml = '';
		$total_amount = 0;
		$accordionHtml = '<form method="post" action="' . base_url('owner/order/update') . '">
		<input type="hidden" name="store_id" value="' . $this->session->userdata('logged_in_store_id') . '">
		<input type="hidden" name="order_id" value="' . $order_id . '">
		<div class="table-responsive">  
		<table class="table">
            <thead>
                <tr>
                    <th width="5%">Sl</th>
                    <th width="25%">Product</th>
					<th width="10%">Quantity</th>
					<th width="10%">Rate</th>
					<th width="10%">Amount</th>
					<th width="5%">Vat(%)</th>
					<th width="10%">Vat-Amt</th>
					<th width="10%">Total-Amt</th>
					<th width="10%">Is Addon</th>
					<th width="20%">Recipe Details</th>
                </tr>
            </thead>
            <tbody>';
		foreach ($orders as $index => $order) {
			$accordionHtml .= '
                <tr id="order-row-' . $order['id'] . '">
                    <td>' . $index + 1 . '</td>
                    <td>' . $this->Ordermodel->getProductName($order['product_id']) . '</td>
					<td>
					<input type="hidden" readonly class="form-control" style="width: 100%;" name="orders[' . $index . '][tax]" value="' . $order['tax'] . '">
					<input type="hidden" readonly class="form-control" style="width: 100%;" name="orders[' . $index . '][id]" value="' . $order['id'] . '">
	<input type="hidden" readonly class="form-control" style="width: 100%;" name="orders[' . $index . '][product_id]" value="' . $order['product_id'] . '">
					<input type="text" class="quantity form-control" name="orders[' . $index . '][quantity]" style="width: 100%;" value="' . $order['quantity'] . '" />
					</td>
					<td><input type="text" readonly class="form-control" style="width: 100%;" name="orders[' . $index . '][rate]" value="' . $order['rate'] . '"></td>
					<td>' . $order['rate'] * $order['quantity'] . '</td>
					<td>' . $order['tax'] . '</td>
					<td>' . $order['tax_amount'] . '</td>
					<td>' . $order['total_amount'] . '</td>
					<td><input type="checkbox" class="form-check-input" disabled name="orders[' . $index . '][is_addon]" value="1" ' . ($order['is_addon'] == 1 ? 'checked' : '') . '></td>
                    <td>' . $order['item_remarks'] . '</td>
					<td><button type="button" class="btn btn-danger delete-order" data-id="' . $order['id'] . '">Delete</button></td>
                </tr>';
				$item_total = $order['quantity'] * $order['total_amount'];
        		$total_amount += $item_total;
		}
		$accordionHtml .= '</tbody>
		<tfoot class="table-light">
                <tr>
				<td colspan="6">
                        <div class="d-flex justify-content-left">
                            <label class="btn text-black bg-b-cyan" width="100px" style="margin-right: 10px;">Order No : '.$order['orderno'].'</label>
                        </div>
                    </td>
					<td colspan="6">
                        <div class="d-flex justify-content-end">
							<a class="btn btn-danger" id="saveConfirmOrder" style="margin-right: 10px;">SAVE ORDER</a>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table></form>
		</div>';

		
	
		echo $accordionHtml;
	}
	}








	// Save New Pickup Order
		public function SaveNewPickupOrder() {
			$this->load->model('owner/Ordermodel');
			$order_id = $this->input->post('order_id');
			$store_id = $this->input->post('store_id');
			$product_id = $this->input->post('product_id');
			$tableId = $this->input->post('tableID');
			$qty = $this->input->post('qty');
			//echo $order_id;echo $store_id;echo $product_id;exit;

			$date = date('Y-m-d');
			$productDetails = $this->Ordermodel->get_store_wise_product_by_id($product_id);
			$is_combo = $this->productIsCombo($product_id);
			if($is_combo)
			{
					$combo_items = $this->Ordermodel->getComboItems($store_id,$product_id);
					foreach ($combo_items as $item) 
					{
						$stock = $this->Ordermodel->getCurrentStock($item['item_id'], date('Y-m-d'), $store_id);
						if ($stock < ($qty * $item['quantity'])) {
							// echo json_encode(['status' => 'error', 'message' => 'Not enough stock for product: ' . $item['item_id']]);
							echo "<div class='alert alert-danger' role='alert'>". $qty .' '. $this->Ordermodel->getProductName($item['item_id']) . " Not available</div>";
							return;
						}          
					}
					$orderItems = [
						'orderno' => $order_id,
						'date' => date('Y-m-d'),
						'store_id' => $store_id,
						'product_id' => $product_id,
						'quantity' => $qty,
						'vat_id' => $productDetails[0]['vat_id'],
						'rate' => $this->input->post('rate'),
						'amount' => $qty * $this->input->post('rate'),
						'tax' => $this->input->post('tax'),
						'tax_amount' => $this->input->post('tax_amount'),
						'total_amount' => $this->input->post('total_amount'),
						'item_remarks' => $product['recipe'] ?? null,
						'variant_id' => $this->input->post('variant_id') ?? null,
						'category_id' => $productDetails[0]['category_id'], // optional timestamp
						'is_addon' => $productDetails[0]['is_addon'],
						'is_customisable' => $productDetails[0]['is_customizable'],
						'table_id' => $tableId,
						'order_type' => $this->input->post('orderType'),
						'is_paid' => 0,
						'is_reorder' => 0
					];
					
					$this->db->insert('order_items', $orderItems);
			
					$orderExists = $this->Ordermodel->isOrderExists($order_id);
					if($orderExists) 
					{
						//echo "here";exit;
						$updatedTotalAmt = $this->Ordermodel->updateTotalAmount($order_id);
								$order_data = [
									'amount' => $updatedTotalAmt[0]['total_rate'],
									'tax_amount' => $updatedTotalAmt[0]['total_tax'],
									'total_amount' => $updatedTotalAmt[0]['total_amount']
								];
									$this->db->where('orderno', $order_id);
									$this->db->update('order', $order_data);
					}
					else
					{
						$updateTotalAmountFromItems = $this->Ordermodel->updateTotalAmountFromItems($order_id);
						$order_data = [
							'orderno' => $order_id,
							'date' => date('Y-m-d'),
							'store_id' => $store_id,
							'amount' => $updateTotalAmountFromItems[0]['total_rate'],
							'tax' => $productDetails[0]['tax'],
							'tax_amount' => $updateTotalAmountFromItems[0]['total_tax'],
							'total_amount' => $updateTotalAmountFromItems[0]['total_amount'],
							'is_paid'   => 0,
							'table_id' => $tableId,
							'order_type' => $this->input->post('orderType'),
							'customer_name	' => $this->input->post('name'),
							'contact_number' => $this->input->post('number'),
							'time' => $this->input->post('time'),
							'location' => '',
							'modified_by'=>0,
							'modified_date'=> date('Y-m-d H:i:s')
						];
						$this->db->insert('order', $order_data);
					}


			}
			else
			{
					$availableStock = $this->Ordermodel->getCurrentStock($product_id , $date , $store_id);
					if ($availableStock < $qty) {
						echo "<div class='alert alert-danger' role='alert'>". $qty .' '. $this->Ordermodel->getProductName($product_id) . " Not available</div>";
					}
					
					else
					{
					$orderItems = [
						'orderno' => $order_id,
						'date' => date('Y-m-d'),
						'store_id' => $store_id,
						'product_id' => $product_id,
						'quantity' => $qty,
						'vat_id' => $productDetails[0]['vat_id'],
						'rate' => $this->input->post('rate'),
						'amount' => $qty * $this->input->post('rate'),
						'tax' => $this->input->post('tax'),
						'tax_amount' => $this->input->post('tax_amount'),
						'total_amount' => $this->input->post('total_amount'),
						'item_remarks' => $product['recipe'] ?? null,
						'variant_id' => $this->input->post('variant_id') ?? null,
						'category_id' => $productDetails[0]['category_id'], // optional timestamp
						'is_addon' => $productDetails[0]['is_addon'],
						'is_customisable' => $productDetails[0]['is_customizable'],
						'table_id' => $tableId,
						'order_type' => $this->input->post('orderType'),
						'is_paid' => 0,
						'is_reorder' => 0
					];
					
					$this->db->insert('order_items', $orderItems);
			
					$orderExists = $this->Ordermodel->isOrderExists($order_id);
					if($orderExists) 
					{
						//echo "here";exit;
						$updatedTotalAmt = $this->Ordermodel->updateTotalAmount($order_id);
								$order_data = [
									'amount' => $updatedTotalAmt[0]['total_rate'],
									'tax_amount' => $updatedTotalAmt[0]['total_tax'],
									'total_amount' => $updatedTotalAmt[0]['total_amount']
								];
									$this->db->where('orderno', $order_id);
									$this->db->update('order', $order_data);
					}
					else
					{
						$updateTotalAmountFromItems = $this->Ordermodel->updateTotalAmountFromItems($order_id);
						$order_data = [
							'orderno' => $order_id,
							'date' => date('Y-m-d'),
							'store_id' => $store_id,
							'amount' => $updateTotalAmountFromItems[0]['total_rate'],
							'tax' => $productDetails[0]['tax'],
							'tax_amount' => $updateTotalAmountFromItems[0]['total_tax'],
							'total_amount' => $updateTotalAmountFromItems[0]['total_amount'],
							'is_paid'   => 0,
							'table_id' => $tableId,
							'order_type' => $this->input->post('orderType'),
							'customer_name	' => $this->input->post('name'),
							'contact_number' => $this->input->post('number'),
							'time' => $this->input->post('time'),
							'location' => '',
							'modified_by'=>0,
							'modified_date'=> date('Y-m-d H:i:s')
						];
						$this->db->insert('order', $order_data);
					}
				}
			}
	
	
			$orders = $this->Ordermodel->getOrderItems($order_id);
	if(!empty($orders)) {
		
			$accordionHtml = '';
			$total_amount = 0;
			$accordionHtml = '<form method="post" action="' . base_url('owner/order/update') . '">
			<input type="hidden" name="store_id" value="' . $this->session->userdata('logged_in_store_id') . '">
			<input type="hidden" name="order_id" value="' . $order_id . '">
			<div class="table-responsive">  
			<table class="table">
				<thead>
					<tr>
						<th width="5%">Sl</th>
						<th width="25%">Product</th>
						<th width="10%">Quantity</th>
						<th width="10%">Rate</th>
						<th width="10%">Amount</th>
						<th width="5%">Vat(%)</th>
						<th width="10%">Vat-Amt</th>
						<th width="10%">Total-Amt</th>
						<th width="10%">Is Addon</th>
						<th width="20%">Recipe Details</th>
					</tr>
				</thead>
				<tbody>';
			foreach ($orders as $index => $order) {
				$accordionHtml .= '
					<tr id="order-row-' . $order['id'] . '">
						<td>' . $index + 1 . '</td>
						<td>' . $this->Ordermodel->getProductName($order['product_id']) . '</td>
						<td>
						<input type="hidden" readonly class="form-control" style="width: 100%;" name="orders[' . $index . '][tax]" value="' . $order['tax'] . '">
						<input type="hidden" readonly class="form-control" style="width: 100%;" name="orders[' . $index . '][id]" value="' . $order['id'] . '">
		<input type="hidden" readonly class="form-control" style="width: 100%;" name="orders[' . $index . '][product_id]" value="' . $order['product_id'] . '">
						<input type="text" class="quantity form-control" name="orders[' . $index . '][quantity]" style="width: 100%;" value="' . $order['quantity'] . '" />
						</td>
						<td><input type="text" readonly class="form-control" style="width: 100%;" name="orders[' . $index . '][rate]" value="' . $order['rate'] . '"></td>
						<td>' . $order['rate'] * $order['quantity'] . '</td>
						<td>' . $order['tax'] . '</td>
						<td>' . $order['tax_amount'] . '</td>
						<td>' . $order['total_amount'] . '</td>
						<td><input type="checkbox" class="form-check-input" disabled name="orders[' . $index . '][is_addon]" value="1" ' . ($order['is_addon'] == 1 ? 'checked' : '') . '></td>
						<td>' . $order['item_remarks'] . '</td>
						<td><button type="button" class="btn btn-danger delete-order" data-id="' . $order['id'] . '">Delete</button></td>
					</tr>';
					$item_total = $order['quantity'] * $order['total_amount'];
					$total_amount += $item_total;
			}
			$accordionHtml .= '</tbody>
			<tfoot class="table-light">
					<tr>
					<td colspan="6">
							<div class="d-flex justify-content-left">
								<label class="btn text-black bg-b-cyan" width="100px" style="margin-right: 10px;">Order No : '.$order['orderno'].'</label>
							</div>
						</td>
						<td colspan="6">
							<div class="d-flex justify-content-end">
								<a class="btn btn-danger" id="saveConfirmOrder" style="margin-right: 10px;">SAVE ORDER</a>
							</div>
						</td>
					</tr>
				</tfoot>
			</table></form>
			</div>';
		
			echo $accordionHtml;
		}
		}









		// Save New Delivery Order
	public function SaveNewDeliveryOrder() {
		$this->load->model('owner/Ordermodel');
		$order_id = $this->input->post('order_id');
		$store_id = $this->input->post('store_id');
		$product_id = $this->input->post('product_id');
		$tableId = $this->input->post('tableID');
		$qty = $this->input->post('qty');

		$date = date('Y-m-d');
		$productDetails = $this->Ordermodel->get_store_wise_product_by_id($product_id);
		$is_combo = $this->productIsCombo($product_id);
		if($is_combo)
		{
					$combo_items = $this->Ordermodel->getComboItems($store_id,$product_id);
					foreach ($combo_items as $item) 
					{
						$stock = $this->Ordermodel->getCurrentStock($item['item_id'], date('Y-m-d'), $store_id);
						if ($stock < ($qty * $item['quantity'])) {
							// echo json_encode(['status' => 'error', 'message' => 'Not enough stock for product: ' . $item['item_id']]);
							echo "<div class='alert alert-danger' role='alert'>". $qty .' '. $this->Ordermodel->getProductName($item['item_id']) . " Not available</div>";
							return;
						}          
					}

					$orderItems = [
						'orderno' => $order_id,
						'date' => date('Y-m-d'),
						'store_id' => $store_id,
						'product_id' => $product_id,
						'quantity' => $qty,
						'vat_id' => $productDetails[0]['vat_id'],
						'rate' => $this->input->post('rate'),
						'amount' => $qty * $this->input->post('rate'),
						'tax' => $this->input->post('tax'),
						'tax_amount' => $this->input->post('tax_amount'),
						'total_amount' => $this->input->post('total_amount'),
						'item_remarks' => $product['recipe'] ?? null,
						'variant_id' => $this->input->post('variant_id') ?? null,
						'category_id' => $productDetails[0]['category_id'], // optional timestamp
						'is_addon' => $productDetails[0]['is_addon'],
						'is_customisable' => $productDetails[0]['is_customizable'],
						'table_id' => $tableId,
						'order_type' => $this->input->post('orderType'),
						'is_paid' => 0,
						'is_reorder' => 0
					];
					
					$this->db->insert('order_items', $orderItems);

					$orderExists = $this->Ordermodel->isOrderExists($order_id);
					if($orderExists) 
					{
						//echo "here";exit;
						$updatedTotalAmt = $this->Ordermodel->updateTotalAmount($order_id);
								$order_data = [
									'amount' => $updatedTotalAmt[0]['total_rate'],
									'tax_amount' => $updatedTotalAmt[0]['total_tax'],
									'total_amount' => $updatedTotalAmt[0]['total_amount']
								];
									$this->db->where('orderno', $order_id);
									$this->db->update('order', $order_data);
					}
					else
					{
						$updateTotalAmountFromItems = $this->Ordermodel->updateTotalAmountFromItems($order_id);
						$order_data = [
							'orderno' => $order_id,
							'date' => date('Y-m-d'),
							'store_id' => $store_id,
							'amount' => $updateTotalAmountFromItems[0]['total_rate'],
							'tax' => $productDetails[0]['tax'],
							'tax_amount' => $updateTotalAmountFromItems[0]['total_tax'],
							'total_amount' => $updateTotalAmountFromItems[0]['total_amount'],
							'is_paid'   => 0,
							'table_id' => $tableId,
							'order_type' => $this->input->post('orderType'),
							'customer_name	' => $this->input->post('name'),
							'contact_number' => $this->input->post('number'),
							'location' => $this->input->post('address'),
							'time' => $this->input->post('time'),
							'payment_mode' => $this->input->post('paymentMode'),
							'modified_by'=>0,
							'modified_date'=> date('Y-m-d H:i:s')
						];
						$this->db->insert('order', $order_data);
					}

		}
		else
		{
					$availableStock = $this->Ordermodel->getCurrentStock($product_id , $date , $store_id);
					if ($availableStock < $qty) {
						echo "<div class='alert alert-danger' role='alert'>". $qty .' '. $this->Ordermodel->getProductName($product_id) . " Not available</div>";
					}
					else
					{
					$orderItems = [
						'orderno' => $order_id,
						'date' => date('Y-m-d'),
						'store_id' => $store_id,
						'product_id' => $product_id,
						'quantity' => $qty,
						'vat_id' => $productDetails[0]['vat_id'],
						'rate' => $this->input->post('rate'),
						'amount' => $qty * $this->input->post('rate'),
						'tax' => $this->input->post('tax'),
						'tax_amount' => $this->input->post('tax_amount'),
						'total_amount' => $this->input->post('total_amount'),
						'item_remarks' => $product['recipe'] ?? null,
						'variant_id' => $this->input->post('variant_id') ?? null,
						'category_id' => $productDetails[0]['category_id'], // optional timestamp
						'is_addon' => $productDetails[0]['is_addon'],
						'is_customisable' => $productDetails[0]['is_customizable'],
						'table_id' => $tableId,
						'order_type' => $this->input->post('orderType'),
						'is_paid' => 0,
						'is_reorder' => 0
					];
					
					$this->db->insert('order_items', $orderItems);

					$orderExists = $this->Ordermodel->isOrderExists($order_id);
					if($orderExists) 
					{
						//echo "here";exit;
						$updatedTotalAmt = $this->Ordermodel->updateTotalAmount($order_id);
								$order_data = [
									'amount' => $updatedTotalAmt[0]['total_rate'],
									'tax_amount' => $updatedTotalAmt[0]['total_tax'],
									'total_amount' => $updatedTotalAmt[0]['total_amount']
								];
									$this->db->where('orderno', $order_id);
									$this->db->update('order', $order_data);
					}
					else
					{
						$updateTotalAmountFromItems = $this->Ordermodel->updateTotalAmountFromItems($order_id);
						$order_data = [
							'orderno' => $order_id,
							'date' => date('Y-m-d'),
							'store_id' => $store_id,
							'amount' => $updateTotalAmountFromItems[0]['total_rate'],
							'tax' => $productDetails[0]['tax'],
							'tax_amount' => $updateTotalAmountFromItems[0]['total_tax'],
							'total_amount' => $updateTotalAmountFromItems[0]['total_amount'],
							'is_paid'   => 0,
							'table_id' => $tableId,
							'order_type' => $this->input->post('orderType'),
							'customer_name	' => $this->input->post('name'),
							'contact_number' => $this->input->post('number'),
							'location' => $this->input->post('address'),
							'time' => $this->input->post('time'),
							'payment_mode' => $this->input->post('paymentMode'),
							'modified_by'=>0,
							'modified_date'=> date('Y-m-d H:i:s')
						];
						$this->db->insert('order', $order_data);
					}
				}
		}


		$orders = $this->Ordermodel->getOrderItems($order_id);
if(!empty($orders)) {
		$accordionHtml = '';
		$total_amount = 0;
		$accordionHtml = '<form method="post" action="' . base_url('owner/order/update') . '">
		<input type="hidden" name="store_id" value="' . $this->session->userdata('logged_in_store_id') . '">
		<input type="hidden" name="order_id" value="' . $order_id . '">
		<div class="table-responsive">  
		<table class="table">
            <thead>
                <tr>
                    <th width="5%">Sl</th>
                    <th width="25%">Product</th>
					<th width="10%">Quantity</th>
					<th width="10%">Rate</th>
					<th width="10%">Amount</th>
					<th width="5%">Vat(%)</th>
					<th width="10%">Vat-Amt</th>
					<th width="10%">Total-Amt</th>
					<th width="10%">Is Addon</th>
					<th width="20%">Recipe Details</th>
                </tr>
            </thead>
            <tbody>';
		foreach ($orders as $index => $order) {
			$accordionHtml .= '
                <tr id="order-row-' . $order['id'] . '">
                    <td>' . $index + 1 . '</td>
                    <td>' . $this->Ordermodel->getProductName($order['product_id']) . '</td>
					<td>
					<input type="hidden" readonly class="form-control" style="width: 100%;" name="orders[' . $index . '][tax]" value="' . $order['tax'] . '">
					<input type="hidden" readonly class="form-control" style="width: 100%;" name="orders[' . $index . '][id]" value="' . $order['id'] . '">
	<input type="hidden" readonly class="form-control" style="width: 100%;" name="orders[' . $index . '][product_id]" value="' . $order['product_id'] . '">
					<input type="text" class="quantity form-control" name="orders[' . $index . '][quantity]" style="width: 100%;" value="' . $order['quantity'] . '" />
					</td>
					<td><input type="text" readonly class="form-control" style="width: 100%;" name="orders[' . $index . '][rate]" value="' . $order['rate'] . '"></td>
					<td>' . $order['rate'] * $order['quantity'] . '</td>
					<td>' . $order['tax'] . '</td>
					<td>' . $order['tax_amount'] . '</td>
					<td>' . $order['total_amount'] . '</td>
					<td><input type="checkbox" class="form-check-input" disabled name="orders[' . $index . '][is_addon]" value="1" ' . ($order['is_addon'] == 1 ? 'checked' : '') . '></td>
                    <td>' . $order['item_remarks'] . '</td>
					<td><button type="button" class="btn btn-danger delete-order" data-id="' . $order['id'] . '">Delete</button></td>
                </tr>';
				$item_total = $order['quantity'] * $order['total_amount'];
        		$total_amount += $item_total;
		}
		$accordionHtml .= '</tbody>
		<tfoot class="table-light">
                <tr>
				<td colspan="6">
                        <div class="d-flex justify-content-left">
                            <label class="btn text-black bg-b-cyan" width="100px" style="margin-right: 10px;">Order No : '.$order['orderno'].'</label>
                        </div>
                    </td>
					<td colspan="6">
                        <div class="d-flex justify-content-end">
							<a class="btn btn-danger" id="saveConfirmOrder" style="margin-right: 10px;">SAVE ORDER</a>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table></form>
		</div>';
	
		echo $accordionHtml;
	}
	}





	// end







    public function tableOrders($tableId) {
        $data['tableId'] = $tableId;
        $this->load->view('owner/order/table_orders',$data);
    }
	public function pickupOrderDetails($orderNo) {
		$data['orderNo'] = $orderNo;
		$this->load->view('owner/order/pickup_order_details',$data);
    }
	public function completedOrdersPKDL($type){
		$data['type'] = $type;
		$this->load->view('owner/order/completed_orders',$data);
	}

	public function ordersPKDL($type){
		$data['type'] = $type;
		$this->load->view('owner/order/pending_orders',$data);
	}
	

	public function OrdersPendingPKDL($table_id){
		$data['table_id'] = $table_id;  //In this case type return table id
		$this->load->view('owner/order/pending_table_orders',$data);
	}

	public function AddOrderItems($orderno){
		$data['type'] = $orderno;
		$data['products']=$this->Productmodel->shopAssignedActiveProducts();
		$this->load->view('owner/order/addOrderItem',$data);
	}


	public function PrintOrderItems(){
		$orderno = $this->input->post('order_no');
		$data['order_no'] = $orderno;
		$data['storeDet']=$this->Storemodel->get($this->session->userdata('logged_in_store_id'));
		//$this->Ordermodel->CheckOrderPaid($this->session->userdata('logged_in_store_id'),$orderno);
		$data['order']=$this->Ordermodel->getOrderSummary($orderno);
		$data['order_items']=$this->Ordermodel->getOrderItems($orderno); //print_r($data['order']);print_r($data['order_items']);exit;
		$this->load->view('owner/order/printOrderItem',$data);
	}

	public function getKotPrintOrderItems(){
		$order_no = $this->input->post('order_no');
		$data['order_no'] = $order_no;
		$data['storeDet']=$this->Storemodel->get($this->session->userdata('logged_in_store_id'));
		//$data['order']=$this->Ordermodel->getOrderSummary($order_no);
		$data['order_items']=$this->Ordermodel->getOrderItems($order_no); //print_r($data['order']);print_r($data['order_items']);exit;
		$this->load->view('owner/order/kotPrintOrderItem',$data);
	}

	public function getProductRates(){
		$this->load->model('owner/Ordermodel');
		$qty = $this->input->post('qty');
		 $store_id = $this->input->post('store_id');
		 $product_id = $this->input->post('product_id');
		 $variant_id = $this->input->post('variant_id');
		 $rates = $this->Ordermodel->getProductRatesDb($store_id,$product_id,$variant_id);
		 $tax_amount = $qty * $rates->rate * 5 / 100;
		 $total_amount = $qty * $rates->rate + $tax_amount;
		 echo json_encode(['rate' => $rates->rate , 'tax' => 5 , 'tax_amount' => $tax_amount,'total_amount' => $total_amount , 'variant_id' => $variant_id]);
	}

	public function getProductRatesNotCustomize(){
		$this->load->model('owner/Ordermodel');
		$qty = $this->input->post('qty');
		$store_id = $this->input->post('store_id');
		$product_id = $this->input->post('product_id');
		$rates = $this->Ordermodel->getProductRatesNotCustomizeDb($store_id,$product_id);
		$productDetails = $this->Ordermodel->get_store_wise_product_by_id($product_id);
		$tax_amount = $qty * $rates->rate * $productDetails[0]['tax']  / 100;
		$total_amount = $qty * $rates->rate + $tax_amount;
		echo json_encode(['rate' => $rates->rate , 'tax' => $productDetails[0]['tax'] , 'tax_amount' => $tax_amount,'total_amount' => $total_amount]);
	}

	public function update() {
		$this->load->model('owner/Ordermodel');
		$orders = $this->input->post('orders');
		$store_id = $this->input->post('store_id');
		$order_id = $this->input->post('order_id');
		if(isset($_POST['approve'])){
			$tax_amount = 0;
			$total_amount = 0;
			foreach ($orders as $key => $order) {
				$tax_amount = $order['quantity'] * $order['rate'] * $order['tax'] / 100;
				$total_amount = $order['quantity'] * $order['rate'] + $tax_amount;
				$order_sl  = $order['id'];
				$product_id  = $order['product_id'];
				$this->Ordermodel->CheckOrderApprove($order_sl,$store_id,$order_id,$product_id,$order['quantity'],$order['rate'],$tax_amount,$total_amount);	
			}
		}
		else if(isset($_POST['pay'])){
			$this->Ordermodel->CheckOrderPaid($store_id,$order_id);	
		}
		else
		{
			echo "Print";
		}
	}


	public function update_order() {
		$this->load->model('owner/Ordermodel');
		$order_id = $this->input->post('orderId');
		$category_id = $this->input->post('category');
    	$orders = $this->input->post('items');
		$store_id = $this->session->userdata('logged_in_store_id');
			$tax_amount = 0;   
			$total_amount = 0;
			$this->db->delete('store_stock', array('ttype' => 'SL', 'store_id' => $store_id, 'order_id' => $order_id,'tr_date' => date('Y-m-d')));
			foreach ($orders as $key => $order) {
				$tax_amount = $order['quantity'] * $order['rate'] * $order['tax'] / 100;
				$total_amount = $order['quantity'] * $order['rate'] + $tax_amount;
				$order_sl  = $order['id'];
				$product_id  = $order['store_product_id'];
				$this->Ordermodel->CheckOrderApprove($order_sl,$store_id,$order_id,$product_id,$order['quantity'],$order['rate'],$tax_amount,$total_amount,$category_id);	
			}
			echo json_encode(['status' => 'success']);
	}

	public function update_order_item() {
		$this->load->model('owner/Ordermodel');
		$item_id = $this->input->post('item_id');
		$product_id = $this->input->post('product_id');
    	$quantity = $this->input->post('quantity');
		$rate = $this->input->post('rate');
		$orderno = $this->input->post('orderno');
		$type = $this->input->post('type'); // button click Increment or decrement for stock updation
		$orderstatus = $this->input->post('orderstatus'); // Order status
		$store_id = $this->session->userdata('logged_in_store_id');
		$productDetails = $this->Ordermodel->get_store_wise_product_by_id($product_id);
		$tax_amount = $quantity * $rate * $productDetails[0]['tax'] / 100;
		$this->Ordermodel->update_order_item($store_id,$orderno,$item_id,$product_id,$quantity,$rate,$tax_amount,$type,$orderstatus);
		echo json_encode(['status' => 'success','item_id' => $item_id,'product_id' => $product_id,'quantity' => $quantity,'rate' => $rate,'tax' => $productDetails[0]['tax'],'orderno' => $orderno]);
	}

/*************  ✨ Codeium Command ⭐  *************/
/**
 * Removes stock associated with a specific order item.
 *
 * This function deletes the stock entry for a given product item
 * from the order based on the provided inputs. It interacts with
 * the Ordermodel to perform the deletion.
 *
 * Inputs:
 * - 'product_id': The ID of the product whose stock is to be removed.
 * - 'item_id': The ID of the order item to be removed.
 * - 'orderstatus': The status of the order.
 *
 * Uses session data to obtain:
 * - 'logged_in_store_id': The ID of the store for the current session.
 */

/******  cd027bc9-ed1e-4de0-a94e-efd4afb6e233  *******/
	public function deleteOrderItemStockRemove(){
		$this->load->model('owner/Ordermodel');
		$product_id = $this->input->post('product_id');
		$item_id = $this->input->post('item_id');
		$orderstatus = $this->input->post('orderstatus'); // Order status
		$store_id = $this->session->userdata('logged_in_store_id');
		$this->Ordermodel->deleteOrderItemStockRemove($store_id,$item_id,$orderstatus,$product_id);
	}

	public function pay_order(){
		$this->load->model('owner/Ordermodel');
		$order_id = $this->input->post('orderId');
		$store_id = $this->session->userdata('logged_in_store_id');
		$this->Ordermodel->CheckOrderPaid($store_id,$order_id);	
		echo json_encode(['status' => 'success']);
	}
	public function dining_order(){
		$this->load->model('owner/Ordermodel');
		$order_id = $this->input->post('orderId');
		$store_id = $this->session->userdata('logged_in_store_id');
		$this->Ordermodel->change_to_dining_status($store_id,$order_id);
		echo json_encode(['status' => 'success']);	
	}
	
	public function SaveOrderWIthExisting(){
		$this->load->model('owner/Ordermodel');
		$order_id = $this->input->post('order_id');
		$store_id = $this->input->post('store_id');
		$product_id = $this->input->post('product_id');
		$qty = $this->input->post('qty');
		$date = date('Y-m-d');
		$productDetails = $this->Ordermodel->get_store_wise_product_by_id($product_id);
		$is_combo = $this->productIsCombo($product_id);
		if($is_combo)
		{
			$combo_items = $this->Ordermodel->getComboItems($store_id,$product_id);
			foreach ($combo_items as $item) 
			{
				$stock = $this->Ordermodel->getCurrentStock($item['item_id'], date('Y-m-d'), $store_id);
				if ($stock < ($qty * $item['quantity'])) {
					echo json_encode(['status' => 'error', 'message' => 'Not enough stock for product: ' . $item['item_id']]);
					return;
				}          
			}
					$data = [
						'orderno' => $order_id,
						'date' => date('Y-m-d'),
						'store_id' => $store_id,
						'product_id' => $product_id,
						'quantity' => $qty,
						'vat_id' => $productDetails[0]['vat_id'],
						'rate' => $this->input->post('rate'),
						'tax' => $this->input->post('tax'),
						'tax_amount' => $this->input->post('tax_amount'),
						'total_amount' => $this->input->post('total_amount'),
						'item_remarks' => $product['recipe'] ?? null,
						'variant_id' => $this->input->post('variant_id') ?? null,
						'category_id' => $productDetails[0]['category_id'], // optional timestamp
						'is_addon' => $productDetails[0]['is_addon'],
						'is_customisable' => $productDetails[0]['is_customizable'],
						'table_id' => $this->Ordermodel->getOrderTableId($order_id),
						'order_type' => 'D',
						'is_paid' => 0,
						'is_reorder' => 1
					];
					//print_r($data);exit;
					$this->db->insert('order_items', $data);
					$this->Ordermodel->changeOrderStatus($order_id,0);

					$updatedTotalAmt = $this->Ordermodel->updateTotalAmount($order_id);
						$data = [
							'amount' => $updatedTotalAmt[0]['total_rate'],
							'tax_amount' => $updatedTotalAmt[0]['total_tax'],
							'total_amount' => $updatedTotalAmt[0]['total_amount']
								];
							$this->db->where('orderno', $order_id);
							$this->db->update('order', $data);
							
							echo json_encode(['status' => 'success', 'table_id' => $this->Ordermodel->getOrderTableId($order_id)]);
			
		}
		else
		{
				$availableStock = $this->Ordermodel->getCurrentStock($product_id , $date , $store_id);
				if ($availableStock < $qty) {
					echo json_encode(['status' => 'error', 'message' => 'Not enough stock']);
				}
				else
				{
					$data = [
						'orderno' => $order_id,
						'date' => date('Y-m-d'),
						'store_id' => $store_id,
						'product_id' => $product_id,
						'quantity' => $qty,
						'vat_id' => $productDetails[0]['vat_id'],
						'rate' => $this->input->post('rate'),
						'tax' => $this->input->post('tax'),
						'tax_amount' => $this->input->post('tax_amount'),
						'total_amount' => $this->input->post('total_amount'),
						'item_remarks' => $product['recipe'] ?? null,
						'variant_id' => $this->input->post('variant_id') ?? null,
						'category_id' => $productDetails[0]['category_id'], // optional timestamp
						'is_addon' => $productDetails[0]['is_addon'],
						'is_customisable' => $productDetails[0]['is_customizable'],
						'table_id' => $this->Ordermodel->getOrderTableId($order_id),
						'order_type' => 'D',
						'is_paid' => 0,
						'is_reorder' => 1
					];
					//print_r($data);exit;
					$this->db->insert('order_items', $data);
					$this->Ordermodel->changeOrderStatus($order_id,0);
					$updatedTotalAmt = $this->Ordermodel->updateTotalAmount($order_id);
						$data = [
							'amount' => $updatedTotalAmt[0]['total_rate'],
							'tax_amount' => $updatedTotalAmt[0]['total_tax'],
							'total_amount' => $updatedTotalAmt[0]['total_amount']
								];
							$this->db->where('orderno', $order_id);
							$this->db->update('order', $data);
							
							echo json_encode(['status' => 'success', 'table_id' => $this->Ordermodel->getOrderTableId($order_id)]);
				}
		}


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
    /**
     * Save a new order with details provided from the POST request.
     *
     * This function retrieves order details from the POST request, such as order ID, store ID,
     * product ID, and quantity. It then fetches product details and constructs an associative array
     * representing the order item. The order item is inserted into the 'order_items' table.
     *
     * If an order already exists with the given order ID, it updates the total amount, tax amount,
     * and amount fields in the 'order' table. Otherwise, it calculates the total amounts from the
     * order items and inserts a new entry into the 'order' table with additional order information
     * such as customer name, contact number, and location if available.
     */


	
	public function returnOrderItem(){
		
		$is_return = 0;
		$is_replace = 0;
		$return_quantity = (int) $this->input->post('return_quantity') ?: 0;
		$replace_quantity = (int) $this->input->post('replace_quantity') ?: 0;
		$order_item_id = (int) $this->input->post('return_order_item_id'); //Return order item id (Primary key order items table)
		$return_item_variant_id=$this->input->post('return_item_variant_id');//Variant id !=0 is customizable otherwise normal product(If variant id not exist 0 else variant id )
		$return_reason = $this->input->post('return_reason');
		if ($return_quantity > 0) {
			$is_return = 1;
		}
		
		if ($replace_quantity > 0) {
			$is_replace = 1;
		}

		if($return_reason == 'other'){
			$return_reason = $this->input->post('return_order_custom_reason');
		}else{
			$return_reason = $return_reason;
		}
		

		$data = [
			'is_return' => $is_return,
			'is_replace' => $is_replace,
			'return_qty' => $return_quantity,
			'replace_qty' => $replace_quantity,
			'return_reason' => $return_reason
		]; 

		$this->Ordermodel->updateOrderItemReturn($order_item_id, $data);
		$productId = $this->input->post('return_order_item_product_id');
        $date = date('Y-m-d');
        $store_id=$this->session->userdata('logged_in_store_id');
		$return_order_id=$this->input->post('return_order_id');
		if($is_replace == 1){
			$this->Ordermodel->InsertReplaceOrderToStock($replace_quantity, $store_id, $productId, $date ,$return_order_id);
		}
		if($is_return == 1){
			//If variant id != 0 get variant price otherwise getnormal product price
			$return_amount = $return_quantity * $this->Ordermodel->getItemRate($store_id,$productId , $return_item_variant_id);
			$this->Ordermodel->updateReturnAmount($return_amount, $order_item_id,$store_id); 
		}
		echo "Item returned";
		         
	}
	

    public function getOrderByDate() {
        $this->load->model('owner/Ordermodel');
		$UnPaidorders = $this->Ordermodel->getUnPaidOrderByDate($this->input->post('date') , $this->input->post('tableId'));
        $orders = $this->Ordermodel->getPaidOrderByDate($this->input->post('date') , $this->input->post('tableId'));
        if (empty($orders) && empty($UnPaidorders)) {
			echo "<div class='alert alert-danger' role='alert'>No orders found for the selected date.</div>";
			return;
		}

		$accordionHtml = '';

	
		
		// Build accordion HTML
		$accordionHtml .= '<div class="accordion"><h5 class="text-center">Completed Orders</h5><hr>';

		foreach ($orders as $index => $order) {
			$isFirst = $index === 0 ? ' ' : ''; // Keep the first accordion open
			$accordionHtml .= '
				<div class="accordion-item">
					<h2 class="accordion-header" id="heading' . $order['id'] . '">
						<button class="accordion-button' . ($index !== 0 ? ' collapsed' : '') . '" type="button" data-bs-toggle="collapse" data-bs-target="#collapse' . $order['id'] . '" aria-expanded="' . ($index === 0 ? 'true' : 'false') . '" aria-controls="collapse' . $order['id'] . '">
							' . $index + 1 . ' :- Order No: ' . $order['orderno'] . ' - Amount: ' . $order['total_amount'] - $order['tax_amount'] .  ' - Vat: ' . $order['tax_amount'] . ' - Total: ' . $order['total_amount'] . '
						</button>
					</h2>
					<div id="collapse' . $order['id'] . '" class="accordion-collapse collapse' . $isFirst . '" aria-labelledby="heading' . $order['id'] . '" data-bs-parent="#ordersAccordion">
						
					<div class="accordion-body">
        <table class="table">
            <thead>
                <tr>
                    <th width="5%">Sl</th>
                    <th width="25%">Product</th>
					<th width="10%">Quantity</th>
					<th width="10%">Rate</th>
					<th width="10%">Amount</th>
					<th width="5%">Vat(%)</th>
					<th width="10%">Vat-Amt</th>
					<th width="10%">Total-Amt</th>
					<th width="10%">Is Addon</th>
					<th width="20%">Recipe Details</th>
                </tr>
            </thead>
            <tbody>';
foreach ($order['items'] as $key => $item) {
    $accordionHtml .= '
                <tr>
                    <td>' . $key + 1 . '</td>
                    <td>' . $this->Ordermodel->getProductName($item['product_id']) . '</td>
					<td>' . $item['quantity'] . '</td>
					<td>' . $item['rate'] . '</td>
					<td>' . $item['rate'] * $item['quantity'] . '</td>
					<td>' . $item['tax'] . '</td>
					<td>' . $item['tax_amount'] . '</td>
					<td>' . $item['total_amount'] . '</td>
					<td><input type="checkbox" class="form-check-input" disabled name="orders[' . $index . '][is_addon]" value="1" ' . ($item['is_addon'] == 1 ? 'checked' : '') . '></td>
                    <td>' . $item['item_remarks'] . '</td>
                </tr>';
}
$accordionHtml .= '
            </tbody>
        </table>
    </div>
</div>

					</div>
				</div>';
		}
		$accordionHtml .= '</div>';
	
		echo $accordionHtml;
    }






	public function getPickupOrderDetails() {
        $this->load->model('owner/Ordermodel');
        $pickuporder = $this->Ordermodel->getPickupOrderDetails($this->input->post('orderId'));//prinr_r($pickuporder);exit;
        if (empty($pickuporder)) {
			echo "<p>No orders found for the selected datee.</p>";
			return;
		}

		$accordionHtml = '';

	
		$total_amount = 0;
		
		$accordionHtml = '<form method="post" action="' . base_url('owner/order/update') . '">
		<input type="hidden" name="store_id" value="' . $this->session->userdata('logged_in_store_id') . '">
		<input type="hidden" name="order_id" value="' . $this->input->post('orderId') . '">
		<div class="table-responsive">  
		<table class="table">
            <thead>
                <tr>
                    <th width="5%">Sl</th>
                    <th width="25%">Product</th>
					<th width="10%">Quantity</th>
					<th width="10%">Rate</th>
					<th width="10%">Amount</th>
					<th width="5%">Vat(%)</th>
					<th width="10%">Vat-Amt</th>
					<th width="10%">Total-Amt</th>
					<th width="10%">Is Addon</th>
					<th width="20%">Recipe Details</th>
                </tr>
            </thead>
            <tbody>';
		foreach ($pickuporder as $index => $order) {
			$accordionHtml .= '
                <tr>
                    <td>' . $index + 1 . '</td>
                    <td>' . $this->Ordermodel->getProductName($order['product_id']) . '</td>
					<td>
					<input type="hidden" readonly class="form-control" style="width: 100%;" name="orders[' . $index . '][tax]" value="' . $order['tax'] . '">
					<input type="hidden" readonly class="form-control" style="width: 100%;" name="orders[' . $index . '][id]" value="' . $order['id'] . '">
	<input type="hidden" readonly class="form-control" style="width: 100%;" name="orders[' . $index . '][product_id]" value="' . $order['product_id'] . '">
					<input type="text" class="quantity form-control" name="orders[' . $index . '][quantity]" style="width: 100%;" value="' . $order['quantity'] . '" />
					</td>
					<td><input type="text" readonly class="form-control" style="width: 100%;" name="orders[' . $index . '][rate]" value="' . $order['rate'] . '"></td>
					<td>' . $order['rate'] * $order['quantity'] . '</td>
					<td>' . $order['tax'] . '</td>
					<td>' . $order['tax_amount'] . '</td>
					<td>' . $order['total_amount'] . '</td>
					<td><input type="checkbox" class="form-check-input" disabled name="orders[' . $index . '][is_addon]" value="1" ' . ($order['is_addon'] == 1 ? 'checked' : '') . '></td>
                    <td>' . $order['item_remarks'] . '</td>
                </tr>';
				$item_total = $order['quantity'] * $order['total_amount'];
        		$total_amount += $item_total;
		}
		$accordionHtml .= '</tbody>
		<tfoot class="table-light">
                <tr>
				<td colspan="3">
                        <div class="d-flex justify-content-left">
                            <label class="btn text-black bg-b-cyan" width="100px" style="margin-right: 10px;">Order No : '.$order['orderno'].'</label>
                        </div>
                    </td>
                    <td colspan="3">
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-secondary" name="approve" width="100px" style="margin-right: 10px;">Approve</button>
                            <button class="btn btn-info" name="pay" width="100px" style="margin-right: 10px;">Paid</button>
							<button class="btn btn-info" name="pay" width="100px" style="margin-right: 10px;">Delete</button>
							
                        </div>
                    </td>
					<td colspan="6">
                        <div class="d-flex justify-content-end">
							<button class="btn btn-danger" style="margin-right: 10px;">Total Amount : ' . $total_amount . '</button>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table></form>
		</div>';
	
		echo $accordionHtml;
    }






	public function getOrdersByType() {
		$this->load->model('owner/Ordermodel');
		$orders=$this->Ordermodel->getOrdersByType($this->session->userdata('logged_in_store_id') , $this->input->post('order_type')); //print_r($orders);exit;
		$deliveryBoys=$this->Ordermodel->getDeliveryBoysByStoreID($this->session->userdata('logged_in_store_id')); 
		$kot_enable = $this->Ordermodel->getKotEnabledStatus($this->session->userdata('logged_in_store_id'));
		
		$accordionHtml = '';
		
		if (!empty($orders)) {
			// Build accordion HTML
			$accordionHtml .= '';
	
			foreach ($orders as $index => $order) {
				$isFirst = $index === 0 ? ' ' : ''; // Keep the first accordion open
				$selectedDeliveryBoy = $order['delivery_boy'];
				$accordionHtml .= '<form>
				<input type="hidden" name="product_name" value="'.$order['orderno'].'">
				<input type="hidden" id="order_type" name="order_type" value="'.$this->input->post('order_type').'">
					<div class="accordion-item">
						<h2 class="accordion-header" id="heading' . $order['id'] . '">
							<button style="background:#eeeef9" class="accordion-button' . ($index !== 0 ? ' collapsed' : '') . '" type="button" data-bs-toggle="collapse" data-bs-target="#collapse' . $order['id'] . '" aria-expanded="' . ($index === 0 ? 'true' : 'false') . '" aria-controls="collapse' . $order['id'] . '">
								' . $index + 1 . ' :- Order No: ' . $order['orderno'] . ' - Amount: ' . $order['total_amount'] - $order['tax_amount'] .  ' - Vat: ' . $order['tax_amount'] . ' - Total: ' . round($order['total_amount'], 2) . ' - ' . $order['customer_name'] .'('.$order['contact_number'].')
							</button>
						</h2>
						<div id="collapse' . $order['orderno'] . '" class="accordion-collapse collapse show' . $isFirst . '" aria-labelledby="heading' . $order['id'] . '" data-bs-parent="#ordersAccordion">
							
						<div class="accordion-body product-item">
			<table class="table">
				<thead>
					<tr>
						<th>Sl</th>
<th>Product</th>
<th>Quantity</th>
<th>Rate</th>
<th>Amount</th>
<th>Total-Amt</th>
<th>Is Addon</th>
<th>Recipe Details</th>
					</tr>
				</thead>
				<tbody>';
				foreach ($order['items'] as $key => $item) {
		
		
					$total_amount = 0;
					$backgroundColor = '#ffffff'; // Default color
					$deleted_entry_button_disable = '';
					if ($item['is_delete'] == 1) 
					{
						$backgroundColor = '#f8d7da'; // Red color Deleted item color
						$deleted_entry_button_disable = 'disabled'; // If deleted entry buttons should be disable
					} 
					elseif ($item['is_reorder'] == 1) 
					{
						$backgroundColor = '#86d7cf'; // Green color Reordered item color
					}
			
					$display_none = $order['order_status'] > 2 ? 'd-none' : ''; //If order status till ready can delete after ready cannot delete
					$disabled = $order['order_status'] > 2 ? 'disabled' : ''; //If order status till ready can change qty after ready cannot change
					$check_approve_order_exist = $this->Ordermodel->check_approve_order_exist($order['orderno']);
					$display_none_order_delete = $check_approve_order_exist == 1 ? 'd-none' : '';
					$productName = $this->Ordermodel->getProductName($item['product_id']);
					   $variantName = $this->Ordermodel->getVariantName($item['variant_id']);
					$variant_id = isset($item['variant_id']) ? $item['variant_id'] : 0;
					$accordionHtml .= '
							<tr id="order-row-' . $item['id'] . '" style="background-color: ' . $backgroundColor . ';">
								<td>' . $key + 1 . '</td>
								<td style="width:200px;">' . 
						$productName . 
						($variantName != null ? ' (' . $variantName . ')' : '') . 
					'</td>
								<td style="width:120px;">
								<input type="hidden" class="form-control tax" style="width: 100%;" value="' . $item['tax'] . '">
								<input type="hidden" class="form-control id" style="width: 100%;" value="' . $item['id'] . '">
								<input type="hidden" class="form-control store_product_id" style="width: 100%;" value="' . $item['product_id'] . '">
								<div class="input-group">
									<button class="btn btn-danger decrement" data-tax="' . $item['tax'] . '" data-orderstatus="'.$order['order_status'].'" data-orderno="'.$order['orderno'].'" data-rate="' . $item['rate'] . '" data-id="' . $item['id'] . '" data-product-id="' . $item['product_id'] . '" type="button" ' . $disabled . '>-</button>
									<input type="text" class="form-control text-center quantity" name="quantity" value="'.$item['quantity'].'" min="1" readonly>
									<button class="btn btn-danger increment" data-tax="' . $item['tax'] . '" data-orderstatus="'.$order['order_status'].'" data-orderno="'.$order['orderno'].'" data-rate="' . $item['rate'] . '" data-id="' . $item['id'] . '" data-product-id="' . $item['product_id'] . '" type="button" ' . $disabled . '>+</button>
								</div>
			
								</td>
								<td style="width:80px;"><input type="text" class="form-control rate" style="width: 100%;" value="' . $item['rate'] . '"></td>
								<td class="amount">' . $item['rate'] * $item['quantity'] . '</td>
								<td class="total-amount">' . $item['total_amount'] . '</td>
								<td><input type="checkbox" class="form-check-input" disabled name="is_addon" value="1" ' . ($item['is_addon'] == 1 ? 'checked' : '') . '></td>
								<td style="width:150px;">' . $item['item_remarks'] . '</td>
								<td class="d-flex gap-2">
								<button type="button" '.$deleted_entry_button_disable.' class="btn btn-danger delete-order ' . $display_none . '" data-id="' . $item['id'] . '" data-status="' . $order['order_status'] . '" data-quantity="' . $item['quantity'] . '">Delete</button>
								<button type="button" class="btn btn-secondary return-order" '.$deleted_entry_button_disable.' data-variant-id='.$variant_id.' data-order-id='.$order['orderno'].' data-item-id="' . $item['product_id'] . '" data-qty="' . $item['quantity'] . '" data-order-item-id="' . $item['id'] . '" data-item="' . 
						$productName . 
						($variantName != null ? ' (' . $variantName . ')' : '') . '">Return</button>
								</td>
							</tr>';
							$item_total = $item['quantity'] * $item['total_amount'];
							$total_amount += $item_total;
				}
				$approveOrderClass = ($order['order_status'] == 0) ? 'btn8-small approve_order' : 'btn6-small approve_order ';
				$payOrderClass = ($order['order_status'] == 4) ? 'btn8-small pay_order' : 'btn6-small pay_order ';
				

	$accordionHtml .= '</tbody>
		<tfoot class="table-light">
				
                <tr>
					<td colspan="1">';

					
						$accordionHtml .= '<button class="btn btn-success dropdown-toggle" type="button" id="orderStatusDropdown" data-bs-toggle="dropdown" aria-expanded="false">';
						$accordionHtml .= (($order['order_status'] == "0") ? "Pending" : (($order['order_status'] == "1") ? "Approved" : (($order['order_status'] == "2") ? "Cooking" : (($order['order_status'] == "3") ? "Ready" :(($order['order_status'] == "4") ? "Out For Delivery" : "Select Status")))));
						$accordionHtml .= '</button>';
					
					
					if ($this->input->post('order_type') == 'DL') {
						
						if ($order['order_status'] == 3) {
							$accordionHtml .= '<td colspan="2"><select class="form-select delivery_boy" data-order-id="' . $order['orderno'] . '" id="delivery_boy">';
							$accordionHtml .= '<option value="">Select Delivery Boy</option>';
							
							foreach ($deliveryBoys as $item) {
								$selected = ($item['userid'] == $selectedDeliveryBoy) ? 'selected' : '';
								$accordionHtml .= '<option value="' . $item['userid'] . '" ' . $selected . '>' . $item['Name'] . '</option>';
							}
							
							$accordionHtml .= '</select></td>';
						}
					}
					
					
					$accordionHtml .= '<td colspan="3">
                        <div class="d-flex justify-content-center">
							<!--<button data-bs-toggle="modal" data-id="2" data-name="fgdfg" data-bs-target="#recipe" class="btn btn-secondary add_order_item" name="approve" width="100px" style="margin-right: 10px;">Add</button>-->
                            <button type="button" class="'.$approveOrderClass.'" data-order-id="' . $order['orderno'] . '" data-kot-enable="'.$kot_enable.'">Approve</button>
                            
							<button class="'.$payOrderClass.'" data-order-id="' . $order['orderno'] . '" width="100px" style="margin-left: 10px;">Pay</button>
							<button type="button" data-bs-toggle="modal" data-id="2" data-name="fgdfg" data-bs-target="#printModal" data-order-id="' . $order['orderno'] . '" class="btn6-small pay_order_print" width="100px" style="margin-left: 10px;">Print</button>
							<a class="btn6-small ' . $display_none_order_delete . ' delete-full-order" data-order-id="' . $order['orderno'] . '" width="100px" style="margin-left: 10px;">Delete</a>
                        </div>
                    </td>
					<td colspan="5">
                        <div class="d-flex justify-content-end">
							<button class="btn6-small" style="margin-right: 10px;">Total : ' . round($order['total_amount'], 2) . '</button>
                        </div>
                    </td>
                </tr>
				<tr class="msgContainer'.$order['orderno'].' d-none"><td colspan="12">
				<div class="alert alert-success dark d-none" role="alert" id="ordermsg'.$order['orderno'].'">Order</div>
				</td></tr>
            </tfoot>
        </table></form>
		</div>';
	$accordionHtml .= '
				</tbody>
			</table>
		</div>
	</div>
	
						</div>

						
                    
                     <div class="modal fade" id="recipe" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h1 class="modal-title fs-5" id="exampleModalLabel"> <span id="table_name"></span></h1>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body tt">
                                    <iframe id="table_iframe_recipe1" height="600px" width="100%"></iframe>
                                </div>
                              </div>
                            </div>
                    </div>
                  
						
					</div>';
			}
			$accordionHtml .= '</div>';



			echo $accordionHtml;
		}
		else
		{
			echo "<div class='alert alert-danger' role='alert'>No orders!</div>";
		}
	}


	

	public function getPendingOrdersByTableID() {
		$this->load->model('owner/Ordermodel');
		$orders=$this->Ordermodel->getPendingOrdersByTableId($this->session->userdata('logged_in_store_id') , $this->input->post('table_id')); 
		$kot_enable = $this->Ordermodel->getKotEnabledStatus($this->session->userdata('logged_in_store_id'));
		$accordionHtml = '';
			
			// Build accordion HTML
		if(!empty($orders)){
		
			$accordionHtml .= '';
	
			foreach ($orders as $index => $order) {
				$isFirst = $index === 0 ? ' ' : ''; // Keep the first accordion open
				$accordionHtml .= '<form>
				<input type="hidden" name="product_name" value="'.$order['orderno'].'">
					<div class="accordion-item">
						<h2 class="accordion-header" id="heading' . $order['id'] . '">
							<button style="background:#eeeef9" class="accordion-button' . ($index !== 0 ? ' collapsed' : '') . '" type="button" data-bs-toggle="collapse" data-bs-target="#collapse' . $order['id'] . '" aria-expanded="' . ($index === 0 ? 'true' : 'false') . '" aria-controls="collapse' . $order['id'] . '">
								' . $index + 1 . ' :- Order No: ' . $order['orderno'] . ' - Amount: <span id="order-amount-'.$order['orderno'].'">' . $order['total_amount'] - $order['tax_amount'] .  '</span> - Vat: <span class="tax">' . $order['tax'] . '</span> - Total: <span id="order-amount-include-tax-'.$order['orderno'].'">' . round($order['total_amount'], 2) . '
							</span></button>
						</h2>
					
						<div id="collapse' . $order['orderno'] . '" class="accordion-collapse collapse show' . $isFirst . '" aria-labelledby="heading' . $order['id'] . '" data-bs-parent="#ordersAccordion">
							
						<div class="accordion-body product-item">
			<table class="table order_details_table">
				<thead>
					<tr>
						<th>Sl</th>
<th>Product</th>
<th>Quantity</th>
<th>Rate</th>
<th>Amount</th>
<th>Total-Amt</th>
<th>Is Addon</th>
<th>Recipe Details</th>
					</tr>
				</thead>
				<tbody>';
	foreach ($order['items'] as $key => $item) {
		
		
		$total_amount = 0;
		$backgroundColor = '#ffffff'; // Default color
		$deleted_entry_button_disable = '';
		if ($item['is_delete'] == 1) 
		{
			$backgroundColor = '#f8d7da'; // Red color Deleted item color
			$deleted_entry_button_disable = 'disabled'; // If deleted entry buttons should be disable
		} 
		elseif ($item['is_reorder'] == 1) 
		{
			$backgroundColor = '#86d7cf'; // Green color Reordered item color
		}
		$display_none = $order['order_status'] > 2 ? 'd-none' : ''; //If order status till ready can delete after ready cannot delete
		$disabled = $order['order_status'] > 2 ? 'disabled' : ''; //If order status till ready can change qty after ready cannot change
		$check_approve_order_exist = $this->Ordermodel->check_approve_order_exist($order['orderno']);
		$display_none_order_delete = $check_approve_order_exist == 1 ? 'd-none' : '';
		$productName = $this->Ordermodel->getProductName($item['product_id']);
   		$variantName = $this->Ordermodel->getVariantName($item['variant_id']);
		$variant_id = isset($item['variant_id']) ? $item['variant_id'] : 0;
		$accordionHtml .= '
				<tr id="order-row-' . $item['id'] . '" style="background-color: ' . $backgroundColor . ';">
                    <td>' . $key + 1 . '</td>
                    <td style="width:200px;">' . 
            $productName . 
            ($variantName != null ? ' (' . $variantName . ')' : '') . 
        '</td>
					<td style="width:120px;">
					<input type="hidden" class="form-control tax" style="width: 100%;" value="' . $item['tax'] . '">
					<input type="hidden" class="form-control id" style="width: 100%;" value="' . $item['id'] . '">
					<input type="hidden" class="form-control store_product_id" style="width: 100%;" value="' . $item['product_id'] . '">
					<div class="input-group">
						<button class="btn btn-danger decrement" data-tax="' . $item['tax'] . '" data-orderstatus="'.$order['order_status'].'" data-orderno="'.$order['orderno'].'" data-rate="' . $item['rate'] . '" data-id="' . $item['id'] . '" data-product-id="' . $item['product_id'] . '" type="button" ' . $disabled . '>-</button>
						<input type="text" class="form-control text-center quantity" name="quantity" value="'.$item['quantity'].'" min="1" readonly>
						<button class="btn btn-danger increment" data-tax="' . $item['tax'] . '" data-orderstatus="'.$order['order_status'].'" data-orderno="'.$order['orderno'].'" data-rate="' . $item['rate'] . '" data-id="' . $item['id'] . '" data-product-id="' . $item['product_id'] . '" type="button" ' . $disabled . '>+</button>
					</div>

					</td>
					<td style="width:80px;"><input type="text" class="form-control rate" style="width: 100%;" value="' . $item['rate'] . '"></td>
					<td class="amount">' . $item['rate'] * $item['quantity'] . '</td>
					<td class="total-amount">' . $item['total_amount'] . '</td>
					<td><input type="checkbox" class="form-check-input" disabled name="is_addon" value="1" ' . ($item['is_addon'] == 1 ? 'checked' : '') . '></td>
                    <td style="width:150px;">' . $item['item_remarks'] . '</td>
					<td class="d-flex gap-2">
					<button type="button" '.$deleted_entry_button_disable.' class="btn btn-danger delete-order ' . $display_none . '" data-id="' . $item['id'] . '" data-status="' . $order['order_status'] . '" data-quantity="' . $item['quantity'] . '">Delete</button>
					<button type="button" class="btn btn-secondary return-order" '.$deleted_entry_button_disable.' data-variant-id='.$variant_id.' data-order-id='.$order['orderno'].' data-item-id="' . $item['product_id'] . '" data-qty="' . $item['quantity'] . '" data-order-item-id="' . $item['id'] . '" data-item="' . 
            $productName . 
            ($variantName != null ? ' (' . $variantName . ')' : '') . '">Return</button>
					</td>
                </tr>';
				$item_total = $item['quantity'] * $item['total_amount'];
        		$total_amount += $item_total;
	}

	$approveOrderClass = ($order['order_status'] == 0) ? 'btn8-small approve_table_order' : 'btn6-small approve_table_order ';
    $payOrderClass = ($order['order_status'] == 4) ? 'btn8-small pay_table_order' : 'btn6-small pay_table_order ';
	$diningOrderClass = ($order['order_status'] == 3) ? 'btn8-small dining_order' : 'btn6-small dining_order ';

	$accordionHtml .= '</tbody>
		<tfoot class="table-light order-details_buttons">
				
                <tr>
					<td colspan="2">
                        <button class="btn btn-success dropdown-toggle" type="button" id="orderStatusDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            ' . (($order['order_status'] == "0") ? "Pending" : (($order['order_status'] == "1") ? "Approved" : (($order['order_status'] == "2") ? "Cooking" : (($order['order_status'] == "3") ? "Ready" :(($order['order_status'] == "4") ? "Dining" : "Select Status"))))) . '
        </button>
                    </td>
                    <td colspan="5">
                        <div class="d-flex justify-content-center">
							<button data-bs-toggle="modal" data-id="2" data-name="fgdfg" data-bs-target="#recipe1" data-order-id="' . $order['orderno'] . '" class="btn6-small add_order_item br-15" name="approve" width="100px" style="margin-right: 10px;">Add</button>
                            <button type="button" class="'.$approveOrderClass.'" data-order-id="' . $order['orderno'] . '" data-kot-enable="'.$kot_enable.'">Approve</button>
							<button type="button" data-order-id="' . $order['orderno'] . '" class="'.$diningOrderClass.'" width="100px" style="margin-left: 10px;">Dining</button>
                            <button class="'.$payOrderClass.'" data-order-id="' . $order['orderno'] . '" width="100px" style="margin-left: 10px;">Pay</button>
							<button type="button" data-bs-toggle="modal" data-id="2" data-name="fgdfg" data-bs-target="#printModal" data-order-id="' . $order['orderno'] . '" class="btn6-small pay_order_print" width="100px" style="margin-left: 10px;">Print</button>
							<a class="btn6-small ' . $display_none_order_delete . ' delete-full-order" data-order-id="' . $order['orderno'] . '" width="100px" style="margin-left: 10px;">Delete</a>
                        </div>
                    </td>
					<td colspan="5">
                        <div class="d-flex justify-content-end">
							<button class="btn6-small" id="order-amount-include-tax-footer-'.$order['orderno'].'" style="margin-right: 10px;">Total : ' . round($order['total_amount'], 2) . '</button>
                        </div>
                    </td>
                </tr>
				<tr class="msgContainer'.$order['orderno'].' d-none"><td colspan="12">
				<div class="alert alert-success dark d-none" role="alert" id="ordermsg'.$order['orderno'].'">Order</div>
				</td></tr>
            </tfoot>
        </table></form>
		</div>';
	$accordionHtml .= '
				</tbody>
			</table>
		</div>
	</div>
	
						</div>

						
                    
                     <div class="modal fade" id="recipe" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h1 class="modal-title fs-5" id="exampleModalLabel"> <span id="table_name"></span></h1>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body tt">
                                    <iframe id="table_iframe_recipe1" height="600px" width="100%"></iframe>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                              </div>
                            </div>
                    </div>
                    
                     <div class="modal fade" id="recipe1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h1 class="modal-title fs-5" id="exampleModalLabel"> <span id="table_name"></span></h1>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body tt">
                                    <iframe id="table_iframe_recipe2" height="600px" width="100%"></iframe>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                              </div>
                            </div>
                    </div>
                  
						
					</div>';
			}
			$accordionHtml .= '</div>';
			echo $accordionHtml;
		}
		else
		{
			echo "<div class='alert alert-danger' role='alert'>No orders!</div>";
		}
	}


		public function getCompletedOrdersByType() {
			$this->load->model('owner/Ordermodel');
			$orders = $this->Ordermodel->getCompletedOrdersByType($this->input->post('date') , $this->input->post('order_type'));
			//print_r($orders);exit;
			if (empty($orders)) {
				echo "<div class='alert alert-danger' role='alert'>No orders!</div>";
				return;
			}
	
			$accordionHtml = '';
			
			// Build accordion HTML
			$accordionHtml .= '<div class="accordion"><h5 class="text-center">Completed Orders</h5><hr>';
	
			foreach ($orders as $index => $order) {
				$isFirst = $index === 0 ? ' ' : ''; // Keep the first accordion open
				$accordionHtml .= '
					<div class="accordion-item">
						<h2 class="accordion-header" id="heading' . $order['id'] . '">
							<button class="accordion-button' . ($index !== 0 ? ' collapsed' : '') . '" type="button" data-bs-toggle="collapse" data-bs-target="#collapse' . $order['id'] . '" aria-expanded="' . ($index === 0 ? 'true' : 'false') . '" aria-controls="collapse' . $order['id'] . '">
								' . $index + 1 . ' :- Order No: ' . $order['orderno'] . ' - Amount: ' . $order['total_amount'] - $order['tax_amount'] .  ' - Vat: ' . $order['tax_amount'] . ' - Total: ' . $order['total_amount'] . ' - ' . $order['customer_name'] .'('.$order['contact_number'].')
							</button>
						</h2>
						<div id="collapse' . $order['id'] . '" class="accordion-collapse collapse' . $isFirst . '" aria-labelledby="heading' . $order['id'] . '" data-bs-parent="#ordersAccordion">
							
						<div class="accordion-body">
			<table class="table">
				<thead>
					<tr>
						<th width="5%">Sl</th>
						<th width="25%">Product</th>
						<th width="10%">Quantity</th>
						<th width="10%">Rate</th>
						<th width="10%">Amount</th>
						<th width="5%">Vat(%)</th>
						<th width="10%">Vat-Amt</th>
						<th width="10%">Total-Amt</th>
						<th width="10%">Is Addon</th>
						<th width="20%">Recipe Details</th>
					</tr>
				</thead>
				<tbody>';
	foreach ($order['items'] as $key => $item) {
		$accordionHtml .= '
					<tr>
						<td>' . $key + 1 . '</td>
						<td>' . $this->Ordermodel->getProductName($item['product_id']) . '</td>
						<td>' . $item['quantity'] . '</td>
						<td>' . $item['rate'] . '</td>
						<td>' . $item['rate'] * $item['quantity'] . '</td>
						<td>' . $item['tax'] . '</td>
						<td>' . $item['tax_amount'] . '</td>
						<td>' . $item['total_amount'] . '</td>
						<td><input type="checkbox" class="form-check-input" disabled name="orders[' . $index . '][is_addon]" value="1" ' . ($item['is_addon'] == 1 ? 'checked' : '') . '></td>
						<td>' . $item['item_remarks'] . '</td>
					</tr>';
	}
	$accordionHtml .= '
				</tbody>
			</table>
		</div>
	</div>
	
						</div>
					</div>';
			}
			$accordionHtml .= '</div>';
		
			echo $accordionHtml;
		}






		public function getProductRatesWithIsCustomizeNewDiningOrder(){
			$is_customise = $this->Ordermodel->checkCustomizable($this->input->post('product_id'));
		
			$html = '';
			if($is_customise == 1){
				$variantsList = $this->Ordermodel->getVariants($this->input->post('product_id'),$this->session->userdata('logged_in_store_id'));
				$html .= '
				<div class="col">
				<input type="hidden" id="store_id" value="'.$this->session->userdata('logged_in_store_id').'">
				<input type="hidden" id="tableId" value="'.$this->input->post('table_id').'">
				<input type="hidden" id="orderType" value="'.$this->input->post('orderType').'">
				<input type="hidden" id="product_id" value="'.$this->input->post('product_id').'">
				<input type="hidden" id="ratenew">
				<input type="hidden" id="taxnew">
				<input type="hidden" id="taxamtnew">
				<input type="hidden" id="totalnew">
				<input type="hidden" id="qty">
				<input type="hidden" id="variantnew_id">
				
            <label for="productId" class="form-label">Variant</label>
            <select class="form-select" name="variant_id" id="variant_id">'; // Default placeholder option
				foreach ($variantsList as $variant1) {
    				$html .= '<option value="' . htmlspecialchars($variant1['variant_id']) . '">' . htmlspecialchars($variant1['variant_name']) . '</option>';
				}
				$html .= '</select>
        	</div>
			<div class="row">
			<div class="col-3">
            	<label for="productQuantity" class="form-label">Quantity</label>
            	<input type="text" class="form-control" name="product_quantity" id="productQuantity" placeholder="Enter Quantity" autofocus>
        	</div>
			<div class="col-3">
            	<label for="productQuantity" class="form-label">Rate</label>
            	<input type="text" disabled class="form-control" id="rate">
        	</div>
			<div class="col-3">
            	<label for="productQuantity" class="form-label">Tax Amount</label>
            	<input type="text" disabled class="form-control" id="tax_amount" placeholder="">
        	</div>
			<div class="col-2">
            	<label for="productQuantity" class="form-label">Total Amount</label>
            	<input type="text" disabled class="form-control" id="total_amount" placeholder="Enter Quantity">
        	</div>
			<div class="col-1">
            <button class="btn btn-primary mt-4" type="button" id="saveOrder">ADD</button>
            </div>
			</div>
			<hr></hr>
			';
			}
			if($is_customise == 0){
				$html = ' 
				<input type="hidden" id="store_id" value="'.$this->session->userdata('logged_in_store_id').'">
				<input type="hidden" id="tableId" value="'.$this->input->post('table_id').'">
				<input type="hidden" id="orderType" value="'.$this->input->post('orderType').'">
				<input type="hidden" id="product_id" value="'.$this->input->post('product_id').'">
				<input type="hidden" id="ratenew">
				<input type="hidden" id="taxnew">
				<input type="hidden" id="taxamtnew">
				<input type="hidden" id="totalnew">
				<input type="hidden" id="qty">
			<div class="row">
			<div class="col-3">
            	<label for="productQuantity" class="form-label">Quantity</label>
            	<input type="text" class="form-control" name="product_quantity" id="productQuantityNotCustomize" placeholder="Enter Quantity" autofocus />
        	</div>
			<div class="col-3">
            	<label for="productQuantity" class="form-label">Rate</label>
            	<input type="text" disabled class="form-control" id="rate1">
        	</div>
			<div class="col-3">
            	<label for="productQuantity" class="form-label">Tax Amount</label>
            	<input type="text" disabled class="form-control" id="tax_amount1" placeholder="">
        	</div>
			<div class="col-2">
            	<label for="productQuantity" class="form-label">Amount</label>
            	<input type="text" disabled class="form-control" id="total_amount1" placeholder="Enter Quantity">
        	</div>
			<div class="col-1">
            <button class="btn btn-primary mt-4" type="button" id="saveOrder">ADD</button>
            </div>
			</div>
			<hr></hr>
			';
			}
echo $html;
}






// Pickup New Order
public function getProductRatesWithIsCustomizeNewPickupOrder(){
$is_customise = $this->Ordermodel->checkCustomizable($this->input->post('product_id'));

$html = '';
if($is_customise == 1){
$variantsList =
$this->Ordermodel->getVariants($this->input->post('product_id'),$this->session->userdata('logged_in_store_id'));
$html .= '<div class="col">
    <input type="hidden" id="store_id" value="'.$this->session->userdata('logged_in_store_id').'">
    <input type="hidden" id="name" value="'.$this->input->post('name').'">
    <input type="hidden" id="number" value="'.$this->input->post('number').'">
    <input type="hidden" id="time" value="'.$this->input->post('time').'">
    <input type="hidden" id="tableId" value="'.$this->input->post('table_id').'">
    <input type="hidden" id="orderType" value="'.$this->input->post('orderType').'">
    <input type="hidden" id="product_id" value="'.$this->input->post('product_id').'">
    <input type="hidden" id="ratenew">
    <input type="hidden" id="taxnew">
    <input type="hidden" id="taxamtnew">
    <input type="hidden" id="totalnew">
    <input type="hidden" id="qty">
    <input type="hidden" id="variantnew_id">

    <label for="productId" class="form-label">Variant</label>
    <select class="form-select" name="variant_id" id="variant_id">'; // Default placeholder option
        foreach ($variantsList as $variant1) {
        $html .= '<option value="' . htmlspecialchars($variant1['variant_id']) . '">' .
            htmlspecialchars($variant1['variant_name']) . '</option>';
        }
        $html .= '</select>
</div>
<div class="row">
    <div class="col-3">
        <label for="productQuantity" class="form-label">Quantity</label>
        <input type="text" class="form-control" name="product_quantity" id="productQuantity"
            placeholder="Enter Quantity">
    </div>
    <div class="col-3">
        <label for="productQuantity" class="form-label">Rate</label>
        <input type="text" disabled class="form-control" id="rate">
    </div>
    <div class="col-3">
        <label for="productQuantity" class="form-label">Tax Amount</label>
        <input type="text" disabled class="form-control" id="tax_amount" placeholder="">
    </div>
    <div class="col-2">
        <label for="productQuantity" class="form-label">Total Amount</label>
        <input type="text" disabled class="form-control" id="total_amount" placeholder="Enter Quantity">
    </div>
    <div class="col-1">
        <button class="btn btn-primary mt-4" type="button" id="saveOrder">ADD</button>
    </div>
</div>
<hr>
</hr>
';
}
if($is_customise == 0){
$html = '
<input type="hidden" id="store_id" value="'.$this->session->userdata('logged_in_store_id').'">
<input type="hidden" id="name" value="'.$this->input->post('name').'">
<input type="hidden" id="number" value="'.$this->input->post('number').'">
<input type="hidden" id="time" value="'.$this->input->post('time').'">
<input type="hidden" id="tableId" value="'.$this->input->post('table_id').'">
<input type="hidden" id="orderType" value="'.$this->input->post('orderType').'">
<input type="hidden" id="product_id" value="'.$this->input->post('product_id').'">
<input type="hidden" id="ratenew">
<input type="hidden" id="taxnew">
<input type="hidden" id="taxamtnew">
<input type="hidden" id="totalnew">
<input type="hidden" id="qty">
<div class="row">
    <div class="col-3">
        <label for="productQuantity" class="form-label">Quantity</label>
        <input type="text" class="form-control" name="product_quantity" id="productQuantityNotCustomize"
            placeholder="Enter Quantity">
    </div>
    <div class="col-3">
        <label for="productQuantity" class="form-label">Rate</label>
        <input type="text" disabled class="form-control" id="rate1">
    </div>
    <div class="col-3">
        <label for="productQuantity" class="form-label">Tax Amount</label>
        <input type="text" disabled class="form-control" id="tax_amount1" placeholder="">
    </div>
    <div class="col-2">
        <label for="productQuantity" class="form-label">Amount</label>
        <input type="text" disabled class="form-control" id="total_amount1" placeholder="Enter Quantity">
    </div>
    <div class="col-1">
        <button class="btn btn-primary mt-4" type="button" id="saveOrder">ADD</button>
    </div>
</div>

<hr>
</hr>
';
}
echo $html;
}







// Delivery New Order
public function getProductRatesWithIsCustomizeNewDeliveryOrder(){
$is_customise = $this->Ordermodel->checkCustomizable($this->input->post('product_id'));

$html = '';
if($is_customise == 1){
$variantsList =
$this->Ordermodel->getVariants($this->input->post('product_id'),$this->session->userdata('logged_in_store_id'));
$html .= '<div class="col">
    <input type="hidden" id="store_id" value="'.$this->session->userdata('logged_in_store_id').'">
    <input type="hidden" id="name" value="'.$this->input->post('name').'">
    <input type="hidden" id="number" value="'.$this->input->post('number').'">
    <input type="hidden" id="time" value="'.$this->input->post('time').'">
    <input type="hidden" id="address" value="'.$this->input->post('address').'">
    <input type="hidden" id="paymentMode" value="'.$this->input->post('paymentMode').'">
    <input type="hidden" id="tableId" value="'.$this->input->post('table_id').'">
    <input type="hidden" id="orderType" value="'.$this->input->post('orderType').'">
    <input type="hidden" id="product_id" value="'.$this->input->post('product_id').'">
    <input type="hidden" id="ratenew">
    <input type="hidden" id="taxnew">
    <input type="hidden" id="taxamtnew">
    <input type="hidden" id="totalnew">
    <input type="hidden" id="qty">
    <input type="hidden" id="variantnew_id">

    <label for="productId" class="form-label">Variant</label>
    <select class="form-select" name="variant_id" id="variant_id">'; // Default placeholder option
        foreach ($variantsList as $variant1) {
        $html .= '<option value="' . htmlspecialchars($variant1['variant_id']) . '">' .
            htmlspecialchars($variant1['variant_name']) . '</option>';
        }
        $html .= '</select>
</div>
<div class="row">
    <div class="col-3">
        <label for="productQuantity" class="form-label">Quantity</label>
        <input type="text" class="form-control" name="product_quantity" id="productQuantity"
            placeholder="Enter Quantity">
    </div>
    <div class="col-3">
        <label for="productQuantity" class="form-label">Rate</label>
        <input type="text" disabled class="form-control" id="rate">
    </div>
    <div class="col-3">
        <label for="productQuantity" class="form-label">Tax Amount</label>
        <input type="text" disabled class="form-control" id="tax_amount" placeholder="">
    </div>
    <div class="col-2">
        <label for="productQuantity" class="form-label">Total Amount</label>
        <input type="text" disabled class="form-control" id="total_amount" placeholder="Enter Quantity">
    </div>
    <div class="col-1">
        <button class="btn btn-primary mt-4" type="button" id="saveOrder">ADD</button>
    </div>
</div>
<hr>
</hr>
';
}
if($is_customise == 0){
$html = '
<input type="hidden" id="store_id" value="'.$this->session->userdata('logged_in_store_id').'">
<input type="hidden" id="name" value="'.$this->input->post('name').'">
<input type="hidden" id="number" value="'.$this->input->post('number').'">
<input type="hidden" id="time" value="'.$this->input->post('time').'">
<input type="hidden" id="address" value="'.$this->input->post('address').'">
<input type="hidden" id="paymentMode" value="'.$this->input->post('paymentMode').'">
<input type="hidden" id="tableId" value="'.$this->input->post('table_id').'">
<input type="hidden" id="orderType" value="'.$this->input->post('orderType').'">
<input type="hidden" id="product_id" value="'.$this->input->post('product_id').'">
<input type="hidden" id="ratenew">
<input type="hidden" id="taxnew">
<input type="hidden" id="taxamtnew">
<input type="hidden" id="totalnew">
<input type="hidden" id="qty">
<div class="row">
    <div class="col-3">
        <label for="productQuantity" class="form-label">Quantity</label>
        <input type="text" class="form-control" name="product_quantity" id="productQuantityNotCustomize"
            placeholder="Enter Quantity">
    </div>
    <div class="col-3">
        <label for="productQuantity" class="form-label">Rate</label>
        <input type="text" disabled class="form-control" id="rate1">
    </div>
    <div class="col-3">
        <label for="productQuantity" class="form-label">Tax Amount</label>
        <input type="text" disabled class="form-control" id="tax_amount1" placeholder="">
    </div>
    <div class="col-2">
        <label for="productQuantity" class="form-label">Amount</label>
        <input type="text" disabled class="form-control" id="total_amount1" placeholder="Enter Quantity">
    </div>
    <div class="col-1">
        <button class="btn btn-primary mt-4" type="button" id="saveOrder">ADD</button>
    </div>
</div>

<hr>
</hr>
';
}
echo $html;
}



public function getProductRatesWithIsCustomizeExistingOrder(){
$is_customise = $this->Ordermodel->checkCustomizable($this->input->post('product_id'));
//echo $this->input->post('product_id');echo $this->session->userdata('logged_in_store_id');exit;

$html = '';
if($is_customise == 1){
$variantsList =
$this->Ordermodel->getVariants($this->input->post('product_id'),$this->session->userdata('logged_in_store_id'));
$html .= '<div class="col">
    <input type="hidden" id="store_id" value="'.$this->session->userdata('logged_in_store_id').'">
    <input type="hidden" id="product_id" value="'.$this->input->post('product_id').'">
    <input type="hidden" id="ratenew" value="">
    <input type="hidden" id="taxnew" value="">
    <input type="hidden" id="taxamtnew" value="">
    <input type="hidden" id="totalnew" value="">
    <input type="hidden" id="qty" value="">
    <input type="hidden" id="variantnew_id" value="">

    <label for="productId" class="form-label">Variant</label>
    <select class="form-select" name="variant_id" id="variant_id">'; // Default placeholder option
        foreach ($variantsList as $variant1) {
        $html .= '<option value="' . htmlspecialchars($variant1['variant_id']) . '">' .
            htmlspecialchars($variant1['variant_name']) . '</option>';
        }

        $html .= '</select>
</div>
<div class="col">
    <label for="productQuantity" class="form-label">Quantity</label>
    <input type="text" class="form-control" name="product_quantity" id="productQuantity" placeholder="Enter Quantity">
</div>
<div class="col">
    <label for="productQuantity" class="form-label">Rate</label>
    <input type="text" class="form-control" id="rate">
</div>
<div class="col">
    <label for="productQuantity" class="form-label">Tax Amount</label>
    <input type="text" class="form-control" id="tax_amount" placeholder="">
</div>
<div class="col">
    <label for="productQuantity" class="form-label">Total Amount</label>
    <input type="text" class="form-control" id="total_amount" placeholder="Enter Quantity">
</div>
<div class="row mt-2">
    <button class="btn btn-primary" type="button" id="saveOrder">Save</button>
</div>';
}
if($is_customise == 0){
$html = '
<input type="hidden" id="store_id" value="'.$this->session->userdata('logged_in_store_id').'">
<input type="hidden" id="product_id" value="'.$this->input->post('product_id').'">
<input type="hidden" id="ratenew" value="1">
<input type="hidden" id="taxnew" value="2">
<input type="hidden" id="taxamtnew" value="2">
<input type="hidden" id="totalnew" value="3">
<input type="hidden" id="qty" value="3">
<div class="col">
    <label for="productQuantity" class="form-label">Quantity</label>
    <input type="text" class="form-control" name="product_quantity" id="productQuantityNotCustomize"
        placeholder="Enter Quantity">
</div>
<div class="col">
    <label for="productQuantity" class="form-label">Rate</label>
    <input type="text" class="form-control" id="rate1">
</div>
<div class="col">
    <label for="productQuantity" class="form-label">Tax Amount</label>
    <input type="text" class="form-control" id="tax_amount1" placeholder="">
</div>
<div class="col">
    <label for="productQuantity" class="form-label">Total Amount</label>
    <input type="text" class="form-control" id="total_amount1" placeholder="Enter Quantity">
</div>
<div class="row mt-2">
    <button class="btn btn-primary" type="button" id="saveOrder">Save</button>
</div>';
}
echo $html;
}







}
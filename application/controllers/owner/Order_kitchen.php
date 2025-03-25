<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_kitchen extends CI_Controller {

	/**
	 * Index Page for this controller.
	 * Get pending orders - OrdersPendingPKDL()
	 * Get pending orders by table id - getPendingOrdersByTableID()
	 * Get orders by type (Pickup or delivery) - getOrdersByType()
	 * Get pickup order details - getPickupOrderDetails()
	 * Delete order item when decrement value == 0 - deleteOrderItemStockRemove()
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
	public function OrdersPendingPKDL($table_id){
		$data['table_id'] = $table_id;  //In this case type return table id
		$this->load->view('owner/order/kitchen_approved_table_orders',$data);
	}

	public function ReadyOrderDetails($table_id){ //ready order details show within poup
		$data['table_id'] = $table_id;  //In this case type return table id
		$this->load->view('owner/order/ready_order_details',$data);
	}

	public function DeliveredOrderDetails($table_id){ //ready order details show within poup
		$data['table_id'] = $table_id;  //In this case type return table id
		$this->load->view('owner/order/delivered_order_details',$data);
	}

	
	public function get_Kitchen_Orders_Count_Status() {
        $user_id = $this->session->userdata('loginid');
        $dining_count = $this->Ordermodel->get_Approved_Orders_Count('D');
        $pickup_count = $this->Ordermodel->get_Approved_Orders_Count('PK');
        $delivery_count = $this->Ordermodel->get_Approved_Orders_Count('DL');
	    $ready_order_count = $this->Ordermodel->get_Ready_Orders_Count();
        $pending_order_table_ids = $this->Ordermodel->get_approved_order_table_ids();
        
        $data = array(
            'dining'   => $dining_count,
            'pickup'   => $pickup_count,
            'delivery' => $delivery_count,
			'ready_order' => $ready_order_count,
            'table_ids' => $pending_order_table_ids
        );
        
        echo json_encode($data);
    }
	public function accept_order(){
		$this->load->model('owner/Ordermodel');
		$order_id = $this->input->post('orderId');
		$store_id = $this->session->userdata('logged_in_store_id');
		$this->Ordermodel->accept_order($store_id,$order_id);
		echo json_encode(['status' => 'success']);	
	}
	public function ready_order(){
		$this->load->model('owner/Ordermodel');
		$order_id = $this->input->post('orderId');
		$store_id = $this->session->userdata('logged_in_store_id');
		$this->Ordermodel->ready_order($store_id,$order_id);
		echo json_encode(['status' => 'success']);	
	}
	public function ordersPKDL($type){
		$data['type'] = $type;
		$this->load->view('owner/order/pending_orders_by_type',$data); // Display pending orders by type delivery and pickup
	}


	public function getApprovedOrdersByTableID() {
		$this->load->model('owner/Ordermodel');
		$orders=$this->Ordermodel->getApprovedOrdersByTableId($this->session->userdata('logged_in_store_id') , $this->input->post('order_id')); 
		$kot_enable = $this->Ordermodel->getKotEnabledStatus($this->session->userdata('logged_in_store_id'));
		$accordionHtml = '';
			
			// Build accordion HTML
		if(!empty($orders)){
		
			$accordionHtml .= '';
	
			foreach ($orders as $index => $order) {
				$isFirst = $index === 0 ? ' ' : ''; // Keep the first accordion open
				$accordionHtml .= '
				<input type="hidden" name="product_name" value="'.$order['orderno'].'">
					<div class="accordion-item">
						<h2 class="accordion-header" id="heading' . $order['id'] . '">
							<button style="background:#eeeef9" class="accordion-button' . ($index !== 0 ? ' collapsed' : '') . '" type="button" data-bs-toggle="collapse" data-bs-target="#collapse' . $order['id'] . '" aria-expanded="' . ($index === 0 ? 'true' : 'false') . '" aria-controls="collapse' . $order['id'] . '">
								' . $index + 1 . ' :- Order No: ' . $order['orderno'] .'
							</span></button>
						</h2>
					
						<div id="collapse' . $order['orderno'] . '" class="accordion-collapse collapse show' . $isFirst . '" aria-labelledby="heading' . $order['id'] . '" data-bs-parent="#ordersAccordion">
							
						<div class="accordion-body product-item">
			<table class="table order_details_table">
				<thead class="thead-light">
					<tr>
						<th>Sl</th>
<th>Product</th>
<th>Quantity</th>
<th>Recipe Details</th>
					</tr>
				</thead>
				<tbody>';
	foreach ($order['items'] as $key => $item) {
		
		
		
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

		$display_none = $item['is_approve'] == 1 ? '' : '';
		$check_approve_order_exist = $this->Ordermodel->check_approve_order_exist($order['orderno']);
		$display_none_order_delete = $check_approve_order_exist == 1 ? 'd-none' : '';
		$productName = $this->Ordermodel->getProductName($item['product_id']);
   		$variantName = $this->Ordermodel->getVariantName($item['variant_id']);
		$variant_id = isset($item['variant_id']) ? $item['variant_id'] : 0;
		$accordionHtml .= '
				<tr id="order-row-' . $item['id'] . '" style="background-color: ' . $backgroundColor . ';">
                    <td>' . $key + 1 . '</td>
                    <td>' . 
            $productName . 
            ($variantName != null ? ' (' . $variantName . ')' : '') . 
        '</td>
					<td>
					<input type="hidden" class="form-control tax" style="width: 100%;" value="' . $item['tax'] . '">
					<input type="hidden" class="form-control id" style="width: 100%;" value="' . $item['id'] . '">
					<input type="hidden" class="form-control store_product_id" style="width: 100%;" value="' . $item['product_id'] . '">
					<div class="input-group qty-group">
						<input type="text" class="form-control text-center quantity" name="quantity" value="'.$item['quantity'].'" min="1" readonly>
					</div>

					</td>
                    <td>' . $item['item_remarks'] . '</td>
					
                </tr>';
				$acceptOrderClass = ($order['order_status'] == 1) ? 'btn8-small accept_table_order' : 'btn6-small accept_table_order ';
				$readyOrderClass = ($order['order_status'] == 2) ? 'btn8-small ready_table_order' : 'btn6-small ready_table_order ';
	}

	$accordionHtml .= '</tbody>
		<tfoot class="table-light order-details_buttons">
				
                <tr>
					<td colspan="2">
                        <button class="btn btn-orange dropdown-toggle" type="button" id="orderStatusDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            ' . (($order['order_status'] == "0") ? "Pending" : (($order['order_status'] == "1") ? "Approved" : (($order['order_status'] == "2") ? "Cooking" : (($order['order_status'] == "3") ? "Ready" : (($order['order_status'] == "4") ? "Delivered" : "Select Status"))))) . '
        </button>
                    </td>
                    <td colspan="5">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn6-small kot_table_order" data-order-id="' . $order['orderno'] . '" data-kot-enable="'.$kot_enable.'">KOT</button>
                            <button class="'.$acceptOrderClass.'" data-order-id="' . $order['orderno'] . '" width="100px" style="margin-left: 10px;">Accept</button>
							<button class="'.$readyOrderClass.'" data-order-id="' . $order['orderno'] . '" width="100px" style="margin-left: 10px;">Ready</button>
                        </div>
                    </td>
					<td colspan="5">
                        
                    </td>
                </tr>
				<tr class="msgContainer'.$order['orderno'].' d-none"><td colspan="12">
				<div class="alert alert-success dark d-none" role="alert" id="ordermsg'.$order['orderno'].'">Order</div>
				</td></tr>
            </tfoot>
        </table>
		</div>';
	$accordionHtml .= '
				</tbody>
			</table>
		</div>
	</div>
	
						</div>

						
                    
                     <div class="modal fade" id="recipe" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl vi">
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

	public function getPendingOrdersByType() {
		$this->load->model('owner/Ordermodel');
		$orders=$this->Ordermodel->getPendingOrdersByType($this->session->userdata('logged_in_store_id') , $this->input->post('order_type')); //print_r($orders);exit;
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
								' . $index + 1 . ' :- Order No: ' . $order['orderno'] .'
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
<th>Is Addon</th>
<th>Recipe Details</th>
					</tr>
				</thead>
				<tbody>';
	foreach ($order['items'] as $key => $item) {
		
		
		
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

		$display_none = $item['is_approve'] == 1 ? '' : '';
		$check_approve_order_exist = $this->Ordermodel->check_approve_order_exist($order['orderno']);
		$display_none_order_delete = $check_approve_order_exist == 1 ? 'd-none' : '';
		$productName = $this->Ordermodel->getProductName($item['product_id']);
   		$variantName = $this->Ordermodel->getVariantName($item['variant_id']);
		$variant_id = isset($item['variant_id']) ? $item['variant_id'] : 0;
		$accordionHtml .= '
				<tr id="order-row-' . $item['id'] . '" style="background-color: ' . $backgroundColor . ';">
                    <td>' . $key + 1 . '</td>
                    <td>' . 
            $productName . 
            ($variantName != null ? ' (' . $variantName . ')' : '') . 
        '</td>
					<td>
					<input type="hidden" class="form-control tax" style="width: 100%;" value="' . $item['tax'] . '">
					<input type="hidden" class="form-control id" style="width: 100%;" value="' . $item['id'] . '">
					<input type="hidden" class="form-control store_product_id" style="width: 100%;" value="' . $item['product_id'] . '">
					<div class="input-group qty-group">
						<input type="text" class="form-control text-center quantity" name="quantity" value="'.$item['quantity'].'" min="1" readonly>
					</div>

					</td>
                    <td>' . $item['item_remarks'] . '</td>
					
                </tr>';
				$acceptOrderClass = ($order['order_status'] == 1) ? 'btn8-small accept_table_order' : 'btn6-small accept_table_order ';
				$readyOrderClass = ($order['order_status'] == 2) ? 'btn8-small ready_table_order' : 'btn6-small ready_table_order ';
	}

	$accordionHtml .= '</tbody>
		<tfoot class="table-light order-details_buttons">
				
                <tr>
					<td colspan="2">
                        <button class="btn btn-warning dropdown-toggle" type="button" id="orderStatusDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            ' . (($order['order_status'] == "0") ? "Pending" : (($order['order_status'] == "1") ? "Approved" : (($order['order_status'] == "2") ? "Cooking" : (($order['order_status'] == "3") ? "Ready" : "Select Status")))) . '
        </button>
                    </td>
                    <td colspan="5">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn6-small kot_table_order" data-order-id="' . $order['orderno'] . '" data-kot-enable="'.$kot_enable.'">KOT</button>
                            <button class="'.$acceptOrderClass.'" data-order-id="' . $order['orderno'] . '" width="100px" style="margin-left: 10px;">Accept</button>
							<button class="'.$readyOrderClass.'" data-order-id="' . $order['orderno'] . '" width="100px" style="margin-left: 10px;">Ready</button>
                        </div>
                    </td>
					<td colspan="5">
                        
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



	public function ready_order_details() {
		$this->load->model('owner/Ordermodel');
		$orders=$this->Ordermodel->getApprovedOrdersByTableId($this->session->userdata('logged_in_store_id') , $this->input->post('order_id')); 
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
								' . $index + 1 . ' :- Order No: ' . $order['orderno'] .'
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
<th>Recipe Details</th>
					</tr>
				</thead>
				<tbody>';
	foreach ($order['items'] as $key => $item) {
		
		
		
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

		$display_none = $item['is_approve'] == 1 ? '' : '';
		$check_approve_order_exist = $this->Ordermodel->check_approve_order_exist($order['orderno']);
		$display_none_order_delete = $check_approve_order_exist == 1 ? 'd-none' : '';
		$productName = $this->Ordermodel->getProductName($item['product_id']);
   		$variantName = $this->Ordermodel->getVariantName($item['variant_id']);
		$variant_id = isset($item['variant_id']) ? $item['variant_id'] : 0;
		$accordionHtml .= '
				<tr id="order-row-' . $item['id'] . '" style="background-color: ' . $backgroundColor . ';">
                    <td>' . $key + 1 . '</td>
                    <td>' . 
            $productName . 
            ($variantName != null ? ' (' . $variantName . ')' : '') . 
        '</td>
					<td>
					<input type="hidden" class="form-control tax" style="width: 100%;" value="' . $item['tax'] . '">
					<input type="hidden" class="form-control id" style="width: 100%;" value="' . $item['id'] . '">
					<input type="hidden" class="form-control store_product_id" style="width: 100%;" value="' . $item['product_id'] . '">
					<div class="input-group qty-group">
						<input type="text" class="form-control text-center quantity" name="quantity" value="'.$item['quantity'].'" min="1" readonly>
					</div>

					</td>
                    <td>' . $item['item_remarks'] . '</td>
					
                </tr>';
				$payOrderClass = ($order['order_status'] == 4) ? 'btn8-small pay_table_order' : 'btn6-small pay_table_order ';
				$diningOrderClass = ($order['order_status'] == 3) ? 'btn8-small dining_order' : 'btn6-small dining_order ';
	}

	$accordionHtml .= '</tbody>
		<tfoot class="table-light order-details_buttons">
				
                <tr>
					<td colspan="2">
                        <button class="btn btn-success dropdown-toggle" type="button" id="orderStatusDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            ' . (($order['order_status'] == "0") ? "Pending" : (($order['order_status'] == "1") ? "Approved" : (($order['order_status'] == "2") ? "Cooking" : (($order['order_status'] == "3") ? "Ready" : (($order['order_status'] == "4") ? "Out For Delivery" : "Select Status"))))) . '
        </button>
                    </td>
                    <td colspan="5">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn6-small kot_table_order" data-order-id="' . $order['orderno'] . '" data-kot-enable="'.$kot_enable.'">KOT</button>
                            <button type="button" data-order-id="' . $order['orderno'] . '" class="'.$diningOrderClass.'" width="100px" style="margin-left: 10px;">Out For Delivery</button>
                            <button class="'.$payOrderClass.'" data-order-id="' . $order['orderno'] . '" width="100px" style="margin-left: 10px;">Pay</button>
							<button type="button" data-bs-toggle="modal" data-id="2" data-name="fgdfg" data-bs-target="#printModal" data-order-id="' . $order['orderno'] . '" class="btn6-small pay_order_print" width="100px" style="margin-left: 10px;">Print</button>
                        </div>
                    </td>
					<td colspan="5">
                        
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

	public function delivered_order_details() {
		$this->load->model('owner/Ordermodel');
		$orders=$this->Ordermodel->getApprovedOrdersByTableId($this->session->userdata('logged_in_store_id') , $this->input->post('order_id')); 
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
								' . $index + 1 . ' :- Order No: ' . $order['orderno'] .'
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
<th>Recipe Details</th>
					</tr>
				</thead>
				<tbody>';
	foreach ($order['items'] as $key => $item) {
		
		
		
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

		$display_none = $item['is_approve'] == 1 ? '' : '';
		$check_approve_order_exist = $this->Ordermodel->check_approve_order_exist($order['orderno']);
		$display_none_order_delete = $check_approve_order_exist == 1 ? 'd-none' : '';
		$productName = $this->Ordermodel->getProductName($item['product_id']);
   		$variantName = $this->Ordermodel->getVariantName($item['variant_id']);
		$variant_id = isset($item['variant_id']) ? $item['variant_id'] : 0;
		$accordionHtml .= '
				<tr id="order-row-' . $item['id'] . '" style="background-color: ' . $backgroundColor . ';">
                    <td>' . $key + 1 . '</td>
                    <td>' . 
            $productName . 
            ($variantName != null ? ' (' . $variantName . ')' : '') . 
        '</td>
					<td>
					<input type="hidden" class="form-control tax" style="width: 100%;" value="' . $item['tax'] . '">
					<input type="hidden" class="form-control id" style="width: 100%;" value="' . $item['id'] . '">
					<input type="hidden" class="form-control store_product_id" style="width: 100%;" value="' . $item['product_id'] . '">
					<div class="input-group qty-group">
						<input type="text" class="form-control text-center quantity" name="quantity" value="'.$item['quantity'].'" min="1" readonly>
					</div>

					</td>
                    <td>' . $item['item_remarks'] . '</td>
					
                </tr>';
				$payOrderClass = ($order['order_status'] == 4) ? 'btn8-small pay_table_order' : 'btn6-small pay_table_order ';
				$diningOrderClass = ($order['order_status'] == 3) ? 'btn8-small dining_order' : 'btn6-small dining_order ';
	}

	$accordionHtml .= '</tbody>
		<tfoot class="table-light order-details_buttons">
				
                <tr>
					<td colspan="2">
                        <button class="btn btn-success dropdown-toggle" type="button" id="orderStatusDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            ' . (($order['order_status'] == "0") ? "Pending" : (($order['order_status'] == "1") ? "Approved" : (($order['order_status'] == "2") ? "Cooking" : (($order['order_status'] == "3") ? "Ready" : (($order['order_status'] == "4") ? "Out For Delivery" : "Select Status"))))) . '
        </button>
                    </td>
                    <td colspan="5">
                       
                    </td>
					<td colspan="5">
                        
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
}
	
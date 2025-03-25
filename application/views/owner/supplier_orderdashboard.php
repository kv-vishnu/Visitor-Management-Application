<div class="application-content order-monitor-content">
    <audio id="alert-audio" src="<?php echo base_url(); ?>uploads/order-siren.mp3" preload="auto"></audio>
    <div class="application-content__container order-monitor-content__container container">
        <h1 class="application-content__page-heading">Order Monitor - <?php echo $name; ?> (Supplier)</h1>


        <div class="modal-container">
            <a target="_blank" href="<?php echo base_url('owner/order/newOrder'); ?>"
                class="order-monitor-content__add-new-dish-btn btn1" data-store-id="41" data-name="SALES">
                <img src="<?php echo base_url();?>assets/admin/images/add-new-dish-icon.svg" alt="add new dish"
                    class="add-new-dish__icon" width="23" height="23">
                Add New Order
            </a>
            <div class="modal-window">
                <div class="modal-wrapper">
                    <div class="modal-data">
                        <iframe id="table_iframe_order" height="750px" width="100%"></iframe>
                    </div>
                    <button class="close-icon"></button>
                </div>
            </div>
        </div>

        <div class="tabs orderdashboard-tab">
            <div class="tabs__row">
                <ul class="tabs__nav">

                    <li class="active"><a href="#tabs1">Tables <span id="tabs__nav_pending_table_count"
                                class="d-none"></span> </a>
                    </li>
                    <?php if($enable_pickup == 1){ ?>
                    <li class=""><a href="#tabs2">Pickup Orders <span id="tabs__nav_pending_pickup_count"
                                class="d-none"></span> </a>
                    </li>
                    <?php }
                    ?>
                    <?php if($enable_delivery == 1){ ?>
                    <li class=""><a href="#tabs3">Delivery Orders<span id="tabs__nav_pending_delivery_count"
                                class="d-none"></span>
                        </a></li>
                    <?php }
                    ?>
                    <li class=""><a href="#tabs4">Ready Orders<span id="tabs__nav_approved_ready_count"
                                class="d-none"></span></a></li>
                </ul>
                <div class="tabs__content orderdashboard-tab__content">
                    <div id="tabs1" class="tabs__pane active">
                        <div class="table-status">
                            <div class="table-status__item">
                                <div class="table-status__item-color table-status__item-color-available"></div>
                                <div class="table-status__item-label">Available</div>
                            </div>
                            <div class="table-status__item">
                                <div class="table-status__item-color table-status__item-reserved"></div>
                                <div class="table-status__item-label">Booked</div>
                            </div>
                            <div class="table-status__item">
                                <div class="table-status__item-color table-status__item-color-booked"></div>
                                <div class="table-status__item-label">Reserved</div>
                            </div>

                        </div>

                        <div class="order-table-list">
                            <?php foreach ($tables as $table) {
                                $orderCount = $this->Ordermodel->getPendingTableOrderCount($table['table_id']); 
                                $table_name = $table['store_table_name'] ? $table['store_table_name'] : $table['table_name'];          
                                $bgClass = '';
                                if ($table['is_reserved'] == 0 && $orderCount == 0) {
                                    $bgClass = 'available';
                                }
                                if ($table['is_reserved'] == 0 && $orderCount > 0) {
                                    $bgClass = 'booked';
                                }
                                if ($table['is_reserved'] == 1 && $orderCount == 0) {
                                    $bgClass = 'reserved';
                                }
                                if ($table['is_reserved'] == 1 && $orderCount > 0) {
                                    $bgClass = 'reserved';
                                } ?>
                            <div class="order-table-list__item">
                                <a data-bs-toggle="modal" data-id="<?php echo $table['table_id']; ?>"
                                    data-name="<?php echo $table_name; ?>" data-bs-target="#recipe"
                                    class="w-100 tableOrderPending" type="button" title="Table Orders">
                                    <div id="order-table-list__item-heading_<?php echo $table['table_id']; ?>"
                                        class="order-table-list__item-heading order-table-list__item-heading-<?php echo $bgClass; ?>">
                                        <?php echo $table_name; ?>
                                        <img src="<?php echo base_url();?>assets/admin/images/table-icon.svg"
                                            alt="table icon" class="order-table-list__item-heading-icon">
                                    </div>
                                </a>
                                <div class="order-table-list__item-content">
                                    <div class="order-table-list__unpaid-cooking">
                                        <div class="order-table-list__unpaid">
                                            <div class="order-table-list__unpaid-label">Unpaid</div>
                                            <div class="order-table-list__unpaid-count"><?php echo $orderCount; ?></div>
                                        </div>
                                        <div class="order-table-list__cooking">
                                            <div class="order-table-list__cooking-label">Cooking</div>
                                            <div class="order-table-list__cooking-count">
                                                <?php echo  $Cooking = $this->Ordermodel->getPendingTableOrderCookingCount($table['table_id']);   ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="order-table-list__completed-reserved">
                                        <a data-bs-toggle="modal" data-id="<?php echo $table['table_id']; ?>"
                                            data-name="<?php echo $table_name; ?>" data-bs-target="#recipe"
                                            class="order-table-list__completed-btn tableOrdercompleted">Completed</a>





                                        <div class="order-table-list__reserved">
                                            <div class="order-table-list__reserved-label">Is Reserved</div>
                                            <div class="order-table-list__reserved-input">
                                                <input type="checkbox" class="cbIsReserved"
                                                    data-id="<?php echo $table['table_id']; ?>"
                                                    <?php if ($table['is_reserved'] == 1) echo 'checked'; ?>>
                                            </div>
                                        </div>





                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>


                    </div>
                    <div id="tabs2" class="tabs__pane">
                        <div class="orders-data">
                            <div class="orders-data__content">
                                <div class="orders-data__order-details">
                                    <a data-id="PK" data-bs-toggle="modal" data-name="Pickup Orders"
                                        data-bs-target="#recipe" class="orders">
                                        <div
                                            class="orders-data__order-details-heading orders-data__order-details-heading-details">
                                            <h2 class="orders-data__order-details-heading-text">Pickup Order
                                                Details</h2>
                                            <img src="<?php echo base_url();?>assets/admin/images/order-details-icon.svg"
                                                alt="order-details-icon"
                                                class="orders-data__order-details-heading-icon">
                                        </div>
                                    </a>
                                    <div class="orders-data__order-details-item-wrapper">
                                        <div class="orders-data__order-details-item">
                                            <div class="orders-data__order-details-item-label">Count</div>
                                            <div class="orders-data__order-details-item-value">
                                                <?php echo $pending_pickup_count; ?></div>
                                        </div>
                                        <div class="orders-data__order-details-item">
                                            <div class="orders-data__order-details-item-label">Cooking</div>
                                            <div class="orders-data__order-details-item-value">
                                                <?php echo $pending_pickup_cooking; ?></div>
                                        </div>
                                        <div class="orders-data__order-details-item">
                                            <div class="orders-data__order-details-item-label">Ready</div>
                                            <div class="orders-data__order-details-item-value">
                                                <?php echo $pending_pickup_ready; ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="orders-data__order-details">
                                    <a data-id="PK" data-bs-toggle="modal" data-name="Completed Pickups"
                                        data-bs-target="#recipe" class="completedOrders">
                                        <div
                                            class="orders-data__order-details-heading orders-data__order-details-heading-completed">
                                            <h2 class="orders-data__order-details-heading-text">Completed Orders</h2>
                                            <img src="<?php echo base_url();?>assets/admin/images/completed-orders-icon.svg"
                                                alt="completed-orders-icon"
                                                class="orders-data__order-details-heading-icon">
                                        </div>
                                    </a>
                                    <div class="orders-data__order-details-item-completed-wrapper">
                                        <div class="orders-data__order-details-item-completed">
                                            <div class="orders-data__order-details-item-completed-label">Count</div>
                                            <div class="orders-data__order-details-item-completed-value">
                                                <?php echo $comp_pickup_count; ?></div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div id="tabs3" class="tabs__pane">
                        <div class="orders-data">
                            <div class="orders-data__content">
                                <div class="orders-data__order-details">
                                    <a data-bs-toggle="modal" data-name="Delivery Orders" data-bs-target="#recipe"
                                        data-id="DL" class="orders">
                                        <div
                                            class="orders-data__order-details-heading orders-data__order-details-heading-details">
                                            <h2 class="orders-data__order-details-heading-text">Delivery Order
                                                Details</h2>
                                            <img src="<?php echo base_url();?>assets/admin/images/delivery-van-icon.svg"
                                                alt="order-details-icon"
                                                class="orders-data__order-details-heading-icon">
                                        </div>
                                    </a>
                                    <div class="orders-data__order-details-item-wrapper">
                                        <div class="orders-data__order-details-item">
                                            <div class="orders-data__order-details-item-label">Count</div>
                                            <div class="orders-data__order-details-item-value">
                                                <?php echo $pending_delivery_count; ?></div>
                                        </div>
                                        <div class="orders-data__order-details-item">
                                            <div class="orders-data__order-details-item-label">Cooking</div>
                                            <div class="orders-data__order-details-item-value">
                                                <?php echo $pending_delivery_cooking; ?></div>
                                        </div>
                                        <div class="orders-data__order-details-item">
                                            <div class="orders-data__order-details-item-label">Ready</div>
                                            <div class="orders-data__order-details-item-value">
                                                <?php echo $pending_delivery_ready; ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="orders-data__order-details">
                                    <a data-bs-toggle="modal" data-name="Completed Deliveries" data-bs-target="#recipe"
                                        data-id="DL" class="completedOrders">
                                        <div
                                            class="orders-data__order-details-heading orders-data__order-details-heading-completed">
                                            <h2 class="orders-data__order-details-heading-text">Completed Orders</h2>
                                            <img src="<?php echo base_url();?>assets/admin/images/completed-orders-icon.svg"
                                                alt="completed-orders-icon"
                                                class="orders-data__order-details-heading-icon">
                                        </div>
                                    </a>
                                    <div class="orders-data__order-details-item-completed-wrapper">
                                        <div class="orders-data__order-details-item-completed">
                                            <div class="orders-data__order-details-item-completed-label">Count</div>
                                            <div class="orders-data__order-details-item-completed-value">
                                                <?php echo $comp_delivery_count; ?></div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="orders-data__content-no-orders">
                                    <h2 class="orders-data__content-no-orders-text">No Orders are Available</h2>
                                </div> -->


                            </div>
                        </div>
                    </div>
                    <div id="tabs4" class="tabs__pane">
                        <div class="order-table-list">
                            <?php foreach ($ready_orders as $order) {
                                $order_status = '';
                                $type = '';
                                if ($order['order_status'] == 1) {
                                    $order_status = 'Approved';
                                    $btn_class='btn-approved w-100 mt-2';
                                } elseif ($order['order_status'] == 2) {
                                    $order_status = 'Cooking';
                                    $btn_class='btn-cooking w-100 mt-2';
                                }elseif ($order['order_status'] == 3) {
                                    $order_status = 'Ready';
                                    $btn_class='btn-ready w-100 mt-2';
                                }
                                elseif ($order['order_status'] == 4) {
                                    $order_status = 'Delivered';
                                }


                                if($order['order_type'] == 'D'){
                                    $type = 'Dining';
                                    $order_type = $this->Ordermodel->get_table_name($order['table_id']); //Display table name if order type dining
                                    $bgClass = '#ede1db';
                                }
                                if($order['order_type'] == 'PK'){
                                    $type = 'Pickup';
                                    $order_type = 'Pickup'; //Display table name if order type dining
                                    $bgClass = '#b4c9dd';
                                }
                                if($order['order_type'] == 'DL'){
                                    $type = 'Delivery';
                                    $order_type = 'Delivery'; //Display table name if order type dining
                                    $bgClass = '#f1b3a1';
                                }
                               ?>
                            <div class="order-table-list__item">
                                <a data-bs-toggle="modal" data-id="<?php echo $order['orderno']; ?>"
                                    data-name="<?php echo $order['orderno']; ?>" data-bs-target="#recipe"
                                    class="w-100 ready_order_details" type="button" title="Table Orders">
                                    <div id="order-table-list__item-heading_<?php echo $order['orderno']; ?>"
                                        class="order-table-list__item-heading order-table-list__item-heading"
                                        style="background-color: <?php echo $bgClass; ?>">
                                        <?php echo $order_type; ?> - ####<?php echo $order['order_token']; ?>
                                        <img src="<?php echo base_url();?>assets/admin/images/table-icon.svg"
                                            alt="table icon" class="order-table-list__item-heading-icon">
                                    </div>
                                </a>
                                <button type="button"
                                    class="btn <?php echo $btn_class; ?>"><?php echo $order_status; ?></button>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for detailed view -->
<div class="modal fade" id="recipe" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel"> <span id="table_name"></span></h1>
                <button type="button" class="emigo-close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <iframe id="table_iframe_recipe" height="700px" width="100%"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- end -->
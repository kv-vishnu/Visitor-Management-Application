<div class="application-content order-monitor-content">
    <audio id="kitchen_alert-audio" src="<?php echo base_url(); ?>uploads/order-siren.mp3" preload="auto"></audio>
    <div class="application-content__container order-monitor-content__container container">
        <h1 class="application-content__page-heading">Kitchen Monitor - <?php echo $name; ?> </h1>


        <div class="modal-container">
            <!-- <a class="modal-trigger order-monitor-content__add-new-dish-btn btn1 new_order" data-store-id="41"
                data-name="SALES">
                <img src="<?php echo base_url();?>assets/admin/images/add-new-dish-icon.svg" alt="add new dish"
                    class="add-new-dish__icon" width="23" height="23">
                Add New Order
            </a> -->
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

                    <li class="active"><a href="#tabs1">Orders <span id="tabs__nav_approved_table_count"
                                class="d-none"></span> </a>
                    </li>
                    <li class=""><a href="#tabs2">Ready Orders <span id="tabs__nav_approved_ready_count"
                                class="d-none"></span> </a>
                    </li>
                    <li class=""><a href="#tabs3">Delivered Orders<span id="tabs__nav_approved_delivered_count"
                                class="d-none"></span>
                        </a></li>
                </ul>
                <div class="tabs__content orderdashboard-tab__content">
                    <div id="tabs1" class="tabs__pane active">
                        <div class="table-status">
                            <div class="table-status__item">
                                <div class="table-status__item-color table-status__item-color-available"></div>
                                <div class="table-status__item-label">Dining</div>
                            </div>
                            <div class="table-status__item">
                                <div class="table-status__item-color table-status__item-reserved"></div>
                                <div class="table-status__item-label">Pickup</div>
                            </div>
                            <div class="table-status__item">
                                <div class="table-status__item-color table-status__item-color-booked"></div>
                                <div class="table-status__item-label">Delivery</div>
                            </div>

                            <!-- <div class="table-status__item">
                                <div class="table-status__item-color table-status__item-color-available"></div>
                                <div class="table-status__item-label">Approved</div>
                            </div>
                            <div class="table-status__item">
                                <div class="table-status__item-color table-status__item-reserved"></div>
                                <div class="table-status__item-label">Ready</div>
                            </div>
                            <div class="table-status__item">
                                <div class="table-status__item-color table-status__item-color-booked"></div>
                                <div class="table-status__item-label">Delivered</div>
                            </div> -->

                        </div>

                        <div class="order-table-list">
                            <?php foreach ($approved_orders as $order) {
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
                                    class="w-100 order_details" type="button" title="Table Orders">
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
                    <div id="tabs2" class="tabs__pane">
                        <div class="table-status">
                            <div class="table-status__item">
                                <div class="table-status__item-color table-status__item-color-available"></div>
                                <div class="table-status__item-label">Dining</div>
                            </div>
                            <div class="table-status__item">
                                <div class="table-status__item-color table-status__item-reserved"></div>
                                <div class="table-status__item-label">Pickup</div>
                            </div>
                            <div class="table-status__item">
                                <div class="table-status__item-color table-status__item-color-booked"></div>
                                <div class="table-status__item-label">Delivery</div>
                            </div>
                        </div>
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
                                    class="w-100 order_details" type="button" title="Table Orders">
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
                    <div id="tabs3" class="tabs__pane">
                        <div class="table-status">
                            <div class="table-status__item">
                                <div class="table-status__item-color table-status__item-color-available"></div>
                                <div class="table-status__item-label">Dining</div>
                            </div>
                            <div class="table-status__item">
                                <div class="table-status__item-color table-status__item-reserved"></div>
                                <div class="table-status__item-label">Pickup</div>
                            </div>
                            <div class="table-status__item">
                                <div class="table-status__item-color table-status__item-color-booked"></div>
                                <div class="table-status__item-label">Delivery</div>
                            </div>
                        </div>
                        <div class="order-table-list">
                            <?php foreach ($delivered_orders as $order) {
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
                                     $btn_class='btn-delivered w-100 mt-2';
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
                                    class="w-100 delivered_order_details" type="button" title="Table Orders">
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
    <div class="modal-dialog modal-lg">
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
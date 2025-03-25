<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet preload" href="<?php echo site_url(); ?>assets/website/css/plugins.css" as="style">
    <link rel="stylesheet preload" href="<?php echo site_url(); ?>assets/website/css/style.css" as="style">
    <link rel="stylesheet" href="<?php echo site_url(); ?>assets/admin/css/user-custom-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <style>
    body {
        font-family: 'Poppins' !important;
    }

    .cart-item img {
        max-width: 60px;
        height: auto;
    }

    .total-price {
        font-size: 1.2rem;
        font-weight: bold;
    }

    .out_of_stock_warning {
        background: #ef4f5f;
        border-radius: 10px;
        padding: 10px;
        margin-top: 10px;
        margin-bottom: 10px;
    }

    .out_of_stock_warning p,
    li {
        color: #fff !important;
        font-size: 14px !important;
    }

    .btn-checkout {
        background-color: #ff7f50;
        color: white;
        width: 100%;
    }

    .bouncing-button {
        padding: 15px 30px;
        font-size: 16px;
        color: #fff;
        background-color: #198754;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        animation: bounce 1s infinite;
    }

    @keyframes bounce {

        0%,
        20%,
        50%,
        80%,
        100% {
            transform: translateY(0);
        }

        40% {
            transform: translateY(-10px);
        }

        60% {
            transform: translateY(-5px);
        }
    }
    </style>
</head>

<body>
    <div class="user-cart-page">
        <div class="user-cart-page_title">
            <span class="left"><i class="fa fa-shopping-cart cart-icon"></i></span>
            <span class="right">Cart</span>
        </div>

        <div class="out_of_stock_warning user-cart-page__out-of-stock" id="out-of-stock-products"
            style="display: none;">
            <p id="error-message">Some products are out of stock</p>
        </div>


        <input type="hidden" value="0" id="is_whatsapp_enable">
        <input type="hidden" id="delivery_type" value="<?php echo $this->session->userdata('delivery_type'); ?>">
        <input type="hidden" id="delivery_type_phone"
            value="<?php echo $this->session->userdata('delivery_type_phone'); ?>">
        <input type="hidden" id="store_id" value="<?php echo $this->session->userdata('store_id'); ?>">
        <input type="hidden" id="orderno" value="<?php echo $this->session->userdata('order_no'); ?>">
        <!-- Header Section -->
        <?php
      $this->load->model('website/Homemodel');
      $prevous_orders = $this->Homemodel->get_prevous_orders($this->session->userdata('order_no') , $store_id);
      $order_summary = $this->Homemodel->get_prevous_order_summary($this->session->userdata('order_no') , $store_id);


      if(!empty($prevous_orders)){ ?>
        <!--<h6 class="mb-3">Previous Orders</h6>-->
        <?php foreach($prevous_orders as $item){ ?>
        <div class="list-group-item d-flex justify-content-between align-items-center cart-item">
            <div class="col-6">
                <h6 class="mb-0 heading"><?php echo $this->Ordermodel->getProductName($item['product_id']); ?></h6>
                <small class="d-none">Short Description</small>
            </div>
            <div class="col-3 text-center product" data-id="">
                <div class="d-flex align-items-center">
                    <input type="number" disabled class="" value="<?php echo $item['quantity']; ?>" min="1">
                </div>
            </div>

            <div class="col-3 text-end">
                <p><b><?php echo $item['quantity'] * $item['rate']; ?></b></p>
            </div>
        </div>
        <?php } ?>
        <div class="user-cart-page__cart-total-container">
            <h6 class="user-cart-page__total-label">Total : </h6>
            <h6 class="user-cart-page__total-count"><?php echo $order_summary['amount']; ?></h6>
        </div>

        <?php } ?>

        <input type="hidden" id="subtotal_previous" value="
        <?php if(isset($order_summary))
        { echo $order_summary['amount']; 
        }
        else
        { 
            echo 0 ;
        }; ?>">


        <!-- Cart Items List -->
        <div class="user-cart-page__order-section">
            <!--<h6 class="mb-3">Current Orders</h6>-->
            <div class="list-group" id="cart-items">
            </div>
            <div class="user-cart-page__cart-total-container">
                <h6 class="user-cart-page__total-label">Total :</h6>
                <h6 id="current-total" class="user-cart-page__total-count"></h6>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <h6 class="user-cart-page__total-label text-capitalize"><?php echo $tax_infr->tax_type;  ?> :
                    <!--(<?php echo $tax_infr->tax_rate;  ?> %)-->
                </h6>
                <h6 class="user-cart-page__total-count" id="vat-total">000</h6>

            </div>

            <!-- Cart Summary Section -->
            <div class="user-cart-page__cart-gst-container">

            </div>
            <!--<div class="user-cart-page__cart-grand-total-container">-->
            <!--    <h6 class="user-cart-page__cart-grand-total-label"><b>Grand Total:</b></h6>-->
            <!--    <h6 id="grand-total" class="grand-total user-cart-page__cart-grand-total-count">1</h6>-->
            <!--</div>-->


            <?php
  if($this->session->userdata('delivery_type') == 'DL' || $this->session->userdata('delivery_type') == 'PK'){ ?>
            <div class="mt-5 cart-view-form">
                <form class="cart-view-form__body">
                    <!-- Name -->
                    <div class="mb-3">
                        <input type="text" class="form-control" id="name" placeholder="Name" required>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <input type="text" class="form-control" id="phone" placeholder="Phone" required>
                    </div>

                    <!-- Address -->
                    <div class="mb-3">
                        <textarea class="form-control" id="address" rows="2" placeholder="Address" required></textarea>
                    </div>

                    <!-- Submit Button -->
                </form>
                <?php } ?>
            </div>










            <!-- Cart Buttons -->


            <?php 
  $cart = $this->session->userdata('cart');
  print_r($cart);     
  if(!empty($cart) && $this->session->userdata('delivery_type') == 'D'){ ?>
            <div class="d-flex justify-content-between cart-total-place-holder">
                <?php
      if($this->session->userdata('store_token') != ''){
        if($this->session->userdata('order_no') != 0){
          $backLink = base_url('website/load_orders/'.$this->session->userdata('store_token').'/'.$this->session->userdata('order_no'));
        }
        else
        {
          $backLink = base_url('website/load_orders/'.$this->session->userdata('store_token').'/0');
        }
      }else{
        $backLink = base_url('website/load_orders/shop/'.$this->session->userdata('store_id').'/'.$this->session->userdata('delivery_type').'/0');
      }
      ?>

                <button class="btn btn-success w-50 p-3 table_order bouncing-button place-order-button-new"
                    style="font-size: 15px;border-radius: 15px;">
                    <span class="place-order-button__col1">
                        <span class="place-order-button__col1-price-label">Grand Total : <span
                                class="place-order-button__col1-price grand-total"></span></span>
                    </span>
                    <span class="place-order-button__col2">
                        <span class="place-order-button__col1-main-label">Place Order</span>
                    </span>


                </button>
                <a href="<?php echo $backLink; ?>" class="btn w-50 btn-checkout-new p-3 me-3"
                    style="font-size: 15px;border-radius: 10px;">Add More Item</a>
            </div>
            <?php }
     if(!empty($cart) && $this->session->userdata('delivery_type') != 'D'){ ?>
            <div class="d-flex justify-content-between cart-total-place-holder">
                <?php
      if($this->session->userdata('store_token') != ''){
        $backLink = base_url('website/load_orders/'.$this->session->userdata('store_token').'/0');
      }else{
        $backLink = base_url('website/load_orders/shop/'.$this->session->userdata('store_id').'/'.$this->session->userdata('delivery_type').'/0');
      }
      ?>

                <button class="btn btn-success w-50 p-3 type_order bouncing-button place-order-button-new"
                    style="font-size: 15px;border-radius: 15px;">
                    <span class="place-order-button__col1">
                        <span class="place-order-button__col1-price"></span>
                        <span class="place-order-button__col1-price-label">Grand Total : <span
                                class="place-order-button__col1-price grand-total"></span></span>
                    </span>
                    <span class="place-order-button__col2">
                        <span class="place-order-button__col1-main-label">Place Order</span>
                    </span>


                </button>
                <a href="<?php echo $backLink; ?>" class="btn w-50 btn-checkout-new p-3 me-3"
                    style="font-size: 15px;border-radius: 10px;">Add More Item</a>
            </div>
            <?php } ?>
            <div class="d-flex justify-content-between">
            </div>

            <!--<a  class="btn w-50 test p-3 me-3" onclick="FnblankspaceTotal()" style="font-size: 15px;border-radius: 10px;">Add More Item</a>-->



            <!-- The Modal -->
            <div class="modal fade" id="validationModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            This is a simple Bootstrap modal popup.
                        </div>
                    </div>
                </div>
            </div>
            <!-- validation -->
        </div>
    </div>
</body>





<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- plugins js -->
<script defer src="<?php echo site_url() ?>assets/website/js/plugins.js"></script>

<!-- custom js -->
<script defer src="<?php echo site_url() ?>assets/website/js/main.js"></script>
<!-- header style two End -->
<script>
$(document).ready(function() {
    $('.table_order').on('click', function() {
        const is_whatsapp = $("#is_whatsapp_enable").val()
        if (is_whatsapp == 1) {
            sendToWhatsAppOrder();
        } else {
            orderListingcheckoutDining();
        }
    });
    $('.type_order').on('click', function() {
        const name = $('#name').val().trim();
        const phone = $('#phone').val().trim();
        const address = $('#address').val().trim();

        if (!name || !phone || !address) {
            $('#validationModal').modal('show');
            $('#validationModal .modal-body').html('Enter Name , Phone , Email');
            return; // Stop execution if any field is empty
        }

        sendToWhatsAppOrderWithDetails(); // Call the function if validation passes
    });
});
</script>

<script>
function orderListingcheckoutDining() {
    $.ajax({
        url: '<?= base_url("website/products/orderListingcheckoutDining") ?>',
        method: 'post',
        dataType: 'json',
        success: function(response) {

            if (response.status === 'error') {
                // Optionally, display out-of-stock products
                if (response.outOfStockProducts && response.outOfStockProducts.length > 0) {
                    var outOfStockList = $('<div>'); // Use a div to hold the title and list
                    //outOfStockList.append('<p class="text-center mb-0">Out of Stock Products</p>');
                    var productList = $('<ul>'); // Create the unordered list
                    response.outOfStockProducts.forEach(function(product) {
                        productList.append('<li>' +
                            product.product_name + ' - ' +
                            'Quantity: ' + product.requested_quantity + ' - ' +
                            'Available Stock: ' + product.available_stock +
                            '</li>');
                    });
                    outOfStockList.append(productList); // Add the list to the div
                    $('#out-of-stock-products').html(outOfStockList).show();
                }
            } else {
                $('#out-of-stock-products').hide();
                window.location.href = '<?= base_url("website/products/orderListing/") ?>' + response
                    .orderNo + '/' +
                    response.store_id;
            }
        }
    });
}


function sendToWhatsAppOrder() {
    const phoneNumber = $('#delivery_type_phone').val();
    //const phoneNumber = 7012713312;
    const table = $('#store_table').val();

    $.ajax({
        url: '<?= base_url("website/products/checkout") ?>',
        method: 'post',
        dataType: 'json',
        success: function(response) {

            // console.log(response);

            if (response.status === 'error') {
                // Optionally, display out-of-stock products
                if (response.outOfStockProducts && response.outOfStockProducts.length > 0) {
                    var outOfStockList = $('<div>'); // Use a div to hold the title and list
                    //outOfStockList.append('<p class="text-center mb-0">Out of Stock Products</p>');
                    var productList = $('<ul>'); // Create the unordered list
                    response.outOfStockProducts.forEach(function(product) {
                        productList.append('<li>' +
                            product.product_name + ' - ' +
                            'Quantity: ' + product.requested_quantity + ' - ' +
                            'Available Stock: ' + product.available_stock +
                            '</li>');
                    });
                    outOfStockList.append(productList); // Add the list to the div
                    $('#out-of-stock-products').html(outOfStockList).show();
                }
            } else {

                $.ajax({
                    url: '<?= base_url("cart/getpreviousorders") ?>',
                    method: 'post',
                    dataType: 'json',
                    data: {
                        order_no: response.orderNo,
                        storeId: response.storeId
                    },
                    success: function(data) {
                        var type = '<?= $tax_infr->tax_type;  ?>';
                        var taxrate = '<?= $tax_infr->tax_rate;  ?>';

                        const previous = data.previous_orders;
                        const current = data.current_orders;

                        let message = `*ORDER DETAILS - ${table}*\n`;
                        message += `------------------------------------------\n`;
                        let overallTotal = 0;

                        let index = 0;
                        $.each(previous, function(id, item) {
                            const itemTotal = item.rate * item.quantity;
                            overallTotal += itemTotal;
                            message +=
                                `${(index + 1).toString().padEnd(3)}.  ${truncateAndPadString(item.name, 20)} : ${Fnqty(item.quantity).toString().padStart(3)}  : ${FnblankspaceTotal(itemTotal).toString().padStart(8)}\n`;
                            index++;
                        });

                        if (Object.keys(current).length > 0) {
                            message += `--------------------------------------\n`;
                        }

                        $.each(current, function(id, item) {
                            const itemTotal = item.rate * item.quantity;
                            overallTotal += itemTotal;
                            message +=
                                `${(index + 1).toString().padEnd(3)}. ${truncateAndPadString(item.name, 20)}  : ${Fnqty(item.quantity).toString().padStart(3)}  : ${FnblankspaceTotal(itemTotal).toString().padStart(8)}\n`;
                            index++;
                        });




                        message += `--------------------------------------\n`;
                        //   let overallTotalnew = overallTotal + 100;
                        //   let taxAmount = (overallTotalnew * taxrate) / 100; // Calculate tax amount

                        const gstAmount = (overallTotal * taxrate) / 100;
                        let grandTotal = overallTotal + gstAmount; // Calculate grand total



                        message += `             Total : ₹${overallTotal.toFixed(2)}\n`;
                        message +=
                            `             ${type}(${taxrate}%)  : ₹${gstAmount.toFixed(2)}\n`;
                        message += `             *Grand Total : ₹${grandTotal.toFixed(2)}*\n`;
                        message += `--------------------------------------\n`;
                        console.log(String(message));

                        const websiteLink =
                            `https://qr-experts.com/emigo-restaurant-application/website/load_orders/${response.storeToken}/${response.orderNo}`;
                        message += `For Re Order, visit: ${websiteLink}\n\n`;
                        const whatsappNumber = phoneNumber;
                        const encodedMessage = encodeURIComponent(message);
                        const whatsappURL =
                            `https://api.whatsapp.com/send?phone=${whatsappNumber}&text=${encodedMessage}`;
                        window.location.href = whatsappURL;
                        clearsession();
                    }
                });
            }
        }
    });
}


function truncateAndPadString(str, length = 20) {
    if (str.length > length) {
        return str.slice(0, length); // Truncate if length is greater than specified
    } else {
        return str.padEnd(length, ' '); // Pad with spaces if length is shorter
    }
}

function Fnqty(val) {
    if (val > 9) {
        str = val;
    } else {
        str = '0' + val;
    }
    return str; // Return the string as is if it's shorter than the length
}

function Fnblankspace(str, length) {
    var cnt = length - str.length;
    for (i = 0; i <= cnt; i++) {
        str = str += " ";
    }
    return str;
}

function FnblankspaceTotal(itemTotal) {
    let itemTotalStr = String(itemTotal); // Convert itemTotal to string
    let length = itemTotalStr.length; // Get the length of the string
    let str = '';
    if (length === 2) {
        str = '  ' + itemTotalStr + '.00';
    } else if (length === 3) {
        str = ' ' + itemTotalStr + '.00';
    } else {
        str = itemTotalStr + '.00';
    }
    return str;
}







function clearsession() {
    $.ajax({
        url: '<?= base_url("website/products/clear_session") ?>',
        method: 'POST',
        success: function(datas) {

        }
    });
}
</script>


<script>
function sendToWhatsAppOrderWithDetails() {
    const phoneNumber = $('#delivery_type_phone').val();
    const name = $('#name').val();
    const phone = $('#phone').val();
    const address = $('#address').val();
    const store_id = $('#store_id').val();
    const type = $('#delivery_type').val();
    const table = '';

    $.ajax({
        url: '<?= base_url("website/products/typecheckout") ?>',
        method: 'POST',
        data: {
            name: name,
            phone: phone,
            address: address,
            store_id1: store_id,
            type: type
        },
        dataType: 'json',
        success: function(response) {

            if (response.status === 'error') {
                // Optionally, display out-of-stock products
                if (response.outOfStockProducts && response.outOfStockProducts.length > 0) {
                    var outOfStockList = $('<div>'); // Use a div to hold the title and list
                    outOfStockList.append('<p class="text-center mb-0">Out of Stock Products</p>');
                    var productList = $('<ul>'); // Create the unordered list
                    response.outOfStockProducts.forEach(function(product) {
                        productList.append('<li>' +
                            product.product_name + ' - ' +
                            'Quantity: ' + product.requested_quantity + ' - ' +
                            'Available Stock: ' + product.available_stock +
                            '</li>');
                    });
                    outOfStockList.append(productList); // Add the list to the div
                    $('#out-of-stock-products').html(outOfStockList).show();
                }
            } else {
                $.ajax({
                    url: '<?= base_url("cart/get") ?>',
                    method: 'GET',
                    success: function(data) {
                        var type = '<?= $tax_infr->tax_type;  ?>';
                        var rate = '<?= $tax_infr->tax_rate;  ?>';

                        const cartData = JSON.parse(data);
                        let message = `*ORDER DETAILS: ${table}\n\n`;
                        message += `------------------------\n`;
                        message += `*Name:* ${name}\n`;
                        message += `*Phone:* ${phone}\n`;
                        message += `*Address:* ${address}\n`;
                        let overallTotal = 0;
                        let index = 0;
                        $.each(cartData, function(id, item) {
                            const itemTotal = item.price * item.quantity;
                            overallTotal += itemTotal;
                            message +=
                                `${index + 1}. ${item.name} : ${item.price}x${item.quantity} = ₹${itemTotal.toFixed(2)}\n`;
                            index++;
                        });
                        message += `------------------------\n`;
                        let taxAmount = (overallTotal * rate) / 100; // Calculate tax amount
                        let grandTotal = overallTotal + taxAmount; // Calculate grand total

                        message += `*SubTotal:* ₹${overallTotal.toFixed(2)}\n\n`;
                        message += `*${type}:* ₹${taxAmount.toFixed(2)} (${rate}%)\n\n`;
                        message += `*Grand Total:* ₹${grandTotal.toFixed(2)}\n\n`;

                        // const websiteLink =
                        //     `https://qr-experts.com/emigo-restaurant-application/website/products/shop/${response.store_id}/${response.order_type}/${response.orderNo}`;
                        // message += `For Re Order, visit: ${websiteLink}\n\n`;

                        const whatsappNumber = phoneNumber;
                        const encodedMessage = encodeURIComponent(message);
                        const whatsappURL =
                            `https://api.whatsapp.com/send?phone=${whatsappNumber}&text=${encodedMessage}`;
                        window.location.href = whatsappURL;
                        clearsession();
                    }
                });
            }
        }
    });

}

function clearsession() {
    $.ajax({
        url: '<?= base_url("website/products/clear_session") ?>',
        method: 'POST',
        success: function(datas) {

        }
    });
}
</script>



<script>
$(document).ready(function() {
    loadCart();

    // Update cart quantity
    function updateCartQuantity(productId, store_productId, variant_code, variant_id, quantity) {
        var store_id = $('#store_id').val();
        //alert(store_id);
        $.ajax({
            url: '<?= base_url("cart/updateQuantity") ?>',
            method: 'POST',
            data: {
                product_id: productId,
                store_productId: store_productId,
                store_id: store_id,
                variant_id: variant_id,
                variant_code: variant_code,
                quantity: quantity
            },
            success: function(data) {
                //alert(data);
                if (data != 'success') {
                    $('#validationModal').modal('show');
                    $('#validationModal .modal-body').html(data);
                }
                loadCart();
            }
        });
    }

    // Update cart quantity
    function updateCartQuantityOutOfStock(productId, store_productId, quantity) {
        var store_id = $('#store_id').val();
        //alert(store_id);
        $.ajax({
            url: '<?= base_url("cart/updateCartQuantityOutOfStock") ?>',
            method: 'POST',
            data: {
                product_id: productId,
                store_productId: store_productId,
                store_id: store_id,
                quantity: quantity
            },
            success: function(response) {
                if (response != '') {
                    $('#validationModal').modal('show');
                    $('#validationModal .modal-body').html(response);
                }
                loadCart();
            }
        });
    }

    // Load cart items and total
    function loadCart() {
        $.ajax({
            url: '<?= base_url("cart/get") ?>',
            method: 'GET',
            success: function(data) {
                console.log(data);
                const cartData = JSON.parse(data);
                displayCart(cartData);
            }
        });
    }

    // Display cart items and update total items count and total value
    function displayCart(cartData) {
        $('#cart-items').empty();
        $('#addon-items').empty();
        let totalItems = 0;
        let cartTotal = 0;

        $.each(cartData, function(id, item) {
            const itemTotal = item.quantity * item.price;
            cartTotal += itemTotal;
            totalItems += item.quantity;
            const addonText = item.is_addon == 1 ? ' (Addon)' : '';
            const variant_name = item.variant_name ? '(' + item.variant_name + ')' : '';
            const variant_code = item.variant_code ? item.variant_code : '';
            const variant_id = item.variant_id ? item.variant_id : '';

            $('#cart-items').append(
                `<div class="list-group-item d-flex justify-content-between align-items-center cart-item">
    <div class="col-6">
      <h6 class="mb-0 heading">${item.name} ${variant_name} ${addonText}</h6>
      <small class="d-none">Short Description</small>
    </div>
    <div class="col-4 text-center product" data-productid="${item.product_id}" data-id="${id}" data-variant_code="${variant_code}" data-variant_id="${variant_id}">
      <div class="d-flex align-items-center add-button2 user-cart-page__qty-controller">
        <button class="btn-sm btn-outline-secondary decrement-btn user-cart-page__qty-decrease" data-id="${id}" data-variant_code="${variant_code}" data-variant_id="${variant_id}">-</button>
        <input type="number" class="form-control1 quantity4 user-cart-page__qty-count" value="${item.quantity}" min="1">
        <button class="btn-sm btn-outline-secondary increment-btn user-cart-page__qty-increase" data-variant_code="${variant_code}" data-variant_id="${variant_id}">+</button>
      </div>
    </div>
    
    <div class="col-1 text-end">
      <p>${itemTotal}</p>
      <input type="text" class="${item.product_id}total_stock" value="${item.quantity * item.variant_value}">
    </div>
  </div>`
            );



        });

        let total = sumOfDigits(totalItems);

        function sumOfDigits(number) {
            let sum = 0;
            let digits = number.toString().split(''); // Convert number to a string and split into digits

            digits.forEach(function(digit) {
                sum += parseInt(digit, 10); // Convert each digit back to an integer and add to sum
            });

            return sum;
        }

        vatPercentage = <?php echo $tax_infr->tax_rate; ?>


        $('#total-items').text(total + ' Items Added' + ' ₹' + cartTotal.toFixed(2));

        // alert(cartTotal.toFixed(2));

        if ($('#order_no').val() != '') {
            $('#current-total').text(cartTotal.toFixed(2));
            $('#item-total').text((cartTotal + parseFloat($('#subtotal_previous').val())).toFixed(2));
            $('#vat-total').text(((cartTotal + parseFloat($('#subtotal_previous').val())) * (vatPercentage /
                100)).toFixed(2));
            $('.grand-total').text(((cartTotal + parseFloat($('#subtotal_previous').val())) + ((cartTotal +
                parseFloat($('#subtotal_previous').val())) * (vatPercentage / 100))).toFixed(2));
        } else {
            $('#current-total').text(cartTotal.toFixed(2));
            $('#item-total').text((cartTotal + parseFloat($('#subtotal_previous').val())).toFixed(2));
            $('#vat-total').text(((cartTotal + parseFloat($('#subtotal_previous').val())) * (vatPercentage /
                100)).toFixed(2));
            $('.grand-total').text(((cartTotal + parseFloat($('#subtotal_previous').val())) + ((cartTotal +
                parseFloat($('#subtotal_previous').val())) * (vatPercentage / 100))).toFixed(2));
        }
    }

    // Delete item from cart
    $(document).on('click', '.delete-item', function() {
        const productId = $(this).data('id');
        deleteCartItem(productId);
    });

    // Function to delete cart item
    function deleteCartItem(productId) {
        $.ajax({
            url: '<?= base_url("cart/delete") ?>',
            method: 'POST',
            data: {
                product_id: productId
            },
            success: function() {
                loadCart();
            }
        });
    }

    $(document).on('click', '.increment-btn', function() {
        const $row = $(this).closest('.cart-item');
        const product = $(this).closest('.product');

        const productId = product.data('id');
        const store_productId = product.data('productid');
        const variant_code = product.data('variant_code');
        const variant_value = product.data('variant_value');
        const variant_id = product.data('variant_id');
        const quantityInput = product.find('.quantity4');

        let quantity = parseInt(quantityInput.val()) + 1;
        quantityInput.val(quantity);

        let sum_variant_total = 0;
        $(`.${store_productId}total_stock`).each(function() {
            let variant_total = parseFloat($(this).val()) ||
                0; // Convert to float, default to 0 if NaN
            sum_variant_total += variant_total;
        });
        alert(sum_variant_total);

        let total_new = 1 * variant_value;

        // Ensure correct class for stock update
        $row.find(`.${store_productId}total_stock`).val(parseInt((quantity + 1) * variant_value));

        let total_stock = 19;

        if (total_stock < sum_variant_total + total_new) {
            $('#validationModal').appendTo('body').modal('show');
            $('#validationModal .modal-body').html('Out of Stock');
            return false;
        } else {
            updateCartQuantity(productId, store_productId, variant_code, variant_id, quantity, 0);
            $totalSpan.text(total.toFixed(2));
            $hiddenTotal.val(total);
        }

    });


    // Decrement button click
    $(document).on('click', '.decrement-btn', function() {
        const product = $(this).closest('.product');
        const productId = product.data('id');
        const store_productId = product.data('productid');
        const quantityInput = product.find('.quantity4');
        let quantity = parseInt(quantityInput.val()) - 1;
        if (quantity > 0) {
            quantityInput.val(quantity);
            updateCartQuantityOutOfStock(productId, store_productId, quantity, 0); //
        } else {
            deleteCartItem(productId, 0);
        }
    });
});
</script>

</html>
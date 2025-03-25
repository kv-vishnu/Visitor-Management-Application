<input type="hidden" id="base_url" value="<?php echo base_url();?>">
<link href="<?php echo base_url();?>assets/admin/css/classic.min.css" rel="stylesheet" /> <!-- 'classic' theme -->
<link href="<?php echo base_url();?>assets/admin/fonts/css/all.min.css" rel="stylesheet" />

<link href="<?php echo base_url();?>assets/admin/css/icon.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>assets/admin/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url();?>assets/admin/js/jquery.min.js"></script>
<link href="<?php echo base_url();?>assets/admin/css/latest-font.css" id="app-style" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>assets/admin/sass/owner-custom-styles.css" id="app-style" rel="stylesheet"
    type="text/css" />

<div class="application-content">
    <input type="hidden" id="order_number" value="<?php echo $order_number;?>">
    <div class="container">
        <div class="row justify-content-center mb-3">
            <div class="emigo-modal__header-button-group justify-content-center mb-3">
                <a id="diningOrder"><button class="btn1">Dining</button></a>
                <a id="pickupOrder"><button class="btn2">Pickup</button></a>
                <a id="deliveryOrder"><button class="btn3">Delivery</button></a>
            </div>
        </div>
    </div>

    <?php echo $heading; ?>
    <div class="container" id="orders-container">
        <iframe id="iframe_body" src="<?php echo base_url('owner/order/newDiningOrder/' . $order_number); ?>"
            height="1000px" width="100%"></iframe>
    </div>

</div>



</div>

<script src="<?php echo base_url();?>assets/admin/js/modules/store.js"></script>

<!-- JAVASCRIPT -->
<script src="<?php echo base_url();?>assets/admin/js/metismenu.js"></script>
<script src="<?php echo base_url();?>assets/admin/js/simplebar.min.js"></script>
<script src="<?php echo base_url();?>assets/admin/js/waves.min.js"></script>
<script src="<?php echo base_url();?>assets/admin/js/feather.min.js"></script>
<script src="<?php echo base_url();?>assets/admin/js/app.js"></script>

<script>
$(document).ready(function() {

    $("#store_product_id").change(function() {
        $.ajax({
            url: '<?= base_url("owner/order/getProductRatesWithIsCustomizeNewOrder"); ?>',
            method: 'POST',
            data: {
                product_id: $('#store_product_id').val(),
                order_number: $('#order_number').val()
            },
            success: function(response) {
                $('#orders-container').html(response);
            }
        });
    });
});



$('#diningOrder').click(function() {
    var order_number = $('#order_number').val();
    document.getElementById('iframe_body').src =
        '<?php echo base_url('owner/order/newDiningOrder/'); ?>' + order_number;
});

$('#pickupOrder').click(function() {
    var order_number = $('#order_number').val();
    document.getElementById('iframe_body').src =
        '<?php echo base_url('owner/order/newPickupOrder/'); ?>' + order_number;
});

$('#deliveryOrder').click(function() {
    var order_number = $('#order_number').val();
    document.getElementById('iframe_body').src =
        '<?php echo base_url('owner/order/newDeliveryOrder/'); ?>' + order_number;
});
</script>
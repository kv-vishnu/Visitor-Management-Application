<input type="hidden" id="base_url" value="<?php echo base_url();?>">
<link href="<?php echo base_url();?>assets/admin/css/crm-responsive.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>assets/admin/css/classic.min.css" rel="stylesheet" /> <!-- 'classic' theme -->
<link href="<?php echo base_url();?>assets/admin/fonts/css/all.min.css" rel="stylesheet" />
<link href="<?php echo base_url();?>assets/admin/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet"
    type="text/css" />
<link href="<?php echo base_url();?>assets/admin/css/icon.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>assets/admin/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url();?>assets/admin/js/jquery.min.js"></script>
<script src="<?php echo base_url();?>assets/admin/js/bootstrap.bundle.min.js"></script>












<div class="row">


    <!-- if response within jquery -->
    <div class="message d-none" role="alert"></div>
    <!-- if response within jquery -->


    <?php if($this->session->flashdata('success')){ ?>
    <div class="alert alert-success dark" role="alert">
        <?php echo $this->session->flashdata('success');$this->session->unset_userdata('success'); ?>
    </div><?php } ?>

    <?php if($this->session->flashdata('error')){ ?>
    <div class="alert alert-danger dark" role="alert">
        <?php echo $this->session->flashdata('error');$this->session->unset_userdata('error'); ?>
    </div><?php } ?>


    <input type="text" id="order_type" value="<?php echo $type;?>">

    <div class="">
        <div class="table-responsive-sm">


            <div class="row">
                <div class="accordion" id="orderAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOrder1">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOrder1" aria-expanded="true" aria-controls="collapseOrder1">
                                Order 1
                            </button>
                        </h2>
                        <div id="collapseOrder1" class="accordion-collapse collapse show"
                            aria-labelledby="headingOrder1" data-bs-parent="#orderAccordion">
                            <div class="accordion-body">
                                <!-- Product 1 -->
                                <div class="product-item">
                                    <p>Product 1 Name</p>
                                    <label for="quantityProduct1">Quantity:</label>
                                    <input type="number" id="quantityProduct1" value="1" min="1">
                                    <p>Price: $10.00</p>
                                </div>

                                <!-- Product 2 -->
                                <div class="product-item">
                                    <p>Product 2 Name</p>
                                    <label for="quantityProduct2">Quantity:</label>
                                    <input type="number" id="quantityProduct2" value="1" min="1">
                                    <p>Price: $15.00</p>
                                </div>

                                <!-- Add more products as needed -->

                                <!-- Button to Update Quantities for All Products in This Order -->
                                <button class="btn btn-primary update-order-quantities" data-order-id="1">Update
                                    Quantities</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion" id="orderAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOrder1">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOrder1" aria-expanded="true" aria-controls="collapseOrder1">
                                Order 1
                            </button>
                        </h2>
                        <div id="collapseOrder1" class="accordion-collapse collapse show"
                            aria-labelledby="headingOrder1" data-bs-parent="#orderAccordion">
                            <div class="accordion-body">
                                <!-- Product 1 -->
                                <div class="product-item">
                                    <p>Product 1 Name</p>
                                    <label for="quantityProduct1">Quantity:</label>
                                    <input type="number" id="quantityProduct1" value="1" min="1">
                                    <p>Price: $10.00</p>
                                </div>

                                <!-- Product 2 -->
                                <div class="product-item">
                                    <p>Product 2 Name</p>
                                    <label for="quantityProduct2">Quantity:</label>
                                    <input type="number" id="quantityProduct2" value="1" min="1">
                                    <p>Price: $15.00</p>
                                </div>

                                <!-- Add more products as needed -->

                                <!-- Button to Update Quantities for All Products in This Order -->
                                <button class="btn btn-primary update-order-quantities" data-order-id="1">Update
                                    Quantities</button>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="accordion" id="orderAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOrder1">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOrder1" aria-expanded="true" aria-controls="collapseOrder1">
                                Order 1
                            </button>
                        </h2>
                        <div id="collapseOrder1" class="accordion-collapse collapse show"
                            aria-labelledby="headingOrder1" data-bs-parent="#orderAccordion">
                            <div class="accordion-body">
                                <!-- Product 1 -->
                                <div class="product-item">
                                    <p>Product 1 Name</p>
                                    <label for="quantityProduct1">Quantity:</label>
                                    <input type="number" id="quantityProduct1" value="1" min="1">
                                    <p>Price: $10.00</p>
                                </div>

                                <!-- Product 2 -->
                                <div class="product-item">
                                    <p>Product 2 Name</p>
                                    <label for="quantityProduct2">Quantity:</label>
                                    <input type="number" id="quantityProduct2" value="1" min="1">
                                    <p>Price: $15.00</p>
                                </div>

                                <!-- Add more products as needed -->

                                <!-- Button to Update Quantities for All Products in This Order -->
                                <button class="btn btn-primary update-order-quantities" data-order-id="1">Update
                                    Quantities</button>
                            </div>
                        </div>
                    </div>
                </div>





            </div>

            <div id="orders-container">
                <!-- Orders will be displayed here -->
            </div>











        </div>
    </div>
</div>





<!--modal for delete confirmation-->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?php echo confirm; ?></h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="table_id" value="" />
                <input type="hidden" name="id" id="store_id_hidden_popup" value="" />
                <?php echo are_you_sure; ?>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="button" data-bs-dismiss="modal">No</button>
                <button class="btn btn-secondary" id="yes_del_table" type="button" data-bs-dismiss="modal">Yes</button>
            </div>
        </div>
    </div>
</div>
<!--modal for delete confirmation-->





</div>

<script src="<?php echo base_url();?>assets/admin/js/modules/store.js"></script>

<!-- JAVASCRIPT -->
<script src="<?php echo base_url();?>assets/admin/js/metismenu.js"></script>
<script src="<?php echo base_url();?>assets/admin/js/simplebar.min.js"></script>
<script src="<?php echo base_url();?>assets/admin/js/waves.min.js"></script>
<script src="<?php echo base_url();?>assets/admin/js/feather.min.js"></script>
<script src="<?php echo base_url();?>assets/admin/js/app.js"></script>
<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<!-- DataTables JS -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js">
</script>
<script>
$(document).ready(function() {
    // When the "Update Quantities" button is clicked
    $('.update-order-quantities').click(function() {
        var orderId = $(this).data('order-id'); // Get the order ID

        // Find all quantity inputs for products within this order
        var updatedItems = [];

        $('#collapseOrder' + orderId + ' .product-item').each(function() {
            var productId = $(this).find('input[type="number"]').attr('id').replace('quantity',
                ''); // Extract product ID
            var quantity = parseInt($(this).find('input[type="number"]')
        .val()); // Get the quantity value

            updatedItems.push({
                productId: productId,
                quantity: quantity
            });
        });

        // Send updated quantities to the server using AJAX
        $.ajax({
            url: '<?= base_url("owner/order/test1"); ?>',
            method: 'POST',
            data: {
                updatedItems: // Send updated items array to the server
            },
            success: function(response) {
                alert(response);
            },
            error: function(xhr, status, error) {
                console.log('Error updating cart:', error);
            }
        });
    });

    // Function to update the cart UI
    function updateCartUI(cart) {
        var total = 0;
        var itemCount = 0;

        $.each(cart, function(index, item) {
            total += item.price * item.quantity;
            itemCount += item.quantity;
        });

        $('#cartCount').text(itemCount);
        $('#cartTotal').text('$' + total.toFixed(2));
    }
});
</script>
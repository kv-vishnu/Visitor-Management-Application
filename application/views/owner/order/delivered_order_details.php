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
<link href="<?php echo base_url();?>assets/admin/css/overiding-style.css" id="app-style" rel="stylesheet"
    type="text/css" />
<link href="<?php echo base_url();?>assets/admin/sass/overiding-style.css" id="app-style" rel="stylesheet"
    type="text/css" />











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


    <input type="hidden" id="table_id" value="<?php echo $table_id;?>">

    <div class="">
        <div class="table-responsive-sm">



            <form class="row g-3">
                <!-- Product ID Field -->
                <div class="col-md-3 d-none">
                    <label for="productId" class="form-label">Product ID</label>
                    <input type="text" class="form-control" id="productId" name="productId"
                        placeholder="Enter Product ID">
                </div>

                <!-- Date Field -->
                <div class="col-md-3 d-none">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" class="form-control" id="order-date" name="date">
                </div>


            </form>

            <div id="orders-container">
                <!-- Orders will be displayed here -->
            </div>


            <div id="orders-containersss">
                <!-- Orders will be displayed here -->
            </div>









        </div>
    </div>
</div>








<!-- KOT Modal -->
<div class="modal fade" id="kotModal" tabindex="-1" aria-labelledby="kotModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="kotModalLabel">Kitchen Order Ticket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="kotContent">
                <!-- KOT details will be loaded here -->
                <p>Print contents here</p>
            </div>
            <div class="modal-footer text-center">
                <button type="button" class="btn btn-secondary close_window_print"
                    data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" onclick="printKOT()">Print</button>
            </div>
        </div>
    </div>
</div>
</div>


<!-- Modal for printing -->
<div class="modal fade" id="printModal" tabindex="-1" aria-labelledby="printModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="printModalLabel">Print Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalContent">
                <!-- Content will be loaded here dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close_window_print"
                    data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" onclick="printPage()">Print</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal for printing -->







</div>

<!-- JAVASCRIPT -->
<script src="<?php echo base_url();?>assets/admin/js/jquery.min.js"></script>
<script src="<?php echo base_url();?>assets/admin/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {

    var base_url = $('#base_url').val();



    $(document).on("click", ".kot_table_order", function() {
        var orderId = $(this).data("order-id");
        var is_kot_enable = $(this).data("kot-enable");
        if (is_kot_enable == 1) {
            $('#kotModal').modal('show');
            $.ajax({
                url: '<?= base_url("owner/order/getKotPrintOrderItems"); ?>',
                method: 'POST',
                data: {
                    order_no: orderId,
                },
                success: function(response) {
                    $('#kotContent').html(response);
                }
            });
        }
    });


    $(document).on("click", ".pay_table_order", function() {
        var orderId = $(this).data("order-id");
        $.ajax({
            url: '<?= base_url("owner/order/pay_order"); ?>',
            type: "POST",
            data: {
                orderId: orderId
            },
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    $(".msgContainer" + orderId).removeClass("d-none");
                    $("#ordermsg" + orderId).removeClass("d-none");
                    $("#ordermsg" + orderId).text("Order Paid successfully.");
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                }
            },
        });
    });

    $(document).on("click", ".dining_order", function() {
        var orderId = $(this).data("order-id");
        $.ajax({
            url: '<?= base_url("owner/order/dining_order"); ?>',
            type: "POST",
            data: {
                orderId: orderId
            },
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    $(".msgContainer" + orderId).removeClass("d-none");
                    $("#ordermsg" + orderId).removeClass("d-none");
                    $("#ordermsg" + orderId).text("Order delivered successfully.");
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                }
            },
        });
    });

    $(document).on("click", ".pay_order_print", function() {
        var orderId = $(this).data("order-id");
        $.ajax({
            url: '<?= base_url("owner/order/PrintOrderItems"); ?>',
            method: 'POST',
            data: {
                order_no: orderId
            },
            success: function(response) {
                $("#modalContent").html(response);
                $("#printModal").modal('show');
            }
        });
    });


    function show_message_modal(message) {
        $("#message_modal").modal("show");
        $(".message_modal_description").text(message);
        //location.reload();
    }
    const order_id = $('#table_id').val(); //Display order id
    //alert(table_id);

    function fetchOrders() {
        $.ajax({
            url: '<?= base_url("owner/order_kitchen/delivered_order_details"); ?>',
            method: 'POST',
            data: {
                order_id: order_id
            },
            success: function(response) {
                $('#orders-container').html(response);
            },
            error: function(xhr, status, error) {
                $('#orders-container').html('<p>Error fetching orders.</p>');
                console.error(error);
            }
        });
    }

    fetchOrders();
    // fetchOrders(currentDate);
});
</script>

<script>
$(document).ready(function() {
    $(".quantity").on("keyup", function() {
        const $row = $(this).closest("tr"); // Current table row
        const price = parseFloat($row.find(".price").text()); // Price in the row
        const quantity = parseInt($(this).val()) || 0; // Get quantity or fallback to 0
        const $rowTotal = $row.find(".row-total"); // Row total cell

        // Update row total
        const rowTotal = price * quantity;
        $rowTotal.text(rowTotal.toFixed(2));

        // Update grand total
        updateGrandTotal();
    });

    function updateGrandTotal() {
        let grandTotal = 0;

        // Sum up all row totals
        $(".row-total").each(function() {
            grandTotal += parseFloat($(this).text()) || 0;
        });

        // Update grand total
        $("#grandTotal").text(grandTotal.toFixed(2));
    }
});

function printKOT() {
    $('#kotModal').modal('hide');
    $('#recipe').modal('hide');
    var printContents = document.getElementById("kotContent").innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    location.reload();

    printWindow.onload = function() {
        printWindow.document.getElementById("cancelBtn").onclick = function() {
            window.opener.location.reload(); // Reload parent page
            printWindow.close(); // Close the print window
        };
    };
}

function printPage() {
    $('#printModal').modal('hide');
    var printContents = document.getElementById("modalContent").innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    location.reload();

    printWindow.onload = function() {
        printWindow.document.getElementById("cancelBtn").onclick = function() {
            window.opener.location.reload(); // Reload parent page
            printWindow.close(); // Close the print window
        };
    };
}
</script>
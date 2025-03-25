<input type="hidden" id="base_url" value="<?php echo base_url();?>">
<link href="<?php echo base_url();?>assets/admin/css/crm-responsive.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>assets/admin/css/classic.min.css" rel="stylesheet" /> <!-- 'classic' theme -->
<link href="<?php echo base_url();?>assets/admin/fonts/css/all.min.css" rel="stylesheet" />
<link href="<?php echo base_url();?>assets/admin/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet"
    type="text/css" />
<link href="<?php echo base_url();?>assets/admin/css/icon.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>assets/admin/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
<!-- <link href="<?php echo base_url();?>assets/admin/css/styles.css" id="styles" rel="stylesheet" type="text/css" /> -->
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



    <div class="">
        <div class="table-responsive-sm">


            <input type="hidden" id="store_product_id" name="store_product_id" value="<?php echo $store_product_id; ?>">
            <input type="hidden" id="taxRate" name="taxRate" value="<?php echo $default_tax_rate; ?>">


            <div class="container">
                <div class="row align-items-center">
                    <!-- Dropdown column -->



                </div>
            </div>


            <table id="examplee" class="table table-striped mt-3" style="width:100%">
                <thead style="background: #e5e5e5;">
                    <tr>
                        <th>No</th>
                        <th>Select</th>
                        <th>Name</th>
                        <th>Rate</th>
                        <th>Is Default</th>
                        <th>Value</th>
                        <th>Is Active</th>
                    </tr>
                </thead>

                <tbody>

                    <?php
                       if(!empty($variants)){
                       $count = 1;
                       foreach($variants as $val){
            $query = $this->db->query("SELECT rate,variant_value, tax, tax_amount, total_amount ,is_default, is_active FROM store_variants WHERE variant_id = ".$val['variant_id']." AND store_id = ".$this->session->userdata('logged_in_store_id')." AND store_product_id = ".$store_product_id."");
            $variantDetails = $query->result_array();
            $hasVariantDetails = !empty($variantDetails);
            //print_r($variantDetails);
                        ?>
                    <tr>
                        <td><?php echo $count;?></td>
                        <td>
                            <input type="checkbox" class="check-item" name="variant_id"
                                value="<?php echo $val['variant_id']; ?>"
                                <?php echo in_array($val['variant_id'], $already_assigned_variants_ids) ? 'checked' : ''; ?>>
                        </td>
                        <td><?php echo $val['variant_name'];?></td>
                        <td><input type="text" class="form-control"
                                value="<?php echo isset($variantDetails[0]['rate']) ? $variantDetails[0]['rate'] : ''; ?>"
                                name="product_price" style="width: 50%;"></td>
                        <td class="d-none">
                            <select class="form-select" name="tax" id="" style="width: 80%;">
                                <option value="0"
                                    <?php echo (isset($variantDetails[0]['tax']) && $variantDetails[0]['tax'] == 0) ? 'selected' : ''; ?>>
                                    0</option>
                                <?php 
                      foreach($store_taxes as $tax) { 
                        if(isset($val['tax'])){
                          $selected = (isset($val['tax']) && $tax['tax_rate'] == $variantDetails[0]['tax']) ? 'selected' : '';
                        }else{
                          $selected = (isset($variantDetails[0]['tax']) && $tax['tax_rate'] == $variantDetails[0]['tax']) ? 'selected' : '';
                      }
                      ?>
                                <option value="<?php echo $tax['tax_rate']; ?>" <?php echo $selected; ?>>
                                    <?php echo $tax['tax_rate']; ?>
                                </option>
                                <?php } ?>
                            </select>
                        </td>
                        <td class="d-none">
                            <input type="text" class="form-control" style="width: 50%;"
                                value="<?php echo isset($variantDetails[0]['tax_amount']) ? $variantDetails[0]['tax_amount'] : ''; ?>"
                                name="tax_amount">
                        </td>
                        <td class="d-none">
                            <input type="text" class="form-control" style="width: 50%;"
                                value="<?php echo isset($variantDetails[0]['total_amount']) ? $variantDetails[0]['total_amount'] : ''; ?>"
                                name="total_amount">
                        </td>
                        <td>
                            <?php if($hasVariantDetails){
                        $ischecked =  $variantDetails[0]['is_default'] == 1 ? 1 : 0;
                    }else{
                        $ischecked = 0;
                    } ?>
                            <input type="checkbox" class="is_default" name="is_default"
                                value="<?php echo $ischecked; ?>"
                                <?php echo $hasVariantDetails && $variantDetails[0]['is_default'] == 1 ? 'checked' : ''; ?>>
                        </td>
                        <td><input type="text" class="form-control"
                                value="<?php echo isset($variantDetails[0]['variant_value']) ? $variantDetails[0]['variant_value'] : ''; ?>"
                                name="variant_value" style="width: 50%;"></td>
                        <td>
                            <select name="is_active" class="form-select" id="" style="width: 80%;">
                                <option value="1"
                                    <?php echo (isset($variantDetails[0]['is_active']) && $variantDetails[0]['is_active'] == 1) ? 'selected' : ''; ?>>
                                    Active</option>
                                <option value="0"
                                    <?php echo (isset($variantDetails[0]['is_active']) && $variantDetails[0]['is_active'] == 0) ? 'selected' : ''; ?>>
                                    Inactive</option>
                            </select>
                        </td>
                    </tr>
                    <?php $count++; }} ?>



                </tbody>
                <tfoot>
                    <tr>

                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>

                    </tr>
                </tfoot>
            </table>
            <!-- Button column -->
            <div class="col-auto">
                <a class="btn6-small" data-id="<?php echo $store_product_id; ?>" id="add_variant" disabled>Add</a>
                <a class="btn6-small" id="update_variant" disabled>Update</a>
            </div>











        </div>
    </div>
</div>





<!-- Modal add variant -->
<div class="modal fade" id="addVariantModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">ADD VARIANT</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="variantForm" method="post" enctype="multipart/form-data">

                    <input type="hidden" id="product_id" name="product_id" value="">
                    <label class="col-sm-12 col-form-label">Variant Name</label>
                    <input type="text" class="form-control" id="variant_name" name="variant_name" value="">
                    <span class="error errormsg mt-2" id="variant_name_error"></span>
                    <label class="col-sm-12 col-form-label">Code</label>
                    <input type="text" class="form-control" id="variant_code" name="variant_code" value="">
                    <span class="error errormsg mt-2" id="variant_code_error"></span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="save_variant" data-bs-dismiss="SAVE">SAVE</button>
            </div>
            </form>
        </div>
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
<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<!-- DataTables JS -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js">
</script>


<script>
$(document).ready(function() {
    $('.is_default').on('change', function() {
        $('.is_default').not(this).prop('checked', false);
    });
});
</script>

<script>
$(document).ready(function() {
    new DataTable('#example');
});
</script>

<script>
$(document).ready(function() {
    $('#category_id').change(function() {
        $('#form_product_display').submit();
    });
});
</script>
<script>
$(document).ready(function() {



    $("#add_variant").on("click", function() {
        $("#addVariantModal").modal("show");
        $('#product_id').val($(this).attr('data-id'));
    });


    $('#save_variant').click(function() {
        let formData = new FormData($('#variantForm')[
            0]); // Capture the form data, including files

        $.ajax({
            url: '<?= base_url("owner/Variants/add") ?>', // URL to the controller method
            type: 'POST',
            data: formData,
            dataType: 'json',
            processData: false, // Prevent jQuery from processing the data
            contentType: false, // Prevent jQuery from setting the Content-Type header
            success: function(response) {
                console.log(response);

                if (response.errors) {
                    if (response.errors.variant_name) {
                        $('#variant_name_error').html(response.errors
                            .variant_name);
                    } else if (response.errors.variant_code) {
                        $('#variant_code_error').html(response.errors
                            .variant_code);
                    }
                } else {
                    location.reload();
                }
            },
            error: function(xhr) {
                $('#response').html('<p>An error occurred: ' + xhr
                    .responseText +
                    '</p>');
            }
        });
    });


    // auto changed tax amount and total amount
    $('body').on('input', 'input[name="product_price"], select[name="tax"]', function() {
        const row = $(this).closest('tr');
        const rate = parseFloat(row.find('input[name="product_price"]').val()) || 0;
        const taxPercent = $('#taxRate').val();

        // Calculate tax amount and total amount
        const taxAmount = (rate * taxPercent) / 100;
        const totalAmount = rate + taxAmount;

        // Set calculated values to respective fields
        row.find('input[name="tax_amount"]').val(taxAmount.toFixed(2));
        row.find('input[name="total_amount"]').val(totalAmount.toFixed(2));
    });

});
</script>

<script>
$('#update_variant').click(function() {
    const selectedProducts = [];

    $('#examplee tbody tr').each(function() {
        if ($(this).find('input[type="checkbox"]').is(':checked')) {
            const row = $(this);
            const productData = {
                store_product_id: $('#store_product_id').val(),
                variant_id: row.find('input[type="checkbox"]').val(),
                rate: parseFloat(row.find('input[name="product_price"]').val()) || 0,
                variant_value: row.find('input[name="variant_value"]').val() || 0,
                tax: $('#taxRate').val(),
                is_active: parseFloat(row.find('select[name="is_active"]').val()) || 0,
                tax_amount: parseFloat(row.find('input[name="tax_amount"]').val()) || 0,
                is_default: row.find('input[name="is_default"]').prop('checked') ? 1 : 0,
                total_amount: parseFloat(row.find('input[name="total_amount"]').val()) || 0,
            };
            selectedProducts.push(productData);
            console.log(selectedProducts);
        }
    });

    // Send selectedProducts to CodeIgniter controller via AJAX
    $.ajax({
        url: '<?= base_url("owner/product/update_selected_variant"); ?>', // Controller method URL
        type: 'POST',
        data: {
            products: selectedProducts
        },
        dataType: 'json',
        success: function(response) {
            console.log(response);

            if (response.status === 'success') {
                $('.message').removeClass('d-none');
                $('.message').removeClass('alert alert-danger');
                $('.message').addClass('alert alert-success');
                $('.message').text('Product variant updated successfully.');
                setTimeout(function() {
                    location.reload();
                }, 1000); // 3000 ms = 3 seconds
                // Reload the page if necessary
            } else {
                $('.message').removeClass('d-none');
                $('.message').addClass('alert alert-danger');
                $('.message').text('Please select at least one checkbox for update.');
            }
        },
        error: function() {
            $('.message').removeClass('d-none');
            $('.message').addClass('alert alert-danger');
            $('.message').text('Please select at least one checkbox for update.');
        }
    });
});
</script>
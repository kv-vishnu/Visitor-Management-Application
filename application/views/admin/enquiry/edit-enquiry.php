<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="main-content">
    <div class="page-content">




        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Edit Product</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/dashboard">Home</a>
                                </li>
                                <i class="fa-solid fa-chevron-right "
                                    style="font-size: 9px;margin: 6px 5px 0px 5px;"></i>
                                <li class="breadcrumb-item active">Edit Product</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->






            <div class="row">


                <form method="post" action="<?php echo base_url(); ?>owner/product/update"
                    enctype="multipart/form-data">
                    <input type="hidden" name="id"
                        value="<?php  if(isset($productDet[0]['product_id'])){echo $productDet[0]['product_id'];}?>">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <!-- <li class="nav-item" role="presentation">
      <button class="nav-link active" id="store-tab" data-bs-toggle="tab" data-bs-target="#store" type="button" role="tab" aria-controls="store" aria-selected="true">Store</button>
    </li> -->
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="store" role="tabpanel" aria-labelledby="store-tab">
                            <div class="row">








                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-body">







                                            <div class="form-group row mb-2">
                                                <label class="col-sm-2 col-form-label">Category</label>
                                                <div class="col-sm-3">
                                                    <select class="form-select" name="category_id">
                                                        <option value="">Select Category</option>
                                                        <?php
                                foreach($categories as $category)
                                {
                                ?>
                                                        <option value="<?=$category['category_id'];?>"
                                                            <?php if(isset($productDet[0]['category_id']) && ($productDet[0]['category_id']==$category['category_id'])) echo 'selected';else echo set_select('category_id', $category['category_id'])?>>
                                                            <?=$category['category_name_en'];?></option>
                                                        <?php
                                }
                                ?>
                                                    </select>
                                                    <?php if(form_error('category_id')){ ?>
                                                    <div class="errormsg mt-2" role="alert">
                                                        <?php echo form_error('category_id'); ?></div>
                                                    <?php } ?>
                                                </div>


                                                <label class="col-sm-2 col-form-label">SubCategory </label>
                                                <div class="col-sm-3">
                                                    <select class="form-select" name="subcategory_id">
                                                        <option value="">Select Category</option>
                                                        <?php
                                                        
    foreach($subcategories as $category)
    {
    ?>
                                                        <option value="<?=$category['subcategory_id'];?>"
                                                            <?php if(isset($productDet[0]['subcategory_id']) && ($productDet[0]['subcategory_id']==$category['subcategory_id'])) echo 'selected';else echo set_select('subcategory_id', $category['subcategory_id'])?>>
                                                            <?=$category['subcategory_name_en'];?></option>
                                                        <?php
    }
    ?>
                                                    </select>
                                                    <?php if(form_error('subcategory_id')){ ?>
                                                    <div class="errormsg mt-2" role="alert">
                                                        <?php echo form_error('subcategory_id'); ?></div>
                                                    <?php } ?>
                                                </div>











                                            </div>




                                            <div class="form-group row mb-2">

                                                <label class="col-sm-2 col-form-label">Veg / Non Veg</label>
                                                <div class="col-sm-3">
                                                    <select class="form-select" name="product_veg_nonveg">
                                                        <option value="">Select Type</option>
                                                        <option value="veg"
                                                            <?php if(isset($productDet[0]['product_veg_nonveg']) && $productDet[0]['product_veg_nonveg']=='veg'){echo 'selected'; }else{ echo set_select('product_veg_nonveg', 'veg'); } ?>>
                                                            Veg</option>
                                                        <option value="non-veg"
                                                            <?php if(isset($productDet[0]['product_veg_nonveg']) && $productDet[0]['product_veg_nonveg']=='non-veg'){echo 'selected'; }else{ echo set_select('product_veg_nonveg', 'non-veg'); }?>>
                                                            Non-veg</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label class="col-sm-12 col-form-label">Is Customizable</label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label class="col-sm-12 col-form-label">
                                                        <input type="hidden" name="iscustomizable_hidden"
                                                            value="<?php echo ($productDet[0]['is_customizable'] == 1) ? '1' : '0'; ?>"
                                                            id="iscustomizable_hidden">
                                                        <input class="form-check-input" type="checkbox"
                                                            <?php echo ($productDet[0]['is_customizable'] == 1) ? 'checked' : ''; ?>
                                                            id="checkbox_is_customizable">
                                                    </label>
                                                </div>
                                                <div class="col-sm-1">
                                                    <label class="col-sm-12 col-form-label">Is Addon</label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label class="col-sm-12 col-form-label">
                                                        <input type="hidden" name="isaddon_hidden"
                                                            value="<?php echo ($productDet[0]['is_addon'] == 1) ? '1' : '0'; ?>"
                                                            id="isaddon_hidden">
                                                        <input class="form-check-input" type="checkbox"
                                                            <?php echo ($productDet[0]['is_addon'] == 1) ? 'checked' : ''; ?>
                                                            id="checkbox_is_addon">
                                                    </label>
                                                </div>


                                            </div>

                                            <div class="form-group row mb-2 mt-2">

                                                <label class="col-sm-2 col-form-label">Rate</label>
                                                <div class="col-sm-2">
                                                    <input type="text" class="form-control"
                                                        value="<?php echo isset($productDet[0]['rate']) ? $productDet[0]['rate'] : ''; ?>"
                                                        id="rate" name="rate" style="width:100%;">
                                                    <span id="rate_error"
                                                        class="error errormsg mt-2"><?= form_error('rate'); ?></span>
                                                </div>
                                                <div class="col-sm-1">
                                                    <label class="col-sm-12 col-form-label">Tax</label>
                                                </div>
                                                <div class="col-sm-1">
                                                    <select class="form-select" name="tax" id="tax" style="width: 80%;">
                                                        <option value="0"
                                                            <?php echo (isset($default_tax_rate) && $default_tax_rate == 0) ? 'selected' : ''; ?>>
                                                            0</option>
                                                        <?php 
foreach($store_taxes as $tax) { 
if(isset($val['tax'])){
$selected = (isset($val['tax']) && $tax['tax_rate'] == $val['tax']) ? 'selected' : '';
}else{
$selected = (isset($default_tax_rate) && $tax['tax_rate'] == $default_tax_rate) ? 'selected' : '';
}
?>
                                                        <option value="<?php echo $tax['tax_rate']; ?>"
                                                            <?php echo $selected; ?>>
                                                            <?php echo $tax['tax_rate']; ?>
                                                        </option>
                                                        <?php } ?>
                                                    </select>
                                                    <span id="tax_error"
                                                        class="error errormsg mt-2"><?= form_error('tax'); ?></span>
                                                </div>
                                                <div class="col-sm-1">
                                                    <label class="col-sm-12 col-form-label">Tax amount</label>
                                                </div>
                                                <div class="col-sm-1">
                                                    <input type="text" class="form-control"
                                                        value="<?php echo isset($productDet[0]['tax_amount']) ? $productDet[0]['tax_amount'] : ''; ?>"
                                                        name="tax_amount" id="taxAmount" style="width: 100%;">
                                                </div>
                                                <div class="col-sm-1">
                                                    <label class="col-sm-12 col-form-label">Total amount</label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <input type="text" class="form-control"
                                                        value="<?php echo isset($productDet[0]['total_amount']) ? $productDet[0]['total_amount'] : ''; ?>"
                                                        id="totalAmount" name="total_amount" style="width: 100%;">
                                                </div>


                                            </div>


                                            <div class="form-group row mb-2">

                                                <label class="col-sm-2 col-form-label"></label>
                                                <div class="col-sm-2">
                                                    <label class="col-sm-12 col-form-label">Malayalam</label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label class="col-sm-12 col-form-label">English</label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label class="col-sm-12 col-form-label">Hindi</label>
                                                </div>
                                                <div class="col-sm-2" style="left:150px;">
                                                    <label class="col-sm-12 col-form-label">Arabic</label>
                                                </div>


                                            </div>


                                            <div class="form-group row mb-2">

                                                <label class="col-sm-2 col-form-label">Product Name</label>
                                                <div class="col-sm-2">
                                                    <input class="form-control"
                                                        value="<?php if(set_value('product_name_ma')){echo set_value('product_name_ma');}else if(isset($productDet[0]['product_name_ma'])){echo $productDet[0]['product_name_ma'];}?>"
                                                        type="text" placeholder="Malayalam" name="product_name_ma">
                                                    <?php if(form_error('product_name_ma')){ ?>
                                                    <div class="errormsg mt-2" role="alert">
                                                        <?php echo form_error('product_name_ma'); ?></div>
                                                    <?php } ?>
                                                </div>
                                                <div class="col-sm-2">
                                                    <input class="form-control"
                                                        value="<?php if(set_value('product_name_en')){echo set_value('product_name_en');}else if(isset($productDet[0]['product_name_en'])){echo $productDet[0]['product_name_en'];}?>"
                                                        type="text" placeholder="English" name="product_name_en">
                                                    <?php if(form_error('product_name_en')){ ?>
                                                    <div class="errormsg mt-2" role="alert">
                                                        <?php echo form_error('product_name_en'); ?></div>
                                                    <?php } ?>
                                                </div>
                                                <div class="col-sm-3">
                                                    <input class="form-control"
                                                        value="<?php if(set_value('product_name_hi')){echo set_value('product_name_hi');}else if(isset($productDet[0]['product_name_hi'])){echo $productDet[0]['product_name_hi'];}?>"
                                                        type="text" placeholder="Hindi" name="product_name_hi">
                                                    <?php if(form_error('product_name_hi')){ ?>
                                                    <div class="errormsg mt-2" role="alert">
                                                        <?php echo form_error('product_name_hi'); ?></div>
                                                    <?php } ?>
                                                </div>
                                                <div class="col-sm-3">
                                                    <input class="form-control"
                                                        value="<?php if(set_value('product_name_ar')){echo set_value('product_name_ar');}else if(isset($productDet[0]['product_name_ar'])){echo $productDet[0]['product_name_ar'];}?>"
                                                        type="text" placeholder="Arabic" name="product_name_ar">
                                                    <?php if(form_error('product_name_ar')){ ?>
                                                    <div class="errormsg mt-2" role="alert">
                                                        <?php echo form_error('product_name_ar'); ?></div>
                                                    <?php } ?>
                                                </div>

                                            </div>



                                            <div class="form-group row mb-2">

                                                <label class="col-sm-2 col-form-label">Description</label>
                                                <div class="col-sm-2">
                                                    <textarea name="product_desc_ma" class="form-control"
                                                        id="exampleFormControlTextarea4" placeholder="Malayalam"
                                                        rows=""><?php if(set_value('product_desc_ma')){echo set_value('product_desc_ma');}else if(isset($productDet[0]['product_desc_ma'])){echo $productDet[0]['product_desc_ma'];}?></textarea>
                                                    <?php if(form_error('product_desc_ma')){ ?>
                                                    <div class="errormsg mt-2" role="alert">
                                                        <?php echo form_error('product_desc_ma'); ?></div>
                                                    <?php } ?>
                                                </div>
                                                <div class="col-sm-2">
                                                    <textarea name="product_desc_en" class="form-control"
                                                        id="exampleFormControlTextarea4" placeholder="English"
                                                        rows=""><?php if(set_value('product_desc_en')){echo set_value('product_desc_en');}else if(isset($productDet[0]['product_desc_en'])){echo $productDet[0]['product_desc_en'];}?></textarea>
                                                    <?php if(form_error('product_desc_en')){ ?>
                                                    <div class="errormsg mt-2" role="alert">
                                                        <?php echo form_error('product_desc_en'); ?></div>
                                                    <?php } ?>
                                                </div>
                                                <div class="col-sm-3">
                                                    <textarea name="product_desc_hi" class="form-control"
                                                        id="exampleFormControlTextarea4" placeholder="Hindi"
                                                        rows=""><?php if(set_value('product_desc_hi')){echo set_value('product_desc_hi');}else if(isset($productDet[0]['product_desc_hi'])){echo $productDet[0]['product_desc_hi'];}?></textarea>
                                                    <?php if(form_error('product_desc_hi')){ ?>
                                                    <div class="errormsg mt-2" role="alert">
                                                        <?php echo form_error('product_desc_hi'); ?></div>
                                                    <?php } ?>
                                                </div>
                                                <div class="col-sm-3">
                                                    <textarea name="product_desc_ar" class="form-control"
                                                        id="exampleFormControlTextarea4" placeholder="Arabic"
                                                        rows=""><?php if(set_value('product_desc_ar')){echo set_value('product_desc_ar');}else if(isset($productDet[0]['product_desc_ar'])){echo $productDet[0]['product_desc_ar'];}?></textarea>
                                                    <?php if(form_error('product_desc_ar')){ ?>
                                                    <div class="errormsg mt-2" role="alert">
                                                        <?php echo form_error('product_desc_ar'); ?></div>
                                                    <?php } ?>
                                                </div>

                                            </div>






















                                            <div class="form theme-form">




                                                <!-- row start -->
                                                <div class="row">











                                                </div>
                                                <!-- row end -->


                                            </div>




                                        </div>
                                    </div>
                                </div>




                                <input type="hidden" name="image" id="imghidden1"
                                    value="<?php if(isset($productDet[0]['image'])) echo $productDet[0]['image']; ?>">
                                <input type="hidden" id="imghidden2" name="image1"
                                    value="<?php if(isset($productDet[0]['image1'])) echo $productDet[0]['image1']; ?>">
                                <input type="hidden" id="imghidden3" name="image2"
                                    value="<?php if(isset($productDet[0]['image2'])) echo $productDet[0]['image2']; ?>">
                                <input type="hidden" id="imghidden4" name="image3"
                                    value="<?php if(isset($productDet[0]['image3'])) echo $productDet[0]['image3']; ?>">
                                <input type="hidden" id="imghidden5" name="image4"
                                    value="<?php if(isset($productDet[0]['image4'])) echo $productDet[0]['image4']; ?>">






                            </div>
                        </div>

                        <!-- <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
fffffffffff
  </div> -->
                    </div>
                    <button class="btn btn-primary pull-right mb-4" type="submit" name="edit">Save</button>
                </form>



            </div>



            <div id="image-container" style="display: flex; flex-wrap: wrap; gap: 10px;">
                <!-- Image Previews and File Inputs -->
                <div class="image-item">
                    <img id="previewImage1"
                        src="<?php echo base_url(); ?>uploads/product/<?php if(isset($productDet[0]['image'])) echo $productDet[0]['image']; ?>"
                        width="200" class="image-to-crop" />
                    <input type="file" class="form-control" id="preview1" />
                </div>
                <div class="image-item">
                    <img id="previewImage2"
                        src="<?php echo base_url(); ?>uploads/product/<?php if(isset($productDet[0]['image1'])) echo $productDet[0]['image1']; ?>"
                        width="200" class="image-to-crop" />
                    <input type="file" class="form-control" id="preview2" />
                </div>
                <div class="image-item">
                    <img id="previewImage3"
                        src="<?php echo base_url(); ?>uploads/product/<?php if(isset($productDet[0]['image2'])) echo $productDet[0]['image2']; ?>"
                        width="200" class="image-to-crop" />
                    <input type="file" class="form-control" id="preview3" />
                </div>
                <div class="image-item">
                    <img id="previewImage4"
                        src="<?php echo base_url(); ?>uploads/product/<?php if(isset($productDet[0]['image3'])) echo $productDet[0]['image3']; ?>"
                        width="200" class="image-to-crop" />
                    <input type="file" class="form-control" id="preview4" />
                </div>
                <div class="image-item">
                    <img id="previewImage5"
                        src="<?php echo base_url(); ?>uploads/product/<?php if(isset($productDet[0]['image4'])) echo $productDet[0]['image4']; ?>"
                        width="200" class="image-to-crop" />
                    <input type="file" class="form-control" id="preview5" />
                </div>
            </div>


            <!-- Modal for Image Cropping -->
            <div id="cropper-modal" class="modal" tabindex="-1" role="dialog" style="display: none;">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Crop Image</h5>
                        </div>
                        <div class="modal-body">
                            <input type="text" id="hiddenImgId">
                            <img id="image-to-crop-modal" src="" alt="image-to-crop" />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="crop-button">Crop and Update</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Cropper.js & jQuery -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

            <script>
            $(document).ready(function() {
                var cropper;

                // Trigger cropper modal when image is clicked
                $('.image-to-crop').click(function() {
                    var imageSrc = $(this).attr('src');
                    var dataId = $(this).attr('id');
                    $('#image-to-crop-modal').attr('src', imageSrc);

                    // Destroy existing cropper if any
                    if (cropper) {
                        cropper.destroy();
                    }

                    // Initialize the cropper on the modal image
                    $('#cropper-modal').modal('show');
                    $('#hiddenImgId').val(dataId);
                    var image = document.getElementById('image-to-crop-modal');
                    cropper = new Cropper(image, {
                        aspectRatio: 1, // Optional: change aspect ratio if needed
                        viewMode: 1,
                        scalable: true,
                        zoomable: true,
                        movable: true
                    });
                });

                // Crop the image and upload
                $('#crop-button').click(function() {
                    var croppedCanvas = cropper.getCroppedCanvas();
                    var croppedImage = croppedCanvas.toDataURL('image/jpeg'); // Get cropped image data
                    var fileName = 'cropped-image-' + new Date().getTime() + '.jpg';
                    $.ajax({
                        url: '<?= base_url("owner/Product/update_image") ?>',
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            image: croppedImage,
                            imageId: $('#hiddenImgId').val(),
                            file_name: fileName // Send file name for saving
                        },
                        success: function(response) {
                            console.log(response);

                            $('.image-to-crop[src="' + $('#image-to-crop-modal').attr(
                                    'src') + '"]')
                                .attr('src', croppedImage);

                            // Hide the modal after updating
                            $('#cropper-modal').modal('hide');
                            if (response.imageId == 'previewImage1') {
                                $('#imghidden1').val(response.filename);
                            }
                            if (response.imageId == 'previewImage2') {
                                $('#imghidden2').val(response.filename);
                            }
                            if (response.imageId == 'previewImage3') {
                                $('#imghidden3').val(response.filename);
                            }
                            if (response.imageId == 'previewImage4') {
                                $('#imghidden4').val(response.filename);
                            }
                            if (response.imageId == 'previewImage5') {
                                $('#imghidden5').val(response.filename);
                            }
                        },
                        error: function() {
                            alert('Failed to update the image.');
                        }
                    });
                });

                // Handle real-time image preview when files are selected
                function previewImage(inputId, previewImageId, imgHidden) {
                    $(inputId).on('change', function(event) {
                        const file = event.target.files[0];
                        if (file) {
                            const filename = file.name;
                            $(imgHidden).val(filename);
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                $(previewImageId).attr('src', e.target
                                    .result); // Update the preview image
                            };
                            reader.readAsDataURL(file); // Read the file as a data URL
                            var formData = new FormData();
                            formData.append("file", file);
                            $.ajax({
                                url: '<?= base_url("owner/Product/update_image1") ?>',
                                method: 'POST',
                                data: formData,
                                contentType: false, // Don't set contentType
                                processData: false,
                                success: function(response) {}
                            });
                        }
                    });
                }

                // Bind preview image functionality to file inputs
                previewImage('#preview1', '#previewImage1', '#imghidden1');
                previewImage('#preview2', '#previewImage2', '#imghidden2');
                previewImage('#preview3', '#previewImage3', '#imghidden3');
                previewImage('#preview4', '#previewImage4', '#imghidden4');
                previewImage('#preview5', '#previewImage5', '#imghidden5');
            });
            </script>
            <script>
            $(document).ready(function() {
                $('#checkbox_is_customizable').on('click', function() {
                    if ($(this).is(':checked')) {
                        $('#iscustomizable_hidden').val(1);
                    } else {
                        $('#iscustomizable_hidden').val(0);
                    }
                });

                $('#checkbox_is_addon').on('click', function() {
                    if ($(this).is(':checked')) {
                        $('#isaddon_hidden').val(1);
                    } else {
                        $('#isaddon_hidden').val(0);
                    }
                });
            })
            </script>
            <script>
            const rateInput = document.getElementById('rate');
            const taxInput = document.getElementById('tax');
            const taxAmountInput = document.getElementById('taxAmount');
            const totalAmountInput = document.getElementById('totalAmount');

            function calculateTotal() {
                const rate = parseFloat(rateInput.value) || 0;
                const tax = parseFloat(taxInput.value) || 0;

                const taxAmount = (rate * tax) / 100;
                const totalAmount = rate + taxAmount;

                taxAmountInput.value = taxAmount.toFixed(2);
                totalAmountInput.value = totalAmount.toFixed(2);
            }

            rateInput.addEventListener('input', calculateTotal);
            taxInput.addEventListener('input', calculateTotal);
            </script>
<div class="application-content dashboard-content">
    <div class="application-content__container container">
        <h1 class="application-content__page-heading">Enquiries</h1>

        <div class="search-add-new-dish-list-combo">
            <form class="product-search__form search">
                <input type="text" id="search_product" placeholder="Search for a product" name="search"
                    class="product-search__field search-input1">
                <button type="submit" class="product-search__button"><img
                        src="<?php echo base_url(); ?>assets/admin/images/product-search-icon.svg" width="22"
                        height="23" alt="SearchIcon" class="product-search__icon"></button>
                <ul id="autocomplete-results1" class="autocomplete-results">
                </ul>
            </form>
            <div class="add-new-dish-list-combo">
                <a href="<?php echo base_url('admin/enquiry/add'); ?>" class="add-new-dish-btn btn1">
                    <img src="<?php echo base_url(); ?>assets/admin/images/add-new-dish-icon.svg" alt="add new dish"
                        class="add-new-dish__icon" width="23" height="23">
                    New Enquiry
                </a>
            </div>

        </div>
        <div class="product-list" id="search_result_container">

            <?php
            if(!empty($enquiries)){
                $count = 1;
                foreach($enquiries as $val){
 ?>
            <div class="product-list__item">
                <div class="product-list__item-image-and-details">
                    <div class="product-list__item-details">
                        <h3 class="product-list__item-name">
                            <?php echo $val['visitor_name']; ?>
                        </h3>
                    </div>
                </div>
                <div class="product-list__item-buttons-block">
                    <div class="product-list__item-buttons-block-two">
                        <a data-bs-toggle="modal" data-bs-target="#Edit-dish" data-id="<?php echo $val['id']; ?>"
                            data-isCustomizable="1" href=""
                            class="product-list__item-buttons-block-btn btn6 edit-btn product-list__item-buttons-block-edit-btn"><img
                                class="product-list__item-button-img"
                                src="<?php echo base_url(); ?>assets/admin/images/edit-dish-icon.svg" alt="add stock"
                                width="23" height="22">View </a>

                    </div>



                </div>
            </div>
            <?php $count++; } } ?>



        </div>
        <div class="pagination-wrapper">
            <?= $pagination; ?>
        </div>

    </div>



</div>



<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="emigo-modal__heading" id="exampleModalLabel">Change Status</h1>
                <button type="button" class="emigo-close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to change the status of this product?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cancelStatusChange"
                    data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmStatusChange">Confirm</button>
            </div>
        </div>
    </div>
</div>
<!-- Confirmation Modal -->







<!-- Change Dish Informations -->

<!-- Change Description -->
<div class="emigo-modal modal fade" id="Edit-dish" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header emigo-modal__header">
                <h1 class="emigo-modal__heading text-center" id="exampleModalLabel">Enquiry Details</h1>
                <button type="button" class="emigo-close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body emigo-modal__body">

                <!-- if response within jquery -->
                <div class="message d-none" role="alert"></div>
                <!-- if response within jquery -->

                <input type="text" id="hiddenField" name="product_id" value="">
                <div class="row">
                    <form class="product-details-form" id="productForm" method="post" enctype="multipart/form-data">
                        <div class="product-details-form__section product-details-form__section--first">
                            <div class="product-details-form__item">
                                <input type="hidden" id="product_id_new" name="product_id">
                                <label class="col-form-label product_rate_label">Name</label>
                                <input type="text" class="form-control form__input-text product_rate" id="visitor_name"
                                    name="visitor_name" value="">
                            </div>
                            <div class="product-details-form__item">
                                <label class="col-form-label">Phone number</label>
                                <input type="text" class="form-control form__input-text" id="phone_number"
                                    name="phone_number" value="">
                            </div>
                            <div class="product-details-form__item">
                                <label class="col-form-label">Email</label>
                                <input type="text" class="form-control form__input-text" id="email" name="email"
                                    value="">
                            </div>
                        </div>

                        <div class="product-details-form__section product-details-form__section--first">
                            <div class="product-details-form__item">
                                <input type="hidden" id="product_id_new" name="product_id">
                                <label class="col-form-label product_rate_label">Name</label>
                                <input type="text" class="form-control form__input-text product_rate" id="company_id"
                                    name="company_id" value="">
                            </div>
                            <div class="product-details-form__item">
                                <label class="col-form-label">Phone number</label>
                                <input type="text" class="form-control form__input-text" id="purpose_of_visit"
                                    name="purpose_of_visit" value="">
                            </div>
                            <div class="product-details-form__item">
                                <label class="col-form-label">Email</label>
                                <input type="text" class="form-control form__input-text" id="contact_person"
                                    name="contact_person" value="">
                            </div>
                        </div>

                        <div class="product-details-form__section">

                            <div class="product-details-form__item">
                                <input type="hidden" id="product_id_new" name="product_id">
                                <label class="col-form-label product_rate_label">Name</label>
                                <textarea class="form-control" value="" id="remarks" name="remarks"></textarea>
                            </div>
                            <div class="product-details-form__item">
                                <input type="hidden" id="product_id_new" name="product_id">
                                <label class="col-form-label product_rate_label">Name</label>
                                <textarea class="form-control" value="" id="visitor_message"
                                    name="visitor_message"></textarea>
                            </div>
                        </div>

                        <div class="mt-2 text-center m-auto">
                            <button class="btn1-small" type="button" id="saveProduct">Save</button>
                        </div>
                </div>

                </form>
            </div>
        </div>
    </div>
</div>
<!-- end -->



<div class="emigo-modal modal fade" id="nextavailabletime" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header emigo-modal__header">
                <h1 class="emigo-modal__heading" id="exampleModalLabel">Next Available Time</h1>
                <button type="button" class="emigo-close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body emigo-modal__body">
                <!-- if response within jquery -->
                <div class="message d-none" role="alert"></div>
                <!-- if response within jquery -->
                <form id="avialablestimes" method="post" enctype="multipart/form-data">
                    <input type="hidden" class="form-control" id="product_id_time" value="">
                    <div class="col-12">
                        <label for="exampleSelect" class="form-label">Select an Option</label>
                        <select class="form-select form__input-select" id="available_select" style="margin-bottom:1rem">
                            <option value="Available Morning">Available Morning</option>
                            <option value="Available Afternoon">Available Afternoon</option>
                            <option value="Available Night">Available Night</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="default-input">Available Time</label>
                        <!-- <input type="text" class="form-control" name="name" id="time" value=""> -->
                        <div class="emigo-modal__time-container time-container d-flex ">
                            <!-- Hour Input -->
                            <input type="number" class="form-control form__input-text mx-1" id="hours" min="1" max="12"
                                value="12">
                            <input type="number" class="form-control form__input-text mx-1" id="minutes" min="0"
                                max="59" value="00">
                            <!-- AM/PM Dropdown -->
                            <select class="form-select form__input-select" id="ampm">
                                <option value="AM">AM</option>
                                <option value="PM">PM</option>
                            </select>
                        </div>
                    </div>
                    <!-- <input type="text" class="form-control mt-2" placeholder="Enter your Quanity" name="sl_qty"
                        id="remove_stocks" value=""> -->
                    <span class="error errormsg mt-2" id="removestocks_error"></span>
                </form>
                <!-- <h1>addons</h1> -->
                <div class="mt-2 text-center m-auto">
                    <button class="btn1-small " type="button" id="nextavaialabletimes">Update</button>
                </div>
                </form>

            </div>
        </div>
    </div>
</div>





<!-- Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <!-- Success message placeholder -->
                <div id="successMessage" class="alert alert-success" style="display: none;">

                </div>
            </div>
        </div>
    </div>
</div>
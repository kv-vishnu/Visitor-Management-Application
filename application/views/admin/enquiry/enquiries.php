<div class="application-content dashboard-content">
    <div class="application-content__container container">
        <div class="search-add-new-dish-list-combo">
            <!-- <form class="product-search__form search">
                <input type="text" id="search_product" placeholder="Search for a product" name="search"
                    class="product-search__field search-input1">
                <button type="submit" class="product-search__button"><img
                        src="<?php echo base_url(); ?>assets/admin/images/product-search-icon.svg" width="22"
                        height="23" alt="SearchIcon" class="product-search__icon"></button>
                <ul id="autocomplete-results1" class="autocomplete-results">
                </ul>
            </form> -->
            <div class="add-new-dish-list-combo">
                <a href="<?php echo base_url('admin/enquiry/add/6'); ?>" class="add-new-dish-btn btn1">
                    <img src="<?php echo base_url(); ?>assets/admin/images/add-new-dish-icon.svg" alt="add new dish"
                        class="add-new-dish__icon" width="23" height="23">
                    New Enquiry
                </a>
            </div>

        </div>

        <div class="table-responsive">
            <table class="table table-bordered mt-3">
                <thead class="table-light">
                    <tr>
                        <th>Sl</th>
                        <th>Name</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>Company</th>
                        <th>Purpose</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($enquiries)) { 
                $count = 1;
                foreach ($enquiries as $val) { ?>
                    <tr>
                        <td><?php echo $count; ?></td>
                        <td><?php echo htmlspecialchars($val['visitor_name']); ?></td>
                        <td><?php echo htmlspecialchars($val['phone_number']); ?></td>
                        <td><?php echo htmlspecialchars($val['email']); ?></td>
                        <td><?php echo htmlspecialchars($this->Commonmodel->get_company_name($val['company_id'])); ?>
                        </td>
                        <td><?php echo htmlspecialchars($val['purpose_of_visit']); ?></td>
                        <td>
                            <a data-bs-toggle="modal" data-bs-target="#Edit-dish" data-id="<?php echo $val['id']; ?>"
                                href="" class="btn btn-success btn-sm edit-btn">View</a>
                            <a data-bs-toggle="modal" data-bs-target="#Edit-dish" data-id="<?php echo $val['id']; ?>"
                                href="" class="btn btn-danger btn-sm edit-btn">Delete</a>
                        </td>
                    </tr>
                    <?php $count++; } 
            } else { ?>
                    <tr>
                        <td colspan="7" class="text-center">No enquiries found.</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="pagination-wrapper">
            <?= $pagination; ?>
        </div>
    </div>



</div>







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
                                <input type="hidden" id="enquiry_id_new" name="enquiry_id">
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
                                <label class="col-form-label product_rate_label">Company</label>
                                <select name="company_id" id="company_id" class="form-select form__input-select"
                                    disabled>
                                    <?php
                foreach($all_companies as $val){ ?>
                                    <option value="<?php echo $val['n_id']; ?>"><?php echo $val['company_name']; ?>
                                    </option>
                                    <?php } ?>
                                </select>
                                <span class="error errormsg mt-2" id="company_id_error"></span>
                            </div>
                            <div class="product-details-form__item">
                                <label class="col-form-label product_rate_label">Purpose of visit</label>
                                <select name="purpose_of_visit" id="purpose_of_visit"
                                    class="form-select form__input-select">
                                    <?php foreach ($purposes as $key => $value) { ?>
                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="product-details-form__item">
                                <label class="col-form-label">Contact Person</label>
                                <input type="text" class="form-control form__input-text" id="contact_person"
                                    name="contact_person" value="">
                            </div>
                        </div>

                        <div class="product-details-form__section">

                            <div class="product-details-form__item">
                                <label class="col-form-label product_rate_label">Remarks</label>
                                <textarea class="form-control" value="" id="remarks" name="remarks"></textarea>
                            </div>
                            <div class="product-details-form__item">
                                <label class="col-form-label product_rate_label">Message</label>
                                <textarea class="form-control" value="" id="visitor_message"
                                    name="visitor_message"></textarea>
                            </div>
                        </div>

                        <div class="mt-2 text-center m-auto">
                            <button class="btn1-small" type="button" id="update-btn">Update</button>
                        </div>
                </div>

                </form>
            </div>
        </div>
    </div>
</div>
<!-- end -->
<div class="application-content add-new-dish">
    <div class="application-content__container container add-new-dish__container">
        <h1 class="application-content__page-heading">Add New Enquiry</h1>
        <div class="add-new-dish-form">

            <form id="add-new-enquiry" method="post" enctype="multipart/form-data" class="add-new-dish-form__body">

                <div class="add-new-dish-form__section-container">
                    <div class="add-new-dish-form__section">


                        <div class="add-new-dish-form__section">
                            <h2 class="add-new-dish-form__section-heading">Enquiry details</h2>
                            <div class="form__field-container-group gc">
                                <div class="form__field-container add-new-dish-form__proudct-name-description xs12 lg4">
                                    <div class="form__field-container-label-input-group">
                                        <label class="form__label">Name</label>
                                        <input class="form-control" value="" type="text" placeholder="Malayalam"
                                            name="visitor_name">
                                    </div>
                                    <div class="form__validation"><span id="visitor_name_error"
                                            class="error errormsg mt-2"><?= form_error('visitor_name'); ?></span></div>
                                </div>
                                <div class="form__field-container add-new-dish-form__proudct-name-description xs12 lg4">
                                    <div class="form__field-container-label-input-group">
                                        <label class="form__label">Phone Number</label>
                                        <input class="form-control" value="" type="text" placeholder="English"
                                            name="phone_number">
                                    </div>
                                    <div class="form__validation"><span id="phone_number_error"
                                            class="error errormsg mt-2"><?= form_error('phone_number'); ?></span>
                                    </div>
                                </div>
                                <div class="form__field-container add-new-dish-form__proudct-name-description xs12 lg4">
                                    <div class="form__field-container-label-input-group">
                                        <label class="form__label">Email</label>
                                        <input class="form-control" value="" type="text" placeholder="Hindi"
                                            name="email">
                                    </div>
                                    <div class="form__validation"><span id="email_error"
                                            class="error errormsg mt-2"><?= form_error('email'); ?></span></div>
                                </div>
                            </div>
                        </div>


                        <div class="form__field-container-group gc">
                            <div class="form__field-container xs12 lg4">
                                <label class="form__label">Company</label>
                                <select class="form__input-select" name="company_id">
                                    <option value="">Select </option>
                                    <?php
                                    foreach($companies as $company)
                                    {
                                    ?>
                                    <option value="<?=$company['n_id'];?>"
                                        <?php echo set_select('company_id', $company['n_id'])?>>
                                        <?=$company['company_name'];?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <div class="form__validation">
                                    <span id="company_id_error"
                                        class="error errormsg mt-2"><?= form_error('company_id'); ?></span>
                                </div>
                            </div>
                            <!-- sub category -->
                            <div class="form__field-container xs12 lg4">
                                <label class="form__label">Purpose </label>
                                <select class="form__input-select" name="purpose_of_visit">
                                    <option value="">Select</option>
                                    <option value="seminar">Seminar</option>
                                    <option value="meeting">Meeting</option>
                                </select>
                                <div class="form__validation">
                                    <span id="purpose_of_visit_error"
                                        class="error errormsg mt-2"><?= form_error('purpose_of_visit'); ?></span>
                                </div>
                            </div>

                            <div class="form__field-container xs12 lg4">
                                <label class="form__label">Contact Person</label>
                                <select class="form__input-select" name="contact_person">
                                    <option value="">Select any</option>
                                    <option value="veg">Veg</option>
                                    <option value="non-veg">Non-Veg</option>
                                </select>
                                <span id="contact_person_error"
                                    class="error errormsg mt-2"><?= form_error('contact_person'); ?></span>
                            </div>

                        </div>


                        <div class="add-new-dish-form__section">
                            <div class="form__field-container-group gc">
                                <div class="form__field-container add-new-dish-form__proudct-name-description xs12 lg6">
                                    <div class="form__field-container-label-input-group">
                                        <label class="form__label">Remarks</label>
                                        <textarea class="form-control" value="" type="text" placeholder="Malayalam"
                                            name="remarks"></textarea>
                                    </div>
                                    <div class="form__validation"><span id="remarks_error"
                                            class="error errormsg mt-2"><?= form_error('remarks'); ?></span></div>
                                </div>
                                <div class="form__field-container add-new-dish-form__proudct-name-description xs12 lg6">
                                    <div class="form__field-container-label-input-group">
                                        <label class="form__label">Message</label>
                                        <textarea class="form-control" value="" type="text" placeholder="English"
                                            name="visitor_message"></textarea>
                                    </div>
                                    <div class="form__validation"><span id="visitor_message_error"
                                            class="error errormsg mt-2"><?= form_error('visitor_message'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>



                    <button class="btn btn1 mt-2" type="button" id="addNewEnquiry">SAVE ENQUIRY</button>


                </div>



            </form>
        </div>

    </div>
</div>


</div>

</div>
</div>
</div>
</form>



</div>
</body>

</html>
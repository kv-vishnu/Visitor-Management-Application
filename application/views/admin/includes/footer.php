<div class="application-footer">
    <div class="application-footer__container container">
        <div class="application-footer__company-logo">
            <img src="<?php echo base_url();?>assets/admin/images/emigo-logo.svg" alt="emigo logo"
                class="application-footer__company-logo-img" width="210" height="69">
        </div>
        <div class="application-footer__copyright">@ All rights reserved. Emigo 2025</div>
        <div class="application-footer__help-desk">
            <div class="application-footer__help-desk-label">
                <img src="<?php echo base_url();?>assets/admin/images/help-desk-icon.svg" alt="help desk icon"
                    class="application-footer__help-desk-label-icon" width="67" height="47">
                <div class="application-footer__help-desk-label-text">Help Desk</div>
            </div>
            <div class="application-footer__help-desk-number-and-email">
                <div class="application-footer__help-desk-number">
                    <img src="<?php echo base_url();?>assets/admin/images/help-desk-phone-icon.svg" alt=""
                        class="application-footer__help-desk-number-icon" width="16" height="17">
                    <a href="tel:+971-7112713311"
                        class="application-footer__help-desk-number-link"><?php echo $support_no; ?></a>
                </div>
                <div class="application-footer__help-desk-email">
                    <img src="<?php echo base_url();?>assets/admin/images/help-desk-email-icon.svg" alt=""
                        class="application-footer__help-desk-email-icon" width="16" height="17">
                    <a href="mailto:emigo@ae.com"
                        class="application-footer__help-desk-email-link"><?php echo $support_email; ?></a>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Confirmation Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="emigo-modal__heading" id="exampleModalLabel"></h1>
                <button type="button" class="emigo-close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Enquiry saved....
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary reload-close-btn" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Confirmation Modal -->


<button id="goToTop" style="display: none; position: fixed; bottom: 20px; right: 20px;">Top</button>

<!-- JAVASCRIPT -->
<script src="<?php echo base_url();?>assets/admin/js/jquery-3.7.1.min.js"></script>
<script src="<?php echo base_url();?>assets/admin/js/bootstrap.min.js"></script>
<script src="<?php echo base_url();?>assets/admin/js/ownerscripts.js"></script>
<script src="<?php echo base_url();?>assets/admin/js/scripts.js"></script>
<!-- DataTables CSS -->
</body>

</html>
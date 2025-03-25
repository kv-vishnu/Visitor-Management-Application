<!DOCTYPE html>
<html lang="en">
  
<!-- Mirrored from admin.pixelstrap.com/tivo/template/forget-password.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 17 Feb 2023 08:01:47 GMT -->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="We welcome you to our website. In this site, we highlight about our services in the field of Inspection & Testing.">
    <meta name="keywords" content="deem,inspection,testing">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="<?php echo base_url();?>assets/images/favicon/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="<?php echo base_url();?>assets/images/favicon/favicon.png" type="image/x-icon">
    <title>Deem Inspection Company Ltd.</title><link rel="preconnect" href="https://fonts.googleapis.com/">
<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/vendors/font-awesome.css">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/vendors/icofont.css">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/vendors/themify.css">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/vendors/flag-icon.css">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/vendors/feather-icon.css">
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/vendors/bootstrap.css">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/style.css">
    <link id="color" rel="stylesheet" href="<?php echo base_url();?>assets/css/color-1.css" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/responsive.css">
  </head>
  <body>
    <!-- tap on top starts-->
    <div class="tap-top"><i data-feather="chevrons-up"></i></div>
    <!-- tap on tap ends-->
    <!-- Loader starts-->
    <div class="loader-wrapper">
      <div class="dot"></div>
      <div class="dot"></div>
      <div class="dot"></div>
      <div class="dot"> </div>
      <div class="dot"></div>
    </div>
    <!-- Loader ends-->
    <!-- page-wrapper Start-->
    <div class="page-wrapper">
      <div class="container-fluid p-0">
        <div class="row">
          <div class="col-12">     
            <div class="login-card">
              <div>
                <div><a class="logo" href="index.html"><img class="img-fluid for-light" src="<?php echo base_url();?>assets/images/logo/logo2.png" alt="looginpage"></a></div>
                <div class="login-main"> 
                  <form class="theme-form" method="POST" action="<?php echo base_url(); ?>forgotpassword">                  
                    <h4 class="text-center">Reset Your Password</h4>
                   
                   <?php if($this->session->flashdata('error')){ ?>
                    <div class="alert alert-danger dark" role="alert">
                        <?php echo $this->session->flashdata('error');$this->session->unset_userdata('error'); ?>
                    </div><?php } ?>
                    
                    <div class="form-group">
                      <label class="col-form-label">Enter your Email address</label>
                      <div class="form-input position-relative">
                        <input class="form-control" type="text" name="email" placeholder="">
                       
                      </div>
                    </div>
                    <?php if(form_error('email')){ ?>
<div class="errormsg" role="alert"><?php echo form_error('email'); ?></div>
                  <?php } ?>
                  
                    <div class="form-group mb-0">
                      
                      <button class="btn btn-primary btn-block w-100 mt-3" type="submit">Send</button>
                      
                    </div>
                    <div class="form-group mb-0">
                   <a class="link" href="<?php echo base_url(); ?>login"">Back to login</a>
                   </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- latest jquery-->
    <script src="<?php echo base_url();?>assets/js/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap js-->
    <script src="<?php echo base_url();?>assets/js/bootstrap/bootstrap.bundle.min.js"></script>
    <!-- feather icon js-->
    <script src="<?php echo base_url();?>assets/js/icons/feather-icon/feather.min.js"></script>
    <script src="<?php echo base_url();?>assets/js/icons/feather-icon/feather-icon.js"></script>
    <!-- scrollbar js-->
    <!-- Sidebar jquery-->
    <script src="<?php echo base_url();?>assets/js/config.js"></script>
    <!-- Template js-->
    <script src="<?php echo base_url();?>assets/js/script.js"></script>
    <!-- login js-->
  </body>

<!-- Mirrored from admin.pixelstrap.com/tivo/template/forget-password.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 17 Feb 2023 08:01:47 GMT -->
</html>
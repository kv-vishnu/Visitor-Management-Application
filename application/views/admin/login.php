<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Login | Emigo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Well Scaffolding CRM" name="description" />
    <meta content="CVS" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="images/favicon.ico">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/admin/css/preloader.css" type="text/css" />
    <link href="<?php echo base_url();?>assets/admin/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet"
        type="text/css" />
    <link href="<?php echo base_url();?>assets/admin/css/icon.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="fonts/<?php echo base_url();?>assets/admin/css/all.min.css" />
    <link href="<?php echo base_url();?>assets/admin/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url();?>assets/admin/css/crm-responsive.css" id="app-style" rel="stylesheet"
        type="text/css" />
    <style>
    iframe {
        display: none;
    }

    .text-white {
        color: #fff !important;
    }

    .auth-page {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        /* Ensure full height */
        background: #f8f9fa !important;
        /* Light grey background */
        padding: 20px;
        width: 100%;
    }

    .auth-bg {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        width: 100%;
        max-width: 625px;
        padding: 30px;
        border-radius: 10px;
        background: white;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .auth-content {
        width: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 1;
        background: #f9e4ed;
        padding: 80px 50px;
        border-radius: 60px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2),
            0 2px 10px rgba(0, 0, 0, 0.1),
            inset 0 1px 2px rgba(255, 255, 255, 0.6);
    }

    .auth-content form {
        width: 80%;
    }

    .form-label {
        display: block;
        /* Makes the label take full width */
        text-align: left;
        /* Aligns text to the left */
        font-weight: 500;
        /* Optional: Make it slightly bold */
    }

    .login-form .form-control {
        background: #ffecee !important;
        width: 100%;
        border-radius: 26px;
        height: 50px !important;
        text-align: center !important;
        font-size: 20px !important;
        letter-spacing: 1px;
    }

    .btn-login {
        width: 100%;
        padding: 0.8rem;
        background-color: #b01a45;
        color: white;
        border: none;
        border-radius: 25px;
        font-size: 1.6rem !important;
        cursor: pointer;
        transition: background-color 0.3s;
        letter-spacing: 1px;
    }

    .btn-login:hover {
        background-color: #8a1536;
        color: white;
    }

    .bg-login-overlay {
        background-color: #eacacb !important;
    }

    .powered-by {
        margin-top: 1.5rem;
        color: #4c4c4c;
        font-size: 0.8rem;
        letter-spacing: 1px;
    }
    </style>
</head>

<body>

    <div class="auth-page" style="background: #f8f9fa;">
        <div class="auth-bg">
            <div class="bg-overlay bg-login-overlay"></div>
            <ul class="bg-bubbles">
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
            </ul>
            <div class="auth-content">




                <a href="#" class="auth-logo mb-5">
                    <img src="<?php echo base_url();?>assets/admin/images/login-choose-my-food.png" alt="Logo">
                </a>

                <form class="login-form" action="<?php echo base_url(); ?>admin/login/userlogin" method="post">

                    <!-- display error messages -->
                    <?php if($this->session->flashdata('success')){ ?>
                    <div class="alert alert-success dark" role="alert">
                        <?php echo $this->session->flashdata('success');$this->session->unset_userdata('success'); ?>
                    </div><?php } ?>
                    <?php if($this->session->flashdata('error')){ ?>
                    <div class="alert alert-danger dark" role="alert">
                        <?php echo $this->session->flashdata('error');$this->session->unset_userdata('error'); ?>
                    </div><?php } ?>

                    <div class="mb-3">

                        <input class="form-control" name="username" type="text"
                            value="<?php echo set_value('username'); ?>" placeholder="User Name">
                    </div>
                    <?php if(form_error('username')){ ?>
                    <div class="errormsg mb-2" role="alert">
                        <?php echo form_error('username'); ?></div>
                    <?php } ?>
                    <div class="mb-3">

                        <div class="input-group">
                            <input class="form-control" type="password" name="login[password]"
                                value="<?php echo set_value('login[password]'); ?>" placeholder="Password">
                            <!-- <button class="btn btn-danger" type="button"><i class="fa fa-eye"></i></button> -->
                        </div>
                    </div>
                    <?php if(form_error('login[password]')){ ?>
                    <div class="errormsg mb-2" role="alert">
                        <?php echo form_error('login[password]'); ?></div>
                    <?php } ?>
                    <button id="BtnLogin" class="btn btn-login" type="submit">LOGIN</button>
                    <div class="powered-by">
                        POWERED BY EMIGO
                    </div>
                </form>

                <!-- <div class="text-center  mt-5">
                    <p>©2025 Emigo. Developed by <a target="_blank" href="https://coinoneglobal.com/">Coinone</a></p>
                </div> -->

            </div>

        </div>
    </div>

    <!-- JAVASCRIPT -->
</body>

</html>
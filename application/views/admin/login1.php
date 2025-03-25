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
    </style>
</head>

<body>

    <!-- <body data-layout="horizontal"> -->
    <div class="auth-page">
        <div class="container-fluid p-0">
            <div class="row g-0">

                <!-- end col -->
                <div class="col-xxl-12 col-lg-12 col-md-12">
                    <div class="auth-bg pt-md-3 p-4">
                        <div class="bg-overlay bg-primary"></div>
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
                        <!-- end bubble effect -->
                        <div class="row justify-content-center align-items-center">
                            <div class="col-xl-4">
                                <div class="p-0 p-sm-4 px-xl-0">
                                    <div class="auth-full-page-contentw p-sm-5 p-4 bg-white">
                                        <div class="w-100">
                                            <div class="">
                                                <div class="mb-4 mb-md-5 text-center">
                                                    <a href="#" class="d-block auth-logo">
                                                        <img src="<?php echo base_url();?>assets/admin/images/emigo_logo.png"
                                                            alt="" height=""> <span class="logo-txt"></span>
                                                    </a>
                                                </div>
                                                <div class="auth-content my-auto">
                                                    <div class="text-center">
                                                        <h5 class="mb-0">Welcome Back !</h5>
                                                        <p class="text-muted mt-2">Sign in to continue to Emigo.</p>
                                                    </div>
                                                    <form action="<?php echo base_url(); ?>admin/login/userlogin"
                                                        method="post" class="needs-validation" novalidate="">



                                                        <!-- display error messages -->
                                                        <?php if($this->session->flashdata('success')){ ?>
                                                        <div class="alert alert-success dark" role="alert">
                                                            <?php echo $this->session->flashdata('success');$this->session->unset_userdata('success'); ?>
                                                        </div><?php } ?>
                                                        <?php if($this->session->flashdata('error')){ ?>
                                                        <div class="alert alert-danger dark" role="alert">
                                                            <?php echo $this->session->flashdata('error');$this->session->unset_userdata('error'); ?>
                                                        </div><?php } ?>
                                                        <!-- dispay error messages -->




                                                        <div class="mt-4 pt-2">
                                                            <div class="mb-2">
                                                                <label class="form-label">Username</label>
                                                                <input class="form-control" name="username" type="text"
                                                                    value="<?php echo set_value('username'); ?>"
                                                                    placeholder="Enter your username">
                                                            </div>
                                                            <?php if(form_error('username')){ ?>
                                                            <div class="errormsg mb-2" role="alert">
                                                                <?php echo form_error('username'); ?></div>
                                                            <?php } ?>
                                                            <div class="mb-2">
                                                                <div class="d-flex align-items-start">
                                                                    <div class="flex-grow-1">
                                                                        <label class="form-label">Password</label>
                                                                    </div>
                                                                    <div class="flex-shrink-0">
                                                                        <div class="">
                                                                            <a href="javascript:void()"
                                                                                class="text-muted">Forgot password?</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="input-group">
                                                                    <input class="form-control" type="password"
                                                                        name="login[password]"
                                                                        value="<?php echo set_value('login[password]'); ?>">
                                                                    <button class="btn btn-light shadow-none ms-0"
                                                                        type="button" id="password-addon"><i
                                                                            class="	fa fa-eye"></i></button>
                                                                </div>
                                                            </div>
                                                            <?php if(form_error('login[password]')){ ?>
                                                            <div class="errormsg mb-2" role="alert">
                                                                <?php echo form_error('login[password]'); ?></div>
                                                            <?php } ?>
                                                            <!-- <div class="mb-3">
                                                                <label class="form-label">Branch</label>
                                                                <select name="" id="" class="form-control form-select">
                                                                    <option value="1">Option 1</option>
                                                                    <option value="2">Option 2</option>
                                                                    <option value="3">Option 3</option>
                                                                </select>
                                                            </div> -->
                                                            <!-- <div class="row mb-4">
                                                                <div class="col">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox" id="remember-check">
                                                                        <label class="form-check-label" for="remember-check">
                                                                            Remember me
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div> -->
                                                            <div class="mb-3">
                                                                <button id="BtnLogin"
                                                                    class="btn btn-primary w-100 waves-effect waves-light"
                                                                    type="submit">Log In</button>
                                                            </div>
                                                        </div>
                                                    </form>


                                                </div>
                                                <div class="mt-4 mt-md-5 text-center">
                                                    <p class="mb-0">©2024 Emigo . developed by <a
                                                            href="https://coinoneglobal.com/">Coinone</a><i
                                                            class="fa fa-heart"></i>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end review carousel -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container fluid -->
    </div>
    <!-- JAVASCRIPT -->
</body>

</html>
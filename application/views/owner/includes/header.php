<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8" />
    <title>Dashboard | Emigo </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Emigo" name="description" />
    <meta content="CVS" name="author" />

    <link rel="shortcut icon" href="<?php echo base_url();?>assets/admin/images/favicon.ico">
    <link href="<?php echo base_url();?>assets/admin/css/crm-responsive.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url();?>assets/admin/css/classic.min.css" rel="stylesheet" /> <!-- 'classic' theme -->
    <link href="<?php echo base_url();?>assets/admin/fonts/css/all.min.css" rel="stylesheet" />
    <link href="<?php echo base_url();?>assets/admin/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet"
        type="text/css" />
    <link href="<?php echo base_url();?>assets/admin/css/icon.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url();?>assets/admin/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url();?>assets/admin/css/custom.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url();?>assets/admin/css/styles.css" rel="stylesheet" type="text/css" />

</head>

<body>
    <div class="application-header">
        <div class="application-header__container container">
            <div class="application-header__brand">
                <a href="<?php echo base_url();?>/owner/dashboard" class="application-header__brand-logo">
                    <img src="<?php echo base_url();?>uploads/store/<?php echo $store_logo; ?>" alt="brand lgo"
                        class="application-header__brand-logo-img" width="97" height="97">
                </a>
                <div class="application-header__brand-address">
                    <h2 class="application-header__brand-name"><?php echo $store_disp_name; ?></h2>
                    <p class="application-header__brand-address"><?php echo $store_address; ?></p>
                </div>
            </div>
            <div class="application-header__provider">
                <img src="<?php echo base_url();?>assets/admin/images/choose-my-food.png" alt="choose my food logo"
                    class="application-header__provider-img" width="250" height="37">
                <div class="application-header__provider-description">Powered By EMIGO</div>
            </div>
        </div>

    </div>
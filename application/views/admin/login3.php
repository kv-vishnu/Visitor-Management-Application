<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose My Food - Login</title>
    <link rel="stylesheet" href="<?php echo base_url();?>assets/admin/css/preloader.css" type="text/css" />
    <link href="<?php echo base_url();?>assets/admin/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet"
        type="text/css" />
    <link href="<?php echo base_url();?>assets/admin/css/icon.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="fonts/<?php echo base_url();?>assets/admin/css/all.min.css" />
    <link href="<?php echo base_url();?>assets/admin/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url();?>assets/admin/css/crm-responsive.css" id="app-style" rel="stylesheet"
        type="text/css" />
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Arial, sans-serif;
    }

    body {
        background-color: #f5f5f5;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        background-image: linear-gradient(rgba(255, 255, 255, 0.7), rgba(255, 255, 255, 0.7)),
            url('/api/placeholder/1200/800');
        background-size: cover;
        background-position: center;
    }

    .login-container {
        background-color: #f8f3ea;
        padding: 2rem;
        border-radius: 15px;
        width: 90%;
        max-width: 450px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .logo {
        margin-bottom: 2rem;
    }

    .logo h1 {
        font-size: 2.5rem;
        color: #333;
        font-style: italic;
        margin-bottom: 0.5rem;
        position: relative;
        display: inline-block;
    }

    .logo h1 .my-circle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 50px;
        height: 50px;
        background-color: #333;
        color: white;
        border-radius: 50%;
        font-size: 1.2rem;
        margin: 0 5px;
        position: relative;
        top: -5px;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-control {
        width: 100%;
        padding: 0.8rem;
        border: 1px solid #ddd;
        border-radius: 25px;
        font-size: 1rem;
        text-align: center;
    }

    .btn-login {
        width: 100%;
        padding: 0.8rem;
        background-color: #b01a45;
        color: white;
        border: none;
        border-radius: 25px;
        font-size: 1.1rem;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .btn-login:hover {
        background-color: #8a1536;
    }

    .powered-by {
        margin-top: 1.5rem;
        color: #999;
        font-size: 0.8rem;
    }

    /* Media Queries for responsiveness */
    @media (max-width: 768px) {
        .login-container {
            width: 85%;
        }
    }

    @media (max-width: 480px) {
        .login-container {
            width: 95%;
            padding: 1.5rem;
        }

        .logo h1 {
            font-size: 2rem;
        }

        .logo h1 .my-circle {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
    }
    </style>
</head>

<body>
    <div class="auth-page" style="background: #f8f9fa;">
        <div class="auth-bg">
            <div class="bg-overlay bg-danger"></div>
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
            <div class="login-container">
                <div class="logo">
                    <img src="<?php echo base_url();?>assets/admin/images/login-choose-my-food.png" alt="" height="">
                </div>

                <form>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="User Name" required>
                    </div>

                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Password" required>
                    </div>

                    <button type="submit" class="btn-login">LOGIN</button>
                </form>

                <div class="powered-by">
                    POWERED BY EMIGO
                </div>
            </div>
        </div>
    </div>
    </div>
</body>

</html>
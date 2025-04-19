<?php
session_start();
require_once __DIR__.'/template/header.php';
require_once __DIR__.'/includes/token.php';



?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <title>Twintelli Authentication</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins';
        }

        body {
            background-color: #ffffff;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            display: flex;
            max-width: 1000px;
            width: 100%;
        }

        .illustration-section {
            flex: 1;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #ffffff;
            padding: 40px;
            margin-left: -80px;
        }

        .illustration {
            width: 500px;
            max-width: 750px;
            height: 500px;
        }

        .auth-section {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .logo {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .logo-icon {
            width: 24px;
            height: 24px;
            color: #1a1b50;
        }

        .logo-text {
            font-size: 22px;
            font-weight: 700;
            color: #1a1b50;
            margin-left: 10px;
            font-weight: 500;
        }

        .title {
            font-size: 28px;
            font-weight: 700;
            color: #1a1b50;
            margin-bottom: 15px;
            font-weight: 500;
        }

        .subtitle {
            font-size: 14px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-label {
            font-size: 14px;
            color: #666;
            display: block;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-input:focus {
            border-color: #b069d8;
            outline: none;
        }

        .form-input.error {
            border-color: #dc3545;
        }

        .error-message {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }

        .continue-btn {
            background-color: #0E1039;
            color: white;
            border: none;
            border-radius: 100px;
            padding: 14px 40px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: max-content;
        }

        .continue-btn:hover {
            background-color: #131335;
        }

        @media screen and (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            
            .illustration-section {
                padding: 30px;
                margin-left: 0;
            }
            
            .auth-section {
                padding: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="illustration-section">
            <img src="images/cc.jpg" class="illustration">
        </div>
        <div class="auth-section">
            <div class="logo">
                <img src="/images/Vector.png" alt="">
                <span class="logo-text">Twintelli</span>
            </div>
            <h1 class="title">Send to code sTwintelli</h1>
            <p class="subtitle">We craft experiences that resonate, designs that captivate, and solutions that leave a lasting impression. Welcome to a world where your vision takes shape.</p>
            <form id="authForm" action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
                <div class="form-group">
                    <label class="form-label">Bearer Token</label>
                    <input name="token" type="text" id="bearerToken" class="form-input" placeholder="">
                    <div class="error-massge" style="color: red;
    font-size: 12px;
    margin-top: 5px;">
                        <?= $errors['token'] ? $errors['token'] : "" ?>
                    </div>
                </div>
                <button type="submit" class="continue-btn">Continuer</button>
            </form>
        </div>
    </div>
</body>
</html>
<?php

require_once __DIR__ . '/template/header.php';
require_once __DIR__ . '/includes/login.php';


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>Twintelli Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #ffffff;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            display: flex;
            max-width: 1100px;
            width: 100%;
            background-color: #ffffff;
        }

        .illustration-section {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }

        .illustration {
            width: 100%;
            max-width: 450px;
        }

        .login-section {
            flex: 1;
            padding: 50px;
            display: flex;
            flex-direction: column;
        }

        .logo {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }

        .logo-icon {
            width: 24px;
            height: 24px;
            color: #1a1b50;
        }

        .logo-text {
            font-size: 24px;
            font-weight: 600;
            color: #1a1b50;
            margin-left: 10px;
            font-weight:500;
        }

        .title {
            font-size: 28px;
            font-weight: 600;
            color: #1a1b50;
            margin-bottom: 15px;
            font-weight:500;
        }

        .subtitle {
            font-size: 14px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-label {
            display: block;
            font-size: 14px;
            color:#0E1039;
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
            border-color: #1a1b50;
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

        .password-field {
            position: relative;
        }

        .hide-button {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            font-size: 14px;
        }

        .terms-checkbox {
            display: flex;
            align-items: flex-start;
            margin: 20px 0 30px;
        }

        .terms-checkbox input {
            margin-top: 5px;
            margin-right: 10px;
        }

        .terms-text {
            font-size: 14px;
            color: #666;
            line-height: 1.5;
        }

        .terms-link {
            color: #1a1b50;
            text-decoration: none;
        }

        .login-btn {
            background-color:#0E1039;
            color: white;
            border: none;
            border-radius: 50px;
            padding: 14px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
            text-align: center;
            margin-bottom: 20px;
        }

        .login-btn:hover {
            background-color: #843091;
        }

        .member-text {
            font-size: 14px;
            color: #666;
            text-align: center;
        }

        .login-link {
            color: #1a1b50;
            text-decoration: none;
            font-weight: 500;
        }

        @media screen and (max-width: 900px) {
            .container {
                flex-direction: column;
                max-width: 500px;
            }
            
            .illustration-section {
                padding: 30px;
            }
            
            .login-section {
                padding: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="illustration-section">
            <img src= "images/cc.jpg" alt="Authentication illustration" class="illustration">
        </div>
        <div class="login-section">
            <div class="logo">
                <img src="/images/Vector.png" alt="">
                <span class="logo-text">Twintelli</span>
            </div>
            <h1 class="title">Welcome to PhnesTwintelli</h1>
            <p class="subtitle">We craft experiences that resonate, designs that captivate, and solutions that leave a lasting impression. Welcome to a world where your vision takes shape.</p>
            <form id="loginForm" action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input  id="email" class="form-input" name="email" placeholder="" value="<?= $email ?>">
                    <p id="emailError" class="error-message">Erreur: Veuillez entrer un email valide.</p>
                </div>
                <div class="error-massge" style="color: red;
    font-size: 12px;
    margin-top: 5px;">
                    <?= $errors['email'] ?>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="password-field">
                        <input id="password" name="pass" class="form-input" placeholder="" value="<?= $password ?>">
                        <button type="button" class="hide-button" id="togglePassword">Hide</button>
                    </div>
                </div>
                <div class="error-massge" style="color: red;
    font-size: 12px;
    margin-top: 5px;">
                    <?= $errors['password'] ?>
                </div>
                
                <div class="terms-checkbox">
                    <input type="checkbox" id="terms">
                    <label for="terms" class="terms-text">I agree to the <a href="#" class="terms-link">Terms of Service</a> and acknowledge you've read our <a href="#" class="terms-link">Privacy Policy</a>.</label>
                    <p id="termsError" class="error-message" style="margin-left: 20px;">Erreur: Vous devez accepter les conditions.</p>
                </div>
                
                <button type="submit" class="login-btn">Login account</button>
                
                <p class="member-text">Already a member? <a href="register.php" class="login-link">Sign up</a></p>
            </form>
    </div>
    <script>




    const passwordInput = document.getElementById('password');
    const togglePasswordBtn = document.getElementById('togglePassword');

    togglePasswordBtn.addEventListener('click', function() {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            togglePasswordBtn.textContent = 'Show';
        } else {
            passwordInput.type = 'password';
            togglePasswordBtn.textContent = 'Hide';
        }
    });

    {
        "status": "success",
        "image_url": "https://oaidalleapiprodscus.blob.core.windows.net/private/org-44Ky6HYYtn5UiAvngid4Bp04/o-poulet/img-cKerISY2fLoxr8X8rYkC2vuY.png?st=2025-04-15T13%3A32%3A30Z&se=2025-04-15T15%3A32%3A30Z&sp=r&sv=2024-08-04&sr=b&rscd=inline&rsct=image/png&skoid=475fd488-6c59-44a5-9aa9-31c4db451bea&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2025-04-14T23%3A41%3A01Z&ske=2025-04-15T23%3A41%3A01Z&sks=b&skv=2024-08-04&sig=kbXRjcO7MpU%2Bsw/24XdzGdX8qz8NWdPyFY%2BF/3B9%2BpY%3D",
        "timestamp": "2025-04-15T15:32:30.281252"
    }

</script>
</body>
</html>
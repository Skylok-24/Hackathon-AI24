<?php
session_start();
require_once __DIR__ . '/template/header.php';
require_once __DIR__.'/includes/register.php';



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <title>Twintelli Signup</title>
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

        .form-section {
            flex: 1;
            padding: 50px;
            display: flex;
            flex-direction: column;
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
            max-width: 100%;
            height: 70%;
            margin-top: -250px;
        }

        .logo {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }

        .logo-icon {
            width: 30px;
            height: 30px;
            background-color: #1a1b50;
            border-radius: 3px;
            position: relative;
        }

        .logo-icon::before {
            content: "";
            position: absolute;
            width: 15px;
            height: 3px;
            background-color: white;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(45deg);
        }

        .logo-icon::after {
            content: "";
            position: absolute;
            width: 15px;
            height: 3px;
            background-color: white;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
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
        }

        .form-label {
            display: block;
            font-size: 14px;
            color: #0E1039;
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
            display: flex;
            align-items: center;
        }

        .hide-button::before {
            content: "";
            display: inline-block;
            width: 16px;
            height: 16px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23666'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21'%3E%3C/path%3E%3C/svg%3E");
            background-size: contain;
            margin-right: 5px;
        }

        .password-requirements {
            margin-top: 10px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .requirement {
            display: flex;
            align-items: center;
            font-size: 12px;
            color: #666;
        }

        .requirement::before {
            content: "";
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #ccc;
            margin-right: 6px;
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

        .signup-btn {
            background-color: #0E1039;
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

        .signup-btn:hover {
            background-color: #843091;
        }

        .member-text {
            font-size: 14px;
            color: #666;
            text-align: left;
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
                order: -1;
            }

            .form-section {
                padding: 30px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-section">
            <div class="logo">
                <img src="/images/Vector.png" alt="">
                <span class="logo-text">Twintelli</span>
            </div>
            <h1 class="title">Welcome to PhnesTwintelli</h1>
            <p class="subtitle">We craft experiences that resonate, designs that captivate, and solutions that leave a
                lasting impression. Welcome to a world where your vision takes shape.</p>

            <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
                <div class="error-massge" style="color: red;
    font-size: 12px;
    margin-top: 5px;">
                    <?= $errors['user'] ?>
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input class="form-input" name="email" value="<?= $email ?>">
                    <div class="error-massge" style="color: red;
    font-size: 12px;
    margin-top: 5px;">
                        <?= $errors['email'] ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-input" name="name" value="<?= $name?>">
                    <div class="error-massge" style="color: red;
    font-size: 12px;
    margin-top: 5px;">
                        <?= $errors['name'] ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="password-field">
                        <input type="password" class="form-input" id="password" name="pass" value="<?= $password ?>">
                        <button class="hide-button" id="togglePassword">Hide</button>
                    </div>
                    <div class="password-requirements">
                        <div class="requirement" id="req-length">Use 8 or more characters</div>
                        <div class="requirement" id="req-uppercase">One uppercase character</div>
                        <div class="requirement" id="req-lowercase">One lowercase character</div>
                        <div class="requirement" id="req-special">One special character</div>
                        <div class="requirement" id="req-number">One number</div>
                    </div>
                    <div class="error-massge" style="color: red;
    font-size: 12px;
    margin-top: 5px;">
                        <?= $errors['password'] ?>
                    </div>
                </div>
                <div class="terms-checkbox">
                    <input type="checkbox" id="terms" checked>
                    <label for="terms" class="terms-text">I agree to the <a href="#" class="terms-link">Terms of Service</a>
                        and acknowledge you've read our <a href="#" class="terms-link">Privacy Policy</a>.</label>
                </div>

                <button class="signup-btn" type="submit">Create an account</button>
            </form>

            <p class="member-text">Already a member? <a href="login.php" class="login-link">Log in</a></p>
        </div>
        <div class="illustration-section">
            <img src="images/cc.jpg" alt="Authentication illustration" class="illustration">
        </div>
    </div>
</body>

</html>
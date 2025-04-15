<?php

function filterString($text)
{
    $text = filter_var(trim($text), FILTER_SANITIZE_SPECIAL_CHARS);
    if (empty($text)) {
        return false;
    } else {
        return $text;
    }
}

function filterEmail($text)
{
    $email = filter_var(trim($text), FILTER_SANITIZE_EMAIL);
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return $email;
    } else {
        return false;
    }
}

function isValidPassword($password) {

    if (strlen($password) < 8) {
        return false;
    }

    if (!preg_match('/[a-z]/', $password)) {
        return false;
    }

    if (!preg_match('/[A-Z]/', $password)) {
        return false;
    }

    if (!preg_match('/[\W_]/', $password)) {
        return false;
    }

    return true;
}



//echo "lokman2004" !== "lokman20204";

$errorName = $errorEmail = $errorPassword = '';
$email = $name = $password  = '';
$errors = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['pass'];
    if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['pass'])) {
        $name = filterString($name);
        $email = filterEmail($email);
        if (!$name) {
            $errorName = "Your Name is Invalid";
        }
        if (!$email) {
            $errorEmail = "Your Email is Invalid";
        }
        if (!isValidPassword($password)) {
            $errorPassword = "The password does not meet the requirements.";
        }
    } else {
        if (empty($_POST['name'])) array_push($errors, "Your Name is required");
        if (empty($_POST['email'])) array_push($errors, "Your Email is required");
        if (empty($_POST['pass'])) array_push($errors, "Your Password is required");
    }

    if (!$errorPassword && !$errorEmail && !$errorName) {
        $query = $pdo->prepare("SELECT id,email FROM user WHERE email=?");
        $query->execute([$email]);
        if ($query->fetch()) {
            $errorUser = "User Already Exists";
        } else {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $query = $pdo->prepare("INSERT INTO user (email,name,password) VALUES (?,?,?)");
            $success = $query->execute([$email, $name, $password]);
            if ($success) {
                $_SESSION['register'] = true;
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['user_name'] = $name;
                $_SESSION['message'] = $name . " Welcome back .";
                header("Location: /");
            } else {
                $errorUser = "regestred error";
            }
        }
    }
}
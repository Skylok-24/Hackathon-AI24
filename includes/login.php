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

$email = $password = '';
$errors = [
    'email' => '',
    'password' => '',
    'user' => ''
];
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['pass'];
    if (!empty($_POST['email']) && !empty($_POST['pass'])) {
        $email = filterEmail($email);
        if (!$email) {
            $errors['email'] = 'Your Email is Invalid';
        }
    }else {
        if (empty($_POST['email'])) $errors['email'] = 'Your Email is required';
        if (empty($_POST['pass'])) $errors['password'] = 'Your Password is required';
    }
    if (!$errors['email']  && !$errors['password']) {
        $query = $pdo->prepare("SELECT * FROM user WHERE email = ?");
        $query->execute([$email]);
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if (empty($user))
           $errors['email'] = "Your email, $email does not exists in our records.";
        else if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['login'] = true;
            $_SESSION['message'] = $user['name']." Welcome to our website";
//            print_r($user);
            header("Location: /Hackathon/token.php");
            exit();
        } else {
            $errors['password'] = "Incorrect password";
        }
    }
}
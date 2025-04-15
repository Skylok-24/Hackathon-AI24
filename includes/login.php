<?php

if (!empty($_SESSION['logen_in'])) {
    header("Location: /mojeeb");
}
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
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!empty($_POST['email']) && !empty($_POST['pass'])) {
        $email = $_POST['email'];
        $password = $_POST['pass'];
        $email = filterEmail($email);
        if (!$email) {
            array_push($errors,"Your Email is Invalid");
        }
    }else {
        if (empty($_POST['email'])) array_push($errors,"Your Email is required");
        if (empty($_POST['pass'])) array_push($errors,"Your Password is required");
    }
    if (!count($errors)) {
        $query = $pdo->prepare("SELECT * FROM user WHERE email = ?");
        $query->execute([$email]);
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if (empty($user))
            array_push($errors,"Your email, $email does not exists in our records.");
        else if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['login'] = true;
            $_SESSION['message'] = $user['name']." Welcome to our website";
//            print_r($user);
            header("Location: /");
            exit();
        } else {
            array_push($errors,"Incorrect password");
        }
    }
}
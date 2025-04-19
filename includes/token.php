<?php


$token = '';
$errors = [
    'token' => ''
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST['token'];
    if (!empty($token)) {
        if (!$token) {
            $errors['token'] = 'Your Bearer Token is Invalid';
        }
    }else {
        if (empty($token)) $errors['token'] = 'Your Bearer Token is required';
    }
    if (!$errors['token']) {
        $userId = $_SESSION['user_id'];
        $query = $pdo->prepare("UPDATE user SET bearer = ? WHERE id = ?");
        $query->execute([$token,$userId]);
        $_SESSION['token'] = $token;
            header("Location: /Hackathon/dashboard.php");
            exit();
    }
}


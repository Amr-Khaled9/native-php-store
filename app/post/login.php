<?php
session_start();
if (!isset($_POST['login'])) {
    header('location:../../layouts/errors/404.php');
    die;
}
include_once "../requests/Validation.php";
include_once "../models/User.php";

// Validation --> email 
$emailValidation = new Validation;
$emailRequiredResult = $emailValidation->required($_POST['email'], "email");
if (empty($emailRequiredResult)) {
    $emailPattern = "/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/";
    $emailRegexResult = $emailValidation->regex($_POST['email'], "email", $emailPattern);
    if (!empty($emailRegexResult)) {
        $_SESSION['errors']['email']['regex'] = $emailRegexResult;
    }
} else {
    $_SESSION['errors']['email']['required'] = $emailRequiredResult;
}
$passwordValidation = new Validation;
$passwordRequiredResult = $passwordValidation->required($_POST['password'], "password");
if (empty($passwordRequiredResult)) {
    $passwordPattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,15}$/";
    $passwordRegexResult = $passwordValidation->regex($_POST['password'], "password", $passwordPattern);
    if (!empty($passwordRegexResult)) {
        $_SESSION['errors']['password']['regex'] = $passwordRegexResult;
    }
} else {
    $_SESSION['errors']['password']['required'] = $passwordRequiredResult;
}
if (empty($_SESSION['errors'])) {
    // i need your status in database
    $userObject = new User;
    $userObject->setEmail($_POST['email']);
    $userObject->setPassword($_POST['password']);
    $result = $userObject->login(); // one user || no user
    if ($result) {
        $user = $result->fetch_object();
        if ($user->status == 1) {
            // status==>1==>home.php
            if(isset($_POST['remember_me'])){
                setcookie('remember_me',$_POST['email'],time() + (24*60*60) * 30 * 12 , '/'); //بتتحسب ب الثواني 
            }
            $_SESSION['user'] = $user;
            
            header("location: ../../home.php");die;


        } elseif ($user->status == 0) {
            // status==>0==>check-code.php
            $_SESSION['user-email'] = $_POST['email'];
            header("location: ../../check-code.php");
            die;
        } else {
            // status==>2==>BLOCK
            $_SESSION['errors']['email']['wrong'] = "Sorry , Your Account Has Been Blocked";
        }
    }
} else {
    $_SESSION['errors']['email']['wrong'] = "Failed Attempt";
}

header('location:../../login.php');

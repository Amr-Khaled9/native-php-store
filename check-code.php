<?php
session_start();
$title = 'check code';
include_once 'layout/header.php';
include_once "app/middleware/guest.php";
include_once 'app/models/User.php';
$page = ['register', 'forget'];
if ($_GET) {
    if (isset($_GET['page'])) {
        if (!in_array($_GET['page'], $page)) {
            header('location:layout/errors/404.php');
            die;
        }
    } else {
        header('location:layout/errors/404.php');
        die;
    }
} else {
    header('location:layout/errors/404.php');
    die;
}
// code => post
// email => session
// validation
// code => required , integer , digits : 5 , min : 10000 , max : 99999

if ($_POST) {
    $errors = [];
    if (empty($_POST['code'])) {
        $errors['requried'] = " <div class='alert alert-danger'> requierd </div>";
    } else {
        if (strlen($_POST['code']) != 5) {
            $errors['digits'] = "<div class='alert alert-danger'> digits is 5 </div>";
        }
    }
    if (empty($errors)) {
        $userObject = new User;
         $userObject->setCode($_POST['code']);
        $userObject->setEmail($_SESSION['email']);
        $resultCheckCode = $userObject->checkCode();
        if ($resultCheckCode) {
            // $errors['true']='goooo';
            // update email verified at and status
            $userObject->setStatus(1); // لازم اكون عامل في الداتا بيز الافتراضي بتاعو ب 0
            date_default_timezone_set('Africa/Cairo');  // عشان لما يحدد الوقت يجي مظبوط علي وقت مصر 
            $userObject->setEmail_verified_at(date('Y-m-d H:i:s'));
            $resultmakeUserVerified = $userObject->makeUserVerified();
            if ($resultmakeUserVerified){
                // header
                if($_GET['page'] == 'register'){
                    unset($_SESSION['email']);
                    $page = "login.php";
                }elseif($_GET['page']== 'forget'){
                    $page = "reset-password.php";
                }   
                header("location:$page");die;
            }else{
                $errors['something'] = "<div class='alert alert-danger'> Something Went Wrong </div>";
            }
        } else {
            $errors['wrong'] = "<div class='alert alert-danger'> wrong </div>";
        }
    }
}
?>

<div class="login-register-area ptb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 col-md-12 ml-auto mr-auto">
                <div class="login-register-wrapper">
                    <div class="login-register-tab-list nav">
                        <a class="active" data-toggle="tab" href="#lg1">
                            <h4> <?php $title; ?> </h4>
                        </a>

                    </div>
                    <div class="tab-content">
                        <div id="lg1" class="tab-pane active">
                            <div class="login-form-container">
                                <div class="login-register-form">
                                    <form method="post">
                                        <input type="number" min="10000" max="99999" name="code" placeholder="Enter Code">
                                        <?php
                                        if (!empty($errors)) {
                                            foreach ($errors as $key => $value) {
                                                echo ($value);
                                            }
                                        }
                                        ?>
                                        <button type="submit"><span>Check</span></button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>



                    <?php
                    include 'layout/footer-script.php';
                    ?>
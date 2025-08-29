<?php
$title = 'Reset Password';
include_once 'layout/header.php';

include_once "app/middleware/guest.php";
if (empty($_SESSION['email'])) {
    header('location:login.php');
    die;
}
include_once 'app/requests/Validation.php';
include_once 'app/models/User.php';




if ($_POST) {
    //validation password 
    $errors = [];
    $passwordValidation = new Validation;
    $resultPasswordRequired = $passwordValidation->required($_POST['password'], 'password');
    if (empty($resultPasswordRequired)) {
        $pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,15}$/";
        $resultPasswordRegex = $passwordValidation->regex($_POST['password'], 'password', $pattern);
        if (empty($resultPasswordRegex)) {
            $resultPasswordConfirm = $passwordValidation->confirmed($_POST['password'], 'password', $_POST['password_confirmation']);
            if (!empty($resultPasswordConfirm)) {
                $errors['password']['confirmed'] = $resultPasswordConfirm;
            }
        } else {
            $errors['password']['regex'] = $resultPasswordRegex;
        }
    } else {
        $errors['password']['required'] = $resultPasswordRequired;
    }
    // (password_confirmation=>requried )
    $confrimPasswordValidation = new Validation();
    $confrimPasswordRequiredResult = $confrimPasswordValidation->required($_POST['password_confirmation'], 'password');
    if (!empty($confrimPasswordRequiredResult)) {
        $errors['confirm']['required'] = $confrimPasswordRequiredResult;
    }

    if (empty($errors)) {
        // update password in db 
        $userObject = new User;
        $userObject->setPassword($_POST['password']);
        $userObject->setEmail($_SESSION['email']);
        $result = $userObject->updatePasswordByEmail();
        if ($result) {
            unset($_SESSION['email']);
            $success = "Your Password Has Been Successfully Updated";
            header('Refresh:3; url=login.php');
        } else {
            $errors['password']['wrong'] = "Something Went Wrong";
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
                            <h4> <?php echo $title; ?> </h4>
                        </a>

                    </div>
                    <div class="tab-content">
                        <div id="lg1" class="tab-pane active">
                            <div class="login-form-container">
                                <div class="login-register-form">
                                    <form method="post">
                                        <?php
                                        if (!empty($success)) {
                                            echo "<div class='alert alert-success text-center'> $success </div>";

                                        }
                                        ?>
                                        <input type="password" name="password" placeholder="Enter Password ">

                                        <?php
                                        if (!empty($errors)) {
                                            foreach ($errors['password'] as $key => $value) {
                                                echo "  <div class='alert alert-danger'> $value </div>";
                                            }
                                        }
                                        ?>
                                        <input type="password" name="password_confirmation" placeholder="Confrim Password">
                                        <?php
                                        if (!empty($errors['confirm']['password'])) {

                                            echo "  <div class='alert alert-danger'> {$errors['confirm']['password']} </div>";
                                        }
                                        ?>
                                        <div class="button-box">
                                            <div class="login-toggle-btn">

                                            </div>
                                            <button type="submit" name="login"><span>RESET</span></button>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>



                        <?php

                        include 'layout/footer-script.php';
                        ?>
<?php

$title = 'Register';
include 'layout/header.php';
include 'layout/nav.php';
include 'app/middleware/guest.php';
include 'layout/breadcrumb.php';
include 'app/requests/Validation.php';
include 'app/services/mail.php';
include_once 'app/models/User.php';
if ($_POST) {
    // validation --> register 
    // print_r($_POST);die;
    // validation rules
    // first_name=>required,string
    // last_name=>required,string
    // gender=>required,['f','m']
    // email =>required,regular expression(pattern),unique
    // phone => required , regex(pattern) , unique
    // password => required , regex(pattern) , = passwrod_confirmation

    $success = [];

    // Validation-->first_name 
    $first_nameValidation = new Validation;
    $first_nameRequiredResult = $first_nameValidation->required($_POST['first_name'], 'first name');
    if (($first_nameRequiredResult)) {
        $success[] = 'first_name';
    }
    // Validation-->last_name
    $last_nameValidation  = new Validation;
    $last_nameRequiredResult = $last_nameValidation->required($_POST['last_name'], 'last name');
    if (!empty($last_nameRequiredResult)) {
        $success[] ='last_name';
    }
    // Validation-->gender
    $genderValidation  = new Validation;
    $genderRequiredResult = $genderValidation->required($_POST['gender'], 'gender');
    // Validation-->email
    $emailValidation = new Validation;
    $emailRequiredResult = $emailValidation->required($_POST['email'], 'email');
    $pattern = "/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/";
    if (empty($emailRequiredResult)) {
        $emailRegexResult = $emailValidation->regex($_POST['email'], 'email', $pattern);
        if (empty($emailRegexResult)) {
            $emailUniqueResult = $emailValidation->unique('users', 'email', $_POST['email']);
            if (empty($emailUniqueResult)) {
                $success []= 'email';
            }
        }
    }
    // Validation-->phone
    $phoneValidation = new Validation;
    $phoneRequiredResult = $phoneValidation->required($_POST['phone'], 'phone');
    $pattern1 = "/^01[0-2,5,9]{1}[0-9]{8}$/";
    if (empty($phoneRequiredResult)) {
        $phoneRegexResult = $phoneValidation->regex($_POST['phone'], 'phone', $pattern1);
        if (empty($phoneRegexResult)) {
            $phoneUniqueResult = $phoneValidation->unique('users', $_POST['phone'], 'phone');
            if (empty($phoneUniqueResult)) {
                $success[] = 'phone';
            }
        }
    }
    // Validation-->password
    $passwordValidation = new Validation;
    $passwordRequiredResult = $passwordValidation->required($_POST['password'], 'password');
    $pattern2 = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,15}$/";
    if (empty($passwordRequiredResult)) {
        $passwordRegexResult = $passwordValidation->regex($_POST['password'], 'password', $pattern2);
        if (empty($passwordRegexResult)) {
            $passwordConfirmedResult = $passwordValidation->confirmed($_POST['password'], 'password', $_POST['password_confirmation']);
            if (empty($passwordConfirmedResult)) {
                $success[] = 'password';
            }
        }
    }
}

if (
    empty($first_nameRequiredResult) &&
    empty($last_nameRequiredResult) &&
    empty($genderRequiredResult) &&
    empty($emailRequiredResult) &&
    empty($emailRegexResult) &&
    empty($emailUniqueResult) &&
    empty($phoneRequiredResult) &&
    empty($phoneRegexResult) &&
    empty($phoneUniqueResult) &&
    empty($passwordRequiredResult) &&
    empty($passwordRegexResult) &&
    empty($passwordConfirmedResult)
){
    //  echo'insert';
    // hash for password
    // generate code
    // insert user
    $userObject = new User;
    $userObject->setFirst_name($_POST['first_name']);
    $userObject->setLast_name($_POST['last_name']);
    $userObject->setEmail($_POST['email']);
    $userObject->setPhone($_POST['phone']);
    $userObject->setGender($_POST['gender']);
    $userObject->setPassword($_POST['password']);
    $code = rand(10000, 99999);
    $userObject->setCode($code);
    $result = $userObject->create();
    if (isset($result)) {
        // send mail with code
        // mail to => $_POST['email']
        // mail subject => verification code
        // mail body => hello name , your verification code is:12345 thank u.
        $subject = 'verification code';
        $body = "hello {$_POST['first_name']} {$_POST['last_name']} <br> your verification code is:<br>'$code'<br> thank you.";
        $mail = new mail($_POST['email'], $subject, $body);
        $resultMail = $mail->send();
        if (isset($resultMail)) {
            $_SESSION['email'] = $_POST['email'];
           header("location:check-code.php?page=register");
        }
        else{
            $error = "<div class='alert alert-danger'> Try Again Later </div>";
        }
    } else
        $error = "<div class='alert alert-danger'> Try Again Later </div>";;
}
?>

<div class="login-register-area ptb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 col-md-12 ml-auto mr-auto">
                <div class="login-register-wrapper">
                    <div class="login-register-tab-list nav">
                        <a class="active" data-toggle="tab" href="#lg2">
                            <h4> register </h4>
                        </a>
                    </div>

                    <div id="lg2" class="tab-pane active">
                        <div class="login-form-container">
                            <div class="login-register-form">
                                <form action="#" method="post">
                                    <input type="text" name="first_name" placeholder="first name" value="<?php if (isset($_POST['first_name']))  echo $_POST['first_name'];  ?>">
                                    <?php echo empty($first_nameRequiredResult) ? "" : "<div class='alert alert-danger'>$first_nameRequiredResult</div>"; ?>
                                    <input type="text" name="last_name" placeholder="Last name" value="<?php if (isset($_POST['last_name']))  echo $_POST['last_name'];  ?>">
                                    <?php echo empty($last_nameRequiredResult) ? "" : "<div class='alert alert-danger'>$last_nameRequiredResult</div>"; ?>

                                    <input name="email" placeholder="Email" type="email" value="<?php if (isset($_POST['email']))  echo $_POST['email'];  ?>">
                                    <?php echo empty($emailRequiredResult) ? "" : "<div class='alert alert-danger'>$emailRequiredResult</div>"; ?>
                                    <?php echo empty($emailRegexResult) ? "" : "<div class='alert alert-danger'>$emailRegexResult</div>"; ?>
                                    <?php echo empty($emailUniqueResult) ? "" : "<div class='alert alert-danger'>$emailUniqueResult</div>"; ?>

                                    <input type="number" name="phone" placeholder="Phone" value="<?php if (isset($_POST['phone']))  echo $_POST['phone'];  ?>">
                                    <?php echo empty($phoneRequiredResult) ? "" : "<div class='alert alert-danger'>$phoneRequiredResult</div>" ?>
                                    <?php echo empty($phoneRegexResult) ? "" : "<div class='alert alert-danger'>$phoneRegexResult</div>" ?>
                                    <?php echo empty($phoneUniqueResult) ? "" : "<div class='alert alert-danger'>$phoneUniqueResult</div>" ?>

                                    <input type="password" name="password" placeholder="Password">
                                    <?php echo empty($passwordRequiredResult) ? '' : "<div class ='alert alert-danger'>$passwordRequiredResult</div>"; ?>
                                    <?php echo empty($passwordRegexResult) ? '' : "<div class ='alert alert-danger'>$passwordRegexResult</div>"; ?>
                                    <input type="password" name="password_confirmation" placeholder="confirm Password">
                                    <?php echo empty($passwordConfirmedResult) ? '' : "<div class ='alert alert-danger'>$passwordConfirmedResult</div>"; ?>

                                    <select name="gender" id="" class="form-control">
                                        <option <?php if (isset($_POST['gender']) && $_POST['gender'] == 'm') echo 'selected' ?> value="m">male</option>
                                        <option <?php if (isset($_POST['gender']) && $_POST['gender'] == 'f') echo 'selected' ?> value="f">female</option>
                                    </select>

                                    <?php echo empty($genderRequiredResult) ? "" : "<div class='alert alert-danger'>$genderRequiredResult</div>"; ?>
                                    <div class="button-box mt-5">
                                        <button type="submit"><span>Register</span></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>



<?php
include 'layout/footer.php';
include 'layout/footer-script.php';
?>
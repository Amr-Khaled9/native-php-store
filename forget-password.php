<?php
$title = "Forget Password";
include_once "layout/header.php";
include_once "app/requests/Validation.php";
include_once "app/models/User.php";
include_once "app/services/mail.php";
//  validation -> email
if ($_POST) {
    $errors = [];
    $validationEmail = new Validation;
    $resultEmailRequired = $validationEmail->required($_POST['email'], 'email');
    if (empty($resultEmailRequired)) {
        $pattern = "/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/";
        $resultEmailRegex = $validationEmail->regex($_POST['email'], 'email', $pattern);
        if (!empty($resultEmailRegex)) {
            $errors['email-regex'] = $resultEmailRegex;
        }
    } else {
        $errors['email-required'] = $resultEmailRequired;
    }
    if (empty($errors)) {
        // search in db
        $userObject = new user;
        $userObject->setEmail($_POST['email']);
        $result = $userObject->getUserByEmail(); // موجود ولا لا 
        // print_r($result);
        if ($result->num_rows == 1) {
            // المستخدم موجود 
            $user = $result->fetch_object();
            // generate code 
            $code = rand(10000, 99999);
            $userObject->setCode($code);
            $updateResult = $userObject->updateCode();
            if ($updateResult) {
                // ابعتو الكود
                $subject = "Forget Password Code";
                $body = "Hello {$user->first_name} {$user->last_name} <br> your Forget Password code is:<br>$code</br> thank you.";
                $mail = new mail($_POST['email'], $subject, $body);
                $mailResult = $mail->send();
            }
            if ($mailResult) {
                // هخزن Session عشان ارجع بيه الاميل في صفحه checkcode
                $_SESSION['email'] = $_POST['email'];
                header("location:check-code.php?page=forget"); die;
            } else 
                $errors['try-again'] = "Try Again Later";
                }
            else
                $errors['some-wrong'] = "Something Went Wrong";
            
        } else {
            //المتسخدم مش موجود 
            $errors['email-wrong'] = "this email dosen't match our records";
        }
    }

    // generate code 
    // send mail 


?>

<div class="login-register-area ptb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 col-md-12 ml-auto mr-auto">
                <div class="login-register-wrapper">
                    <div class="login-register-tab-list nav">
                        <a class="active" data-toggle="tab" href="#lg1">
                            <h4> <?php echo $title ?> </h4>
                        </a>
                    </div>
                    <div class="tab-content">
                        <div id="lg1" class="tab-pane active">
                            <div class="login-form-container">
                                <div class="login-register-form">
                                    <form method="post">
                                        <input type="email" name="email" placeholder="Enter Your Email Address">
                                        <?php
                                        if (!empty($errors)) {
                                            foreach ($errors as $key => $value) {
                                                echo "<div class='alert alert-danger'>$value</div>";
                                            }
                                        }
                                        ?>
                                        <div class="button-box">
                                            <button type="submit"><span>Verify Email Address</span></button>
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
include_once "layout/footer-script.php";
?>
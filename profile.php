<?php
$title = "My Account";
include_once "layout/header.php";
include_once "layout/nav.php";
include_once "app/middleware/auth.php";
include_once 'layout/breadcrumb.php';
include_once "app/models/User.php";
$userObject = new User;
// print_r($_SESSION["user"]->email);die;
$userObject->setEmail($_SESSION["user"]->email);

if (isset($_POST['update'])) {
    $errors = [];
    if (empty($_POST['first_name']) || empty($_POST['last_name']) || empty($_POST['phone']) || empty($_POST['gender'])) {
        $errors = [];
        $errors['all'] = "<div class='alert alert-danger'> All Feilds Are Required </div>";
    }
    $userObject->setFirst_name($_POST["first_name"]);
    $userObject->setLast_name($_POST["last_name"]);
    $userObject->setPhone($_POST["phone"]);
    $userObject->setGender($_POST["gender"]);

    if ($_FILES['image']['error'] == 0) {
        // data image
        // Validation
        // size 
        $imageSize = 10 ** 6; //1Mega
        $imageByte = $imageSize / (10 ** 6);
        if ($_FILES['image']['size'] > $imageSize) {
            $errors['image-size'] = "<div class='alert alert-danger'> Max upload Size Of Image Is $imageByte Bytes </div>";
        }
        // , extension
        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $availableExtensions = ['jpg', 'png', 'jpeg'];
        if (!in_array($extension, $availableExtensions)) { //هقارن بين القيمه و القيم الجوه (الاراي) 
            $errors['image-extension'] = "<div class='alert alert-danger'> Allowed Exentsions are " . implode(",", $availableExtensions) . " </div>";
        }
        // name image 
        if (empty($errors)) {
            $photoName = uniqid() . '.' . $extension; // sakdfljlks.png
            $photoPath = "assets/img/users/$photoName";
            move_uploaded_file($_FILES['image']['tmp_name'], $photoPath); // بتحفظ الصوره 
            // set image
            $userObject->setImage($photoName);
            $_SESSION['user']->image = $photoName;
        }
    }

    if (empty($errors)) {
        $result = $userObject->update();
        $_SESSION['user']->first_name = $_POST['first_name'];
        $_SESSION['user']->last_name = $_POST['last_name'];
        $_SESSION['user']->phone = $_POST['phone'];
        $_SESSION['user']->gender = $_POST['gender'];
        if ($result) {
            $success = "<div class='alert alert-success'> Updated Successfully </div>";
        } else {
            $errors['all'] = "<div class='alert alert-danger'> Something Went Wrong </div>";
        }
    }
}
if (isset($_POST['update_password'])) {
    // 123456 -> hashed -> compare -> 23153s2f1g32fds2fg1h32
    // 23153s2f1g32fds2fg1h32 
    // old password => required , regex , correct=>database
    // new password => required , regex , confirmed
    // confrim password => required

    // if no validation errors
    // $userObject->setPassword($_POST['new_password']);
    // $result = $userObject->updatePasswordByEmail();
    // if($result){
    //     // print success message
    // }else{
    //     // print error
    // }

}

$result = $userObject->getUserByEmail();
$user = $result->fetch_object();
// print_r($user);




?>

<!-- my account start -->
<div class="checkout-area pb-80 pt-100">
    <div class="container">
        <div class="row">
            <div class="ml-auto mr-auto col-lg-9">
                <div class="checkout-wrapper">
                    <div id="faq" class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5 class="panel-title"><span>1</span> <a data-toggle="collapse" data-parent="#faq" href="#my-account-1">Edit your account information </a></h5>
                            </div>
                            <div id="my-account-1" class="panel-collapse collapse show">
                                <div class="panel-body">
                                    <div class="billing-information-wrapper">
                                        <div class="account-info-wrapper">
                                            <h4>My Account Information</h4>
                                            <h5>Your Personal Details</h5>
                                            <h5><?php if (!empty($errors)) {
                                                    foreach ($errors as $key => $error) {
                                                        echo $error;
                                                    }
                                                }
                                                if (isset($success)) {
                                                    echo $success;
                                                }
                                                ?></h5>
                                        </div>
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-12 ">
                                                    <div class="row">
                                                        <div class="col-4 offset-4">
                                                            <img src="assets/img/users/<?php echo $user->image ?>" alt="" id="image" class="w-100 rounded-circle" style="cursor: pointer;">
                                                            <input type="file" name="image" id="file" class="d-none">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6">
                                                        <div class="billing-info">
                                                            <label>First Name</label>
                                                            <input type="text" name="first_name" value="<?php echo $user->first_name ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6">
                                                        <div class="billing-info">
                                                            <label>Last Name</label>
                                                            <input type="text" name="last_name" value="<?php echo $user->last_name ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6">
                                                        <div class="billing-info">
                                                            <label>Phone</label>
                                                            <input type="text" name="phone" value="<?php echo $user->phone ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6">
                                                        <div class="billing-info">
                                                            <label>Gender</label>
                                                            <select name="gender" id="Gender" class="form-control">>
                                                                <option <?php if ($user->gender == "m") {
                                                                            echo "selected";
                                                                        } ?> value="m">Male</option>
                                                                <option <?php if ($user->gender == "f") {
                                                                            echo "selected";
                                                                        } ?> value="f">Female</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="billing-btn">
                                                    <button type="submit" name="update">UPDATE</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5 class="panel-title"><span>2</span> <a data-toggle="collapse" data-parent="#faq" href="#my-account-2">Change your password </a></h5>
                            </div>
                            <div id="my-account-2" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="billing-information-wrapper">
                                        <div class="account-info-wrapper">
                                            <h4>Change Password</h4>
                                            <h5>Your Password</h5>
                                        </div>
                                        <form  method="post">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12">
                                                    <div class="billing-info">
                                                        <label>Old Password</label>
                                                        <input type="password" name="old_password">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12">
                                                    <div class="billing-info">
                                                        <label>New Password</label>
                                                        <input type="password" name="new_password">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12">
                                                    <div class="billing-info">
                                                        <label>Password Confirm</label>
                                                        <input type="password" name="password_confirmation">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="billing-back-btn">
                                                <div class="billing-btn">
                                                    <button type="submit" name="update_password">UPDATE PASSWORD</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5 class="panel-title"><span>3</span> <a data-toggle="collapse" data-parent="#faq" href="#my-account-3">Modify your address book entries </a></h5>
                            </div>
                            <div id="my-account-3" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="billing-information-wrapper">
                                        <div class="account-info-wrapper">
                                            <h4>Address Book Entries</h4>
                                        </div>
                                        <div class="entries-wrapper">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 d-flex align-items-center justify-content-center">
                                                    <div class="entries-info text-center">
                                                        <p>Farhana hayder (shuvo) </p>
                                                        <p>hastech </p>
                                                        <p> Road#1 , Block#c </p>
                                                        <p> Rampura. </p>
                                                        <p>Dhaka </p>
                                                        <p>Bangladesh </p>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 d-flex align-items-center justify-content-center">
                                                    <div class="entries-edit-delete text-center">
                                                        <a class="edit" href="#">Edit</a>
                                                        <a href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="billing-back-btn">
                                            <div class="billing-back">
                                                <a href="#"><i class="ion-arrow-up-c"></i> back</a>
                                            </div>
                                            <div class="billing-btn">
                                                <button type="submit">Continue</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5 class="panel-title"><span>4</span> <a href="wishlist.html">Modify your wish list </a></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- my account end -->
<!-- Footer style Start -->
<footer class="footer-area pt-75 gray-bg-3">
    <div class="footer-top gray-bg-3 pb-35">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer-widget mb-40">
                        <div class="footer-title mb-25">
                            <h4>My Account</h4>
                        </div>
                        <div class="footer-content">
                            <ul>
                                <li><a href="my-account.html">My Account</a></li>
                                <li><a href="about-us.html">Order History</a></li>
                                <li><a href="wishlist.html">WishList</a></li>
                                <li><a href="#">Newsletter</a></li>
                                <li><a href="about-us.html">Order History</a></li>
                                <li><a href="#">International Orders</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer-widget mb-40">
                        <div class="footer-title mb-25">
                            <h4>Information</h4>
                        </div>
                        <div class="footer-content">
                            <ul>
                                <li><a href="about-us.html">About Us</a></li>
                                <li><a href="#">Delivery Information</a></li>
                                <li><a href="#">Privacy Policy</a></li>
                                <li><a href="#">Terms & Conditions</a></li>
                                <li><a href="#">Customer Service</a></li>
                                <li><a href="#">Return Policy</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer-widget mb-40">
                        <div class="footer-title mb-25">
                            <h4>Quick Links</h4>
                        </div>
                        <div class="footer-content">
                            <ul>
                                <li><a href="#">Support Center</a></li>
                                <li><a href="#">Term & Conditions</a></li>
                                <li><a href="#">Shipping</a></li>
                                <li><a href="#">Privacy Policy</a></li>
                                <li><a href="#">Help</a></li>
                                <li><a href="#">FAQS</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer-widget footer-widget-red footer-black-color mb-40">
                        <div class="footer-title mb-25">
                            <h4>Contact Us</h4>
                        </div>
                        <div class="footer-about">
                            <p>Your current address goes to here,120 haka, angladesh</p>
                            <div class="footer-contact mt-20">
                                <ul>
                                    <li>(+008) 254 254 254 25487</li>
                                    <li>(+009) 358 587 657 6985</li>
                                </ul>
                            </div>
                            <div class="footer-contact mt-20">
                                <ul>
                                    <li>yourmail@example.com</li>
                                    <li>example@admin.com</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom pb-25 pt-25 gray-bg-2">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="copyright">
                        <p><a target="_blank" href="https://www.templateshub.net">Templates Hub</a></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="payment-img f-right">
                        <a href="#">
                            <img alt="" src="assets/img/icon-img/payment.png">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Footer style End -->


<!-- all js here -->
<script src="assets/js/vendor/jquery-1.12.0.min.js"></script>
<script src="assets/js/popper.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/imagesloaded.pkgd.min.js"></script>
<script src="assets/js/isotope.pkgd.min.js"></script>
<script src="assets/js/ajax-mail.js"></script>
<script src="assets/js/owl.carousel.min.js"></script>
<script src="assets/js/plugins.js"></script>
<script src="assets/js/main.js"></script>
</body>

</html>

<?php
include 'layout/footer.php';
include 'layout/footer-script.php';
?>

<script>
    // document.getElementById('image').click(function(){
    //     document.getElementById('file').click();
    // });
    $('#image').on('click', function() {
        $('#file').click();
    });
</script>
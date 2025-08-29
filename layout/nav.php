<?php
include_once "app/models/Category.php";
include_once "app/models/Subcategory.php";

$categoryObject = new Category;
$categoryObject->setStatus(1);
$resultCategory = $categoryObject->read();
$subcategoreObject = new Subcategory;

?>
<!-- header start -->
<header class="header-area gray-bg clearfix">
    <div class="header-bottom">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-4 col-6">
                    <div class="logo">
                        <a href="index.html">
                            <img alt="" src="assets/img/logo/logo.png">
                        </a>
                    </div>
                </div>
                <div class="col-lg-9 col-md-8 col-6">
                    <div class="header-bottom-right">
                        <div class="main-menu">
                            <nav>
                                <ul>
                                    <li class="top-hover"><a href="index.html">home</a>
                                        <ul class="submenu">
                                            <li><a href="home.php"> HOME </a></li>
                                        </ul>
                                    </li>
                                    <li><a href="about-us.html">about</a></li>
                                    <li><a href="shop.php">shop</a></li>
                                    <li class="mega-menu-position top-hover"><a href="shop.html">Categories</a>
                                        <ul class="mega-menu">


                                            <?php if ($resultCategory) {
                                                $categories = $resultCategory->fetch_all(MYSQLI_ASSOC);
                                                // print_r($categories);die;
                                                foreach ($categories as $key => $category) {


                                            ?>
                                                    <li>
                                                        <ul>

                                                            <li class="mega-menu-title"><?php echo $category['name_ar'] ?></li>
                                                            <?php $subcategoreObject->setCategory_id($category['id']);
                                                            $resultSubcategore = $subcategoreObject->getSubsByCats();
                                                            // print_r($resultSubcategore);die;
                                                            if ($resultSubcategore) {
                                                                $subcategories = $resultSubcategore->fetch_all(MYSQLI_ASSOC);
                                                                foreach ($subcategories as $key => $subcategory) {
                                                            ?>
                                                                    <li><a href="shop.php?sub=<?php echo $subcategory['id']; ?>"><?php echo $subcategory['name_ar']; ?> </a></li>


                                                            <?php
                                                                }
                                                            }


                                                            ?>
                                                        </ul>
                                                    </li>

                                            <?php
                                                }
                                            }  ?>

                                    </li>
                                </ul>
                            </nav>
                        </div>
                        <div class="header-currency">
                            <?php
                            if (isset($_SESSION['user'])) {
                            ?>
                                <span class="digit"><?php echo $_SESSION['user']->first_name . ' ' .  $_SESSION['user']->last_name  ?> <i class="ti-angle-down"></i></span>
                                <div class="dollar-submenu">
                                    <ul>
                                        <li><a href="profile.php">Profile</a></li>
                                        <li><a href="app/post/logout.php">Logout</a></li>
                                    </ul>
                                </div>
                            <?php
                            } else {
                            ?>
                                <span class="digit">WELCOME <i class="ti-angle-down"></i></span>
                                <div class="dollar-submenu">
                                    <ul>
                                        <li><a href="login.php">Login</a></li>
                                        <li><a href="register.php">Register</a></li>
                                    </ul>
                                </div>
                            <?php
                            }
                            ?>
                            <div class="header-cart">
                                <a href="#">
                                    <div class="cart-icon">
                                        <i class="ti-shopping-cart"></i>
                                    </div>
                                </a>
                                <div class="shopping-cart-content">
                                    <ul>
                                        <li class="single-shopping-cart">
                                            <div class="shopping-cart-img">
                                                <a href="#"><img alt="" src="assets/img/cart/cart-1.jpg"></a>
                                            </div>
                                            <div class="shopping-cart-title">
                                                <h4><a href="#">Phantom Remote </a></h4>
                                                <h6>Qty: 02</h6>
                                                <span>$260.00</span>
                                            </div>
                                            <div class="shopping-cart-delete">
                                                <a href="#"><i class="ion ion-close"></i></a>
                                            </div>
                                        </li>
                                        <li class="single-shopping-cart">
                                            <div class="shopping-cart-img">
                                                <a href="#"><img alt="" src="assets/img/cart/cart-2.jpg"></a>
                                            </div>
                                            <div class="shopping-cart-title">
                                                <h4><a href="#">Phantom Remote</a></h4>
                                                <h6>Qty: 02</h6>
                                                <span>$260.00</span>
                                            </div>
                                            <div class="shopping-cart-delete">
                                                <a href="#"><i class="ion ion-close"></i></a>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="shopping-cart-total">
                                        <h4>Shipping : <span>$20.00</span></h4>
                                        <h4>Total : <span class="shop-total">$260.00</span></h4>
                                    </div>
                                    <div class="shopping-cart-btn">
                                        <a href="cart-page.html">view cart</a>
                                        <a href="checkout.html">checkout</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mobile-menu-area">
                    <div class="mobile-menu">
                        <nav id="mobile-menu-active">
                            <ul class="menu-overflow">
                                <li><a href="#">HOME</a>
                                    <ul>
                                        <li><a href="index.html">home version 1</a></li>
                                        <li><a href="index-2.html">home version 2</a></li>
                                    </ul>
                                </li>
                                <li><a href="#">pages</a>
                                    <ul>
                                        <li><a href="about-us.html">about us </a></li>
                                        <li><a href="shop.html">shop Grid</a></li>
                                        <li><a href="shop-list.html">shop list</a></li>
                                        <li><a href="product-details.html">product details</a></li>
                                        <li><a href="cart-page.html">cart page</a></li>
                                        <li><a href="checkout.html">checkout</a></li>
                                        <li><a href="wishlist.html">wishlist</a></li>
                                        <li><a href="my-account.html">my account</a></li>
                                        <li><a href="login-register.html">login / register</a></li>
                                        <li><a href="contact.html">contact</a></li>
                                    </ul>
                                </li>
                                <li><a href="shop.html"> Shop </a>
                                    <ul>
                                        <li><a href="#">Categories 01</a>
                                            <ul>
                                                <li><a href="shop.html">Aconite</a></li>
                                                <li><a href="shop.html">Ageratum</a></li>
                                                <li><a href="shop.html">Allium</a></li>
                                                <li><a href="shop.html">Anemone</a></li>
                                                <li><a href="shop.html">Angelica</a></li>
                                                <li><a href="shop.html">Angelonia</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="#">Categories 02</a>
                                            <ul>
                                                <li><a href="shop.html">Balsam</a></li>
                                                <li><a href="shop.html">Baneberry</a></li>
                                                <li><a href="shop.html">Bee Balm</a></li>
                                                <li><a href="shop.html">Begonia</a></li>
                                                <li><a href="shop.html">Bellflower</a></li>
                                                <li><a href="shop.html">Bergenia</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="#">Categories 03</a>
                                            <ul>
                                                <li><a href="shop.html">Caladium</a></li>
                                                <li><a href="shop.html">Calendula</a></li>
                                                <li><a href="shop.html">Carnation</a></li>
                                                <li><a href="shop.html">Catmint</a></li>
                                                <li><a href="shop.html">Celosia</a></li>
                                                <li><a href="shop.html">Chives</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="#">Categories 04</a>
                                            <ul>
                                                <li><a href="shop.html">Daffodil</a></li>
                                                <li><a href="shop.html">Dahlia</a></li>
                                                <li><a href="shop.html">Daisy</a></li>
                                                <li><a href="shop.html">Diascia</a></li>
                                                <li><a href="shop.html">Dusty Miller</a></li>
                                                <li><a href="shop.html">Dame’s Rocket</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li><a href="#">BLOG</a>
                                    <ul>
                                        <li><a href="blog-masonry.html">Blog Masonry</a></li>
                                        <li><a href="#">Blog Standard</a>
                                            <ul>
                                                <li><a href="blog-left-sidebar.html">left sidebar</a></li>
                                                <li><a href="blog-right-sidebar.html">right sidebar</a></li>
                                                <li><a href="blog-no-sidebar.html">no sidebar</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="#">Post Types</a>
                                            <ul>
                                                <li><a href="blog-details-standerd.html">Standard post</a></li>
                                                <li><a href="blog-details-audio.html">audio post</a></li>
                                                <li><a href="blog-details-video.html">video post</a></li>
                                                <li><a href="blog-details-gallery.html">gallery post</a></li>
                                                <li><a href="blog-details-link.html">link post</a></li>
                                                <li><a href="blog-details-quote.html">quote post</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li><a href="contact.html"> Contact us </a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
</header>
<!-- header end -->
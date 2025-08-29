<?php


// allow guests and prevent authenticated users
if(!empty($_SESSION['user'])){
    header('location:home.php');die;
}

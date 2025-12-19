<?php 

include('../config/function.php');

if(isset($_POST['saveAdmin'])){
    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $phone = validate($_POST['phone']);
    $is_ban = validate($_POST['name']) == true ? 1 : 0; // Checked = 1 / Not Check = 0

    if($name != '' && $email != '' && $password != ''){

    }else{
        
    }
}

?>
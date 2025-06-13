<?php
function isValidEmail($email){
    if(!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^",$email))
    { 
        return false;
    }else{
        return true;
    }
}

function isValidMobile($mobile){
    return preg_match("/^[0-9]*$/", $mobile);
}
function containsWhiteSpace($str){
    return preg_match('/\s/',$str);
}
function isValidLatitude($lat) {
    return preg_match('/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/', $lat);
}
function isValidLongitude($long) {
    return preg_match('/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/', $long);
}
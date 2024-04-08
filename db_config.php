<?php

if (!function_exists('connection')) {
    function connection(){
        $host = "localhost";
        $user = "root";
        $pass = "";
    
        $bd = "registro_ventas";
    
        $connect = mysqli_connect($host, $user, $pass);
    
        mysqli_select_db($connect, $bd);
    
        return $connect;
    }
}   

?>
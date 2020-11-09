<?php
    include "Database.php";
    $mydb = new Database('iteh');

// DUTY INSERT
    if(isset($_POST['duty_date']) && $_POST['duty_time']){
        if($_POST['duty_date'] != null && $_POST['duty_time'] != null && $_POST['duty_ti'] != null && $_POST['duty_desc'] != null){

            $d = "'". $_POST['duty_date']. "'";
            $t = "'". $_POST['duty_time']. "'";
            $desc = "'". $_POST['duty_desc']. "'";
            $dutyType = $_POST['duty_ti'];
            $arr=[$d, $t, $desc , $dutyType];
            if($mydb->insert("duties", "date, time, description, type_id", $arr)){
                echo "Obaveza je ubacena";
            }
            else {
                echo "Doslo je do greske! Obaveza nije ubacena";
            }
            $_POST = array();
            exit();
        }
    }

// DUTY UPDATE  
    if(isset($_POST["duty-update"]) && $_POST["duty-update"]="Izmeni stavku"){
        if($_POST["duty-id-update"] != null){
            if($_POST["duty-date-update"] != null && $_POST["duty-time-update"] != null && $_POST["duty-type-update"] != null){
                $id = $_POST["duty-id-update"];
                $d = "'". $_POST["duty-date-update"]. "'";
                $t = "'". $_POST["duty-time-update"]. "'";
                $desc = "'". $_POST["duty-description-update"]. "'";
                $dutyType = $_POST["duty-type-update"];
                $arr=[$d, $t, $desc , $dutyType];
                if($mydb->update("duties", $id, ["date", "time", "description", "type_id"], $arr)){
                    echo "stavka je izmenjena";
                }
                else {
                    echo "stavka nije izmenjena";
                }
                $_POST = array();
                exit();
            }
        }
        else{
            echo "Doslo je do greske";
        }
    }

// DUTY DELETE
    if(isset($_POST['delete_duty_id'])){
        $id = $_POST['delete_duty_id'];
        if($mydb->delete("duties", "id", $id)){
            return true;
        }
        else {
            return false;
        }
        $_POST = array();
        exit();
    }

// TYPE INSERT 
    if(isset($_POST['type_name'])){
        $n = "'". $_POST['type_name']. "'";

        if($mydb->insert("types", "name", [$n])){
            echo "tip obaveze je ubacen ";
        }
        else {
            echo "tip obaveze nije ubacen";
        }
        $_POST = array();
        exit();
    }

// TYPE UPDATE 
    if(isset($_POST['update_type_id']) && isset($_POST['update_type_name'])){
        $id = $_POST['update_type_id'];
        $n = "'". $_POST['update_type_name']. "'";
        $arr = [$n];
        $keys = ["name"];
        if($mydb->update("types", $id, $keys, $arr)){
            return true;
        }
        else{
            return false;
        }
        $_POST = array();
        exit();
    }

// TYPE DELETE 
    if(isset($_POST['delete_type_id'])){
        $id = $_POST['delete_type_id'];
        if($mydb->delete("types", "id", $id) && $mydb->delete("duties", "type_id", $id)){
            return true;
        }
        else {
            return false;
        }
        $_POST = array();
        exit();
    }

?>

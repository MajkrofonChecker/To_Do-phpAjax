<?php
    include "Database.php";
    $mydb = new Database('iteh');

// DUTY INSERT
    if(isset($_POST['duty_date']) && isset($_POST['duty_time']) && isset($_POST['duty_ti']) && isset($_POST['duty_desc'])){
        $d = "'". $_POST['duty_date']. "'";
        $t = "'". $_POST['duty_time']. "'";
        $desc = "'". $_POST['duty_desc']. "'";
        $dutyType = $_POST['duty_ti'];
        $arr=[$d, $t, $desc , $dutyType];
        if($mydb->insert("duties", "date, time, description, type_id", $arr)){
            echo "Obaveza je uspesno ubacena";
        }
        else {
            echo "Doslo je do greske! Obaveza nije ubacena";
        }
        $_POST = array();
        exit();
    }

// DUTY UPDATE  
    if(isset($_POST['update-id']) && isset($_POST['update-duty_date']) && isset($_POST['update-duty_time']) && isset($_POST['update-duty_ti']) && isset($_POST['update-duty_desc'])){
        $id = $_POST['update-id'];
        $d = "'". $_POST['update-duty_date']. "'";
        $t = "'". $_POST['update-duty_time']. "'";
        $desc = "'". $_POST['update-duty_desc']. "'";
        $dutyType = $_POST['update-duty_ti'];
        $arr=[$d, $t, $desc , $dutyType];
        if($mydb->update("duties", $id, ["date", "time", "description", "type_id"], $arr)){
            echo "Obaveza je uspesno izmenjena";
        }
        else {
            echo "Doslo je do greske! Obaveza nije izmenjena";
        }
        $_POST = array();
        exit();
    }

// DUTY DELETE
    if(isset($_POST['delete_duty_id'])){
        $id = $_POST['delete_duty_id'];
        if($mydb->delete("duties", "id", $id)){
            echo "Obaveza je uspesno obrisana";
        }
        else {
            echo "Doslo je do greske! Obaveza nije obrisana";
        }
        $_POST = array();
        exit();
    }

// TYPE INSERT 
    if(isset($_POST['type_name'])){
        $n = "'". $_POST['type_name']. "'";

        if($mydb->insert("types", "name", [$n])){
            echo "Tip obaveze je ubacen ";
        }
        else {
            echo "Doslo je do greske! Tip obaveze nije ubacen";
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
            echo "Tip obaveze je uspesno izmenjen";
        }
        else{
            echo "Doslo je do greske! Tip obaveze nije izmenjen";
        }
        $_POST = array();
        exit();
    }

// TYPE DELETE 
    if(isset($_POST['delete_type_id'])){
        $id = $_POST['delete_type_id'];
        if($mydb->delete("types", "id", $id) && $mydb->delete("duties", "type_id", $id)){
            echo "Tip obaveze je uspesno obrisan";
        }
        else {
            echo "Doslo je do greske! Tip obaveze nije obrisan";
        }
        $_POST = array();
        exit();
    }

// GET
    if(isset($_GET['duties'])){
        if($mydb->select("duties", "duties.id, duties.date, duties.time, duties.description, duties.type_id, types.name", "types", "type_id", "id", null, "duties.date ASC")){
            $duties = array();
            while($arr = $mydb->getResult()->fetch_assoc()){
                array_push($duties, $arr);
            }
            echo json_encode($duties, JSON_HEX_TAG);
        }
        else{ 
            echo null;
        }
    }

    if(isset($_GET['types'])){
        if($arr = $mydb->select("types", "*", null, null, null, null, null)){
            $types = array();
            while($arr = $mydb->getResult()->fetch_assoc()){
                array_push($types, $arr);
            }
            echo json_encode($types, JSON_HEX_TAG);
        }
        else {
            echo null;
        }
    }

?>

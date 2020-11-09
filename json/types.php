<?php 
    include "../Database.php";
    $mydb = new Database('iteh');

    $types = array();

    $mydb->select("types", "*", null, null, null, null, null);

    while($arr = $mydb->getResult()->fetch_assoc()){
        array_push($types, $arr);
    }
    echo json_encode($types);
?>
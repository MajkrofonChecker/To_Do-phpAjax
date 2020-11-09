<?php 
    include "../Database.php";
    $mydb = new Database('iteh');

    $duties = array();

    $sql = "Select duties.id, duties.date, duties.time, duties.description, duties.type_id, types.name FROM duties JOIN types ON duties.type_id = types.id";
    $stmt = $mydb->getDBLink()->prepare($sql);

    $stmt->execute();
    $stmt->bind_result($id, $date, $time, $desc, $type_id, $type);

    while($stmt->fetch()){
        $arr = [
            'id' => $id,
            'date' => $date,
            'time' => $time,
            'description' => $desc,
            'type_id' => $type_id,
            'type' => $type,
        ];
        array_push($duties, $arr);
    }
    echo json_encode($duties, JSON_HEX_TAG);

?>
<?php 

class Database
{
    private $hostname;
    private $username = "root";
    private $password = "";
    private $dbname;
    private $dblink;
    private $result;
    private $records;
    private $affected;

    function __construct($name)
    {
        $this->dbname = $name;
        $this->Connect();
    }

    function Connect(){
        $this->dblink = new mysqli($this->hostname, $this->username, $this->password, $this->dbname);
        if($this->dblink->connect_errno){
            printf("Doslo je do greske prilikom konekcije: ".$this->dblink->connect_error);
            exit();
        }
        $this->dblink->set_charset("utf-8");
    }

    function ExecuteQuery($q){
        $this->result = mysqli_query($this->dblink, $q);

        if ($this->result){
            if(isset($this->result->num_rows)){
                $this->records = $this->result->num_rows;
            }
            if(isset($this->result->affected_rows)){
                $this->affected = $this->result->affected_rows;
            }
            return true;
        }
        else{
            return false;
        }
    }

    function select($table, $rows, $join_table, $key1, $key2, $where, $order){
        $q = "SELECT ".$rows." FROM ".$table;
        if($join_table!=null){
            $q.=" JOIN ".$join_table." ON ".$table.".".$key1."=".$join_table.".".$key2;
        }
        if($where!=null){
            $q.=" WHERE ".$where;
        }
        if($order!=null){
            $q.=" ORDER BY ".$order;
        }

        if($this->ExecuteQuery($q)){
            return true;
        }
        else{
            return false;
        }
    }

    function insert($table, $rows, $values){
        $insert_values = implode(", ", $values);
        $q = "INSERT INTO ".$table;
        if($rows != null){
            $q .= " (". $rows. ")";
        }
        $q .= " VALUES ($insert_values)";

        if($this->ExecuteQuery($q)){
            return true;
        }
        else{
            return false;
        }
    }

    function update($table, $id, $keys, $values){
        $query_values ="";
        $set_query = array();
        for($i =0; $i<sizeof($keys); $i++){
            $set_query[] = "$keys[$i] = $values[$i]";
        }
        $query_values = implode(", ", $set_query);  
        $q = "UPDATE $table SET $query_values WHERE id=$id";

        if($this->ExecuteQuery($q)){
            return true;
        }else{
            return false;
        }
    }

    function delete($table, $id, $id_value){
        $q = "DELETE FROM $table WHERE $table.$id=$id_value";

        if($this->ExecuteQuery($q)){
            return true;
        }
        else{
            return false;
        }
    }

    function getResult(){
        return $this->result;
    }
}

?>
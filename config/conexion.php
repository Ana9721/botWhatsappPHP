<?php 
class Conectar{
    protected $dbh;

    protected function conexion(){
        try{
            $conectar = $this->dbh = new PDO("mysql:local=localhost;dbname=u395210755_webhook","u395210755_webhook","Apibot2025");
            return $conectar;

        } catch (Exception $e){

        }

    }


    public function set_name(){
        return $this->dbh->query("SET NAMES 'utf8' ");
    }
}


?>
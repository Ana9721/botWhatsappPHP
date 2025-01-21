<?php
  class Registro extends Conectar{

     public function insert_registro($log_numero, $log_texto){
      $conectar = parent::conexion();
      parent::set_names();
      $sql = "INSERT INTO tm_log (log_numero, log_texto, fech_crea) VALUES (?,?,now()";
      $sql=$conectar->prepare($sql);
      $sql->bindValue(1,$log_numero);
      $sql->bindValue(2,$log_texto);
      $sql->execute();
  }
}


?>
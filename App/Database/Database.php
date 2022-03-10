<?php
    namespace App\Database;

    class Database{

        public function dbConnection(){
            try{
                $conn = new \PDO(DBDRIVE.':host='.DBHOST.'; port='.DBPORT.'; dbname='.DBNAME,DBUSER,DBPASS);
                $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                return $conn;
            } catch(\PDOException $e){
                echo "Connection error: ".$e->getMessage();
                exit;
            }
        }
    }
<?php
    class db{
        // properties
        private $dbhost = 'localhost';
        private $dbuser = 'root';
        private $dbpass = '';
        private $dbname = 'parcher';

        // connect
        public function connect(){
            // $mysql_connect_str = "mysql:host=$this->dbhost;dbname=$this->dbname;port:4406;charset=utf8";
            // $dbConnection = new PDO($mysql_connect_str, $this->dbuser, $this->dbpass);
            $dbConnection = new PDO('mysql:host=localhost;dbname=parcher;port=3306;charset=utf8', 'root' ,'');
            $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $dbConnection;
        }
    }
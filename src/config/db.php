<?php

    class db{
        private $dbHost = "localhost";
        private $dbUser = "root";
        private $dbPass = "devil1107";
        private $dbName = "ticketsproject";

        //conección
        public function connectDB(){
            $mysqlConnect = "mysql:host=$this->dbHost; dbname=$this->dbName";
            $dbConnetion  = new PDO($mysqlConnect, $this->dbUser, $this->dbPass);
            $dbConnetion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $dbConnetion;
        }
    }
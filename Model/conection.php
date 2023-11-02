<?php
    class conection{
        private $con;
        public function __contruct()
        {
            $this->con = new mysqli('localhost','host','','matricula');
        }
    

        public function getStudent()
        {
            $query = $this->con->query('SELECT * FROM student');


            $result = [];
            $i = 0;
            while ($row = $query->fetch)
             {
                $result[$i] = $row;
                $i++;
            }
            return $result;
        }
    }
?>
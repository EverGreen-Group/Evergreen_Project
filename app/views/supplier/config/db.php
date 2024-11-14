<?php
    $servername="localhost";
    $username="root";
    $password="";
    $database="testdb";

    try {
        $con=mysqli_connect($servername,$username,$password,$database);
    }
    catch(mysqli_sql_exception){
        echo ("Could not connect to the Database!");
    }
    if($con){
        echo "success";
    }
?>























<?php
/*
class Database {
    private static $connection;

    public static function connect() {
        if (!self::$connection) {
            try {
                self::$connection = new PDO('mysql:host=localhost;dbname=tfms', 'root', '');
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Database connection error: " . $e->getMessage());
            }
        }
        return self::$connection;
    }
}
    
*/
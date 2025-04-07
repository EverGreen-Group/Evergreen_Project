<?php
class Database
{
    private $host = DB_HOST;
    private $user = DB_USER;
    private $password = DB_PASSWORD;
    private $dbname = DB_NAME;

    private $dbh;
    private $statement;
    private $error;


    // Method to fetch a single column
    public function fetchColumn() {
        return $this->statement->fetchColumn();
    }

    // Get error info
    public function errorInfo() {
        if ($this->statement) {
            return $this->statement->errorInfo();
        } elseif ($this->dbh) {
            return $this->dbh->errorInfo();
        }
        return ["No active database connection or statement"];
    }

    // Get the last error message
    public function getError() {
        return $this->error;
    }

    // Debug method to log query information
    public function debugQuery($sql) {
        error_log("Executing query: " . $sql);
        try {
            $this->statement = $this->dbh->prepare($sql);
            $success = $this->statement->execute();
            if (!$success) {
                error_log("Query execution failed: " . print_r($this->errorInfo(), true));
            }
            return $success;
        } catch (PDOException $e) {
            error_log("Query error: " . $e->getMessage());
            $this->error = $e->getMessage();
            return false;
        }
    }

    // Modified query method with error logging
    public function query($sql) {
        try {
            error_log("Preparing query: " . $sql);
            $this->statement = $this->dbh->prepare($sql);
            if ($this->statement->execute()) {
                error_log("Query executed successfully");
                return $this->statement;
            }
            error_log("Query execution failed: " . print_r($this->errorInfo(), true));
            return false;
        } catch (PDOException $e) {
            error_log("Query error: " . $e->getMessage());
            $this->error = $e->getMessage();
            return false;
        }
    }

    // Method to check connection status
    public function isConnected() {
        return ($this->dbh !== null);
    }

    public function __construct()
    {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;

        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            // Added recently
            // PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
            // PDO::MYSQL_ATTR_SSL_CA => false
            
        );

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->password, $options);
            error_log("Database connection established successfully");
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            error_log("Database connection error: " . $this->error);
            echo $this->error;
            throw $e;
        }
    }


    public function testConnection() {
        try {
            $stmt = $this->query("SELECT 1");
            if ($stmt) {
                $result = $stmt->fetchColumn();
                error_log("Connection test successful: " . $result);
                return true;
            }
            return false;
        } catch (Exception $e) {
            error_log("Connection test failed: " . $e->getMessage());
            return false;
        }
    }

    // Add prepare method
    public function prepare($sql)
    {
        return $this->dbh->prepare($sql);
    }

    // prepared statement
    // public function query($sql)
    // {
    //     $this->statement = $this->dbh->prepare($sql);
    //     return $this->statement;
    // }

    public function bind($param, $value, $type = NULL)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):  // Fixed the boolean case
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->statement->bindValue($param, $value, $type);
    }

    //execute the prepared statement
    public function execute() {
        try {
            $result = $this->statement->execute();
            if (!$result) {
                $errorInfo = $this->statement->errorInfo();
                $this->error = "SQL Error: " . $errorInfo[2];
                return false;
            }
            return true;
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    //Get multiple records as the result
    public function resultSet()
    {
        $this->execute();
        return $this->statement->fetchAll(PDO::FETCH_OBJ);
    }

    //get single record
    public function single()
    {
        $this->execute();
        return $this->statement->fetch(PDO::FETCH_OBJ);
    }

    //Get Row count
    public function rowCount()
    {
        return $this->statement->rowCount();
    }

    public function beginTransaction() {
        return $this->dbh->beginTransaction();
    }

    public function commit() {
        return $this->dbh->commit();
    }

    public function rollBack() {
        return $this->dbh->rollBack();
    }

    public function lastInsertId() {
        return $this->dbh->lastInsertId();
    }

    public function escapeString($string) {
        return $this->dbh->quote($string);
    }

    public function executeRawQuery($sql) {
        try {
            // Execute the query directly without prepare/bind
            $result = $this->dbh->exec($sql);
            if ($result === false) {
                error_log("Raw query error: " . json_encode($this->dbh->errorInfo()));
                return false;
            }
            return true;
        } catch (PDOException $e) {
            error_log("PDO Exception in raw query: " . $e->getMessage());
            return false;
        }
    }
}
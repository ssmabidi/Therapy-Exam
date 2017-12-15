<?php
require_once '/home/afonseca/code/login.php';

class Db
{
    // The database connection
    protected static $connection;
    protected $db_name = TEP_DB_NAME; // default to TEP db

    // Switch database
    public function switchDB($name)
    {
        if ($name)
        {
            if ($self::$connection)
            {
                unset($self::$connection);
            }
            $this->db_name = $name;
        }
    }

    // Connect to database
    // Returns false on failure, PDO instance on success.
    public function connect()
    {
        // Try and connect to the database
        if(!isset(self::$connection)) 
        {
            try
            {
                self::$connection = new PDO("mysql:host=" . TEP_DB_HOST . 
                    ";dbname=" . $this->db_name . ";charset=utf8", TEP_DB_USERNAME, 
                    TEP_DB_PASSWORD);

                // Configure to throw exceptions on errors
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, 
                    PDO::ERRMODE_EXCEPTION);
            }
            catch(PDOException $pe)
            {
                // If connection was not successfu, log error.
                $errorStr = $pe->getMessage() . "\n" . $pe->getTraceAsString();
                error_log($errorStr);

                // E-mail error             
                error_log($errorStr, 1, "alberto@therapyexamprep.com");
                
                return false;
            }
        }

        return self::$connection;    
    }

    // Query the database
    // Returns number of affected rows
    public function query($query)
    {
        // Connect to database
        $connection = $this->connect();

        if (!$connection)
        {
            throw new DbException("Database connection not available.");
            return;
        }

        try
        {
            // Execute query
            $rows = $connection->exec($query);
            
            return $rows;
        }
        catch(PDOException $pe)
        {
                // Log error.
                $errorStr = "Query: " . $query . "\n" . $pe->getMessage() . 
                "\n" . $pe->getTraceAsString();
                error_log($errorStr);

                // E-mail error             
                error_log($errorStr, 1, "alberto@therapyexamprep.com");

                throw new DbException($errorStr);
        }
    }

    // Query the database using a prepared statement
    // Returns number of affected rows
    public function queryPrepared($query, $values)
    {
        // Connect to database
        $connection = $this->connect();

        if (!$connection)
        {
            throw new DbException("Database connection not available.");
            return;
        }

        try
        {
            // Execute query
            $stmt = $connection->prepare($query);
            $stmt->execute($values);

            $rows = $stmt->rowCount();;
            
            return $rows;
        }
        catch(PDOException $pe)
        {
                // Log error.
                $errorStr = "Query: " . $query . "\n" . $pe->getMessage() . 
                "\n" . $pe->getTraceAsString();
                error_log($errorStr);

                // E-mail error             
                error_log($errorStr, 1, "alberto@therapyexamprep.com");

                throw new DbException($errorStr);
        }
    }
    

    // Fetch rows from the database (SELECT query)
    public function select($query)
    {
        // Connect to database
        $connection = $this->connect();

        if (!$connection)
        {
            throw new DbException("Database connection not available.");
            return;
        }

        try
        {
            // Execute query        
            $stmt = $connection->query($query);

            // Get result rows
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $rows;
        }
        catch(PDOException $pe)
        {
            // Log error.
            $errorStr = "Query: " . $query . "\n" . $pe->getMessage() . 
            "\n" . $pe->getTraceAsString();
            error_log($errorStr);

            // E-mail error             
            error_log($errorStr, 1, "alberto@therapyexamprep.com");

            throw new DbException($errorStr);
        }        
    }

    // Return ID of last insert.
    public function lastInsertID()
    {
        // Connect to database
        $connection = $this->connect();

        if (!$connection)
        {
            throw new DbException("Database connection not available.");
            return;
        }

        try
        {
            return $connection->lastInsertId();
        }
        catch(PDOException $pe)
        {
            // Log error.
            $errorStr = "Query: " . $query . "\n" . $pe->getMessage() . 
            "\n" . $pe->getTraceAsString();
            error_log($errorStr);

            // E-mail error             
            error_log($errorStr, 1, "alberto@therapyexamprep.com");

            throw new DbException($errorStr);            
        }
        
    }
}

class DbException extends Exception {
    public function __construct($message = null, $code = 0) {
            parent::__construct($message, $code);
            //$this->sendNotifications();
            //$this->logError();
        }
}

function test()
{
    $db = new Db();
    if ($db->connect())
    {
        echo "Connection success.";
    }
    else
    {
        echo "Sorry, error occurred.";
    }

    //$db->Select("Hi");
}

// Uncomment to test db connection
//test();


?>
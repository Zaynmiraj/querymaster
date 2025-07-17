<?php
class QueryMaster {
    private $connection;
    private $dbType;
    private $host = "localhost"; 
    private $username = "root";  
    private $password = "";      
    private $dbname = "your_database_name"; 

    // Supported databases
    private $supportedDbTypes = ['mysql', 'pgsql', 'sqlite'];

    public function __construct($dbType = 'mysql', $host = null, $username = null, $password = null, $dbname = null) {
        $this->dbType = $dbType;
        
        if ($host) $this->host = $host;
        if ($username) $this->username = $username;
        if ($password) $this->password = $password;
        if ($dbname) $this->dbname = $dbname;
        
        if (!in_array($this->dbType, $this->supportedDbTypes)) {
            throw new Exception("Unsupported Database Type");
        }

        $this->connect();
    }

    // Connect to the database
    private function connect() {
        try {
            if ($this->dbType == 'mysql') {
                $this->connection = new mysqli($this->host, $this->username, $this->password, $this->dbname);
                if ($this->connection->connect_error) {
                    throw new Exception("Connection failed: " . $this->connection->connect_error);
                }
            } elseif ($this->dbType == 'pgsql') {
                $dsn = "pgsql:host=$this->host;dbname=$this->dbname";
                $this->connection = new PDO($dsn, $this->username, $this->password);
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } elseif ($this->dbType == 'sqlite') {
                $this->connection = new PDO("sqlite:$this->dbname");
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
        } catch (Exception $e) {
            die("Database connection error: " . $e->getMessage());
        }
    }

    // Insert a record into the database
    public function insert($table, $data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));
        
        $query = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $this->prepareAndBind($query, array_values($data));
        return $stmt->execute();
    }

    // Update a record in the database
    public function update($table, $data, $condition) {
        $setValues = "";
        foreach ($data as $column => $value) {
            $setValues .= "$column = ?, ";
        }
        $setValues = rtrim($setValues, ", ");

        $query = "UPDATE $table SET $setValues WHERE $condition";
        $stmt = $this->prepareAndBind($query, array_values($data));
        return $stmt->execute();
    }

    // Delete a record from the database
    public function delete($table, $condition) {
        $query = "DELETE FROM $table WHERE $condition";
        return $this->connection->exec($query);
    }

    // Select records from the database
    public function select($table, $columns = "*", $condition = "1", $limit = null) {
        $query = "SELECT $columns FROM $table WHERE $condition";
        if ($limit) $query .= " LIMIT $limit";

        $stmt = $this->connection->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single record by ID
    public function getById($table, $id) {
        $query = "SELECT * FROM $table WHERE id = ?";
        $stmt = $this->prepareAndBind($query, [$id]);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Find records by condition (returns multiple records)
    public function findByMany($table, $condition, $columns = "*") {
        $query = "SELECT $columns FROM $table WHERE $condition";
        $stmt = $this->connection->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Find a single record by condition
    public function findByOne($table, $condition, $columns = "*") {
        $query = "SELECT $columns FROM $table WHERE $condition LIMIT 1";
        $stmt = $this->connection->query($query);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Prepare and bind parameters
    private function prepareAndBind($query, $params) {
        if ($this->dbType == 'mysql') {
            $stmt = $this->connection->prepare($query);
            foreach ($params as $key => $value) {
                $stmt->bind_param('s', $params[$key]); // assuming 's' for strings, add more if needed
            }
        } else {
            $stmt = $this->connection->prepare($query);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key + 1, $value); // PDO uses 1-based index
            }
        }
        return $stmt;
    }

    // Paginate the results
    public function paginate($table, $columns = "*", $condition = "1", $page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;
        return $this->select($table, $columns, $condition, "$limit OFFSET $offset");
    }

    // Database connection close
    public function close() {
        $this->connection = null;
    }
}
?>

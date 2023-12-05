<?php

class DB
{
    protected $DB_NAME;
    protected $DB_USER;
    protected $DB_PASSWORD;
    protected $DB_HOST;
    public $Fetch;
    public $Manipulate;
    public $Retrieve;
    public $Util;

    public function __construct($DB_NAME = null, $DB_USER = null, $DB_PASSWORD = null, $DB_HOST = null) {
        $this->DB_NAME = $DB_NAME;
        $this->DB_USER = $DB_USER;
        $this->DB_PASSWORD = $DB_PASSWORD;
        $this->DB_HOST = $DB_HOST;
        $this->Fetch = new Fetch;
        $this->Manipulate = new Manipulate;
        $this->Retrieve = new Retrieve;
        $this->Util = new Util;



        $this->connect();
    }

    private function connect()
    {
        try {
            $this->connection = new PDO("mysql:host=$this->DB_HOST;dbname=$this->DB_NAME", $this->DB_USER, $this->DB_PASSWORD);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    private function disconnect()
    {
        $this->connection = null;
    }

    public function query($sql)
    {
        return $this->connection->query($sql);
    }

    public function prepare($sql)
    {
        return $this->connection->prepare($sql);
    }

    public function bind($statement, $parameters)
    {
        foreach ($parameters as $key => $value) {
            $statement->bindParam($key, $value);
        }
    }

    public function execute($statement)
    {
        $statement->execute();
    }

    public function beginTransaction()
    {
        $this->connection->beginTransaction();
    }

    public function commit()
    {
        $this->connection->commit();
    }

    public function rollback()
    {
        $this->connection->rollback();
    }

    private function HandleError($errorMessage)
    {
        die("Error: " . $errorMessage);
    }

    

}

class Fetch
{
    public function id($table, $id)
    {
        $sql = "SELECT * FROM {$table} WHERE id = :id";
        $statement = $this->DB->prepare($sql);
        $this->DB->bind($statement, [':id' => $id]);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function all($table)
    {
        $sql = "SELECT * FROM {$table}";
        $statement = $this->DB->query($sql);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}

class Manipulate
{
    public function insert($table, $data)
    {
        $sql = "INSERT INTO {$table} (column1, column2) VALUES (:value1, :value2)";
        $statement = $this->DB->prepare($sql);
        $this->DB->bind($statement, [':value1' => $data['value1'], ':value2' => $data['value2']]);
        $this->DB->execute($statement);
        return $this->DB->Util->getLastInsertedId();
    }

    public function update($table, $id, $data)
    {
        $sql = "UPDATE {$table} SET column1 = :value1, column2 = :value2 WHERE id = :id";
        $statement = $this->DB->prepare($sql);
        $this->DB->bind($statement, [':value1' => $data['value1'], ':value2' => $data['value2'], ':id' => $id]);
        $this->DB->execute($statement);
        return $this->DB->Util->getRowCount();
    }

    public function delete($table, $id)
    {
        $sql = "DELETE FROM {$table} WHERE id = :id";
        $statement = $this->DB->prepare($sql);
        $this->DB->bind($statement, [':id' => $id]);
        $this->DB->execute($statement);
        return $this->DB->Util->getRowCount();
    }
}

class Retrieve
{
    public function select($table, $columns, $conditions = [])
    {
        $sql = "SELECT " . implode(', ', $columns) . " FROM {$table}";
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }
        $statement = $this->DB->query($sql);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function join($table, $onCondition, $columns)
    {
        $sql = "SELECT " . implode(', ', $columns) . " FROM {$table} INNER JOIN {$table} ON {$onCondition}";
        $statement = $this->DB->query($sql);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function aggregate($function, $table, $column)
    {
        $sql = "SELECT {$function}({$column}) FROM {$table}";
        $statement = $this->DB->query($sql);
        return $statement->fetchColumn();
    }
}

class Util
{
    public function getLastInsertedId()
    {
        return $this->DB->connection->lastInsertId();
    }

    public function getRowCount()
    {
        return $this->DB->statement->rowCount();
    }

    public function sanitize($data)
    {
        return htmlspecialchars($data);
    }

    public function validate($data)
    {
        return $data;
    }
}

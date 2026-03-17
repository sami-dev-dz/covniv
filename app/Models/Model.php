<?php
// app/Models/Model.php

require_once __DIR__ . '/../../config/database.php';

abstract class Model {
    protected static $table;
    protected static $primaryKey = 'id';
    protected $db;
    
    public function __construct() {
        $this->db = Database::getConnection();
    }
    
    /**
     * Find a record by ID
     */
    public static function find($id) {
        $db = Database::getConnection();
        $table = static::$table;
        $primaryKey = static::$primaryKey;
        
        $stmt = $db->prepare("SELECT * FROM {$table} WHERE {$primaryKey} = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $instance = new static();
            foreach ($result as $key => $value) {
                $instance->$key = $value;
            }
            return $instance;
        }
        return null;
    }
    
    /**
     * Find all records
     */
    public static function all() {
        $db = Database::getConnection();
        $table = static::$table;
        
        $stmt = $db->query("SELECT * FROM {$table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Find records by conditions
     */
    public static function where($conditions, $params = []) {
        $db = Database::getConnection();
        $table = static::$table;
        
        $whereClause = implode(' AND ', array_map(function($key) {
            return "{$key} = :{$key}";
        }, array_keys($conditions)));
        
        $sql = "SELECT * FROM {$table} WHERE {$whereClause}";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Find one record by conditions
     */
    public static function findOne($conditions, $params = []) {
        $db = Database::getConnection();
        $table = static::$table;
        
        $whereClause = implode(' AND ', array_map(function($key) {
            return "{$key} = :{$key}";
        }, array_keys($conditions)));
        
        $sql = "SELECT * FROM {$table} WHERE {$whereClause} LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $instance = new static();
            foreach ($result as $key => $value) {
                $instance->$key = $value;
            }
            return $instance;
        }
        return null;
    }
    
    /**
     * Create a new record
     */
    public static function create($data) {
        $db = Database::getConnection();
        $table = static::$table;
        
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $db->prepare($sql);
        
        if ($stmt->execute($data)) {
            return $db->lastInsertId();
        }
        return false;
    }
    
    /**
     * Update a record
     */
    public function update($data) {
        $primaryKey = static::$primaryKey;
        $table = static::$table;
        
        if (!isset($this->$primaryKey)) {
            return false;
        }
        
        $setClause = implode(', ', array_map(function($key) {
            return "{$key} = :{$key}";
        }, array_keys($data)));
        
        $sql = "UPDATE {$table} SET {$setClause} WHERE {$primaryKey} = :id";
        $data[':id'] = $this->$primaryKey;
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    /**
     * Delete a record
     */
    public function delete() {
        $primaryKey = static::$primaryKey;
        $table = static::$table;
        
        if (!isset($this->$primaryKey)) {
            return false;
        }
        
        $sql = "DELETE FROM {$table} WHERE {$primaryKey} = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $this->$primaryKey]);
    }
    
    /**
     * Get attribute
     */
    public function __get($name) {
        if (isset($this->$name)) {
            return $this->$name;
        }
        return null;
    }
    
    /**
     * Set attribute
     */
    public function __set($name, $value) {
        $this->$name = $value;
    }
}

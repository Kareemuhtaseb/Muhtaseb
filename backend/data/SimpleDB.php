<?php

class SimpleDB {
    private $dataDir;
    
    public function __construct() {
        $this->dataDir = __DIR__ . "/data";
    }
    
    public function getAll($table) {
        $file = $this->dataDir . "/$table.json";
        if (file_exists($file)) {
            return json_decode(file_get_contents($file), true);
        }
        return [];
    }
    
    public function getById($table, $id) {
        $data = $this->getAll($table);
        foreach ($data as $record) {
            if ($record["id"] == $id) {
                return $record;
            }
        }
        return null;
    }
    
    public function create($table, $data) {
        $records = $this->getAll($table);
        $data["id"] = count($records) + 1;
        $records[] = $data;
        file_put_contents($this->dataDir . "/$table.json", json_encode($records, JSON_PRETTY_PRINT));
        return $data;
    }
    
    public function update($table, $id, $data) {
        $records = $this->getAll($table);
        foreach ($records as &$record) {
            if ($record["id"] == $id) {
                $record = array_merge($record, $data);
                break;
            }
        }
        file_put_contents($this->dataDir . "/$table.json", json_encode($records, JSON_PRETTY_PRINT));
        return true;
    }
    
    public function delete($table, $id) {
        $records = $this->getAll($table);
        $records = array_filter($records, function($record) use ($id) {
            return $record["id"] != $id;
        });
        file_put_contents($this->dataDir . "/$table.json", json_encode(array_values($records), JSON_PRETTY_PRINT));
        return true;
    }
    
    public function getStats() {
        $stats = [];
        $tables = ["users", "properties", "units", "owners", "income", "expenses", "contracts"];
        
        foreach ($tables as $table) {
            $stats[$table] = count($this->getAll($table));
        }
        
        return $stats;
    }
}

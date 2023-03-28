<?php

namespace App\Core;

use PDO;

class Migration
{
    public array $migrations;

    public Database $db;

    const BLACK_COLOR = "\033[01;30m";
    const RED_COLOR = "\033[01;31m";
    const GREEN_COLOR = "\033[01;32m";
    const YELLOW_COLOR = "\033[01;33m";
    const BLUE_COLOR = "\033[01;34m";
    const MAGENTA_COLOR = "\033[01;35m";
    const CYAN_COLOR = "\033[01;36m";
    const LIGHT_GRAY_COLOR = "\033[01;37m";
    const DEFAULT_COLOR = "\033[01;39m";
    const DARK_GRAY_COLOR = "\033[01;90m";
    const LIGHT_RED_COLOR = "\033[01;91m";
    const LIGHT_GREEN_COLOR = "\033[01;92m";
    const LIGHT_YELLOW_COLOR = "\033[01;93m";
    const LIGHT_BLUE_COLOR = "\033[01;94m";
    const LIGHT_MAGENTA_COLOR = "\033[01;95m";
    const LIGHT_CYAN_COLOR = "\033[01;96m";
    const WHITE_COLOR = "\033[01;97m";
    
    function __construct()
    {
        $this->db = Application::$app->db;
    }

    public function migrate()
    {
        if ($this->isFreshAction()){
            $this->cleanMigrations();
            $this->dropAllTables();
        }
        
        $this->createMigrationsTable();
        $this->getMigrations();

        $this->isDownAction() ? $this->downMigration() : $this->upMigration();
    }

    // TODO: should be refactored move db logic to db class
    public function createMigrationsTable()
    {
        $this->db
            ->connection()
            ->exec(
                'CREATE TABLE IF NOT EXISTS migrations (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    migration VARCHAR(255) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=INNODB;'
            );
    }

    // TODO: should be refactored move db logic to db class
    public function getMigrations() 
    {
        $statement = $this->db
            ->connection()
            ->prepare('SELECT migration FROM migrations');
        
        $statement->execute();

        return $this->migrations = $statement->fetchAll(PDO::FETCH_COLUMN);
    }

    // TODO: should be refactored move db logic to db class
    public function addCreatedMigrations(array $migrations) 
    {
        $query = implode(', ', array_map(fn($m) => "('{$m}')", $migrations));

        $statement = $this->db
            ->connection()
            ->prepare("INSERT INTO migrations (migration) VALUES $query");

        $statement->execute();
    }
    
    public function isFreshAction() 
    {        
        global $argv;

        return ($argv[1] ?? "") === "fresh";
    }
    
    public function isDownAction() 
    {        
        global $argv;

        return ($argv[1] ?? "") === "down";
    }

    public function cleanMigrations() 
    {
        $statement = $this->db->connection()->prepare("DELETE FROM migrations");
        
        $statement->execute();
    }
    
    public function dropAllTables()
    {
        $this->db->connection()->exec("SET FOREIGN_KEY_CHECKS = 0");
        
        $tables = $this->tables();
        foreach ($tables as $table) {
            if ($table === "migrations") continue;

            $this->db->connection()->exec("DROP TABLE IF EXISTS {$table};");
        }

        $this->db->connection()->exec("SET FOREIGN_KEY_CHECKS = 1");
    }

    public function tables() 
    {
        $dbName = Application::$app->config["db"]["name"] ?? NULL;

        $statement = $this->db->connection()
            ->prepare(
                "SELECT
                    table_name
                FROM
                    information_schema.tables
                WHERE
                    table_schema = ?"
            );

        $statement->execute([$dbName]);

        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }

    public function log($message, $color = self::GREEN_COLOR, $withDate = true) 
    {
        $date = $withDate ? "[". date("Y-m-d H:i:s") ."] " : "";

        echo PHP_EOL . $color . $date . $message . PHP_EOL;
    }

    protected function upMigration()
    {

        $files = scandir(Application::$ROOT_DIR . "/migrations");
        $notMigrated = array_diff($files, $this->migrations);

        $createdMigrations = [];
        foreach ($notMigrated as $migration) {
            if (in_array($migration, [".", ".."])) continue;

            require Application::$ROOT_DIR . '/migrations/' . $migration;

            $class = pathinfo($migration, PATHINFO_FILENAME);
            
            $this->log("Creating migration {$class}");
            
            (new $class)->up();
            
            $this->log("Created migration {$class}");

            $createdMigrations[] = $migration;
        }

        if (empty($createdMigrations)) {
            $this->log("No migrations to migrate", self::RED_COLOR, false);
            return;
        }
    
        $this->addCreatedMigrations($createdMigrations);
    }

    protected function downMigration()
    {

        $this->cleanMigrations();
        
        $migrations = array_reverse(scandir(Application::$ROOT_DIR . "/migrations"));

        foreach ($migrations as $migration) {
            if (in_array($migration, [".", ".."])) continue;

            require Application::$ROOT_DIR . '/migrations/' . $migration;

            $class = pathinfo($migration, PATHINFO_FILENAME);
            
            $this->log("Down migration {$class} Started");
            
            (new $class)->down();
            
            $this->log("Down migration {$class} Ended");

            $createdMigrations[] = $migration;
        }
    }

    public function execute($query) 
    {
        if ($query) $this->db->connection()->exec($query);
    }
}
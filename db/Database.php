<?php


namespace VMSMeruDairy\core\db;


use VMSMeruDairy\core\Application;

class Database
{

    public \PDO $pdo;
    public function __construct($dbConfig = [])
    {
        $dbDsn = $dbConfig['dsn'] ?? '';
        $username = $dbConfig['user'] ?? '';
        $password = $dbConfig['password'] ?? '';
        $this->pdo = new \PDO($dbDsn, $username, $password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
    public function applyMigrations()
    {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();

        $newMigrations = [];
        $files = scandir(Application::$ROOT_DIR . '/migrations');
        $toApplyMigrations = array_diff($files, $appliedMigrations);
        foreach ($toApplyMigrations as $migration) {
            if ($migration === '.' || $migration === '..') {
                continue;
            }

            require_once Application::$ROOT_DIR . '/migrations/' . $migration;
            $className = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $className();
            $this->log("Applying migration $migration");
            $instance->up();
            $this->log("Applied migration $migration");
            $newMigrations[] = $migration;
        }

        if (!empty($newMigrations)) {
            $this->saveMigrations($newMigrations);
        } else {
            $this->log("There are no migrations to apply");
        }
    }

    protected function createMigrationsTable()
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )  ENGINE=INNODB;");
    }

    protected function getAppliedMigrations()
    {
        $statement = $this->pdo->prepare("SELECT migration FROM migrations");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    protected function saveMigrations(array $newMigrations)
    {
        $str = implode(',', array_map(fn($m) => "('$m')", $newMigrations));
        $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES  $str ");
        $statement->execute();
    }

    public function prepare($sql): \PDOStatement
    {
        return $this->pdo->prepare($sql);
    }
   public function getAll($tables, $columns = '*', $conditions = [])
   {
        if (!is_array($tables)) {
            $tables = [$tables];
        }
        // Build the SQL query
        $sql = 'SELECT ' . $columns . ' FROM ' . implode(', ', $tables);
        // Add conditions if provided
       if (!empty($conditions)) {
           $sql .= ' WHERE ' . implode(' AND ', array_map(function ($key) {
                   return "$key = :$key";
               }, array_keys($conditions)));
       }

    // Prepare the statement
           $stmt = $this->pdo->prepare($sql);

// Bind parameters
       foreach ($conditions as $key => &$value) {
           $stmt->bindParam($key, $value);
       }
        // Execute the query
        $stmt->execute();
        // Fetch all rows as an associative array
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
}
    private function log($message)
    {
        echo "[" . date("Y-m-d H:i:s") . "] - " . $message . PHP_EOL;
    }
}
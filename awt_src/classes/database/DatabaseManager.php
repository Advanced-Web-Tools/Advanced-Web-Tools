<?php

namespace database;

require ROOT . "/awt_db.php";

use PDO;
use PDOException;

/**
 * DatabaseManager Class
 *
 * The DatabaseManager class provides a data access layer for interacting
 * with a database using PDO (PHP Data Objects). It encapsulates common
 * database operations such as inserting, selecting, updating, and deleting
 * records, while promoting code reuse and reducing redundancy in database
 * interactions.
 *
 *Features:
 *
 * - Connection Management:
 *   - Establishes a connection to the database using defined credentials.
 *   - Handles connection errors by throwing exceptions.
 *
 * - Table Operations:
 *   - Allows chaining of methods to specify the target database table for operations.
 *
 * - Insert Operation:
 *   - Facilitates insertion of new records into a specified table.
 *   - Prepares and executes SQL statements with bound parameters.
 *
 * - Select Operation:
 *   - Supports selecting records with options for specifying columns,
 *     applying joins, and filtering results with WHERE clauses.
 *   - Returns results as an associative array.
 *
 * - Update Operation:
 *   - Provides functionality to update existing records based on specified conditions.
 *
 * - Delete Operation:
 *   - Allows deletion of records from a specified table based on conditions.
 *
 * - Schema Inspection:
 *   - Includes methods to retrieve table structures and check for the existence
 *     of tables and columns in the database.
 *
 * - Destructor:
 *   - Cleans up class properties and connection resources when the object is destroyed.
 */
class DatabaseManager
{
    public array $tables = [];
    private string $hostname = DB_HOSTNAME;
    private string $username = DB_USERNAME;
    private string $password = DB_PASSWORD;
    private string $database = DB_NAME;
    private string $sql = '';
    private string $selectQuery = '';
    private string $joinQuery = '';
    private string $whereQuery = '';
    private array $orderBy = [];
    private string $tableName = '';
    private array $columns = [];
    private array $values = [];
    private array $joins = [];
    private array $conditions = [];
    protected ?PDO $pdo;
    private string $lastQuery = '';

    /**
     * Constructor method that initializes the PDO connection to the database.
     * It sets the error mode to exceptions for better error handling.
     */

    public function __construct()
    {
        global $shared;

        $dsn = DB_TYPE . ":host={$this->hostname};dbname={$this->database}";

        if(!isset($shared["DBEngine"]["PDO"])) {
            try {
                $this->pdo = new PDO($dsn, $this->username, $this->password);
                $this->pdo->setAttribute(PDO::ATTR_PERSISTENT, PDO::ERRMODE_EXCEPTION);
                $shared["DBEngine"]["PDO"] = $this->pdo;
            } catch (PDOException $e) {
                die('Connection failed: ' . $e->getMessage());
            }
        } else {
            $this->pdo = $shared["DBEngine"]["PDO"];
        }
    }

    public function __destruct() {
        $this->pdo = null;
    }

    /**
     * Specifies the table to operate on.
     *
     * @param string $name The name of the table.
     * @return $this The instance of the DatabaseManager for chaining.
     */
    public function table(string $name): self
    {
        $this->tableName = $name;
        return $this;
    }

    /**
     * Builds an insert query by accepting an associative array of column-value pairs.
     *
     * @param array $data Associative array where keys are columns and values are the values to insert.
     * @return $this The instance of the DatabaseManager for chaining.
     */
    public function insert(array $data): self
    {
        foreach ($data as $column => $value) {
            $this->columns[] = $column;
            $this->values[":{$column}"] = $value;
        }
        return $this;
    }

    /**
     * Executes the built insert query and returns the last inserted ID.
     *
     * @return int|null Returns the ID of the inserted row or null on failure.
     * @throws PDOException If there is a mismatch between the number of columns and values.
     */
    public function executeInsert(): ?int
    {
        $columnList = implode(', ', $this->columns);
        $placeholderList = implode(', ', array_keys($this->values));

        // Check if the number of columns matches the number of values
        if (count($this->columns) !== count($this->values)) {
            throw new PDOException("Column count does not match value count.");
        }

        $sql = "INSERT INTO {$this->tableName} ({$columnList}) VALUES ({$placeholderList})";

        $stmt = $this->pdo->prepare($sql);

        foreach ($this->values as $placeholder => $value) {
            $stmt->bindValue($placeholder, $value);
        }

        if ($stmt->execute()) {
            $lastInsertId = (int)$this->pdo->lastInsertId();
            $stmt->closeCursor();
            $this->columns = array();
            $this->values = array();
            return $lastInsertId;
        }

        $stmt->closeCursor();
        $this->columns = array();
        $this->values = array();

        self::showDebugTrace();

        $this->reset();
        return null;
    }

    /**
     * Builds a SELECT query by specifying columns to retrieve.
     *
     * @param array $columns List of column names to select.
     * @return $this The instance of the DatabaseManager for chaining.
     */
    public function select(array $columns = ['*']): self
    {
        $columnList = implode(', ', $columns);
        $this->selectQuery = "SELECT {$columnList} FROM {$this->tableName}";
        return $this;
    }

    /**
     * Adds a join clause to the query.
     *
     * @param string $table The table to join.
     * @param string $on The condition for joining.
     * @param string $type The type of join (e.g., INNER, LEFT).
     * @return $this The instance of the DatabaseManager for chaining.
     */
    public function join(string $table, string $on, string $type = 'INNER'): self
    {
        $this->joins[] = " {$type} JOIN {$table} ON {$on}";
        return $this;
    }

    /**
     * Adds a WHERE clause to the query with conditions.
     *
     * @param array $conditions Associative array where keys are columns and values are the values to filter by.
     * @param bool $useNot Whether to use != instead of = in the condition.
     * @return $this The instance of the DatabaseManager for chaining.
     */
    public function where(array $conditions, bool $useNot = false, string $conjunction = 'AND'): self
    {
        $conjunction = strtoupper($conjunction);
        if (!in_array($conjunction, ['AND', 'OR'])) {
            $conjunction = 'AND';
        }

        $whereClauses = [];
        foreach ($conditions as $column => $value) {
            $operator = $useNot ? "!=" : "=";
            $whereClauses[] = "{$column} {$operator} :{$column}";
            $this->conditions[":{$column}"] = $value;
        }
        $this->whereQuery = " WHERE " . implode(" {$conjunction} ", $whereClauses);
        return $this;
    }


    /**
     * Adds a LIKE clause to the query with conditions.
     *
     * @param array $conditions Associative array where keys are columns and values are the patterns to match.
     * @param bool $useNot Whether to use NOT LIKE instead of LIKE in the condition.
     * @return $this The instance of the DatabaseManager for chaining.
     */
    public function like(array $conditions, bool $useNot = false): self
    {
        $likeClauses = [];
        foreach ($conditions as $column => $value) {
            $operator = $useNot ? "NOT LIKE" : "LIKE";
            $likeClauses[] = "{$column} {$operator} :{$column}";
            $this->conditions[":{$column}"] = $value;
        }
        $this->whereQuery = " WHERE " . implode(' AND ', $likeClauses);
        return $this;
    }


    /**
     * Adds an ORDER BY clause to the query.
     *
     * @param string $column The column to order by.
     * @param string $direction The direction of sorting (ASC or DESC).
     * @return $this The instance of the DatabaseManager for chaining.
     */
    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $this->orderBy[] = "$column $direction";
        return $this;
    }

    /**
     * Executes the SELECT query and retrieves the results as an associative array.
     *
     * @param int|null $offset Optional offset for LIMIT clause.
     * @param int|null $limit Optional limit for LIMIT clause.
     * @return array The resulting rows as an associative array.
     */
    public function get(?int $offset = null, ?int $limit = null): array
    {
        $sql = $this->selectQuery . implode('', $this->joins) . $this->whereQuery;

        if (!empty($this->orderBy)) {
            $sql .= ' ORDER BY ' . implode(', ', $this->orderBy);
        }

        if ($limit !== null) {
            $sql .= ' LIMIT :limit';
            if ($offset !== null) {
                $sql .= ' OFFSET :offset';
            }
        }

        $stmt = $this->pdo->prepare($sql);

        foreach ($this->conditions as $placeholder => $value) {
            $stmt->bindValue($placeholder, $value);
        }

        if ($limit !== null) {
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            if ($offset !== null) {
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            }
        }

        $this->sql = $sql;

        try {
            $stmt->execute();
        } catch (PDOException $e) {
            $stmt->closeCursor();
            if (DEBUG)
                die("Error has occurred: " . $e->getMessage() . "<br>" . "SQL: " . $sql);
        }

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        self::showDebugTrace();
        $this->reset();
        return $result;
    }

    /**
     * Builds and executes an UPDATE query.
     *
     * @param array $data Associative array where keys are columns and values are the new values to update.
     * @return bool Returns true on success, false on failure.
     */
    public function update(array $data): bool
    {
        $setClauses = [];
        foreach ($data as $column => $value) {
            if ($value === 'DEFAULT') {
                $setClauses[] = "{$column} = DEFAULT"; // Set to DEFAULT directly
            } else {
                $setClauses[] = "{$column} = :{$column}";
                $this->values[":{$column}"] = $value;
            }
        }

        $setQuery = implode(', ', $setClauses);
        $sql = "UPDATE {$this->tableName} SET {$setQuery}" . $this->whereQuery;

        $stmt = $this->pdo->prepare($sql);

        foreach ($this->conditions as $placeholder => $value) {
            $stmt->bindValue($placeholder, $value);
        }

        foreach ($this->values as $placeholder => $value) {
            $stmt->bindValue($placeholder, $value);
        }

        $result = $stmt->execute();
        $stmt->closeCursor();
        self::showDebugTrace();

        $this->reset();
        return $result;
    }

    /**
     * Executes a DELETE query with the conditions specified in the WHERE clause.
     *
     * @return bool Returns true on success, false on failure.
     */
    public function delete(): bool
    {
        if($this->whereQuery === '') {
            if(DEBUG) {
                $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5);

                foreach ($trace as $level) {
                    echo "File: " . ($level['file'] ?? '[internal]') . " Line: " . ($level['line'] ?? '?') . "<br>";
                    echo "Function: " . ($level['function'] ?? '[global]') . "<br><br>";
                }

                die("No WHERE clause specified for DELETE query. This is a dangerous operation. Please check your code.<br>If your intention is to delete all records, use where(['1'=>'1']) instead.");
            }

            return false;
        }


        $sql = "DELETE FROM {$this->tableName}" . $this->whereQuery;

        $stmt = $this->pdo->prepare($sql);

        foreach ($this->conditions as $placeholder => $value) {
            $stmt->bindValue($placeholder, $value);
        }

        $result = $stmt->execute();
        $stmt->closeCursor();
        self::showDebugTrace();

        $this->reset();
        return $result;
    }

    /**
     * Retrieves a list of tables by performing a join with a predefined table structure.
     *
     * @return $this The instance of the DatabaseManager for chaining.
     */
    public function getTables(): self
    {
        $this->tables = $this->table('awt_table')->select(['*'])
            ->where(['1' => 1])
            ->join("awt_table_structure", "awt_table.id = awt_table_structure.table_id")
            ->get();
        return $this;
    }

    /**
     * Checks if a specific table exists in the database.
     *
     * @param string $table The name of the table.
     * @return bool Returns true if the table exists, false otherwise.
     */
    public function checkTable(string $table): bool
    {
        if(empty($this->tables))
            $this->getTables();

        foreach ($this->tables as $tables) {
            if (array_key_exists('name', $tables) && $tables["name"] === $table) {
                return true;
            }
        }
        return false;
    }

    /**
     * Checks if a specific column exists within a table.
     *
     * @param string $table The table name.
     * @param string $column The column name.
     * @return bool Returns true if the column exists, false otherwise.
     */
    public function checkColumn(string $table, string $column): bool
    {
        if (empty($this->tables)) {
            $this->getTables();
        }

        foreach ($this->tables as $tables) {
            if (array_key_exists('name', $tables) && $tables["column_name"] === $column && $tables["name"] === $table) {
                return true;
            }
        }
        return false;
    }


    public function getLastQuery(): string
    {
        return $this->lastQuery;
    }

    private static function getCallerChain(): string
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $files = [];

        foreach ($backtrace as $trace) {
            if (isset($trace['file'])) {
                $filename = basename($trace['file']);
                $line = $trace['line'] ?? '?';
                $files[] = "{$filename}:{$line}";
            }
        }

        // Reverse to show caller first
        $files = array_reverse($files);

        return implode('->', $files);
    }

    private static function showDebugTrace(): void
    {
        if(DEBUG && SHOW_SQL_CONNECTIONS_CALLS)
            echo "SQL Connection called by: " . self::getCallerChain() . "<br>";
    }


    public function reset(): void
    {
        $this->lastQuery = $this->sql;
        $this->sql = '';
        $this->selectQuery = '';
        $this->joinQuery = '';
        $this->whereQuery = '';
        $this->orderBy = [];
        $this->tableName = '';
        $this->columns = [];
        $this->values = [];
        $this->joins = [];
        $this->conditions = [];
        $this->tables = [];

        if ($this->pdo !== null) {
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

    }
}
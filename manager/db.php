<?php

namespace manager;
/**
 * Class db
 * A simple PDO driver for MySql, MariaDB inspired from moodle from framework
 * @package manager
 */
class db
{
    private $pdo;
    public $host;
    public $dbname;
    public $username;
    public $password;
    public $dbtype;
    public $is_connected = false;
    public $table_prefix = '';

    /**
     * db constructor that takes value from the config file
     */
    public function __construct()
    {
        global $C;
        $this->host = $C->db_host;
        $this->dbname = $C->db_name;
        $this->username = $C->db_username;
        $this->password = $C->db_password;
        $this->dbtype = $C->db_type;
        $this->table_prefix = $C->db_table_prefix;
        $this->connect();
    }

    /**
     * Connects to the database
     */
    public function connect()
    {
        global $LOG;
        if (isset($this->dbtype) && isset($this->host)) {
            try {
                $this->pdo = new \PDO("$this->dbtype:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
                //set the PDO error mode to exception
                $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $this->is_connected = true;
            } catch (\PDOException $e) {
                $LOG->write_log($e->getTraceAsString());
            }
        }
    }

    /**
     * Creates new table from the specified schema
     */
    public function create_tables()
    {
        global $C, $DB;
        require_once($C->dirroot . '/db/create_tables.php');
        foreach (get_create_tables_sql() as $table_name => $table_sql) {
            $this->pdo->exec($table_sql);
            echo $table_name . ' created succussfully <br>';
        }
    }

    /**
     * Alters table from the query
     */
    public function alter_tables()
    {
        global $C;
        require_once($C->dirroot . '/db/alter_tables.php');
        foreach (get_alter_tables_sql() as $table_name => $alter_table_sql) {
            $this->pdo->exec($alter_table_sql);
            echo $table_name . ' altered succussfully <br>';
        }
    }

    /** Updates a single record
     * @param $table_name
     * @param $record -> object
     * @return int
     */
    public function update_record($table_name, $record)
    {
        $table_name = $this->get_table_name_with_prefix($table_name);
        if ($this->table_exists($table_name) && isset($record->id) && $record->id > 0) {
            $set = '';
            $new_record = [];
            $count = 0;
            $table_columns = $this->get_table_schema($table_name, true);
            $new_record = [];
            foreach ($record as $field => $value) {
                if (in_array($field, $table_columns) && $field != 'id') {
                    $count++;
                    $set .= $count == 1 ? ($field . ' = :' . $field) : (', ' . $field . ' = :' . $field);
                }
                $new_record[$field] = $value;
            }
            $sql = 'UPDATE ' . $table_name . ' SET ' . $set . ' WHERE id = :id';
            try {
                $status = $this->pdo->prepare($sql)->execute($new_record);
                return $status;
            } catch (\Exception $e) {
                global $LOG;
                $LOG->write_log($e->getMessage());
                return 0;
            }
        } else {
            return 0;
        }
    }

    /** Updates set clause of single/multiple records that matches where clause
     * @param $table_name
     * @param array $set
     * @param array $where
     * @return int
     */
    public function update_record_param($table_name, $set = [], $where = [])
    {
        $table_name = $this->get_table_name_with_prefix($table_name);
        if ($this->table_exists($table_name) && isset($set) && isset($where)) {
            $set_sql = '';
            $where_sql = '';
            $count = 0;
            $table_columns = $this->get_table_schema($table_name, true);
            $new_record = [];
            foreach ($set as $field => $value) {
                if (in_array($field, $table_columns) && $field != 'id') {
                    $count++;
                    $set_sql .= $count == 1 ? ($field . ' = :' . $field) : (', ' . $field . ' = :' . $field);
                    $new_record[$field] = $value;
                }
            }
            $count = 0;
            foreach ($where as $field => $value) {
                if (in_array($field, $table_columns) && $field != 'id') {
                    $count++;
                    $where_sql .= $count == 1 ? ($field . ' = :' . $field) : ('AND ' . $field . ' = :' . $field);
                    $new_record[$field] = $value;
                }
            }
            $sql = 'UPDATE ' . $table_name . ' SET ' . $set_sql . ' WHERE ' . $where_sql;
            try {
                $status = $this->pdo->prepare($sql)->execute($new_record);
                return $status;
            } catch (\Exception $e) {
                global $LOG;
                $LOG->write_log($e->getMessage());
                return 0;
            }
        } else {
            return 0;
        }
    }

    /** Insert the record in the given table
     * @param $table_name
     * @param $record -> (object)
     * @return int
     */
    public function insert_record($table_name, $record)
    {
        $table_name = $this->get_table_name_with_prefix($table_name);
        if ($this->table_exists($table_name)) {
            $fields = '';
            $values = '';
            $new_record = [];
            $count = 0;
            $table_columns = $this->get_table_schema($table_name, true);

            foreach ($record as $field => $value) {
                if (in_array($field, $table_columns) && $field != 'id') {
                    $count++;
                    $fields .= $count == 1 ? $field : (', ' . $field);
                    $values .= $count == 1 ? (' :' . $field) : (', :' . $field);
                    $new_record[$field] = $value;
                }
            }
            $sql = 'INSERT INTO ' . $table_name . ' (' . $fields . ') VALUES (' . $values . ')';
            try {
                $status = $this->pdo->prepare($sql)->execute($new_record);
                return $status ? $this->pdo->lastInsertId() : 0;
            } catch (\Exception $e) {
                global $LOG;
                $LOG->write_log($e->getMessage());
                return 0;
            }
        } else {
            return 0;
        }
    }

    /** To get multiple records that matches a certain condition
     * @param string $table_name
     * @param array $params
     * @param null $fields -> TODO
     * @param null $sort -> TODO
     * @return bool
     */
    public function get_records($table_name, $params = [], $fields = null, $sort = null)
    {
        $table_name = $this->get_table_name_with_prefix($table_name);
        if (isset($table_name) && $this->table_exists($table_name)) {
            $select = '*';
            $where = '';
            $count = 0;
            //prepare the query
            foreach ($params as $field => $value) {
                $count++;
                $where .= $count == 1 ? ($field . ' = :' . $field) : (' AND ' . $field . ' = :' . $field);
            }
            //if there are no condition for the where clause
            if (empty($where)) $where = 1;

            $sql = 'SELECT ' . $select . ' FROM ' . $table_name . ' WHERE ' . $where;
            //prepare the sql
            $data = $this->pdo->prepare($sql);
            $data->execute($params);
            $data->setFetchMode(\PDO::FETCH_ASSOC);
            //fetch the result
            $result = $data->fetchAll();
            //returns array of object with unique key
            return $result;
        } else {
            return false;
        }
    }

    /** To get a single record from a table
     * @param $table_name
     * @param array $params -> should be unique identifier
     * @param null $fields
     * @param null $sort
     * @return bool
     */
    public function get_record($table_name, $params = [], $fields = null, $sort = null)
    {
        $table_name = $this->get_table_name_with_prefix($table_name);
        if (isset($table_name) && $this->table_exists($table_name)) {
            $select = '*';
            $where = '';
            $count = 0;
            //prepare the query
            foreach ($params as $field => $value) {
                $count++;
                $where .= $count == 1 ? ("$field = :$field") : (" AND $field = :$field");
            }
            $sql = 'SELECT ' . $select . ' FROM ' . $table_name . ' WHERE ' . $where;
            //prepare the sql
            $data = $this->pdo->prepare($sql);
            //execute
            $data->execute($params);
            $data->setFetchMode(\PDO::FETCH_OBJ);
            //fetch the result
            $result = $data->fetchAll();
            if (sizeof($result) > 0) {
                return $result[0];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /** To get records from sql query
     * @param $sql
     * @param array $params
     * @return bool
     */
    public function get_records_sql($sql, $params = [])
    {
        if (isset($sql)) {
            $data = $this->pdo->prepare($sql);
            isset($params) ? $data->execute($params) : $data->execute();
            $data->setFetchMode(\PDO::FETCH_ASSOC);
            //fetch the result
            $result = $data->fetchAll();
            //returns array of object
            return $result;
        } else {
            return false;
        }
    }

    /** Checks if the given table exist in the database
     * @param $table_name
     * @return bool
     */
    public function table_exists($table_name)
    {
        $table_name = $this->get_table_name_with_prefix($table_name);
        if (isset($table_name)) {
            $sql = 'show tables like \'' . $table_name . '\'';
            $data = $this->pdo->query($sql)->fetchAll(\PDO::FETCH_COLUMN);
            foreach ($data as $key => $table) {
                if ($table == $table_name) {
                    return true;
                }
            }
            return false;
        }
    }

    /** Checks where the columns exists in a table
     * @param $table_name
     * @param $column_name
     * @return bool
     */
    public function column_exists($table_name, $column_name)
    {
        if (isset($table_name) && isset($column_name)) {
            $columns = $this->get_table_schema($this->get_table_name_with_prefix($table_name), true);
            if (is_array($column_name, $columns)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Creates new tables, alter tables in the db
     */
    public function update_db()
    {
        $this->create_tables();
        $this->alter_tables();
    }

    /** deletes all the data in a table
     * @param $table_name
     */
    public function empty_table($table_name)
    {
        if (isset($table_name)) {
            $this->delete_records($this->get_table_name_with_prefix($table_name), []);
        }
    }

    protected function delete_table()
    {

    }

    /** deletes records in a table
     * @param $table_name
     * @param array $where - array of fields & values -> to delete based on a condition
     * @return int
     */
    public function delete_records($table_name, $where = [])
    {
        $table_name = $this->get_table_name_with_prefix($table_name);
        if ($this->table_exists($table_name) && isset($where)) {
            $where_sql = '';
            $count = 0;
            $table_columns = $this->get_table_schema($table_name, true);
            $new_record = [];
            if (isset($where['id'])) {
                $where_sql = 'id = :id';
                $new_record['id'] = $where['id'];
            } else {
                foreach ($where as $field => $value) {
                    if (in_array($field, $table_columns) && $field != 'id') {
                        $count++;
                        $where_sql .= $count == 1 ? ($field . ' = :' . $field) : (' AND ' . $field . ' = :' . $field);
                        $new_record[$field] = $value;
                    }
                }
            }

            if (sizeof($where) > 0 && !empty($where_sql)) {
                $sql = 'DELETE FROM ' . $table_name . ' WHERE ' . $where_sql;
            } else {
                $sql = 'DELETE FROM ' . $table_name;
            }
            try {
                $status = $this->pdo->prepare($sql)->execute($new_record);
                return $status;
            } catch (\Exception $e) {
                global $LOG;
                $LOG->write_log($e->getMessage());
                return 0;
            }
        } else {
            return 0;
        }
    }

    /**To get the table schema
     * @param $table_name
     * @param bool $return_columns - returns only column names as array, if true
     * @return array|bool
     */
    public function get_table_schema($table_name, $return_columns = false)
    {
        if (isset($table_name) && $this->table_exists($table_name)) {
            $sql = 'describe ' . $this->get_table_name_with_prefix($table_name);
            $data = $this->pdo->query($sql)->fetchAll();
            if ($return_columns) {
                $columns = [];
                foreach ($data as $schema) {
                    $columns[] = $schema['Field'];
                }
                return $columns;
            }
            return $data;
        } else {
            return false;
        }
    }

    /**
     * To add append the table name with the prefix
     * @param string $table_name name of the table
     * @return string table name
     */
    public function get_table_name_with_prefix($table_name)
    {
        if (!empty($this->table_prefix) && !empty($table_name)) {
            $prefix = $this->table_prefix . '_';
            if (!(substr($table_name, 0, strlen($prefix)) === $prefix)) {
                return $prefix . $table_name;
            }
        }
        return $table_name;
    }
}
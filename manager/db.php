<?php

namespace manager;
/**
 * Class db
 * The Easy to Use PDO inspired from moodle from framework
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

    public function __construct()
    {
        global $C;
        $this->host = $C->db_host;
        $this->dbname = $C->db_name;
        $this->username = $C->db_username;
        $this->password = $C->db_password;
        $this->dbtype = $C->db_type;

    }

    public function connect()
    {
        global $LOG;
        if (isset($this->dbtype) && isset($this->host)) {
            try {
                $this->pdo = new \PDO("$this->dbtype:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
                //set the PDO error mode to exception
                $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $this->is_connected = true;
                $LOG->write_log("Database Connected");
            } catch (\PDOException $e) {
                $LOG->write_log($e->getTraceAsString());
            }
        }
    }


    public function create_tables()
    {
        global $C, $DB;
        require_once($C->dirroot . '/db/create_tables.php');
        foreach (get_create_tables_sql() as $table_name => $table_sql) {
            $this->pdo->exec($table_sql);
            echo $table_name . ' created succussfully <br>';
        }
    }

    public function alter_tables()
    {
        global $C;
        require_once($C->dirroot . '/db/alter_tables.php');
        foreach (get_alter_tables_sql() as $table_name => $alter_table_sql) {
            $this->pdo->exec($alter_table_sql);
            echo $table_name . ' altered succussfully <br>';
        }
    }

    public function update_record($table_name, $record)
    {
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

    public function update_record_param($table_name, $set, $where)
    {
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


    public function update_records($table_name, $params = [])
    {

    }

    public function insert_record($table_name, $record)
    {
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

    public function insert_records($table_name, $params = [])
    {
        //TODO
    }

    public function get_records($table_name, $params = [], $fields = null, $sort = null)
    {
        if (isset($table_name) && $this->table_exists($table_name)) {
            $select = '*';
            $where = '';
            $count = 0;
            //prepare the query
            foreach ($params as $field => $value) {
                $count++;
                $where .= $count == 1 ? ($field . ' = :' . $field) : ('AND ' . $field . ' :' . $field);
            }
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


    public function get_record($table_name, $params = [], $fields = null, $sort = null)
    {
        if (isset($table_name) && $this->table_exists($table_name)) {
            $select = '*';
            $where = '';
            $count = 0;
            //prepare the query
            foreach ($params as $field => $value) {
                $count++;
                $where .= $count == 1 ? ($field . ' = :' . $field) : ('AND ' . $field . ' :' . $field);
            }
            $sql = 'SELECT ' . $select . ' FROM ' . $table_name . ' WHERE ' . $where;
            //prepare the sql
            $data = $this->pdo->prepare($sql);
            // bind the params
            foreach ($params as $field => $value) {
                $data->bindParam(':' . $field, $value);
            }
            //execute
            $data->execute();
            $data->setFetchMode(\PDO::FETCH_OBJ);
            //fetch the result
            $result = $data->fetchAll();
            return $result[0];
        } else {
            return false;
        }
    }

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

    public function table_exists($table_name)
    {
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
        //show tables like 'config'
    }

    public function column_exists($table_name, $column_name)
    {
        if (isset($table_name) && isset($column_name)) {
            $columns = $this->get_table_schema($table_name, true);
            if (is_array($column_name, $columns)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    public function upgrade_db()
    {

    }

    public function empty_table($table_name)
    {
        if (isset($table_name)) {
            $this->delete_records($table_name, []);
        }
    }

    protected function delete_table()
    {

    }

    public function delete_records($table_name, $where = [])
    {
        if ($this->table_exists($table_name) && isset($where)) {
            $where_sql = '';
            $count = 0;
            $table_columns = $this->get_table_schema($table_name, true);
            $new_record = [];
            foreach ($where as $field => $value) {
                if (in_array($field, $table_columns) && $field != 'id') {
                    $count++;
                    $where_sql .= $count == 1 ? ($field . ' = :' . $field) : (' AND ' . $field . ' = :' . $field);
                    $new_record[$field] = $value;
                }
            }
            if (sizeof($where) > 0) {
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

    public function get_table_schema($table_name, $return_columns = false)
    {
        if (isset($table_name) && $this->table_exists($table_name)) {
            $sql = 'describe ' . $table_name;
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
}
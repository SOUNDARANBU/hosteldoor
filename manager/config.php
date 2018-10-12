<?php

namespace manager;

class config
{
    public $configs;

    /**
     * Preloads all the configs to the $C global variable
     */
    public function preload()
    {
        $configs = $this->get_all_configs();
    }

    /**Gets the value for the given config name
     * @param string $name name of the config
     * @return string|bool
     */
    public static function get($name)
    {
        global $DB, $C;
        //if already loaded to the global variable return from there instead of db query
        if (isset($C->configs[$name])) {
            return $C->configs[$name];
        }
        $result = $DB->get_record('config', ['name' => $name]);
        return isset($result->value) ? $result->value : false;
    }

    /** sets the config value if already exists
     *  adds a new config if not exists
     * @param string $name name of the config
     * @param string|int $value value of the config
     * @param bool $create if config not found, should it be created ?
     * @return bool
     */
    public static function set($name, $value, $create = false)
    {
        global $DB;
        $record = $DB->get_record('config', ['name' => $name]);
        if ($record) {
            $record->value = $value;
            if ($DB->update_record('config', $record)) {
                return true;
            }
        } elseif ($create) {
            if (self::add($name, $value)) {
                return true;
            }
        }
        return false;
    }

    /** adds new config
     * @param string $name name of the config
     * @param string|int $value value of the config
     * @return bool
     */
    private static function add($name, $value)
    {
        global $DB;
        $record = new \stdClass();
        $record->name = $name;
        $record->value = $value;
        $result = $DB->insert_record('config', $record);
        return $result ? true : false;
    }

    /**
     * Get all the config from the database
     */
    public static function get_all_configs()
    {
        global $DB;
        $result = $DB->get_records('config', null, 'name, value', true);
        return $result;
    }
}
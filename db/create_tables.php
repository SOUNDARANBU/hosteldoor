<?php
function get_create_tables_sql(){
    $tables_sql = array(
        'config' => 'CREATE TABLE IF NOT EXISTS config(
                                                        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                                        name VARCHAR(255),
                                                        value VARCHAR(255)
                                                      )',
        'user' => 'CREATE TABLE IF NOT EXISTS user_profile(
                                                     id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                                     username VARCHAR(255) DEFAULT NULL,
                                                     password VARCHAR(255) DEFAULT NULL,
                                                     firstname VARCHAR(255) DEFAULT NULL,
                                                     lastname VARCHAR(255) DEFAULT NULL,
                                                     email VARCHAR(255) DEFAULT NULL,
                                                     mobile VARCHAR(255) DEFAULT NULL,
                                                     city VARCHAR(255) DEFAULT NULL,
                                                     timecreated BIGINT DEFAULT NULL
                                                     )'

    );
    return $tables_sql;
}

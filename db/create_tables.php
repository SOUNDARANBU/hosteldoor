<?php
function get_create_tables_sql(){
    $tables_sql = array(
        'config' => 'CREATE TABLE IF NOT EXISTS hdr_config(
                                                        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                                        name VARCHAR(255),
                                                        value VARCHAR(255)
                                                      )',
        'user' => 'CREATE TABLE IF NOT EXISTS hdr_user(
                                                     id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                                     username VARCHAR(255) DEFAULT NULL,
                                                     password VARCHAR(255) DEFAULT NULL,
                                                     firstname VARCHAR(255) DEFAULT NULL,
                                                     lastname VARCHAR(255) DEFAULT NULL,
                                                     email VARCHAR(255) DEFAULT NULL,
                                                     mobile VARCHAR(255) DEFAULT NULL,
                                                     city VARCHAR(255) DEFAULT NULL,
                                                     active INT DEFAULT(1) NULL,
                                                     deleted INT DEFAULT(0) NULL,
                                                     timecreated BIGINT DEFAULT NULL
                                                     )',
        'role' => 'CREATE TABLE IF NOT EXISTS hdr_role(
                                                      id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                                      name VARCHAR(255) DEFAULT NULL,
                                                      description VARCHAR(255) DEFAULT NULL,
                                                      level VARCHAR(255) DEFAULT NULL
                                                      )',
        'capability' => 'CREATE TABLE IF NOT EXISTS hdr_capability(
                                                      id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                                      userid BIGINT DEFAULT NULL,
                                                      name VARCHAR(255) DEFAULT NULL
                                                      )'
    );
    return $tables_sql;
}

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
                                                     username VARCHAR(255) NOT NULL,
                                                     password VARCHAR(255) NOT NULL,
                                                     firstname VARCHAR(255) NOT NULL,
                                                     lastname VARCHAR(255) DEFAULT NULL,
                                                     email VARCHAR(255) NOT NULL,
                                                     mobile VARCHAR(255) DEFAULT NULL,
                                                     city VARCHAR(255) DEFAULT NULL,
                                                     active INT DEFAULT(1) NULL,
                                                     deleted INT DEFAULT(0) NULL,
                                                     confirmed INT DEFAULT(0) NULL,
                                                     timecreated BIGINT DEFAULT NULL,
                                                     timemodified BIGINT DEFAULT NULL,
                                                     CONSTRAINT Uni_user UNIQUE(id, username, email)
                                                     )',
        'user_settings' => 'CREATE TABLE IF NOT EXISTS hdr_user_settings(
                                                     id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                                     userid BIGINT NOT NULL,
                                                     name VARCHAR(255) NOT NULL,
                                                     value VARCHAR(255) NOT NULL,
                                                     timecreated BIGINT DEFAULT NULL,
                                                     timemodified BIGINT DEFAULT NULL
                                                     )',
        'role' => 'CREATE TABLE IF NOT EXISTS hdr_role(
                                                      id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                                      name VARCHAR(255) NOT NULL,
                                                      description VARCHAR(255) NULL,
                                                      level VARCHAR(255) DEFAULT NULL,
                                                      timecreated BIGINT DEFAULT NULL,
                                                      timemodified BIGINT DEFAULT NULL
                                                      )',

        'role_assignment' => 'CREATE TABLE IF NOT EXISTS hdr_role_assignment(
                                                      id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                                      roleid BIGINT NOT NULL,
                                                      userid BIGINT NOT NULL,
                                                      timecreated BIGINT DEFAULT NULL,
                                                      timemodified BIGINT DEFAULT NULL
                                                      )',

        'permissions' => 'CREATE TABLE IF NOT EXISTS hdr_permissions(
                                                      id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                                      name VARCHAR(255) NOT NULL,
                                                      description VARCHAR(255) NOT NULL,
                                                      timecreated BIGINT DEFAULT NULL,
                                                      timemodified BIGINT DEFAULT NULL
                                                      )',

        'permission_assignment' => 'CREATE TABLE IF NOT EXISTS hdr_permission_assignment(
                                                      id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                                      permissionid BIGINT NOT NULL,
                                                      roleid BIGINT NOT NULL,
                                                      timecreated BIGINT DEFAULT NULL,
                                                      timemodified BIGINT DEFAULT NULL
                                                      )',
    );
    return $tables_sql;
}
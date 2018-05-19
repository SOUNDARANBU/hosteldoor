<?php
function get_alter_tables_sql(){
    $alter_tables_sql = array(
        'user' => 'ALTER TABLE hdr_user
                            ADD COLUMN active INT DEFAULT(1) NULL, 
                            ADD COLUMN deleted INT DEFAULT(0) NULL'
    );
    return $alter_tables_sql;
}
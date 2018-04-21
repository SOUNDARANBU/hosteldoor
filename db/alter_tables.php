<?php
function get_alter_tables_sql(){
    $alter_tables_sql = array(
        'user_profile' => 'ALTER TABLE user_profile
                            ADD COLUMN timecreated BIGINT DEFAULT NULL'
    );
    return $alter_tables_sql;
}
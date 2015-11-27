<?php

namespace app\payverify\dbs;

use hellaEngine\data\BaseDataDBCell;

abstract class DbsBase extends BaseDataDBCell
{
    function __construct($tableName = constants_db::EMPTY_TABLE_NAME, $db_field_keys = array(), $db_field_primary_key = array())
    {
        parent::__construct($tableName, $db_field_keys, $db_field_primary_key, false, FALSE);
    }
}
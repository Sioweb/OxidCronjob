<?php

namespace Sioweb\Oxid\Cronjob\Core;

use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\DbMetaDataHandler;
use OxidEsales\Eshop\Core\Registry;

class Events
{

    public static function onActivate()
    {
        $Database = DatabaseProvider::getDb();

        $Database->execute("
            CREATE TABLE IF NOT EXISTS `sio_cronjob` (
                `OXID` char(32) character set utf8 collate utf8_general_ci NOT NULL COMMENT 'Cronjob id',
                PRIMARY KEY  (`OXID`)
            ) ENGINE=InnoDB;
        ");

        return;
        $tableFields = [
            // ['sio_cronjob', 'OXALIAS', "varchar(255) NOT NULL default ''"],
        ];

        $dbMetaDataHandler = oxNew(DbMetaDataHandler::class);
        foreach ($tableFields as $fieldData) {
            if (!$dbMetaDataHandler->fieldExists($fieldData[1], $fieldData[0])) {
                $Database->execute(
                    "ALTER TABLE `{$fieldData[0]}` ADD `{$fieldData[1]}` {$fieldData[2]};"
                );
            }
        }
    }

    public static function onDeactivate()
    {}
}

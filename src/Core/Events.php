<?php

namespace Sioweb\Oxid\Cronjob\Core;

use OxidEsales\Eshop\Core\DatabaseProvider;

class Events
{
    public static function onActivate()
    {
        $Database = DatabaseProvider::getDb();

        $Database->execute("
            CREATE TABLE IF NOT EXISTS `sio_cronjob` (
                `OXID` char(32) character set utf8 collate utf8_general_ci NOT NULL COMMENT 'Cronjob id',
                `OXSHOPID` int(11) NOT NULL default '1' COMMENT 'Shop id (oxshops)',
                `OXTITLE` varchar(255) NOT NULL default '' COMMENT 'Title (multilanguage)',
                `OXTITLE_1` varchar(255) NULL,
                `OXTITLE_2` varchar(255) NULL,
                `OXTITLE_3` varchar(255) NULL,
                `OXSORT` int( 5 ) NOT NULL DEFAULT '0' COMMENT 'Sorting',
                `OXACTIVE` tinyint(1) NOT NULL default '1' COMMENT 'Active',
                `OXACTIVEFROM` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Active from specified date',
                `OXACTIVETO` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Active to specified date',
                `OXTIMESTAMP` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP COMMENT 'Timestamp',
                `MINUTE` varchar(30) NULL DEFAULT '0',
                `HOUR` varchar(30) NULL DEFAULT '0',
                `DAY` varchar(30) NULL DEFAULT '0',
                `MONTH` varchar(30) NULL DEFAULT '0',
                `WEEKDAY` varchar(30) NULL DEFAULT '0',
                `LAST_EXECUTED` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'When was this cron executeted',
                PRIMARY KEY  (`OXID`)
            ) ENGINE=InnoDB;
        ");
    }

    public static function onDeactivate()
    {}
}

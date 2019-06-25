<?php

/**
 * Metadata version
 */
$sMetadataVersion = '2.0';

/**
 * Module information
 */
$aModule = [
    'id' => 'SiowebOxidCronjob',
    'title' => '<i></i><b style="color: #005ba9">Sioweb</b> | Cronjobs',
    'description' => 'Zugriffspunkte für Cronjobs definieren. Rufen Sie yourdomain.tld?cl=swexeccrons auf, um die Cronjobs auszuführen. (https://de.wikipedia.org/wiki/Cron)',
    'version' => '1.0',
    'url' => 'https://www.sioweb.de',
    'email' => 'support@sioweb.com',
    'author' => 'Sascha Weidner',
    'extend' => [
        \OxidEsales\Eshop\Core\Module\Module::class =>
            \Sioweb\Oxid\Cronjob\Core\Module\Module::class,
        \OxidEsales\Eshop\Core\Module\ModuleInstaller::class =>
            \Sioweb\Oxid\Cronjob\Core\Module\ModuleInstaller::class,
    ],
    'events' => [
        'onActivate' => '\Sioweb\Oxid\Cronjob\Core\Events::onActivate',
        'onDeactivate' => '\Sioweb\Oxid\Cronjob\Core\Events::onDeactivate',
    ],
    'controllers' => [
        'swexeccrons' => Sioweb\Oxid\Cronjob\Controller\Cronjob::class,
        'swshedulecrons' => Sioweb\Oxid\Cronjob\Controller\Cronjob::class,
        'siocronjob' => Sioweb\Oxid\Cronjob\Controller\Admin\Cronjobs::class,
        'siocronjoblist' => Sioweb\Oxid\Cronjob\Controller\Admin\CronjobList::class,
        'siocronjobmain' => Sioweb\Oxid\Cronjob\Controller\Admin\CronjobMain::class,
    ],
    'templates' => [
        // Admin-Bereich
        'sio_cronjob_admin.tpl' => 'sioweb/Cronjob/views/tpl/admin/sio_cronjob_admin.tpl',
        'sio_cronjob_admin_list.tpl' => 'sioweb/Cronjob/views/tpl/admin/sio_cronjob_admin_list.tpl',
        'sio_cronjob_admin_main.tpl' => 'sioweb/Cronjob/views/tpl/admin/sio_cronjob_admin_main.tpl',
    ]
];

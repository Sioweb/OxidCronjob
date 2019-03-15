<?php

/**
 * Metadata version
 */
$sMetadataVersion = '2.0';

/**
 * Module information
 */
$aModule = [
    'id' => 'SiowebCronjob',
    'title' => '<b style="color: #005ba9">Sioweb</b> | Cronjobs',
    'description' => 'Zugriffspunkte für Cronjobs definieren. Rufen Sie yourdomain.tld?cl=swexeccrons auf, um die Cronjobs auszuführen.',
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
        'swexeccrons' => Sioweb\Oxid\Cronjob\Controller\Cronjob::class
    ]
];

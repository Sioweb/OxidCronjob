<?php

namespace Sioweb\Oxid\Cronjob\Model;

use OxidEsales\Eshop\Core\Model\MultiLanguageModel;

class CronjobList extends MultiLanguageModel
{
    public $aList = [];

    public function __construct()
    {
        parent::__construct();
        $this->init("sio_cronjob");
    }
}

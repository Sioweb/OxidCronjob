<?php

namespace Sioweb\Oxid\Cronjob\Model;

use OxidEsales\Eshop\Core\Model\MultiLanguageModel;

class Cronjob extends MultiLanguageModel
{

    public function __construct()
    {
        parent::__construct();
        $this->init("sio_cronjob");
    }
}

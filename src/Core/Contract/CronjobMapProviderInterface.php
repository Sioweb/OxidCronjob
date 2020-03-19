<?php

namespace Sioweb\Oxid\Cronjob\Core\Contract;

/**
 * The implementation of this class determines the cronjobs which should be allowed to be called directly via
 * HTTP GET/POST Parameters, inside form actions or with oxid_include_widget.
 * Those cronjobs are specified e.g. inside a form action with a cronjob key which is mapped to its class.
 *
 */
interface CronjobMapProviderInterface
{
    /**
     * Get all cronjob keys and their assigned classes
     *
     * @return array
     */
    public function getCronjobMap();
}

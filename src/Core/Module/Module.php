<?php

namespace Sioweb\Oxid\Cronjob\Core\Module;

class Module extends Module_parent
{
    /**
     * Returns associative array of module cronjob classes.
     *
     * @return array
     */
    public function getCronjob()
    {
        if (isset($this->_aModule['cronjob']) && ! is_array($this->_aModule['cronjob'])) {
            throw new \InvalidArgumentException('Value for metadata key "cronjob" must be an array');
        }

        return isset($this->_aModule['cronjob']) ? array_change_key_case($this->_aModule['cronjob']) : [];
    }
}
<?php

/*
 * This file is part of Sioweb Cronjob for oxid.
 *
 * (c) Sioweb
 */

namespace Sioweb\Oxid\Cronjob\Form\Admin;

class Cronjob implements \Sioweb\Lib\Formgenerator\Core\FormInterface, \Sioweb\Lib\Formgenerator\Core\SubpaletteInterface
{
    public function loadData()
    {
        return [
            'form' => [
                'formname' => 'myform',
                'table' => 'sio_cronjob',
                'fieldname' => 'editval[[{ $TABLE }]__[{ $FIELDNAME }]]',
                'palettes' => $this->loadPalettes(),
                'fields' => $this->loadFieldConfig(),
            ],
        ];
    }

    public function loadSubpalettes()
    {
        return [
            'oxvalidation_email' => [
                'oxconfirmfield'
            ],
        ];
    }

    public function loadPalettes()
    {
        return [
            'default' => [
                'default' => [
                    'class' => 'w50',
                    'fields' => ['oxtitle', 'oxsort', 'oxactive', 'oxactivefrom', 'oxactiveto'],
                ],
                'cronjob' => [
                    'class' => 'w50',
                    'fields' => ['minute', 'hour', 'day', 'month', 'weekday'],
                ],
                'submit' => [
                    'fields' => ['submit'],
                ]
            ]
        ];
    }

    public function loadFieldConfig()
    {
        return [
            'oxtitle' => [
                'type' => 'text',
                'required' => true,
                'label' => 'SIO_CRONJOB_OXTITLE',
            ],
            'oxsort' => [
                'type' => 'text',
                'required' => true,
            ],
            'minute' => [
                'type' => 'text',
                'required' => true,
                'label' => 'SIO_CRONJOB_MINUTE',
                'help' => 'SIO_CRONJOB_MINUTE_HELP',
            ],
            'hour' => [
                'type' => 'text',
                'required' => true,
                'label' => 'SIO_CRONJOB_HOUR',
                'help' => 'SIO_CRONJOB_HOUR_HELP',
            ],
            'day' => [
                'type' => 'text',
                'required' => true,
                'label' => 'SIO_CRONJOB_DAY',
                'help' => 'SIO_CRONJOB_DAY_HELP',
            ],
            'month' => [
                'type' => 'text',
                'required' => true,
                'label' => 'SIO_CRONJOB_MONTH',
                'help' => 'SIO_CRONJOB_MONTH_HELP',
            ],
            'weekday' => [
                'type' => 'text',
                'required' => true,
                'label' => 'SIO_CRONJOB_WEEKDAY',
                'help' => 'SIO_CRONJOB_WEEKDAY_HELP',
            ],
            'oxactive' => [
                'type' => 'checkbox',
                'required' => true,
            ],
            'oxactivefrom' => [
                'type' => 'text',
                'class' => 'w50',
                'validation' => 'datetime',
                'autocomplete' => 'off',
                'attributes' => [
                    'data-datepicker'
                ],
            ],
            'oxactiveto' => [
                'type' => 'text',
                'class' => 'w50',
                'validation' => 'datetime',
                'autocomplete' => 'off',
                'attributes' => [
                    'data-datepicker'
                ],
            ],
            'submit' => [
                'name' => 'save',
                'type' => 'submit',
                'value' => 'Senden',
                'attributes' => [
                    'onclick="Javascript:document.myedit.fnc.value=\'save\'"',
                ],
                'editable' => true,
            ],
        ];
    }
}

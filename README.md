# OxidCronjob

Dieses Modul stellt einen zentralen Entrypoint für Cronjobs zur Verfügung. Module für Oxid 6 können durch dieses Modul in der Datei `metadata.php` `cronjobs` konfigurieren. Diese müssen wie Controller ab `$sMetadataVersion = "2.0"` notiert werden.

```
$aModule = [
    'cronjob' => [
        'ein_eindeutiger_name' => Name\Space\Zu\Deinem\Controller\ControllerKlassenName::class
    ]
];
```

Eure Klasse muss mindestens die öffentliche Funktion init() {} enthalten und sollte von FontendController erben.

```
<?php

namespaceName\Space\Zu\Deinem\Controller;

use OxidEsales\Eshop\Application\Controller\FrontendController;

class ControllerKlassenName extends FrontendController {
  public function init() {
  }
}
```

## Installation

Die Installation erfolg via Composer. Es kann von Hand, oder durch die Abhängigkeitsangabe eines Modules in der composer.json installiert werden:

Von Hand: `composer req sioweb/oxid-cronjob`.

Als Abhängigkeit:

```
{
  "name": "dein/modul",
  "require": {
    "sioweb/oxid-cronjob": "*"
  }
}
```

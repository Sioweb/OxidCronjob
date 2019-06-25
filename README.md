# OxidCronjob

Dieses Modul stellt einen zentralen Entrypoint für Cronjobs zur Verfügung. Module für Oxid 6 können durch dieses Modul in der Datei `metadata.php` `cronjobs` konfigurieren. Diese müssen wie Controller ab `$sMetadataVersion = "2.0"` notiert werden.

**Hinweis:** Cronjobs, müssen auf einem Server, oder Rechner, eingerichtet werden. Der Cronjob muss dann die URL https://yourdomain.tld?cl=swexeccrons aufrufen.

## Für wen ist dieses Modul?

Dieses Modul ist ein Helfer für Modulentwickler, die einen Controller anbieten wollen, der regelmäßig aufgerufen werden soll. Sollte ein Kunde/Anwender mehrere Module einsetzen, die regelmäßig aufgerufene Controller enthalten, muss nur noch ein Cronjob auf dem Server eingerichtet werden.

**Warnung:** Bei zu großen Datenmengen, kann es sein, dass der Cronjob nicht vollständig ausgeführt wird.

## Zwei Arten von Cronjobs

### Schedule

Scheduled Cronjobs können im Backend unter Service > Cronjob eingerichtet werden und sind sehr viel dynamischer als `Executables`. Ein Cronjob des Servers - oder eines externen Servers, könnte nun jede Minute den Shop aufrufen: https://dein-shop.tld?cl=swshedulecrons

Das Modul lädt dann alle eingerichteten Cronjobs aus der Tabelle `sio_cronjob` die älter sind als zum Zeitpunk des Aufrufen und führt die Cronjobs aus. Als Beispiel wollen wir einen Cronjob der alle fünf Minuten ausgeführt wird, dazu wird im Backend folgender Cronjob hinterlegt:

    Cronjob ID: test
    Minute: /5
    Stunde: *
    Tag: *
    Monat: *
    Wochentag: *
    
Ein Modul kann nun einen `test::` Cronjob registrieren:

```php
$aModule = [
    'cronjob' => [
        'test::ein_eindeutiger_name' => Name\Space\Zu\Deinem\Controller\ControllerKlassenName::class
    ]
];
```

### Executable

In der ersten Version dieses Modules, waren Cronjobs ledigliche eine Gruppe von registrierbaren Klassen, welche nacheinander aufgerufen und ausgeführt wurden. Diese Cronjobs können via URL `?cl=swexeccrons` im Frontend ausgeführt werden. Alle Cronjobs die nach dem folgendem Muster registriert wurden, werden ausgeführt:

```php
$aModule = [
    'cronjob' => [
        'ein_eindeutiger_name' => Name\Space\Zu\Deinem\Controller\ControllerKlassenName::class
    ]
];
```

**Hinweis:** Eure Klasse muss mindestens die öffentliche Funktion init() {} enthalten und muss von FontendController erben oder muss alle für eine Frontendausgabe nötigen Methoden besitzen.

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

## Dein Modul braucht einen Cronjob?

Dann füge `sioweb/oxid-cronjob` als Composer-Abhängigkeit zu deinem Modul hinzu. Über das Oxid-Event `onActivate` kannst du deinen Cronjob in die Tabelle `sio_cronjob` einfügen. **Achtung:** Es kann sein, dass der Endbenutzer das Cronjob-Modul nicht aktiviert hat, daher rate ich dazu die Tabelle `sio_cronjob` in deinem Event ebenfalls zu installieren. Kopiere dazu einfach den Inhalt aus der [onActivate-Methode](https://github.com/Sioweb/OxidCronjob/blob/master/Core/Events.php#L9) den Cronjob-Modules.

Weise den Benutzer darauf hin, dass das Cronjob-Modul unbedingt aktiviert sein muss, damit dein Cronjob ausgeführt wird.

Benenne den Cronjob am besten wie folgt: `Modulname::Vendorname_Modulname_Cronjobname`. Die Cronjob ID muss dann ebenfalls `Modulname` heißen, die bezeichnung nach den beiden Doppelpunkten ist frei wählbar, muss aber unique sein.

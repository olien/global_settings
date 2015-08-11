Globale Einstellungen AddOn für REDAXO 4
========================================

Mit diesem Addon kann man globale MetaInfos setzen die für die gesamte Website gültig sind. Admins können Felder anlegen und bearbeiten, Nicht-Admins können nur bearbeiten

Features
--------

* MetaInfos für die gesamte Website
* API für den Zugriff auf die Felder
* Nicht-Admins dürfen Felder nur bearbeiten
* Mehrsprachigkeit

API
---

```php
// ausgabe eines Feldes der aktuellen Sprache
echo rex_global_settings::getValue('glob_feldname');

// ausgabe eines Feldes der Sprache mit der ID = 2
echo rex_global_settings::getValue('glob_feldname', 2);

// ausgabe eines Feldes der Haupt-Sprache
echo rex_global_settings::getDefaultValue('glob_feldname');
```

Hinweise
--------

* Getestet mit REDAXO 4.6
* MetaInfo AddOn muss installiert sein
* AddOn-Ordner lautet: `global_settings`

Changelog
---------

siehe [CHANGELOG.md](CHANGELOG.md)

Lizenz
------

siehe [LICENSE.md](LICENSE.md)

Collaborators
-------------

* [Sysix](https://github.com/Sysix)
* [polarpixel](https://github.com/polarpixel)

Credits
-------

* [Parsedown](http://parsedown.org/) Class by Emanuil Rusev

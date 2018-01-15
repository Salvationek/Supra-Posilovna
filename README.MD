# Supra Posilovna

Semestrální práce na webové aplikace. Repozitáø obsahuje webovou aplikaci ve frameworku Yii2. Aplikace je jednoduchý rezervaèní systém smyšlené posilovny.

## Požadavky

* webový server Apache
* PHP 7
* MySQL
* balíèkovací systém Composer
* GIT client
* generátor dokumentace phpDocumentator

## Instalace

Pod webrootem spustíme stažení z GIT repozitáøe

```
git clone https://github.com/Salvationek/Supra-Posilovna.git
```

Dostaneme se do novì vytvoøené složky "Supra-Posilovna"

```
cd Supra-Posilovna
```

Spustíme stažení všech potøebných balíèkù pøes Composer

```
composer update
```

Stáhneme phpDocumentator

```
composer global require "phpdocumentor/phpdocumentor:2.*"
```

Pøedpokládáme, že máte nastaveny systémové cesty ke globálním balíkùm Composeru.
Spustíme phpdoc.

```
phpdoc -d "assets,models,controllers,views" -t "web/doc"
```

Nastavíme apache, aby pøistupoval rovnou do podsložky projektu "web" (vhosts)

```
<VirtualHost *>
   ServerAdmin webmaster@localhost
   DocumentRoot /var/www/supraposilovna.duckdns.org/web
   ServerName supraposilovna.duckdns.org
   ErrorLog /var/log/apache2/04_supraposilovna.duckdns.org.error.log
   CustomLog /var/log/apache2/04_supraposilovna.duckdns.org.custom.log combined
   <Directory /var/www/supraposilovna.duckdns.org/public>
        Require all granted
        AllowOverride All
   </Directory>
</VirtualHost>
```

Spustíme databázový skript umístìný v podsložce "database" v libovolném databázovém klientovi. (adminer, phpMyAdmin, heidiSQL)

Vytvoøíme v databázi uživatele supraadmin.

```
CREATE USER 'supraadmin'@'localhost' IDENTIFIED BY 'supraadmin';
GRANT SELECT,UPDATE,INSERT,DELETE,CREATE,DROP PRIVILEGES ON supraadmin.* TO 'supraadmin'@'localhost';
FLUSH PRIVILEGES;
```

Nyní by mìlo být vše pøipraveno.

## Použité balíky a nástroje

* Yii2 framework
* Bootstrap
* Gii
* kartik-v/yii2-widget-datepicker
* adminer
* phpStorm
* XAMPP pro vývoj
* SourceTree

## Autor

[Martin Mašata](masatma1@fel.cvut.cz)

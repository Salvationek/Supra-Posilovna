# Supra Posilovna

Semestr�ln� pr�ce na webov� aplikace. Repozit�� obsahuje webovou aplikaci ve frameworku Yii2. Aplikace je jednoduch� rezerva�n� syst�m smy�len� posilovny.

## Po�adavky

* webov� server Apache
* PHP 7
* MySQL
* bal��kovac� syst�m Composer
* GIT client
* gener�tor dokumentace phpDocumentator

## Instalace

Pod webrootem spust�me sta�en� z GIT repozit��e

```
git clone https://github.com/Salvationek/Supra-Posilovna.git
```

Dostaneme se do nov� vytvo�en� slo�ky "Supra-Posilovna"

```
cd Supra-Posilovna
```

Spust�me sta�en� v�ech pot�ebn�ch bal��k� p�es Composer

```
composer update
```

St�hneme phpDocumentator

```
composer global require "phpdocumentor/phpdocumentor:2.*"
```

P�edpokl�d�me, �e m�te nastaveny syst�mov� cesty ke glob�ln�m bal�k�m Composeru.
Spust�me phpdoc.

```
phpdoc -d "assets,models,controllers,views" -t "web/doc"
```

Nastav�me apache, aby p�istupoval rovnou do podslo�ky projektu "web" (vhosts)

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

Spust�me datab�zov� skript um�st�n� v podslo�ce "database" v libovoln�m datab�zov�m klientovi. (adminer, phpMyAdmin, heidiSQL)

Vytvo��me v datab�zi u�ivatele supraadmin.

```
CREATE USER 'supraadmin'@'localhost' IDENTIFIED BY 'supraadmin';
GRANT SELECT,UPDATE,INSERT,DELETE,CREATE,DROP PRIVILEGES ON supraadmin.* TO 'supraadmin'@'localhost';
FLUSH PRIVILEGES;
```

Nyn� by m�lo b�t v�e p�ipraveno.

## Pou�it� bal�ky a n�stroje

* Yii2 framework
* Bootstrap
* Gii
* kartik-v/yii2-widget-datepicker
* adminer
* phpStorm
* XAMPP pro v�voj
* SourceTree

## Autor

[Martin Ma�ata](masatma1@fel.cvut.cz)

# Aktivliste

Die Aktivliste zeigt alle aktiven Variablen, welche zuvor der Liste auf der Konfigurationsseite hinzugefügt wurden, im WebFront an und bietet die Möglichkeit 
diese simultan auszuschalten.

### Inhaltverzeichnis

1. [Funktionsumfang](#1-funktionsumfang)
2. [Voraussetzungen](#2-voraussetzungen)
3. [Software-Installation](#3-software-installation)
4. [Einrichten der Instanzen in IP-Symcon](#4-einrichten-der-instanzen-in-ip-symcon)
5. [Statusvariablen und Profile](#5-statusvariablen-und-profile)
6. [WebFront](#6-webfront)
7. [PHP-Befehlsreferenz](#7-php-befehlsreferenz)

### 1. Funktionsumfang

* Ermöglicht es den Status ausgewählter Variablen anzuzeigen, sowie diese zu deaktivieren.

### 2. Voraussetzungen

- IP-Symcon ab Version 5.0

### 3. Software-Installation

* Über den Modul Store das Modul Aktivliste installieren.
* Alternativ über das Modul Control folgende URL hinzufügen:
´https://github.com/symcon/Aktivliste`

### 4. Einrichten der Instanzen in IP-Symcon

- Unter "Instanz hinzufügen" ist das 'Aktivliste'-Modul aufgeführt.  

__Konfigurationsseite__:

Name      | Beschreibung
--------- | ---------------------------------
Variablen | Eine Liste mit Variablen, deren Status überprüft wird.

### 5. WebFront

Auf dem Webfront werden alle aktiven Variablen angezeigt. 
Mit einem Klick auf "Ausschalten" werden alle angezeigten Variablen ausgeschaltet.


### 6. PHP-Befehlsreferenz

`AL_SwitchOff(integer $InstanzID);`
Schaltet alle aktiven Variablen aus.
Beispiel:
`AL_SwitchOff(integer $InstanzID);`
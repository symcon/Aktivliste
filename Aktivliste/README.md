# Aktivliste
Die Aktivliste zeigt alle aktiven Variablen im WebFront an und bietet die Möglichkeit 
diese simultan auszuschalten.  
Hierzu müssen sie zuvor der Liste auf der Konfigurationsseite hinzugefügt wurden

### Inhaltsverzeichnis

1. [Funktionsumfang](#1-funktionsumfang)
2. [Voraussetzungen](#2-voraussetzungen)
3. [Software-Installation](#3-software-installation)
4. [Einrichten der Instanzen in IP-Symcon](#4-einrichten-der-instanzen-in-ip-symcon)
5. [Statusvariablen und Profile](#5-statusvariablen-und-profile)
6. [WebFront](#6-webfront)
7. [PHP-Befehlsreferenz](#7-php-befehlsreferenz)

### 1. Funktionsumfang

* Zeigt alle aktiven Variablen im WebFront an und erlaubt das Ausschalten dieser.

### 2. Voraussetzungen

- IP-Symcon ab Version 5.0

### 3. Software-Installation

* Über den Module Store das Modul Aktivliste installieren.
* Alternativ über das Module Control folgende URL hinzufügen:
`https://github.com/symcon/Aktivliste`

### 4. Einrichten der Instanzen in IP-Symcon

- Unter "Instanz hinzufügen" kann das 'Aktivliste'-Modul mithilfe des Schnellfilters gefunden werden.
    * Weitere Information in der Dokumentation der Instanzen: [Dokumentation der Instanzen](https://www.symcon.de/service/dokumentation/konzepte/instanzen/#Instanz_hinzufügen)

__Konfigurationsseite__:

Name      | Beschreibung
--------- | ---------------------------------
Variablen | Eine Liste mit Variablen, deren Status überprüft wird.    

Variablen gelten als aktiv, wenn ... 

... der Wert einer Integer oder Float Variable größer als der Minimalwert ist. Sollte die Variable ein .Reversed Profil haben gilt sie als aktiv,  
    wenn der Wert kleiner als der Maximalwert ist.  
... der Wert einer Boolean Variable true ist. Sollte die Variable ein .Reversed Profil haben ist false der aktive Zustand.  
... der Wert einer String Variable nicht leer ist.

Dementsprechend gelten Variablen als inaktiv, wenn ...  

... der Wert einer Integer oder Float Variable der Minimalwert ist. Sollte die Variable ein .Reversed Profil haben gilt sie als inaktiv,  
    wenn der Wert der Maximalwert ist.  
... der Wert einer Boolean false ist. Sollte die Variable ein .Reversed Profil haben ist true der inaktive Zustand.   
... der Wert einer String Variable leer ist.  

### 5. Statusvariablen und Profile

Die Statusvariablen/Kategorien werden automatisch angelegt. Das Löschen einzelner kann zu Fehlfunktionen führen.

##### Statusvariablen

Name         | Typ    | Beschreibung
------------ | ------ | -------------------------------
Ausschalten  | Skript | Schaltet alle noch aktiven Variablen inaktiv. 

##### Profile:

Es werden keine zusätzlichen Profile hinzugefügt.

### 6. WebFront

Auf dem Webfront werden alle aktiven Variablen angezeigt. 
Mit einem Klick auf "Ausschalten" werden alle angezeigten Variablen auf inaktiv geschaltet.


### 7. PHP-Befehlsreferenz

`AL_SwitchOff(integer $InstanzID);`
Schaltet alle in der Liste vorhandenen aktiven Variablen inaktiv.  
Beispiel:
`AL_SwitchOff(12345);`

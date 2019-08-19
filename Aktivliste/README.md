# Aktivliste


Die Aktivliste zeigt alle aktiven Variablen, im WebFront an und bietet die Möglichkeit 
diese simultan auszuschalten.  
Hierzu müssen sie zuvor der Liste auf der Konfigurationsseite hinzugefügt wurden


### 1. Funktionsumfang

* Zeigt alle aktiven Variablen im WebFront an und erlaubt das Ausschalten dieser.

### 2. Voraussetzungen

- IP-Symcon ab Version 5.0

### 3. Software-Installation

* Über den Modul Store das Modul Aktivliste installieren.
* Alternativ über das Modul Control folgende URL hinzufügen:
`https://github.com/symcon/Aktivliste`

### 4. Einrichten der Instanzen in IP-Symcon

- Unter "Instanz hinzufügen" ist das 'Aktivliste'-Modul aufgeführt.  

__Konfigurationsseite__:

Name      | Beschreibung
--------- | ---------------------------------
Variablen | Eine Liste mit Variablen, deren Status überprüft wird.    

Variablen gelten als aktiv, wenn ... 

... der Wert eines Integer oder Float größer als der Minimalwert ist. Im Falle eines Variablen Profiles mit .Reversed kleiner als der Maximalwert.  
... der Wert eines Boolean true ist. Im Falle eines Variablen Profiles mit .Reversed false.  
... der Wert eines Strings nicht leer ist.

Dementsprechend gelten Variablen als inaktiv, wenn ...  

... der Wert eines Integer oder Float der Minimalwert ist. Im Falle eines Variablen Profiles mit .Reversed der Maximalwert.  
... der Wert ernes Boolean false ist. Im Falle eines Variablenpofiles mit .Reversed true.  
... der Wert eines String " " entspricht.  

### 5. WebFront

Auf dem Webfront werden alle aktiven Variablen angezeigt. 
Mit einem Klick auf "Ausschalten" werden alle angezeigten Variablen ausgeschaltet.


### 6. PHP-Befehlsreferenz

`AL_SwitchOff(integer $InstanzID);`
Schaltet alle in der Liste vorhandenen aktiven Variablen aus.  
Beispiel:
`AL_SwitchOff(12345);`
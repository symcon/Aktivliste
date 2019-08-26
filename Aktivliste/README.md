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

... der Wert einer Integer oder Float Variable größer als der Minimalwert ist. Sollte die Variable ein .Reversed Profil haben gilt sie als aktiv,  
    wenn der Wert kleiner als der Maximalwert ist.  
... der Wert einer Boolean Variable true ist. Sollte die Variable ein .Reversed Profil haben ist false der aktive Zustand.  
... der Wert einer String Variable nicht leer ist.

Dementsprechend gelten Variablen als inaktiv, wenn ...  

... der Wert einer Integer oder Float Variable der Minimalwert ist. Sollte die Variable ein .Reversed Profil haben gilt sie als inaktiv,  
    wenn der Wert der Maximalwert ist.  
... der Wert einer Boolean false ist. Sollte die Variable ein .Reversed Profil haben ist true der inaktive Zustand.   
... der Wert einer String Variable " " entspricht.  

### 5. WebFront

Auf dem Webfront werden alle aktiven Variablen angezeigt. 
Mit einem Klick auf "Ausschalten" werden alle angezeigten Variablen auf inaktiv geschaltet.


### 6. PHP-Befehlsreferenz

`AL_SwitchOff(integer $InstanzID);`
Schaltet alle in der Liste vorhandenen aktiven Variablen inaktiv.  
Beispiel:
`AL_SwitchOff(12345);`
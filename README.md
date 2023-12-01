- Refactoring eines Scripts zum Auslesen einer Telefonanlage und Erstellung einer Webseite zum Darstellen der Anrufe
- zusätzlich kann man die Telefondaten (Name & Telefonnummer) auf der Seite ändern und diese werden in die Datei der Telefonanlage zurückgeschrieben
\
\
Aufgrund von Datenschutz ist der res/ Ordner und alle commits entfernt worden. Daher sind es auch nur 3(4) Commits.
\
\
Interessant: 
- [Commit 2](https://github.com/michmue/telephone-system/tree/92aa8e95aac28f69586fcb4ebe7a3df206f8cc77) hatte ich bekommen, [Commit 3](https://github.com/michmue/telephone-system/tree/0769e7e1429683e615da0342a82654e00bad1ec3) ist der aktuelle Stand nach meinem Refactoring. Ziel war ein bessere MVC-Achritektur, Klassen Einfühung und allgemein mehr Codelesbarkeit durch bessere Varaiblennamen/Modularisierung/Methodenbezeichungen.
- Telefonanlage verwendet altes Character Encoding daraus ergaben sich "?" für Umlaute auf der Website: [Fix Character Encoding](https://github.com/michmue/telephone-system/blob/main/src/core/LogfileParser.php?plain=1#L29)

![beispiel](https://github.com/michmue/telephone-system/assets/37136025/d2b8a2d5-d1a0-4be7-b812-e0d298ecf098)

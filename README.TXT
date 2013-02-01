Webprogrammeren en -databases

PROJECT
=======
Project door groep webdb13AD3
Shafiq Ahmadi - 10242740
Ramon Creijghton - 5952948
Frank van Luijn - 6117015
Ingeleverd op 1 februari 2013

Resultaat zichtbaar op:
https://webdb.science.uva.nl/webdb13AD3/

Inlog-accounts zijn zelf aan te maken.
Er is ook een admin-account beschikbaar:
Login:		mail@webdb.science.uva.nl
Wachtwoord:	beoordeling



BRONCODE
========
Alle code is te vinden in de map htdocs/
maar ook, code-colored en in een vriendelijke opmaak, IN de PHPdoc (zie hieronder).

Er zijn twee MySQL-dumps beschikbaar:
- Een volledige, met structuur én gegevens van de test-omgeving: documentation/mysqldump-webdb13AD3.sql.gz
Hierin staan enkele test-users, -categories, -threads en -posts, zodat de werking van de site getest kan worden.

- Een met  uitsluitende de structuur, ten behoeve van een schone installatie van de site: documentation/mysqldump-webdb13AD3-structuur.sql.gz
Hierin is slechts een record: een admin-user met dezelfde gegevens als hierboven. Zodoende kunnen nieuwe categorieen worden aangemaakt.
Deze login is wel afhankelijk de salt die zich in mysqlconfig.xml bevindt. (zie ook de technische documentatie).
WAARSCHUWING:
Het is raadzaam uiteindelijk zelf een andere salt te kiezen en een eigen nieuwe admin-gebruiker te maken, aangezien deze MySQL-dump, alsook de mysqlconfig, alsmede de salt daarin op de openbare code-repository GitHub (hebben ge-)staan. Dit ongewijzigd laten zou een enorm beveiliginsriciso inhouden.



DOCUMENTATIE
============
Er zijn 3 typen documentatie beschikbaar.

- Functionele documentatie: documentation/functioneledocumentatie.pdf
Bedoeld voor de 'klant': het bedrijf dat onze software in gebruik gaat nemen om zijn klantenservice te ontlasten.
Bevat onder andere uitleg over het gebruik van de admin-functies van de site.

- Technische documentatie: documentation/technischedocumentatie.pdf
Bedoeld voor de IT-afdeling van het bedrijf.
Bevat uitleg over de de benodigdheden (LAMP), gekozen opzet (MVC-framework) en onderhoud (database).

- PHPdoc: documentation/phpdoc/<HTML..>  (Twee submappen met verschillende opmaak voor exact dezelfde php-doc: kies wat u fijner vindt browsen.)
Bedoeld voor de IT-afdeling van het bedrijf, of een toekomstige webdevelloper die uitbreiding op de site gaat plegen.
Bevat al het php-doc commentaar in de broncode, geordend naar classes, methodes en velden. Maakt duidelijk welke stukken code welke functie hebben in het framework.



BRONVERMELDING
=============
Nagenoeg alle code is van eigen hand, met uitzondering van twee delen JavaScript tbv de AJAX-elementen van de zoekfunctie (link naar bron aldaar).
Inspiratie komt van Frank van Luijns eerdere ervaring, StackOverflow (zowel tbv het idee van de site, als tbv code), en php.net.



SAMENWERKING
============
In de broncode is bij de meeste classes als PHP-doc opgenomen wie hoofdverantwoordelijk was voor de ontwikkeling ervan.

De ontwikkeling van de code is na te volgen op onze GitHub repository.
https://github.com/RCreyghton/webdb

De individuele bijdrages zijn aldaar ook getrackt. Een grafisch overzicht is beschikbaar op
https://github.com/RCreyghton/webdb/graphs/contributors


In zijn algemeenheid kunnen we verklaren:

Frank van Luijn had een rol had als expert. Gezien zijn ervaring in het maken van webapplicaties en het werken met MVC-structure, is hij in belangrijke mate verantwoordelijk voor de technische opzet van de site. Bovendien was hij altijd in staat problemen op te lossen.
Concreet is hij verantwoordelijk voor vitale onderdelen als de classe-structuur, index en error-afhandeling. Bovendien heeft hij de meeste formulier-controllers en -views (registeren, threads maken, etc) gebouwd.

Ramon Creijghton is verantwoordelijk voor opzet en onderhoud van de Data-strcutuur. Bovendien heeft veel uitvoerend werk gedaan qua code. Zo heeft hij diverse Models opgezet naar analagie van de Base, en is hij verantwoordelijk voor de meeste listings-views. (Threads-listings zoals Views_Threads_Base en childs. Category- en User-listing met admin-opties. Inclusief de bijbehorende controllers.) Tot slot is hij de bedenker en uitvoerder van de hele zoekfunctie, van SQL tot AJAX.

Shafiq Ahmadi heeft bijdrages gelevert aan Model, Control en View van Users. Zo heeft hij binnen de bestaande class-structuur het User_Model gemaakt, en een controller opgezet voor de Login, met bijbehordend formulier. Daarbij heeft hij ook logo's en icons ontworpen. Bovendien was hij verantwoordelijk voor de documenatie. In die hoedanigheid heeft hij gewerkt aan de functionele documentatie.


Bovendien melden de deelnemers nog de volgende persoonlijke ervaring.
Shafiq:
Ik vond het erg leuk om een keer een duscussiefom te schrijven in een team.

Ramon: 
Ik heb mijn vaardigheid in het bouwen van websites aardig opgefrist en uitebreid, niet in de laatste plaatst met dank aan Frank's expertise. Ik heb nu meer oog voor structuur en veiligheid van een webapplicatie.
Horizontaal samenwerken leidt soms wel tot rare keuzes. Zo is het (on) zichtbaar maken van vragen door Frank qua User Interface net tegengesteld geimplementeerd aan hoe ik Categorie en Gebruikers-beheer heb uitgevoerd.

Frank:
Voor het begin van dit vak zag ik er wat tegenop om veel tijd te steken in een project zoals deze. Dit omdat ik mijn werk dagelijks dergelijke sites/applicaties bouw en hiermee niets zou leren. Echter tot mijn eigen verbazing vond ik het een zeer leuk vak, niet vanwege het project zelf, alhoewel deze wel heel goed is gelukt, maar vanwege de samenwerking. Het is voor mij verfrissend en leerzaam om in dergelijke teamverband te werken, gezien dit in de praktijk wat meer ontbreekt. Graag ik Shafiq en Ramon dan ook bedanken voor de goede samenwerking en hun inzet.

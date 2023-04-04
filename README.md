# Case 12 - Fullstack PHP

###### Min Linode-server: http://192.46.239.230/ (och sidor skapade via detta CMS)
###### För att signa in: http://192.46.239.230/cms-admin/signin.php

###### För att starta detta projekt lokalt:
- Clona ner repot och se till att du står i rätt mapp "case12-php-cms"
- Kör komamandot: "docker compose up" i terminalen
- Gå till "localhost:8089" 
- Logga in med:
 - server: "mysql"
 - username: "db_user"
 - password: "db_password"
- Importera databasfilen som du hittar i mappen "app/sql-init" (detta för att skapa tabellerna och de tidigare skapade sidorna och användarna)
- Gå till "localhost:8088/cms-admin/signup.php" för att skapa en användare och logga in!

###### För att logga in och komma åt phpmyadmin på linode-servern:
- Gå till: http://192.46.239.230/phpmyadmin
- username: db_user_linode
- password: db_password_linode

---

I caset ska en applikation kodas som lagrar data i en relationsdatabas. Applikationen fungerar för att en administratör ska kunna skriva och publicera enklare sidor eller blogg-inlägg förslagsvis kallad `Page`.

## Grundläggande krav
- [x] Programspråk som får användas är PHP (utan ramverk), Javascript, HTML/CSS
- [x] Visa alla hemsidor som är publicerade genom applikationen
- [x] Sidor skall återfinnas i en enkel meny
- Administratör skall kunna:
  - [x] Loggas in 
  - [x] Registera sig 
  - [x] Läsa, skapa, editera och ta bort resurs 
  - [x] Lösenordet skall vara kryperad innan det lagras i databasen 
- Pages tabellen skall:
  - [x] Ha minst fyra databas-kolumner utöver primary key (t.ex title, content, created_at, site_id)
  - [x] Vara länkad på databasnivå till en användare 
  - [x] Hantera markdown (Det fungerar att spara ner det som text och låta klienten parsa markdown till html) 
- Besökare skall kunna:
 - [x] Besöka olika sidor t.ex "thewebapp.com?id=about" och "thewebapp.com?id=contact" alternativt med friendly urls "thewebapp.com/page/about" och "thewebapp.com/page/contact" 
 
## Utmaning
Utöver alla grundläggande krav:
- [x] Applikationen ska vara publicerad via Linode eller liknande hosting tjänst
- [x] Administratör ska kunna spara Page som draft
- [x] Sidor ska kunna hantera bilder
- Sidor skall återfinnas i en hierarkisk meny
- [x] Integrera en JavaScript WYSIWYG editor för innehållet i content fältet ex. https://editorjs.io/ eller https://www.tiny.cloud/
- Besökare skall kunna registera sig som intresserad av nyhetsbrev
 
## Utöver kraven och utmaningar kan:
- [x] Kan man enbart skapa/redigare/ta bort en sida om man är registrerad som admin i databasen
- [x] En admin kan ta bort/uppdatera information om en användare och ändra position på en användare (user/admin).
- [x] En användare kan ändra meny-prioritering. (hur menyn skrivs ut)

## Feedback
Veckan efter presentation kommer feedback ges under följande rubriker:

- Databas-hantering
- Användarupplevelse
- Allmän kodstil

*Designfeedback tillkommer från Mattias*

## Presentation- och Inlämningsdatum
Presenteras och inlämning för feedback är den 4:e april kl 8.45. Tiden 4e till 6de april är feedbackperiod då ni får feedback, ni kan under tiden jobba med er portfolio och göra klart gamla case.
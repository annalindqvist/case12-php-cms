# Case 12 - Fullstack PHP

###### Min Linode-server: http://192.46.239.230/
###### För att signa in: http://192.46.239.230/cms-admin/signin.php


I caset ska en applikation kodas som lagrar data i en relationsdatabas. Applikationen fungerar för att en administratör ska kunna skriva och publicera enklare sidor eller blogg-inlägg förslagsvis kallad `Page`.

## Grundläggande krav
- [x] Programspråk som får användas är PHP (utan ramverk), Javascript, HTML/CSS
- [x] Visa alla hemsidor som är publicerade genom applikationen
- [x] Sidor skall återfinnas i en enkel meny
- Administratör skall kunna:
 [x] - Loggas in 
 [x] - Registera sig 
 [x] - Läsa, skapa, editera och ta bort resurs 
 [x] - Lösenordet skall vara kryperad innan det lagras i databasen 
- Pages tabellen skall:
 [x] - Ha minst fyra databas-kolumner utöver primary key (t.ex title, content, created_at, site_id)
 [x] - Vara länkad på databasnivå till en användare 
 [x] - Hantera markdown (Det fungerar att spara ner det som text och låta klienten parsa markdown till html) 
- Besökare skall kunna:
 [x] - Besöka olika sidor t.ex "thewebapp.com?id=about" och "thewebapp.com?id=contact" alternativt med friendly urls "thewebapp.com/page/about" och "thewebapp.com/page/contact" 
 
## Utmaning
Utöver alla grundläggande krav:
- [x] Applikationen ska vara publicerad via Linode eller liknande hosting tjänst
- [x] Administratör ska kunna spara Page som draft
- [x] Sidor ska kunna hantera bilder
- Sidor skall återfinnas i en hierarkisk meny
- Integrera en JavaScript WYSIWYG editor för innehållet i content fältet ex. https://editorjs.io/ eller https://www.tiny.cloud/
- Besökare skall kunna registera sig som intresserad av nyhetsbrev
 
## Utöver kraven och utmaningar kan:
-[x] Kan man enbart skapa/redigare/ta bort en sida om man är registrerad som admin i databasen
-[x] En admin kan ta bort/uppdatera information om en användare och ändra position på en användare (user/admin).

## Feedback
Veckan efter presentation kommer feedback ges under följande rubriker:

- Databas-hantering
- Användarupplevelse
- Allmän kodstil

*Designfeedback tillkommer från Mattias*

## Presentation- och Inlämningsdatum
Presenteras och inlämning för feedback är den 4:e april kl 8.45. Tiden 4e till 6de april är feedbackperiod då ni får feedback, ni kan under tiden jobba med er portfolio och göra klart gamla case.
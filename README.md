# Individualaus Autoserviso Valdymo Sistema

Kursinio darbo I dalis: projektavimo dokumentas


## 1. Problema ir idėja

**Sistema vienu sakiniu:** Sistema yra skirta individualiai dirbantiems automechanikams ir mažiems autoservisams centralizuotai valdyti darbų srautą, stebėti atsarginių dalių prieinamumą ir automatizuoti remonto sąmatų skaičiavimą.

**Problema ir dabartinis procesas:** Susiduriama su problemoma, kad darbai organizuojami necentralizuotai, t. y. žodiniais susitarimais, telefonu, SMS žinute ar kitais informacijos šaltiniais. Atsarginės dalys dažnai užsakomos tik suderinus remonto darbus ir jų sąmatą su klientu. Jei klientas yra nuolatinis ir artimiausiu metu automobilio neplanuoja keisti, dažniausiai naudojamos atsarginės dalys gali būti užsakomos didesniais kiekiais. Tačiau nėra patogaus būdo sekti atsarginių dalių ir kitų serviso medžiagų likučius, todėl gali būti užsakomi jau turimų atsargų kiekiai, taip susidarant atsargų pertekliui ir papildomoms išlaidoms. 

**Nauda:** Sistema leis organizuoti remonto darbus vienoje vietoje - nuo kliento ir automobilio informacijos bei remonto užklausos pateikimo iki sąmatos sudarymo, reikalingų atsarginių dalių ir medžiagų prieinamumo bei jų panaudojimo, ir darbų užbaigimo. Klientas galės pateikti remonto užklausą viešoje formoje, o jos informacija bus išsaugoma sistemos duomenų bazėje. Bus galima matyti, kokie darbai yra atliekami, kokios dalys jiems reikalingos ir ar jos yra servise. Tai leis geriau planuoti darbus, laiku pasirūpinti reikalingomis dalimis ir efektyviau valdyti serviso atsargas.

**Naudotojai:** Automechanikas / serviso darbuotojas - pagrindinis sistemos naudotojas. Jis gali registruoti ir valdyti klientų bei jų automobilių informaciją, priimti ir administruoti remonto užklausas, atlikti ir registruoti diagnostiką, sudaryti remonto sąmatas, valdyti remonto darbų eigą, tikrinti atsarginių dalių likučius, registruoti jų panaudojimą ir inicijuoti reikalingų dalių užsakymus.  
  
Klientas - sistemoje nesiregistruoja ir neprisijungia. Jis gali viešoje formoje pateikti remonto užklausą, nurodyti savo kontaktinius bei automobilio duomenis ir problemą. Informaciją apie užklausos ir remonto eigą klientui pateikiama el. paštu.

**Prielaidos:**  
• Pagrindinis naudotojas bus individualiai dirbantis automechanikas arba mažo serviso darbuotojas.  
• Klientai galės pateikti remonto užklausas internetu be paskyros kūrimo.  
• Pildydamas viešą remonto užklausos formą, klientas automobilio duomenis įves rankiniu būdu, remdamasis automobilio techniniu pasu, jei nebus pasiekiamas išorinis API dekoduoti VIN (Vehicle Identification Number).  
• Klientų, automobilių, remonto darbų ir atsargų informacija bus saugoma bendroje duomenų bazėje.  
• Atsarginių dalių ir kitų serviso medžiagų likučius sistemoje registruos ir atnaujins serviso darbuotojas.  
• Klientams pakaks pagrindinių pranešimų apie užklausos ir remonto eigą el. paštu.  
• Atsarginių dalių suderinamumas su konkrečiu automobiliu bus administruojamas rankiniu būdu, jei nebus pasiekiamas išorinis API dalių suderinamumo patikrinti.  
• Kelių autoservisų duomenys bus saugomi bendroje duomenų bazėje, o kiekvieno serviso klientų, automobilių, remonto užsakymų ir atsargų duomenys bus atskiriami pagal servisą.

## 2. Apimtis

| Funkcija | Ką naudotojas galės atlikti | Pagrindinis modulis ar pagalbinė funkcija |
|---|---|---|
| Remonto užsakymų valdymas | Peržiūrėti gautas užklausas, jas priimti arba atmesti, registruoti diagnostikos rezultatus, stebėti ir keisti remonto būsenas bei pažymėti darbus kaip užbaigtus.| Pagrindinis |
| Remonto sąmatų sudarymas | Apskaičiuoti remonto kainą pagal darbų įkainius, reikalingų atsarginių dalių ir medžiagų kainas, parengti sąmatą ir išsiųsti ją klientui el. paštu patvirtinti.| Pagrindinis |
| Klientų ir automobilių valdymas | Registruoti klientus, saugoti jų automobilių duomenis (techniniai duomenys, registracijos liudijimo numeris ir VIN kodas) ir susieti juos su remonto užsakymais. | Pagalbinis |
| Vieša remonto užklausos forma | Pateikti remonto užklausą be paskyros, nurodant kontaktinius duomenis, automobilio duomenis ir problemos aprašymą.| Pagalbinis |
| Atsargų valdymas| Peržiūrėti atsarginių dalių ir serviso medžiagų likučius, registruoti jų gavimą bei panaudojimą.| Pagalbinis |
| El. pašto pranešimai| Informuoti klientą apie užklausos gavimą, jos priėmimą arba atmetimą, taip pat apie sąmatą ir remonto eigos pasikeitimus.| Pagalbinis |

**Į kursinio darbo apimtį neįeina:**  
• Išorinių API integracijos automatiniam automobilio techninių duomenų gavimui pagal VIN kodą.  
• Išorinių API integracijos automatinei atsarginių dalių suderinamumo paieškai.  
• Automatinis atsarginių dalių papildymas iš įvykdytų užsakymų tiekejų sistemose.  
• Internetinių mokėjimų apdorojimas ir integracija su buhalterinės apskaitos sistemomis.  
• Klientų paskyrų kūrimas ir savitarnos portalo kūrimas.  
• SMS pranešimų siuntimas.  
• Kelių autoservisų valdymas vienoje sistemoje.

## 3. Pagrindinis modulis

**Pavadinimas ir atsakomybė:** Remonto užsakymų valdymas. Modulis skirtas valdyti visą remonto procesą nuo kliento užklausos gavimo iki remonto darbų užbaigimo. Darbuotojas galės peržiūrėti užklausas, jas priimti arba atmesti, registruoti diagnostikos rezultatus, stebėti užsakymų būsenas ir valdyti remonto eigą.

**Logika, kurią reikės projektuoti ir testuoti:** Remonto užklausų ir užsakymų būsenų keitimo taisyklės, privalomų duomenų tikrinimas ir remonto proceso eigos kontrolė. Sistema turi užtikrinti, kad naujos užklausos lauktų darbuotojo sprendimo ir kad darbuotojas galėtų jas priimti arba atmesti. Priėmus užklausą, darbuotojas registruoja diagnostikos rezultatus, sudaro remonto sąmatą, naudodamasis sąmatų skaičiuokle. Prireikus, į sąmatą įtraukiamos reikalingos atsarginės dalys ir medžiagos iš sistemos atsargų. Jų kainos įtraukiamos į bendrą sąmatos sumą. Sąmata išsiunčiama klientui el. paštu. Remonto darbai gali būti pradėti tik klientui patvirtinus sąmatą. Sistema turi neleisti praleisti nustatytų proceso etapų.

**Įvestis:** Kliento pateikta remonto užklausa, kurioje nurodomi kliento kontaktiniai duomenys, automobilio duomenys ir problemos aprašymas. Pavyzdžiui, klientas nurodo savo vardą, pavardę, el. pašto adresą, automobilio markę, modelį, VIN kodą, registracijos liudijimo numerį ir aprašo pastebėtus keistus garsus pakaboje.

**Išvestis:** Sistemoje išsaugota remonto užklausa su atitinkama būsena ir susijusiais duomenimis. Priėmus užklausą ir atlikus diagnostiką, užregistruojami jos rezultatai bei sudaroma remonto sąmata, kuri išsiunčiama klientui el. paštu patvirtinti arba atmesti. Pavyzdžiui, diagnostikos metu nustatoma, kad susidėvėję šarnyrai skleidžia neįprastus garsus. Užsakymui išsaugomi diagnostikos rezultatai, sudaroma 60 € remonto sąmata ir nustatoma būsena „Laukiama sąmatos patvirtinimo“.

**Veikimo eiga:**  
1. Klientas pateikia remonto užklausą per viešą formą.

2. Sistema patikrina duomenis, išsaugo užklausą ir priskiria būseną „Laukiama darbuotojo sprendimo“.
3. Darbuotojas peržiūri užklausą ir ją priima arba atmeta. Atmetus užklausą, procesas baigiamas.

4. Priėmęs užklausą, darbuotojas atlieka diagnostiką ir užregistruoja jos rezultatus.

5. Darbuotojas sudaro remonto sąmatą, įtraukdamas darbų, reikalingų atsarginių dalių ir medžiagų kainas. Sistema išsaugo sąmatą ir išsiunčia klientui el. laišką su galimybe ją patvirtinti arba atmesti.

6. Klientui patvirtinus sąmatą, užsakymo būsena pakeičiama į „Vykdoma“ ir pradedamas remontas. Jei sąmata pakeičiama, ankstesnis patvirtinimas nebegalioja ir klientas turi patvirtinti naują jos versiją. Klientui atsisakius remonto, užsakymas uždaromas.
7. Baigęs remonto darbus, darbuotojas pakeičia užsakymo būseną į „Užbaigta“. Sistema užregistruoja panaudotas atsargines dalis ir atnaujina jų likučius.

### Taisyklės arba sprendimo žingsniai

1. Nauja remonto užklausa išsaugoma tik tada, kai užpildyti visi privalomi laukai, ir jai automatiškai priskiriama būsena „Laukiama darbuotojo sprendimo“.
2. Remonto darbai gali būti pradėti tik klientui patvirtinus aktualią sąmatos versiją. Pakeitus sąmatą, ankstesnis patvirtinimas nebegalioja.
3. Atsarginių dalių likučiai sumažinami tik užregistravus jų faktinį panaudojimą remontui. Sistema neleidžia panaudoti daugiau dalių, nei yra likę sandėlyje.

### Scenarijai būsimiems testams

| Scenarijus | Pradinės sąlygos ir konkreti įvestis | Veiksmas | Tikslus laukiamas rezultatas |
|---|---|---|---|
| Įprastas atvejis | Klientas užpildo visus privalomus laukus: vardą, pavardę, el. paštą, VIN, registracijos liudijimo numerį, automobilio techninius duomenis ir gedimo aprašymą.| Klientas pateikia užklausą per viešą formą.| Užklausa išsaugoma duomenų bazėje su unikaliu ID ir būsena „Laukiama darbuotojo sprendimo“. Klientui parodomas patvirtinimo pranešimas.|
| Ribinis atvejis arba konfliktas | Užsakymo sąmatą, kurios versiją klientas jau patvirtino, sudaro 60 €.| Darbuotojas pakeičia sąmatą ir padidina jos sumą iki 80 €.| Išsaugoma nauja sąmatos versija. Ankstesnis patvirtinimas nebegalioja, todėl užsakymo negalima pradėti vykdyti, kol klientas nepatvirtina naujos versijos|
| Klaida arba neįmanomas rezultatas | Užsakymas yra būsenos „Laukiama darbuotojo sprendimo“. Diagnostika neatlikta, sąmata nesukurta.| Darbuotojas bando pakeisti užsakymo būseną į „Vykdoma“.| Būsenos pakeitimas atmetamas. Užsakymo būsena lieka nepakitusi, o sistema pateikia pranešimą, kad prieš pradedant remontą būtina atlikti diagnostiką, sudaryti sąmatą ir gauti kliento patvirtinimą.|

**Jei modulis naudoja AI:** Netaikoma.

## 4. Kokybės atributas

**Pasirinktas atributas:** Saugumas

**Kodėl svarbus šiai sistemai:** Kiekvienas autoservisas turi savo klientus, automobilius, remonto užsakymus ir sąmatas. Būtina užtikrinti, kad vieno autoserviso darbuotojai negalėtų pasiekti kito autoserviso duomenų.

**Tikrinimo scenarijus ir sąlygos:** Sistemoje yra du autoservisai – A ir B. Kiekvienas turi savo darbuotojus ir remonto užsakymus. Autoserviso A darbuotojas bando peržiūrėti arba pakeisti autoserviso B užsakymą, tiesiogiai nurodydamas jo ID URL adrese arba HTTP užklausoje.

**Sėkmės kriterijus:** Autoserviso A darbuotojas negali peržiūrėti ar pakeisti autoserviso B užsakymo. Sistema atmeta neleistiną prieigą, o užsakymo duomenys lieka nepakeisti.

**Numatytas projektavimo sprendimas:** Naudoti Laravel autentifikaciją ir autorizaciją bei tenant_id pagrįstą duomenų izoliavimą. Kiekvienas remonto užsakymas susiejamas su konkrečiu autoservisu. Prieš atliekant duomenų peržiūros ar keitimo operacijas, sistema patikrina, ar užsakymas priklauso prisijungusio darbuotojo autoservisui.

**Kaip patikrinsiu vėlesniame etape:** Sukursiu automatinius Laravel testus, kuriuose patikrinsiu, ar darbuotojas gali pasiekti savo autoserviso užsakymus, tačiau negali peržiūrėti, pakeisti ar ištrinti kito autoserviso užsakymų.

**Sprendimo kaina arba ribojimas:** Reikės papildomai įgyvendinti prieigos kontrolę ir parašyti testus skirtingiems prieigos scenarijams. Pridėjus naujų funkcijų, taip pat reikės užtikrinti, kad jos neapeitų autoservisų duomenų izoliavimo taisyklių.

## 5. Pradinė sistemos struktūra

### Paprasta schema

![IAVS UML Komponentų schema](IAVS.drawio(1).svg)

| Sistemos dalis | Atsakomybė |
|---|---|
| Vieša remonto užklausos forma | Leidžia klientui pateikti remonto užklausą neregistruojantis sistemoje. Klientas nurodo kontaktinius duomenis, automobilio informaciją ir gedimo aprašymą. |
| Remonto užsakymų valdymas | Valdo remonto užsakymų gyvavimo ciklą: priima užklausas, registruoja diagnostikos rezultatus, keičia užsakymų būsenas ir kontroliuoja, ar klientas patvirtino aktualią sąmatos versiją prieš pradedant remontą. |
| Darbuotojo sąsaja | Suteikia darbuotojui grafinę aplinką užklausoms, klientams, automobiliams, remonto užsakymams, sąmatoms ir atsargoms peržiūrėti bei tvarkyti. |
| Remonto sąmatų sudarymas | Apskaičiuoja preliminarią remonto kainą pagal numatytus darbus, darbo kainas, reikalingas detales ir medžiagas. Leidžia sudaryti bei atnaujinti sąmatos versijas. |
| Atsargų valdymas | Tvarko detalių ir medžiagų likučius, jų kainas bei prieinamumą. Sumažina likučius tik tada, kai užregistruojamas faktinis detalių ar medžiagų panaudojimas. |
| El. pašto pranešimai | Siunčia klientams automatinius el. laiškus apie užklausos gavimą, jos priėmimą ar atmetimą, remonto sąmatą, sąmatos patvirtinimą ir remonto būsenos pasikeitimus. |
| Duomenų bazė | Saugo ir pateikia sistemos duomenis apie klientus, automobilius, remonto užsakymus, sąmatas, jų versijas, detales, medžiagas ir atsargų likučius. |
| Klientų ir automobilių valdymas | Registruoja ir tvarko klientų kontaktinius duomenis bei su jais susijusią automobilių informaciją. Leidžia darbuotojui rasti ir susieti klientą bei jo automobilį su remonto užsakymu. |

**Planuojamos technologijos ir pasirinkimo priežastys:**  
• PHP ir Laravel - backend programavimui.  
• HTML, CSS - internetinių puslapių struktūrai ir išvaizdai kurti.  
• JavaScript - papildomam naudotojo sąsajos interaktyvumui užtikrinti.  
• Blade - Laravel šablonų varikliui, skirtam dinamiškiems internetiniams puslapiams generuoti.  
• Bootstrap - prisitaikančiam ir nuosekliam naudotojo sąsajos dizainui kurti.  
• MySQL - reliacinei duomenų bazei, kurioje bus saugoma informaciją apie klientus, automobilius, remonto užsakymus, sąmatas, detales ir atsargų likučius.  
• Laravel Mail ir SMTP - automatiniams el. pašto pranešimams siųsti klientams apie gautas remonto užklausas, pateiktas sąmatas ir remonto būsenos pasikeitimus.  
  
Pasirinktos technologijos leis sukurti funkcionalią ir lengvai prižiūrimą internetinę sistemą. Laravel, Blade ir Bootstrap derinys padės sumažinti kūrimo sudėtingumą, o MySQL užtikrins patikimą sistemos duomenų saugojimą.

## 6. AI panaudojimas

### AI rengiant šį dokumentą

[Nurodykite, ar naudojote AI. Jei taip, užpildykite lentelę.]

| Priemonė ir užduotis | Ką panaudojau | Ką atmečiau arba perrašiau ir kodėl | Kaip patikrinau |
|---|---|---|---|
| ChatGPT - idėjos proto šturmas | Generavau ir aptariau sistemos funkcionalumo, komponentų struktūros ir technologijų pasirinkimo idėjas. | Atmečiau arba pakoregavau pasiūlymus, kurie buvo nereikalingi projekto apimčiai arba per sudėtingi įgyvendinti per numatytą laiką. | Įvertinau idėjų atitiktį projekto tikslui, funkciniams reikalavimams ir turimoms techninėms žinioms. |
| ChatGPT - technologijų pasirinkimas | Laravel, Blade, Bootstrap ir MySQL technologijų pasirinkimo argumentus.| Technologijų rinkinys pakoreguotas atsižvelgiant į projekto terminą ir turimas JavaScript žinias.| Palyginau technologijų tinkamumą projekto funkcijoms ir įgyvendinimo sudėtingumą. |
| ChatGPT - teksto rengimas| Paprašiau sugeneruoti dokumento tekstą pagal pateiktą struktūrą, reikalavimus ir projekto informaciją.| Pakoregavau fomuluotes ir pašalinau arba pakeičiau netikslumus, kad tekstas atitiktų mano projektą.| Patikrinau, ar tekstas atitinka pateiktus reikalavimus, projekto tikslą ir numatytas sistemos funkcijas. |
### Planuojamas AI naudojimas kuriant sistemą

**Kur ir kam naudosiu AI:** AI planuoju naudoti kaip pagalbinę priemonę sprendžiant programavimo problemas, aiškinantis Laravel funkcionalumą, kuriant SQL užklausų pavyzdžius ir jas optimizuojant, projektuojant duomenų bazės esybes bei jų ryšius, ieškant klaidų ir rengiant testavimo scenarijus.

**Kaip tikrinsiu pasiūlymus ir sugeneruotą kodą:** Sugeneruotą kodą peržiūrėsiu, įvertinsiu jo atitiktį sistemos reikalavimams ir išbandysiu praktikoje.

**Ar AI bus sistemos funkcionalumo dalis:** Ne.

## 7. Tolesnių darbų planas

| Darbas | Apčiuopiamas rezultatas | Planuojama darbų seka |
|---|---|---|
| Duomenų bazės projektavimas ir sukūrimas| MySQL duomenų bazė su klientų, automobilių, remonto užsakymų, sąmatų ir atsargų lentelėmis| 1 |
| Laravel projekto paruošimas| Veikianti aplikacija, prijungta prie duomenų bazės| 2 |
| Klientų ir automobilių valdymo kūrimas| Galimybė registruoti, peržiūrėti ir redaguoti klientų bei automobilių duomenis| 3 |
| Viešos remonto užklausos formos kūrimas| Veikianti forma, kuri priima kliento ir automobilio duomenis bei užklausos aprašymą| 4 |
| Remonto užsakymų valdymo kūrimas| Galimybė peržiūrėti užklausas, kurti užsakymus ir keisti jų būsenas| 5 |
| Remonto sąmatų sudarymas| Galimybė apskaičiuoti darbų ir detalių kainas bei atnaujinti sąmatą| 6 |
| Atsargų valdymo kūrimas| Galimybė tvarkyti detalių likučius ir registruoti faktinį jų panaudojimą| 7 |
| El. pašto pranešimų įgyvendinimas| Siunčiami pranešimai apie užklausas, sąmatas ir remonto būsenų pasikeitimus| 8 |
| Testavimas ir klaidų taisymas| Patikrinti pagrindiniai naudojimo scenarijai ir ištaisytos aptiktos klaidos| 9 |
| Prototipo paruošimas ir dokumentacijos užbaigimas| Veikiantis pagrindinių funkcijų prototipas ir parengta projekto dokumentacija| 10 |

**Būsimo prototipo veikimo scenarijus:** Prototipo demonstravimo metu klientas viešoje remonto užklausos formoje įves savo kontaktinius duomenis, automobilio informaciją ir gedimo aprašymą. Pateikta užklausa bus išsaugota duomenų bazėje.

Darbuotojas prisijungs prie sistemos, peržiūrės gautą užklausą, susies ją su klientu ir automobiliu, užregistruos diagnostikos rezultatus bei sudarys remonto sąmatą, į kurią bus įtrauktos darbų ir reikalingų detalių kainos.

Klientui bus išsiųstas el. laiškas su sąmata. Darbuotojas galės pradėti remonto darbus tik tada, kai klientas patvirtins aktualią sąmatos versiją. Jei sąmata bus pakeista, ankstesnis patvirtinimas nebegalios. Užregistravus faktinį detalių panaudojimą, atitinkamai sumažės jų likučiai sandėlyje.

Tikimasi, kad šis scenarijus parodys pagrindinių sistemos komponentų bendradarbiavimą ir pagrindinio remonto užsakymo proceso veikimą.

| Rizika arba neaiškumas | Kaip patikrinsiu arba sumažinsiu |
|---|---|
| Nepakankamai laiko visoms funkcijoms įgyvendinti| Pirmiausia sukursiu pagrindinį remonto užsakymo scenarijų, o papildomas funkcijas įgyvendinsiu vėliau. |
| Nepakankamos Laravel ir JavaScript žinios| Remsiuosi oficialia dokumentacija, kursų medžiaga ir mažais praktiniais pavyzdžiais. AI naudosiu kaip pagalbinę mokymosi priemonę. |
| Duomenų bazės ryšių ir vientisumo klaidos| Iš anksto suprojektuosiu lenteles, apibrėšiu pirminius bei išorinius raktus ir išbandysiu pagrindines operacijas. |
| Neteisingai keičiami atsargų likučiai| Tikrinsiu, kad likučiai mažėtų tik užregistravus faktinį detalių panaudojimą, o ne vien sudarant sąmatą. |
| Remontas pradedamas nepatvirtinus sąmatos| Serverio pusėje įgyvendinsiu ir išbandysiu taisyklę, draudžiančią pradėti remontą be aktualios sąmatos patvirtinimo. |
| El. laiškai neišsiunčiami arba siunčiami netinkamu metu| Išbandysiu SMTP konfigūraciją ir pagrindinius pranešimų scenarijus naudodamas bandomąją el. pašto aplinką. |
| Klaidos arba saugumo spragos formose| Įgyvendinsiu serverio pusės validavimą, prieigos kontrolę ir išbandysiu neteisingus įvesties duomenis. |



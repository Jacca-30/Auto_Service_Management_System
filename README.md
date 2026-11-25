# Autoserviso valdymo sistema

## 1. Projekto aprašymas

Tai Laravel pagrindu sukurta autoserviso valdymo sistema, skirta remonto užklausoms, klientams, automobiliams, diagnostikai, remonto sąmatoms ir inventoriaus likučiams valdyti.

Sistema palaiko kelių autoservisų (Multi-Tenancy) veikimą. Kiekvienas autoservisas turi atskirus duomenis, o darbuotojai gali pasiekti tik savo autoserviso informaciją.

### Pagrindinės funkcijos

- Klientų ir jų automobilių valdymas.
- Remonto užklausų registravimas per viešą formą.
- Remonto užsakymų priėmimas ir atmetimas.
- Diagnostikos rezultatų registravimas.
- Remonto sąmatų sudarymas ir versijavimas.
- Sąmatų siuntimas klientams el. paštu.
- Sąmatų patvirtinimas arba atmetimas per unikalią nuorodą.
- Atsarginių dalių ir medžiagų inventoriaus valdymas.
- Automatinis inventoriaus likučių atnaujinimas užbaigus remontą.
- Duomenų atskyrimas pagal autoservisą.

## 2. Naudojamos technologijos

- PHP 8.3
- Laravel 13.17
- MySQL
- HTML, CSS
- Tailwind CSS
- JavaScript
- Vite
- Composer
- Node.js ir NPM

## 3. Reikalavimai

Norint paleisti projektą, kompiuteryje turi būti įdiegta:

- PHP 8.3 arba naujesnė suderinama versija.
- Composer.
- MySQL duomenų bazės serveris.
- Node.js ir NPM.
- Git.

## 4. Projekto atsisiuntimas ir įdiegimas

Atsisiųskite projektą iš GitHub:

```bash
git clone <GITHUB_REPOSITORY_URL>
```

Pereikite į projekto katalogą:

```bash
cd <PROJECT_DIRECTORY>
```

Įdiekite PHP priklausomybes:

```bash
composer install
```

Įdiekite JavaScript priklausomybes:

```bash
npm install
```

## 5. Aplinkos konfigūracija

Sukurkite `.env` failą pagal pateiktą pavyzdį:

```bash
cp .env.example .env
```

Sugeneruokite aplikacijos raktą:

```bash
php artisan key:generate
```

`.env` faile sukonfigūruokite MySQL duomenų bazės prisijungimo duomenis:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=YOUR_DATABASE_NAME
DB_USERNAME=YOUR_DATABASE_USER
DB_PASSWORD=YOUR_DATABASE_PASSWORD
```

Prieš paleidžiant migracijas reikia sukurti tuščią MySQL duomenų bazę.

## 6. Duomenų bazės paruošimas

Sukurkite lenteles ir įrašykite pradinius duomenis:

```bash
php artisan migrate --seed
```

Seederiai gali sukurti pradinius autoservisų, darbuotojų ir kitus demonstracinius duomenis.

**Pastaba:** migracijų ir seederių vykdymas su `migrate:fresh` ištrina esamus lentelių duomenis. Naudokite šią komandą tik tada, kai norite iš naujo paruošti demonstracinę duomenų bazę.

## 7. El. pašto konfigūracija

Kūrimo aplinkoje naudojamas laiškų registravimas į Laravel žurnalą.

`.env` faile:

```env
MAIL_MAILER=log
```

Tokiu atveju sąmatų el. laiškai nesiunčiami į tikrą el. pašto dėžutę. Jų turinį ir patvirtinimo nuorodas galima rasti faile:

```text
storage/logs/laravel.log
```

## 8. Projekto paleidimas

Paleiskite Laravel serverį:

```bash
php artisan serve
```

Atskirame terminalo lange paleiskite Vite:

```bash
npm run dev
```

Aplikacija pasiekiama adresu:

```text
http://127.0.0.1:8000
```

Prieš galutinį projekto paleidimą produkcinėje aplinkoje galima sugeneruoti optimizuotus frontend failus:

```bash
npm run build
```

## 9. Automatinių testų vykdymas

Automatinius testus galima paleisti komanda:

```bash
php artisan test
```

Testai skirti pagrindinių sistemos funkcijų, verslo taisyklių ir skirtingų autoservisų duomenų izoliavimo patikrinimui.

## 10. Demonstravimo scenarijus

Pagrindinį sistemos veikimą galima demonstruoti tokia seka:

1. Prisijungti prie pirmo autoserviso darbuotojo paskyros.
2. Per viešą formą pateikti kliento remonto užklausą.
3. Darbuotojui priimti remonto užklausą.
4. Užregistruoti diagnostikos rezultatus.
5. Sudaryti remonto sąmatą, įtraukiant darbų ir inventoriaus prekių kainas.
6. Išsiųsti sąmatą klientui.
7. Patvirtinimo nuorodą rasti `storage/logs/laravel.log` ir atidaryti ją naršyklėje.
8. Patvirtinti sąmatą.
9. Užbaigti remontą ir patikrinti inventoriaus likučių pasikeitimą.
10. Prisijungti prie antro autoserviso ir patikrinti, ar pirmojo autoserviso duomenys jam neprieinami.

## 11. Duomenų saugumas

Sistemoje įgyvendintas duomenų atskyrimas pagal autoservisus naudojant Laravel Global Scope ir Local Scope mechanizmus.

Tai užtikrina, kad darbuotojai galėtų pasiekti tik savo autoserviso klientų, inventoriaus ir remonto užsakymų duomenis.

Sąmatų patvirtinimui naudojami unikalūs saugomi tokenų maišos įrašai ir jų galiojimo laikas.

---

**Projekto paskirtis:** autoserviso veiklos procesų automatizavimas ir valdymas.

**Technologinė platforma:** Laravel / PHP / MySQL.

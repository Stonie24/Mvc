# Mvc Project – Constellation Blackjack

[![Build Status](https://scrutinizer-ci.com/g/Stonie24/Mvc/badges/build.png?b=main)](https://scrutinizer-ci.com/g/Stonie24/Mvc/build-status/main)
[![Code Coverage](https://scrutinizer-ci.com/g/Stonie24/Mvc/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/Stonie24/Mvc/?branch=main)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Stonie24/Mvc/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/Stonie24/Mvc/?branch=main)

![Screenshot](img/info.png)

## Om projektet
Detta är ett MVC-projekt byggt med Symfony och PHP. Det innehåller en report-sida med kursmomentsdokumentation samt ett Blackjack-spel under `/proj` där spelaren kan spela 1–3 händer samtidigt mot banken med insatser i en stjärnkonstellations-tema.

## Table of Contents
- [Setup](#setup)
- [Install](#install)
- [Databas](#databas)
- [Usage](#usage)
- [Tester](#tester)
- [Dokumentation](#dokumentation)
- [Info](#info)

## Setup
Klona repot:
```bash
git clone https://github.com/Stonie24/Mvc
cd Mvc
```

## Install
Installera beroenden:
```bash
composer install
npm install
```

Kopiera miljöfilen och konfigurera:
```bash
cp .env .env.local
```

## Databas
Projektet använder SQLite. Skapa databasen och kör migrationer:
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

## Usage
Starta den lokala servern:
```bash
symfony serve
```

Öppna webbläsaren på `http://127.0.0.1:8000`

Spelet finns på `/proj` och report-sidan på `/report`.

Kontrollera tillgängliga routes:
```bash
php bin/console debug:router
```

## Tester
Kör enhetstester:
```bash
composer phpunit
```

Generera kodtäckningsrapport:
```bash
composer phpunit-coverage
```

## Dokumentation
Generera phpdoc:
```bash
composer phpdoc
```

Generera phpmetrics:
```bash
composer phpmetrics
```

Dokumentationen finns sedan under `docs/`.

## Info
This project was made by William Stenqvist.  
Contact: GitHub [@Stonie24](https://github.com/Stonie24) or wise23@student.bth.se
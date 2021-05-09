# Localhero CRM
[![Build Status](https://devops.alphatier.de/api/badges/Alphatier/alphatierCRM/status.svg)](https://devops.alphatier.de/Alphatier/alphatierCRM)

## Struktur

Alle Module haben einen eigenen Namespace z.B. \Callcenter.

Für Jedes Modul wird eine eigene Datei mit Routes angelegt und im
LocalheroPortal\Core\Providers\RouteServiceProvider registriert.

## Module

### Core

Zuständig für User-Management

### Callcenter

Enthält Funktionalität für Callcenter und Aussendienst Mitarbeiter

### LLI (LocalLink Interface)

Google MyBusiness Api Zugriff

### Business

Modul zur Verwaltung von Produkten

## API

### Standard

Alle API Controller sollen folgende Argumente annehmen:

* per_page => Anzahl Leads pro Seite der Pagination. per_page=0 gibt alle Leads aus (default: 15)
* page => welche Seite der Pagination
* sort_by => DB-Attribut nach dem sortiert werden soll

## User-Rollen

### Callcenter

* Callcenter-Agent
    * kann telefonieren
* Callcenter-Supervisor
    * kann Experten Leads zuweisen
* Callcenter-Admin
    * kann neue Agents, Supervisors und Experts anlegen
* Expert
    * Aussendienst

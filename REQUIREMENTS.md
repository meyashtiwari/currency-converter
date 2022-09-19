# Requirements

## Goal

Implement an app showing currency rates.

## Technology stack
* PHP
* Laravel
* Vue
* MySQL

**Usage of vanilla framework functionalities is preferred. Amount of 3rd-party packages should be minimized.**

## 1. Console commands

### a. Importing currency rates

Implement necessary command(s) to import data from http://www.cbr.ru/scripts/XML_daily.asp.

## 2. API

Implement REST API providing various endpoints. You have to define API structure yourself based on high-level requirements.

API must be secured with token-based authentication. Authentication endpoints have to be provided.

Following endpoints returning currency data are required.

### a. Currencies with their currency rates.

Input parameters (all are optional):

* page: Page to return.
* page_size: Number of currencies per page.
* date: Date to return currency rates for.
* base_currency_id: ID of the currency to return rates for (extra calculations required).

Response:

* An array of currencies with:
* ID, symbol.
* Rate converted to selected base currency with nominal applied.
* Rate nominal. Some currency rates are large numbers. They have to be divided by the nominal so that final rate doesn't exceed 1000. Nominal is a value of 10^(x*3) where "x" is an integer greater or equal to zero, i.e. 1, 1000, etc.
* Maximum, minimum and average rate of the currency.

### b. History of the specific currency

Input parameters (all except `id` are optional):

* id: ID of the currency to return
* page: Page to return.
* page_size: Number of days per page.
* date_from/date_to: Date region to return history for.
* base_currency_id: ID of the currency to return rates for (extra calculations required).

Response:

* An array of daily historical data. One record per day with:
* Rate converted to selected base currency.

## 3. Frontend

Implement SPA based on Vue. The following views are required.

### a. List of currencies.

Elements:

* Table with currencies and their data.
* Base currency dropdown.
* Page size dropdown.
* Page selection.

### b. History of the currency

Elements:

* Table with daily currency rate.
* Base currency dropdown.
* Page size dropdown.
* Page selection.
* Date from/to.
* Line chart with the daily rate.

## 4. Unit tests

Cover the most important parts with unit tests.

# Full Stack Web Developer Test

This is a basic implementation of full stack web developer test.

Full requirements are available at [REQUIREMENTS.md](REQUIREMENTS.md).

**Your goal is to finish this project:**

1. Create fork of this repository (private).
2. Implement missing functionality (check "what's left to implement" section).
3. Commit your changes.
4. Create pull request to original repository.

## What's implemented

* Registration, authentication of users.
* Database structure and importing of currency rates.
* Page showing currency rates for last available date.

## What's left to implement

1. Paging.
2. Base currency selection.
3. Nominal calculation and display.
4. Min/max/average rate.
5. History of currency rate.
6. History chart.
7. Unit tests.

## Time to complete

Please spend no more that **4 hours** on this test.

## Constraints

Implementing functionality with native Laravel/Vue instruments will be valued greater than using 3rd-party packages (paging, charts, etc.).

## Installation

```
# Clone repository.

cp .env.example .env

# Set database connection in ".env" file.

# Obtain API key at https://fixer.io/ and insert it into `.env` file via APILAYER_API_KEY variable.

composer install

php artisan key:generate

php artisan migrate

php artisan currency:import

npm install

npm run dev

php artisan serve

# Go to http://localhost:8000/
```

# Book Review

A Laravel 12 application for managing book reviews.

## Setup

1. `git clone git@github.com:awashrahnaeem/book-review.git`
2. `composer install`
3. Copy `.env.example` to `.env` and fill in your database + mail settings  
4. `php artisan key:generate`  
5. `php artisan migrate --seed`  
6. `npm install && npm run dev`  

## Usage

- Register a new user to trigger email verification  
- Browse to `/books` to see the list, `/books/{id}` for details

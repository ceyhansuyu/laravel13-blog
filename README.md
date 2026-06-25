# Laravel 13 Blog Script

A minimalist, performance-optimized, and open-source blog platform built with Laravel 13, designed for a clean user experience and fast page load times.

## 🚀 Features

- **Performance-Focused:** Optimized assets and minimal network requests for fast loading.
- **Clean UI/UX:** A minimalist, premium interface built using Tailwind CSS v4.
- **Media Manager:** Built-in image management supporting modern formats and precise image cropping.
- **SEO Optimization:** Dynamic and custom SEO description generation for posts.
- **AJAX Comment System:** Interactive and asynchronous comment sections secured by hCaptcha integration.
- **Universal Database Schema:** Clean and standard database architecture using English enums (`draft`, `publish`).

## 🛠️ Tech Stack

- **Backend:** Laravel 13 & Eloquent ORM
- **Frontend:** Tailwind CSS v4, Alpine.js
- **Security:** hCaptcha

## 🔧 Requirements

- PHP 8.2 or higher
- MySQL 8.0+

## 📥 Installation

Follow these steps to install and set up the project in your local development environment.

1. **Clone the repository:**
   ```bash
   git clone https://github.com/ceyhansuyu/laravel13-blog.git

2. **Install Composer dependencies:**
    ```bash
    composer install

3. **Configure your environment variables:**
    ```bash
    cp .env.example .env
    php artisan key:generate
    
4. **Run database migrations:**
    ```bash
    php artisan migrate

5. **Link the storage directory and build frontend assets:**
    ```bash
    php artisan storage:link
    npm install && npm run dev


**💡 Important Note on First Setup:**
You do not need default database seeders or predefined login credentials. Simply navigate to the registration page and create a new account. The system is designed to automatically grant the Founder (highest authorization) role to the very first registered user. Subsequent registrations will default to regular user roles.

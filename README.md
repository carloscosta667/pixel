<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Car Service at Pixel

The system will then have unauthenticated READ functionally for users be able to select a service filtered by type, select a date for that service and finally select the mechanics available for that date.

## Installation steps

- ** 1. Pull the code from https://github.com/carloscosta667/pixel **
- ** 2. Copy over the .env.example to .env file **
- ** 3. composer install **
- ** 4. php artisan migrate and click enter to create pixel database **
- ** 5. php artisan db:seed It will create your account to be able to test **
- ** 6. Create postman collection by uploading this file Pixel_Car_Booking_API_(v1).postman_collection.json located in the root of the project**
- ** 7. Log in and collect "api_token" and set up on collection Auth Type 'Bear Token'**

## Notes

- ** When you do php artisan migrate:rollback & php artisan migrate always run php artisan db:seed to create your account **



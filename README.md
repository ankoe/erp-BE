# Minta Jasa website admin
Mengelola data konten [aplikasi](http://google.play) dan [website](https://mintajasa.com) minta jasa

## Description
- Core : Laravel 7.21.0 (PHP)
- Database : Postgresql
- SMS Provider : zenziva (sementara, cari price yg lebih murah)
- Time Zone :  UTC (database), untuk menampilkan data sesuaikan waktu lokal user

#### Requipment
- Composer
- Webserver : Xampp / Laragon
- NGINX (*production mode*)

#### Minimal Version
- PHP 7.2.5
- Postgres 11.3

### Must Available
- BCMath PHP Extension
- Ctype PHP Extension
- Fileinfo PHP extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension

## Build Setup
### Development Mode
1. Clone ke folder web server htdocs
2. Buka command prompt dan arahkan ke path / folder project ini
3. Ketik perintah `composer install` untuk install dependency
4. Buat database baru melalui phppgadmin atau pgadmin
5. Buka folder project dan copy *.env.example* menjadi *.env*
6. Buka file *.env* dan sesuaikan berdasarkan konfigurasi database
```
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```
7. Pada command prompt ketik `php artisan migrate`
8. Lanjutkan dengan `php artisan db:seed`
9. Atau alternatif cepat 7 dan 8 `php artisan migrate:fresh --seed`
10. Generate unique key melalui perintah `php artisan key:generate`
11. Sambungkan storage ke folder public dengan perintah `php artisan storage:link`
12. Buat key jwt dengan perintah `php artisan jwt:secret`
13. Jalankan server dengan mengetikan perintah `php artisan serve`


### Production Mode
1. Akses server kemudian clone project ini, setelah selesai masuk ke direktori project
2. Ketik perintah `composer install` untuk install dependency
3. Buat database baru melalui **phppgadmin** / **pgadmin** / **bash** langsung
4. Copy *.env.example* menjadi *.env*
5. Buka file *.env* dan sesuaikan berdasarkan konfigurasi database
```
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```
6. Ketik `php artisan migrate`
7. Lanjutkan dengan `php artisan db:seed`
8. Atau alternatif cepat 6 dan 7 `php artisan migrate:fresh --seed`
9. Generate unique key melalui perintah `php artisan key:generate`
10. Sambungkan storage ke folder public dengan perintah `php artisan storage:link`
11. Buat key jwt dengan perintah `php artisan jwt:secret`
12. Jangan lupa arahkan root pada server block ke 

> ...path_project\public
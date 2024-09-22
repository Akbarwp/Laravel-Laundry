# Laravel Laundry

The laundry business is increasingly popular as a source of adequate income, exemplified by the initiative led by the village head of Simokerto, Surabaya, which operates five locations. However, the management of transactions remains traditional, and the lack of monitoring has resulted in minimal information about the laundry's progress. To address socio-economic disparities in Simokerto, the village head has involved underprivileged families (Gamis) in a labor-intensive laundry program, where income from laundry services is allocated to the laundry itself, while additional services like ironing and delivery are designated for Gamis.

The challenges faced include insufficient monitoring of Gamis, with only one or two successfully employed, as the village head lacks clear information on whether those hired are part of Gamis. Another challenge is the increasing number of transactions, leading to long queues and disrupting service delivery, which may cause customer dissatisfaction.

This research proposes a solution in the form of an application that can monitor the laundry program, facilitating the social program for Gamis, implementing priority services, and generating digital reports. The application is built using the Priority Service method, helping to manage data, transactions, and effectively monitor the Gamis social program in the laundry business.

This application is the result of my Tugas Akhir (Thesis), which I have been working on over the past few months. The project not only fulfills the requirements for completing my degree but also serves as an opportunity to apply the knowledge and skills I’ve gained throughout my studies. By developing this application, I hope to offer a practical solution that benefits the community while showcasing my abilities in software development. Moreover, this project reflects my dedication and commitment to completing my education and making a positive contribution to my surroundings.

## Tech Stack

- **Laravel 11**
- **Laravel Breeze**
- **MySQL Database**
- **[maatwebsite/excel](https://laravel-excel.com/)**
- **[spatie/laravel-pdf](https://spatie.be/docs/laravel-pdf/v1/introduction)**
- **[spatie/laravel-permission](https://spatie.be/docs/laravel-permission/v6/introduction)**
- **[spatie/laravel-sluggable](https://github.com/spatie/laravel-sluggable)**
- **[barryvdh/laravel-debugbar](https://github.com/barryvdh/laravel-debugbar)**

## Features

- Main features available in this application:
  - CRUD Data Master
  - Laundry Transaction Management
  - Implementation Priority Service method
  - Monitoring Gamis
  - Generate Reports

## Installation

Follow the steps below to clone and run the project in your local environment:

1. Clone repository:

    ```bash
    git clone https://github.com/Akbarwp/Laravel-Laundry.git
    ```

2. Install dependencies use Composer and NPM:

    ```bash
    composer install
    npm install
    ```

3. Copy file `.env.example` to `.env`:

    ```bash
    cp .env.example .env
    ```

4. Generate application key:

    ```bash
    php artisan key:generate
    ```

5. Setup database in the `.env` file:

    ```plaintext
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=laravel_laundry
    DB_USERNAME=root
    DB_PASSWORD=
    ```

6. Run migration database:

    ```bash
    php artisan migrate
    ```
    
7. Run seeder database:

    ```bash
    php artisan db:seed
    ```

8. Run website:

    ```bash
    npm run dev
    php artisan serve
    ```

## Screenshot

- ### **Login page**

<img src="https://github.com/user-attachments/assets/c1784d4a-a3f3-4202-9cb1-154e6f9d1ea7" alt="Halaman Login" width="" />
<br><br>

- ### **Transaction page**

<img src="https://github.com/user-attachments/assets/b86692ae-91e4-4a30-a227-f712dc9a5893" alt="Halaman Transaksi" width="" />
&nbsp;&nbsp;&nbsp;
<img src="https://github.com/user-attachments/assets/fef5b3c1-19cc-481d-9e78-4b128900ab52" alt="Halaman Tambah Transaksi" width="" />
&nbsp;&nbsp;&nbsp;
<img src="https://github.com/user-attachments/assets/257b6965-a759-4586-92fb-65225ba8fa49" alt="Halaman Jadwal Transaksi" width="" />
&nbsp;&nbsp;&nbsp;
<img src="https://github.com/user-attachments/assets/0c3ac666-b617-4174-8391-e58362421e1b" alt="Cetak Struk" width="" />
<br><br>

- ### **Monitoring Gamis page**

<img src="https://github.com/user-attachments/assets/047449e1-1062-4c3c-9c6f-beddb4f691d1" alt="Halaman Monitoring Gamis" width="" />
&nbsp;&nbsp;&nbsp;
<img src="https://github.com/user-attachments/assets/ae402d48-9973-402c-aeaa-d6cbb7b981ab" alt="Halaman Monitoring Detail Gamis" width="" />
<br><br>

- ### **Report page**

<img src="https://github.com/user-attachments/assets/5eb469b1-5452-4f97-aea3-0afe4ad42bdd" alt="Halaman Laporan Pendapatan Laundry" width="" />
&nbsp;&nbsp;&nbsp;
<img src="https://github.com/user-attachments/assets/185ee332-d469-424b-9823-5be0c2d35cca" alt="Laporan Pendapatan Laundry" width="" />
<br><br>

- ### **Landing page**

<img src="https://github.com/user-attachments/assets/7240a75a-8e1f-4537-8200-a5ef279c3eef" alt="Landing Page" width="" />
&nbsp;&nbsp;&nbsp;
<img src="https://github.com/user-attachments/assets/5afe2ca0-f340-4940-8c20-6f2bef88ddb6" alt="Landing Page - Cek Transaksi" width="" />
<br><br>

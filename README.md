# Payment Page

This project was made to meet the challenge for a [job described in this link ](project-requirements.md)

## Pre-Installation requirements

-PHP 8.2;
-Composer;
-Docker;
-Docker-compose;
-An account on the website https://sandbox.asaas.com/

## Installation

1. Access the root folder of the project;
2. Create a copy of the file .env.example, rename it to .env;
3. Fill the information regarding ASAAS sandbox keys: ASAAS api key and ASAAS Wallet ID
3. Install the packages with the following command:
    composer install
4. Run the command below and wait for it to be done
    ./vendor/bin/sail build
    ./vendor/bin/sail up
5. The first time you run the system, you will have to run migrations, seeders and generate the app key inside the sail container with the following commands:
    ./vendor/bin/sail artisan migrate:fresh --seed
    ./vendor/bin/sail artisan key:generate
    

## Running the system

1. To start the system you should run the following command at the root of the project:
    ./vendor/bin/sail up
2. Access the system through the URL provided on the command line.
3. To stop the system you should run the following command at the root of the project:
    ./vendor/bin/sail stop

# Installation

- PHP dependencies :
    - `composer install`
- Create environment file :
    - `cp .env .env.local`
- Edit .env.local
    - this line :
    - `DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/simple_library_db?serverVersion=8.0.32&charset=utf8mb4"`
        - "app" = your MySQL user
        - "!ChangeMe!" = your password
- Create databes and make migrations :
    - `symfony console doctrine:database:create` or `php bin/console doctrine:database:create`
    - `symfony console doctrine:migrations:migrate` or `php bin/console doctrine:migrations:migrate`



Note: This project is written with php 8.0

If you are using docker, you can do following steps in the root of project:

    docker build . --tag test_calc_commission
    docker container run -d --name test_calc_commission_app ---volume $(pwd):/var/www test_calc_commission
    docker exec -it test_calc_commission_app bash

Do following steps in the root of project inside the container in order:

    cp .env.example .env
    composer install
    php artisan key:generate

For running test, run following script in the root of project:

    ./vendor/bin/phpunit


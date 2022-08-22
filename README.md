

Note: This project is written with php 8.1

If you are using docker, you can do following steps in the root of project:

    docker build . --tag test_calc_commission
    docker run -it -d --name test_calc_commission_app -v $(pwd):/var/www test_calc_commission
    docker exec -it test_calc_commission_app bash

Do following steps in the root of project inside the container in order:

    cp .env.example .env
    composer install
    php artisan key:generate

For running test, run following script in the root of project:

    ./vendor/bin/phpunit


## Installation Project
1. Clone the project to parent directory

    ```bash
    $ cd ..
    $ git clone git@github.com:uTarasa/doc-spy.git doc-spy
    ```

2. Install dependencies

    ```bash
    $ cd doc-spy && composer install
    ```

3. Setting file permissions

    ```bash
    $ sudo chmod -R 777 var/cache var/logs var/sessions
    ```
        
4. Build/run containers with (with and without detached mode)

    ```bash
    $ docker-compose build
    $ docker-compose up -d     
        
5. Create database and tables if not exist

    ```bash
    $ docker-compose exec app bash
    $ php bin/console doctrine:database:create
    $ php bin/console doctrine:schema:update --force
    ```
    
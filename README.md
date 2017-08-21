## Installation Docker Symfony
1. Clone Docker Symfony to preferred directory

    ```bash
    $ git clone git@github.com:maxpou/docker-symfony.git doc-spy-docker
    ```

2. Create a `.env` from the `.env.dist` file. Change `SYMFONY_APP_PATH` to `../doc-spy`  

    ```bash
    $ cd doc-spy-docker
    $ cp .env.dist .env
    $ nano .env
    ```

3. Build/run containers with (with and without detached mode)

    ```bash
    $ docker-compose build
    $ docker-compose up -d
    ```


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
        
4. Create database and tables if not exist

    ```bash
    $ cd ../doc-spy-docker/
    $ docker-compose exec php bash
    $ sf3 doctrine:database:create
    $ sf3 doctrine:schema:update --force
    ```
    
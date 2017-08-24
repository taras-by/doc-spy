## Installation Project
1. Clone the project to parent directory

    ```bash
    $ git clone git@github.com:uTarasa/doc-spy.git doc-spy
    ``` 

2. Setting file permissions

    ```bash
    $ sudo chmod -R 777 var/cache var/logs var/sessions
    ```

3. Build/run containers with (with and without detached mode)

    ```bash
    $ cd doc-spy
    $ docker-compose build
    $ docker-compose up -d  

4. Install dependencies

    ```bash
    $ docker-compose exec app bash
    $ composer install
    ```
        
5. Create database and tables if not exist

    ```bash
    $ php bin/console doctrine:database:create
    $ php bin/console doctrine:schema:update --force
    ```
    
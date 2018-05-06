## Installation Project
1. Clone the project to parent directory

    ```bash
    $ git clone git@github.com:taras-by/doc-spy.git doc-spy
    ``` 
2. Build/run containers with (with and without detached mode)

    ```bash
    $ cd doc-spy
    $ docker-compose build
    $ docker-compose up -d  

3. Install dependencies

    ```bash
    $ docker-compose exec app bash
    $ composer install
    ```
        
4. Create database and tables if not exist

    ```bash
    $ php bin/console doctrine:database:create
    $ php bin/console doctrine:schema:update --force
    ```
### Run console commands with Docker
```
$ ./docker/composer install
$ ./docker/console
```
    
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
    $ ./docker/composer install
    ```
        
4. Create database and tables if not exist

    ```bash
    $ ./docker/console doctrine:schema:update --force
    ```
4. Run application: http://localhost:834
### Usage
Start containers:
```
$ ./docker/start
```
Symfony commands:
```
$ ./docker/console
```
Composer:
```
$ ./docker/composer update
$ ./docker/composer install
```
Stop containers:
```   
$ ./docker/stop
```
# **Travho**

## **Acerca de Travho**

TRAVHO es una API simple que permite a los usuarios prestar servicio de renta de casas, así como , solicitar servicios de renta a otros usuarios. Lo anterior se base en las relaciones de casas, rentas y usuarios.

- **[Documentación](https://app.swaggerhub.com/apis-docs/GarciaAlejandro/Travelers/1.0.0#/ ).**
- **[Manual de instalación](https://docs.google.com/document/d/1Wp2In0jmpjy7xdZLoG7dgG4OWusw_6lMQfIC0BX3G_M/edit).**
- **[Diagrama de recursos](https://drive.google.com/file/d/1779bWg0p5Fz1k32d5vKmvwjmCtwBdihe/view?usp=sharing).**
- **[Deploy](https://evening-wave-89560.herokuapp.com/).**

## **Pre-requisitos**

- Php mayor a  7.2.9
- manejador de paquetes Composer version 1.9.1 o mayora
- Postgresql instalado y añadido al path
- manejador de versiones Git version  2.21.

## **Instalación**
- ## Paso 1 
    clonar el repositorio de la api con el siguiente comand  
    ~~~
    git clone https://github.com/Eliezfer/Travho.git 
    ~~~
- ## Paso 2 
     ingresa a la dirección del proyecto que se ha clonado por medio de la consola 
- ## Paso 3 
    Ejecute el comando   
    ~~~
    composer install 
    ~~~
    y espere a que termine de ejecutarse
- ## Paso 4 
    Crear una base de datos en Postgresql ejecutar el comando  
    ~~~
    psql -U postgres 
    ~~~
    Cambia "postgres" por el nombre de tu usuario si postgres no es el usuario.  
    acceder al bash de postgres y ejecuta ` \l ` 

- ## Paso 5 
    Para crear tu base de datos ejecute en el mismo bash de postgres  
    ~~~ 
    CREATE DATABASE travho; 
    ~~~   
    y verifica que se creó con el comando   
    ~~~
    \l 
    ~~~   
    ejecuta el comando  
    ~~~
    exit 
    ~~~   
    para salir del bash de postgres

- ## Paso 6 
    Crea un archivo `.env` en la carpeta Travho y abre el archivo `.env.example` y copia su contenido al archivo `.env` que acabas de crear.
- ## Paso 7 
    Accede al .env y cambia las siguientes variables y guarda el archivo
    ~~~
    APP_NAME=Laravel
    APP_ENV=local
    APP_KEY=base64:P09E8lmC6yYzENKtfRwULulQ3So68jPzDJg2Rr0cfGg=
    APP_DEBUG=true
    APP_URL=http://localhost
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=travho
    DB_USERNAME=root
    DB_USERNAME=postgres
    DB_PASSWORD=
    ~~~
    En ocaciones puede que `DB_USERNAME` y `DB_PASSWORD` sean otros, si este es su caso cambie el usuario y contraseña del manejador de su equipo.

- ## Paso 8 
    Ejecutar el siguiente comando en la dirección del proyecto clonado para abrir la consola de laravel
    ~~~
    php artisan tinker
    ~~~

- ## Paso 9 
    Se procede a verificar la conexión entre la base de datos y el archivo de configuración estando en la consola de laravel
    Se ejecuta el siguiente comando:
    ~~~
    DB::connection()->getPdo()
    ~~~
    Si todo es correcto saldrá lo siguiente
    ~~~
    => PDO {#2960
        inTransaction: false,
        attributes: {
        CASE: NATURAL,
        ERRMODE: EXCEPTION,
        PERSISTENT: false,
        DRIVER_NAME: "pgsql",
        SERVER_INFO: "PID: 984; Client Encoding: UTF8; Is Superuser: on; Session Authorization: postgres; Date Style: ISO, MDY",
        ORACLE_NULLS: NATURAL,
        CLIENT_VERSION: "11.5",
        SERVER_VERSION: "11.4 (Debian 11.4-1.pgdg90+1)",
        STATEMENT_CLASS: [
            "PDOStatement",
        ],
        EMULATE_PREPARES: false,
        CONNECTION_STATUS: "Connection OK; waiting to send.",
        DEFAULT_FETCH_MODE: BOTH,
        },
    }
    ~~~

- ## Paso 10 
    sal del bash de tinker ejecutando:
    ~~~
    exit
    ~~~

- ## Paso 11 
    Ejecute el siguiente comando para migrar las tablas a la base de datos 
    ~~~
    php artisan migrate
    ~~~

y listo finalmente se ha instaldo la api y la base de datos, para conocer los endpoints de Travho visite la **[Documentación](https://app.swaggerhub.com/apis-docs/GarciaAlejandro/Travelers/1.0.0#/ ).**

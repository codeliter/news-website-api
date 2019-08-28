# news-website-api

## Project setup
- Install Packages
```
composer install
```

- Setup configuration in the `.env` file found in the project root.
    - `DB_CONNECTION`:  The database connection e.g mysql
    - `DB_HOST`: The database host e.g 127.0.0.1
    - `DB_PORT`: The database port e.g 3306
    - `DB_DATABASE`: The database name
    - `DB_USERNAME`: The database user
    - `DB_PASSWORD`: The database password

You can duplicate the .env.example and rename it to .env to make things faster.


- Provision the Application
```
composer provision
```


### Run
```
composer run serve
```

The API is mounted on port **8030** by default.

The port can be changed in the composer.json file in the `scripts` property in the JSON.


If the port is changed for any reason; the API base URL must be updated to use the same port in the Vue front-end APP.
The API URL can be found int  

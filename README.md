# Inventor

Storage managment app.

# Configuration

*app/config/parameters.yml*
* standard database parameters
* **database_name_test** - name for testing database
* **photo_path** - path to save photos (with chmod 775)
* **siteTitle** - ...

# Installation

```sh
$ php composer.phar install
$ php bin/console doctrine:migrations:migrate
$ php bin/console assets:install --symlink
```

First migration will add example categories to **category** table.
You can not manage it on site **admin/category**. 

# Running
## Create user from command line
Run command
```sh
$ php bin/console app:add-user --email email@email.com --password someSuperHardPassword

```
*email* - your email
*someSuperHardPassword* - password

## Run
Run website and use login form with selected credentials.

You must change your password on first run.
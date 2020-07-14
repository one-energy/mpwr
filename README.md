## One Energy

| QA | Stage | Production  |
|---|---|---|
| ![Develop][develop] | ![Develop][develop] | ![Master][master] |

### Setting Up

To collaborate you will need accounts for the following tools:

 * [Flare][flare] error tracking tool
 * [Laravel Envoyer][laravel-envoyer] deployment tool
 * [Mailtrap][mailtrap] fake email delivery system
 * [ChipperCI][chipper-ci] Continuous Integration (CI) tool

#### <TODO>

#### Laravel Envoyer
 
 We will use Laravel Envoyer to auto deploy new code into our QA environment available at [here][qa-environment].
 I'ts important to mention that a new version will be published after a successful build on [ChipperCI][chipper-ci] and
 that our database will be refreshed (and reseeded) after each build.
 
 Chances are that you have already been invited to the project QA environment configuration. You can check the pending
 invitations [here](https://envoyer.io/user/profile#/invitations).
 
 The project was named: **[<TODO>] QA**
 
#### Mailtrap

 With the default Laravel setup you can configure your mailing configuration by setting these values in the .env file 
 in the root directory of your project. The snippet bellow is and example from the CI environment configuration.

 ```
  MAIL_DRIVER=smtp
  MAIL_HOST=smtp.mailtrap.io
  MAIL_PORT=2525
  MAIL_USERNAME=<TODO>
  MAIL_PASSWORD=<TODO>
  MAIL_FROM_ADDRESS=team+ci@<TODO>
  MAIL_FROM_NAME="[CI] <TODO> Team"
  ```

### Before Committing

#### PHP Mess Detector 

This will validate the code against a set of code standard rules.

```bash
 ./vendor/bin/phpmd app text ./phpmd.xml
```

#### PHP CS Fixer

This will check if the code is obeying the Code Style

```bash
 ./vendor/bin/php-cs-fixer fix --dry-run --using-cache=no --verbose --stop-on-violation
```

This will fix your Code Style

```bash
 ./vendor/bin/php-cs-fixer fix
```

Alternatively, you can run `composer format`.

#### PHP Code Sniffer

```bash
./vendor/bin/phpcs --standard=phpcs.xml
```

#### PHPUnit

```bash
./vendor/bin/phpunit
```

#### ESLint

````bash
eslint --ext .js,.vue resources/js
````

 You might try to use ESLint to automatically fix the problems by running:

````bash
eslint --ext .js,.vue resources/js --fix
````
 
 Keep in mind that some of the problems will require manual fix.

## Docker

### What is inside? ###

* Redis
* MySQL
* Nginx
* PHP 7.4
* Composer
* Nodejs
* NPM
* Artisan   

### How do I get set up? ###

#### Summary of set up ####

* Clone this repository
* Run `docker-compose` to the environment up and running

#### Dependencies ####

* Docker
* Docker-compose

#### Configuration ####

```bash
chmod -R 755 .
chmod -R 757 ./storage
```

Create shell aliases:

```bash
alias dcomposer="docker-compose run --rm composer"
alias dcnpm="docker-compose run --rm npm"
alias dcartisan="docker-compose run --rm artisan"
```

Create shell variable:

```bash
export CURRENT_UID=$(id -u):$(id -g)
```

VSCode debug launch.json:

```json
{
    "name": "Listen for XDebug",
    "type": "php",
    "request": "launch",
    "port": 9001,
    "pathMappings": {
        "/var/www": "${workspaceFolder}"
    },
    "ignore": [
        "**/vendor/**/*.php"
    ]
}
```

Install [hostess](https://github.com/cbednarski/hostess) to help manage local hosts file

```bash
for d in * ; do sudo hostess add $d.local 127.0.0.1 ; done
```

___
[master]: https://app.chipperci.com/projects/23c7db80-64a0-4c0d-ad3c-0b274a888129/status/master
[develop]: https://app.chipperci.com/projects/23c7db80-64a0-4c0d-ad3c-0b274a888129/status/develop
[flare]: https://flareapp.io/
[laravel-envoyer]: https://envoyer.io
[mailtrap]: https://mailtrap.io
[qa-environment]: https://<TODO>.devsquadstage.com
[stage-environment]: https://<TODO>.com
[production-environment]: https://<TODO>.com
[chipper-ci]: https://chipperci.com

# Information Documents

A [Docker](https://www.docker.com/) based [Symfony 3.4 (LTS)](https://symfony.com) web console application for document evaluation

## Environment setup

First clone this repository by using `git clone https://github.com/devawal/document-evaluation.git`
And run `composer install`

## Docker command

1. Docker environment `PHP 7.2` and `NGINX 1.15`
2. Run `docker-compose up -d` to install all the dependency and then run `docker-compose up` command to start the container
3. Console command for docker container `docker-compose exec -T app php bin/console identification-requests:process input.csv`
4. Unit test for docker container `docker-compose exec -T app ./vendor/bin/phpunit`
5. Run `docker-compose run --rm app chown -R $(id -u):$(id -g) .` command for setting up permission


## Third party dependencies

Only PHP Unit is used for unit testing. For temporary data store I used symfony `cache`

# Document evaluation

Console command `php bin\console identification-requests:process input.csv`

## Input data

There is CSV file(input.csv) stored in project web resource directory, the file data structure is collected from the requirment. Different input data set can be tested by replacing the existing `input.csv` with the new one.

Following is the sample input and output

```
➜  php bin/console identification-requests:process input.csv
valid
valid
valid
document_number_length_invalid
request_limit_exceeded
valid
document_is_expired
valid
document_type_invalid
valid
valid
document_number_invalid
valid
document_issue_date_invalid
```

# Testing

The application is tested with different data set. For Unit test following is the command `./vendor/bin/phpunit`

Test class `Tests\AppBundle\CommandDocumentCommandTest`

# Test output from windows
```
$ ./vendor/bin/phpunit
PHPUnit 5.7.27 by Sebastian Bergmann and contributors.

..                                                                  2 / 2 (100%)

Time: 284 ms, Memory: 12.00MB

OK (2 tests, 3 assertions)
```

# Test output from docker container
```
$ docker-compose exec -T app ./vendor/bin/phpunit
PHPUnit 5.7.27 by Sebastian Bergmann and contributors.

..                                                                  2 / 2 (100%)

Time: 1.36 seconds, Memory: 16.00MB

OK (2 tests, 3 assertions)
```
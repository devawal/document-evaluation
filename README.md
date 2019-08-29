# Information Documents

A [Docker](https://www.docker.com/) based [Symfony 3.4 (LTS)](https://symfony.com) web console application for document evaluation

## Environment setup

First I download the symfony 3.4 LTS version using composer command and then I configure docker file for the container

## Docker command

1. Docker environment `PHP 7.2` and `NGINX 1.15`
1. Run `docker-compose up -d` to install all the dependency and then run `docker-compose up` command to start the container
2. Console command for docker container `docker-compose exec -T app php bin/console identification-requests:process input.csv`
3. Run `docker-compose run --rm app chown -R $(id -u):$(id -g) .` command for setting up permission


## Third party dependencies

Only PHP Unit used for unit testing. For temporary data store I used symfony `session`

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

The application is tested with different data set. For Unit test following is the command `.\vendor\bin\phpunit`

There is an issue in Unit test related to session, I am currently working on to fixed it
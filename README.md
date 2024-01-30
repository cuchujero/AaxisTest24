# KOOMBEA Test

A project with a crud of products 


## Author

Jerónimo Sola


## Description

in this website an user with a valid token can make crud operations for products.

## Running instructions

**composer update** 
**composer install** &nbsp; --> install dependencies
**symfony serve** &nbsp; --> run locally

## env file

###> symfony/framework-bundle ###
APP_ENV=dev
APP_SECRET=ee6ca51265edc9160f940db037f5f91f
###< symfony/framework-bundle ###

###> doctrine/doctrine-bundle ###
DATABASE_URL=postgresql://cuchujero:Goku123!@postgresql-cuchujero.alwaysdata.net:5432/cuchujero_test
###< doctrine/doctrine-bundle ###

## Endpoints

http://localhost:8000/index.php/v1/test (GET)
http://localhost:8000/index.php/v1/products (GET)
http://localhost:8000/index.php/v1/products (POST)
http://localhost:8000/index.php/v1/products (PUT)

## Documentation 

Api documentation
https://documenter.getpostman.com/view/13819778/2s9YysE2UF

token for test: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c

## Future upgrades

Frontend framework work for register, login of user

Frontend framework work for the operations with the products

Do a process that make change the token of an user
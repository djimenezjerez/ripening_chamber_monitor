# DOCKERIZING THE PROJECT

## Requirements

* [Docker](https://docs.docker.com/install/)
* [Docker Compose](https://docs.docker.com/compose/install/)
* Internet connection

## Configuration

* Within the root project's path, clone laradock submodule

```sh
git submodule update --init --recursive
```

* Copy the modified files to laradock folder

```sh
cp -rf docs/docker/node laradock/
cp -f docs/docker/mosquitto/mosquitto.conf laradock/mosquitto/
cp -f docs/docker/docker-compose.yml laradock/
cp -f docs/docker/env-example laradock/.env
```

* Move to laradock's path

```sh
cd laradock
```

* Modify `.env` file with desired data

* Up the compose with nginx server

```sh
docker-compose up -d --build mosquitto nginx pgadmin php-fpm postgres workspace node
```

* Instead you can use apache2 server

```sh
docker-compose up -d --build mosquitto apache2 pgadmin php-fpm postgres workspace node
```

* To tail the logs

```sh
docker-compose logs --follow
```

## Install project dependencies

* Within the laradock's path verify which containers are running

```sh
docker-compose ps
```

* Within the container called `workspace` you need to run

```sh
docker-compose exec workspace composer run-script post-root-package-install
```

```sh
docker-compose exec workspace composer install
```

* Generate laravel's session and jwt auth keys

```sh
docker-compose exec workspace composer run-script post-create-project-cmd
```

* Modify `.env` file according to the right credentials

* To change the application to development mode you need to run

```sh
docker-compose exec workspace yarn dev
```

## Issues

* If you have error console output you can verify `exited` containers

```sh
docker ps -a
```

* And finally erase all unused containers, builded images, unused networks and volumes

```sh
docker container prune
docker image prune -a
docker network prune
docker volume prune
```

* Then you can build images and recreate containers from scratch
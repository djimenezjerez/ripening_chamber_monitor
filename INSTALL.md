# RIPENING CHAMBER MONITOR

## Requirements

* PHP 7.3 (with `GD, PGSQL, PDO_PGSQL, MBSTRING, XML, ZIP` modules enabled)
* Node.js 10.16.0
* NPM 6.9.0 or Yarn 1.17.3
* PostgreSQL 11.4

## Install

* Roboto fonts support (Based on Ubuntu 16.04 distro)

```sh
sudo apt update
sudo apt install fonts-roboto fonts-roboto-fontface unzip fontconfig
cd /tmp
wget -O RobotoMono.zip https://fonts.google.com/download\?family\=Roboto%20Mono
sudo mkdir /usr/share/fonts/googlefonts
sudo unzip -d /usr/share/fonts/googlefonts /tmp/RobotoMono.zip
sudo chmod -R --reference=/usr/share/fonts/googlefonts /usr/share/fonts/googlefonts
sudo fc-cache -fv
fc-match "Roboto Mono"
```

* Clone the project

```sh
git clone https://github.com/djimenezjerez/ripening_chamber_monitor.git
cd ripening_chamber_monitor
git fetch --tags
latestVersion=$(git describe --tags `git rev-list --tags --max-count=1`)
git checkout $latestVersion
```

* Install dependencies

```sh
composer run-script post-root-package-install
composer install
```

* Edit `.env` file with database credentials and established manteinance modes

* Generate keys and compile JS files

```sh
composer run-script post-create-project-cmd
yarn prod
```

## Development

* To setup the admin user

```sh
php artisan db:seed --class=DatabaseProductionSeeder
```

* To generate the documentation

```sh
php artisan api:generate --output="public/docs" --routePrefix="api/*" --actAsUserId=1
```

* To view the documentation unput in your web browser URL: [http://server:port/docs/](http://localhost:8888/docs/)
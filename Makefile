.PHONY: all

all: runserver

install: migrations
	@echo Installed.

composer.phar:
	curl -s https://getcomposer.org/installer | php
	touch composer.phar

vendor: composer.phar
	./composer.phar install
	touch vendor

migrations: vendor
	app/console doctrine:migrations:migrate --no-interaction

clearcache:
	sudo rm -rf app/cache/* app/logs/*

update: clearcache migrations

fetch:
	git fetch
	git merge origin/master master

deploy: fetch update

runserver: vendor
	app/console server:run

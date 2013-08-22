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

runserver: vendor
	app/console server:run

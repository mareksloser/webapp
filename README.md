# webapp
Web application project based on Nette Framework (@nette), Doctrine (@nettrine) and Contributte (@contributte)


# CMDs

* docker-compose -p webapp up --build
* vendor/bin/phpstan analyse -c phpstan.neon --memory-limit=512M
* vendor/bin/codesniffer app tests
* vendor/bin/codefixer app tests

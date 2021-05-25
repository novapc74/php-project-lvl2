install:
	composer install

validate:
	composer validate

lint:
	composer run-script phpcs -- --standard=PSR12 src bin
	
lint-fix:
	composer run-script phpcbf -- --standard=PSR12 src bin

test:
	composer phpunit tests

test-coverage:
	composer phpunit tests -- --coverage-clover build/logs/clover.xml

install:
	composer install

validate:
	composer validate

lint:
	composer run-script phpcs -- --standard=PSR12 src bin
	
lint-fix:
	composer run-script phpcbf -- --standard=PSR12 src bin	
	
test:
	composer exec --verbose phpunit tests -- --coverage-text

test-coverage:
	composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml
	
test-html:
	composer exec --verbose phpunit tests -- --coverage-html coverage

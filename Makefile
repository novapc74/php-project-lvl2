install:
	composer install

validate:
	composer validate

lint:
	composer run-script phpcs -- --standard=PSR12 src bin
	
lint-fix:
	composer run-script phpcbf -- --standard=PSR12 src bin
	
test:
	composer exec --verbose phpunit tests

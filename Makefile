#!make

# TESTS
test:
	composer run cs
	composer run analyse
	composer run test

analyse:
	composer run analyse

pint:
	composer run format

coverage:
	./vendor/bin/phpunit --coverage-html code_coverage

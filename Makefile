#!make

# TESTS
test:
	composer run cs
	composer run analyse
	composer run test

ci:
	composer run format
	composer run phpstan
	composer run test

phpstan:
	composer run phpstan

pint:
	composer run format

coverage:
	./vendor/bin/phpunit --coverage-html code_coverage

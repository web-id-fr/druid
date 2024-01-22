#!make

# TESTS
test:
	composer run phpstan
	composer run pint
	./vendor/bin/phpunit --no-coverage

stan:
	composer run phpstan

pint:
	composer run pint

coverage:
	./vendor/bin/phpunit --coverage-html code_coverage

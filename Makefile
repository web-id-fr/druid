#!make

# TESTS
test:
	composer run analyse
	composer run format
	composer run test-coverage

stan:
	composer run phpstan

pint:
	composer run pint

coverage:
	./vendor/bin/phpunit --coverage-html code_coverage

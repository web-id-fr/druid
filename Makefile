#!make

# TESTS
test:
	composer run analyse
	composer run format
	composer run test-coverage

analyse:
	composer run analyse

pint:
	composer run pint

coverage:
	./vendor/bin/phpunit --coverage-html code_coverage

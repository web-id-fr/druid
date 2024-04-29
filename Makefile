#!make

# TESTS
test:
	composer run format
	composer run analyse
	composer run test

analyse:
	composer run analyse

coverage:
	./vendor/bin/phpunit --coverage-html code_coverage

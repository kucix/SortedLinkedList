.DEFAULT_GOAL = without_targets

# defaultni target pri zavolani make bez specifikovaneho targetu
without_targets:
	@echo Nothing to do, specify target name

tests.all: tests.cs tests.phpstan tests.unit

tests.cs:
	docker compose run php ./vendor/bin/phpcs

tests.phpstan:
	docker compose run php ./vendor/bin/phpstan

tests.unit:
	docker compose run php ./vendor/bin/phpunit tests

fix.cs:
	docker compose run php ./vendor/bin/phpcs


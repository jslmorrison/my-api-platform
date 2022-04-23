# Info

An example Symfony project using the API Platform to expose some resources by implementing the DTO pattern to manually define the input/output structure of resource.

# Usage

**N.B** This makes use of an external network - remove it from the docker-compose file if not required.

Bring the containers up with ```docker-compose up [-d]```

Create the db and schema:

```docker-compose exec app bin/console doctrine:database:create```

```docker-compose exec app bin/console doc:sch:up```

Run the migration(s) then load the fixtures.

```docker-compose exec app bin/console doctrine:mig:mig```

```docker-compose exec app bin/console doc:fix:load```

# Tests

Create the test db and schema:

```docker-compose exec app bin/console doctrine:database:create --env=test```

```docker-compose exec app bin/console doc:sch:up --force --env=test```

Run tests:

```docker-compose exec app bash -c "vendor/bin/phpspec run && bin/phpunit"```
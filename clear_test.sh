symfony console doctrine:database:drop --force --if-exists --env=test
symfony console doctrine:database:create --env=test
symfony console doctrine:migrations:migrate --env=test
symfony console doctrine:fixtures:load --env=test

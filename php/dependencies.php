<?php

use SofiB\Business\Entity\Root;
use SofiB\Infra\IoC\Container;
use SofiB\Infra\Entity\MySql\Loader;
use SofiB\Infra\Entity\MySql\LoaderCollection;
use SofiB\Infra\Entity\MySql\Migrations;
use SofiB\Infra\Entity\MySql\Persister;

$dbConfig = require(__DIR__ . '/db.config.php');
$pdo = new PDO($dbConfig->dsn, $dbConfig->username, $dbConfig->password, $dbConfig->options);

Migrations::run($pdo);

$dependencyProvider = new Container();

$dependencyProvider->register(Root::class, function () use ($pdo) : Root {
  return new Root(
    new Loader($pdo),
    new LoaderCollection($pdo),
    new Persister($pdo)
  );
});

return $dependencyProvider;

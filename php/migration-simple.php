<?php

namespace SofiB\Infra\Entity\MySql;

class Migrations
{
  public static function run(\PDO $pdo): void
  {
    $sqls = [
      static::init()
    ];
    foreach($sqls as $sql) {
      $pdo->exec($sql);
    }
  }

  private static function init () : string
  {
    return <<<SQL
    /*!40101 CREATE DATABASE IF NOT EXISTS some_db_name; use some_db_name; */

    CREATE TABLE entity(
      id INTEGER NOT NULL PRIMARY KEY /*!40101 AUTO_INCREMENT */,
      name VARCHAR(255) NOT NULL
    ) /*!40101 engine=InnoDB */;
    SQL;
  }
}

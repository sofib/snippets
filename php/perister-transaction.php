<?php

namespace SofiB\Infra\Entity\MySql;

use SofiB\Domain\Entity\Persistable;
use SofiB\Domain\Entity\Entity\Id;
use SofiB\Domain\Entity\Entity;

class Persister implements Persistable
{
  private $pdo;

  private static $sqlIns = 'INSERT INTO Entity (name, createdAt) VALUES (:name, :create)';
  private static $sqlDel = 'DELETE FROM Entity WHERE id = ?';

  public function __construct(\PDO $pdo)
  {
    $this->pdo = $pdo;
  }

  public function save (Entity $entity, Id $id = null) : Id
  {
    $this->pdo->beginTransaction();
    try {
      if ($id !== null) {
        $statement = $this->pdo->prepare(static::$sqlDel);
        $statement->execute([$id->getValue()]);
      }

      $statement = $this->pdo->prepare(static::$sqlIns);
      $statement->execute([
        'name' => $entity->getName(),
        'create' => $entity->getCreatedAt()->format(\DateTime::ISO8601),
      ]);

      $id = $this->pdo->lastInsertId();
  
      $this->pdo->commit();

      return new MySqlEntityId((int) $id);

    } catch (\PDOException $exception) {
      $this->pdo->rollBack();
      throw $exception;
    }
  }
}

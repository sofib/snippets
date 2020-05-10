<?php

namespace SofiB\Infra\Entity\MySql;

use PHPUnit\Framework\TestCase;
use SofiB\Business\Entity\Root;

class PersisterTest extends TestCase
{
  private $connection;

  /**
   * @var Persister $subjectUnderTest
   */
  private $subjectUnderTest;

  public function setUp () : void
  {
    $this->connection = new \PDO('sqlite::memory:');
    Migrations::run($this->connection);
    $this->subjectUnderTest = new Persister($this->connection);
  }

  public function testEntityIsSaved()
  {
    $builder = Root::builder()
      ->withName('Test entry')
      ->withCreateTime(new \DateTime());
    $id = $this->subjectUnderTest->save($builder->build());
    $loader = new Loader($this->connection);
    $entity = $loader->load($id);
    static::assertSame('Test entry', $entity->getTitle());
  }
}
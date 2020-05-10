<?php

namespace SofiB\Infra\IoC;

use SofiB\Common\IoC\DependencyResolver;

class Container implements DependencyResolver
{
  private $factories = [];

  public function register(string $type, callable $handler) : void
  {
    $this->factories[$type] = $handler;
  }

  public function resolve(string $type)
  {
    $factory = $this->factories[$type] ?? null;
    if ($factory === null) return null;

    return call_user_func($factory);
  }
}

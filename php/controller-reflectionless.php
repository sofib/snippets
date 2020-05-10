<?php

namespace SofiB\Api\Entity;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SofiB\Common\IoC\DependencyResolver;
use SofiB\Business\Entity\Root;

class Controller
{
  private $resolver;
  
  public function __construct(DependencyResolver $resolver)
  {
    $this->resolver = $resolver;
  }

  public function add(ServerRequestInterface $request) : ResponseInterface
  {
    $body = (object)$request->getParsedBody() ?? null;
    if ($body === null) return (new Response())->withStatus(400);

    /** @var Root $root */  
    $root = $this->resolver->resolve(Root::class);
    $saved = $root->save(
      Root::builder()
        ->withName($body->name)
        ->build()
    );
      
    return (new JsonResponse((object)['id'=> $saved->id->getValue()]))->withStatus(201);
  }

  public function update(ServerRequestInterface $request) : ResponseInterface
  {
    $body = $request->getParsedBody();
    if ($body === null) return (new Response())->withStatus(400);

    /** @var Root $root */  
    $root = $this->resolver->resolve(Root::class);
    $root->save(
      Root::builder()
        ->withId($body->id)
        ->withName($body->name)
        ->build()
    );
      
    return (new EmptyResponse())->withStatus(204);
  }
}
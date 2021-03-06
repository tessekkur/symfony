<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Tests\Component\HttpKernel\Debug;

use Symfony\Component\HttpKernel\Debug\ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testDebug()
    {
        $handler = new ExceptionHandler(false);
        $response = $handler->createResponse(new \RuntimeException('Foo'));

        $this->assertContains('<h1>Whoops, looks like something went wrong.</h1>', $response->getContent());
        $this->assertNotContains('<div class="block_exception clear_fix">', $response->getContent());

        $handler = new ExceptionHandler(true);
        $response = $handler->createResponse(new \RuntimeException('Foo'));

        $this->assertContains('<h1>Whoops, looks like something went wrong.</h1>', $response->getContent());
        $this->assertContains('<div class="block_exception clear_fix">', $response->getContent());
    }

    public function testStatusCode()
    {
        $handler = new ExceptionHandler(false);

        $response = $handler->createResponse(new \RuntimeException('Foo'));
        $this->assertEquals('500', $response->getStatusCode());
        $this->assertContains('<title>Whoops, looks like something went wrong.</title>', $response->getContent());

        $response = $handler->createResponse(new NotFoundHttpException('Foo'));
        $this->assertEquals('404', $response->getStatusCode());
        $this->assertContains('<title>Sorry, the page you are looking for could not be found.</title>', $response->getContent());
    }

    public function testNestedExceptions()
    {
        $handler = new ExceptionHandler(true);
        $response = $handler->createResponse(new \RuntimeException('Foo', null, new \RuntimeException('Bar')));
    }
}

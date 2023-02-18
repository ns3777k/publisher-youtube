<?php

declare(strict_types=1);

namespace App\Tests\Listener;

use App\Listener\JwtCreatedListener;
use App\Tests\AbstractTestCase;
use App\Tests\MockUtils;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JwtCreatedListenerTest extends AbstractTestCase
{
    public function testInvoke(): void
    {
        $user = MockUtils::createUser();
        $this->setEntityId($user, 123);

        $listener = new JwtCreatedListener();
        $event = new JWTCreatedEvent(['flag' => true], $user, []);

        $listener($event);

        $this->assertEquals(['flag' => true, 'id' => 123], $event->getData());
    }
}

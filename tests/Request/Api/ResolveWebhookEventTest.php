<?php

namespace Tests\FluxSE\PayumStripe\Request\Api;

use Payum\Core\Model\Token;
use Payum\Core\Request\Convert;
use PHPUnit\Framework\TestCase;
use FluxSE\PayumStripe\Request\Api\ResolveWebhookEvent;

final class ResolveWebhookEventTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeSubClassOfConvert()
    {
        $resolveWebhookEvent = new ResolveWebhookEvent(new Token());

        $this->assertInstanceOf(Convert::class, $resolveWebhookEvent);
    }
}

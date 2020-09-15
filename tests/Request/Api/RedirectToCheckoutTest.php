<?php

namespace Tests\FluxSE\PayumStripe\Request\Api;

use Payum\Core\Request\Generic;
use PHPUnit\Framework\TestCase;
use FluxSE\PayumStripe\Request\Api\RedirectToCheckout;

final class RedirectToCheckoutTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeSubClassOfGeneric()
    {
        $redirectToCheckout = new RedirectToCheckout([]);

        $this->assertInstanceOf(Generic::class, $redirectToCheckout);
    }
}

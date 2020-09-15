<?php

namespace Tests\FluxSE\PayumStripe\Action\Api\WebhookEvent;

use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\Model\Token;
use Payum\Core\Request\GetToken;
use Payum\Core\Request\Notify;
use PHPUnit\Framework\TestCase;
use FluxSE\PayumStripe\Action\Api\WebhookEvent\AbstractPaymentAction;
use FluxSE\PayumStripe\Action\Api\WebhookEvent\AbstractWebhookEventAction;
use FluxSE\PayumStripe\Action\Api\WebhookEvent\SetupIntentCanceledAction;
use FluxSE\PayumStripe\Request\Api\WebhookEvent\WebhookEvent;
use FluxSE\PayumStripe\Wrapper\EventWrapper;
use Stripe\Event;
use Tests\FluxSE\PayumStripe\Action\GatewayAwareTestTrait;

final class SetupIntentCanceledActionTest extends TestCase
{
    use GatewayAwareTestTrait;

    /**
     * @test
     */
    public function shouldImplements()
    {
        $action = new SetupIntentCanceledAction();

        $this->assertNotInstanceOf(ApiAwareInterface::class, $action);
        $this->assertInstanceOf(ActionInterface::class, $action);
        $this->assertInstanceOf(GatewayAwareInterface::class, $action);

        $this->assertInstanceOf(AbstractPaymentAction::class, $action);
        $this->assertInstanceOf(AbstractWebhookEventAction::class, $action);
    }

    /**
     * @test
     */
    public function shouldConsumeAWebhookEvent()
    {
        $model = [
            'id' => 'event_1',
            'data' => [
                'object' => [
                    'metadata' => [
                        'token_hash' => 'test_hash',
                    ],
                ],
            ],
            'type' => Event::SETUP_INTENT_CANCELED,
        ];

        $event = Event::constructFrom($model);
        $token = new Token();

        $gatewayMock = $this->createGatewayMock();
        $gatewayMock
            ->expects($this->exactly(2))
            ->method('execute')
            ->withConsecutive(
                [$this->isInstanceOf(GetToken::class)],
                [$this->isInstanceOf(Notify::class)]
            )
            ->willReturnOnConsecutiveCalls(
                $this->returnCallback(function (GetToken $request) use ($token) {
                    $this->assertEquals('test_hash', $request->getHash());
                    $request->setToken($token);
                }),
                $this->returnCallback(function (Notify $request) use ($token) {
                    $this->assertEquals($token, $request->getToken());
                })
            );

        $action = new SetupIntentCanceledAction();
        $action->setGateway($gatewayMock);
        $eventWrapper = new EventWrapper('', $event);
        $webhookEvent = new WebhookEvent($eventWrapper);

        $action->execute($webhookEvent);
    }
}

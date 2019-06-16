<?php

declare(strict_types = 1);

namespace Larium\Model;

use AktiveMerchant\Billing\CreditCard;
use AktiveMerchant\Billing\Gateways\Bogus;
use AktiveMerchant\Billing\Base;
use Larium\Event\EventHandler;
use Larium\Listener\PaymentListener;
use Larium\Model\Method\PaymentMethod;
use Larium\Model\Method\CreditCardMethod;
use Larium\Model\Account\Account;
use Money\Money;
use PHPUnit\Framework\TestCase;

class PaymentTest extends TestCase
{
    private $payment;

    public function setUp(): void
    {
        $this->payment = new Payment();
    }

    public function testNewPaymentShouldHavePendingState()
    {
        $this->assertEquals(Payment::PENDING, $this->payment->getState());
    }

    public function testNewPaymentShouldHaveAReferenceId()
    {
        $this->assertNotNull($this->payment->getReferenceId());
    }

    public function testPaymentShouldHaveUniqueReferenceId()
    {
        $ids = [];

        for ($i = 0; $i < 1000; $i++) {
            $p = new Payment();
            $this->assertFalse(in_array($p->getReferenceId(), $ids));
            $ids[] = $p->getReferenceId();
        }
    }

    public function testPaymentShouldNotBePaidWithoutAnAmount()
    {
        $this->expectException(
            'Larium\Exception\RequiredAmountException',
            'Payment amount is required.'
        );

        $this->payment->pay($this->getPaymentMethod());
    }

    public function testPaidPaymentShouldHavePaidState()
    {
        $this->payment->setAmount(Money::EUR(100));
        $this->payment->pay($this->getPaymentMethod());

        $this->assertSuccessPayment();
    }

    public function testFailedPayment()
    {
        $this->payment->setAmount(Money::EUR(100));

        $this->payment->pay($this->getFailedResponseMethod());

        $this->assertFailedPayment();
    }

    public function testShouldHaveATransactionIdAfterSuccessPay()
    {
        $this->payment->setAmount(Money::EUR(100));

        $this->payment->pay($this->getPaymentMethod());

        $this->assertNotNull($this->payment->getTransactionId());
    }

    public function testShouldPayWithCreditCardMethod()
    {
        $this->payment->setAmount(Money::EUR(100));

        $this->payment->pay($this->getCreditCardMethod());

        $this->assertSuccessPayment();
    }

    public function testShouldFailWithCreditCardMethod()
    {
        $this->payment->setAmount(Money::EUR(100));

        $this->payment->pay($this->getCreditCardMethod('failed'));

        $this->assertFailedPayment();
    }

    public function testShouldHandleCreditCardMethodException()
    {
        $this->expectException('Larium\Exception\GatewayException');

        $this->payment->setAmount(Money::EUR(100));
        $this->payment->pay($this->getCreditCardMethod('exception'));
    }

    public function testPaymentEvents()
    {
        $this->payment->setAmount(Money::EUR(100));

        $this->payment->pay($this->getCreditCardMethod());
        $listener = new PaymentListener(new Account('merchant'), new Account('buyer'));

        $eventHandler = new EventHandler(
            $listener,
            $this->payment->popEvents()
        );

        $eventHandler->handle();

        $this->assertTrue($listener->isCaptured());
    }

    private function getPaymentMethod()
    {
        return new PaymentMethod();
    }

    private function getCreditCardMethod($status = 'success', $gateway = 'bogus', array $options = array())
    {
        return new CreditCardMethod($this->getCreditCard($status), $this->getGateway($gateway, $options));
    }

    private function getCreditCard($status)
    {
        switch ($status) {
            case 'success':
                $number = 1;
                break;
            case 'failed':
                $number = '4111111111111111';
                break;
            default:
                $number = 2;
                break;
        }

        return new CreditCard([
            "first_name" => "John",
            "last_name"  => "Doe",
            "number"     => $number,
            "month"      => "01",
            "year"       => date('Y') + 1,
            "verification_value" => "000"
        ]);
    }

    private function getGateway($gateway, array $options = array())
    {
        return Base::gateway($gateway, $options);
    }

    private function getFailedResponseMethod()
    {
        $method = $this->getMockBuilder('Larium\Model\Method\PaymentMethod')
            ->setMethods(array('pay'))
            ->getMock();
        $method->expects($this->once())
            ->method('pay')
            ->will($this->returnValue(new Response(false)));

        return $method;
    }

    private function assertSuccessPayment()
    {
        $this->assertEquals(Payment::PAID, $this->payment->getState());
        $this->assertNotNull($this->payment->getTransactionId());
    }

    private function assertFailedPayment()
    {
        $this->assertEquals(Payment::FAILED, $this->payment->getState());
    }

    private function getFixtures()
    {
        $ini = parse_ini_file(__DIR__ . "/../fixtures.ini", true);
        return new \ArrayIterator($ini);
    }
}

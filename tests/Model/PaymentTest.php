<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Model;

use AktiveMerchant\Billing\CreditCard;
use AktiveMerchant\Billing\Gateways\Bogus;

class PaymentTest extends \PHPUnit_Framework_TestCase
{
    private $payment;

    public function setUp()
    {
        $this->payment = new Payment();
    }

    public function testNewPaymentShouldHavePendingState()
    {
        $this->assertEquals(Payment::PENDING, $this->payment->getState());
    }

    public function testPaymentShouldNotBePaidWithoutAnAmount()
    {
        $this->setExpectedException(
            'Larium\Exception\RequiredAmountException',
            'Payment amount is required.'
        );

        $this->payment->pay($this->getPaymentMethod());
    }

    public function testPaymentShouldNotBePaidWithoutAValidAmount()
    {
        $this->payment->setAmount('100 cents');

        $this->setExpectedException(
            'Larium\Exception\InvalidAmountException',
            'Payment amount must be an integer.'
        );

        $this->payment->pay($this->getPaymentMethod());
    }

    public function testPaidPaymentShouldHavePaidState()
    {
        $this->payment->setAmount(100);
        $this->payment->pay($this->getPaymentMethod());

        $this->assertSuccessPayment();
        $this->assertTrue(is_int($this->payment->getAmount()));
    }

    public function testFailedPayment()
    {
        $this->payment->setAmount(100);

        $this->payment->pay($this->getFailedResponseMethod());

        $this->assertFailedPayment();
    }

    public function testShouldHaveATransactionIdAfterSuccessPay()
    {
        $this->payment->setAmount(100);

        $this->payment->pay($this->getPaymentMethod());

        $this->assertNotNull($this->payment->getTransactionId());
    }

    public function testShouldPayWithCreditCardMethod()
    {
        $this->payment->setAmount(100);

        $this->payment->pay($this->getCreditCardMethod());

        $this->assertSuccessPayment();
    }

    public function testShouldFailWithCreditCardMethod()
    {
        $this->payment->setAmount(100);

        $this->payment->pay($this->getCreditCardMethod('failed'));

        $this->assertFailedPayment();
    }

    public function testShouldHandleCreditCardMethodException()
    {
        $this->setExpectedException('Larium\Exception\GatewayException');

        $this->payment->setAmount(100);
        $this->payment->pay($this->getCreditCardMethod('exception'));

    }

    private function getPaymentMethod()
    {
        return new PaymentMethod();
    }

    private function getCreditCardMethod($status = 'success')
    {
        return new CreditCardMethod($this->getCreditCard($status), $this->getGateway());
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

    private function getGateway()
    {
        return new Bogus();
    }

    private function getFailedResponseMethod()
    {
        $method = $this->getMockBuilder('Larium\Model\PaymentMethod')
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
    }

    private function assertFailedPayment()
    {
        $this->assertEquals(Payment::FAILED, $this->payment->getState());
    }
}

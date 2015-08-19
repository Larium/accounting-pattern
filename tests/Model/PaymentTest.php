<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Model;

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

        $this->assertEquals(Payment::PAID, $this->payment->getState());
        $this->assertTrue(is_int($this->payment->getAmount()));
    }

    public function testFailedPayment()
    {
        $this->payment->setAmount(100);

        $this->payment->pay($this->getFailedResponseMethod());

        $this->assertEquals(Payment::FAILED, $this->payment->getState());
    }

    public function testShouldHaveATransactionIdAfterSuccessPay()
    {
        $this->payment->setAmount(100);

        $this->payment->pay($this->getPaymentMethod());

        $this->assertNotNull($this->payment->getTransactionId());
    }

    public function getPaymentMethod()
    {
        return new PaymentMethod();
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
}

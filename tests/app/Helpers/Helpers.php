<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-06 19:32:45
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:14
 */
class calculateInterestTest extends TestCase
{
    public function test_calculate_interest()
    {
        $interest = calculateInterest(110, 30, 4);

        $this->assertEquals(114.40, $interest);
    }

    public function test_calculate_interest_second()
    {
        $interest = calculateInterest(159.45, 30, 6);

        $this->assertEquals(169.02, $interest);
    }

    public function test_payment_of_line()
    {
        $paymentOfline = calculatePaymentOfLine(110, 4);

        $this->assertEquals(114.4, $paymentOfline);
    }

    public function test_payment_of_line_second()
    {
        $paymentOfline = calculatePaymentOfLine(230, 3);

        $this->assertEquals(236.9, $paymentOfline);
    }
}

<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-04-24 06:50:43
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:59:50
 */
class ApiTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testVisitUrl()
    {
        $this->visit('/')
            ->see('Escola');
    }
}

<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-06 19:24:57
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:52:39
 */
function calculateInterest($value, $days, $percent)
{
    return round($value * (1 + ((($percent / 30) * $days) / 100)), 2);
}

function calculatePaymentOfLine($value, $percent)
{
    return round($value * (1 + ($percent / 100)), 2);
}

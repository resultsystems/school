<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-11 10:30:21
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:52:35
 */

namespace Student\Schedule;

class Schedule extends \Domain\Schedule\Schedule
{
    protected $visible = ['name', 'start', 'end'];
}

<?php

namespace Teacher\Schedule;

class Schedule extends \Domain\Schedule\Schedule
{
    protected $visible = ['name', 'start', 'end'];
}

<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-04-26 07:24:17
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:52:29
 */

namespace Student\Billet;

class Billet extends \Domain\Billet\Billet
{
    protected $fillable = ['new_due_date'];
}

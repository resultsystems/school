<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:40:48
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:33
 */

namespace Domain\Config;

use Domain\BaseModel;

class Config extends BaseModel
{
    protected $fillable = ['field', 'value'];
    protected $incrementKey = false;
}

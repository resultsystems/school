<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-11 10:34:06
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:44
 */

namespace Domain\Http\Controllers\Traits;

/**
 * Crud Trait.
 *
 * @author Leandro Henrique <emtudo@gmail.com>
 */
trait CrudTrait
{
    use GetTrait, AllTrait, StoreTrait, UpdateTrait, DeleteTrait;
}

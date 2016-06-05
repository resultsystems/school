<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-11 10:32:35
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:52:38
 */

namespace App\Exceptions;

use Exception;

/**
 * Repository Exception.
 *
 * @author Leandro Henrique <emtudo@gmail.com>
 */
class RepositoryException extends Exception
{
    protected $message = 'Error on CRUD';
    protected $code = 500;
}

<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-11 10:30:21
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:50:24
 */

namespace App\Http\Middleware;

use Closure;
use Domain\Teacher\Teacher as ModelTeacher;
use Illuminate\Support\Facades\Auth;

class Teacher
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $owner = Auth::user()->owner;

        if (!$owner instanceof ModelTeacher) {
            return response('Unauthorized.', 401);
        }

        return $next($request);
    }
}

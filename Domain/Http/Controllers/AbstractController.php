<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-11 10:36:18
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:43
 */

namespace Domain\Http\Controllers;

use Illuminate\Container\Container as App;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

abstract class AbstractController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Columns default to get and all.
     * @var array
     */
    protected $columns = ['*'];

    /**
     * With relationships.
     * @var array
     */
    protected $with = [];

    /**
     * Load relationships.
     * @var array
     */
    protected $load = [];

    /**
     * Container.
     * @var Container
     */
    protected $app;

    /**
     * Repository.
     * @var \Domain\Repositories\BaseRepository
     */
    protected $repo;

    /**
     * Constrcut.
     * @param App        $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->repo = $app->make($this->repo());
    }

    abstract public function repo();
}

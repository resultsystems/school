<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:41:44
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:09
 */

namespace Domain\Teacher;

use App;
use App\Exceptions\RepositoryException;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Pagination\LengthAwarePaginator;

class TeacherRepositoryTest extends \TestCase
{
    use DatabaseTransactions;

    public function test_store()
    {
        $repo = App::make(TeacherRepository::class);

        $store = $repo->store(['name' => 'oi']);

        $this->assertInstanceOf(Teacher::class, $store);
        $this->assertInstanceOf(Carbon::class, $store->created_at);
    }

    public function test_update()
    {
        $repo = App::make(TeacherRepository::class);

        $teacher = factory(Teacher::class)->create();

        $update = $repo->update(['name' => 'oi 2'], $teacher->id);

        $this->assertInstanceOf(Teacher::class, $update);
        $this->assertEquals($teacher->id, $update->id);
    }

    public function test_get()
    {
        $repo = App::make(TeacherRepository::class);

        $teacher = factory(Teacher::class)->create();

        $get = $repo->get($teacher->id);

        $this->assertInstanceOf(Teacher::class, $get);
        $this->assertEquals($get->id, $teacher->id);
    }

    public function test_all()
    {
        $repo = App::make(TeacherRepository::class);

        factory(Teacher::class)->create();

        $all = $repo->all();

        $this->assertInstanceOf(LengthAwarePaginator::class, $all);
        $this->assertInstanceOf(Teacher::class, $all->first());
    }

    public function test_delete()
    {
        $repo = App::make(TeacherRepository::class);

        $teacher = factory(Teacher::class)->create();

        $delete = $repo->delete($teacher->id);

        $trashed = $repo->onlyTrashed()->get($teacher->id);

        $this->assertEquals(1, $delete);
        $this->assertInstanceOf(Carbon::class, $trashed->deleted_at);
        $this->assertInstanceOf(Teacher::class, $trashed);

        $this->setExpectedException(RepositoryException::class);
        $repo->withoutTrashed()->get($teacher->id);
    }

    public function test_force_delete()
    {
        $repo = App::make(TeacherRepository::class);

        $teacher = factory(Teacher::class)->create();

        $repo->forceDelete($teacher->id);

        $this->setExpectedException(RepositoryException::class);
        $trashed = $repo->onlyTrashed()->get($teacher->id);
    }

    public function test_restore()
    {
        $repo = App::make(TeacherRepository::class);

        $teacher = factory(Teacher::class)->create();

        $repo->delete($teacher->id);

        $restore = $repo->restore($teacher->id);

        $get = $repo->get($teacher->id);

        $this->assertEquals(1, $restore);
        $this->assertNull($get->deleted_at);
        $this->assertInstanceOf(Teacher::class, $get);
    }
}

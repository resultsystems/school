<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:41:46
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:50:43
 */

namespace Domain\Billet;

use App;
use App\Exceptions\RepositoryException;
use Carbon\Carbon;
use DB;
use Domain\Student\Student;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Pagination\LengthAwarePaginator;

class BilletRepositoryTest extends \TestCase
{
    use DatabaseTransactions;

    public function test_store()
    {
        $billet = factory(Billet::class)->make();
        $assignor = factory(BilletAssignor::class)->create();
        $repo = App::make(BilletRepository::class);
        $student = factory(Student::class)->create();
        $data = $billet->toArray();
        $data['student_id'] = $student->id;
        $store = $repo->store($data);

        $this->assertInstanceOf(Billet::class, $store);
        $this->assertInstanceOf(Carbon::class, $store->created_at);

        $this->seeInDatabase('billets', [
            'id' => $store->id,
            'student_id' => $student->id,
        ]);
    }

    public function test_update()
    {
        $repo = App::make(BilletRepository::class);

        $billet = factory(Billet::class)->create();

        $update = $repo->update(['amount' => 152.15], $billet->id);

        $this->assertInstanceOf(Billet::class, $update);
        $this->assertEquals($billet->id, $update->id);
    }

    public function test_get()
    {
        $repo = App::make(BilletRepository::class);

        $billet = factory(Billet::class)->create();

        $get = $repo->get($billet->id);

        $this->assertInstanceOf(Billet::class, $get);
        $this->assertEquals($get->id, $billet->id);
    }

    public function test_all()
    {
        $repo = App::make(BilletRepository::class);

        factory(Billet::class)->create();

        $all = $repo->all();

        $this->assertInstanceOf(LengthAwarePaginator::class, $all);
        $this->assertInstanceOf(Billet::class, $all->first());
    }

    public function test_delete()
    {
        $repo = App::make(BilletRepository::class);

        $billet = factory(Billet::class)->create();

        $delete = $repo->delete($billet->id);

        $trashed = $repo->onlyTrashed()->get($billet->id);

        $this->assertEquals(1, $delete);
        $this->assertInstanceOf(Carbon::class, $trashed->deleted_at);
        $this->assertInstanceOf(Billet::class, $trashed);

        $this->setExpectedException(RepositoryException::class);
        $repo->withoutTrashed()->get($billet->id);
    }

    public function test_force_delete()
    {
        $repo = App::make(BilletRepository::class);

        $billet = factory(Billet::class)->create();

        $repo->forceDelete($billet->id);

        $this->setExpectedException(RepositoryException::class);
        $trashed = $repo->onlyTrashed()->get($billet->id);
    }

    public function test_restore()
    {
        $repo = App::make(BilletRepository::class);

        $billet = factory(Billet::class)->create();

        $repo->delete($billet->id);

        $restore = $repo->restore($billet->id);

        $get = $repo->get($billet->id);

        $this->assertEquals(1, $restore);
        $this->assertNull($get->deleted_at);
        $this->assertInstanceOf(Billet::class, $get);
    }

    public function test_defaulters()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::SELECT('DELETE FROM billets');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        factory(Billet::class, 5)->create([
            'due_date' => Carbon::yesterday(),
        ]);

        $repo = App::make(BilletRepository::class);
        $defaulters = $repo->defaulters();

        foreach ($defaulters as $billet) {
            $this->assertTrue($billet->due_date->isPast());
            $this->assertInstanceOf(Student::class, $billet->student);
        }
    }

    public function test_pay()
    {
        $today = Carbon::today();

        $billet = factory(Billet::class)->create();

        $repo = App::make(BilletRepository::class);
        $response = $repo->pay($billet->id, $today);

        $this->assertInstanceOf(Billet::class, $response);
        $this->assertInstanceOf(Carbon::class, $response->discharge_date);

        $this->seeInDatabase('billets', [
            'id' => $billet->id,
            'discharge_date' => $today->toDateString(),
        ]);
    }

    public function test_cant_twice_pay()
    {
        $today = Carbon::today();

        $billet = factory(Billet::class)->create([
            'discharge_date' => null,
        ]);

        $repo = App::make(BilletRepository::class);
        $response = $repo->pay($billet->id, $today);

        $this->setExpectedException(RepositoryException::class);
        $response = $repo->pay($billet->id, $today);
    }
}

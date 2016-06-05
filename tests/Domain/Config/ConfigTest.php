<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:41:33
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:50:52
 */

namespace Domain\Config;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ConfigTeste extends \TestCase
{
    use DatabaseTransactions;

    public function test_create_config()
    {
        $config = factory(Config::class)->create();

        $this->assertInstanceOf(Config::class, $config);

        $this->assertInstanceOf(Carbon::class, $config->created_at);
        $this->assertInstanceOf(Carbon::class, $config->updated_at);

        $this->seeInDataBase('configs', [
            'field' => $config->field,
            'value' => $config->value,
        ]);
    }

    public function test_create_config_with_values()
    {
        $config = factory(Config::class)->create([
            'field' => 'IP',
            'value' => 'localhost',
        ]);

        $this->assertInstanceOf(Config::class, $config);

        $this->seeInDataBase('configs', [
            'field' => 'IP',
            'value' => 'localhost',
        ]);
    }
}

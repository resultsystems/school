<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-13 08:35:12
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:52:38
 */

namespace App\Console\Commands;

use Domain\Billet\GenerateBilletService;
use Illuminate\Console\Command;

class BilletGenerate extends Command
{
    /**
     * @var GenerateBilletService
     */
    protected $generate;

    public function __construct(GenerateBilletService $generate)
    {
        parent::__construct();

        $this->generate = $generate;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billet:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gera boleto dos próximos 15 dias';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->generate->run(15);
    }
}

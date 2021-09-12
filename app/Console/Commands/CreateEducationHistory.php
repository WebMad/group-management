<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateEducationHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'education-history:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создает запись в таблице education_history о парах сегодня';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return 0;
    }
}

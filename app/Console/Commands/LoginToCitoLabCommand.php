<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\LoginToCitoLabJob;
use Illuminate\Support\Facades\Bus;

class LoginToCitoLabCommand extends Command
{
    protected $signature = 'login-to-citolab';

    protected $description = 'Execute LoginToCitoLabJob directly from the console';

    public function handle()
    {
        Bus::dispatchNow(new LoginToCitoLabJob());
    }
}
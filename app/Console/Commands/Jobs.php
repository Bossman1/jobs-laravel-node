<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class Jobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $url = 'https://jobs.ge/?page=1&q=&cid=6&lid=1&jid=1&has_salary=1';
//        $url = $this->argument('url');
//
//        $ret = exec("node public/core/index.js  --url=".$url." 2>&1 ", $out, $err);
// dd($out,$err);

        $process = new \Symfony\Component\Process\Process(['C:\Program Files\nodejs\node', 'public\core\index.js', '--url=' . $url]);

//        $process = new \Symfony\Component\Process\Process(['/usr/bin/node', 'core/index.js', '--url=' . $url]);
        $process->run();

        if (!$process->isSuccessful()) {
            dd($process->getErrorOutput());
            throw new ProcessFailedException($process);
        }

        return true;
    }
}

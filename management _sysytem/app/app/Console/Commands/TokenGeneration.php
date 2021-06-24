<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use JWTAuth;
use App\User;

class TokenGeneration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:token {key}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Take user key as input and output a encripted token';


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
     * @return mixed
     */
    public function handle()
    {
        $key = $this->argument('key');

        $user = User::where('key', $key)->first();

        if ($user) {
            $encryptedOne = JWTAuth::fromUser($user);

            $this->info('The gernerated token of key is ========> ' . $encryptedOne);
        } else {
            $this->info('user not found!!!' . json_encode($user));
        }
    }
}

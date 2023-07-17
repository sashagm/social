<?php

namespace Sashagm\Social\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class AccessSocialsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'socials:access
    {--u= : User search field  (ID)}
    {--a= : Access flag (0,1)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Данная команда может банить/разбанить пользователя';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userField = (int)$this->option('u');
        $accessFlag = (int)$this->option('a');

        $accessColumn = config('socials.user.access_colum');
        $nameColumn = config('socials.user.name_colum');
        $emailColumn = config('socials.user.email_colum');
        
        $user = User::where('id',$userField)->first();

        if (!$user) {
            $this->error('User not found.');
            return;
        }

        $user->$accessColumn = $accessFlag;
        $user->save();
        
        $this->info("User {$user->$nameColumn} ({$user->$emailColumn}) access has been changed to {$accessFlag}.");
    }
}

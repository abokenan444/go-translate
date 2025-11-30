<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateSuperAdmin extends Command
{
    protected $signature = 'superadmin:create {email} {--name=Admin User} {--password=}';
    protected $description = 'Create or update a super admin user with active status';

    public function handle(): int
    {
        $email = $this->argument('email');
        $name = $this->option('name');
        $password = $this->option('password') ?: bin2hex(random_bytes(8));

        $user = User::firstOrNew(['email' => $email]);
        $isNew = !$user->exists;

        $user->name = $name;
        $user->role = 'super_admin';
        $user->account_status = 'active';
        $user->password = Hash::make($password);
        $user->save();

        $this->info(($isNew ? 'Created' : 'Updated').' super admin: '.$email);
        $this->line('Password: '.$password);
        $this->warn('Store this password securely if newly generated.');

        return Command::SUCCESS;
    }
}

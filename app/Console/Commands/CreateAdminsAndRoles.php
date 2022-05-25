<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

use DB;
use App\Models\User;
use App\Models\Role;

class CreateAdminsAndRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:admin-role';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        DB::beginTransaction();

        try {
            $this->createRoles();
            $this->createAdmin();

            DB::commit();

            $this->info('Data created');
        } catch (\Exception $err) {
            DB::rollback();

            $this->error($err->getMessage());
            $this->error('Failed to create roles, please re-run command');
        }
    }
    
    private function createRoles()
    {
        $checkRole = Role::count();
        
        if ($checkRole == 0)
            Role::insert([
                ['name' => 'Admin'],
                ['name' => 'Account Manager'],
            ]);
    }

    private function createAdmin()
    {
        $adminRole = Role::where('name', 'Admin')->first();

        if (!$adminRole) {
            $this->error("Admin's role not found, cannot continue process");
            exit;
        }

        $checkAdmin = User::where('role_id', $adminRole->id)->exists();

        if ($checkAdmin) {
            $this->error("Admin already created!");
            exit;
        }
        
        $admin = new User;
        $admin->name = 'Admin';
        $admin->role_id = $adminRole->id;
        $admin->email =  'admin@clientmanagement.com';
        $admin->email_verified_at = now();
        $admin->password = Hash::make('123456');
        $admin->remember_token = Str::random(10);
        $admin->save();
    }
}

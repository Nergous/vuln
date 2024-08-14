<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Предполагается, что у вас есть модель User

class CreateAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:admin {name} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin user';

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
        $name = $this->argument('name');
        $password = $this->argument('password');

        $hashedPassword = Hash::make($password);

        $user = User::create([
            'login' => $name,
            'password' => $hashedPassword,
            'type' => 'Admin', // Предполагается, что у вас есть поле role
        ]);

        $this->info("Admin user created successfully with");
    }
}
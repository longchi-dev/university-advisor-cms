<?php

namespace App\Console\Commands;

use App\Enums\UserRoleEnum;
use App\Models\User;
use Illuminate\Console\Command;

class InitUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:init-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {

        $name = $this->ask('Enter the user name');
        $email = $this->ask('Enter the user email');
        $password = $this->secret('Enter the user password');
        $confirmPassword = $this->secret('Confirm the user password');
//        $role = $this->choice('Choose the user role', UserRoleEnum::values());
        // Kiểm tra xem mật khẩu có khớp không
        if ($password !== $confirmPassword) {
            $this->error('Passwords do not match. Please try again.');
            return static::SUCCESS;
        }

        // Tạo user
        try {
            if (User::query()->where('email', $email)->exists()) {
                $this->output->writeln('Email is exists');
                return static::SUCCESS;
            }
            $user = User::make($name, $email, $password);
//            $user->role = $role;
            $user->save();

            $this->info('User has been created successfully.');
        } catch (\Exception $e) {
            // Xử lý lỗi nếu có
            $this->error('An error occurred: ' . $e->getMessage());
        }

        return static::SUCCESS;
    }
}

<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use RuntimeException;

final class ShutdownHostCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'host:shutdown';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shutdown the host Ubuntu server';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Initiating host server shutdown...');

        try {
            $hostUser = config('host.user');
            $hostIp = config('host.ip');
            $password = config('host.password');

            if (empty($password)) {
                throw new RuntimeException('HOST_SSH_PASSWORD is not set in .env file');
            }

            // Use sshpass for SSH authentication and echo the password for sudo
            $command = sprintf(
                'sshpass -p %s ssh -o StrictHostKeyChecking=no %s@%s "echo %s | sudo -S shutdown -h now" 2>&1',
                escapeshellarg($password),
                escapeshellarg($hostUser),
                escapeshellarg($hostIp),
                escapeshellarg($password)
            );

            $result = shell_exec($command);

            if ($result === null || empty($result)) {
                $this->info('Shutdown command sent successfully to the host system. The server will shutdown now.');
            } else {
                $this->error('Failed to execute shutdown command. Error: '.$result);
                $this->error('Make sure:');
                $this->error('1. The HOST_SSH_PASSWORD is set correctly in your .env file');
                $this->error('2. The HOST_SSH_USER and HOST_SSH_IP are set correctly');
                $this->error('3. The host user exists and has the correct password');
                $this->error('4. The host user has sudo privileges for shutdown command');
            }
        } catch (Exception $e) {
            $this->error('An error occurred: '.$e->getMessage());
        }
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ShutdownHostCommand extends Command
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
            // Execute shutdown command on the host system via SSH with password
            $hostUser = env('HOST_SSH_USER', 'root');
            $hostIp = env('HOST_SSH_IP', 'host.docker.internal');
            $password = env('HOST_SSH_PASSWORD');
            
            if (empty($password)) {
                throw new \RuntimeException('HOST_SSH_PASSWORD is not set in .env file');
            }
            
            // Use sshpass for password authentication
            $command = sprintf(
                'sshpass -p %s ssh -o StrictHostKeyChecking=no %s@%s \'sudo shutdown -h now\' 2>&1',
                escapeshellarg($password),
                escapeshellarg($hostUser),
                escapeshellarg($hostIp)
            );
            
            $result = shell_exec($command);
            
            if ($result === null || empty($result)) {
                $this->info('Shutdown command sent successfully to the host system. The server will shutdown now.');
            } else {
                $this->error('Failed to execute shutdown command. Error: ' . $result);
                $this->error('Make sure:');
                $this->error('1. The HOST_SSH_PASSWORD is set correctly in your .env file');
                $this->error('2. The HOST_SSH_USER and HOST_SSH_IP are set correctly');
                $this->error('3. The host user exists and has the correct password');
                $this->error('4. The host user has sudo privileges for shutdown command');
            }
        } catch (\Exception $e) {
            $this->error('An error occurred: ' . $e->getMessage());
        }
    }
}

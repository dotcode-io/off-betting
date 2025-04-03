# Off-Betting Application

A Laravel-based betting application with WebSocket support using Laravel Reverb.

## System Requirements

- Docker
- Docker Compose
- Git

## Setup Instructions

1. Clone the repository:
```bash
git clone <repository-url>
cd off-betting
```

2. Copy the environment file:
```bash
cp .env.example .env
```

3. Configure your `.env` file with the following settings:
```env
APP_URL=http://192.168.254.111:8088

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=off_betting
DB_USERNAME=off_betting
DB_PASSWORD=secret

REDIS_CLIENT=phpredis
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

BROADCAST_CONNECTION=reverb
REVERB_APP_ID=928134
REVERB_APP_KEY=dhoqbptglvmhcaeuqmcd
REVERB_APP_SECRET=dppwarecleaoo2hiodus
REVERB_HOST=192.168.254.111
REVERB_PORT=8089
REVERB_SCHEME=ws
```

4. Build and start the Docker containers:
```bash
docker-compose build
docker-compose up -d
```

5. Install dependencies and set up the application:
```bash
# Access the app container
docker exec -it off-betting-app bash

# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate

# Clear configuration cache
php artisan config:clear
```

## Services

The application runs the following services:

- **Web Application**: http://192.168.254.111:8088
- **WebSocket Server**: ws://192.168.254.111:8089
- **MySQL**: Port 3307 (external), 3306 (internal)
- **Redis**: Port 6380 (external), 6379 (internal)

## Managed Processes

All processes are managed by Supervisor:

- PHP-FPM
- Laravel Queue Worker (2 processes)
- Laravel Scheduler
- Laravel Reverb (WebSocket Server)

## File Descriptors and Connections

The application is configured to handle high concurrent connections:

- Nginx: 10,000 concurrent connections
- Supervisor: 10,000 file descriptors
- Laravel Reverb: WebSocket connections

## Logs

You can find logs in the following locations inside the containers:

- **Supervisor Logs**: `/var/log/supervisor/`
  - PHP-FPM: `php-fpm.out.log`, `php-fpm.err.log`
  - Queue Worker: `worker.log`
  - Scheduler: `scheduler.log`
  - Reverb: `reverb.log`
- **Nginx Logs**: `/var/log/nginx/`
  - Access Log: `access.log`
  - Error Log: `error.log`

To view logs:
```bash
# Supervisor logs
docker exec off-betting-app tail -f /var/log/supervisor/worker.log
docker exec off-betting-app tail -f /var/log/supervisor/reverb.log

# Nginx logs
docker exec off-betting-nginx tail -f /var/log/nginx/error.log
```

## Common Commands

```bash
# Start all services
docker-compose up -d

# Stop all services
docker-compose down

# Restart a specific service
docker-compose restart app
docker-compose restart nginx

# View logs
docker-compose logs -f app
docker-compose logs -f nginx

# Access container shell
docker exec -it off-betting-app bash
```

## Production Deployment

For production environments, you can run Cloudflare tunnel with auto-restart:
```bash
docker run -d \
  --name off-betting-cloudflared \
  --restart always \
  cloudflare/cloudflared:latest \
  tunnel --no-autoupdate run --token eyJhIjoiZDZjNjAxOWFjODRhMGFjNDg5Y2FiY2IzZWRmZmJmM2QiLCJ0IjoiOTcyYWUxNmEtNGQwOS00NzUwLTg2ZjUtZTdmYTJmYTdjN2RmIiwicyI6IllUUmtZemRoWlRBdE0yVmxOUzAwWVRBNUxUZzRPVGd0TURGalpESXhNek0zTVRFMCJ9
```

This will ensure the tunnel restarts automatically if the server reboots.

## Troubleshooting

1. If WebSocket connections fail:
   - Check if Reverb is running: `docker exec off-betting-app supervisorctl status`
   - Verify Nginx ports are accessible
   - Check Reverb logs for errors

2. If queue jobs aren't processing:
   - Check queue worker status: `docker exec off-betting-app supervisorctl status laravel-worker:*`
   - View worker logs for errors

3. For database connection issues:
   - Verify MySQL is running: `docker-compose ps mysql`
   - Check connection settings in `.env`

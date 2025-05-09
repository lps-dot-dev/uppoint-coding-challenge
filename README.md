# uppoint-coding-challenge

## Dependencies
- [Node v22 (LTS)](https://nodejs.org/en/download)
- [Docker Desktop](https://www.docker.com/products/docker-desktop/) or [Podman](https://podman.io/)

## Getting Started
### Frontend
The building process is handled by Vite. The following commands should do the trick:
```
npm install
npm run build
```

### Backend
The `entrypoint.sh` script is called everything time the container is started and does the following things for you:
1. Copies the `.env.example` file to `.env` (if it doesn't already exist)
2. Install/Update `composer` dependencies
3. Will generate `APP_KEY` via `php artisan key:generate` (if it is not already present)
4. Clear Laravel's caches, run migrations and tests
5. Restart the `apache2` service
6. Start `supervisord` to manage Laravel's queue and reverb broadcasting system

From this directory, build containers using the following command: `docker-compose up --build`

Once the containers are up and running, you can access the application by going to [localhost:3000](localhost:3000)
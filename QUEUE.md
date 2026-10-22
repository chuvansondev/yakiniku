# Queue workers

The application uses Laravel's database queue by default (`QUEUE_CONNECTION=database`). The `jobs`, `job_batches`, and `failed_jobs` tables are created by the existing queue migration.

## Local development

Run `composer dev` to start the web server, Vite, and a queue worker together. To run the worker separately, use:

```bash
php artisan queue:work database --queue=default --tries=3 --timeout=60
```

## Production

Keep a worker process running under a process manager such as Supervisor or systemd:

```bash
php artisan queue:work database --queue=default --sleep=3 --tries=3 --timeout=60 --max-time=3600
```

Restart workers after deployments so they load the latest code:

```bash
php artisan queue:restart
```

Inspect and retry failed jobs with:

```bash
php artisan queue:failed
php artisan queue:retry all
```

Jobs are configured to wait until the surrounding database transaction commits before entering the queue.

# Environment Variables

## Backend (.env)

| Variable | Description | Default |
|----------|-------------|---------|
| `APP_NAME` | Application name | UptimeMonitor |
| `APP_ENV` | Environment (local/production) | local |
| `APP_KEY` | Encryption key | (generated) |
| `APP_DEBUG` | Debug mode | true |
| `APP_URL` | Application URL | http://localhost |
| `DB_CONNECTION` | Database driver | mysql |
| `DB_HOST` | Database host | 127.0.0.1 |
| `DB_PORT` | Database port | 3306 |
| `DB_DATABASE` | Database name | uptime_monitor |
| `DB_USERNAME` | Database user | root |
| `DB_PASSWORD` | Database password | |

## Frontend (.env)

| Variable | Description | Default |
|----------|-------------|---------|
| `VITE_API_BASE_URL` | Base URL for the backend API | http://localhost:8000/api |

**Note**: In production, ensure `VITE_API_BASE_URL` points to your production API endpoint.

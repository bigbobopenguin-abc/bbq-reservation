# Render setup

This project is prepared for deployment as a Docker Web Service.

## Required database variables
Set these in Render under Environment:

- `DB_HOST`
- `DB_PORT` (normally `3306`)
- `DB_NAME`
- `DB_USER`
- `DB_PASSWORD`

Import `Project.sql` into the MySQL database before opening the site.

## Render Web Service
- Runtime/Language: Docker
- Root directory: leave blank if these files are at the repository root
- Dockerfile path: `./Dockerfile`
- No build command is required
- No start command is required

The container runs Apache with PHP 8.2 and mysqli support.

# Render setup

This project is prepared for deployment as a Docker Web Service.
The database is hosted on **TiDB Cloud Starter** (free, MySQL-compatible).

## 1. Create the TiDB Cloud database
1. Sign up at https://tidbcloud.com and create a **Starter** (serverless) cluster.
   Pick the region closest to your Render service (e.g. Singapore).
2. Open the cluster, click **Connect**, and choose **Connect with: General**.
   Click **Generate Password** and copy it now; it is only shown once.
   Note the host, port (`4000`) and user (looks like `xxxxxxxx.root`).
3. Open **SQL Editor** in the TiDB console and run:
   ```sql
   CREATE DATABASE bbq;
   ```

## 2. Load the data
Moving from an existing database (e.g. Aiven), export it first:
```bash
mysqldump -h <old-host> -P <old-port> -u <old-user> -p --ssl-mode=REQUIRED --set-gtid-purged=OFF --no-tablespaces <old-db> > bbq_backup.sql
```

Then import into TiDB:
```bash
mysql -h <tidb-host> -P 4000 -u <tidb-user> -p --ssl-mode=VERIFY_IDENTITY bbq < bbq_backup.sql
```

For a fresh install, import `Project.sql` instead of `bbq_backup.sql`.

If the import stops on an Aiven-specific line (for example one mentioning
`GTID_PURGED` or `sql_require_primary_key`), delete that line from the dump and run it again.

## 3. Database variables on Render
Set these in Render under **Environment**:

| Variable      | Value for TiDB Cloud                         |
|---------------|----------------------------------------------|
| `DB_HOST`     | e.g. `gateway01.ap-southeast-1.prod.aws.tidbcloud.com` |
| `DB_PORT`     | `4000`                                       |
| `DB_NAME`     | `bbq`                                        |
| `DB_USER`     | e.g. `xxxxxxxx.root` (including the prefix)  |
| `DB_PASSWORD` | the generated password                       |

TiDB Cloud requires TLS. `dbinfo.php` already connects with SSL, so no code change is needed.

## Render Web Service
- Runtime/Language: Docker
- Root directory: leave blank if these files are at the repository root
- Dockerfile path: `./Dockerfile`
- No build command is required
- No start command is required

The container runs Apache with PHP 8.2 and mysqli support.

## Notes
- A Starter cluster that sits idle scales down to zero, so the first request
  after a quiet period can take a few seconds.
- Check https://www.pingcap.com/tidb-cloud-pricing for the current free-tier limits.

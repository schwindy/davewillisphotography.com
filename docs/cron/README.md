# kekPHP Cron Jobs

## Configuration: Cron Environment
Configure the crontab for your application environment.
```
PATH=/sbin:/bin:/usr/sbin:/usr/bin
path = /var/www/skippy.thewashplant.com/thewashplant-kek/
```

## Job Manager
Run Job System Queue Manager(s).
```
* * * * * cd $path; php elements/cron.php run=jobs/JobManager
* * * * * cd $path; php elements/cron.php run=jobs/KekJobManager
```

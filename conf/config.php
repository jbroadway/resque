; <?php /*

[Resque]

; Backend Redis connection info.

backend = "localhost:6379"

; The Redis backend database to select.

backend_db = 0

; The name of the queue or queues to run ("*" runs
; all queues).

queue = "*"

; The number of workers to launch.

workers = 1

; A prefix for the Redis keys used by the Resque app.

prefix = "resque:"

; Logging output level (Off, normal, or verbose).

logging = Off

; Sleep interval for workers, in seconds.

sleep_interval = 5

; File to store the PID of the worker process.

pid_file = Off

[Admin]

;name = Resque
;handler = resque/admin
version = 0.9.0-beta

; */ ?>

#!/bin/bash

if ! pgrep -f "php /home/vincenti/rockertech-app/artisan queue:work" > /dev/null
then
    /bin/bash /home/vincenti/rockertech-app/queue/queue_worker.sh &
fi
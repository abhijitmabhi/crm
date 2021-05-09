#!/bin/bash

trap 'kill $HORIZON_ID && kill $WS_ID; exit' SIGINT SIGTERM
php artisan horizon > storage/logs/horizon.log &
HORIZON_ID=$!
php artisan websockets:serve > storage/logs/websockets.log &
WS_ID=$!

while :
do
    sleep 2
    echo "running scheduler..."
    php artisan schedule:run
    sleep 58
done
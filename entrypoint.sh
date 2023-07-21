#!/usr/bin/env bash

sleep 10

composer install;

while true; do
    php index.php $1 $2
    sleep 30
done



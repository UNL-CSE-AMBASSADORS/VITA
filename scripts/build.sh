#!/bin/bash
if [ "$1" == "production" ] || [ "$1" == "prod" ]; then
    npm run build:prod
else
    npm run build:dev
fi

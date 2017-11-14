#!/bin/bash
if [ "$1" == "production" ] || [ "$1" == "prod" ]; then
    npm run production
else
    npm run build
fi

#!/bin/bash
#Just for documenting how to begin a repo
git submodule init
git submodule update
npm install
npm update
./scripts/build.sh dev
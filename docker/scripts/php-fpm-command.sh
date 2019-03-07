#!/usr/bin/env bash

cd "`dirname \"$0\"`" && \
  docker-compose -f ${PWD}/../docker-compose.yml exec -T "app" sh -c "cd /app && $*"
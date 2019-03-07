#!/usr/bin/env bash

green=$(tput setf 2)
toend=$(tput hpa $(tput cols))$(tput cub 6)


echo 'Сейчас мы запустим сборку докера!'
docker-compose -f ${PWD}/docker/docker-compose.yml up --force-recreate -d || exit

echo -en '\n'
echo -n "Докер успешно собрался! ${green}${toend}[OK]"
#!/bin/sh

TEMPLATE_CONF="./docker.template"
CONF_FILE="/etc/nginx/conf.d/docker.conf"

CONTAINER_ID=`sudo docker ps|tail -1|awk '{print $1}'`

DOCKER_NGINX_PORT=`sudo docker inspect -f '{{(index (index .NetworkSettings.Ports "80/tcp") 0).HostPort}}' $CONTAINER_ID`

NGINX_STATUS=`sudo sh -c "service nginx status"` > /dev/null
if [ $? -ne 0 ]; then
  sudo sh -c "service nginx start"
fi

if [ $DOCKER_NGINX_PORT ]; then
  echo $DOCKER_NGINX_PORT
  sudo sh -c "sed -e 's/PORT/$DOCKER_NGINX_PORT/g' $TEMPLATE_CONF > $CONF_FILE"
  sudo sh -c "service nginx configtest" > /dev/null
  if [ $? -eq 0 ]; then
    sudo sh -c "service nginx reload"
  else
    echo "error."
  fi
else
  echo "error."
fi

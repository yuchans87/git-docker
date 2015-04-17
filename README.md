# git-docker
Github Docker Test 2015-04-16

Usage:
* sudo yum install -y git docker nginx
* sudo service docker start
* sudo docker pull yuchans87/amazn:v0.1
* mkdir DIR
* cd DIR/
* git clone git@github.com:yuchans87/git-docker.git

* sudo docker build -t REPO:TAG .
* sudo docker run -d -p 22 -p 80 REPO:TAG

* /bin/sh ./nginx_docker_port_setting.sh

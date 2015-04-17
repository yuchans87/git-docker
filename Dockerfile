FROM yuchans87/amazn:v0.1
MAINTAINER yuchans87 yuichi@news2u.co.jp

# Init System
RUN cp /usr/share/zoneinfo/Japan /etc/localtime
RUN sed -ri 's/"UTC"/"Asia\/Tokyo"/g' /etc/sysconfig/clock
RUN sed -ri 's/true/false/g' /etc/sysconfig/clock
RUN sed -ri 's/en_US/ja_JP/g' /etc/sysconfig/i18n

# System Update & Install
RUN yum -y update
RUN yum -y install vim nginx php55-cli php55-common php55-fpm php55-gd php55-intl php55-mbstring php55-mcrypt php55-mysqlnd php55-pdo php55-pecl-http php55-pecl-jsonc php55-pecl-oauth php55-xml php55-xmlrpc php55-opcache

# ADD key & template
ADD ./key /home/ec2-user/.ssh/
ADD ./template /tmp/

# Init SSHD
RUN sed -ri 's/UsePAM yes/#UsePAM yes/g' /etc/ssh/sshd_config
RUN sed -ri 's/#UsePAM no/UsePAM no/g' /etc/ssh/sshd_config
RUN passwd -f -u ec2-user

RUN service sshd start
RUN service sshd stop

RUN chown ec2-user:ec2-user /home/ec2-user/.ssh/authorized_keys
RUN chmod 0600 /home/ec2-user/.ssh/authorized_keys

# Supervisord
RUN easy_install supervisor
RUN echo_supervisord_conf > /etc/supervisord.conf
RUN echo '[include]' >> /etc/supervisord.conf
RUN echo 'files = /etc/supervisord.d/conf/*.ini' >> /etc/supervisord.conf

RUN mkdir -p /etc/supervisord.d/conf
RUN cp /tmp/service.ini /etc/supervisord.d/conf/service.ini
RUN cp /tmp/supervisord /etc/init.d/supervisord
RUN chmod 0755 /etc/init.d/supervisord

# Add Contents
ADD ./app /usr/share/nginx/html/

EXPOSE 22 80
CMD /usr/local/bin/supervisord -c /etc/supervisord.conf

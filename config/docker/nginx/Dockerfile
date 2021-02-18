FROM nginx:stable

ARG UID
ARG GID

RUN usermod -u $UID nginx
RUN groupmod -g $GID nginx

RUN rm /etc/nginx/conf.d/default.conf
COPY conf.d/fdj-nginx.conf /etc/nginx/conf.d/fdj.conf

STOPSIGNAL SIGTERM

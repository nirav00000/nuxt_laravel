FROM alpine:3.12

RUN mkdir -p /usr/src/app

# set version for s6 overlay
ARG OVERLAY_VERSION="v1.21.4.0"
ARG OVERLAY_ARCH="amd64"

ENV DOCKERIZE_VERSION v0.11.2

RUN apk update \
  && apk add --no-cache --virtual .run-deps \
  htop curl nginx supervisor nodejs npm \
  && wget https://github.com/just-containers/s6-overlay/releases/download/${OVERLAY_VERSION}/s6-overlay-${OVERLAY_ARCH}.tar.gz \
  && tar -xzvf s6-overlay-amd64.tar.gz -C / \
  && wget https://github.com/powerman/dockerize/releases/download/$DOCKERIZE_VERSION/dockerize-linux-x86_64 \
  && mv dockerize-linux-x86_64 /usr/local/bin/dockerize \
  && chmod a+x /usr/local/bin/dockerize

RUN chown -R nginx:nginx /var/www \
  # && mkdir -p /etc/nginx \
  && mkdir -p /etc/nginx/sites-available \
  && mkdir -p /etc/nginx/sites-enabled \
  && mkdir -p /var/log/supervisor \
  && rm -rf \
  /tmp/*
  # && rm -Rf /var/www/*

WORKDIR /usr/src/app
# Install app dependencies
COPY *.json /usr/src/app/

RUN npm install

# # Bundle app source
COPY . /usr/src/app

ARG BUILD_TYPE

COPY .env.$BUILD_TYPE /usr/src/app/.env

RUN npm run build

STOPSIGNAL SIGTERM
ENTRYPOINT ["dockerize", \
  # "-stdout", "/var/log/nginx/access.log", \
  "-stdout", "/var/log/nginx/error.log"]
  # "-stdout", "/dev/stderr"]

RUN mkdir -p /run/nginx \
    && rm -r /etc/nginx/conf.d/default.conf \
    && cp -r rootfs/etc/nginx/sites-enabled/default.conf /etc/nginx/conf.d/default.conf \
    && cp -r rootfs/etc/supervisord.conf /etc/supervisord.conf \
    && cp -r rootfs/etc/services.d /etc

EXPOSE 80 443

CMD [ "/init" ]

# CMD ["./process-start.sh"]
# CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisord.conf"]

FROM php:5.6-cli

RUN apt-get update && \
    apt-get install -y --no-install-recommends git zip

RUN curl --silent --show-error https://getcomposer.org/installer | php

# Endless command to keep container running
CMD tail -f /dev/null
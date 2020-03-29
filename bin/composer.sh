#!/bin/bash

docker run --rm -t \
    -u $UID:$UID \
    -v $(pwd):/app \
    -v $HOME/.cache/composer:$HOME/.composer \
    -v $HOME/.ssh:/home/$USER/.ssh \
    -v /etc/passwd:/etc/passwd:ro \
    -v /etc/group:/etc/group:ro \
    --network=host \
    composer $@
<?php

return [
    'user' => env('HOST_SSH_USER', 'root'),
    'ip' => env('HOST_SSH_IP', 'host.docker.internal'),
    'password' => env('HOST_SSH_PASSWORD'),
];

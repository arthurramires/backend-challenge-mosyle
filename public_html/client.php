<?php

    $url = 'http://localhost/mosyle-challenge/public_html/api';

    $class = '/user';
    $param = '';

    $response = file_get_contents($url.$class.$param);
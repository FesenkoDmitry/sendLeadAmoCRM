<?php

const CLIENT_SECRET = ''; 
const CLIENT_ID     = '';
const AUTH_CODE          = '';
const TOKEN_FILE    = 'tokens.txt';
const COOKIE_FILE = 'cookie.txt';;
const LOG_FILE = 'log.txt';
const REDIRECT_URI  = 'http://fesenkwh.beget.tech/index.php';

const CURL_REQUEST_METHOD = 'POST';
const CURL_SEND_LEAD_URL = 'https://fesenkodmitry.amocrm.ru/api/v4/leads/complex';
const CURL_SEND_LEAD_USERAGENT = 'amoCRM-API-client/1.0';
const CURL_OAUTH_TOKEN_URL = 'https://fesenkodmitry.amocrm.ru/oauth2/access_token';
const CURL_OAUTH_TOKEN_USERAGENT = 'amoCRM-oAuth-client/1.0';
const CURL_OAUTH_HTTP_HEADER = ['Content-Type:application/json'];

const REFRESH_GRANT_TYPE = 'refresh_token';
const AUTH_GRANT_TYPE = 'authorization_code';

const SEND_LEAD_METHOD = "/api/v4/leads/complex";

<?php

$router->get('', 'PagesController@home');
$router->get('placement-test', 'PagesController@test');

$router->get('questoes', 'PagesController@getQuestoes');

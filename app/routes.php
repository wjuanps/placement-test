<?php

$router->get('', 'PagesController@home');
$router->get('register', 'PagesController@register');
$router->get('test-your-english', 'PagesController@test');
$router->get('result', 'PagesController@showResult');

$router->get('questoes', 'PagesController@getQuestoes');
$router->get('geonames', 'PagesController@getGeoNames');

$router->post('store', 'PagesController@store');
$router->post('save-answer', 'PagesController@saveAnswer');
$router->post('end-placement', 'PagesController@endPlacement');

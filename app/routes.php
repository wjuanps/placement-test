<?php

$router->get('', 'PagesController@home');
$router->get('register', 'PagesController@register');

$router->get('result', 'PlacementController@showResult');

$router->get('test-your-english', 'PlacementController@testYourEnglish');

$router->get('questoes', 'PlacementController@getQuestoes');
$router->get('geonames', 'PagesController@getGeoNames');

$router->post('store', 'PlacementController@store');
$router->post('save-answer', 'PlacementController@saveAnswer');
$router->post('end-placement', 'PlacementController@endPlacement');

<?php

namespace App\Core;

use App\Core\Database\QueryBuilder;
use App\Core\Database\Connection;

require_once 'core/helpers.php';

App::bind('config', require 'config.php');

App::bind('database', new QueryBuilder(
    Connection::make(App::get('config')['database'])
));

<?php

namespace App\Util;

use stdClass;

interface MenuServiceInterface {
  public function getMenu(int $storeId): stdClass;
}
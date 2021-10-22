<?php

namespace App\Controller;

use stdClass;
use \Pimcore\Model\DataObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Util\MenuServiceInterface;

class MenuController extends BaseController {
  private $menuService;

  public function __construct(MenuServiceInterface $menuService)
  {
      $this->menuService = $menuService;
  }

  /**
  * @Route("/menus/stores/{storeId}")
  * @param Request $request
  *
  * @return Response
  */
  public function getMenusByStoreAction(Request $request) {
    $storeId = $request->get('storeId');

    $store = DataObject\Store::getById($storeId);
    
    if (!$store) {
      return $this->returnErrorResponse('Store not found.', 404);
    }

    $menu = $this->menuService->getMenu($storeId);
    if (empty($menu)) {
      return $this->returnErrorResponse('There are no menus associated to that store.');
    }

    return $this->returnContent($menu); 
  }
}
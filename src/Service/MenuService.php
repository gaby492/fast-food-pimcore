<?php

namespace App\Service;

use stdClass;
use App\Util\MenuServiceInterface;
use \Pimcore\Model\DataObject;

class MenuService implements MenuServiceInterface {

  /**
  * @return stdClass
  */
  public function getMenu(int $storeId): stdClass {
    $store = DataObject\Store::getById($storeId);
    $newMenu = new stdClass();
    $newMenu->regular = $this->getRegularMenu($store->getMenu());
    $newMenu->combos = $this->getCombos($store->getCombos());

    return $newMenu;
  }

  /**
  * @return array
  */
  private function getRegularMenu($regularMenu) {
    $menuElements = [];

    foreach ($regularMenu->getItems() as $category) {
      $menuElement = new stdClass();
      $menuElement->category = $category->getName();

      foreach ($category->getItems() as $product) {
          $item = $this->createBasicItem($product);
          $menuElement->items[] = $item;
        $menuElement->items[] = $item;
      }

      $menuElements[] = $menuElement;
    }

    return $menuElements;
  }

  /**
  * @return stdClass
  */
  private function createBasicItem($originalProduct) {
    $basicItem = $this->getCommonProperties($originalProduct);
    $this->getModifierGroups($basicItem, $originalProduct); 

    return $basicItem;
  }

  /**
  * @return array
  */
  private function getCombos($combosList) {
    $combos = [];
    foreach ($combosList as $combo) {
      $newCombo = $this->getCommonProperties($combo);
      $basicItems = $combo->getBasicItems();
  
      $newCombo->basicItems = [];
      foreach ($basicItems as $basicItem) {
        $newCombo->basicItems[] = $this->createBasicItem($basicItem);
      }

      $combos[] = $newCombo;
    }

    return $combos;
  }

  /**
  * @return stdClass
  */
  private function getCommonProperties($product) {
    $newProduct = new stdClass();
    $newProduct->name = $product->getName();
    $newProduct->externalName = $product->getExternalName();
    $newProduct->description = $product->getDescription();
    $newProduct->photo = $product->getPhoto() ? $_SERVER['HOST'] . $product->getPhoto()->getFullPath() : null;

    return $newProduct;
  }

  private function getModifierGroups(&$item, $originalProduct) {
    $modifierGroups = [];
    $metadata = $originalProduct->getModifiers();
    $item->modifierGroups = [];

    foreach ($metadata as $row) {
      $modifierGroup = $row->getObject();
      $newModifierGroup = new stdClass();
      $newModifierGroup->name = $modifierGroup->getName();
      $newModifierGroup->externalName = $modifierGroup->getExternalName();
      $newModifierGroup->isExtra = $row->getdata()['isExtra'];
      $newModifierGroup->size = $row->getdata()['size'];

      foreach ($modifierGroup->getItemModifiers() as $modifier) {
        $newModifierGroup->modifiers[] = $modifier->getExternalName();
      }
      
      $item->modifierGroups[] = $newModifierGroup;
    }
  }
}
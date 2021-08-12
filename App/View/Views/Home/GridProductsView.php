<?php

  namespace App\View\Views\Home;
  use \Core\View\View;

  /**
   * Muestra un grid con los productos
   */
  class GridProductsView extends View {

    private $products;
    public function __construct($products) {
      $this->products = $products;
    }

    protected function _html() {
      ?>
      <h1>Productos</h1>
      <?php
      foreach ($this->products as $product) {
        echo $product . '<br>';
      }
    }
  }

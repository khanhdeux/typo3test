<?php

namespace Khanhdeux\Inventory\Controller;

class InventoryController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * @var \Khanhdeux\Inventory\Domain\Repository\ProductRepository
     * @inject
     */
    protected $productRepository;

    public function listAction()
    {
        $products = $this->productRepository->findAll();
        $this->view->assign('products', $products);
    }
}

?>
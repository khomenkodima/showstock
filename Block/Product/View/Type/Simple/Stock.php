<?php
namespace Khomenko\ShowStock\Block\Product\View\Type\Simple;

use Magento\InventoryApi\Api\GetSourceItemsBySkuInterface;

class Stock extends \Magento\Catalog\Block\Product\View\AbstractView
{
    /**
     * @var GetSourceItemsBySkuInterface
     */
    private $getSourceItemsBySku;

    /**
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\Stdlib\ArrayUtils $arrayUtils
     * @param GetSourceItemsBySkuInterface $getSourceItemsBySku
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Stdlib\ArrayUtils $arrayUtils,
        GetSourceItemsBySkuInterface $getSourceItemsBySku,
        array $data = []
    ) {
        $this->getSourceItemsBySku = $getSourceItemsBySku;
        parent::__construct(
            $context,
            $arrayUtils,
            $data
        );
    }

    public function getStockQty()
    {
        $qty = 0;
        $product = $this->getProduct();
        $sourceItemsBySku = $this->getSourceItemsBySku->execute($product->getSku());
        foreach ($sourceItemsBySku as $sourceItem) {
            $source = $sourceItem->getSourceCode();
            $qty += $sourceItem->getQuantity();
        }
        return $qty;
    }
}

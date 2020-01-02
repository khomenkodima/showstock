<?php
namespace Khomenko\ShowStock\Plugin\Product\View\Type;

use Magento\ConfigurableProduct\Block\Product\View\Type\Configurable as OriginalClass;
use Magento\InventoryApi\Api\GetSourceItemsBySkuInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;

class Configurable extends OriginalClass
{
    /**
     * @var \Magento\Framework\Json\DecoderInterface;
     */
    protected $jsonDecoder;
    /**
     * @var GetSourceItemsBySkuInterface
     */
    private $getSourceItemsBySku;
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @param \Magent\Framework\Json\EncoderInterface $jsonEncoder
     * @param GetSourceItemsBySkuInterface $getSourceItemsBySku
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        \Magento\Framework\Json\DecoderInterface $jsonDecoder,
        GetSourceItemsBySkuInterface $getSourceItemsBySku,
        ProductRepositoryInterface $productRepository
    ) {
        $this->jsonDecoder = $jsonDecoder;
        $this->getSourceItemsBySku = $getSourceItemsBySku;
        $this->productRepository = $productRepository;
    }
    
    public function afterGetAllowProducts(OriginalClass $subject, $result)
    {
        if (!$this->hasAllProducts()) {
            $products = [];
            $allProducts = $subject->getProduct()->getTypeInstance()->getUsedProducts($subject->getProduct(), null);
            foreach ($allProducts as $product) {
                $products[] = $product;
            }
            $this->setAllProducts($products);
        }
        return $this->getData('all_products');
    }

    public function afterGetJsonConfig(OriginalClass $subject, $result)
    {
        $data = $this->jsonDecoder->decode($result);
        foreach ($data['attributes'] as $id => &$attribute) {
            foreach ($attribute['options'] as &$option) {
                $qty = 0;
                foreach ($option['products'] as $productId) {
                    $qty += $this->getMsiQty($productId);
                }
                $stockLabel = $qty ? __("(%1 Avaiable In Stock)",  $qty) : __("(Out Of Stock)");
                $option['label'] = $option['label'] . ' ' . $stockLabel;
                $option['stock'] = $qty;
            }
        }
        return $subject->jsonEncoder->encode($data);
    }

    private function getMsiQty($productId)
    {
        $qty = 0;
        $product = $this->productRepository->getById($productId);
        $sourceItemsBySku = $this->getSourceItemsBySku->execute($product->getSku());

        foreach ($sourceItemsBySku as $sourceItem) {
            $source = $sourceItem->getSourceCode();
            $qty += $sourceItem->getQuantity();
        }
        return $qty;
    }
}

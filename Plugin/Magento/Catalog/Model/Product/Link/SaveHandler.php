<?php
/**
 * @author Lex Beelen <lex@weprovide.com>
 */

namespace WeProvide\FixDuplicate\Plugin\Magento\Catalog\Model\Product\Link;

use Magento\Catalog\Api\ProductLinkRepositoryInterface;

/**
 * Class SaveHandler
 * @package WeProvide\FixDuplicate\Plugin\Magento\Catalog\Model\Product\Link
 */
class SaveHandler {

    /**
     * @var ProductLinkRepositoryInterface
     */
    protected $productLinkRepository;

    /**
     * SaveHandler constructor.
     * @param ProductLinkRepositoryInterface $productLinkRepository
     */
    public function __construct(
        ProductLinkRepositoryInterface $productLinkRepository
    ) {
        $this->productLinkRepository = $productLinkRepository;
    }

    /**
     * Around execute
     * @param \Magento\Catalog\Model\Product\Link\SaveHandler $subject
     * @param \Closure $proceed
     * @param $entityType
     * @param $entity
     * @return \Magento\Catalog\Api\Data\ProductInterface
     */
    public function aroundExecute(
        \Magento\Catalog\Model\Product\Link\SaveHandler $subject,
        \Closure $proceed,
        $entityType,
        $entity
    )
    {
        if (!$entity->getIsDuplicate()) {
            /** @var \Magento\Catalog\Api\Data\ProductInterface $entity */
            foreach ($this->productLinkRepository->getList($entity) as $link) {
                $this->productLinkRepository->delete($link);
            }
        }
        foreach ($entity->getProductLinks() as $link) {
            $this->productLinkRepository->save($link);
        }
        return $entity;
    }

}

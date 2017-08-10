<?php declare(strict_types=1);

namespace Shopware\Product\Writer\Field\ProductTopSellerRo;

use Shopware\Framework\Validation\ConstraintBuilder;
use Shopware\Product\Writer\Api\IntField;

class SalesField extends IntField
{
    public function __construct(ConstraintBuilder $constraintBuilder)
    {
        parent::__construct('sales', 'sales', 'product_top_seller_ro', $constraintBuilder);
    }
}
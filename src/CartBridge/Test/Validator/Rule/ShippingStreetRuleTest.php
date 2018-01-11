<?php declare(strict_types=1);
/**
 * Shopware 5
 * Copyright (c) shopware AG
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * "Shopware" is a registered trademark of shopware AG.
 * The licensing of the program under the AGPLv3 does not imply a
 * trademark license. Therefore any rights, title and interest in
 * our trademarks remain entirely with us.
 */

namespace Shopware\CartBridge\Test\Validator\Rule;

use PHPUnit\Framework\TestCase;
use Shopware\Api\Country\Struct\Country;
use Shopware\Api\Country\Struct\CountryBasicStruct;
use Shopware\Api\Country\Struct\CountryStateBasicStruct;
use Shopware\Api\Customer\Struct\CustomerAddressBasicStruct;
use Shopware\Cart\Cart\Struct\CalculatedCart;
use Shopware\Cart\Delivery\Struct\ShippingLocation;
use Shopware\CartBridge\Rule\ShippingStreetRule;
use Shopware\Context\Struct\ShopContext;
use Shopware\Framework\Struct\StructCollection;

class ShippingStreetRuleTest extends TestCase
{
    public function testWithExactMatch(): void
    {
        $rule = new ShippingStreetRule('example street');

        $cart = $this->createMock(CalculatedCart::class);

        $context = $this->createMock(ShopContext::class);

        $context->expects($this->any())
            ->method('getShippingLocation')
            ->will($this->returnValue(
                ShippingLocation::createFromAddress(
                    $this->createAddress('example street')
                )
            ));

        $this->assertTrue(
            $rule->match($cart, $context, new StructCollection())->matches()
        );
    }

    public function testCaseInsensitive(): void
    {
        $rule = new ShippingStreetRule('ExaMple StreEt');

        $cart = $this->createMock(CalculatedCart::class);

        $context = $this->createMock(ShopContext::class);

        $context->expects($this->any())
            ->method('getShippingLocation')
            ->will($this->returnValue(
                ShippingLocation::createFromAddress(
                    $this->createAddress('example street')
                )
            ));

        $this->assertTrue(
            $rule->match($cart, $context, new StructCollection())->matches()
        );
    }

    public function testNotMatch(): void
    {
        $rule = new ShippingStreetRule('example street');

        $cart = $this->createMock(CalculatedCart::class);

        $context = $this->createMock(ShopContext::class);

        $context->expects($this->any())
            ->method('getShippingLocation')
            ->will($this->returnValue(
                ShippingLocation::createFromAddress(
                    $this->createAddress('test street')
                )
            ));

        $this->assertFalse(
            $rule->match($cart, $context, new StructCollection())->matches()
        );
    }

    public function testWithoutAddress(): void
    {
        $rule = new ShippingStreetRule('ExaMple StreEt');

        $cart = $this->createMock(CalculatedCart::class);

        $context = $this->createMock(ShopContext::class);

        $context->expects($this->any())
            ->method('getShippingLocation')
            ->will($this->returnValue(
                ShippingLocation::createFromCountry(
                    new CountryBasicStruct()
                )
            ));

        $this->assertFalse(
            $rule->match($cart, $context, new StructCollection())->matches()
        );
    }

    private function createAddress(string $street): CustomerAddressBasicStruct
    {
        $address = new CustomerAddressBasicStruct();
        $state = new CountryStateBasicStruct();
        $country = new CountryBasicStruct();
        $state->setCountryId('SWAG-AREA-COUNTRY-ID-1');

        $address->setStreet($street);
        $address->setCountry($country);
        $address->setCountryState($state);

        return $address;
    }
}
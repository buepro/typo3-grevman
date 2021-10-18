<?php

declare(strict_types=1);

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Grevman\Tests\Unit\Domain\Model;

use Buepro\Grevman\Domain\Model\Member;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class PersonNameTraitTest extends UnitTestCase
{
    public function getScreenNameDataProvider(): array
    {
        return [
            'no name' => ['', '', ''],
            'first name only' => ['Max', '', 'Max'],
            'last name only' => ['', 'Meier', 'Meier'],
            'first and last name' => ['Max', 'Meier', 'Max Meier'],
        ];
    }

    /**
     * @dataProvider getScreenNameDataProvider
     * @test
     */
    public function getScreenName(string $firstName, string $lastName, string $expected)
    {
        $member = new Member();
        $member->setFirstName($firstName)->setLastName($lastName);
        $this->assertSame($expected, $member->getScreenName());
    }
}

<?php

declare(strict_types=1);

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Grevman\Tests\Functional\Domain\Model;

use Buepro\Grevman\Domain\Repository\MemberRepository;
use Buepro\Grevman\Tests\Functional\FunctionalFrontendTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;

class MemberTest extends FunctionalFrontendTestCase
{
    protected $coreExtensionsToLoad = [
        'impexp',
        'seo',
        'felogin',
    ];

    protected $testExtensionsToLoad = [
        'typo3conf/ext/bootstrap_package',
        'typo3conf/ext/container',
        'typo3conf/ext/container_elements',
        'typo3conf/ext/pizpalue',
        'typo3conf/ext/grevman',
    ];

    /**
     * @var MemberRepository
     */
    protected $memberRepository;

    /**
     * Sets up this test case.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->importDataSet(ORIGINAL_ROOT . 'typo3conf/ext/grevman/Tests/Functional/Fixtures/Site/db.xml');

        $this->setUpFrontendSite(1);
        $this->setupFrontendController(223);

        $this->memberRepository = $this->getContainer()->get(MemberRepository::class);
    }

    /**
     * @test
     */
    public function getIsLeader(): void
    {
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);
        $this->memberRepository->setDefaultQuerySettings($querySettings);
        [$leader] = $this->memberRepository->findByUsername('leader1');
        $this->assertTrue($leader->getIsLeader());
        [$participant] = $this->memberRepository->findByUsername('participant1');
        $this->assertFalse($participant->getIsLeader());
    }
}

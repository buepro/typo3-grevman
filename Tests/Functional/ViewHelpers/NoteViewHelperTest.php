<?php

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Grevman\Tests\Functional\ViewHelpers;

use Buepro\Grevman\Domain\Model\Member;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class NoteViewHelperTest extends FunctionalTestCase
{
    private const TEMPLATE_PATH = 'EXT:grevman/Tests/Functional/Fixtures/ViewHelpers/NoteViewHelper/Template.html';

    /**
     * @var bool Speed up this test case, it needs no database
     */
    protected $initializeDatabase = false;

    /**
     * @var array
     */
    protected $testExtensionsToLoad = [
        'typo3conf/ext/grevman',
    ];

    /**
     * @test
     */
    public function throwExceptionOnMissingEvent(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionCode(1634201039234);

        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename(self::TEMPLATE_PATH);
        $view->assign('member', new Member());
        $view->render();
    }
}

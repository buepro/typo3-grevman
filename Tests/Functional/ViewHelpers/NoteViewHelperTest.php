<?php

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Grevman\Tests\Functional\ViewHelpers;

use Buepro\Grevman\Domain\Model\Event;
use Buepro\Grevman\Domain\Model\Member;
use Buepro\Grevman\Domain\Model\Note;
use Buepro\Grevman\ViewHelpers\NoteViewHelper;
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

    public function renderDataProvider(): array
    {
        $note11 = 'Happy to join!';
        $note21 = 'Not sure yet...';
        $note22 = 'Happy to join, too!';
        $member1Prophecy = $this->prophesize(Member::class);
        $member1Prophecy->getUid()->willReturn(1);
        $member1 = $member1Prophecy->reveal();
        $member2Prophecy = $this->prophesize(Member::class);
        $member2Prophecy->getUid()->willReturn(2);
        $member2 = $member2Prophecy->reveal();
        $member3Prophecy = $this->prophesize(Member::class);
        $member3Prophecy->getUid()->willReturn(3);
        $member3 = $member3Prophecy->reveal();
        $eventProphecy = $this->prophesize(Event::class);
        $eventProphecy->getNotes()->willReturn([
            (new Note())->setMember($member1)->setText($note11),
            (new Note())->setMember($member2)->setText($note21),
            (new Note())->setMember($member2)->setText($note22),
        ]);
        $event = $eventProphecy->reveal();
        return [
            'no event no member' => [
                null,
                null,
                null,
                null,
                [''],
            ],
            'no member' => [
                $event,
                null,
                null,
                null,
                [''],
            ],
            'no event' => [
                null,
                $member1,
                null,
                null,
                [''],
            ],
            'member1 notes' => [
                $event,
                $member1,
                null,
                null,
                [htmlspecialchars($note11)],
            ],
            'member2 notes' => [
                $event,
                $member2,
                null,
                null,
                [htmlspecialchars($note21 . NoteViewHelper::DEFAULT_GLUE . $note22)],
            ],
            'member3 notes' => [
                $event,
                $member3,
                null,
                null,
                [''],
            ],
            'member2 notes with glue' => [
                $event,
                $member2,
                ' *** ',
                null,
                [htmlspecialchars($note21 . ' *** ' . $note22)],
            ],
            'member2 notes with empty glue' => [
                $event,
                $member2,
                '',
                null,
                [htmlspecialchars($note21 . $note22)],
            ],
            'member2 notes with as' => [
                $event,
                $member2,
                null,
                '_notes',
                [htmlspecialchars('Notes: ' . $note21 . NoteViewHelper::DEFAULT_GLUE . $note22)],
            ],
            'member2 notes with glue and as' => [
                $event,
                $member2,
                ' *** ',
                '_notes',
                [htmlspecialchars('Notes: ' . $note21 . ' *** ' . $note22)],
            ],
        ];
    }

    /**
     * @dataProvider renderDataProvider
     * @test
     */
    public function render(?Event $event, ?Member $member, ?string $glue, ?string $as, array $expected): void
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename(self::TEMPLATE_PATH);
        $view->assignMultiple([
            'event' => $event,
            'member' => $member,
        ]);
        if ($glue !== null) {
            $view->assign('glue', $glue);
        }
        if ($as !== null) {
            $view->assign('as', $as);
        }

        $result = array_values(GeneralUtility::trimExplode(LF, $view->render(), true));
        // Correct result in case vh returned ''
        if (!(bool)$result) {
            $result[] = '';
        }
        self::assertEquals($expected, $result);
    }
}

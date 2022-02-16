<?php

/*
 * This file is part of the package buepro/grevman.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Grevman\Tests\Functional\ViewHelpers\Event;

use Buepro\Grevman\Domain\Model\Event;
use Buepro\Grevman\Domain\Model\Member;
use Buepro\Grevman\Domain\Model\Registration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class ParticipationViewHelperTest extends FunctionalTestCase
{
    private const TEMPLATE_PATH = 'EXT:grevman/Tests/Functional/Fixtures/ViewHelpers/RegistrationViewHelper/Template.html';

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

    public function renderDataProvider()
    {
        $memberProphecy = $this->prophesize(Member::class);
        $memberProphecy->getEmail()->willReturn('member@grevman.ch');
        $member = $memberProphecy->reveal();
        $altMember = $this->prophesize(Member::class)->reveal();
        $registrationProphecy = $this->prophesize(Registration::class);
        $registrationProphecy->getState()->willReturn(Registration::REGISTRATION_CONFIRMED);
        $registrationProphecy->getMember()->willReturn($member);
        $registration = $registrationProphecy->reveal();
        $eventProphecy = $this->prophesize(Event::class);
        $eventProphecy->getRegistrationForMember($member)->willReturn($registration);
        $eventProphecy->getRegistrationForMember($altMember)->willReturn(null);
        return [
            'no event, no member, no property, no as' => [null, null, null, null, ['']],
            'no event, member, no property, no as' => [null, $member, null, null, ['']],
            'event, no member, no property, no as' => [$eventProphecy->reveal(), null, null, null, ['']],
            'no event, no member, no property, as' => [null, null, null, '_reg', ['registration.member.email:']],
            'no event, no member, state, no as' => [null, null, 'state', null, [(string)Registration::REGISTRATION_UNDEFINED]],
            'no event, no member, state, as' => [null, null, 'state', '_reg', ['_reg.state: ' . Registration::REGISTRATION_UNDEFINED]],
            'get registration for registered member' => [$eventProphecy->reveal(), $member, null, null, ['']],
            'get registration for unregistered member' => [$eventProphecy->reveal(), $altMember, null, null, ['']],
            'get state for registered member' => [$eventProphecy->reveal(), $member, 'state', null, [Registration::REGISTRATION_CONFIRMED]],
            'get state for unregistered member' => [$eventProphecy->reveal(), $altMember, 'state', null, [Registration::REGISTRATION_UNDEFINED]],
            'get registration for registered member with as' => [$eventProphecy->reveal(), $member, null, '_reg', ['registration.member.email: ' . $member->getEmail()]],
            'get state for registered member with as' => [$eventProphecy->reveal(), $member, 'state', '_reg', ['_reg.state: ' . Registration::REGISTRATION_CONFIRMED]],
        ];
    }

    /**
     * @dataProvider renderDataProvider
     * @test
     */
    public function render(?Event $event, ?Member $member, ?string $property, ?string $as, array $expected)
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename(self::TEMPLATE_PATH);
        $view->assignMultiple([
            'event' => $event,
            'member' => $member,
        ]);
        if ($property !== null) {
            $view->assign('property', $property);
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

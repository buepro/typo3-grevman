# 1.0.0

## FEATURE

- 06a203f [FEATURE] Add event recurrence
- 8612afb [FEATURE] Add development site
- ccee7bb [FEATURE] Allow to directly unregister for an event
- 90ccc56 [FEATURE] Add note hint in matrix view

## TASK

- e28da59 [TASK] Remove obsolete dependency to vhs
- b5ec006 [TASK] Drop support for php 7.2
- 0ac9392 [TASK] Add bk2k/extension-helper
- 149be9c [TASK] Move site resources to Build directory
- ae28f07 [TASK] Adjust .gitattributes
- 236bb3e [TASK] Correct composer support config
- 8c852c3 [TASK] Update db
- 0f84dce [TASK] Refactor matrix member axis
- d323802 [TASK] Improve event scrolling in matrix view
- 7486efa [TASK] Adjust column width in matrix view
- f7abd1b [TASK] Open restrictions for view helpers
- a800985 [TASK] Add MemberTest
- 7f0e6b2 [TASK] Add PersonNameTrait
- 3ab5953 [TASK] Add tests for domain model Event
- df5e032 [TASK] Update composer ddev:delete command
- e84c1cf [TASK] Add functional test for RegistrationViewHelper
- b233a6f [TASK] Add functional test for NoteViewHelper
- 03eca5b [TASK] Add functional test
- 8f2cb1c [TASK] Add environment variable for debugging
- ac2e024 [TASK] Move var and config folder to .build
- ba7ed4e [TASK] Add TYPO3 v11 to package dependency
- 644c689 [TASK] Add php unit test
- 961e604 [TASK] Migrate deprecated domain models (TYPO3 v12)
- f8cda59 [TASK] Remove ci support for PHP v8
- 022c9f9 [TASK] Add ci support for TYPO3 v11.5
- 067af4d [TASK] Add ci
- b581826 [TASK] Add php linter
- d5e4c6f [TASK] Strictify php code
- ddb34b1 [TASK] Set minimal php version for TYPO3 10.4
- 7201bcc [TASK] Add PHPStan
- f9883ef [TASK] Clean up db, set user passwords, use new assets
- 422a4ad [TASK] Add test assets
- d0a085d [TASK] Apply coding guideline
- 0346eac [TASK] Add code sniffer `php-cs-fixer`
- ac4f819 [TASK] Show no event information in view
- 41977f1 [TASK] Remove animation from registration state indicator
- fed5df0 [TASK] Exclude rendered doc from git
- 09ba3f3 [TASK] Rise to dev version
- ffa3425 [TASK] Remove obsolete todo file
- 47ad6f1 [TASK] Cache the list and matrix view
- cb8fd6e [TASK] Remove registration controller
- 707ed83 [TASK] Remove tests
- 832e434 [TASK] Add customized icons
- b113e18 [TASK] Rise to dev version
- 1046ead [TASK] Change git definition for line endings
- b80655f [TASK] Adjust TCA for fe-users
- e19a9ba [TASK] Add button to show matrix from within detail view
- e1e1a43 [TASK] Rearrange action buttons on detail view
- 209583b [TASK] Set matrix view as default, don't cache list view
- 1bd675a [TASK] Fix php code with code sniffer
- 6e24d22 [TASK] Encapsulate TCA from extension builder
- c5bb0f4 [TASK] Cache list and matrix view
- 45335df [TASK] Add TSconfig to preview event
- 0f14ff3 [TASK] Add TSconfig to clear page cache
- 88739ce [TASK] Rename registration status to state
- ff33e87 [TASK] Create matrix view
- 35905f3 [TASK] Allow to query properties with registration view helper
- 875fe6a [TASK] Set ordering for event repository
- 878af21 [TASK] Add registration indicator to event list view
- a5d7a7d [TASK] Add member note functionality
- 8ad359e [TASK] Adjust style from event views
- a24b4b8 [TASK] Create css with sass
- 033f4bd [TASK] Remove obsolete mail config
- 263d4cf [TASK] Refactor ts constants
- c8de316 [TASK] Refactor terminology. Use leader instead of trainer.
- 433caa3 [TASK] Add event email notification
- 860a071 [TASK] Add notes to detail view
- c8620b2 [TASK] Add guest registrations to detail view
- 7f24482 [TASK] Add spontaneous registrations to detail view
- d8ca981 [TASK] Add event configuration
- cc6e0e9 [TASK] Adjust TCA for note
- f2129a4 [TASK] Create (un-)register actions
- 986c1a6 [TASK] Add registration info to detail view
- 12dbda4 [TASK] Create domain transfer objects for event
- fd39253 [TASK] Update composer config
- 8dd2f87 [TASK] Query upcoming events
- 0592f76 [TASK] Adjust TCA
- 1d46fc2 [TASK] Style event detail template
- f96661e [TASK] Create partial to format event period
- 3f08e31 [TASK] Style event list template
- 39ce0c3 [TASK] Configure extension builder
- c9d6929 [TASK] Modify TCA for event
- b7900b7 [TASK] Add license and code sniffer
- 96c1082 [TASK] Replace TYPO3_MODE by TYPO3
- 8e315a1 [TASK] Add event properties and plugin
- cda8dc2 [TASK] Modify TCA for registration
- 2421557 [TASK] Modify TCA for note
- 889249a [TASK] Add TsConfig for members
- 21b104a [TASK] Modify TCA for member
- e28d9b2 [TASK] Modify TCA for group
- 25c158a [TASK] Add documentation
- 2f96d68 [TASK] Add domain model `guest`
- 116027a [TASK] Correct db definition
- f4b38c5 [TASK] Create base model with extension builder
- b39ddf7 [TASK] Add git config

## BUGFIX

- 240b940 [BUGFIX] Fix deleted member issues
- 91f341c [BUGFIX] Cleanup spontaneous registrations in matrix view
- fc68b82 [BUGFIX] Show event detail view
- 44000b5 [BUGFIX] Correct issue reference in documentation
- 38d2ef2 [BUGFIX] Correct svg icon extension

## MISC

- fbdae06 [DOCS] Correct syntax error, update matrix view image
- 193f602 [DOCS] Streamline introduction
- dcc75ec [DOCS] Extend development manual
- 70e34d7 [DOCS] Correct code blocks
- 11c6223 [DOCS] Correct syntax errors
- 7637502 [DOCS] Correct syntax errors
- 1342cef Update Crowdin configuration file
- 73e403a [DOCS] Complement the administrator chapter
- 498e6cb [DOCS] Add section `Acknowledgement`
- eca5ea6 [DOCS] Add images and update settings
- d42091a [DOCS] Improve admin chapter
- a7077cf [DOCS] Add installation chapter

## Contributors

- Roman Büchler
- buepro
- roman


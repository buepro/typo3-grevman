.. include:: ../Includes.txt


.. _develop:

===========
Development
===========

Work with site
==============

Installation
------------

#. Clone the repository

   .. code-block:: bash

      git clone https://github.com/buepro/typo3-grevman.git

#. Create site

   .. code-block:: bash

      composer ddev:install

Development
-----------

Use the ddev container during development. Like this the system environment
is being respected. E.g.:

.. code-block:: bash

   ddev composer update

Uninstallation
--------------

To remove the development site use:

.. code-block:: bash

   composer ddev:delete


Create tests
============

Create test db
--------------

.. rst-class:: bignums

#. Export db using preset **grevman-test-static**

#. Remove the `header`-tag

#. Rename the `T3RecordDocument` to `dataset`

#. Remove `records`-tag

#. Remove the following tags: `lockToDomain`-, `disableIPlock`, `createdbyaction`,
   `usergroup_cached_list`, `t3ver_.*?`, `legacy_overlay_uid`, `sitetitle`

   code-block:: bash

   <[TagName]>.*?</[TTagName]>\n\s*

#. Remove `related`-tag using the following regular expression

   .. code-block:: bash

      <related[\s\w="]*>[\w\W\s]*?</related>\\n

#. Create table tags using the following regular expression

   **Search:**

   .. code-block:: bash

      <tablerow index="(\w*)[:\w\s="]*>[\n\s]+<fieldlist index="data"
      type="array">([\w\W\s]+?)<\/fieldlist>[\n\s]*<\/tablerow>

   **Replace:**

   .. code-block:: bash

      <$1>$2</$1>

#. Create columns tag using the following regular expression

   **Search:**

   .. code-block:: bash

      <field index="(\w+)"[\s\w\d="]*>([\w\W]*?)</field>

   **Replace:**

   .. code-block:: bash

      <$1>$2</$1>


Add needed extensions
---------------------

It might be needed to add the following extensions:

.. code-block:: php

   protected $coreExtensionsToLoad = [
      'impexp',
      'seo',
      'felogin',
   ];

.. code-block:: php

   protected $testExtensionsToLoad = [
      'typo3conf/ext/vhs',
      'typo3conf/ext/bootstrap_package',
      'typo3conf/ext/container',
      'typo3conf/ext/container_elements',
      'typo3conf/ext/pizpalue',
      'typo3conf/ext/grevman',
   ];

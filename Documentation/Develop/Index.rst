.. include:: ../Includes.txt


.. _develop:

================
Development site
================

Install site
============

#. Clone the repository

   .. code-block:: bash

      git clone https://github.com/buepro/typo3-grevman.git

#. Create site

   .. code-block:: bash

      composer ddev:install

Develop
=======

Use the ddev container during development. Like this the system environment
is being respected. E.g.:

.. code-block:: bash

   ddev composer update

Remove site
===========

To remove the development site use:

.. code-block:: bash

   composer ddev:delete

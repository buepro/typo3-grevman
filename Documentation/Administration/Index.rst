.. include:: ../Includes.txt


.. _administration:

==============
Administration
==============

Installation
============

Carry out the following standard installation steps:

#. Add static template to page where the plugin will be used
#. Add static pagesTS configuration `Grevman: General storage` to the page
   properties (resources tab - Page TSconfig) from the parent storage folder
   (for the settings to be applied to the child folders too).


Configuration
=============

TS constants
------------

*  `storagePid`: List first the `uid` from the folder where the grevman records
   are stored.

PageTS config
-------------

The following individual configurations are needed (replace values as needed):

All storage
~~~~~~~~~~~

.. code-block:: typoscript

   TCEFORM {
     tx_grevman_domain_model_group {
       members {
         # pid's from members
         PAGE_TSCONFIG_IDLIST = 225, 226
       }
     }
     tx_grevman_domain_model_registration {
       member {
         # pid's from members
         PAGE_TSCONFIG_IDLIST = 225, 226
       }
     }
   }

Trainers storage
~~~~~~~~~~~~~~~~

.. code-block:: typoscript

   TCAdefaults {
     fe_users {
       # Default user group
       usergroup = 3
     }
   }

Participants storage
~~~~~~~~~~~~~~~~~~~~

.. code-block:: typoscript

   TCAdefaults {
     fe_users {
       # Default user group
       usergroup = 4
     }
   }

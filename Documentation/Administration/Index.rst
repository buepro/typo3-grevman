.. include:: ../Includes.txt


.. _administration:

==============
Administration
==============

Installation
============

Carry out the following standard installation steps:

#. Create page structure. Typically it consists of the following pages:

   |  ┡ 🗌 Grevman
   |  ┊ ┃ 🗌 Login
   |  ┊ ┣ 🖿 Participants
   |  ┊ ┣ 🖿 Leaders
   |  ┊ ┗ 🖿 Data

   .. note::
      In the above page structure the pages/folders are used as following:

      - Page `Grevman` holds the grevman plugin.
      - Page `Login` holds the fe login plugin.
      - Folder `Participants` holds the fe users considered to be participants.
        The fe user group for the participants might be stored here too.
      - Folder `Leaders` holds the fe users considered to be leaders (e.g.
        trainers). The fe user group for the leaders might be stored here too.
      - Folder `Data` holds the remaining grevman records as events, groups,
        guests, notes and registrations.

#. Add static template to page where the plugin will be located. In the above
   page structure this would be the page `Grevman`.

#. Add static pagesTS configuration `Grevman: General storage` to the page
   properties (resources tab - Page TSconfig) from the parent storage page
   (for the settings to be applied to the child folders too). In the above
   page structure this would be the page `Grevman`.


Configuration
=============

PageTS config
-------------

The following individual configurations are needed (replace values as needed):

Cache and preview
~~~~~~~~~~~~~~~~~

The following configuration is needed for the following:

-  To clear the cache from the page where the plugin is located upon changing a
   grevman related record.

-  To define the page shown upon pressing the view button when editing a grevman
   record.

.. code-block:: typoscript

   TCEMAIN {
     # Clear the cache for page 359 when saving a record in the storage branch
     clearCacheCmd = 359
     preview {
       tx_grevman_domain_model_event {
         # Uid from page where plugin is located
         previewPageId = 359
       }
     }
   }

.. note::
   In the above page structure this would be assigned to the page `Grevman` and
   the value `359` would be replaced by the uid from the page `Grevman`.

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

.. note::
   In the above page structure this would be assigned to the page `Grevman` and
   the values `225, 226` would be replaced by the uid values from the pages
   `Participants` and `Leaders`.

Participants storage
~~~~~~~~~~~~~~~~~~~~

.. code-block:: typoscript

   TCAdefaults {
     fe_users {
       # Default user group
       usergroup = 3
     }
   }

.. note::
   In the above page structure this would be assigned to the page `Participants`
   where the value `3` would be replaced with the uid from the participants
   fe user group.

Leaders storage
~~~~~~~~~~~~~~~~

.. code-block:: typoscript

   TCAdefaults {
     fe_users {
       # Default user group
       usergroup = 4
     }
   }

.. note::
   In the above page structure this would be assigned to the page `Leaders`
   where the value `4` would be replaced with the uid from the leaders
   fe user group.

TS constants
------------

Define the plugin related TS constants with the constant editor.

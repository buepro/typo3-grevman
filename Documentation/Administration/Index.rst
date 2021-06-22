.. include:: ../Includes.txt


.. _administration:

==============
Administration
==============

PageTS config
-------------

For the storage folders the static pagesTS configuration
`Grevman: Page TS config` is available and should be embedded through the page
properties (resources tab - Page TSconfig).

Further the following individual configurations are needed (replace values as
needed):

All storage
~~~~~~~~~~~

.. code-block:: typoscript

   TCEFORM {
     tx_grevman_domain_model_group {
       members {
         PAGE_TSCONFIG_IDLIST = 225, 226
       }
     }
   }

Trainers storage
~~~~~~~~~~~~~~~~

.. code-block:: typoscript

   TCAdefaults {
     fe_users {
       usergroup = 3
     }
   }

Participants storage
~~~~~~~~~~~~~~~~~~~~

.. code-block:: typoscript

   TCAdefaults {
     fe_users {
       usergroup = 4
     }
   }

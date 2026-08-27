moodle-mod_flexaccess
=====================

[![Moodle Plugin CI](https://github.com/ralferlebach/moodle-mod_flexaccess/actions/workflows/moodle-plugin-ci-main.yml/badge.svg?branch=main)](https://github.com/ralferlebach/moodle-mod_flexaccess/actions?query=workflow%3A%22Moodle+Plugin+CI+Main%22+branch%3Amain)

The FlexAccess activity lets a temporary visitor turn their account into a permanent one from inside the course, at the moment they decide their progress is worth keeping.

FlexAccess is not a single plugin but a set of four that work as one system. They are released
together, carry the same version number and declare each other as dependencies, so they can only be
installed and updated as a set.

* **auth_flexaccess** provides the identity layer: it creates the temporary accounts, converts them into permanent ones, issues one-time login links and runs the central, rate-limited mail queue that all four plugins send through.
* **enrol_flexaccess** decides who may enter a course and how: it owns the access policy across site, category and course, enforces capacity, access windows, access keys and role or cohort restrictions.
* **mod_flexaccess** is the in-course entry point for keeping an account: it lets a temporary visitor convert their own account into a permanent one at the point in the course the teacher chooses.
* **tool_flexaccess** is the operator's view: account overview, mail queue, site and category policies, invitations, campaigns and printable anonymous access lists.

This README documents **mod_flexaccess** - the third bullet point above. The other three plugins are
documented in their own repositories.

Because the responsibilities are split this way, no rule exists twice: access is decided in one
place, identity is handled in another, and every mail leaves through one queue. That is also why a
partial installation does not work - a missing sibling means a missing part of the mechanism.


Requirements
------------

This plugin requires Moodle 4.5+

It also requires the other FlexAccess plugins. All four are released together and must be installed
in the same version (currently 1.0.0-RC1 / 2026082700):

* **auth_flexaccess (FlexAccess authentication)** - required dependency, declared in version.php\
  https://github.com/ralferlebach/moodle-auth_flexaccess
* **enrol_flexaccess (FlexAccess enrolment)** - required dependency, declared in version.php\
  https://github.com/ralferlebach/moodle-enrol_flexaccess
* **tool_flexaccess (FlexAccess administration)** - part of the same set; install it as well to use the complete feature range\
  https://github.com/ralferlebach/moodle-tool_flexaccess


Motivation for this plugin
--------------------------

A visitor who entered a course temporarily has no obvious place to say "I would like to keep this". Sending them back to a registration page breaks the flow and loses the connection to what they have already done.

This activity puts that step where it belongs: inside the course, at a point the teacher chooses - after the first self-assessment, at the end of a section, wherever it makes sense. The account is not replaced but personalised, so nothing is lost.


Installation
------------

Install the plugin like any other plugin to folder
/mod/flexaccess

See http://docs.moodle.org/en/Installing_plugins for details on installing Moodle plugins


Usage & Settings
----------------

After installing the plugin, it does not do anything to Moodle yet. Add a FlexAccess activity to a course whose FlexAccess enrolment method allows temporary access.

The activity has no site-wide settings. What a visitor sees depends on their account: temporary visitors are offered the activation form, while permanent users are simply told that their account is already secured.

If you want to learn more about using activity plugins in Moodle, please see https://docs.moodle.org/en/Activities.


Capabilities
------------

This plugin also introduces these additional capabilities:

* **mod/flexaccess:addinstance** - Add a FlexAccess activity to a course. By default, this is assigned to managers and editing teachers.
* **mod/flexaccess:view** - View the activity. By default, this is assigned to all participating roles.
* **mod/flexaccess:activate** - Convert one's own temporary account into a permanent one through this activity. By default, this is assigned to all participating roles.


Scheduled Tasks
---------------

This plugin does not add any additional scheduled tasks.


How this plugin works / Pitfalls
--------------------------------

The activity classifies the current user through the FlexAccess authentication API. A temporary visitor is shown a short form asking for an e-mail address, a name and a password; a permanent user sees a confirmation instead.

The activation itself is carried out by auth_flexaccess, so it follows exactly the same rules as every other path: e-mail verification if the site requires it, the site password policy, and the same account. After activation the user receives a mail with their username and where to log in - the username was generated for the temporary account and would otherwise be unknown to them.

**Pitfall:** the activity is only useful in courses that actually offer temporary access. In a course without it, every user is already permanent and the activity has nothing to do.


Theme support
-------------

This plugin is developed and tested on Moodle Core's Boost theme.
It should also work with Boost child themes, including Moodle Core's Classic theme. However, we can't support any other theme than Boost.


Plugin repositories
-------------------

This plugin is not published in the Moodle plugins repository.

The latest development version can be found on Github:
https://github.com/ralferlebach/moodle-mod_flexaccess


Bug and problem reports / Support requests
------------------------------------------

This plugin is carefully developed and thoroughly tested, but bugs and problems can always appear.

Please report bugs and problems on Github:
https://github.com/ralferlebach/moodle-mod_flexaccess/issues

We will do our best to solve your problems, but please note that due to limited resources we can't always provide per-case support.


Feature proposals
-----------------

Due to limited resources, the functionality of this plugin is primarily implemented for our own local needs and published as-is to the community. We are aware that members of the community will have other needs and would love to see them solved by this plugin.

Please issue feature proposals on Github:
https://github.com/ralferlebach/moodle-mod_flexaccess/issues

Please create pull requests on Github:
https://github.com/ralferlebach/moodle-mod_flexaccess/pulls

We are always interested to read about your feature proposals or even get a pull request from you, but please accept that we can handle your issues only as feature _proposals_ and not as feature _requests_.


Moodle release support
----------------------

Due to limited resources, this plugin is only maintained for the most recent major release of Moodle as well as the most recent LTS release of Moodle. Bugfixes are backported to the LTS release. However, new features and improvements are not necessarily backported to the LTS release.

Apart from these maintained releases, previous versions of this plugin which work in legacy major releases of Moodle are still available as-is without any further updates in the Moodle Plugins repository.

There may be several weeks after a new major release of Moodle has been published until we can do a compatibility check and fix problems if necessary. If you encounter problems with a new major release of Moodle - or can confirm that this plugin still works with a new major release - please let us know on Github.

This plugin is designed to be compatible with all currently supported versions of Moodle, leveraging its latest APIs. However, if you are using a legacy version of Moodle, we kindly advise against installing or using this plugin. Instead, we strongly recommend updating your Moodle instance to a supported version to ensure security and compliance with current technological standards. Thank you for your understanding.


Translating this plugin
-----------------------

This Moodle plugin is provided with English and German language packs only. Translations into other languages must be managed through AMOS (https://lang.moodle.org), where they will become part of Moodle's official language pack.

As the plugin creator, we continue to maintain the German translation. For all other languages, we kindly ask you to contribute your translations directly in AMOS. These contributions will be reviewed by Moodle's official language pack maintainers before being included in the official repository.

Thank you for supporting the global Moodle community!


Right-to-left support
---------------------

This plugin has not been tested with Moodle's support for right-to-left (RTL) languages.
If you want to use this plugin with a RTL language and it doesn't work as-is, you are free to send us a pull request on Github with modifications.


Maintainers
-----------

The plugin is maintained by\
Ralf Erlebach

Copyright
---------

The copyright of this plugin is held by\
Ralf Erlebach

Individual copyrights of individual developers are tracked in PHPDoc comments and Git commits.

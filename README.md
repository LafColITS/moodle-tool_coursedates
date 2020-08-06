
Set course dates
==========================

[![Build Status](https://api.travis-ci.org/LafColITS/moodle-tool_coursedates.png)](https://api.travis-ci.org/LafColITS/moodle-tool_coursedates)

This admin tool allows managers to set the start and end dates for all courses in a category, including subcategories. This is useful when your student information system integration does not set these automatically, or when you have courses which were loaded prior to Moodle 3.2 and do not have end dates set.

Requirements
------------
- Moodle 3.7 (build 2019052000 or later)

Installation
------------
Copy the coursedates folder into your /admin/tool directory and visit your Admin Notification page to complete the installation.

Usage
-----
The tool adds a link to the category navigation block, "Set course dates." The user will be taken to a page with date pickers for start and end dates. Each is optional, but you must select at least one to set. On confirmation, Moodle will create an "[adhoc task](https://docs.moodle.org/dev/Task_API#Adhoc_tasks)" to set all the dates in the background. This requires that cron be enabled.

If you're using Boost or a similar theme, you may need to access /course/index.php directly, navigate to the desired category, then click the edit cog at top right to reach the link.

Configuration
-------------
The tool has no options but does require, as mentioned above, that cron be running.

Author
------
Charles Fulton (fultonc@lafayette.edu)

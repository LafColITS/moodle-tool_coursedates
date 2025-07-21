
Set course dates
==========================

![Moodle Plugin CI](https://github.com/LafColITS/moodle-tool_coursedates/workflows/Moodle%20Plugin%20CI/badge.svg)

This admin tool allows managers to set the start and end dates for all courses in a category, including subcategories. This is useful when your student information system integration does not set these automatically, or when you have courses which were loaded prior to Moodle 3.2 and do not have end dates set.

Requirements
------------
- Moodle 4.1 (build 2022112800 or later)

Installation
------------
Copy the coursedates folder into your /admin/tool directory and visit your Admin Notification page to complete the installation.

Usage
-----
Navigate to Site administration > Courses > Manage courses and categories. The tool adds a navigation tab labeled "Set course dates".  Make sure you're on the desired category before proceeding. Clicking on "Set course dates" will take you to a page with date pickers for start and end dates. Each is optional, but you must select at least one to set. On confirmation, Moodle will create an "[adhoc task](https://docs.moodle.org/dev/Task_API#Adhoc_tasks)" to set all the dates in the background. This requires that cron be enabled.

Configuration
-------------
The tool has no options but does require, as mentioned above, that cron be running.

Author
------
Charles Fulton (fultonc@lafayette.edu)

@tool @tool_coursedates
Feature: Set end dates for week format courses
  In order to set the end date for a course with a weeks format
  As a manager
  I need to turn off the auto end date feature

  Background:
    Given the following "categories" exist:
      | name       | category | idnumber | visible |
      | Category 1 | 0        | CAT1     | 0       |
      | Category 2 | CAT1     | CAT2     | 0       |
      | Category 3 | CAT1     | CAT3     | 0       |
      | Category 4 | 0        | CAT4     | 0       |
    And the following "courses" exist:
      | fullname | shortname | category | visible | startdate  | format |
      | Course 1 | C1        | CAT2     | 1       | 1546300800 | weeks  |
      | Course 2 | C2        | CAT2     | 1       | 1546300800 | weeks  |
      | Course 3 | C3        | CAT3     | 1       | 1546300800 | weeks  |
      | Course 4 | C4        | CAT3     | 1       | 1546300800 | weeks  |
      | Course 5 | C5        | CAT4     | 1       | 1546300800 | weeks  |

  Scenario: Manager sets an end date for each course
    When I log in as "admin"
    And I am on course index
    And I follow "Category 1"
    When I navigate to "Set course dates" in current page administration
    And I set the following fields to these values:
      | id_enddate_enabled   | 1                      |
      | id_enddate_day       | 1                      |
      | id_enddate_month     | January                |
      | id_enddate_year      | 2020                   |
      | id_autoenddate       | Disable auto end dates |
    And I press "Confirm"
    And I should see "An adhoc task has been queued"
    And I trigger cron

@tool @tool_coursedates
Feature: The course dates tool allows a manager to set start and end dates in bulk
  In order to set course start and end dates in bulk
  As a manager
  I need to see all the categories

  Background:
    Given the following "categories" exist:
      | name       | category | idnumber | visible |
      | Category 1 | 0        | CAT1     | 0       |
      | Category 2 | CAT1     | CAT2     | 0       |
      | Category 3 | CAT1     | CAT3     | 0       |
      | Category 4 | 0        | CAT4     | 0       |
    And the following "courses" exist:
      | fullname | shortname | category | visible | startdate  |
      | Course 1 | C1        | CAT2     | 1       | 1546300800 |
      | Course 2 | C2        | CAT2     | 1       | 1546300800 |
      | Course 3 | C3        | CAT3     | 1       | 1546300800 |
      | Course 4 | C4        | CAT3     | 1       | 1546300800 |
      | Course 5 | C5        | CAT4     | 1       | 1546300800 |

  Scenario: Manager sets an end date for each course
    When I log in as "admin"
    And I am on course index
    And I follow "Category 1"
    When I navigate to "Set course dates" in current page administration
    And I set the following fields to these values:
      | id_enddate_enabled   | 1       |
      | id_enddate_day       | 1       |
      | id_enddate_month     | January |
      | id_enddate_year      | 2020    |
    And I press "Confirm"
    And I should see "An adhoc task has been queued"
    And I trigger cron

  Scenario: Manager sets start and end dates for each course
    When I log in as "admin"
    And I am on course index
    And I follow "Category 1"
    When I navigate to "Set course dates" in current page administration
    And I set the following fields to these values:
      | id_startdate_enabled | 1       |
      | id_startdate_day     | 1       |
      | id_startdate_month   | January |
      | id_startdate_year    | 2020    |
      | id_enddate_enabled   | 1       |
      | id_enddate_day       | 2       |
      | id_enddate_month     | January |
      | id_enddate_year      | 2020    |
    And I press "Confirm"
    And I should see "An adhoc task has been queued"
    And I trigger cron

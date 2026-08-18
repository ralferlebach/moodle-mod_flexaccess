@mod @mod_flexaccess
Feature: FlexAccess activation activity scaffold
  Scenario: Teacher can add the activity
    Given the following "courses" exist:
      | fullname | shortname | category |
      | FlexAccess course | FLEX | 0 |
    And I log in as "admin"
    And I am on "FlexAccess course" course homepage with editing mode on
    When I add a "FlexAccess activation" to section "1"
    And I set the following fields to these values:
      | Name | Activate account |
    And I press "Save and display"
    Then I should see "Nothing to activate here"

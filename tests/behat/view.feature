@mod @mod_flexaccess
Feature: FlexAccess activation activity
  In order to let temporary users convert their account
  As a teacher
  I need to add a FlexAccess activation activity to a course

  Scenario: A teacher can add a FlexAccess activation activity
    Given the following "courses" exist:
      | fullname          | shortname | category |
      | FlexAccess course | FLEX      | 0        |
    And I log in as "admin"
    When I add a "flexaccess" activity to course "FlexAccess course" section "1" and I fill the form with:
      | Name | Activate account |
    Then I should see "Activate account"

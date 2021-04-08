@tool @tool_edward
  Feature: Activity navigation involving activities with access restrictions
  In order to quickly switch to another activity that has access restrictions
  As a student
  I need to be able to use the activity navigation feature to access the activity after satisfying its access conditions

  Background:
    Given the following "users" exist:
      | username  | firstname  | lastname  | email                 |
      | teacher1  | Teacher    | 1         | teacher1@example.com  |
      | student1  | Student    | 1         | student1@example.com  |
    And the following "courses" exist:
      | fullname | shortname | format | enablecompletion |
      | Course 1 | C1        | topics | 1                |
    And the following "course enrolments" exist:
      | user      | course  | role            |
      | student1  | C1      | student         |
      | teacher1  | C1      | editingteacher  |
    And the following "activities" exist:
      | activity  | name    | intro                   | course | idnumber | section |
      | page      | page 1  | Test page description 1 | C1     | page1    | 0       |
      | page      | page 2  | Test page description 2 | C1     | page2    | 0       |
      | page      | page 3  | Test page description 3 | C1     | page3    | 0       |
      | page      | page 4  | Test page description 4 | C1     | page4    | 0       |

	@javascript
	Scenario: Activity navigation involving activities with access restrictions
	    Given I log in as "admin"
	    And I am on "Course 1" course homepage
	    And I follow "My First Moodle Plugin"
	    And I click the ".add" element
	    And I set the field "Name" to "Name 1"
      And I set the field "Description" to "Description 1"
	    And I press "Save changes"
	    And I click the ".add" element
	    And I set the field "Name" to "Name 2"
	    And I press "Save changes"
	    And I click the ".del" element
	    And I am on "Course 1" course homepage
	    And I follow "My First Moodle Plugin"
	    And I log out
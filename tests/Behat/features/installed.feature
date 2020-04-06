Feature: Is installed
    As a developer
    I want to have Behat features
    In order to do acceptance and E2E testing

    Background:
        Given I have a wordpress installation
            | name           | email             | username | password |
            | WordPress Test | admin@example.com | admin    | test     |

    Scenario: Installation works
        Given I am on "/"
        Then the application should be in test running mode
        And the response contains no PHP errors
        And the response contains no database errors
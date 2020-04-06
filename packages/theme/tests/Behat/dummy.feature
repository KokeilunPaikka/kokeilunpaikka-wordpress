Feature: Dummy feature to test that Behat can operate
    In order to have a testable theme
    As a developer
    I need to be able to run Behat tests properly

    Scenario: Homepage is accessible
        Given I am on the homepage
        Then the response status code should be 200

    @mink:browserstack
    Scenario: Try whether Browserstack works
        Given I am on the homepage
        Then I should not see text matching "404"


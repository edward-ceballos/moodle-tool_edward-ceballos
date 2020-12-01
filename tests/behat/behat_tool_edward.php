<?php

require_once(__DIR__ . '/../../../../../lib/behat/behat_base.php');

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Behat\Context\CustomSnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

class behat_tool_edward extends behat_base {
 
    protected function get_creatable_entities(): array {
        return [
            'things' => [
                'datagenerator' => 'thing',
                'required' => ['name']
            ],
        ];
    }
    /**
      * @When /^I click on the "(.*)" button$/
      */
    public function i_click_on_the_button($button) {
       // Simulates the user interaction (see Mink description below for more info)
       $this->getSession()->getPage()->pressButton($button);
    }

    /**
 * @Given I click the :arg1 element
 */
public function iClickTheElement($selector)
{
    $page = $this->getSession()->getPage();
    $element = $page->find('css', $selector);

    if (empty($element)) {
        throw new Exception("No html element found for the selector ('$selector')");
    }

    $element->click();
}
}
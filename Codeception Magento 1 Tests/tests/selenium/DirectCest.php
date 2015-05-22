<?php
use \SeleniumGuyTester;

class DirectCest
{
    public function _before(SeleniumGuyTester $I)
    {
    }

    public function _after(SeleniumGuyTester $I)
    {
    }

    protected function _login($I) {

        $I->amOnPage('/customer/account/login');
        $I->submitForm('#login-form',
            array(
                'login[username]' => 'pablo@ebizmarts.com',
                'login[password]' => 'q1w2e3r4',
            )
        );

        $I->amOnPage('/customer/account/index');
        $I->see('Hello, Pablo Benitez!');
    }

    // tests
    public function try_to_place_an_order_logged_in(SeleniumGuyTester $I)
    {
        $I->wantTo('Check place an order with Direct integration, no TOKEN as a logged in customer.');

        $this->_login($I);

        $I->amOnPage('index.php/prueba.html');
        $I->click('Add to Cart');
        $I->amOnPage('checkout/cart/');
        $I->click('Proceed to Checkout');
        $I->amOnPage('checkout/onepage/');
        $I->click('Continue');
        $I->click('//*[@id="s_method_freeshipping_freeshipping"]');
        $I->click('div#shipping-method-buttons-container button');
        $I->click('//*[@id="p_method_sagepaydirectpro"]');
        //$I->fillField(['id' => 'sagepaydirectpro_cc_owner'], "Hello World!");
        $I->selectOption('#sagepaydirectpro_cc_type', "MasterCard");
        $I->click('div#payment-buttons-container.buttons-set button.button');

    }

}
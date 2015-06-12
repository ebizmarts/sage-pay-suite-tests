<?php
use \SeleniumGuyTester;

require_once('BaseCest.php');
require_once('ConfigHelper.php');

class DirectCest{

    public function _before(SageTester $I){

        $config = new SagePayConfig();
        $config->resetConfig();
        $config->useDirect();
        $config->useToken(); //the Sage Tester is smart enough to don't use tokens when it is explicit.

        $I->login();

        $I->addProductToCart(); //all the tests begin logged in and with a product in the cart.

        $I->proceedToCheckout();

        $I->fillOnepageBillingInformation();
        $I->fillOnepageShippingMethod();


    }

    public function _after(SageTester $I){

        $I->fillOnepageOrderReview();

        $I->handle_3D();

        $I->checkPaymentSuccess();
        $I->checkInvoice();
    }

    /*
     * before place_3D_order
     * before place_3D_order_with_token
     */
    protected function use3D(){
        $config = new SagePayConfig();
        $config->use3D();
    }

    public function place_order(SageTester $I){

        $I->wantTo('Place a simple order without tokens');

        $I->fillOnepagePaymentInformation('direct',FALSE);

    }

    public function place_order_with_token(SageTester $I){

        $I->wantTo('Place a simple order with first token');

        $I->fillOnepagePaymentInformation('direct',TRUE); //default with token

    }

    public function place_3D_order(SageTester $I){

        $I->wantTo('Place a 3D order without tokens');

        $I->fillOnepagePaymentInformation('direct',FALSE); //default with token

    }

    public function place_3D_order_with_token(SageTester $I){

        $I->wantTo('Place a 3D order with first token');

        $I->fillOnepagePaymentInformation('direct',TRUE); //default with token

    }











    protected function _place_order_with_token(SageTester $I){ // this is just to have an overview of what is happening on each test

        $I->wantTo('Place a simple order with first token');

        $I->login();

        $I->addProductToCart();
        $I->proceedToCheckout();

        $I->fillOnepageBillingInformation();
        $I->fillOnepageShippingMethod();
        $I->fillOnepagePaymentInformation('direct',TRUE); //default with token
        $I->fillOnepageOrderReview();

        $I->handle_3D();

        $I->checkPaymentSuccess();
        $I->checkInvoice();

    }
}
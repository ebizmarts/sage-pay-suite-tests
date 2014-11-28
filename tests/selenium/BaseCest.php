<?php
use \SeleniumGuyTester;

class BaseCest {

    protected $tester = null;

    protected function init(SeleniumGuyTester $I){
        $this->tester = $I;
    }

    public function _before(SeleniumGuyTester $I)
    {
    }

    public function _after(SeleniumGuyTester $I)
    {
    }

    protected function _login() {

        $I = $this->tester; //just easy writing
        
        $I->amOnPage('/customer/account/login');

        //$I->fillField('login[username]', 'thiago@ebizmarts.com');
        //$I->fillField('login[password]', 'q1w2e3r4');
        $I->fillField('#email', 'thiago@ebizmarts.com');
        $I->fillField('#pass', 'q1w2e3r4');
        $I->click('#send2');

        //$I->amOnPage('/customer/account/index');
        $I->see('Hello');
    }

    protected function _add_product_to_cart($product_url=null){

        $I = $this->tester; //just easy writing

        if($product_url == null){
            $product_url = 'test.html';
        }

        $I->amOnPage($product_url);
        $I->click('.button.btn-cart');
        $I->see('was added to your shopping cart'); //now we are in the checkout page

    }

    protected function _proceed_to_checkout(){

        $I = $this->tester;
        if(strpos($I->grabFromCurrentUrl(),'checkout/cart')){
            $I->click('.button.btn-proceed-checkout.btn-checkout');
        }else{
            $I->amOnPage('checkout/onepage/');
        }

    }

    protected function _onepage_billing_information($guest = null, $differentShippingAddress = null, Array $address = null){

        $I = $this->tester;

        if(isset($guest)){
            //TODO
        }

        if(isset($differentShippingAddress)){
            //TODO
        }

        if($address){
            //TODO
        }

        $I->click('#billing-buttons-container .button');

    }

    protected function _onepage_shipping_information(Array $address = null){

        $I = $this->tester;

        //TODO
    }

    protected function _onepage_shipping_method($shippingSselector = null){

        $I = $this->tester;

        if(isset($shippingSselector)){
            //TODO
        }

        $I->click('#shipping-method-buttons-container .button');
    }

    protected function _onepage_payment_information($sagePayment, $token = TRUE, $paymentSelector = null){

        $I = $this->tester;

        if(is_string($sagePayment)){

            switch($sagePayment){

                case 'direct':

                    $I->click('#p_method_sagepaydirectpro');

                    $I->wait(1);

                    $hasTokens = $this->evaluate('canSeeElement','#tokencards-payment-sagepaydirectpro .tokensage');

                    if($hasTokens){

                        if($token){
                            if(is_numeric($token)){

                                $I->click('#sagepaydirectpro_tokencard_'.$token);
                                $I->wait(1);
                                $I->fillField('#sagepaydirectpro_token_cvv_'.$token, 123);

                            }else{ // default behaviour

                                $I->click('#tokencards-payment-sagepaydirectpro .tokensage li:first-child .input-radio');
                                $I->wait(1);
                                $I->fillField('#tokencards-payment-sagepaydirectpro .tokensage li:first-child .input-text.required-entry.validate-digits.cvv.tokencvv', 123);
                            }

                            break;
                        }else{
                            $I->click('#payment_form_sagepaydirectpro .addnew');
                            $I->wait(1);
                        }
                    }

                    $I->selectOption('#sagepaydirectpro_cc_type', "MasterCard");
                    $I->wait(1);

                    break;

                case 'server':

                    break;

                case 'form':

                    break;
            }



        }else{
            //TODO deal with the payment Selector
        }


        $I->click('#payment-buttons-container.buttons-set button.button');

    }



    public function _onepage_order_review(){
        $I = $this->tester;

        $I->click('#review-buttons-container .button.btn-checkout');
        $I->wait(8);
    }

    protected function handle_3D(){
        $I = $this->tester;
        if($this->is_3D()){

            $I->see('enter your password');
            $I->fillField("input[name='password']", 'password');
            $I->click("input[type='submit']");

            $I->wait(2);

            $I->acceptPopup();

            $I->wait(6);

            $I->see('YOUR ORDER HAS BEEN RECEIVED.');
        }
    }

    protected function checkPaymentSuccess(){
        $I = $this->tester;

        $this->handle_3D();

        $I->see('YOUR ORDER HAS BEEN RECEIVED.');


    }

    protected function is_3D(){
        return $this->evaluate('canSee','enter your password');
    }

    protected function evaluate($method,$param){
        $I = $this->tester;
        $I->wait(5);
        try{
            $I->{(string)$method}($param);
            return TRUE;
        }catch(Exception $e){
            return FALSE;
        }
    }



    // tests
    public function try_to_place_an_order_logged_in(SeleniumGuyTester $I){

        $this->init($I);


        $I->wantTo('Check place an order with Direct integration, no TOKEN as a logged in customer.');

        $this->_login();

        $this->_add_product_to_cart();
        $this->_proceed_to_checkout();


        $this->_onepage_billing_information();
        $this->_onepage_shipping_method();

        $this->_onepage_payment_information('direct'); //don't use token
        //$this->_onepage_payment_information('direct'); //use first token
        //$this->_onepage_payment_information('direct',40); //use token 40

        $this->_onepage_order_review();
        $this->checkPaymentSuccess();





    }

}
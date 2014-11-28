<?php

require_once('SeleniumGuyTester.php');

class SageTester extends \SeleniumGuyTester{

    public function login(){

        $I = $this; //just easy writing

        $I->amOnPage('/customer/account/login');

        $I->fillField('#email', 'thiago@ebizmarts.com');
        $I->fillField('#pass', 'q1w2e3r4');
        $I->click('#send2');

        $I->see('Hello');

    }

    public function addProductToCart($product_url=null){

        $I = $this; //just easy writing

        if($product_url == null){
            $product_url = 'test.html';
        }

        $I->amOnPage($product_url);
        $I->click('.button.btn-cart');
        $I->see('was added to your shopping cart'); //now we are in the checkout page

    }

    public function proceedToCheckout(){

        $I = $this;
        if(strpos($I->grabFromCurrentUrl(),'checkout/cart')){
            $I->click('.button.btn-proceed-checkout.btn-checkout');
        }else{
            $I->amOnPage('checkout/onepage/');
        }

    }

    public function addDiscount($discount=789){
        $I = $this;

        if(!strpos($I->grabFromCurrentUrl(),'checkout/cart')){
            $I->amOnPage('checkout/cart/');
        }

        $I->fillField('#coupon_code',$discount);
        $I->click('button[value=Apply]');

    }

    public function fillOnepageBillingInformation($guest = null, $differentShippingAddress = null, Array $address = null){

        $I = $this;

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

    public function fillOnepageShippingMethod($shippingSselector = null){

        $I = $this;

        if(isset($shippingSselector)){
            //TODO
        }

        $I->click('#shipping-method-buttons-container .button');
    }

    public function fillOnepagePaymentInformation($sagePayment, $token = null, $paymentSelector = null){

        $I = $this;

        if(is_string($sagePayment)){

            switch($sagePayment){

                case 'direct':

                    $I->click('#p_method_sagepaydirectpro');

                    $I->wait(1);

                    if($token === TRUE){
                        $I->seeElement('#tokencards-payment-sagepaydirectpro .tokensage');
                    }

                    $hasTokens = $this->evaluate('seeElement','#tokencards-payment-sagepaydirectpro .tokensage');

                    if($hasTokens){

                        if($token !== FALSE){
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
                    //TODO
                    break;

                case 'form':
                    //TODO
                    break;
            }



        }else{
            //TODO deal with the payment Selector
        }


        $I->click('#payment-buttons-container.buttons-set button.button');

    }



    public function fillOnepageOrderReview(){
        $I = $this;

        $I->click('#review-buttons-container .button.btn-checkout');
        $I->wait(25);
    }

    public function handle_3D(){
        $I = $this;
        if($this->is_3D()){

            $I->see('enter your password');
            $I->fillField("input[name='password']", 'password');
            $I->click("input[type='submit']");

            $I->wait(2);

            $I->acceptPopup(); //important, without this an javascript exception is raised.

            $I->wait(6);
        }
    }

    public function checkPaymentSuccess(){
        $I = $this;

        $I->see('YOUR ORDER HAS BEEN RECEIVED.');
    }

    public function checkInvoice(){
        $I = $this;

        $I->click('div h2 + p > a');

        $I->canSee('Order Information');
        $I->canSee('Invoices');
    }

    public function is_3D(){
        return $this->evaluate('see','enter your password');
    }

    public function evaluate($method,$param){
        $I = $this;
        try{
            $I->{(string)$method}($param);
            return TRUE;
        }catch(Exception $e){
            return FALSE;
        }
    }

}
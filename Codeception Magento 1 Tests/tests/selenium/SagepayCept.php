<?php

$I = new SeleniumGuyTester($scenario);
$I->wantTo('Check that home is working.');
$I->amOnPage('/');
$I->see('Default welcome msg!');
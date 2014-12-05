<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('perform actions and see result');

$I->amOnPage('localhost.exampleapp/');
$I->see('Mercadona');
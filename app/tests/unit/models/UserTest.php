<?php

namespace tests\models;

use app\models\Yii2User;

class UserTest extends \Codeception\Test\Unit
{
    public function testFindUserById()
    {
        expect_that($user = Yii2User::findIdentity(100));
        expect($user->username)->equals('admin');

        expect_not(Yii2User::findIdentity(999));
    }

    public function testFindUserByAccessToken()
    {
        expect_that($user = Yii2User::findIdentityByAccessToken('100-token'));
        expect($user->username)->equals('admin');

        expect_not(Yii2User::findIdentityByAccessToken('non-existing'));
    }

    public function testFindUserByUsername()
    {
        expect_that($user = Yii2User::findByUsername('admin'));
        expect_not(Yii2User::findByUsername('not-admin'));
    }

    /**
     * @depends testFindUserByUsername
     */
    public function testValidateUser($user)
    {
        $user = Yii2User::findByUsername('admin');
        expect_that($user->validateAuthKey('test100key'));
        expect_not($user->validateAuthKey('test102key'));

        expect_that($user->validatePassword('admin'));
        expect_not($user->validatePassword('123456'));        
    }

}

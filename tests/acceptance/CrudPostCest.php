<?php
namespace App\Test\Acceptance;

use AcceptanceTester;
use yii\helpers\Url;

class CrudPostCest
{
    public function _before(AcceptanceTester $I)
    {

    }

    public function seePage(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute(['/post/index', 'id' => 1]));
        $I->see('Пост' );
    }
}

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
        $I->amOnPage(Url::toRoute(['/post/index']));
        $I->see('Posts');
        $I->wantTo('Create new post');
        $I->click('Create Post');
        $I->see('Create Post');
        $title = 'title ' . uniqid();
        $text = 'text ' . uniqid();
        $I->fillField('Post[title]', $title);
        $I->fillField('Post[text]', $text);
        $I->selectOption('Post[author_id]', 'Добавить нового');
        $I->fillField('Post[author_name]', 'Петрович');
        $I->click('Save');
        $I->wait(1);
        $I->click('Update');
        $postId = $I->grabFromCurrentUrl('#&id=(\d+)#');
        $I->seeInField('Post[title]', $title);
        $I->seeInField('Post[text]', $text);
        $authorId = $I->grabValueFrom('Post[author_id]');
        $I->see('Петрович', "option[value='$authorId']");
        $newTitle = $title . '2';
        $newText = $text . '2';
        $I->fillField('Post[title]', $newTitle);
        $I->fillField('Post[text]', $newText);
        $I->click('Save');
        $I->wait(1);
        $I->seeInSource("<td>$newText</td>");
        $I->seeInSource("<td>$newText</td>");
        $I->seeInSource("<td>Петрович</td>");
    }
}

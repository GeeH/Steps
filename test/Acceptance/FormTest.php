<?php

namespace Acceptance;

use Steps\PHPUnit\WebPageTestCase;

class FormTest extends WebPageTestCase
{
    public function testFillTextField()
    {
        $this->visit('http://127.0.0.1:4242/form.php')
            ->fill('first_textbox', 'I typed something')
            ->click('Submit')
            ->shouldBeOnUrlContaining('first_textbox');
    }

    public function testCheckCheckbox()
    {
        $this->visit('http://127.0.0.1:4242/form.php')
            ->click('first_checkbox')
            ->click('Submit')
            ->shouldBeOnUrlContaining('first_checkbox=on');
    }

    public function testUncheckCheckbox()
    {
        $this->visit('http://127.0.0.1:4242/form.php')
            ->click('second_checkbox')
            ->click('Submit')
            ->shouldBeOnUrlNotContaining('second_checkbox');
    }

    public function testSelectedRadioButton()
    {
        $this->visit('http://127.0.0.1:4242/form.php')
            ->click('second_value', 'first_select')
            ->click('Submit')
            ->shouldBeOnUrlContaining('first_select=second_value');
    }
}

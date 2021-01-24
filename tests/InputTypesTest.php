<?php
namespace Tests\Inputs;

use Forms\CI\Types\EmailType;
use Forms\CI\Types\PasswordType;
use Forms\CI\Types\SelectorType;
use Forms\CI\Types\TextAreaType;
use Forms\CI\Types\TextType;
use CodeIgniter\Test\CIUnitTestCase;

class InputTypesTest extends CIUnitTestCase
{

    public function testInputEmailReturnString()
    {
        $emaiInput = new EmailType(['id'=>'email', 'name'=>'email','class'=>'form-control']);
        $this->assertIsString($emaiInput->buildType());
    }

    public function testIntputEmailReturnCorrectlyData()
    {
        $emailInput = new EmailType(['id'=>'email', 'name'=>'email','class'=>'form-control']);
        $input = $emailInput->buildType();
        $this->assertStringContainsString('name="email"', $input);
        $this->assertStringContainsString('<input', $input);
        $this->assertStringContainsString('class="form-control"', $input);
        $this->assertStringContainsString('id="email"', $input);
        $this->assertStringContainsString('type="email"', $input);
    }

    public function testInputTextReturnString()
    {
        $textInput = new TextType(['id'=>'username', 'name'=>'username','class'=>'form-control']);
        $this->assertIsString($textInput->buildType());
    }

    public function testInputTextReturnCorrectlyData()
    {
        $textInput = new TextType(['id'=>'username', 'name'=>'username','class'=>'form-control']);
        $stringText = $textInput->buildType();

        $this->assertStringContainsString('id="username"',$stringText);
        $this->assertStringContainsString('name="username"',$stringText);
        $this->assertStringContainsString('type="text"',$stringText);
    }


    public function testInputPasswordtReturnString()
    {
        $textInput = new PasswordType(['id'=>'password_hash', 'name'=>'password_hash','class'=>'form-control']);
        $this->assertIsString($textInput->buildType());
    }

    public function testInputPasswordReturnCorrectlyData()
    {
        $passwordInput = new PasswordType([
            'id'=>'password_hash',
            'name'=>'password_hash',
            'class'=>'form-control',
        ]);

        $passwordInput->setIsReadOnly(true);
        $passwordInput->setIsEnable(false);

        $stringText = $passwordInput->buildType();

        $this->assertStringContainsString('id="password_hash"',$stringText);
        $this->assertStringContainsString('<input ',$stringText);
        $this->assertStringContainsString('name="password_hash"',$stringText);
        $this->assertStringContainsString('type="password"',$stringText);
        $this->assertStringContainsString('readonly',$stringText);
        $this->assertStringContainsString('disabled',$stringText);
    }

    public function testInputTextAreaReturnCorrectlyData()
    {
        $textAreaInput = new TextAreaType([
            'id'=>'address',
            'name'=>'address',
            'class'=>'form-control',
        ]);

        $textAreaInput->setIsReadOnly(true);
        $textAreaInput->setIsEnable(false);
        $this->assertIsString($textAreaInput->buildType());


        $stringText = $textAreaInput->buildType();

        $this->assertStringContainsString('id="address"',$stringText);
        $this->assertStringContainsString('<textarea ',$stringText);
        $this->assertStringContainsString('name="address"',$stringText);
        $this->assertStringContainsString('type="textarea"',$stringText);
        $this->assertStringContainsString('readonly',$stringText);
        $this->assertStringContainsString('disabled',$stringText);
    }

    public function testSelectorInputReturnCorrectlyData()
    {
        $selector = new SelectorType(['id'=>'address',
            'name'=>'edad',
            'class'=>'form-control',
            'options'=>['1'=>'uno','2'=>'dos'],
            'value'=>'1'
            ]);

        $dataBuilded = $selector->buildType();
        $this->assertIsString($dataBuilded);
        $this->assertStringContainsString('type="selector"', $dataBuilded);
        $this->assertStringContainsString('name="edad"', $dataBuilded);
        $this->assertStringContainsString('value="1" selected="selected"', $dataBuilded);
        $this->assertStringContainsString('<option', $dataBuilded);
    }



}

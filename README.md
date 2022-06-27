# CI Forms

![Build Status](https://travis-ci.com/herodsoft/codeigniter-forms.svg?branch=master)

## Installation


### Prerequisites

PHP version 7.4 or higher is required for this component :

```
CodeIgniter 4

PHP >= 7.4

```

Use composer to install 
```
composer require hdrodriguez/ci-forms
```



## Usage

```php

namespace App\Forms;

use Forms\CI\FormType;
use Forms\CI\Types\EmailType;
use Forms\CI\Types\TextAreaType;
use Forms\CI\Types\TextType;

class PostForm extends FormType
{

    public function buildForm()
    {
        self::addInput('name', new TextType(['name'=>'name', 'label'=>'Name']))
            ->addInput('email', new EmailType(['name'=>'email', 'label'=>'Correo']))
            ->addInput('address', new TextAreaType())
            ->addInput('age', new SelectorType(['options'=>[
                1=>'one',
                2=>'two',
                3=>'three',
                4=>'four',
                5=>'five',
            ], 'default'=>3]))
            ->addInput('password', new PasswordType())
            ->addInput('remember_me', new CheckBoxType([
                'value'=>'dog',
                'checked'=>false,
                'label'=>'Dog']
            ))
            ->addInput('remember_me_2',
                new CheckBoxType([
                    'value'=>'chicken',
                    'checked'=>false,
                    'label'=>'Gallina'
                ]))
            ->addInput('address_info', new FieldSetType(['legend'=>'text of fieldset']))
            ->addInput('gender_1', new RadioType(['name'=>'gender','value'=>'male', 'checked'=>false, 'label'=>'Masculino']))
            ->addInput('gender_2', new RadioType(['name'=>'gender','value'=>'female', 'checked'=>false, 'label'=>'Femenino']))
            ->addInput('address_info_close', new FieldSetCloseType())
            ->addInput('submit', new SubmitType(['value'=>'Submit']))
        ;
    }
}

```

## Using in controller


```php
<?php


namespace App\Controllers;


use App\Forms\PostForm;

use App\Models\PostModel;
use CodeIgniter\Controller;

class PostController extends Controller
{

    public function index() : string
    {
        $postModel = new PostModel();
        $data['posts'] = $postModel->asArray()->findAll();
        return view('post/index', $data);
    }
    
    public function create(): string
    {
        $form = new PostForm();
        $form->setRequestHandler($this->request);
        if($form->isSubmited() && $form->isValidated())
        {
            $data = $this->request->getPost();
            $user = new UserModel();
            $user->save($data);

        }else
        {
            return $form->buildView();
        }
    }

}

```

## Contributing

We are accepting contributions




# CI Forms

![Build Status](https://travis-ci.com/hdrodriguez/codeigniter-forms.svg?branch=master)

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
        self::add('name', new TextType(['name'=>'name', 'label'=>'Name']))
            ->add('email', new EmailType(['name'=>'email', 'label'=>'Correo']))
            ->add('address', new TextAreaType())
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

    public function index()
    {
        $postModel = new PostModel();
        $data['posts'] = $postModel->asArray()->findAll();
        return view('post/index', $data);
    }
    
    public function form()
    {
        $form = new PostForm();

        $form->addPropertyField('email')->setLabel('Email');
        $form->addPropertyField('address')->setLabel('Work Address');
        return $form->buildView();
    }

}

```




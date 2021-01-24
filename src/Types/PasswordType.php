<?php


namespace App\Libraries\Forms\Types;


class PasswordType extends InputType
{
    use BuildTypeTrait;

    protected string $type = 'password';

}

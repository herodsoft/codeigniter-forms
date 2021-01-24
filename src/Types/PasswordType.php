<?php


namespace Forms\CI\Types;


class PasswordType extends InputType
{
    use BuildTypeTrait;

    protected string $type = 'password';

}

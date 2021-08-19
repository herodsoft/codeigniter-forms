<?php


namespace Forms\CI\Types;


trait BuildTypeTrait
{
    public function buildType(): string
    {
        return $this->label() . $this->buildInput();
    }



}

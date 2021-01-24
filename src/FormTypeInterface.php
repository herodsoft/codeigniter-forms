<?php


namespace Forms\CI;


interface FormTypeInterface
{
    public function buildForm();

    public function buildView():string;

}

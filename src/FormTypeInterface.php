<?php


namespace App\Libraries\Forms;


interface FormTypeInterface
{
    public function buildForm();

    public function buildView():string;

}

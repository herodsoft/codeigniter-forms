<?php


namespace App\Libraries\Forms\Types;


class EmailType extends InputType
{
    protected string $type = 'email';

    public function buildType(): string
    {
        $data=[];
        foreach ($this->getProperties() as $key => $value)
        {
            $data[$key]=$value;
        }
        return $this->label() . form_input($this->cleanedProperties($data),$this->value,'',$this->type);
    }






}

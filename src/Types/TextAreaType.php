<?php


namespace Forms\CI\Types;


class TextAreaType extends InputType
{

    protected string $type = 'textarea';
    protected int $rows = 5;
    protected int $cols;
    protected string $style = 'resize:none';
    protected bool $isReadOnly = false;


    public function buildType()
    {

        $data=[];
        foreach ($this->getProperties() as $key => $value)
        {
            $data[$key]=$value;
        }

        $data['value'] = $this->getValue();
        return $this->label() . form_textarea($this->cleanedProperties($data), $this->getValue(),'');
    }
}

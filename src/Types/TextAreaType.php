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

        return $this->label() . $this->buildInput();
    }

    protected function buildInput(): string
    {
        $data=[];
        foreach ($this->getProperties() as $key => $value)
        {
            $data[$key]=$value;
        }
        $data['value'] = $this->getValue();
        $input = form_textarea($this->cleanedProperties($data), $this->getValue(),'');
        return $this->filteredInput($input);
    }
}

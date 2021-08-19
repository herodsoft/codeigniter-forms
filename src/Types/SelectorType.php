<?php


namespace Forms\CI\Types;


use CodeIgniter\Model;

class SelectorType extends InputType
{

    protected string $type = 'selector';
    protected array $options = [];


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
        $data = $this->cleanedProperties($data);
        unset($data['options'],$data['value']);
        $input = form_dropdown($data, $this->options, $this->getValue(), '');
        return $this->filteredInput($input);
    }

}

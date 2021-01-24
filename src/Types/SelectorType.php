<?php


namespace Forms\CI\Types;


use CodeIgniter\Model;

class SelectorType extends InputType
{

    protected string $type = 'selector';
    protected array $options = [];



    public function buildType()
    {
        $data = [];
        foreach ($this->getProperties() as $key => $value) {
            $data[$key] = $value;
        }

        $data = $this->cleanedProperties($data);
        unset($data['options'],$data['value']);

        return $this->label() . form_dropdown($data, $this->options, $this->value, '');
    }

}

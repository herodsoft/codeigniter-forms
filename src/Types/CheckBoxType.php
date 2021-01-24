<?php


namespace Forms\CI\Types;


class CheckBoxType extends InputType implements TypeInputInterface
{

    protected bool $checked = false;
    protected string $type = 'checkbox';
    protected string $style = 'margin:10px';
    public function buildType()
    {
        $data = [];
        foreach ($this->getProperties() as $key => $value) {
            $data[$key] = $value;
        }
        $data = $this->cleanedProperties($data);
        return form_checkbox($data) . $this->label();
    }
}

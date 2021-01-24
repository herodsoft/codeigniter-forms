<?php


namespace App\Libraries\Forms\Types;


class RadioType extends InputType implements TypeInputInterface
{

    protected bool $checked = false;
    protected string $type = 'radio';
    protected string $style = 'margin:10px';

    public function buildType()
    {
        $data = [];
        foreach ($this->getProperties() as $key => $value) {
            $data[$key] = $value;
        }
        $data = $this->cleanedProperties($data);
        return form_radio($data) . $this->label();
    }


}

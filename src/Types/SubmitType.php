<?php


namespace Forms\CI\Types;


class SubmitType extends InputType implements TypeInputInterface
{


    protected string $type = 'submit';
    protected string $style = '';

    public function buildType()
    {
        $data = [];
        foreach ($this->getProperties() as $key => $value) {
            $data[$key] = $value;
        }
        $data = $this->cleanedProperties($data);
        unset($data['label']);
        return form_submit($data);
    }


}

<?php


namespace Forms\CI\Types;


class FieldSetCloseType extends InputType implements TypeInputInterface
{

    public function buildType()
    {
        return form_fieldset_close();
    }

}

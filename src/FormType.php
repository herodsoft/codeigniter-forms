<?php


namespace Forms\CI;


use Forms\CI\Types\InputType;
use Forms\CI\Types\TypeInputInterface;

abstract class FormType implements FormTypeInterface
{
    protected array $inputs = [];


    public function __construct()
    {
        $this->buildForm();
    }


    public function buildView(): string
    {
        $form = form_open('/');
        foreach ($this->inputs as $key =>  $value)
        {
            $form .= $this->getInput($key)->buildType()."<br>";
        }
        return form_close().$form;
    }

    public function add(string $name, TypeInputInterface $type)
    {
        $this->inputs[$name] = $type;
        return $this;
    }

    /**
     * @return array
     */
    public function getInputs(): array
    {
        return $this->inputs;
    }

    public function getInput($name):TypeInputInterface
    {
        return $this->inputs[$name];
    }

    public function addPropertyField($name): InputType
    {
        return $this->inputs[$name];
    }


    /**
     * @param array $inputs
     */
    public function setInputs(array $inputs): void
    {
        $this->inputs = $inputs;
    }




}

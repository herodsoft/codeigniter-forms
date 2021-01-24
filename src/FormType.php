<?php


namespace App\Libraries\Forms;


use App\Libraries\Forms\Types\InputType;
use App\Libraries\Forms\Types\TypeInputInterface;
use MongoDB\BSON\Type;

abstract class FormType implements FormTypeInterface
{
    protected array $inputs = [];


    public function __construct()
    {
        $this->buildForm();
    }


    public function buildView(): string
    {
        $form = '';
        foreach ($this->inputs as $key =>  $value)
        {
            $form .= $this->getInput($key)->buildType();
        }
        return $form;
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

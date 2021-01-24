<?php


namespace Forms\CI;


use CodeIgniter\HTTP\Request;
use Forms\CI\Types\InputType;
use Forms\CI\Types\TypeInputInterface;

abstract class FormType implements FormTypeInterface
{
    protected array $inputs = [];
    protected bool $isSubmited = false;
    protected bool $isValidated = false;
    protected Request $request;


    public function __construct()
    {
        $this->buildForm();
    }


    public function buildView(): string
    {
        $form = form_open(current_url());
        foreach ($this->inputs as $key =>  $value)
        {
            $form .= $this->getInput($key)->buildType()."<br>";
        }
        return $form . form_close();
    }

    public function addInput(string $name, TypeInputInterface $type)
    {
        $this->inputs[$name] = $type;
        return $this;
    }

    /**
     * @return bool
     */
    public function isValidated(): bool
    {
        if($this->request)

        return $this->isValidated;
    }

    /**
     * @param bool $isValidated
     */
    protected function setIsValidated(bool $isValidated): void
    {
        $this->isValidated = $isValidated;
    }


    /**
     * @return string
     */
    public function getRequestHandler(): string
    {
        return $this->request;
    }

    /**
     * @param Request $request
     */
    public function setRequestHandler(Request $request): void
    {
        $this->request = $request;
    }


    /**
     * @return bool
     */
    public function isSubmited(): bool
    {
        if( !empty($this->request) && is_a($this->request, Request::class) && $this->request->getMethod() != 'get')
        {
            $this->setIsSubmited(true);
        }
        return $this->isSubmited;
    }

    /**
     * @param bool $isSubmited
     */
    public function setIsSubmited(bool $isSubmited): void
    {

        $this->isSubmited = $isSubmited;
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

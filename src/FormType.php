<?php


namespace Forms\CI;


use CodeIgniter\Config\Services;
use CodeIgniter\HTTP\Request;
use Forms\CI\Types\InputType;
use Forms\CI\Types\TypeInputInterface;

abstract class FormType implements FormTypeInterface
{
    protected array $inputs = [];
    protected bool $isSubmited = false;
    protected bool $isValidated = false;
    protected Request $request;
    protected string $action = ''; //route_name
    protected array $attributes = ['class'=>'', 'id'=>''];
    protected array $hiddenInputs = [];
    protected bool $multiPartForm  = false;
    protected string $validationRule = '';
    protected bool $skipValidation = false;
    protected array $validationRules      = [];
    protected array $validationErrors = [];


    public function __construct()
    {
        $this->buildForm();
    }


    public function buildView(): string
    {

        if(!empty($this->getAction()))
        {
            $this->setAction(route_to($this->getAction()));
        }

        if($this->multiPartForm)
        {
            $form = form_open_multipart($this->getAction()??current_url(), $this->getAttributes(), $this->getHiddenInputs());

        }else
        {
            $form = form_open($this->getAction()??current_url(), $this->getAttributes(), $this->getHiddenInputs());
        }

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




    protected function runValidation()
    {
        $validation =  Services::validation();
        if(!$this->skipValidation)
        {
            if(!empty($this->validationRule) && !empty($this->request))
            {
                $rule = $this->validationRule;
                $validation->setRuleGroup($rule);

                $resultValidation =
                    $validation
                    ->withRequest($this->request)
                    ->run();
                $this->setIsValidated($resultValidation);

                if(!$resultValidation)
                {
                    $this->validationErrors = $validation->getErrors();
                }

                return $resultValidation;

            }

            if(count($this->validationRules) && !empty($this>$this->request))
            {
                $validation->reset();


                $resultValidation =  $validation
                    ->setRules($this->validationRules)
                    ->withRequest($this->request)
                    ->run();
                $this->setIsValidated($resultValidation);

                if(!$resultValidation)
                {
                    $this->validationErrors = $validation->getErrors();
                }

                return $resultValidation;
            }
        }
    }

    /**
     * @return bool
     */
    public function isValidated(): bool
    {
        $this->runValidation();
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

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param string $action
     */
    public function setAction(string $action): void
    {
        $this->action = $action;
    }

    /**
     * @return array|string[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param array|string[] $attributes
     */
    public function setAttributes(array $attributes): void
    {
        $this->attributes = $attributes;
    }

    /**
     * @return array
     */
    public function getHiddenInputs(): array
    {
        return $this->hiddenInputs;
    }

    /**
     * @param array $hiddenInputs
     */
    public function setHiddenInputs(array $hiddenInputs): void
    {
        $this->hiddenInputs = $hiddenInputs;
    }

    /**
     * @return array
     */
    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }












}

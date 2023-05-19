<?php


namespace Forms\CI;


use CodeIgniter\Config\Services;
use CodeIgniter\HTTP\Request;
use Forms\CI\Types\InputType;
use Forms\CI\Types\TypeInputInterface;

abstract class FormType implements FormTypeInterface
{
    protected array $inputs = [];
    protected bool $isSubmitted = false;
    protected bool $isValidated = true;
    protected Request $request;
    protected string $action = ''; //route_name
    protected array $attributes = ['class'=>'', 'id'=>''];
    protected array $hiddenInputs = [];
    protected bool $multiPartForm  = false;
    protected string $validationRule = '';
    protected bool $skipValidation = false;
    protected array $validationRules      = [];
    protected array $validationErrors = [];
    protected string $formTitle = '';
    protected bool $addErrorOnHeaderForm = true;
    protected bool $hasInputGroup = true;
    protected bool $hasFeedback = true;
    protected bool $addValidationOnFeedBack = true;
    protected string $classInputGroup = 'input-group';
    protected string $classFeedbackOnSuccess = 'valid-feedback';
    protected string $classFeedbackOnError = 'invalid-feedback';
    protected string $formClassAfterValidation = 'was-validated';
    protected bool $withNewLine = false;
    protected array $values = [];


    public function __construct()
    {
        $this->buildForm();
    }

    public function asArray() : array
    {
        $inputs = [];
        foreach ($this->inputs as $input)
        {
            $inputs[] = $this->passValidation($input);
        }
        return $inputs;
    }

    public function passValidation($input) : array
    {
        $properties = $input->getProperties();
        if($this->isSubmitted())
        {
            $properties['value'] = $input->getValue();
            $this->runValidation();
            if($this->getValidationErrors())
            {
                if(array_key_exists($properties['name'], $this->getValidationErrors()))
                {
                    $properties['message_error'] = $this->getValidationErrors()[$properties['name']];
                }
            }
        }
        return $properties;
    }

    /**
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @param array $values
     */
    public function setValues(array $values): FormType
    {
        $this->values = $values;

        foreach ($this->inputs as &$input)
        {
            $input->setValue($values[$input->getName()]??'');
        }
        return $this;
    }




    public function buildView(): string
    {

        $this->addAfterValidationFormClass();
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

        $form.=$this->getFormTitle();
        $form.=$this->addErrorOnHeaderForm?$this->addValidationErrorAsListOnForm():'';

        foreach ($this->inputs as $key =>  $value)
        {
            $inputType = $this->groupInput($this->getInput($key));
            $form .= $inputType->buildType().$this->newLine();
        }
        return $form . form_close();
    }


    protected function newLine()
    {
        return $this->withNewLine?"<br>":'';
    }



    protected function groupInput(InputType $inputType):InputType
    {
        if($this->isHasInputGroup())
        {
            $inputType->setGroupInput(true);
            $inputType->setClassGroup($this->getClassInputGroup());
        }

        if($this->isHasFeedback())
        {
            $inputType->setHasFeedBack(true);
        }

        if(count($this->getValidationErrors()))
        {
            $errors = $this->getValidationErrors();
            if(isset($errors[$inputType->getName()]) && $this->isSubmitted())
            {
                $inputType->setContentFeedBack($errors[$inputType->getName()]);
                $inputType->setClassFeedBack($this->getClassFeedbackOnError());
            }else
            {
                if($this->isSubmitted())
                {
                    $inputType->setClassFeedBack($this->getClassFeedbackOnSuccess());
                }
            }
        }

        return $inputType;
    }

    public function addInput(string $name, TypeInputInterface $type)
    {
        if(strlen($type->getName()) == 0)
        {
            $type->setName($name);
        }

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

        return true;
    }



    protected function addAfterValidationFormClass()
    {
        if($this->isSubmitted())
        {
            $this->attributes['class'].=' '.$this->formClassAfterValidation;
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
    public function isSubmitted(): bool
    {
        if( !empty($this->request) && is_a($this->request, Request::class) && $this->request->getMethod() != 'get')
        {
            $this->setIsSubmitted(true);
        }
        return $this->isSubmitted;
    }

    /**
     * @param bool $isSubmitted
     */
    public function setIsSubmitted(bool $isSubmitted): void
    {

        $this->isSubmitted = $isSubmitted;
    }


    /**
     * @return array
     */
    public function getInputs(): array
    {
        return $this->inputs;
    }

    public function getInput($name):InputType
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


    protected function addValidationErrorAsListOnForm()
    {
        $errors =  $this->getValidationErrors();
        $html = '';
        if(count($errors))
        {
            $html = '<div class="alert alert-danger"><ul>';
            foreach ($errors as $key => $value)
            {
                $html.="<li>{$value}</li>";
            }
            $html.='</ul></div>';
        }
        return $html;
    }

    /**
     * @return string
     */
    public function getFormTitle(): string
    {
        return $this->formTitle;
    }

    /**
     * @param string $formTitle
     */
    public function setFormTitle(string $formTitle): void
    {
        $this->formTitle = $formTitle;
    }

    /**
     * @return bool
     */
    public function isAddErrorOnHeaderForm(): bool
    {
        return $this->addErrorOnHeaderForm;
    }

    /**
     * @param bool $addErrorOnHeaderForm
     */
    public function setAddErrorOnHeaderForm(bool $addErrorOnHeaderForm): void
    {
        $this->addErrorOnHeaderForm = $addErrorOnHeaderForm;
    }

    /**
     * @return bool
     */
    public function isHasInputGroup(): bool
    {
        return $this->hasInputGroup;
    }

    /**
     * @param bool $hasInputGroup
     */
    public function setHasInputGroup(bool $hasInputGroup): void
    {
        $this->hasInputGroup = $hasInputGroup;
    }

    /**
     * @return bool
     */
    public function isHasFeedback(): bool
    {
        return $this->hasFeedback;
    }

    /**
     * @param bool $hasFeedback
     */
    public function setHasFeedback(bool $hasFeedback): void
    {
        $this->hasFeedback = $hasFeedback;
    }

    /**
     * @return bool
     */
    public function isAddValidationOnFeedBack(): bool
    {
        return $this->addValidationOnFeedBack;
    }

    /**
     * @param bool $addValidationOnFeedBack
     */
    public function setAddValidationOnFeedBack(bool $addValidationOnFeedBack): void
    {
        $this->addValidationOnFeedBack = $addValidationOnFeedBack;
    }

    /**
     * @return string
     */
    public function getClassInputGroup(): string
    {
        return $this->classInputGroup;
    }

    /**
     * @param string $classInputGroup
     */
    public function setClassInputGroup(string $classInputGroup): void
    {
        $this->classInputGroup = $classInputGroup;
    }

    /**
     * @return string
     */
    public function getClassFeedbackOnSuccess(): string
    {
        return $this->classFeedbackOnSuccess;
    }

    /**
     * @param string $classFeedbackOnSuccess
     */
    public function setClassFeedbackOnSuccess(string $classFeedbackOnSuccess): void
    {
        $this->classFeedbackOnSuccess = $classFeedbackOnSuccess;
    }

    /**
     * @return string
     */
    public function getClassFeedbackOnError(): string
    {
        return $this->classFeedbackOnError;
    }

    /**
     * @param string $classFeedbackOnError
     */
    public function setClassFeedbackOnError(string $classFeedbackOnError): void
    {
        $this->classFeedbackOnError = $classFeedbackOnError;
    }

















}

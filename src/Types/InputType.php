<?php


namespace Forms\CI\Types;


use CodeIgniter\CodeIgniter;
use CodeIgniter\Config\BaseService;
use CodeIgniter\Config\Config;
use CodeIgniter\HTTP\Request;
use Config\Services;

abstract class InputType implements TypeInputInterface
{

    protected string $id = '';
    protected string $name = '';
    protected string $type = 'text';
    protected string $label = '';
    protected string $placeholder = '';
    protected string $class= '';
    protected string $value = '';
    protected string $default = '';
    protected int $maxlength = 0;
    protected int $size = 0;
    protected string $style = '';

    protected bool $isPlaceHolder = false;
    protected bool $isEnable = true;
    protected bool $isReadOnly = false;


    protected bool $groupInput = true;
    protected string $classGroup = '';
    protected bool $hasFeedBack = true;
    protected string $classFeedBack = '';
    protected string $contentFeedBack = '';


    public function __construct(array $options = [])
    {

        foreach ($options as $key => $value)
        {
            if(property_exists($this, $key) && !empty($value))
            {
                if($key != 'type')
                {
                    $this->$key = $value;
                }

            }
        }
        helper('form');
    }

    public function label(array $attr = []): string
    {
        return  form_label($this->label, $this->name, $attr);
    }

    protected function cleanedProperties($data):array
    {
        if(!empty($data['default']))
        {
            $data['value'] = $data['default'];
        }

        unset(
            $data['isPlaceHolder'],
            $data['isEnable'],
            $data['isReadOnly'],
            $data['label'],
            $data['default'],
            $data['groupInput'],
            $data['classGroup'],
            $data['hasFeedBack'],
            $data['classFeedBack'],
            $data['contentFeedBack'],
        );

        $noCommonProperties = ['maxlength','size','style','class','placeholder', 'readonly'];
        foreach ($noCommonProperties as $key => $value)
        {
            if(property_exists($this, $value))
            {
                if(empty($this->$value))
                {
                    unset($data[$value]);
                }
            }
        }

        if(!$this->isPlaceHolder())
        {
            unset($data['placeholder']);
        }

        if($this->isReadOnly())
        {
            $data['readonly'] = true;
        }

        if(!$this->isEnable())
        {
            $data['disabled'] = true;
        }

        return $data;
    }

    protected function buildInput(): string
    {
        $data=[];
        foreach ($this->getProperties() as $key => $value)
        {
            $data[$key]=$value;
        }
        $data['value'] = $this->getValue();
        $input = form_input($this->cleanedProperties($data),'',$this->type);
        return $this->filteredInput($input);
    }

    protected function filteredInput($input):string
    {
        if($this->isGroupInput())
        {
            $input='<div class="'.$this->getClassGroup().'">'.$input;

            if($this->isHasFeedBack())
            {
                $input.='<div class="'.$this->getClassFeedBack().'">'.$this->getContentFeedBack().'</div>';
            }
            $input.='</div>';
        }
        return $input;
    }


    public function __toString()
    {
        return $this->name;
    }

    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getPlaceHolder(): string
    {
        return $this->placeHolder;
    }

    /**
     * @param string $placeHolder
     */
    public function setPlaceHolder(string $placeHolder): void
    {
        $this->placeHolder = $placeHolder;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @param string $class
     */
    public function setClass(string $class): void
    {
        $this->class = $class;
    }

    /**
     * @return string
     */
    public function getJs(): string
    {
        return $this->js;
    }

    /**
     * @param string $js
     */
    public function setJs(string $js): void
    {
        $this->js = $js;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        $request = Services::request();
        $post = $request->getPost($this->getName());

        if(strlen($this->value)>0)
        {
            $value = $this->value;
        }else
        {
            $value = $post??$this->getDefault();
        }

        if($this->type === 'checkbox')
        {
            return set_checkbox($this->getName(), $value);
        }
        if($this->type === 'radio')
        {
            return set_radio($this->getName(), $value, '');
        }

        return set_value($this->getName(), $value);
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getDefault(): string
    {
        return $this->default;
    }

    /**
     * @param string $default
     */
    public function setDefault(string $default): void
    {
        $this->default = $default;
    }


    /**
     * @return bool
     */
    public function isPlaceHolder(): bool
    {
        return $this->isPlaceHolder;
    }

    /**
     * @param bool $isPlaceHolder
     */
    public function setIsPlaceHolder(bool $isPlaceHolder): void
    {
        $this->isPlaceHolder = $isPlaceHolder;
    }

    /**
     * @return bool
     */
    public function isEnable(): bool
    {
        return $this->isEnable;
    }

    /**
     * @param bool $isEnable
     */
    public function setIsEnable(bool $isEnable): void
    {
        $this->isEnable = $isEnable;
    }

    /**
     * @return bool
     */
    public function isReadOnly(): bool
    {
        return $this->isReadOnly;
    }

    /**
     * @param bool $isReadOnly
     */
    public function setIsReadOnly(bool $isReadOnly): void
    {
        $this->isReadOnly = $isReadOnly;
    }

    protected function getProperties():array
    {
        return get_object_vars($this);
    }

    /**
     * @return int
     */
    public function getMaxlength(): int
    {
        return $this->maxlength;
    }

    /**
     * @param int $maxlength
     */
    public function setMaxlength(int $maxlength): void
    {
        $this->maxlength = $maxlength;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param int $size
     */
    public function setSize(int $size): void
    {
        $this->size = $size;
    }

    /**
     * @return string
     */
    public function getStyle(): string
    {
        return $this->style;
    }

    /**
     * @param string $style
     */
    public function setStyle(string $style): void
    {
        $this->style = $style;
    }

    /**
     * @return bool
     */
    public function isGroupInput(): bool
    {
        return $this->groupInput;
    }

    /**
     * @param bool $groupInput
     */
    public function setGroupInput(bool $groupInput): void
    {
        $this->groupInput = $groupInput;
    }

    /**
     * @return string
     */
    public function getClassGroup(): string
    {
        return $this->classGroup;
    }

    /**
     * @param string $classGroup
     */
    public function setClassGroup(string $classGroup): void
    {
        $this->classGroup = $classGroup;
    }

    /**
     * @return bool
     */
    public function isHasFeedBack(): bool
    {
        return $this->hasFeedBack;
    }

    /**
     * @param bool $hasFeedBack
     */
    public function setHasFeedBack(bool $hasFeedBack): void
    {
        $this->hasFeedBack = $hasFeedBack;
    }

    /**
     * @return string
     */
    public function getClassFeedBack(): string
    {
        return $this->classFeedBack;
    }

    /**
     * @param string $classFeedBack
     */
    public function setClassFeedBack(string $classFeedBack): void
    {
        $this->classFeedBack = $classFeedBack;
    }

    /**
     * @return string
     */
    public function getContentFeedBack(): string
    {
        return $this->contentFeedBack;
    }

    /**
     * @param string $contentFeedBack
     */
    public function setContentFeedBack(string $contentFeedBack): void
    {
        $this->contentFeedBack = $contentFeedBack;
    }








}

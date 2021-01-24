<?php


namespace Forms\CI\Types;


use CodeIgniter\CodeIgniter;
use CodeIgniter\Config\BaseService;
use CodeIgniter\Config\Config;

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
        unset(
            $data['isPlaceHolder'],
            $data['isEnable'],
            $data['isReadOnly'],
            $data['label'],
            $data['default'],
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
        return $this->value;
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






}

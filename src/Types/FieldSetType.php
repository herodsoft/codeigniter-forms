<?php


namespace Forms\CI\Types;


class FieldSetType extends InputType implements TypeInputInterface
{
    protected string $legend = '';

    public function buildType()
    {
        $data = [];
        foreach ($this->getProperties() as $key => $value) {
            $data[$key] = $value;
        }
        $data = $this->cleanedProperties($data);
        return form_fieldset($this->getLegend(),$data);
    }

    /**
     * @return string
     */
    public function getLegend(): string
    {
        return $this->legend;
    }

    /**
     * @param string $legend
     */
    public function setLegend(string $legend): void
    {
        $this->legend = $legend;
    }



}

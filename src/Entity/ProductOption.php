<?php


namespace Test\Entity;


use SplObjectStorage;

class ProductOption
{
    /**
     * @var int
     */
    protected $id;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string|null
     */
    protected $unit;
    /**
     * @var SplObjectStorage
     */
    protected $values;

    /**
     * ProductOption constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->values = new SplObjectStorage();
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
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
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return SplObjectStorage
     */
    public function getValues(): SplObjectStorage
    {
        return $this->values;
    }

    /**
     * @param OptionValue $value
     */
    public function addValue(OptionValue $value)
    {
        $this->values->attach($value);
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return null|string
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @param null|string $unit
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;
    }

}
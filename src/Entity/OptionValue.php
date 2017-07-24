<?php


namespace Test\Entity;


class OptionValue
{
    protected $id;
    /**
     * @var ProductOption
     */
    protected $productOption;
    /**
     * @var string
     */
    protected $value;

    /**
     * OptionValue constructor.
     * @param ProductOption $productOption
     * @param string $value
     */
    public function __construct(ProductOption $productOption, $value)
    {
        $this->productOption = $productOption;
        $this->value = $value;

        $this->productOption->addValue($this);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
    public function setValue(string $value)
    {
        $this->value = $value;
    }

    public function __toString()
    {
        return $this->value;
    }

    /**
     * @return ProductOption
     */
    public function getProductOption(): ProductOption
    {
        return $this->productOption;
    }

}
<?php

namespace Test\Entity;

use SplObjectStorage;

class Product
{
    /**
     * @var int
     */
    protected $id;
    /**
     * @var string
     */
    protected $model;
    /**
     * @var float
     */
    protected $price;
    /**
     * @var SplObjectStorage
     */
    protected $options;

    /**
     * Product constructor.
     */
    public function __construct()
    {
        $this->options = new SplObjectStorage();
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
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return $this
     */
    public function setPrice(float $price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return SplObjectStorage
     */
    public function getOptions(): SplObjectStorage
    {
        return $this->options;
    }

    public function addOption(ProductOption $option)
    {
        $this->options->attach($option);
    }

    /**
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * @param string $model
     */
    public function setModel(string $model)
    {
        $this->model = $model;
    }

}
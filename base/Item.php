<?php

class Item
{
    public string $id;
    public string $name;
    public float $price;
    public string $category;
    public string $date;

    function __construct($name, $price, $category)
    {
        $this->id = uniqid('item-');
        $this->name = $name;
        $this->price = $price;
        $this->category = $category;
        $this->date = date('Y-m-d H:i:s');
    }
}
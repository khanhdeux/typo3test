<?php

namespace Khanhdeux\Inventory\Domain\Model;

/**
 * Created by PhpStorm.
 * User: Arrabiata
 * Date: 08/01/2016
 * Time: 14:53
 */
class Product extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /** * @var string * */
    protected $name = '';
    /** * @var string * */
    protected $description = '';
    /** * @var int * */
    protected $quantity = 0;

    public function __construct($name = '', $description = '', $quantity = 0)
    {
        $this->setName($name);
        $this->setDescription($description);
        $this->setQuantity($quantity);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }



}
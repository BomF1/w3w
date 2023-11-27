<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="product", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="unique_product_name", columns={"name"})
 * })
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="float", scale=2)
     */
    private $price;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @var ?ProductManufacturer
     * @ORM\ManyToOne(targetEntity="ProductManufacturer", inversedBy="products", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="manufacturer_id", referencedColumnName="id")
     */
    private ?ProductManufacturer $manufacturer;

    /**
     * @var ProductParameter[]|Collection
     * @ORM\ManyToMany(targetEntity="ProductParameter")
     * @ORM\JoinTable(name="product__product__parameter",
     *      joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="parameter_id", referencedColumnName="id")}
     * )
     */
    private $productParameters;

    public function __construct()
    {
        $this->productParameters = new ArrayCollection();
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
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    public function getManufacturer(): ?ProductManufacturer
    {
        return $this->manufacturer;
    }

    public function setManufacturer(?ProductManufacturer $manufacturer): void
    {
        $this->manufacturer = $manufacturer;
    }

    /**
     * @return ProductParameter[]|Collection
     */
    public function getProductParameters()
    {
        return $this->productParameters;
    }

    /**
     * @param ProductParameter[]|Collection $productParameters
     */
    public function setProductParameters($productParameters): void
    {
        $this->productParameters = $productParameters;
    }


}

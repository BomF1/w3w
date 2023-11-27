<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="product_manufacturer", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="unique_name", columns={"name"})
 * })
 */
class ProductManufacturer
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
     * @var Product[]|Collection
     * @ORM\OneToMany(targetEntity="Product", mappedBy="manufacturer")
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
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
     * @return Product[]|ArrayCollection|Collection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param Product[]|ArrayCollection|Collection $products
     */
    public function setProducts($products): void
    {
        $this->products = $products;
    }

   public function existProduct(Product $product): bool
   {
       return $this->products->contains($product);
   }

   public function addProduct(Product $product): void
   {
       if (!$this->existProduct($product)) {
           $this->products->add($product);
           $product->setManufacturer($this);
       }
   }

    public function removeProduct(Product $product): void
    {
         if ($this->existProduct($product)) {
              $this->products->removeElement($product);
              $product->setManufacturer(null);
         }
    }

}

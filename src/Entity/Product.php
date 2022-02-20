<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ApiResource(itemOperations={
 *     "get"={
 *         "method"="GET",
 *         "access_control"="is_granted('IS_AUTHENTICATED_FULLY')",
 *         "path"="/product/{id}",
 *          "openapi_context"={
 *                  "summary"="Retrieve information on a specific product"
 *              } ,
 *     },
 *      "delete"={
 *         "method"="DELETE",
 *          "access_control"="is_granted('IS_AUTHENTICATED_FULLY')",
 *         "path"="/product/{id}",
 *          "openapi_context"={
 *                  "summary"="Delete a product"
 *              }
 *     },
 *      "put"={
 *         "method"="PUT",
 *          "access_control"="is_granted('IS_AUTHENTICATED_FULLY')",
 *         "path"="/product/{id}",
 *            "openapi_context"={
 *                  "summary"="Modify a product"
 *              }
 *     },
 * },
 *       collectionOperations={
 *          "get"={
 *           "method"="GET",
 *           "path"="/products",
 *              "openapi_context"={
 *                  "summary"="Retrieve list of products"
 *              }
 *          },
 *          "post"={
 *              "method"="POST",
 *              "access_control"="is_granted('IS_AUTHENTICATED_FULLY')",
 *              "path"="/product",
 *              "openapi_context"={
 *                  "summary"="Add a product"
 *              }
 *          },
 * 
 *          
 *      },
 * )
 *
 * @ORM\Entity(repositoryClass=ProductRepository::class)
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
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $photo;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }
}

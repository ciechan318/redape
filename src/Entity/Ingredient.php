<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IngredientRepository")
 */
class Ingredient
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\IngredientQuantity", mappedBy="ingredient", orphanRemoval=true)
     */
    private $ingredientQuantities;

    public function __construct()
    {
        $this->ingredientQuantities = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->getName();
    }

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

    /**
     * @return Collection|IngredientQuantity[]
     */
    public function getIngredientQuantities(): Collection
    {
        return $this->ingredientQuantities;
    }

    public function addIngredientQuantity(IngredientQuantity $ingredientQuantity): self
    {
        if (!$this->ingredientQuantities->contains($ingredientQuantity)) {
            $this->ingredientQuantities[] = $ingredientQuantity;
            $ingredientQuantity->setIngredient($this);
        }

        return $this;
    }

    public function removeIngredientQuantity(IngredientQuantity $ingredientQuantity): self
    {
        if ($this->ingredientQuantities->contains($ingredientQuantity)) {
            $this->ingredientQuantities->removeElement($ingredientQuantity);
            // set the owning side to null (unless already changed)
            if ($ingredientQuantity->getIngredient() === $this) {
                $ingredientQuantity->setIngredient(null);
            }
        }

        return $this;
    }

}

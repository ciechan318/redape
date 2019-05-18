<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecipeRepository")
 */
class Recipe
{
    const TYPE_BREAKFAST = 1;
    const TYPE_DINNER = 2;
    const TYPE_SNACK = 3;
    const TYPE_FESTIVE = 4;
    const TYPE_OTHER = 5;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @Assert\NotBlank()
     * @Assert\GreaterThan(0)
     *
     * @ORM\Column(type="integer")
     */
    private $preparationTime;

    /**
     * @ORM\Column(type="integer")
     */
    private $likes = 0;

    /**
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @Assert\Valid()
     *
     * @ORM\OneToMany(targetEntity="App\Entity\IngredientQuantity", mappedBy="recipe", orphanRemoval=true, cascade={"persist"})
     */
    private $ingredientQuantities;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="recipes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct()
    {
        $this->ingredientQuantities = new ArrayCollection();
    }

    public function __toString()
    {
        return (string)$this->getName();
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

    public function getHumanType(): string
    {
        return self::getTypes(false)[$this->getType()];
    }

    static public function getTypes(?bool $flip = true): array
    {
        $result = [
            self::TYPE_BREAKFAST => 'recipe_type_breakfast',
            self::TYPE_DINNER => 'recipe_type_dinner',
            self::TYPE_SNACK => 'recipe_type_snack',
            self::TYPE_FESTIVE => 'recipe_type_festive',
            self::TYPE_OTHER => 'recipe_type_other',
        ];

        return $flip ? array_flip($result) : $result;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPreparationTime(): ?int
    {
        return $this->preparationTime;
    }

    public function setPreparationTime(int $preparationTime): self
    {
        $this->preparationTime = $preparationTime;

        return $this;
    }

    public function getLikes(): ?int
    {
        return $this->likes;
    }

    public function setLikes(int $likes): self
    {
        $this->likes = $likes;

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
            $ingredientQuantity->setRecipe($this);
        }

        return $this;
    }

    public function removeIngredientQuantity(IngredientQuantity $ingredientQuantity): self
    {
        if ($this->ingredientQuantities->contains($ingredientQuantity)) {
            $this->ingredientQuantities->removeElement($ingredientQuantity);
            // set the owning side to null (unless already changed)
            if ($ingredientQuantity->getRecipe() === $this) {
                $ingredientQuantity->setRecipe(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}

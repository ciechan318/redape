<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
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
     * @Gedmo\Translatable()
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Gedmo\Translatable()
     *
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
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string", length=128, unique=true)
     */
    private $slug;

    /**
     * @Assert\Valid()
     *
     * @ORM\OneToMany(targetEntity="App\Entity\IngredientQuantity", mappedBy="recipe", orphanRemoval=true, cascade={"persist"})
     */
    private $ingredientQuantities;

    /**
     * @Assert\Valid()
     *
     * @ORM\OneToMany(targetEntity="App\Entity\RecipeImage", mappedBy="recipe", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $images;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="recipeFavourites")
     */
    private $userFavourites;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="recipes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct()
    {
        $this->ingredientQuantities = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->userFavourites = new ArrayCollection();
    }

    public function isLikedBy(?User $user): bool
    {
        return $this->getUserFavourites()->contains($user);
    }

    /**
     * @return mixed
     */
    public function getUserFavourites()
    {
        return $this->userFavourites;
    }

    /**
     * @param mixed $userFavourites
     */
    public function setUserFavourites($userFavourites): self
    {
        $this->userFavourites = $userFavourites;

        return $this;
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

    /**
     * @param mixed $ingredientQuantities
     */
    public function setIngredientQuantities($ingredientQuantities): self
    {
        $this->ingredientQuantities = $ingredientQuantities;

        return $this;
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

    /**
     * @return Collection|RecipeImage[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    /**
     * @param mixed $images
     */
    public function setImages($images): self
    {
        $this->images = $images;

        return $this;
    }

    public function addImage(RecipeImage $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setRecipe($this);
        }

        return $this;
    }

    public function removeImage(RecipeImage $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getRecipe() === $this) {
                $image->setRecipe(null);
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

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}

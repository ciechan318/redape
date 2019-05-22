<?php

namespace App\Service;

use App\Entity\Recipe;
use App\Entity\User;
use App\Repository\IngredientRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class RecipeManager
{

    const SEARCH_KEY_PHRASE = 'search_phrase';
    const SEARCH_KEY_INGREDIENTS = 'search_ingredients';
    const SEARCH_RESULTS_PER_PAGE = 12;
    const SEARCH_QUERY_ALIAS = 'x';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var RecipeRepository
     */
    private $recipeRepository;
    /**
     * @var IngredientRepository
     */
    private $ingredientRepository;

    public function __construct(SessionInterface $session, EntityManagerInterface $entityManager, RecipeRepository $recipeRepository, IngredientRepository $ingredientRepository)
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
        $this->recipeRepository = $recipeRepository;
        $this->ingredientRepository = $ingredientRepository;
    }

    public function getIngredientChoices(): array
    {
        $results = [];

        $ingredients = $this->ingredientRepository->createQueryBuilder('i', 'i.name')
            ->select('i.id, i.name')
            ->getQuery()
            ->getArrayResult();

        foreach ($ingredients as $key => $ingredientData) {
            $results[$key] = $ingredientData['id'];
        }

        return $results;
    }

    public function search(int $page): array
    {
        $queryBuilder = $this->recipeRepository->createQueryBuilder(self::SEARCH_QUERY_ALIAS);

        if ($this->getSearchPhrase()) {
            $this->searchByPhrase($queryBuilder);
        }

        if ($this->getSearchIngredients()) {
            $this->searchByIngredients($queryBuilder);
        }

        $this->paginate($queryBuilder);

        $this->session->clear(); //@TODO remove after tests

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }

    public function getSearchPhrase(): ?string
    {
        return $this->session->get(self::SEARCH_KEY_PHRASE, null);
    }

    protected function searchByPhrase(QueryBuilder $queryBuilder): void
    {
        $alias = self::SEARCH_QUERY_ALIAS;
        $queryBuilder
            ->andWhere($alias . '.name LIKE :phrase OR ' . $alias . '.description LIKE :phrase')
            ->setParameter('phrase', '%' . $this->getSearchPhrase() . '%');
    }

    public function getSearchIngredients(): ?array
    {
        return $this->session->get(self::SEARCH_KEY_INGREDIENTS, null);
    }

    protected function searchByIngredients(QueryBuilder $queryBuilder): void
    {
        $alias = self::SEARCH_QUERY_ALIAS;
        $queryBuilder
            ->leftJoin($alias . '.ingredientQuantities', 'iq')
            ->andWhere('iq.ingredient IN (:ingredients)')
            ->setParameter('ingredients', $this->getSearchIngredients());
    }

    protected function paginate(QueryBuilder $queryBuilder): void
    {
        //@TODO
    }

    public function setPhrase(?string $phrase): void
    {
        $this->session->set(self::SEARCH_KEY_PHRASE, empty($phrase) ? null : $phrase);
    }

    public function setIngredients(?array $ingredients): void
    {
        $this->session->set(self::SEARCH_KEY_INGREDIENTS, empty($ingredients) ? null : $ingredients);
    }

    public function toggleFavourite(Recipe $recipe, User $user): void
    {
        if ($recipe->isLikedBy($user)) {
            $recipe->setLikes($recipe->getLikes() - 1);
            $recipe->getUserFavourites()->removeElement($user);
            $user->getRecipeFavourites()->removeElement($recipe);
        } else {
            $recipe->setLikes($recipe->getLikes() + 1);
            $recipe->getUserFavourites()->add($user);
            $user->getRecipeFavourites()->add($recipe);
        }

        $this->entityManager->persist($user);
        $this->saveRecipe($recipe);
    }

    public function saveRecipe(Recipe $recipe): void
    {
        $this->entityManager->persist($recipe);
        $this->entityManager->flush();
    }


}

<?php

namespace App\Service;

use App\Entity\Recipe;
use App\Entity\User;
use App\Repository\IngredientRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Gedmo\Translatable\Query\TreeWalker\TranslationWalker;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Knp\Component\Pager\PaginatorInterface;
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
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(SessionInterface $session, EntityManagerInterface $entityManager, PaginatorInterface $paginator, RecipeRepository $recipeRepository, IngredientRepository $ingredientRepository)
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
        $this->recipeRepository = $recipeRepository;
        $this->ingredientRepository = $ingredientRepository;
        $this->paginator = $paginator;
    }

    public function getIngredientChoices(): array
    {
        $results = [];

        $ingredients = $this->ingredientRepository->createQueryBuilder('i', 'i.name')
            ->select('i.id, i.name')
            ->getQuery()
            ->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, TranslationWalker::class)
            ->getArrayResult();

        foreach ($ingredients as $key => $ingredientData) {
            $results[$key] = $ingredientData['id'];
        }

        return $results;
    }

    public function search(int $page): SlidingPagination
    {
        $queryBuilder = $this->recipeRepository->createQueryBuilder(self::SEARCH_QUERY_ALIAS);

        if ($this->getSearchPhrase()) {
            $this->searchByPhrase($queryBuilder);
        }

        if ($this->getSearchIngredients()) {
            $this->searchByIngredients($queryBuilder);
        }

        return $this->paginate($queryBuilder, $page);
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
            ->addGroupBy($alias . '.id')
            ->andHaving('count(iq.id) = :ingredientQuantity')
            ->setParameter('ingredients', $this->getSearchIngredients())
            ->setParameter('ingredientQuantity', count($this->getSearchIngredients()));
    }

    protected function paginate(QueryBuilder $queryBuilder, int $page): SlidingPagination
    {
        return $this->paginator->paginate($queryBuilder, $page, self::SEARCH_RESULTS_PER_PAGE, ['wrap-queries' => true]);
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

    public function getUserRecipes(User $user, int $page): SlidingPagination
    {
        $queryBuilder = $this->recipeRepository->createQueryBuilder('r')
            ->andWhere('r.user = :user')
            ->setParameter('user', $user);

        return $this->paginate($queryBuilder, $page);
    }


}

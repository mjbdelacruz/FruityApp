<?php

namespace App\Controller;

use App\Entity\Nutrition;
use App\Exception\EntityLimitException;
use App\Service\FruitService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class FruitController extends AbstractController
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var FruitService
     */
    private $fruitService;

    /**
     * @param FruitService $fruitService
     * @param Environment $twig
     */
    public function __construct(FruitService $fruitService, Environment $twig)
    {
        $this->fruitService = $fruitService;
        $this->twig = $twig;
    }

    /**
     * @Route("/fruits", name="fruits", methods={"GET"})
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function index(): Response
    {
        $fruits = $this->fruitService->getFruits();

        return new Response(
            $this->twig->render('index.html.twig', [
                'fruits' => $fruits,
            ])
        );
    }

    /**
     * @Route("/fruits/favorites", name="get_favorites", methods={"GET"})
     */
    public function getFavorites(): Response
    {
        $fruits = $this->fruitService->getFruits(true);

        if (!empty($fruits)) {
            $totalCarbs = 0;
            $totalProtein = 0;
            $totalFat = 0;
            $totalCalories = 0;
            $totalSugar = 0;
            foreach ($fruits as $fruit) {
                $totalCarbs += $fruit->getNutritions()->getCarbohydrates();
                $totalProtein += $fruit->getNutritions()->getProtein();
                $totalFat += $fruit->getNutritions()->getFat();
                $totalCalories += $fruit->getNutritions()->getCalories();
                $totalSugar += $fruit->getNutritions()->getSugar();
            }

            $nutritionSummary = new Nutrition(
                null,
                $totalCarbs,
                $totalProtein,
                $totalFat,
                $totalCalories,
                $totalSugar
            );
        }

        return new Response(
            $this->twig->render('favorites.html.twig', [
                'fruits' => $fruits,
                'nutritionSummary' => $nutritionSummary ?? null,
            ])
        );
    }

    /**
     * @Route("/fruits/favorites", name="add_to_favorites", methods={"POST"})
     */
    public function addToFavorites(Request $request): RedirectResponse
    {
        $fruitId = $request->get('fruit_id');
        try {
            $fruit = $this->fruitService->getFruit($fruitId);

            $this->fruitService->addToFavorite($fruit);

            $this->addFlash('add_to_favorites_success', sprintf('Successfully added %s to favorites', $fruit->getName()));
        } catch (EntityLimitException $exception) {
            $this->addFlash('add_to_favorites_error', $exception->getMessage());
        }

        return $this->redirectToRoute('fruits');
    }
}

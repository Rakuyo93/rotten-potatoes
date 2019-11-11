<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    /**
     * @Route("/categories", name="show_categories")
     */
    public function showCategories(CategoryRepository $repo)
    {
        return $this->render('category/show_categories.html.twig', [
            'categories' => $repo->findBy([], ['title' => 'ASC'])
        ]);
    }

    /**
     * @Route("/category/{slug}", name="show_category")
     */
    public function showCategory(Category $category)
    {
        return $this->render('category/show_category.html.twig', [
            'category' => $category
        ]);
    }
}

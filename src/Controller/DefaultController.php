<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(MovieRepository $repo)
    {
        return $this->render('default/index.html.twig', [
            'lastReleased' => $repo->findLastReleasedMovies(),
            'bestMovies' => $repo->findBestMoviesByAvgRatings(),
            'worstMovies' => $repo->findWorstMoviesByAvgRatings()
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Rating;
use App\Form\RatingType;
use App\Repository\MovieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MovieController extends AbstractController
{
    /**
     * @Route("/movie/{slug}", name="show_movie")
     */
    public function showMovie(Movie $movie, ObjectManager $manager, Request $request, Security $security)
    {
        $user = $security->getUser();
        $rating = new Rating();
        $form = $this->createForm(RatingType::class, $rating);
        $form->handleRequest($request);

        // Requete DQL aller chercher dans le movie si l'utilisateur a déjà posté un comment

        if ($form->isSubmitted() && $form->isValid() && $this->isGranted('ROLE_USER')) {
            $rating->setAuthor($user)
                ->setMovie($movie)
                ->setCreatedAt(new \DateTime());

            $manager->persist($rating);
            $manager->flush();

            return $this->redirectToRoute('show_movie', ["slug" => $movie->getSlug()]);
        }

        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
            'average' => $movie->getAverageRating(),
            'ratingForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/search", name="movie_search")
     */
    public function search(Request $request, MovieRepository $repo)
    {
        $search = $request->query->get('search', null);

        $results = [];

        if ($search) {
            $results = $repo->findBySearch($search);
        }

        return $this->render('movie/search.html.twig', [
            'search' => $search,
            'results' => $results
        ]);
    }
}

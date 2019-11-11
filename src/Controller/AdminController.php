<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MovieRepository;
use App\Entity\Movie;
use Doctrine\Common\Persistence\ObjectManager;
use App\Repository\RatingRepository;
use App\Entity\Rating;
use App\Form\MovieType;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/movie", name="admin_movie")
     */
    public function showMovies(MovieRepository $repo)
    {
        return $this->render('admin/show_movies.html.twig', [
            'movies' => $repo->findAll()
        ]);
    }

    /**
     * @Route("/admin/movie/new", name="new_movie")
     */
    public function newMovie(Request $request, ObjectManager $manager)
    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($movie);
            $manager->flush();

            return $this->redirectToRoute('admin_movie');
        }

        return $this->render('admin/new.html.twig', [
            'movieForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/movie/{id}/edit", name="edit_movie")
     */
    public function editMovie(Movie $movie, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($movie);
            $manager->flush();

            return $this->redirectToRoute('admin_movie');
        }

        return $this->render('admin/new.html.twig', [
            'movieForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/movie/delete/{id}", name="delete_movie")
     */
    public function deleteMovie(Movie $movie, ObjectManager $manager)
    {
        $manager->remove($movie);
        $manager->flush();

        return $this->redirectToRoute('admin_movie');
    }

    /**
     * @Route("/admin/rating", name="admin_rating")
     */
    public function showRatings(RatingRepository $repo)
    {
        return $this->render('admin/show_ratings.html.twig', [
            'ratings' => $repo->findAll()
        ]);
    }

    /**
     * @Route("/admin/rating/delete/{id}", name="delete_rating")
     */
    public function deleteRating(Rating $rating, ObjectManager $manager)
    {
        $manager->remove($rating);
        $manager->flush();

        return $this->redirectToRoute('admin_rating');
    }
}

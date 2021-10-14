<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class MovieAdminController extends AbstractController
{
    /**
     * @Route("/", name="movie_index", methods="GET")
     */
    public function index(MovieRepository $movieRepository, $adminEnabled): Response
    {
        if (!$adminEnabled) {
            throw $this->createAccessDeniedException();
        }

        return $this->render(
            'movie/index.html.twig',
            [
                'movies' => $movieRepository->findBy(
                    [],
                    ['yearFeasted' => 'DESC']
                )
            ]
        );
    }

    /**
     * @Route("/new", name="movie_new", methods="GET|POST")
     */
    public function new(Request $request, $adminEnabled): Response
    {
        if (!$adminEnabled) {
            throw $this->createAccessDeniedException();
        }

        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($movie);
            $em->flush();

            $this->addFlash('notice', "$movie was created.");

            return $this->redirectToRoute('movie_index');
        }

        return $this->render('movie/new.html.twig', [
            'movie' => $movie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="movie_show", methods="GET")
     */
    public function show(Movie $movie, $adminEnabled): Response
    {
        if (!$adminEnabled) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('movie/show.html.twig', ['movie' => $movie]);
    }

    /**
     * @Route("/{id}/edit", name="movie_edit", methods="GET|POST")
     */
    public function edit(Request $request, Movie $movie, $adminEnabled, FlashBagInterface $flashBag): Response
    {
        if (!$adminEnabled) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(MovieType::class, $movie, [
            'include_title' => true,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('notice', "$movie was saved.");

            return $this->redirectToRoute('movie_edit', ['id' => $movie->getId()]);
        }

        return $this->render('movie/edit.html.twig', [
            'movie' => $movie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="movie_delete", methods="DELETE")
     */
    public function delete(Request $request, Movie $movie, $adminEnabled): Response
    {
        if (!$adminEnabled) {
            throw $this->createAccessDeniedException();
        }

        $title = $movie->getTitle();

        if ($this->isCsrfTokenValid('delete' . $movie->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($movie);
            $em->flush();
            $this->addFlash('notice', "$title was deleted.");
        }

        return $this->redirectToRoute('movie_index');
    }
}

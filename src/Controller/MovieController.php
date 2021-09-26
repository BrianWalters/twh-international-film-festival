<?php


namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Movie;
use App\Entity\Rating;
use App\Form\CommentType;
use App\Form\RatingType;
use App\Repository\CommentRepository;
use App\Repository\MovieRepository;
use App\Service\DateProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class MovieController extends AbstractController
{
    /**
     * @Route("/movie/{movie}", name="movie_details")
     */
    public function movieDetailsAction(
        Movie $movie,
        CommentRepository $commentRepository,
        ?Request $request = null
    ) {
        $comment = new Comment();
        $comment->setMovie($movie);
        $commentForm = $this->createForm(CommentType::class, $comment, [
            'action' => $this->generateUrl('submit_comment'),
        ]);

        if ($request) {
            $commentForm->handleRequest($request);
        }

        $comments = $commentRepository->findBy(
            ['movie' => $movie],
            ['createdAt' => 'DESC'],
            5
        );

        $rating = new Rating();
        $rating->setMovie($movie);
        $ratingForm = $this->createForm(RatingType::class, $rating, [
            'action' => $this->generateUrl('submit_rating'),
        ]);

        if ($request) {
            $ratingForm->handleRequest($request);
        }

        return $this->render('movie.html.twig', [
            'readOnly' => $movie->isReadOnly(),
            'ratingForm' => $ratingForm->createView(),
            'commentForm' => $commentForm->createView(),
            'comments' => $comments,
            'movie' => $movie,
        ]);
    }

    /**
     * @Route("/comment", name="submit_comment", methods={"POST"})
     */
    public function submitComment(Request $request, EntityManagerInterface $entityManager)
    {
        $comment = new Comment();

        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            if ($comment->getMovie()->isReadOnly())
                throw new AccessDeniedException();

            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('notice', 'Your comment was added.');
            return $this->redirectToRoute('movie_details', [
                'movie' => $comment->getMovie()->getId(),
            ]);
        }

        $this->addFlash('error', 'There were errors submitting your comment.');

        return $this->forward('App\Controller\MovieController:movieDetailsAction', [
            'movie' => $comment->getMovie()->getId(),
        ]);
    }

    /**
     * @Route("/rating", name="submit_rating", methods={"POST"})
     */
    public function submitRating(
        Request $request,
        EntityManagerInterface $entityManager,
        DateProvider $dateProvider
    ) {
        $rating = new Rating();

        $ratingForm = $this->createForm(RatingType::class, $rating);
        $ratingForm->handleRequest($request);

        $movie = $rating->getMovie();
        if ($movie->isReadOnly())
            throw new AccessDeniedException();

        if ($dateProvider->getToday() < $movie->getStartTime()) {
            $this->addFlash('error', "You can't add a rating before you've seen the movie you fucking tosser!");
            return $this->redirectToRoute('movie_details', [
                'movie' => $movie->getId(),
            ]);
        }

        if ($ratingForm->isSubmitted() && $ratingForm->isValid()) {
            $entityManager->persist($rating);
            $entityManager->flush();
            $this->addFlash('notice', 'Your rating was added.');
            return $this->redirectToRoute('movie_details', [
                'movie' => $movie->getId(),
            ]);
        }

        $this->addFlash('error', 'There were errors submitting your feedback.');

        return $this->forward('App\Controller\MovieController:movieDetailsAction', [
            'movie' => $rating->getMovie()->getId(),
        ]);
    }

    /**
     * @Route("/movie/{movie}/comments", name="movie_comments")
     */
    public function movieComments(Movie $movie, CommentRepository $commentRepository)
    {
        $comments = $commentRepository->findBy([
                                                   'movie' => $movie,
                                               ], [
                                                   'createdAt' => 'ASC'
                                               ]);

        return $this->render('page/comments.html.twig', [
            'comments' => $comments,
            'movie' => $movie,
        ]);
    }

    /**
     * @Route("/past", name="movie_past_index")
     */
    public function pastIndex(MovieRepository $movieRepository)
    {
        $currentYear = (int)(new \DateTime('now'))->format('Y');

        $movies = $movieRepository->findAllExceptYear($currentYear);

        $moviesByYear = [];

        while ($movie = array_pop($movies)) {
            $moviesByYear[$movie->getYearFeasted()][] = $movie;
        }

        return $this->render('page/past-index.html.twig', [
            'moviesByYear' => $moviesByYear,
        ]);
    }

    /**
     * @Route("/past/{id}", name="movie_past_detail")
     */
    public function pastMovie($id, MovieRepository $movieRepository): Response
    {
        $movie = $movieRepository->find($id);

        if (!$movie) {
            throw $this->createNotFoundException();
        }

        return $this->render('page/past-movie.html.twig', [
            'movie' => $movie,
        ]);
    }
}
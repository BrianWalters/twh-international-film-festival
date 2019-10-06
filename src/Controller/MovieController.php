<?php


namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Movie;
use App\Entity\Rating;
use App\Form\CommentType;
use App\Form\RatingType;
use App\Repository\CommentRepository;
use App\Repository\RatingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class MovieController extends AbstractController
{
    /**
     * @Route("/movie/{movie}", name="movie_details")
     */
    public function movieDetailsAction(Request $request, Movie $movie, CommentRepository $commentRepository)
    {
        $comment = new Comment();
        $comment->setMovie($movie);
        $commentForm = $this->createForm(CommentType::class, $comment, [
            'action' => $this->generateUrl('submit_comment'),
        ]);
        $commentForm->handleRequest($request);

        $comments = $commentRepository->findBy(
            [ 'movie' => $movie ],
            [ 'createdAt' => 'DESC' ],
            5
        );

        $rating = new Rating();
        $rating->setMovie($movie);
        $ratingForm = $this->createForm(RatingType::class, $rating, [
            'action' => $this->generateUrl('submit_rating'),
        ]);
        $ratingForm->handleRequest($request);

        return $this->render('movie.html.twig', [
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
    public function submitRating(Request $request, EntityManagerInterface $entityManager)
    {
        $rating = new Rating();

        $ratingForm = $this->createForm(RatingType::class, $rating);
        $ratingForm->handleRequest($request);

        if ($ratingForm->isSubmitted() && $ratingForm->isValid()) {
            $entityManager->persist($rating);
            $entityManager->flush();
            $this->addFlash('notice', 'Your rating was added.');
            return $this->redirectToRoute('movie_details', [
                'movie' => $rating->getMovie()->getId(),
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
}
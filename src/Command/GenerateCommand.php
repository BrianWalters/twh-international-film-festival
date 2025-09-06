<?php

namespace App\Command;

use App\Controller\HomeController;
use App\Controller\MovieController;
use App\Entity\Movie;
use App\OMDB\API;
use App\Repository\CommentRepository;
use App\Repository\MovieRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

class GenerateCommand extends Command
{
    protected static $defaultName = 'app:generate';
    /**
     * @var HomeController
     */
    private $homeController;
    /**
     * @var MovieRepository
     */
    private $movieRepository;
    private $filesystem;
    /**
     * @var MovieController
     */
    private $movieController;
    /**
     * @var CommentRepository
     */
    private $commentRepository;
    /**
     * @var bool
     */
    private $readOnly;
    private API $API;

    public function __construct(
                                HomeController $homeController,
                                MovieRepository $movieRepository,
                                MovieController $movieController,
                                CommentRepository $commentRepository,
                                API $API,
                                $readOnly = false)
    {
        parent::__construct();
        $this->homeController = $homeController;
        $this->movieRepository = $movieRepository;
        $this->filesystem = new Filesystem();
        $this->movieController = $movieController;
        $this->commentRepository = $commentRepository;
        $this->API = $API;
        $this->readOnly = $readOnly;
    }

    protected function configure()
    {
        $this->setDescription('Generates the static site');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        if ($this->readOnly) {
            $io->text('Generating site in read only mode.');
            $this->clean();
            $this->copyStatic();

            $this->generateHome();
            $this->generateMovies();
            $this->generateComments();

            $io->success('Done.');
        } else
            $io->error('This command only makes sense if the site is in read only mode.');
    }

    private function generateHome()
    {
        $homeResponse = $this->homeController->index($this->movieRepository, $this->API);
        $this->filesystem->dumpFile('build/index.html', $homeResponse->getContent());
    }

    private function generateMovies()
    {
        /* @var $movies Movie[] */
        $movies = $this->movieRepository->findAll();

        foreach ($movies as $movie) {
            $id = $movie->getId();
            $movieResponse = $this->movieController->movieDetailsAction($movie, $this->commentRepository);
            $path = "build/movie/$id/index.html";
            $this->filesystem->dumpFile($path, $movieResponse->getContent());
        }
    }

    private function generateComments()
    {
        /* @var $movies Movie[] */
        $movies = $this->movieRepository->findAll();

        foreach ($movies as $movie) {
            $id = $movie->getId();
            $commentsResponse = $this->movieController->movieComments($movie, $this->commentRepository);
            $path = "build/movie/$id/comments/index.html";
            $this->filesystem->dumpFile($path, $commentsResponse->getContent());
        }
    }

    private function clean()
    {
        $this->filesystem->remove('build');
    }

    private function copyStatic()
    {
        $this->filesystem->mirror('public/bootstrap-4.5.2-dist', 'build/bootstrap-4.5.2-dist');
        $this->filesystem->mirror('public/fontawesome-free-5.11.2-web', 'build/fontawesome-free-5.11.2-web');
        $this->filesystem->mirror('public/images', 'build/images');
        $this->filesystem->mirror('public/js', 'build/js');
        $this->filesystem->copy('public/custom.css', 'build/custom.css');
    }
}

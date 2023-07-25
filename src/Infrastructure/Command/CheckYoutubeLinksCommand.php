<?php

namespace App\Infrastructure\Command;

use App\Infrastructure\Persistence\Entity\Song;
use App\Infrastructure\Service\LinkYoutubeValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:check-youtube-links',description: 'Check the validity of YouTube links for songs')]
class CheckYoutubeLinksCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private LinkYoutubeValidator $linkYouTubeValidator;

    public function __construct(EntityManagerInterface $entityManager, LinkYoutubeValidator $linkYouTubeValidator)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->linkYouTubeValidator = $linkYouTubeValidator;
    }



    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        dd('yo jonas');
        $songRepository = $this->entityManager->getRepository(Song::class);
        $songs = $songRepository->findAll();

        foreach ($songs as $song) {
            $isValid = $this->linkYouTubeValidator->isValidYouTubeLink($song);
            $song->setIsValid($isValid);
            $this->entityManager->persist($song);
        }

        $this->entityManager->flush();

        $output->writeln('YouTube links checked and updated successfully.');

        return Command::SUCCESS;
    }
}

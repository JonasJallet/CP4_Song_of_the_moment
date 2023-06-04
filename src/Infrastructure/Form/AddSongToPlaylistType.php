<?php

namespace App\Infrastructure\Form;

use App\Infrastructure\Persistence\Entity\Playlist;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class AddSongToPlaylistType extends AbstractType
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();

        $builder
            ->add('playlist', EntityType::class, [
                'class' => Playlist::class,
                'choices' => $user->getPlaylists(),
                'choice_label' => 'name',
                'expanded' => true,
                'multiple' => false,
                'label' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Add Song',
            ]);
    }
}

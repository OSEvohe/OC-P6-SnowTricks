<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\TrickGroup;
use App\Entity\TrickMedia;
use App\Entity\User;
use App\Entity\UserTrick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public const trickTitle = "Trick Name";
    public const trickDescription = "Le mieux c’est de s’entrainer à le faire sur un trampoline car le mouvement est le même.";

    public const trickMediaVideo =
        <<<EOF
<iframe width="560" height="315" src="https://www.youtube.com/embed/4JfBfQpG77o" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
EOF;


    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)

    {

        /*
         * Trick Groups
         */
        $trickGroup = new TrickGroup();
        $trickGroup
            ->setName("Groupe 1")
            ->setSlug("groupe1");
        $manager->persist($trickGroup);

        /*
         * Users
         */
        $user = new User();
        $user
            ->setDisplayName("Sebastien")
            ->setEmail("user1@mail.com")
            ->setPassword($this->passwordEncoder->encodePassword($user, 'password'));
        $manager->persist($user);

        $user2 = new User();
        $user2
            ->setDisplayName("Nicolas")
            ->setEmail("user2@mail.com")
            ->setPassword($this->passwordEncoder->encodePassword($user, 'password'));
        $manager->persist($user2);

        /*
         * Trick Medias
         */

        $media = new TrickMedia();
        $media
            ->setContent('figure1.jpg')
            ->setType(TrickMedia::MEDIA_TYPE_IMAGE)
            ->setAlt('Figure 1');
        $manager->persist($media);

        $media2 = new TrickMedia();
        $media2
            ->setContent(self::trickMediaVideo)
            ->setType(TrickMedia::MEDIA_TYPE_VIDEO)
            ->setAlt('Video de la figure');
        $manager->persist($media2);


        /*
         * Tricks
         */
        $trick = new Trick();
        $trick
            ->setName('Le Trick "720"')
            ->setDescription(self::trickDescription)
            ->setTrickGroup($trickGroup)
            ->setUser($user)
            ->setCover($media)
            ->addTrickMedium($media)
            ->addTrickMedium($media2);
        $manager->persist($trick);

        /*
         * Comments
         */
        $comment = new Comment();
        $comment
            ->setContent("Pas mal mais je pense qu'on peut faire cette figure plus facilement")
            ->setUser($user)
            ->setTrick($trick);
        $manager->persist($comment);

        /*
         * Contributors (UserTrick)
         */
        $contributor = new UserTrick();
        $contributor
            ->setTrick($trick)
            ->setUser($user2);
        $manager->persist($contributor);

        $manager->flush();
    }
}
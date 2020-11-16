<?php


namespace App\DataFixtures;


use App\Entity\Trick;
use App\Entity\TrickGroup;
use App\Entity\TrickMedia;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Sample data
 * This fixtures will add some tricks and commments
 *
 * Class TricksFixture
 * @package App\DataFixtures
 */
class TricksFixture extends Fixture implements FixtureGroupInterface
{
    private $passwordEncoder;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $user1 = new User();
        $user1->setDisplayName("User1");
        $user1->setEmail("user1@snowtricks");
        $user1->setPassword($this->passwordEncoder->encodePassword($user1, 'Password'));
        $manager->persist($user1);

        $john = new User();
        $john->setDisplayName("John Doe");
        $john->setEmail("john@snowtricks");
        $john->setPassword($this->passwordEncoder->encodePassword($user1, 'Password'));
        $john->addRole(USER::USER_VERIFIED);
        $manager->persist($john);

        $grabs = new TrickGroup();
        $grabs->setName('Grabs');
        $manager->persist($grabs);

        $flips = new TrickGroup();
        $flips->setName('Flips');
        $manager->persist($flips);

        $rotations = new TrickGroup();
        $rotations->setName('Rotations');
        $manager->persist($rotations);

        $slides = new TrickGroup();
        $slides->setName('Slides');
        $manager->persist($slides);

        $manager->flush();

        foreach ($this->getSamplesTricks() as $data) {
            $trick = $data['trick'];
            $cover = $data['cover'];
            $video = $data['video'];
            $t = new Trick();
            $t->setName($trick['name']);
            $t->setDescription($trick['description']);
            $g = $trick['trickGroup'];
            $t->setTrickGroup($$g);
            $t->addTrickMedium($c = new TrickMedia());
            $c->setContent($cover['content']);
            $c->setAlt($cover['alt']);
            $c->setType($cover['type']);
            $t->addTrickMedium($c);
            $t->setCover($c);
            $t->addContributor($john);
            $t->setUser($john);
            $t->addTrickMedium($v = new TrickMedia());
            $v->setContent($video['content']);
            $v->setAlt($video['alt']);
            $v->setType($video['type']);

            $manager->persist($t);
        }

        $manager->flush();


    }

    public static function getGroups(): array
    {
        return ['samples_data'];
    }

    private function getSamplesTricks()
    {
        $tricks[] =
            [
                'trick' => [
                    'name' => 'Le 180',
                    'description' => 'Un 180 désigne un demi-tour, soit 180 degrés d\'angle',
                    'trickGroup' => 'rotations'
                ],
                'cover' => [
                    'content' => 'Twist-180-5fae96c2d4d2f.jpeg',
                    'alt' => 'Twist 180',
                    'type' => TrickMedia::MEDIA_TYPE_IMAGE
                ],
                'video' => [
                    'content' => 'https://www.youtube.com/watch?v=XyARvRQhGgk',
                    'alt' => 'How to Twist 180 on a Snowboard - Snowboarding Tricks',
                    'type' => TrickMedia::MEDIA_TYPE_VIDEO
                ],
            ];
        $tricks[] =
            [
                'trick' => [
                    'name' => 'Japan air',
                    'description' => 'Japan ou Japan air

Saisie de l\'avant de la planche, avec la main avant, du côté de la carre frontside.
',
                    'trickGroup' => 'grabs'
                ],
                'cover' => [
                    'content' => 'Japan-Grab-5fae981926d2f.jpeg',
                    'alt' => 'Japan Grab',
                    'type' => TrickMedia::MEDIA_TYPE_IMAGE
                ],
                'video' => [
                    'content' => 'https://www.youtube.com/watch?v=CzDjM7h_Fwo',
                    'alt' => 'How To Japan Grab - Snowboard Trick Tutorial',
                    'type' => TrickMedia::MEDIA_TYPE_VIDEO
                ],
            ];
        $tricks[] =
            [
                'trick' => [
                    'name' => 'Le Truck Driver',
                    'description' => 'Saisie du carre avant et carre arrière avec chaque main (comme tenir un volant de voiture)
',
                    'trickGroup' => 'grabs'
                ],
                'cover' => [
                    'content' => 'truck-driver-1-5fae9aceaa2f4.jpeg',
                    'alt' => 'Le Truck Driver par Frederic Malard',
                    'type' => TrickMedia::MEDIA_TYPE_IMAGE
                ],
                'video' => [
                    'content' => 'https://www.youtube.com/watch?v=Ey5elKTrUCk&ab_channel=SnowboardProCamp',
                    'alt' => '10 Snowboard Tricks To Learn Outside The Park',
                    'type' => TrickMedia::MEDIA_TYPE_VIDEO
                ],
            ];
        $tricks[] =
            [
                'trick' => [
                    'name' => '180 Backflip',
                    'description' => 'Rotation en arrière à 180°',
                    'trickGroup' => 'flips'
                ],
                'cover' => [
                    'content' => 'backflip180-5fae9d39c82b3.jpeg',
                    'alt' => 'Backflip 180',
                    'type' => TrickMedia::MEDIA_TYPE_IMAGE
                ],
                'video' => [
                    'content' => 'https://www.youtube.com/watch?v=arzLq-47QFA&t=128s&ab_channel=SnowboardProCamp',
                    'alt' => 'How To Layout a Backflip - Snowboarding Trick Tutorial',
                    'type' => TrickMedia::MEDIA_TYPE_VIDEO
                ],
            ];
        $tricks[] =
            [
                'trick' => [
                    'name' => 'Le stalefish',
                    'description' => 'saisie de la carre backside de la planche entre les deux pieds avec la main arrière ;',
                    'trickGroup' => 'grabs'
                ],
                'cover' => [
                    'content' => 'maxresdefault-5fae9e7cc679a.jpeg',
                    'alt' => 'Stale Grab',
                    'type' => TrickMedia::MEDIA_TYPE_IMAGE
                ],
                'video' => [
                    'content' => 'https://www.youtube.com/watch?v=f9FjhCt_w2U&ab_channel=SnowboarderMagazine',
                    'alt' => 'How to Grab Stalefish | TransWorld SNOWboarding Grab Directory',
                    'type' => TrickMedia::MEDIA_TYPE_VIDEO
                ],
            ];
        $tricks[] =
            [
                'trick' => [
                    'name' => 'Nose grab',
                    'description' => 'Saisie de la partie avant de la planche, avec la main avant.',
                    'trickGroup' => 'grabs'
                ],
                'cover' => [
                    'content' => 'Nosegrab-5fae9ef11ce0e.jpeg',
                    'alt' => 'Le Nosegrab',
                    'type' => TrickMedia::MEDIA_TYPE_IMAGE
                ],
                'video' => [
                    'content' => 'https://www.youtube.com/watch?v=M-W7Pmo-YMY',
                    'alt' => 'How to Nose Grab Snowboard - Snowboarding Tricks',
                    'type' => TrickMedia::MEDIA_TYPE_VIDEO
                ],
            ];
        $tricks[] =
            [
                'trick' => [
                    'name' => 'Seat belt',
                    'description' => 'Saisie du carre frontside à l\'arrière avec la main avant',
                    'trickGroup' => 'grabs'
                ],
                'cover' => [
                    'content' => 'seatbelt-alex-sherman-back-fenelon-1-5faea33d4a407.jpeg',
                    'alt' => 'Seat Belt',
                    'type' => TrickMedia::MEDIA_TYPE_IMAGE
                ],
                'video' => [
                    'content' => 'https://www.youtube.com/watch?v=4vGEOYNGi_c&feature=emb_logo&ab_channel=SnowboarderMagazine',
                    'alt' => 'How to Grab Seatbelt | TransWorld SNOWboarding Grab Directory',
                    'type' => TrickMedia::MEDIA_TYPE_VIDEO
                ],
            ];
        $tricks[] =
            [
                'trick' => [
                    'name' => 'Tail Grab',
                    'description' => 'Saisie de la partie arrière de la planche, avec la main arrière;',
                    'trickGroup' => 'grabs'
                ],
                'cover' => [
                    'content' => 'tailgrab-5faea455e64bb.jpeg',
                    'alt' => 'How to Tail Grab',
                    'type' => TrickMedia::MEDIA_TYPE_IMAGE
                ],
                'video' => [
                    'content' => 'https://www.youtube.com/watch?v=YAElDqyD-3I&ab_channel=SnowboarderMagazine',
                    'alt' => 'How to Tail Grab | TransWorld SNOWboarding Grab Directory',
                    'type' => TrickMedia::MEDIA_TYPE_VIDEO
                ],
            ];
        $tricks[] =
            [
                'trick' => [
                    'name' => 'Indy',
                    'description' => 'saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière
',
                    'trickGroup' => 'grabs'
                ],
                'cover' => [
                    'content' => 'Indy-5faea4fd935b1.jpeg',
                    'alt' => 'Indy',
                    'type' => TrickMedia::MEDIA_TYPE_IMAGE
                ],
                'video' => [
                    'content' => 'https://www.youtube.com/watch?v=6yA3XqjTh_w&ab_channel=SnowboardProCamp',
                    'alt' => 'How To Indy Grab - Snowboarding Tricks',
                    'type' => TrickMedia::MEDIA_TYPE_VIDEO
                ],
            ];
        $tricks[] =
            [
                'trick' => [
                    'name' => 'Le 720',
                    'description' => 'Rotation de deux tours complets',
                    'trickGroup' => 'rotations'
                ],
                'cover' => [
                    'content' => '720-5faea5814afd0.jpeg',
                    'alt' => 'Le 720',
                    'type' => TrickMedia::MEDIA_TYPE_IMAGE
                ],
                'video' => [
                    'content' => 'https://www.youtube.com/watch?v=4JfBfQpG77o&ab_channel=SnowboardProCamp',
                    'alt' => '720 Snowboard Trick Progression with TJ',
                    'type' => TrickMedia::MEDIA_TYPE_VIDEO
                ],
            ];


        return $tricks;
    }
}
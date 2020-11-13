<?php


namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
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

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setDisplayName("User1");
        $user->setEmail("user1@snowtricks");
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'Password'));
        $manager->persist($user);

        $user = new User();
        $user->setDisplayName("John Doe");
        $user->setEmail("john@snowtricks");
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'Password'));
        $user->addRole(USER::USER_VERIFIED);
        $manager->persist($user);


        $manager->flush();

    }

    public static function getGroups(): array
    {
        return ['sample_data'];
    }
}
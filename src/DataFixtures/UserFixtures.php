<?php


namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Create a user with admin role
 * You should change the password and Email once logged with this account
 * Use "Admin@snowtricks" as email and "Password" as password
 *
 * Class UserFixtures
 * @package App\DataFixtures
 */
class UserFixtures extends Fixture implements FixtureGroupInterface
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setDisplayName("Admin");
        $user->setEmail("Admin@snowtricks");
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'Password'));
        $user->addRole(User::USER_ADMIN);
        $user->addRole(User::USER_VERIFIED);
        $manager->persist($user);


        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['starting_users', 'samples_data'];
    }
}
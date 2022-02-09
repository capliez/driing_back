<?php

namespace App\DataFixtures;

use App\Entity\Language;
use App\Entity\Role;
use App\Entity\User;
use App\Repository\LanguageRepository;
use App\Repository\RoleRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    /**
     * @var UserPasswordHasherInterface
     */
    private $hasher;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    private $LANGUES = [
        ['name' => 'Français', 'shortname' => 'fr'],
        ['name' => 'English', 'shortname' => 'en']
    ];

    /**
     * @var LanguageRepository
     */
    private $languageRepository;

    /**
     * @var RoleRepository
     */
    private $roleRepository;

    /**
     * @param RoleRepository $roleRepository
     * @param LanguageRepository $languageRepository
     * @param UserPasswordHasherInterface $hasher
     */
    public function __construct(RoleRepository $roleRepository, LanguageRepository $languageRepository, UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
        $this->faker = Factory::create('fr_FR');
        $this->languageRepository = $languageRepository;
        $this->roleRepository = $roleRepository;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $this->createLanguages($manager);
        $this->createRoles($manager);
        $this->createdUser($manager);

        $manager->flush();
    }

    /**
     * @param $manager
     */
    public function createLanguages($manager)
    {
        foreach ($this->LANGUES as $LANGUE) {
            $newLang = new Language();
            $newLang->setName($LANGUE['name'])
                ->setShortname($LANGUE['shortname']);

            $manager->persist($newLang);
        }

        $manager->flush();
    }

    /**
     * @param $manager
     */
    public function createRoles($manager)
    {
        $ROLES = [
            ['label' => 'Super Administrateur', 'shortname' => 'ROLE_SUPER_ADMIN'],
            ['label' => 'Administrateur', 'shortname' => 'ROLE_ADMIN'],
            ['label' => 'Gardian', 'shortname' => 'ROLE_GUARDIAN'],
        ];

        foreach ($ROLES as $ROLE) {
            $newRole = new Role();
            $newRole->setLabel($ROLE['label'])
                ->setShortname($ROLE['shortname']);

            $manager->persist($newRole);
        }

        $manager->flush();
    }

    /**
     * @param $manager
     */
    public function createdUser($manager) {

        for ($i = 0; $i < 2; $i++) {
            $user = new User();
            $password = $this->hasher->hashPassword($user, 'Password77!');

            $user->setIsEnabled($this->faker->boolean())
                ->setEmail($this->faker->email)
                ->setLastName($this->faker->lastName)
                ->setFirstName($this->faker->firstName)
                ->setPhone($this->faker->phoneNumber)
                ->setUserRole($this->roleRepository->findOneBy(['shortname' => "ROLE_GUARDIAN"]))
                ->setLanguage($this->languageRepository->findOneBy(['shortname' => $this->faker->randomElement(['fr', 'en'])]))
                ->setPassword($password);

            $manager->persist($user);
        }

        $manager->flush();
    }
}

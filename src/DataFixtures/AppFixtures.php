<?php

namespace App\DataFixtures;

use App\Entity\Building;
use App\Entity\Language;
use App\Entity\Resident;
use App\Entity\Role;
use App\Entity\User;
use App\Repository\BuildingRepository;
use App\Repository\LanguageRepository;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
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
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var BuildingRepository
     */
    private $buildingRepository;

    /**
     * @param BuildingRepository $buildingRepository
     * @param UserRepository $userRepository
     * @param RoleRepository $roleRepository
     * @param LanguageRepository $languageRepository
     * @param UserPasswordHasherInterface $hasher
     */
    public function __construct(BuildingRepository $buildingRepository, UserRepository $userRepository, RoleRepository $roleRepository, LanguageRepository $languageRepository, UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
        $this->faker = Factory::create('fr_FR');
        $this->languageRepository = $languageRepository;
        $this->roleRepository = $roleRepository;
        $this->userRepository = $userRepository;
        $this->buildingRepository = $buildingRepository;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $this->createLanguages($manager);
        $this->createRoles($manager);
        $this->createGuardian($manager);
        $this->createAdmin($manager);
        $this->createBuilding($manager);
        $this->createResidents($manager);

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
    public function createGuardian($manager) {

        $user = new User();
        $password = $this->hasher->hashPassword($user, 'Password77!');

        $user->setIsEnabled($this->faker->boolean())
            ->setEmail($this->faker->email)
            ->setLastName($this->faker->lastName)
            ->setFirstName($this->faker->firstName)
            ->setPhone('0602231075')
            ->setUserRole($this->roleRepository->findOneBy(['shortname' => "ROLE_GUARDIAN"]))
            ->setLanguage($this->languageRepository->findOneBy(['shortname' => 'fr']))
            ->setPassword($password);

        $manager->persist($user);


        $manager->flush();
    }

    /**
     * @param $manager
     */
    public function createAdmin($manager) {

        $user = new User();
        $password = $this->hasher->hashPassword($user, 'Password77!');

        $user->setIsEnabled($this->faker->boolean())
            ->setEmail('capliezalexis@yahoo.fr')
            ->setLastName($this->faker->lastName)
            ->setFirstName($this->faker->firstName)
            ->setPhone('0602231074')
            ->setUserRole($this->roleRepository->findOneBy(['shortname' => "ROLE_ADMIN"]))
            ->setLanguage($this->languageRepository->findOneBy(['shortname' => 'fr']))
            ->setPassword($password);

        $manager->persist($user);


        $manager->flush();
    }

    public function createBuilding($manager) {
        $building = new Building();
        $building
            ->setName("Les 4 chemins")
            ->setIsEnabled(true)
            ->setCity('Aulney-sous-bois')
            ->setPostcode('93456')
            ->setCountry('France')
            ->setAddress('14 rue des chemins')
            ->setGuardian($this->userRepository->findAll()[0]);

        $manager->persist($building);
        $manager->flush();
    }

    public function createResidents($manager) {

        for ($i = 0; $i < 20; $i++) {
            $resident = new Resident();

            $resident->setIsEnabled($this->faker->boolean())
                ->setEmail($this->faker->email)
                ->setPhone($this->faker->phoneNumber)
                ->setBuilding($this->buildingRepository->findAll()[0])
                ->setLastName($this->faker->lastName)
                ->setPhone('0602231075');

            $manager->persist($resident);
        }

        $manager->flush();
    }

}

<?php

namespace App\DataFixtures;
use App\Entity\SuperAdmin;
use App\Entity\Profil;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
class SuperAdminFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;
  
   public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');
        $password="Nop@LEweDev221++";
        $userName = ['papadjibyniang@innovdigital.sn', 'abdoulaye.faye@innovdigital.sn', 'it-ops@innovidital.sn'];
        for ($i=0; $i <3 ; $i++) { 
            $admin=new SuperAdmin();
            //$profil = new Profil();
            $password=$this->encoder->hashPassword($admin,$password);
            $profil=$this->getReference('Super Admin');
            $admin  ->setPrenom($faker->firstName())
                    ->setNom($faker->lastName())
                    ->setUsername($userName[$i])
                    ->SetEmail($userName[$i])
                    ->setRoles(['ROLE_'.$profil -> getLiBelle()])
                    ->setAdresse($faker->address())
                    ->setTelephone($faker->PhoneNumber())
                    ->setPassword($password)
                    ->setProfil($profil);
         
            $manager->persist($admin);
    }
    $manager->flush();
  //  dd($admin);
}
    public function getDependencies() {
    return array(
        ProfilsFixtures::class,
    );
}
}

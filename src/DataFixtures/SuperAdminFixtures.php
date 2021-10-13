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
        $prenoms = ['papa djiby', 'abdoulaye', 'it-ops'];
        $noms = ['niang', 'faye', 'it-ops'];
        $userName = ['papadjibyniang@innovdigital.sn', 'abdoulaye.faye@innovdigital.sn', 'it-ops@innovidital.sn'];
        $telephones = [773731936, 772159696,772159696];
        for ($i=0; $i <3 ; $i++) { 
            $admin=new SuperAdmin();
            $profil = new Profil();
            $password=$this->encoder->hashPassword($admin,$password);
            $profil=$this->getReference('Super_Admin');
            $admin->setPrenom($prenoms[$i]);
            $admin->setNom($noms[$i]);
            $admin->setUsername($userName[$i]);
            $admin->SetEmail($userName[$i]);
            $admin->setRoles(["ROLE_Super_Admin"]);
            $admin->setAdresse($faker->address());
            $admin->setTelephone($telephones[$i]);
            $admin->setPassword($password);
            $admin->setProfil($profil);
         
            $manager->persist($admin);
    }
    $manager->flush();
  //  dd($admin);
}
    public function getDependencies()
{
    return array(
        ProfilsFixtures::class,
    );
}
}

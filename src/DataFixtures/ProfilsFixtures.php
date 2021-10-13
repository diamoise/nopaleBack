<?php

namespace App\DataFixtures;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Profil;
class ProfilsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $UsersProfiles=['Super_Admin','Distributeur'];
        foreach ($UsersProfiles as $Oneprofile) {
        $profil = new Profil();
        $profil->setLibelle($Oneprofile);
        $manager->persist($profil);
        $this->setReference($Oneprofile,$profil);        
        }
        $manager->flush();     
    }
}

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
        $profil = new Profil();
        $profil->setLibelle("Super Admin");
        $manager->persist($profil);
        $this->setReference("Super Admin",$profil);
        $manager->persist($profil);
        $manager->flush();     
    }
}

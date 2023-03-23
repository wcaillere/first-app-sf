<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Person;
use App\Entity\Skill;
use App\Entity\Student;
use App\Entity\Teacher;
use App\Factory\AddressFactory;
use App\Factory\SkillFactory;
use App\Factory\TeacherFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PersonFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        SkillFactory::createOne(['skillname' => 'Java']);
        SkillFactory::createOne(['skillname' => 'Python']);
        SkillFactory::createOne(['skillname' => 'Ruby']);
        SkillFactory::createOne(['skillname' => 'C++']);
        SkillFactory::createOne(['skillname' => 'javascript']);
        SkillFactory::createOne(['skillname' => 'PHP']);

        AddressFactory::createMany(20);
        TeacherFactory::createMany(50,
            function () {
                return ['address' => AddressFactory::random()];
            }
        );

        /*
        $teacher = new Teacher();

        $address = (new Address())->setStreet('5 rue du bac')
                                  ->setCity('Paris')
                                  ->setZipCode('75008');

        $teacher->setFirstName('Joseph')
                ->setLastName('Koudelka')
                ->setDateOfBirth(new \DateTime('1945-2-8'))
                ->setDailyRate(400)
                ->setAddress($address)
                ->addSkill((new Skill())->setSkillName('PHP'))
                ->addSkill((new Skill())->setSkillName('Java'))
                ->addSkill((new Skill())->setSkillName('Python'));

        $student = (new Student())->setFirstName('Sophie')
                                  ->setLastName('Calle')
                                  ->setDateOfBirth(new \DateTime('1988-2-8'))
                                  ->setEnrolledAt(new \DateTime('now - 5 months'))
                                  ->setAddress($address);

        $manager->persist($teacher);
        $manager->persist($student);
        $manager->flush();

        */
    }
}

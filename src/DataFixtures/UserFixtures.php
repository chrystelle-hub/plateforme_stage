<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
	private $encode;
 
    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encode = $encoder;
    }
	public function load(ObjectManager $manager)
	{
		$user=new User();
		$user -> setEmail('test');
		$user -> setNom('test');
		$user -> setPrenom('test');
		$user -> setDateCreationPassword(new \DateTime());
		$user -> setListePwd(array());
		$user -> setEtatCompte(1);
		$user ->setPassword( $this->encode->encodePassword(
                    $user,
                    'test'
                ));
		
		$manager->persist($user);
	
		$manager->flush();
	}

}
<?php
//https://symfony.com/doc/current/doctrine/events.html#doctrine-entity-listeners
namespace App\EventListener;

use App\Entity\Badge;
use App\Entity\User;
use Doctrine\ORM\EntityManager;

class SetAwardBadge
{

    protected $em;
    function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    
    public function postUpdate(User $user)
    {
        switch (true) {
            case (
                //Get Badge "Voyageur Aguerri"
                //points
                $user->getPoints() > 100 &&
                $user->getPoints() <= 500 &&
                //Posts published
                count($user->getReviews()) > 2 &&
                //Posts liked by others travelers
                count($user->getReviewLikes()) > 5 &&
                //Someone adds you as a favorite 
                count($user->getUsers()) > 5 &&
                //you've added someone to your favorites
                count($user->getFavoriteUser()) > 5 &&
                //dating from (in days)
                (int)($user->getCreatedAt()->diff(new \DateTime('now')))->format('%R%a') > 30):
                

                $badge = $this->em
                    ->getRepository(Badge::class)
                    ->find(2);
                $user->addBadge($badge);


                break;
            case (
                //Get Badge "Grand Voyageur"
                //points
                $user->getPoints() > 500 &&
                $user->getPoints() <= 1000 &&
                //Posts published
                count($user->getReviews()) > 4 &&
                //Posts liked by others travelers
                count($user->getReviewLikes()) > 10 &&
                //Someone adds you as a favorite 
                count($user->getUsers()) > 10 &&
                //you've added someone to your favorites
                count($user->getFavoriteUser()) > 10 &&
                //dating from (in days)
                (int)($user->getCreatedAt()->diff(new \DateTime('now')))->format('%R%a') > 180):

                //add rol
                $user->setRoles(['ROLE_MODERATOR']);


                $badge = $this->em
                    ->getRepository(Badge::class)
                    ->find(3);
                $user->addBadge($badge);
                break;
            case (
                //Get Badge "Globe-Trotter"
                //points
                $user->getPoints() > 1000 &&
                //Posts published
                count($user->getReviews()) > 8 &&
                //Posts liked by others travelers
                count($user->getReviewLikes()) > 30 &&
                //Someone adds you as a favorite 
                count($user->getUsers()) > 30 &&
                //you've added someone to your favorites
                count($user->getFavoriteUser()) > 30 &&
                //dating from (in days)
                (int)($user->getCreatedAt()->diff(new \DateTime('now')))->format('%R%a') > 730):

                $badge = $this->em
                    ->getRepository(Badge::class)
                    ->find(4);
                $user->addBadge($badge);
                break;
        }
        $this->em->persist($user);
        $this->em->flush();
    }
}

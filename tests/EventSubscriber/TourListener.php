<?php
namespace App\EventListener;

use Doctrine\Persistence\Event\LifecycleEventArgs;
use App\Entity\Tour;
use App\Event\EventoCancelaTour;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: Tour::class)]
class TourListener
{
//     // private $eventDispatcher;

//     // public function __construct(EventDispatcherInterface $eventDispatcher)
//     // {
//     //     $this->eventDispatcher = $eventDispatcher;
//     // }

//     public function onPreUpdate(EventoCancelaTour $eventoCancelaTour)
//     {
//         $tour = $eventoCancelaTour->getTour();
//     }

//     // public function postUpdate(Tour $tour, PostUpdateEventArgs $event)
//     // {
//     //     $changeSet = $args->getObjectManager()->getUnitOfWork()->getEntityChangeSet($tour);

//     //     if (isset($changeSet['disponible']) && $changeSet['disponible'][0] === true && $changeSet['disponible'][1] === false) {
//     //         $event = new EventoCancelaTour($tour);
//     //         $this->eventDispatcher->dispatch($event, EventoCancelaTour::NAME);
//     //     }
//     // }
}
<?php

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\EntityDesigner\EventListener;

use Bitter\EntityDesigner\Entity\Entity;
use Bitter\EntityDesigner\Generator\GeneratorService;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;

class EntityGeneratorSubscriber implements EventSubscriber, ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    protected $generatorService;
    protected $entityManager;

    public function __construct(
        GeneratorService $generatorService,
        EntityManagerInterface $entityManager
    )
    {
        $this->generatorService = $generatorService;
        $this->entityManager = $entityManager;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::postPersist,
            Events::postUpdate,
            Events::preRemove,
            Events::postRemove
        ];
    }

    /** @noinspection PhpUnused */
    public function postUpdate(
        $args
    )
    {
        /** @var Entity $entity */
        if ($args->getObject() instanceof Entity) {
            $entity = $args->getObject();

            $this->install($entity);
        } else {
            return; // The given entity is not relevant.
        }
    }

    /** @noinspection PhpUnused */
    public function postPersist(
        $args
    )
    {
        /** @var Entity $entity */
        if ($args->getObject() instanceof Entity) {
            $entity = $args->getObject();

            $this->install($entity);
        } else {
            return; // The given entity is not relevant.
        }
    }

    /** @noinspection PhpUnused */
    public function preRemove(
        $args
    )
    {
        /** @var Entity $entity */
        if ($args->getObject() instanceof Entity) {
            $entity = $args->getObject();

            $this->generatorService->setEntity($entity)->uninstall();
        } else {
            return; // The given entity is not relevant.
        }
    }

    /** @noinspection PhpUnused */
    public function postRemove(
        $args
    )
    {
        if ($args->getObject() instanceof Entity) {
            $this->generatorService->install();
        }
    }

    private function install(
        Entity $entity
    )
    {
        if ($entity->isTemp()) {
            return; // The entity is not ready yet.

        } else {

            $uow = $this->entityManager->getUnitOfWork();
            $uow->computeChangeSets(); // do not compute changes if inside a listener
            $changeSet = $uow->getEntityChangeSet($entity);

            if ((isset($changeSet["handle"]) && $changeSet["handle"][0] != $entity->getHandle()) ||
                (isset($changeSet["parentPage"]) && $changeSet["parentPage"][0] != $entity->getParentPage()) ||
                (isset($changeSet["packageHandle"]) && $changeSet["packageHandle"][0] != $entity->getPackageHandle())
            ) {

                /*
                 * the handle was changed. Need to remove the old files
                 */

                $prevEntityMock = new Entity();

                $prevEntityMock->setId($entity->getId());

                if (isset($changeSet["handle"])) {
                    $prevEntityMock->setHandle($changeSet["handle"][0]);
                } else {
                    $prevEntityMock->setHandle($entity->getHandle());
                }

                if (isset($changeSet["parentPage"])) {
                    $prevEntityMock->setParentPage($changeSet["parentPage"][0]);
                } else {
                    $prevEntityMock->setParentPage($entity->getParentPage());
                }

                if (isset($changeSet["packageHandle"])) {
                    $prevEntityMock->setPackageHandle($changeSet["packageHandle"][0]);
                } else {
                    $prevEntityMock->setPackageHandle($entity->getPackageHandle());
                }

                /*
                 * Delete the old version
                 */

                $this->generatorService->uninstallBlockTypes($prevEntityMock);
                $this->generatorService->uninstallBlockTypeSet($prevEntityMock);
                $this->generatorService->uninstallSinglePage($prevEntityMock);

                if ($prevEntityMock->hasValidPackageHandle()) {
                    $this->generatorService->updatePackageInstaller($prevEntityMock->getPackageHandle());
                }
            }

            /*
             * Generate the files
             */

            $this->generatorService->setEntity($entity)->install();

        }
    }
}
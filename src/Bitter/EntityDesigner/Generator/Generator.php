<?php

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\EntityDesigner\Generator;

use Bitter\EntityDesigner\Entity\Entity;
use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Database\Connection\Connection;
use Concrete\Core\File\Service\File;
use Concrete\Core\Package\Package;
use Concrete\Core\Entity\Package as PackageEntity;
use Concrete\Core\Package\PackageService;
use Concrete\Core\Page\Page;
use Concrete\Core\Utility\Service\Identifier;
use Concrete\Core\Utility\Service\Text;
use Doctrine\ORM\EntityManagerInterface;

abstract class Generator implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    protected $fileSystem;
    protected $textService;
    protected $packageService;
    /** @var Package */
    protected $pkg;
    /** @var Entity */
    protected $entity;
    protected $entityManager;
    protected $config;
    protected $identifierService;
    protected $connection;

    public function __construct(
        File $fileSystem,
        Text $textService,
        PackageService $packageService,
        EntityManagerInterface $entityManager,
        Repository $config,
        Identifier $identifierService,
        Connection $connection
    )
    {
        $this->fileSystem = $fileSystem;
        $this->textService = $textService;
        $this->packageService = $packageService;
        $this->pkg = $this->packageService->getByHandle("entity_designer");
        $this->entityManager = $entityManager;
        $this->config = $config;
        $this->identifierService = $identifierService;
        $this->connection = $connection;
    }

    public function hasFullDoctrineSupport()
    {
        if ($this->entity->hasValidPackageHandle()) {
            /** @var PackageEntity $packagesEntity */
            $packagesEntity = $this->entityManager->getRepository(PackageEntity::class)->findOneBy([
                "pkgHandle" => $this->entity->getPackageHandle()
            ]);

            if ($packagesEntity instanceof PackageEntity) {
                /** @var Package $packageController */
                $packageController = $packagesEntity->getController();

                return version_compare($packageController->getApplicationVersionRequired(), "8.0", ">=");
            }
        }

        return version_compare(APP_VERSION, '8.0', '>=');
    }

    public function getNamespace()
    {
        if ($this->entity->hasValidPackageHandle()) {
            $namespace = "Concrete\Package\\" . $this->textService->camelcase($this->entity->getPackageHandle());
        } else {
            $namespace = "Application";
        }

        return $namespace;
    }

    public function getTableAlias($tableName)
    {
        global $tableAliases;

        if (!isset($tableAliases)) {
            $tableAliases = [];
        }

        if (!isset($tableAliases[$tableName])) {
            $tableAliases[$tableName] = "t" . count($tableAliases);
        }

        return $tableAliases[$tableName];
    }

    public function getPath()
    {
        $path = "";

        if ($this->entity->hasValidPackageHandle()) {
            /** @var PackageEntity $packagesEntity */
            $packagesEntity = $this->entityManager->getRepository(PackageEntity::class)->findOneBy([
                "pkgHandle" => $this->entity->getPackageHandle()
            ]);

            if ($packagesEntity instanceof PackageEntity) {
                /** @var Package $packageController */
                $packageController = $packagesEntity->getController();

                $path = $packageController->getPackagePath();
            }
        } else {
            $path = DIR_BASE . "/application";
        }

        return $path;
    }

    public function getSrcPath()
    {
        $path = "";

        if ($this->entity->hasValidPackageHandle()) {
            /** @var PackageEntity $packagesEntity */
            $packagesEntity = $this->entityManager->getRepository(PackageEntity::class)->findOneBy([
                "pkgHandle" => $this->entity->getPackageHandle()
            ]);

            if ($packagesEntity instanceof PackageEntity) {
                /** @var Package $packageController */
                $packageController = $packagesEntity->getController();

                $autoloaderRegistries = array_keys($packageController->getPackageAutoloaderRegistries());

                if (count($autoloaderRegistries) > 0) {
                    $path = $packageController->getPackagePath() . DIRECTORY_SEPARATOR . (string)array_shift($autoloaderRegistries);
                } else {
                    $path = $packageController->getPackagePath() . DIRECTORY_SEPARATOR . "src";
                }
            }

        } else {
            $path = DIR_BASE . "/application/src";
        }

        return $path;
    }

    public function getSrcNamespace($entity = null)
    {
        if ($entity === null) {
            $entity = $this->getEntity();
        }

        $namespace = "";

        if ($entity->hasValidPackageHandle()) {
            /** @var PackageEntity $packagesEntity */
            $packagesEntity = $this->entityManager->getRepository(PackageEntity::class)->findOneBy([
                "pkgHandle" => $entity->getPackageHandle()
            ]);

            if ($packagesEntity instanceof PackageEntity) {
                /** @var Package $packageController */
                $packageController = $packagesEntity->getController();

                $autoloaderRegistries = $packageController->getPackageAutoloaderRegistries();

                if (count($autoloaderRegistries) > 0) {
                    $namespace = (string)array_shift($autoloaderRegistries);
                } else {
                    $namespace = "Concrete\Package\\" . $this->textService->camelcase($entity->getPackageHandle()) . "\Src";
                }
            }

        } else {
            if ($this->config->get('app.enable_legacy_src_namespace')) {
                $namespace = "Application\Src";
            } else {
                $namespace = "Application";
            }
        }

        return $namespace;
    }

    public function getRelPath()
    {
        return $this->entity->getParentPage();
    }

    public function getRelNamespace()
    {
        $baseNamespace = "";

        foreach (explode("/", substr($this->entity->getParentPage(), 11)) as $pagePath) {
            if (strlen($pagePath) > 0) {
                $baseNamespace .= "\\" . str_replace("_", " ", $this->textService->camelcase($pagePath));
            }
        }

        return $baseNamespace;
    }

    /**
     * @return Entity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param Entity $entity
     * @return Generator
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }
}
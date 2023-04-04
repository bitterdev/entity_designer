<?php /** @noinspection DuplicatedCode */

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

/** @noinspection PhpUndefinedClassInspection */

namespace Bitter\EntityDesigner\Generator;

use Bitter\EntityDesigner\Entity\Entity;
use Bitter\EntityDesigner\Entity\GeneratedFile;
use Bitter\EntityDesigner\Entity\GeneratedTable;
use Bitter\EntityDesigner\Generator\ContentImportFormat\ContentImporterFileGenerator;
use Bitter\EntityDesigner\Generator\Controller\Dialog\AdvancedSearchGenerator;
use Bitter\EntityDesigner\Generator\Controller\Dialog\Preset\DeleteGenerator;
use Bitter\EntityDesigner\Generator\Controller\Dialog\Preset\EditGenerator;
use Bitter\EntityDesigner\Generator\Controller\MenuControllerGenerator;
use Bitter\EntityDesigner\Generator\Controller\Search\SearchControllerGenerator;
use Bitter\EntityDesigner\Generator\Controller\HeaderControllerGenerator;
use Bitter\EntityDesigner\Generator\Element\MenuElementGenerator;
use Bitter\EntityDesigner\Generator\Routing\DialogRoutesGenerator;
use Bitter\EntityDesigner\Generator\Routing\SearchRoutesGenerator;
use Bitter\EntityDesigner\Generator\SearchProvider\ItemListGenerator;
use Bitter\EntityDesigner\Generator\Element\HeaderElementGenerator;
use Bitter\EntityDesigner\Generator\Entity\EntityGenerator;
use Bitter\EntityDesigner\Generator\Entity\SavedSearchEntityGenerator;
use Bitter\EntityDesigner\Generator\Package\PackageControllerGenerator;
use Bitter\EntityDesigner\Generator\Routing\RouteListGenerator;
use Bitter\EntityDesigner\Generator\SearchProvider\MenuGenerator;
use Bitter\EntityDesigner\Generator\SearchProvider\Search\ColumnSet\AvailableGenerator;
use Bitter\EntityDesigner\Generator\SearchProvider\Search\ColumnSet\Column\ColumnGenerator;
use Bitter\EntityDesigner\Generator\SearchProvider\Search\ColumnSet\ColumnSetGenerator;
use Bitter\EntityDesigner\Generator\SearchProvider\Search\ColumnSet\DefaultSetGenerator;
use Bitter\EntityDesigner\Generator\SearchProvider\Search\Field\Field\FieldGenerator;
use Bitter\EntityDesigner\Generator\SearchProvider\Search\Field\ManagerGenerator;
use Bitter\EntityDesigner\Generator\SearchProvider\Search\Field\ManagerServiceProviderGenerator;
use Bitter\EntityDesigner\Generator\SearchProvider\Search\ItemList\Pager\ItemListPagerManagerGenerator;
use Bitter\EntityDesigner\Generator\SearchProvider\Search\Result\ItemColumnGenerator;
use Bitter\EntityDesigner\Generator\SearchProvider\Search\Result\ItemGenerator;
use Bitter\EntityDesigner\Generator\SearchProvider\Search\Result\ResultGenerator;
use Bitter\EntityDesigner\Generator\SearchProvider\Search\SearchProviderGenerator;
use Bitter\EntityDesigner\Generator\ServiceProvider\EntityDesignerServiceProviderGenerator;
use Bitter\EntityDesigner\Generator\ServiceProvider\EntityServiceProviderGenerator;
use Bitter\EntityDesigner\Generator\SinglePage\ControllerGenerator;
use Bitter\EntityDesigner\Generator\SinglePage\DetailViewGenerator;
use Bitter\EntityDesigner\Generator\SinglePage\ListViewGenerator;
use Bitter\EntityDesigner\NodeVisitor\AddFullInstallMethodNodeVisitor;
use Bitter\EntityDesigner\NodeVisitor\AddFullPackageOnStartNodeVisitor;
use Bitter\EntityDesigner\NodeVisitor\AddInstallerNodeVisitor;
use Bitter\EntityDesigner\NodeVisitor\AddPackageOnStartNodeVisitor;
use Bitter\EntityDesigner\NodeVisitor\RemoveInstallerNodeVisitor;
use Bitter\EntityDesigner\NodeVisitor\RemovePackageOnStartNodeVisitor;
use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Bitter\EntityDesigner\Generator\BlockType\FormBlock\AddViewGenerator as FormBlockTypeAddViewGenerator;
use Bitter\EntityDesigner\Generator\BlockType\FormBlock\DatabaseSchemaGenerator as FormBlockTypeDatabaseSchemaGenerator;
use Bitter\EntityDesigner\Generator\BlockType\FormBlock\EditViewGenerator as FormBlockTypeEditViewGenerator;
use Bitter\EntityDesigner\Generator\BlockType\FormBlock\IconGenerator as FormBlockTypeIconGenerator;
use Bitter\EntityDesigner\Generator\BlockType\FormBlock\ViewGenerator as FormBlockTypeViewGenerator;
use Bitter\EntityDesigner\Generator\BlockType\FormBlock\ControllerGenerator as FormBlockTypeControllerGenerator;
use Bitter\EntityDesigner\Generator\BlockType\DetailBlock\AddViewGenerator as DetailBlockTypeAddViewGenerator;
use Bitter\EntityDesigner\Generator\BlockType\DetailBlock\DatabaseSchemaGenerator as DetailBlockTypeDatabaseSchemaGenerator;
use Bitter\EntityDesigner\Generator\BlockType\DetailBlock\EditViewGenerator as DetailBlockTypeEditViewGenerator;
use Bitter\EntityDesigner\Generator\BlockType\DetailBlock\IconGenerator as DetailBlockTypeIconGenerator;
use Bitter\EntityDesigner\Generator\BlockType\DetailBlock\ViewGenerator as DetailBlockTypeViewGenerator;
use Bitter\EntityDesigner\Generator\BlockType\DetailBlock\ControllerGenerator as DetailBlockTypeControllerGenerator;
use Bitter\EntityDesigner\Generator\BlockType\ListBlock\AddViewGenerator as ListBlockTypeAddViewGenerator;
use Bitter\EntityDesigner\Generator\BlockType\ListBlock\DatabaseSchemaGenerator as ListBlockTypeDatabaseSchemaGenerator;
use Bitter\EntityDesigner\Generator\BlockType\ListBlock\EditViewGenerator as ListBlockTypeEditViewGenerator;
use Bitter\EntityDesigner\Generator\BlockType\ListBlock\IconGenerator as ListBlockTypeIconGenerator;
use Bitter\EntityDesigner\Generator\BlockType\ListBlock\ViewGenerator as ListBlockTypeViewGenerator;
use Bitter\EntityDesigner\Generator\BlockType\ListBlock\ControllerGenerator as ListBlockTypeControllerGenerator;
use Concrete\Core\Block\BlockType\BlockType;
use Concrete\Core\Block\BlockType\Set;
use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Database\Connection\Connection;
use Concrete\Core\Entity\Block\BlockType\BlockType as BlockTypeEntity;
use Concrete\Core\Entity\Package as PackageEntity;
use Concrete\Core\Package\Package;
use Concrete\Core\Package\PackageService;
use Concrete\Core\Page\Page;
use Concrete\Core\Page\Single;
use Concrete\Core\Permission\Access\Access;
use Concrete\Core\Permission\Access\Entity\GroupEntity;
use Concrete\Core\Permission\Key\Key;
use Concrete\Core\User\Group\Group;
use Concrete\Core\Utility\Service\Text;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\Adapter\Local;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\Filesystem;
use Exception;
use PhpParser\NodeFinder;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;
use PhpParser\Node;
use Symfony\Component\HttpFoundation\Session\Session;

class GeneratorService implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /** @var GeneratorInterface[] */
    protected $generatorClasses = [
        EntityGenerator::class,
        ControllerGenerator::class,
        DetailViewGenerator::class,
        ListViewGenerator::class,
        HeaderControllerGenerator::class,
        MenuControllerGenerator::class,
        HeaderElementGenerator::class,
        MenuElementGenerator::class,
        FormBlockTypeAddViewGenerator::class,
        FormBlockTypeDatabaseSchemaGenerator::class,
        FormBlockTypeEditViewGenerator::class,
        FormBlockTypeIconGenerator::class,
        FormBlockTypeViewGenerator::class,
        FormBlockTypeControllerGenerator::class,
        DetailBlockTypeAddViewGenerator::class,
        DetailBlockTypeDatabaseSchemaGenerator::class,
        DetailBlockTypeEditViewGenerator::class,
        DetailBlockTypeIconGenerator::class,
        DetailBlockTypeViewGenerator::class,
        DetailBlockTypeControllerGenerator::class,
        ListBlockTypeAddViewGenerator::class,
        ListBlockTypeDatabaseSchemaGenerator::class,
        ListBlockTypeEditViewGenerator::class,
        ListBlockTypeIconGenerator::class,
        ListBlockTypeViewGenerator::class,
        ListBlockTypeControllerGenerator::class,
        SavedSearchEntityGenerator::class,
        ItemListGenerator::class,
        ItemListPagerManagerGenerator::class,
        AvailableGenerator::class,
        ColumnSetGenerator::class,
        DefaultSetGenerator::class,
        ColumnGenerator::class,
        MenuGenerator::class,
        \Bitter\EntityDesigner\Generator\SearchProvider\Search\Result\ColumnGenerator::class,
        ItemColumnGenerator::class,
        ItemGenerator::class,
        ResultGenerator::class,
        SearchProviderGenerator::class,
        ManagerGenerator::class,
        FieldGenerator::class,
        ManagerServiceProviderGenerator::class,
        AdvancedSearchGenerator::class,
        EditGenerator::class,
        DeleteGenerator::class,
        RouteListGenerator::class,
        DialogRoutesGenerator::class,
        SearchRoutesGenerator::class,
        EntityServiceProviderGenerator::class,
        SearchControllerGenerator::class,
        EntityDesignerServiceProviderGenerator::class
    ];

    protected $entityManager;
    protected $connection;
    protected $fileSystem;
    /** @var Entity */
    protected $entity;
    protected $parser;
    protected $prettyPrinter;
    protected $textService;
    protected $config;

    const START_INDENT = 0;
    const INDENT_SIZE = 4;

    public function __construct(
        EntityManagerInterface $entityManager,
        Repository $config,
        Text $textService,
        Connection $connection
    )
    {
        $this->entityManager = $entityManager;
        $this->connection = $connection;
        $this->fileSystem = new Filesystem(new Local("/"));
        $this->parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
        $this->prettyPrinter = new Standard();
        $this->textService = $textService;
        $this->config = $config;
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
     * @return GeneratorService
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }

    public function updatePackageInstaller($packageHandle)
    {
        $this->removePackageInstaller($packageHandle);
        $this->addPackageInstaller($packageHandle);
    }

    private function addPackageInstaller($packageHandle)
    {
        $entity = $this->entityManager->getRepository(Entity::class)->findOneBy(["packageHandle" => $packageHandle, "isTemp" => false]);

        if ($entity instanceof Entity) {
            $generatorClasses = $this->generateFiles(ContentImporterFileGenerator::class, $entity);

            if (count($generatorClasses) === 1) {
                /** @var GeneratorItem $generatorClass */
                $generatorClass = array_shift($generatorClasses);
                $contentImporterFile = basename($generatorClass->getFileName());
                $this->addInstallerToPackageController($contentImporterFile, $packageHandle);
            }
        }
    }

    private function removePackageInstaller($packageHandle)
    {
        $this->removeInstallerFromPackageController($packageHandle);
        $this->deleteAllContentImporterFiles($packageHandle);
    }

    private function deleteAllContentImporterFiles($packageHandle)
    {
        $packagesEntity = $this->entityManager->getRepository(PackageEntity::class)->findOneBy(["pkgHandle" => $packageHandle]);

        if ($packagesEntity instanceof PackageEntity) {
            /** @var Package $packageController */
            $packageController = $packagesEntity->getController();
            $path = $packageController->getPackagePath();

            foreach ($this->fileSystem->listContents($path) as $file) {
                if (strlen($file["filename"]) === 20 &&
                    substr($file["filename"], 0, 8) === "install_" &&
                    isset($file["extension"]) && $file["extension"] === "xml") {

                    /** @noinspection PhpUnhandledExceptionInspection */
                    $this->fileSystem->delete($file["path"]);
                }
            }
        }
    }

    private function getSrcNamespace($entity = null)
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

    /** @noinspection DuplicatedCode */
    private function addInstallerToPackageController($contentImporterFile, $packageHandle)
    {
        /** @var Entity $entity */
        $entity = $this->entityManager->getRepository(Entity::class)->findOneBy(["packageHandle" => $packageHandle, "isTemp" => false]);

        if ($entity instanceof Entity && $entity->hasValidPackageHandle()) {
            $packagesEntity = $this->getPackage($entity);

            if ($packagesEntity instanceof PackageEntity) {
                /** @var Package $packageController */
                $packageController = $packagesEntity->getController();
                $controllerFile = $packageController->getPackagePath() . "/controller.php";
                /** @noinspection PhpUnhandledExceptionInspection */
                $controllerFileCode = $this->fileSystem->read($controllerFile);
                $stmts = $this->parser->parse($controllerFileCode);
                $traverser = new NodeTraverser();

                $controllerHasInstallMethod = false;
                $controllerHasOnStartMethod = false;

                $serviceProviderClassName = "\\" . $this->getSrcNamespace($entity) . "\\EntityDesignerServiceProvider";

                $nodeFinder = new NodeFinder();

                foreach ($nodeFinder->findInstanceOf($stmts, Node\Stmt\ClassMethod::class) as $classMethod) {
                    /** @var Node\Stmt\ClassMethod $classMethod */
                    if ($classMethod->name == "install") {
                        $controllerHasInstallMethod = true;
                    }

                    /** @var Node\Stmt\ClassMethod $classMethod */
                    if ($classMethod->name == "on_start") {
                        $controllerHasOnStartMethod = true;
                    }
                }

                if ($controllerHasInstallMethod) {
                    $traverser->addVisitor(new AddInstallerNodeVisitor($contentImporterFile));
                } else {
                    $traverser->addVisitor(new AddFullInstallMethodNodeVisitor($contentImporterFile));
                }

                if ($controllerHasOnStartMethod) {
                    $traverser->addVisitor(new AddPackageOnStartNodeVisitor($serviceProviderClassName));
                } else {
                    $traverser->addVisitor(new AddFullPackageOnStartNodeVisitor($serviceProviderClassName));
                }

                $stmts = $traverser->traverse($stmts);
                $controllerFileCode = $this->prettyPrinter->prettyPrintFile($stmts);
                /** @noinspection PhpUnhandledExceptionInspection */
                $this->fileSystem->update($controllerFile, $controllerFileCode);
            }
        }
    }

    /** @noinspection DuplicatedCode */
    private function removeInstallerFromPackageController($packageHandle)
    {
        /** @var Entity $entity */
        $entity = $this->entityManager->getRepository(Entity::class)->findOneBy(["packageHandle" => $packageHandle, "isTemp" => false]);

        if ($entity instanceof Entity && $entity->hasValidPackageHandle()) {
            $packagesEntity = $this->getPackage($entity);

            if ($packagesEntity instanceof PackageEntity) {
                /** @var Package $packageController */
                $packageController = $packagesEntity->getController();
                $controllerFile = $packageController->getPackagePath() . "/controller.php";
                /** @noinspection PhpUnhandledExceptionInspection */
                $controllerFileCode = $this->fileSystem->read($controllerFile);
                $stmts = $this->parser->parse($controllerFileCode);
                $traverser = new NodeTraverser();
                $traverser->addVisitor(new RemoveInstallerNodeVisitor());
                $traverser->addVisitor(new RemovePackageOnStartNodeVisitor());
                $stmts = $traverser->traverse($stmts);
                $controllerFileCode = $this->prettyPrinter->prettyPrintFile($stmts);
                /** @noinspection PhpUnhandledExceptionInspection */
                $this->fileSystem->update($controllerFile, $controllerFileCode);
            }
        }
    }

    private function generateFiles($generatorClass, $entity = null)
    {
        if ($entity === null) {
            $entity = $this->entity;
        }

        /** @var GeneratorInterface $generator */
        $generator = $this->app->make($generatorClass);
        $generator->setEntity($entity);

        $generatorItems = $generator->build(self::START_INDENT, self::INDENT_SIZE);

        foreach ($generatorItems as $generatorItem) {
            if (strlen($generatorItem->getFileContents()) > 0 &&
                strlen($generatorItem->getFileName()) > 0) {

                $dirName = dirname($generatorItem->getFileName());

                if (!file_exists($dirName)) {
                    $this->fileSystem->createDir($dirName);
                }

                if ($this->fileSystem->has($generatorItem->getFileName())) {
                    /** @noinspection PhpUnhandledExceptionInspection */
                    $this->fileSystem->update($generatorItem->getFileName(), $generatorItem->getFileContents());
                } else {
                    /** @noinspection PhpUnhandledExceptionInspection */
                    $this->fileSystem->write($generatorItem->getFileName(), $generatorItem->getFileContents());
                }

                /*
                 * Log generated file
                 */

                if (!is_null($entity->getId())) {
                    $generatedFileEntity = new GeneratedFile();
                    $generatedFileEntity->setEntity($entity);
                    $generatedFileEntity->setFile($generatorItem->getFileName());
                    $this->entityManager->persist($generatedFileEntity);
                }
            }

            if (strlen($generatorItem->getTable()) > 0) {

                /*
                 * Log generated table
                 */

                if (!is_null($entity->getId())) {
                    $generatedTableEntry = new GeneratedTable();
                    $generatedTableEntry->setEntity($entity);
                    $generatedTableEntry->setTable($generatorItem->getTable());
                    $this->entityManager->persist($generatedTableEntry);
                }
            }
        }

        return $generatorItems;
    }

    private function generateAllFiles($entity = null)
    {
        if ($entity === null) {
            $entity = $this->entity;
        }

        foreach ($this->generatorClasses as $generatorClass) {
            $this->generateFiles($generatorClass, $entity);
        }
    }

    private function getPackage($entity = null)
    {
        if ($entity === null) {
            $entity = $this->entity;
        }

        return $this->entityManager->getRepository(PackageEntity::class)->findOneBy(["pkgHandle" => $entity->getPackageHandle()]);
    }

    private function installSinglePage($entity = null)
    {
        if ($entity === null) {
            $entity = $this->entity;
        }

        if (!($pkg = $this->getPackage($entity)) instanceof PackageEntity) {
            $pkg = null;
        }

        if (!($page = $entity->getPage()) instanceof Page) {
            $page = Single::add($entity->getPath(), $pkg);
        }

        $page->update([
            "cName" => $entity->getName()
        ]);
    }

    private function installTaskPermissions($entity = null)
    {
        if ($entity === null) {
            $entity = $this->entity;
        }

        $taskPermissions = [];

        foreach (["create", "read", "update", "delete"] as $action) {
            $taskPermissions[] = [
                "handle" => $action . "_" . $entity->getHandle() . "_entries",
                "name" => ucfirst($action) . " " . $entity->getName() . " Entries"
            ];
        }

        $group = Group::getByID(ADMIN_GROUP_ID);

        $adminGroupEntity = GroupEntity::getOrCreate($group);

        foreach ($taskPermissions as $taskPermission) {
            $pkg = null;

            if ($entity->hasValidPackageHandle()) {
                /** @var PackageService $packageService */
                $packageService = $this->app->make(PackageService::class);
                $pkg = $packageService->getByHandle($entity->getPackageHandle());
            }

            $pk = Key::getByHandle($taskPermission["handle"]);

            if ($pk === null) {
                /** @var Key $pk */
                $pk = Key::add('admin', $taskPermission["handle"], $taskPermission["name"], "", false, false, $pkg);

                $pa = Access::create($pk);
                $pa->addListItem($adminGroupEntity);
                $pt = $pk->getPermissionAssignmentObject();
                $pt->assignPermissionAccess($pa);
            }
        }
    }

    private function installBlockTypeSet($entity = null)
    {
        if ($entity === null) {
            $entity = $this->entity;
        }

        if (!($pkg = $this->getPackage($entity)) instanceof PackageEntity) {
            $pkg = false;
        }

        if (!Set::getByHandle($entity->getHandle()) instanceof Set) {
            Set::add($entity->getHandle(), $entity->getName(), $pkg);
        }
    }

    private function installBlockTypes($entity = null)
    {
        if ($entity === null) {
            $entity = $this->entity;
        }

        if (!($pkg = $this->getPackage($entity)) instanceof PackageEntity) {
            $pkg = false;
        }

        $blockTypeSet = Set::getByHandle($entity->getHandle());

        $blockTypeSet->clearBlockTypes();

        foreach (["detail", "list", "form"] as $blockTypeHandle) {
            $blockTypeHandle = $entity->getHandle() . "_" . $blockTypeHandle;

            $blockType = BlockType::getByHandle($blockTypeHandle);

            if ($blockType instanceof BlockTypeEntity) {
                $blockType->delete();
            }

            $blockType = BlockType::installBlockType($blockTypeHandle, $pkg);

            if ($blockTypeSet instanceof Set) {
                $blockTypeSet->addBlockType($blockType);
            }
        }
    }

    private function upgradePackage($packageHandle = null)
    {
        $pkg = $this->entityManager->getRepository(PackageEntity::class)->findOneBy(["pkgHandle" => $packageHandle]);

        if ($pkg instanceof PackageEntity) {
            /** @var Package $package */
            $package = $pkg->getController();

            try {
                $package->upgradeDatabase();
            } catch (Exception $err) {
                // Skip errors
            }
        }
    }

    public function uninstallBlockTypes($entity = null)
    {
        if ($entity === null) {
            $entity = $this->entity;
        }

        foreach (["detail", "list", "form"] as $blockTypeHandle) {
            $blockTypeHandle = $entity->getHandle() . "_" . $blockTypeHandle;

            if (($blockType = BlockType::getByHandle($blockTypeHandle)) instanceof BlockTypeEntity) {
                $blockType->delete();
            }
        }
    }

    public function uninstallBlockTypeSet($entity = null)
    {
        if ($entity === null) {
            $entity = $this->entity;
        }

        $blockTypeSet = Set::getByHandle($entity->getHandle());

        if ($blockTypeSet instanceof Set) {
            $blockTypeSet->delete();
        }
    }

    public function uninstallSinglePage($entity = null)
    {
        if ($entity === null) {
            $entity = $this->entity;
        }

        if (($page = $entity->getPage()) instanceof Page) {
            $page->delete();
        }
    }

    /**
     * @param Entity|null $entity
     * @return GeneratedFile[]
     */
    private function getGeneratedFiles($entity = null)
    {
        if ($entity === null) {
            $entity = $this->entity;
        }

        $generatedFileEntities = [];

        $rows = $this->connection->fetchAll("SELECT id FROM GeneratedFile WHERE entityId = ?", [$entity->getId()]);

        foreach ($rows as $row) {
            $entityId = $row["id"];

            $fileEntity = $this->entityManager->getRepository(GeneratedFile::class)->findOneBy(["id" => $entityId]);

            if ($fileEntity instanceof GeneratedFile) {
                $generatedFileEntities[] = $fileEntity;
            }
        }
        return $generatedFileEntities;
    }

    private function deleteAllGeneratedFiles($entity = null)
    {
        if ($entity === null) {
            $entity = $this->entity;
        }

        if (!is_null($this->getGeneratedFiles($entity))) {
            foreach ($this->getGeneratedFiles($entity) as $generatedFileEntity) {
                $fileName = $generatedFileEntity->getFile();

                if ($fileName !== null) {
                    try {
                        $this->fileSystem->delete($fileName);
                        $this->cleanUpDirectory(dirname($fileName));
                    } catch (FileNotFoundException $e) {
                        // Skip error
                    }
                }

                $this->entityManager->remove($generatedFileEntity);
            }
        }
    }

    private function deleteAssociations($entity = null)
    {
        if ($entity === null) {
            $entity = $this->entity;
        }

        /** @noinspection SqlDialectInspection */
        /** @noinspection SqlNoDataSourceInspection */
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->connection->executeQuery("DELETE FROM Field WHERE associatedEntityId = ?", [$entity->getId()]);
    }


    public function clearSearchSession($entity = null)
    {
        if ($entity === null) {
            $entity = $this->entity;
        }

        /** @var Session $session */
        $session = $this->app->make(Session::class);
        $session->remove('search/' . $entity->getHandle() . '/query');
    }

    private function deleteAllTablesInDatabase($entity = null)
    {
        if ($entity === null) {
            $entity = $this->entity;
        }

        /** @noinspection SqlDialectInspection */
        /** @noinspection SqlNoDataSourceInspection */
        $tables = $this->connection->fetchAll("SELECT t.table FROM GeneratedTable AS t LEFT JOIN Entity AS e ON (e.id = t.entityId) WHERE e.id = ?", [$entity->getId()]);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->connection->executeQuery("SET FOREIGN_KEY_CHECKS=0;");

        foreach ($tables as $table) {
            try {
                /** @noinspection SqlNoDataSourceInspection */
                $this->connection->executeQuery("DROP TABLE " . $table["table"]);
            } catch (Exception $err) {
                // Skip on error
            }
        }

        /** @noinspection SqlDialectInspection */
        /** @noinspection SqlNoDataSourceInspection */
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->connection->executeQuery("DELETE t.* FROM GeneratedTable AS t LEFT JOIN Entity AS e ON (e.id = t.entityId) WHERE e.id = ?", [$entity->getId()]);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->connection->executeQuery("SET FOREIGN_KEY_CHECKS=1;");
    }

    /**
     * Removes all empty sub folders.
     *
     * @param string $path
     * @return mixed
     */
    private function cleanUpDirectory($path)
    {
        $files = $this->fileSystem->listContents($path);

        if (count($files) === 0) {
            $this->fileSystem->deleteDir($path);

            return $this->cleanUpDirectory(dirname($path)); // now clean up the parent directory if required
        }

        return true;
    }

    public function createPackage(
        $packageName,
        $packageHandle,
        $packageDescription
    )
    {

        /** @var PackageControllerGenerator $packageGenerator */
        $packageGenerator = $this->app->make(PackageControllerGenerator::class);

        /** @var GeneratorItem[] $generatorItem */
        $generatorItems = $packageGenerator
            ->setPackageName($packageName)
            ->setPackageHandle($packageHandle)
            ->setPackageDescription($packageDescription)
            ->build(
                self::START_INDENT,
                self::INDENT_SIZE
            );

        $generatorItem = array_shift($generatorItems);

        if (strlen($generatorItem->getFileContents()) > 0 &&
            strlen($generatorItem->getFileName()) > 0) {

            /** @noinspection PhpUnhandledExceptionInspection */
            $this->fileSystem->write($generatorItem->getFileName(), $generatorItem->getFileContents());

            // load the package controller
            /** @noinspection PhpIncludeInspection */
            require($generatorItem->getFileName());

            // and now install the package
            /** @var PackageService $packageService */
            $packageService = $this->app->make(PackageService::class);
            $package = $packageService->getClass($packageHandle);

            $packageService->install($package, []);
        }
    }

    /**
     * @return Entity[]
     */
    private function getEntities()
    {
        return $this->entityManager->getRepository(Entity::class)->findBy(["isTemp" => false]);
    }

    private function getPackageHandles()
    {
        $packageHandles = [];

        foreach ($this->getEntities() as $entity) {
            if (!in_array($entity->getPackageHandle(), $packageHandles)) {
                $packageHandles[] = $entity->getPackageHandle();
            }
        }

        return $packageHandles;
    }

    public function install()
    {
        /*
         * clean up
         */
        foreach ($this->getEntities() as $entity) {
            $this->deleteAllGeneratedFiles($entity);
        }

        foreach ($this->getEntities() as $entity) {
            $this->deleteAllTablesInDatabase($entity);
        }

        foreach ($this->getEntities() as $entity) {
            $this->clearSearchSession($entity);
        }

        /*
         * generate new
         */
        foreach ($this->getEntities() as $entity) {
            $this->generateAllFiles($entity);
        }

        foreach ($this->getEntities() as $entity) {
            $this->uninstallSinglePage($entity);
            $this->installSinglePage($entity);
        }

        foreach ($this->getEntities() as $entity) {
            $this->installBlockTypeSet($entity);
        }

        foreach ($this->getEntities() as $entity) {
            $this->installBlockTypes($entity);
        }

        foreach ($this->getEntities() as $entity) {
            $this->installTaskPermissions($entity);
        }

        /*
         * it's important to execute the flush statement at the correct position because once it's
         * executed the generated files and tables tables are cleared.
         *
         * Core methods like uninstalling block types are flushing the entity manager too.
         */

        try {
            $this->entityManager->flush();
        } catch (Exception $err) {
            // Do Nothing
        }

        foreach ($this->getPackageHandles() as $packageHandle) {
            $this->updatePackageInstaller($packageHandle);
            $this->upgradePackage($packageHandle);
        }
    }

    public function uninstall()
    {
        $this->deleteAllGeneratedFiles();
        $this->deleteAllTablesInDatabase();
        $this->deleteAssociations();

        $this->entityManager->flush();

        $this->uninstallBlockTypes();
        $this->uninstallBlockTypeSet();
        $this->uninstallSinglePage();

        if ($this->entity->hasValidPackageHandle()) {
            $this->updatePackageInstaller($this->entity->getPackageHandle());
        }
    }
}
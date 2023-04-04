<?php /** @noinspection DuplicatedCode */

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Concrete\Package\EntityDesigner\Controller\SinglePage\Dashboard;

use Bitter\EntityDesigner\Controller\HeaderController;
use Bitter\EntityDesigner\Entity\Field;
use Bitter\EntityDesigner\Entity\Entity;
use Bitter\EntityDesigner\Enumeration\FieldType;
use Bitter\EntityDesigner\Validator\HandleValidator;
use Concrete\Core\Application\Service\Dashboard\Sitemap;
use Concrete\Core\Application\UserInterface\Dashboard\Navigation\NavigationCache;
use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Database\DatabaseStructureManager;
use Concrete\Core\Entity\Package;
use Concrete\Core\Package\Event\PackageEntities;
use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Http\ResponseFactory;
use Concrete\Core\Http\Request;
use Concrete\Core\Page\Page;
use Concrete\Core\Permission\Key\Key;
use Concrete\Core\Support\Facade\Url;
use Concrete\Core\Utility\Service\Text;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\HttpFoundation\Response;
use DateTime;

class EntityDesigner extends DashboardPageController
{
    /** @var ResponseFactory */
    protected $responseFactory;
    /** @var Request */
    protected $request;
    /** @var Text */
    protected $textService;
    /** @var HandleValidator */
    protected $handleValidator;
    /** @var Repository */
    protected $config;
    /** @var Sitemap */
    protected $sitemap;

    public function on_start()
    {
        parent::on_start();

        $this->responseFactory = $this->app->make(ResponseFactory::class);
        $this->request = $this->app->make(Request::class);
        $this->textService = $this->app->make(Text::class);
        $this->handleValidator = $this->app->make(HandleValidator::class);
        $this->config = $this->app->make(Repository::class);
        $this->sitemap = $this->app->make(Sitemap::class);

        $this->sitemap->setIncludeSystemPages(true);

        if (!Key::getByHandle("access_entity_designer")->validate()) {
            $this->responseFactory->forbidden(null)->send();
            $this->app->shutdown();
        }
    }

    private function updateDoctrineEntities()
    {
        /*
         * Update the entities
         */

        $pev = new PackageEntities();
        $this->app->make('director')->dispatch('on_refresh_package_entities', $pev);
        $entityManagers = array_merge([$this->entityManager], $pev->getEntityManagers());

        foreach ($entityManagers as $em) {
            $manager = new DatabaseStructureManager($em);
            $manager->refreshEntities();
        }

        $this->app->make(NavigationCache::class)->clear();
    }

    private function getPackages()
    {
        $packages = ["" => t("** None")];

        /** @var Package[] $packagesEntities */
        $packagesEntities = $this->entityManager->getRepository(Package::class)->findAll();

        foreach ($packagesEntities as $packagesEntity) {
            $packageHandle = $packagesEntity->getPackageHandle();
            $packageName = $packagesEntity->getPackageName();

            $packages[$packageHandle] = $packageName;
        }

        return $packages;
    }

    /**
     * @param Entity $entity
     */
    private function setDefaults($entity)
    {
        $header = new HeaderController();

        $header->setLabel(t("Add Field"));

        $packages = $this->getPackages();

        $this->set('headerMenu', $header);
        $this->set("entity", $entity);
        $this->set("packages", $packages);

        $this->requireAsset("javascript", "jquery");
        $this->requireAsset("javascript", "underscore.js");
        $this->requireAsset("javascript", "custom_jquery/ui");
        $this->requireAsset("tag_it");

        $this->requireAsset("fancytree");
        $this->requireAsset("core/sitemap"); // the js is not required but the css of this asset group

        $this->render("/dashboard/entity_designer/edit");

    }

    /**
     * This custom get method is required because to core page selector widget
     * has no support for displaying system pages.
     *
     * @noinspection PhpUnused
     */
    public function get_sitemap()
    {
        $rootNode = [];

        /** @noinspection PhpUndefinedMethodInspection */
        $rootCID = (int)Page::getByPath("/dashboard")->getCollectionID();

        if ($this->request->query->has("selected")) {
            $selectedCID = (int)$this->request->query->get("selected");

            /** @noinspection PhpUndefinedMethodInspection */
            if (Page::getByID($selectedCID)->isError()) {
                $selectedCID = $rootCID;
            }
        } else {
            $selectedCID = $rootCID;
        }

        if ($this->request->query->has("cParentID")) {
            $cID = $this->request->query->get("cParentID");
            foreach ($this->sitemap->getSubNodes($cID) as $node) {
                $node->selected = (int)$node->cID === $selectedCID;
                $rootNode[] = $node;
            }
        } else {
            $rootNode = $this->sitemap->getNode($rootCID);
            $rootNode->lazy = false;
            $rootNode->selected = (int)$rootNode->cID === $selectedCID;

            foreach ($this->sitemap->getSubNodes($rootCID) as $node) {
                $node->selected = (int)$node->cID === $selectedCID;
                $rootNode->children[] = $node;
            }
            $rootNode = [$rootNode];
        }

        $this->responseFactory->json($rootNode)->send();
        $this->app->shutdown();
    }

    /**
     * @noinspection PhpUnused
     * @param string $id
     * @return void|Response
     */
    public function get_fields($id = null)
    {
        $entity = $this->entityManager->getRepository(Entity::class)->findOneBy([
            "id" => $id
        ]);

        if ($entity instanceof Entity) {
            return $this->responseFactory->json([
                "fields" => $entity->getFields()
            ]);
        } else {
            $this->responseFactory->notFound(null)->send();
            $this->app->shutdown();
        }
    }

    /** @noinspection PhpUnused */
    public function get_packages()
    {
        return $this->responseFactory->json([
            "packages" => $this->getPackages()
        ]);
    }

    /**
     * @noinspection PhpUnused
     * @return \Concrete\Core\Http\Response|Response
     * @noinspection PhpInconsistentReturnPointsInspection
     */
    public function remove_field()
    {
        $id = $this->request->query->get("id");

        $field = $this->entityManager->getRepository(Field::class)->findOneBy([
            "id" => $id
        ]);

        if ($field instanceof Field) {
            $this->entityManager->remove($field);
            $this->entityManager->flush();

            return $this->responseFactory->create(null, Response::HTTP_OK);
        } else {
            $this->responseFactory->notFound(null)->send();
            $this->app->shutdown();
        }
    }

    /** @noinspection PhpUnused */
    public function get_handle()
    {
        $name = strtolower(trim($this->request->query->get("name")));

        $handle = "";

        $prevChar = null;

        for ($i = 0; $i < strlen($name); $i++) {
            $curChar = ord(substr($name, $i, 1));

            // replace spaces with _
            if ($curChar === 32) {
                $curChar = 95;
            }

            // allowed: a-z, _ when last char was not _
            if ($curChar >= 97 && $curChar <= 122 || ($curChar === 95 && $prevChar !== $curChar)) {
                $handle .= chr($curChar);
            }

            $prevChar = $curChar;
        }

        // cut of last char when _ is at end
        if (ord(substr($handle, strlen($handle) - 1)) === 95) {
            $handle = substr($handle, 0, strlen($handle) - 1);
        }

        return $this->responseFactory->json([
            "handle" => $handle
        ]);
    }

    /**
     * @param string $id
     * @return \Concrete\Core\Http\Response|Response
     * @noinspection PhpInconsistentReturnPointsInspection
     */
    public function remove($id = null)
    {
        $entity = $this->entityManager->getRepository(Entity::class)->findOneBy([
            "id" => $id
        ]);

        if ($entity instanceof Entity) {
            $this->entityManager->remove($entity);
            $this->entityManager->flush();

            return $this->responseFactory->redirect(Url::to("/dashboard/entity_designer/removed"), Response::HTTP_TEMPORARY_REDIRECT);
        } else {
            $this->responseFactory->notFound(null)->send();
            $this->app->shutdown();
        }
    }

    /**
     * @noinspection PhpUnused
     * @param string $id
     * @return \Concrete\Core\Http\Response|Response
     * @noinspection PhpInconsistentReturnPointsInspection
     */
    public function edit($id = null)
    {
        $entity = $this->entityManager->getRepository(Entity::class)->findOneBy([
            "id" => $id
        ]);

        if ($entity instanceof Entity) {
            if ($this->token->validate("save_entity")) {
                $data = $this->request->request->all();

                if (!isset($data["name"]) || strlen($data["name"]) === 0) {
                    $this->error->add(t("You need to enter a name."));
                }

                if (!isset($data["handle"]) || strlen($data["handle"]) === 0) {
                    $this->error->add(t("You need to enter a handle."));
                }

                if (!isset($data["displayValue"]) || strlen($data["displayValue"]) === 0) {
                    $this->error->add(t("You need to enter a display value."));
                }

                /** @noinspection PhpUndefinedMethodInspection */
                if (!isset($data["parentPage"]) || empty($data["parentPage"]) || Page::getByID($data["parentPage"])->isError()) {
                    $this->error->add(t("You need to select a parent page."));
                }

                if (($foundEntity = $this->entityManager->getRepository(Entity::class)->findOneBy(["handle" => $data["handle"], "isTemp" => false])) instanceof Entity) {
                    /** @var Entity $foundEntity */
                    if ($foundEntity->getId() !== $entity->getId()) {
                        $this->error->add(t("The given handle is already in use."));
                    }
                }

                $this->handleValidator->isValid($data["handle"], $this->error);

                if (empty($entity->getFields())) {
                    $this->error->add(t("You need to create at least one field."));
                } else {
                    $hasDisplayInListViewField = false;

                    foreach ($entity->getFields() as $field) {
                        if ($field->isDisplayInListView()) {
                            $hasDisplayInListViewField = true;
                        }
                    }

                    if (!$hasDisplayInListViewField) {
                        $this->error->add(t("At least one field needs to be displayed in the list view."));
                    }

                    $entitiesAreCompatible = true;

                    foreach($entity->getFields() as $field) {
                        if ($field->getType() === FieldType::FIELD_TYPE_ASSOCIATION) {
                            $associatedEntity = $field->getAssociatedEntity();

                            if ($associatedEntity instanceof Entity) {
                                if ($associatedEntity->getPackageHandle() != $data["packageHandle"]) {
                                    $entitiesAreCompatible = false;
                                }
                            } else if ($data["packageHandle"] != "") {
                                $entitiesAreCompatible = false;
                            }
                        }
                    }

                    if (!$entitiesAreCompatible) {
                        $this->error->add(t("All associated entities needs to be in the same directory."));
                    }

                    $relatedEntitiesAreCompatible = true;

                    foreach($entity->getAssociatedFields() as $field) {
                        $associatedEntity = $field->getAssociatedEntity();

                        if ($associatedEntity instanceof Entity) {
                            if ($associatedEntity->getPackageHandle() != $data["packageHandle"]) {
                                $relatedEntitiesAreCompatible = false;
                            }
                        } else if ($data["packageHandle"] != "") {
                            $relatedEntitiesAreCompatible = false;
                        }
                    }

                    if (!$relatedEntitiesAreCompatible) {
                        $this->error->add(t("This entity must be in the same directory like all entities that are associated with this entity."));
                    }
                }

                if (!$this->error->has()) {
                    $entity->setName($data["name"]);
                    $entity->setDisplayValue($data["displayValue"]);
                    $entity->setHandle($data["handle"]);
                    $entity->setDetailViewHelpText($data["detailViewHelpText"]);
                    $entity->setListViewHelpText($data["listViewHelpText"]);
                    $entity->setParentPage(Page::getByID($data["parentPage"])->getCollectionPath());
                    $entity->setPackageHandle($data["packageHandle"]);
                    $entity->setIsTemp(false);
                    $entity->setUpdatedAt(new DateTime());

                    $this->entityManager->persist($entity);
                    $this->entityManager->flush();

                    return $this->responseFactory->redirect(Url::to("/dashboard/entity_designer/saved"), Response::HTTP_TEMPORARY_REDIRECT);
                }
            }

            $this->setDefaults($entity);
        } else {
            $this->responseFactory->notFound(null)->send();
            $this->app->shutdown();
        }
    }

    public function add()
    {
        $entity = new Entity();

        $entity->setIsTemp(true);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $this->responseFactory->redirect(Url::to("/dashboard/entity_designer/edit", $entity->getId()), Response::HTTP_TEMPORARY_REDIRECT);
    }

    public function removed()
    {
        $this->updateDoctrineEntities();

        $this->set("success", t("The entity has been successfully removed."));

        $this->view();
    }

    public function saved()
    {
        $this->updateDoctrineEntities();

        $this->set("success", t("The entity has been successfully updated."));

        $this->view();
    }

    public function view()
    {
        $header = new HeaderController();

        $header->setLabel(t("Add Entity"));
        $header->setUrl((string)Url::to("/dashboard/entity_designer/add"));

        $entities = $this->entityManager->getRepository(Entity::class)->findBy(["isTemp" => false]);

        $this->set('headerMenu', $header);
        $this->set("entities", $entities);
    }
}
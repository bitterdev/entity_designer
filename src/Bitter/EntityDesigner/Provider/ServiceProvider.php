<?php

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

/** @noinspection PhpDeprecationInspection */

namespace Bitter\EntityDesigner\Provider;

use Bitter\EntityDesigner\EventListener\EntityGeneratorSubscriber;
use Bitter\EntityDesigner\RouteList;
use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Asset\AssetList;
use Concrete\Core\Http\ResponseFactory;
use Concrete\Core\Package\Package;
use Concrete\Core\Package\PackageService;
use Concrete\Core\Routing\Router;
use Concrete\Core\Http\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\ClassLoader\Psr4ClassLoader;

class ServiceProvider implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /** @var Router */
    protected $router;

    /** @var ResponseFactory */
    protected $responseFactory;

    /** @var Package */
    protected $pkg;

    /** @var EntityManagerInterface */
    protected $eventManager;

    public function __construct(
        PackageService $packageService,
        ResponseFactory $responseFactory,
        Router $router,
        EntityManagerInterface $eventManager
    )
    {
        $this->router = $router;
        $this->pkg = $packageService->getByHandle("entity_designer");
        $this->responseFactory = $responseFactory;
        $this->eventManager = $eventManager;
    }

    public function register()
    {
        $classLoader = new Psr4ClassLoader();
        $classLoader->addPrefix('Application', DIR_APPLICATION . '/' . DIRNAME_CLASSES );
        $classLoader->register();

        if (class_exists(\Application\EntityDesignerServiceProvider::class)) {
            $entityDesignerServiceProvider = $this->app->make(\Application\EntityDesignerServiceProvider::class);
            $entityDesignerServiceProvider->register();
        }

        $al = AssetList::getInstance();

        $al->register("javascript", "tag_it", "bower_components/tag-it/js/tag-it.js", ["version" => "2.0"], $this->pkg->getPackageHandle());
        $al->register("css", "tag_it", "bower_components/tag-it/css/jquery.tagit.css", ["version" => "2.0"], $this->pkg->getPackageHandle());

        $al->registerGroup(
            "tag_it",
            [
                ["javascript", "tag_it"],
                ["css", "tag_it"]
            ]
        );

        /*
         * jQuery UI 1.12.1
         *
         * The included jQuery UI version from the core hasn't the autocomplete widget.
         * Therefore we use a custom build.
         */
        $al->register('javascript', 'custom_jquery/ui', "bower_components/jquery-ui/jquery-ui.min.js", ["version" => "1.12.1"], $this->pkg->getPackageHandle());

        $this->eventManager->getEventManager()->addEventSubscriber(
            $this->app->make(EntityGeneratorSubscriber::class)
        );

        $this->router->register('/bitter/entity_designer/dialogs/edit_field/edit/{id}', '\Concrete\Package\EntityDesigner\Controller\Dialog\EntityDesigner\EditField::edit');

        $this->router->register('/bitter/entity_designer/dialogs/edit_field/add/{entityId}', '\Concrete\Package\EntityDesigner\Controller\Dialog\EntityDesigner\EditField::add');

        $this->router->register('/bitter/entity_designer/dialogs/edit_field/submit', '\Concrete\Package\EntityDesigner\Controller\Dialog\EntityDesigner\EditField::submit');

        $this->router->register('/bitter/entity_designer/dialogs/add_package/add', '\Concrete\Package\EntityDesigner\Controller\Dialog\EntityDesigner\AddPackage::add');

        $this->router->register('/bitter/entity_designer/dialogs/add_package/submit', '\Concrete\Package\EntityDesigner\Controller\Dialog\EntityDesigner\AddPackage::submit');


        $this->router->register("/bitter/entity_designer/reminder/hide", function () {
            $this->pkg->getConfig()->save('reminder.hide', true);
            $this->responseFactory->create("", Response::HTTP_OK)->send();
            $this->app->shutdown();
        });

        $this->router->register("/bitter/entity_designer/did_you_know/hide", function () {
            $this->pkg->getConfig()->save('did_you_know.hide', true);
            $this->responseFactory->create("", Response::HTTP_OK)->send();
            $this->app->shutdown();
        });

        $this->router->register("/bitter/entity_designer/license_check/hide", function () {
            $this->pkg->getConfig()->save('license_check.hide', true);
            $this->responseFactory->create("", Response::HTTP_OK)->send();
            $this->app->shutdown();
        });

        $list = new RouteList();
        $list->loadRoutes($this->router);
    }

}

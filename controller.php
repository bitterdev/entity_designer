<?php

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Concrete\Package\EntityDesigner;

use Bitter\EntityDesigner\Console\Command\GenerateEntities;
use Bitter\EntityDesigner\Provider\ServiceProvider;
use Concrete\Core\Error\ErrorList\ErrorList;
use Concrete\Core\Package\Package;
use Concrete\Core\Page\Single;
use Concrete\Core\Permission\Access\Access;
use Concrete\Core\Permission\Access\Entity\GroupEntity;
use Concrete\Core\Permission\Key\Key;
use Concrete\Core\User\Group\Group;

class Controller extends Package
{
    protected $pkgHandle = 'entity_designer';
    protected $pkgVersion = '2.5.1';
    protected $appVersionRequired = '9.0.0';
    protected $pkgAutoloaderRegistries = [
        'src/Bitter/EntityDesigner' => 'Bitter\EntityDesigner',
    ];

    public function getPackageDescription()
    {
        return t('Create Doctrine-Entities easily in your concrete5 Dashboard. The code for entity, controller, detail view and list view is generated automatically.');
    }

    public function getPackageName()
    {
        return t('Entity Designer');
    }

    public function on_start()
    {
        require_once("vendor/autoload.php");

        if ($this->app->isRunThroughCommandLineInterface()) {
            $console = $this->app->make('console');
            $console->add(new GenerateEntities());
        }

        /** @var ServiceProvider $serviceProvider */
        $serviceProvider = $this->app->make(ServiceProvider::class);
        $serviceProvider->register();
    }

    /**
     * @param bool $testForAlreadyInstalled
     * @return ErrorList|true|void
     */
    public function testForInstall($testForAlreadyInstalled = false)
    {
        /** @var ErrorList $errors */
        $errors = $this->app->make(ErrorList::class);

        $directories = [
            "application/controllers",
            "application/single_pages",
            "application/src",
            "application/elements",
            "application/blocks",
            "packages"
        ];

        foreach ($directories as $directory) {
            if (!is_writable(DIR_BASE . DIRECTORY_SEPARATOR . $directory)) {
                $errors->add(t('The directory "%s" needs to be writable.', $directory));
            }
        }

        return $errors->has() ? $errors : true;
    }

    public function upgrade()
    {
        require_once("vendor/autoload.php");
        parent::upgrade();
    }

    public function install()
    {
        require_once("vendor/autoload.php");

        /*
         * Install single pages
         */

        $pkg = parent::install();

        Single::add("/dashboard/entity_designer", $pkg);

        /*
         * Install task permissions
         */

        $taskPermissions = [
            [
                "handle" => "access_entity_designer",
                "name" => t("Access Entity Designer")
            ],

            [
                "handle" => "edit_fields",
                "name" => t("Insert Rows")
            ],

            [
                "handle" => "add_packages",
                "name" => t("Add Packages")
            ]
        ];

        $group = Group::getByID(ADMIN_GROUP_ID);

        $adminGroupEntity = GroupEntity::getOrCreate($group);

        foreach ($taskPermissions as $taskPermission) {
            /** @var Key $pk */
            $pk = Key::add('admin', $taskPermission["handle"], $taskPermission["name"], "", false, false, $pkg);

            $pa = Access::create($pk);
            $pa->addListItem($adminGroupEntity);
            $pt = $pk->getPermissionAssignmentObject();
            $pt->assignPermissionAccess($pa);
        }
    }
}

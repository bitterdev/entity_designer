<?php /** @noinspection DuplicatedCode */

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\EntityDesigner\Entity;

use Bitter\EntityDesigner\Enumeration\FieldType;
use Concrete\Core\Entity\Package;
use Concrete\Core\Package\PackageService;
use Concrete\Core\Page\Page;
use Concrete\Core\Page\Single;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Database\Connection\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use JsonSerializable;
use DateTime;

/**
 * @ORM\Entity
 */
class Entity implements JsonSerializable
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true) *
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true) *
     */
    protected $handle;

    /**
     * @ORM\OneToMany(targetEntity="Bitter\EntityDesigner\Entity\Field", mappedBy="entity", cascade={"persist", "remove", "merge"}, orphanRemoval=true)
     *
     * @var Field[]
     */
    protected $fields;

    /**
     * @ORM\OneToMany(targetEntity="Bitter\EntityDesigner\Entity\GeneratedFile", mappedBy="entity", cascade={"persist", "remove", "merge"}, orphanRemoval=true)
     *
     * @var GeneratedFile[]
     */
    protected $generatedFiles;

    /**
     * @ORM\OneToMany(targetEntity="Bitter\EntityDesigner\Entity\GeneratedTable", mappedBy="entity", cascade={"persist", "remove", "merge"}, orphanRemoval=true)
     *
     * @var GeneratedTable[]
     */
    protected $generatedTables;

    /**
     * @var bool
     * @ORM\Column(type="boolean") *
     */
    protected $isTemp;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true) *
     */
    protected $listViewHelpText;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true) *
     */
    protected $detailViewHelpText;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true) *
     */
    protected $packageHandle ='';

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true) *
     */
    protected $displayValue = 'Entry {id}';

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $parentPage;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var DateTime
     */
    protected $updatedAt;

    /**
     * @return string
     */
    public function getPackageHandle()
    {
        return $this->packageHandle;
    }

    /**
     * @return bool
     */
    public function hasValidPackageHandle()
    {
        if (strlen($this->packageHandle) > 0) {
            $app = Application::getFacadeApplication();
            /** @var PackageService $packageService */
            $packageService = $app->make(PackageService::class);
            $pkg = $packageService->getByHandle($this->packageHandle);
            return $pkg instanceof Package;
        } else {
            return false;
        }
    }

    /**
     * @return string
     */
    public function getDisplayValue()
    {
        return $this->displayValue;
    }

    /**
     * @param string $displayValue
     * @return Entity
     */
    public function setDisplayValue($displayValue)
    {
        $this->displayValue = $displayValue;
        return $this;
    }

    /**
     * @param string $packageHandle
     * @return Entity
     */
    public function setPackageHandle($packageHandle)
    {
        $this->packageHandle = $packageHandle;
        return $this;
    }

    /**
     * @return int
     */
    public function getParentPageId()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        return Page::getByPath($this->getParentPage())->getCollectionID();
    }

    /**
     * @return string
     */
    public function getParentPage()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        if (Page::getByPath($this->parentPage)->isError()) {
            /** @noinspection PhpUndefinedMethodInspection */
            return Page::getByPath("/dashboard")->getCollectionPath();
        } else {
            return $this->parentPage;
        }
    }

    /**
     * @param string $parentPage
     * @return Entity
     */
    public function setParentPage($parentPage)
    {
        $this->parentPage = $parentPage;
        return $this;
    }

    /**
     * @noinspection PhpUnused
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     * @return Entity
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Entity
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getListViewHelpText()
    {
        return $this->listViewHelpText;
    }

    /**
     * @param string $listViewHelpText
     * @return Entity
     */
    public function setListViewHelpText($listViewHelpText)
    {
        $this->listViewHelpText = $listViewHelpText;
        return $this;
    }

    /**
     * @return string
     */
    public function getDetailViewHelpText()
    {
        return $this->detailViewHelpText;
    }

    /**
     * @param string $detailViewHelpText
     * @return Entity
     */
    public function setDetailViewHelpText($detailViewHelpText)
    {
        $this->detailViewHelpText = $detailViewHelpText;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Entity
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * @param string $handle
     * @return Entity
     */
    public function setHandle($handle)
    {
        $this->handle = $handle;
        return $this;
    }

    /**
     * @param bool $prependIdField
     * @return Field[]
     */
    public function getFields($prependIdField = false)
    {
        $app = Application::getFacadeApplication();
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $app->make(EntityManagerInterface::class);
        /** @var PersistentCollection $fields */
        $fields = $entityManager->getRepository(Field::class)->findBy(["isTemp" => false, "entity" => $this]);

        if (!is_array($fields)) {
            $fields = [];
        }

        if ($prependIdField) {
            $idField = new Field();
            $idField->setEntity($this);
            $idField->setLabel("id");
            $idField->setHandle("id");
            $idField->setIsPrimaryKey(true);
            $idField->setType(FieldType::FIELD_TYPE_NUMBER);

            array_unshift($fields, $idField);
        }

        return $fields;
    }

    /**
     * @noinspection PhpUnused
     * @param Field[] $fields
     * @return Entity
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * @return bool
     */
    public function isTemp()
    {
        return $this->isTemp;
    }

    /**
     * @param bool $isTemp
     * @return Entity
     */
    public function setIsTemp($isTemp)
    {
        $this->isTemp = $isTemp;
        return $this;
    }

    public function getPath()
    {
        return Page::getByPath($this->getParentPage())->getCollectionPath() . "/" . $this->getHandle();
    }

    /**
     * @return Page|null
     */
    public function getPage()
    {
        foreach (Single::getList() as $page) {
            /** @var Page $page */
            if ($page->getCollectionPath() === $this->getPath()) {
                return $page;
            }
        }

        return null;
    }

    /**
     * @return GeneratedTable[]
     */
    public function getGeneratedTables()
    {
        return $this->generatedTables;
    }

    /**
     * @param GeneratedTable[] $generatedTables
     * @return Entity
     */
    public function setGeneratedTables($generatedTables)
    {
        $this->generatedTables = $generatedTables;
        return $this;
    }

    /**
     * @return GeneratedFile[]
     */
    public function getGeneratedFiles()
    {
        return $this->generatedFiles;
    }

    /**
     * @param GeneratedFile[] $generatedFiles
     * @return Entity
     */
    public function setGeneratedFiles($generatedFiles)
    {
        $this->generatedFiles = $generatedFiles;
        return $this;
    }

    /**
     * @return Field[]
     */
    public function getAssociatedFields()
    {
        $fields = [];

        $app = Application::getFacadeApplication();
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $app->make(EntityManagerInterface::class);
        /** @var Connection $connection */
        $connection = $app->make(Connection::class);

        $rows = $connection->fetchAll("SELECT id FROM Field WHERE associatedEntityId = ?", [
            $this->getId()
        ]);

        foreach ($rows as $row) {
            $fieldEntityId = $row["id"];

            $fieldEntity = $entityManager->getRepository(Field::class)->findOneBy([
                "id" => $fieldEntityId
            ]);

            $fields[] = $fieldEntity;
        }

        return $fields;
    }

    /**
     * @return Entity[]
     */
    public function getAssociatedEntities()
    {
        $associatedEntities = [];

        foreach ($this->getFields() as $fieldEntity) {
            if ($fieldEntity->getType() === FieldType::FIELD_TYPE_ASSOCIATION) {
                $associatedEntity = $fieldEntity->getAssociatedEntity();

                if (!in_array($associatedEntity, $associatedEntities)) {
                    $associatedEntities[] = $associatedEntity;
                }
            }
        }

        return $associatedEntities;
    }

    public function jsonSerialize()
    {
        return [
            "id" => $this->getId(),
            "handle" => $this->getHandle(),
            "name" => $this->getName(),
            "fields" => $this->getFields(),
            "isTemp" => $this->isTemp()
        ];
    }
}

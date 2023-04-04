<?php /** @noinspection DuplicatedCode */

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Concrete\Package\EntityDesigner\Controller\Dialog\EntityDesigner;

use Bitter\EntityDesigner\Entity\Field;
use Bitter\EntityDesigner\Entity\FieldOption;
use Bitter\EntityDesigner\Entity\Entity;
use Bitter\EntityDesigner\Enumeration\FieldType;
use Bitter\EntityDesigner\Enumeration\FieldValidation;
use Bitter\EntityDesigner\Enumeration\FileType;
use Bitter\EntityDesigner\Enumeration\PageSelectorStyle;
use Bitter\EntityDesigner\Enumeration\AssociationType;
use Bitter\EntityDesigner\Validator\HandleValidator;
use Concrete\Controller\Backend\UserInterface;
use Concrete\Core\Http\Request;
use Concrete\Core\Http\ResponseFactory;
use Concrete\Core\File\EditResponse;
use Concrete\Core\Permission\Key\Key;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Validation\CSRF\Token;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

class EditField extends UserInterface
{
    protected $viewPath = '/dialogs/entity_designer/edit_field';
    /** @var Request */
    protected $request;
    /** @var ResponseFactory */
    protected $responseFactory;
    /** @var EntityManagerInterface */
    protected $entityManager;
    /** @var HandleValidator */
    protected $handleValidator;
    /** @var Token */
    protected $token;
    protected $fieldTypes;
    protected $fieldValidations;
    protected $fileTypes;
    protected $pageSelectorStyles;
    protected $associationTypes;
    protected $associatedEntities;

    /** @noinspection SqlDialectInspection */
    public function __construct()
    {
        parent::__construct();

        if (is_null($this->app)) {
            $this->app = Application::getFacadeApplication();
        }

        $this->request = $this->app->make(Request::class);
        $this->responseFactory = $this->app->make(ResponseFactory::class);
        $this->entityManager = $this->app->make(EntityManagerInterface::class);
        $this->handleValidator = $this->app->make(HandleValidator::class);
        $this->token = $this->app->make(Token::class);
    }

    /**
     * @param Entity $entity
     * @param Field $currentField
     */
    private function setDefaults($entity, $currentField) {
        $displayFileField = true;

        foreach($entity->getFields() as $field) {
            if ($field->getType() === FieldType::FIELD_TYPE_FILE) {
                if ($field->getId() !== $currentField->getId()) {
                    $displayFileField = false;
                }
            }
        }

        $this->associatedEntities = [];

        /** @var Entity[] $associatedEntityEntries */
        $associatedEntityEntries = $this->entityManager->getRepository(Entity::class)->findBy(["isTemp" => false]);

        foreach ($associatedEntityEntries as $associatedEntityEntry) {
            if ($associatedEntityEntry->getId() != $entity->getId()) {
                $this->associatedEntities[$associatedEntityEntry->getId()] = $associatedEntityEntry->getName();
            }
        }

        $this->fieldTypes = [
            FieldType::FIELD_TYPE_PAGE_SELECTOR => t("Page"),
            FieldType::FIELD_TYPE_TEXT => t("Text"),
            FieldType::FIELD_TYPE_EMAIL => t("Email"),
            FieldType::FIELD_TYPE_TELEPHONE => t("Telephone"),
            FieldType::FIELD_TYPE_PASSWORD => t("Password"),
            FieldType::FIELD_TYPE_WEB_ADDRESS => t("Web address"),
            FieldType::FIELD_TYPE_TEXT_AREA => t("Textarea"),
            FieldType::FIELD_TYPE_NUMBER => t("Number"),
            FieldType::FIELD_TYPE_RADIO_BUTTONS => t("Radio buttons"),
            FieldType::FIELD_TYPE_CHECKBOX_LIST => t("Checkbox list"),
            FieldType::FIELD_TYPE_SELECT_BOX => t("Select box")
        ];

        if ($displayFileField) {
            $this->fieldTypes[FieldType::FIELD_TYPE_FILE] = t("File");
        }

        if (count($this->associatedEntities) > 0) {
            $this->fieldTypes[FieldType::FIELD_TYPE_ASSOCIATION] = t("Association");
        }

        $this->fileTypes = [
            FileType::FILE_TYPE_ALL => t("All"),
            FileType::FILE_TYPE_VIDEO => t("Video"),
            FileType::FILE_TYPE_TEXT => t("Text"),
            FileType::FILE_TYPE_AUDIO => t("Audio"),
            FileType::FILE_TYPE_DOC => t("Documents"),
            FileType::FILE_TYPE_APP => t("App"),
        ];

        $this->associationTypes = [
            AssociationType::ASSOCIATION_TYPE_ONE_TO_ONE => t("One to one (1:1)"),
            AssociationType::ASSOCIATION_TYPE_ONE_TO_MANY => t("One to many (1:n)"),
            AssociationType::ASSOCIATION_TYPE_MANY_TO_MANY => t("Many to many (n:m)")
        ];

        /** @noinspection SqlNoDataSourceInspection */
        $this->pageSelectorStyles = [
            PageSelectorStyle::PAGE_SELECTOR_STYLE_SELECT_FROM_SITEMAP => t("Select from Sitemap"),
            PageSelectorStyle::PAGE_SELECTOR_STYLE_SELECT_PAGE => t("Select Page"),
            PageSelectorStyle::PAGE_SELECTOR_STYLE_QUICK_SELECT => t("Quick Select")
        ];

        $this->fieldValidations = [
            FieldValidation::FIELD_VALIDATION_NONE => t("** None"),
            FieldValidation::FIELD_VALIDATION_REQUIRED => t("Required")
        ];
    }

    public function add($entityId = null)
    {
        $entity = $this->entityManager->getRepository(Entity::class)->findOneBy([
            "id" => $entityId
        ]);

        if ($entity instanceof Entity) {
            $field = new Field();

            $field->setEntity($entity);

            $this->entityManager->persist($field);
            $this->entityManager->flush();

            $this->edit($field->getId());
        } else {
            $this->responseFactory->notFound(null)->send();
            $this->app->shutdown();
        }
    }

    public function edit($id = null)
    {
        $field = $this->entityManager->getRepository(Field::class)->findOneBy([
            "id" => $id
        ]);

        if ($field instanceof Field) {

            $this->setDefaults($field->getEntity(), $field);

            $this->set("field", $field);
            $this->set("fieldTypes", $this->fieldTypes);
            $this->set("fieldValidations", $this->fieldValidations);
            $this->set("fileTypes", $this->fileTypes);
            $this->set("pageSelectorStyles", $this->pageSelectorStyles);
            $this->set("associationTypes", $this->associationTypes);
            $this->set("associatedEntities", $this->associatedEntities);

            $this->requireAsset("javascript", "underscore.js");
            $this->requireAsset("javascript", "jquery");

        } else {
            $this->responseFactory->notFound(null)->send();
            $this->app->shutdown();
        }
    }

    public function submit()
    {
        $id = (int)$this->request->request->get("id", 0);

        $field = $this->entityManager->getRepository(Field::class)->findOneBy([
            "id" => $id
        ]);

        if ($field instanceof Field && $this->token->validate("save_field")) {

            $this->setDefaults($field->getEntity(), $field);

            $response = new EditResponse();

            $data = $this->request->request->all();

            if (!isset($data["label"]) || strlen($data["label"]) === 0) {
                $this->error->add(t("You need to enter a label."));
            }

            if (!isset($data["handle"]) || strlen($data["handle"]) === 0) {
                $this->error->add(t("You need to enter a handle."));
            }

            if (($foundField = $this->entityManager->getRepository(Field::class)->findOneBy(["handle" => $data["handle"], "entity" => $field->getEntity()])) instanceof Field) {
                /** @var Field $foundField */
                if ($foundField->getId() !== $field->getId()) {
                    $this->error->add(t("The given handle is already in use."));
                }
            }

            $this->handleValidator->isValid($data["handle"], $this->error);

            if (!isset($data["type"]) || !in_array($data["type"], array_keys($this->fieldTypes))) {
                $this->error->add(t("You need to select a valid type."));
            }

            if (!isset($data["validation"]) || !in_array($data["validation"], array_keys($this->fieldValidations))) {
                $this->error->add(t("You need to select a valid validation method."));
            }

            if (!isset($data["allowedFileType"]) || !in_array($data["allowedFileType"], array_keys($this->fileTypes))) {
                $this->error->add(t("You need to select a valid file type."));
            }

            if (!isset($data["pageSelectorStyle"]) || !in_array($data["pageSelectorStyle"], array_keys($this->pageSelectorStyles))) {
                $this->error->add(t("You need to select a page selector style."));
            }

            $associatedEntity = null;

            if ($data["type"] === FieldType::FIELD_TYPE_ASSOCIATION) {
                if (isset($data["associatedEntity"]) && !empty($data["associatedEntity"])) {
                    /** @var Entity $associatedEntity */
                    $associatedEntity = $this->entityManager->getRepository(Entity::class)->findOneBy(["id" => $data["associatedEntity"]]);
                }

                if (!$associatedEntity instanceof Entity) {
                    $this->error->add(t("You need to select a associated entity."));
                }

                if (!isset($data["associationType"]) || !in_array($data["associationType"], array_keys($this->associationTypes))) {
                    $this->error->add(t("You need to select a valid association type."));
                }

                /** @var Field[] $otherAssociatedFields */
                $otherAssociatedFields = $this->entityManager->getRepository(Field::class)->findBy([
                    "type" => FieldType::FIELD_TYPE_ASSOCIATION,
                    "entity" => $field->getEntity(),
                    "associatedEntity" => $associatedEntity
                ]);

                if (count($otherAssociatedFields) > 1) {
                    $this->error->add(t("This entity already contains an association with the selected target entity."));
                }
            }

            if ($data["type"] === FieldType::FIELD_TYPE_CHECKBOX_LIST ||
                $data["type"] === FieldType::FIELD_TYPE_SELECT_BOX ||
                $data["type"] === FieldType::FIELD_TYPE_RADIO_BUTTONS) {

                if (!is_array($data["options"]) || count($data["options"]) === 0) {
                    $this->error->add(t("You need to add one option at least."));
                } else {
                    foreach($data["options"] as $option) {
                        if (!isset($option["value"]) || strlen($option["value"]) === 0) {
                            $this->error->add(t("You need to enter a option value."));
                        }

                        if (!isset($option["label"]) || strlen($option["label"]) === 0) {
                            $this->error->add(t("You need to enter a option label."));
                        }
                    }
                }
            }

            if (!$this->error->has()) {
                $field->setIsTemp(false);
                $field->setDisabled(isset($data["isDisabled"]));
                $field->setDisplayInListView(isset($data["isDisplayInListView"]));
                $field->setHandle($data["handle"]);
                $field->setPrefix($data["prefix"]);
                $field->setLabel($data["label"]);
                $field->setSuffix($data["suffix"]);
                $field->setHelpText($data["helpText"]);
                $field->setPlaceholder($data["placeholder"]);
                $field->setMaxLength((int)$data["maxLength"]);
                $field->setValue($data["value"]);
                $field->setMin((int)$data["min"]);
                $field->setMax((int)$data["max"]);
                $field->setStep((int)$data["step"]);
                $field->setValidation($data["validation"]);
                $field->setType($data["type"]);
                $field->setAllowedFileType($data["allowedFileType"]);
                $field->setPageSelectorStyle($data["pageSelectorStyle"]);
                $field->setAssociatedEntity($associatedEntity);
                $field->setAssociationType($data["associationType"]);
                $field->setUpdatedAt(new DateTime());

                $this->entityManager->persist($field);

                if (!empty($field->getOptions())) {
                    foreach ($field->getOptions() as $fieldOption) {
                        $this->entityManager->remove($fieldOption);
                    }
                }

                if (is_array($data["options"])) {
                    foreach ($data["options"] as $option) {
                        $fieldOption = new FieldOption();

                        $fieldOption->setField($field);
                        $fieldOption->setLabel($option["label"]);
                        $fieldOption->setValue($option["value"]);
                        $fieldOption->setUpdatedAt(new DateTime());

                        $this->entityManager->persist($fieldOption);
                    }
                }

                $this->entityManager->flush();

                $response->setMessage(t('Field updated successfully.'));
            } else {
                $response->setError($this->error);
            }

            $this->responseFactory->json($response)->send();
            $this->app->shutdown();
        } else {
            $this->responseFactory->notFound(null)->send();
            $this->app->shutdown();
        }
    }

    public function canAccess()
    {
        return Key::getByHandle("edit_fields")->validate();
    }

}

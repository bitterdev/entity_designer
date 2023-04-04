<?php

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\EntityDesigner\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class GeneratedTable
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
     * @ORM\Column(name="`table`", type="string", nullable=true) *
     */
    protected $table = '';

    /**
     * @var Entity
     * @ORM\ManyToOne(targetEntity="Bitter\EntityDesigner\Entity\Entity")
     * @ORM\JoinColumn(name="entityId", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $entity;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return GeneratedTable
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
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
     * @return GeneratedTable
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @param string $table
     * @return GeneratedTable
     */
    public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }

}
<?php

namespace App\Repository;

trait RepositoryModifyTrait
{
    public function save(object $object): void
    {
        assert($this->_entityName === get_class($object));
        $this->_em->persist($object);
    }

    public function remove(object $object): void
    {
        assert($this->_entityName === get_class($object));
        $this->_em->remove($object);
    }

    public function saveAndCommit(object $object): void
    {
        $this->save($object);
        $this->commit();
    }

    public function removeAndCommit(object $object): void
    {
        $this->remove($object);
        $this->commit();
    }

    public function commit(): void
    {
        $this->_em->flush();
    }
}

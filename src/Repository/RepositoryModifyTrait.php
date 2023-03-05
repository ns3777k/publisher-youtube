<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Repository;

trait RepositoryModifyTrait
{
    public function save(object $object): void
    {
        assert($this->_entityName === $object::class);
        $this->_em->persist($object);
    }

    public function remove(object $object): void
    {
        assert($this->_entityName === $object::class);
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

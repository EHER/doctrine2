<?php

namespace Doctrine\Tests\ORM\Mapping;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\Tests\OrmTestCase;

class ClassMetadataLoadEventTest extends OrmTestCase
{
    /**
     * @group DDC-1610
     */
    public function testEvent()
    {
        $em = $this->_getTestEntityManager();
        $metadataFactory = $em->getMetadataFactory();
        $evm = $em->getEventManager();
        $evm->addEventListener(Events::loadClassMetadata, $this);
        $classMetadata = $metadataFactory->getMetadataFor(LoadEventTestEntity::class);
        self::assertTrue($classMetadata->hasField('about'));
        self::assertArrayHasKey('about', $classMetadata->reflFields);
        self::assertInstanceOf('ReflectionProperty', $classMetadata->reflFields['about']);
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $classMetadata = $eventArgs->getClassMetadata();
        $field = [
            'fieldName' => 'about',
            'type' => 'string',
            'length' => 255
        ];
        $classMetadata->mapField($field);
    }
}

/**
 * @Entity
 * @Table(name="load_event_test_entity")
 */
class LoadEventTestEntity
{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @Column(type="string", length=255)
     */
    private $name;

    private $about;
}

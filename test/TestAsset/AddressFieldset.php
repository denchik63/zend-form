<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\Form\TestAsset;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Hydrator\ClassMethods as ClassMethodsHydrator;

class AddressFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct()
    {
        parent::__construct('address');
        $this->setHydrator(new ClassMethodsHydrator(false))
             ->setObject(new Entity\Address());

        $street = new \Zend\Form\Element('street', array('label' => 'Street'));
        $street->setAttribute('type', 'text');

        $city = new CityFieldset;
        $city->setLabel('City');

        $this->add($street);
        $this->add($city);

        $phones = new \Zend\Form\Element\Collection('phones');
        $phones->setLabel('Phone numbers')
               ->setOptions(array(
                    'count'          => 2,
                    'allow_add'      => true,
                    'allow_remove'   => true,
                    'target_element' => new PhoneFieldset(),
               ));
        $this->add($phones);
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array(
            'street' => array(
                'required' => true,
            )
        );
    }
}

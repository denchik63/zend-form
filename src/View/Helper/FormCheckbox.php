<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Form
 */

namespace Zend\Form\View\Helper;

use Traversable;
use Zend\Form\ElementInterface;
use Zend\Form\Element\Checkbox;
use Zend\Form\Exception;

/**
 * @category   Zend
 * @package    Zend_Form
 * @subpackage View
 */
class FormCheckbox extends FormInput
{
    /**
     * Render a form <input> element from the provided $element
     *
     * @param  ElementInterface $element
     * @throws \Zend\Form\Exception\DomainException
     * @return string
     */
    public function render(ElementInterface $element)
    {
        $name = $element->getName();
        if (empty($name) && $name !== 0) {
            throw new Exception\DomainException(sprintf(
                '%s requires that the element has an assigned name; none discovered',
                __METHOD__
            ));
        }

        $attributes            = $element->getAttributes();
        $attributes['name']    = $name;
        $attributes['type']    = $this->getInputType();
        $closingBracket        = $this->getInlineClosingBracket();

        if ($element instanceof Checkbox) {
            if ($element->isChecked()) {
                $attributes['checked'] = 'checked';
            }
            $attributes['value'] = $element->getCheckedValue();
            $useHiddenElement    = $element->useHiddenElement();
            $unCheckedValue      = $element->getUncheckedValue();
        }
        if (!$element instanceof Checkbox) {
            $value = (bool) $element->getValue();
            if ($value) {
                $attributes['checked'] = 'checked';
            }
            $attributes['value'] = $element->getOption('checked_value');
            if (null === $attributes['value']) {
                $attributes['value'] = '1';
            }
            $useHiddenElement    = $element->getOption('use_hidden_element');
            if (null === $useHiddenElement) {
                $useHiddenElement = true;
            }
            $unCheckedValue = $element->getOption('unchecked_value');
            if (null === $unCheckedValue) {
                $unCheckedValue = '0';
            }
        }

        $rendered = sprintf(
            '<input %s%s',
            $this->createAttributesString($attributes),
            $closingBracket
        );

        if ($useHiddenElement) {
            $hiddenAttributes = array(
                'name'  => $attributes['name'],
                'value' => $unCheckedValue,
            );

            $rendered = sprintf(
                '<input type="hidden" %s%s',
                $this->createAttributesString($hiddenAttributes),
                $closingBracket
            ) . $rendered;
        }

        return $rendered;
    }

    /**
     * Return input type
     *
     * @return string
     */
    protected function getInputType()
    {
        return 'checkbox';
    }
}

<?php

namespace Onyx\Service;
use Zend\Soap\Wsdl\ComplexTypeStrategy\AbstractComplexTypeStrategy;
use Zend\Soap\Exception;
/**
 * Description of SoapDoctrineComplexTypeStrategy
 *
 * @author cesperanc
 */
class SoapDoctrineDefaultComplexType extends AbstractComplexTypeStrategy{
    
    public function addComplexType($type)
    {
        if (!class_exists($type)&& !interface_exists($type)) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Cannot add a complex type %s that is not an object or where '
                . 'class could not be found in "DefaultComplexType" strategy.'.$type,
                $type
            ));
        }

        if (($soapType = $this->scanRegisteredTypes($type)) !== null) {
            return $soapType;
        }

        $dom = $this->getContext()->toDomDocument();
        $class = new \ReflectionClass($type);

        $soapTypeName = $this->getContext()->translateType($type);
        $soapType     = 'tns:' . $soapTypeName;

        // Register type here to avoid recursion
        $this->getContext()->addType($type, $soapType);


        $defaultProperties = $class->getDefaultProperties();

        $complexType = $dom->createElement('xsd:complexType');
        $complexType->setAttribute('name', $soapTypeName);

        $all = $dom->createElement('xsd:all');

        foreach ($class->getProperties() as $property) {
            if ($property->isPublic() && preg_match_all('/@var\s+([^\s]+)/m', $property->getDocComment(), $matches)) {

                /**
                 * @todo check if 'xsd:element' must be used here (it may not be compatible with using 'complexType'
                 * node for describing other classes used as attribute types for current class
                 */
                $element = $dom->createElement('xsd:element');
                $element->setAttribute('name', $propertyName = $property->getName());
                $element->setAttribute('type', $this->getContext()->getType(trim($matches[1][0])));

                // If the default value is null, then this property is nillable.
                if ($defaultProperties[$propertyName] === null) {
                    $element->setAttribute('nillable', 'true');
                }

                $all->appendChild($element);
            }
        }

        $complexType->appendChild($all);
        $this->getContext()->getSchema()->appendChild($complexType);

        return $soapType;
    }
}

?>

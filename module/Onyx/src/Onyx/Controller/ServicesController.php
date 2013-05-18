<?php

/**
 * Onyx
 *
 * @link      <http://dei.estg.ipleiria.pt>
 * @copyright Copyright (c) 2013 Cláudio Esperança <cesperanc@gmail.com>, Diogo Serra <2120915@my.ipleiria.pt>
 * @license   This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; If not, see <http://www.gnu.org/licenses/>.
 */

namespace Onyx\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Soap\AutoDiscover;
use Zend\Soap\Wsdl\ComplexTypeStrategy\ArrayOfTypeComplex;

class ServicesController extends AbstractActionController {    

    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;
    
    /**
     * @return Doctrine\ORM\EntityManager with the doctrine entity manager
     */
    public function getEntityManager() {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->em;
    }
    
    /**
     * On soap requests, serve the service
     */
    public function soapAction() {
        $uri = $this->getRequest()->getUri();
        $soapURL = sprintf('%s://%s%s', $uri->getScheme(), $uri->getHost(), $uri->getPath());
        $wsdlURL = "{$soapURL}/wsdl";
        
        $soap = new \Zend\Soap\Server($wsdlURL);
        $soap->setClass('Onyx\Service\Soap', '', $this->getEntityManager());
        $soap->handle();
        
        exit();
    }
    
    /**
     * On WSDL request, serve the WSDL file
     */
    public function wsdlAction(){
        header("Content-Type: text/xml");
        $uri = $this->getRequest()->getUri();
        $soapURL = sprintf('%s://%s%s', $uri->getScheme(), $uri->getHost(), dirname($uri->getPath()));
            
        $autodiscover = new AutoDiscover();
        $autodiscover->setComplexTypeStrategy(new ArrayOfTypeComplex());
        $autodiscover->setClass('Onyx\Service\Soap');
        
        //$autodiscover->setBindingStyle(array('style' => 'document'));
        //$autodiscover->setOperationBodyStyle(array('use' => 'literal'));
        $autodiscover->setUri($soapURL);
        $autodiscover->setServiceName("Onyx");
        
        $xml = $autodiscover->toXml();
        
        // Append the stylesheet
        $document = new \DOMDocument("1.0", "UTF-8");
        if($document->loadXML($xml)):
            $xpath = new \DOMXPath($document);
            $document->insertBefore($document->createProcessingInstruction("xml-stylesheet",'type="text/xsl" href="xsl"'), $xpath->evaluate('/*[1]')->item(0)); 
            $xml = $document->saveXML();
        endif;
        
        die($xml);
    }
    
    /**
     * Serve the XSL file for the WSDL document
     */
    public function xslAction(){
        die(file_get_contents(dirname(__FILE__)."/../Libs/xsl/wsdl-viewer.xsl"));
    }

}

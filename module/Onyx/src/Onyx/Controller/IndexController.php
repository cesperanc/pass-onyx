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
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController {
    
    public function indexAction() {
        
        $result = "";
        $client = new \SoapClient("http://localhost/onyx/public/services/wsdl", array('cache_wsdl' => 0));
        $result.=("<pre>".print_r($client->__getFunctions(), true)."</pre>");
        
        
        /*
        // Authentication with SOAP Header
        $client->__setSoapHeaders(new \SoapHeader('http://localhost/onyx/public/services/soap', 'authenticate', array(new \SoapVar(array(
            'username'=>'pass',
            'password'=>'pass'
        ), SOAP_ENC_OBJECT)), false));
        $users = $client->getUsers();
        
        */
        
        // Authentication with session ID
        if(session_start()):
            $result.="<pre>Local session started</pre>";
        endif;
        if(!empty($_SESSION["serverId"])):
            $session_id = $_SESSION["serverId"];
        else:
            $session_id = $_SESSION["serverId"] = $client->authenticate(array(
                'username'=>'pass',
                'password'=>'pass'
            ));
        endif;
        $result.=("<pre>Using session mode with the remote session ID ".$session_id."</pre>");
        $users = $client->getUsers($session_id);
        
        
        $result.=("<pre>users:".print_r($users, true)."</pre>");
        
        return new ViewModel(array('result' => $result));
    }
}

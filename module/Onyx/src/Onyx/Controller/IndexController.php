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

    /**
     * @var \Doctrine\ORM\EntityManager
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
    
    public function indexAction() {
        $result = "";
        
//$client = new \SoapClient("http://localhost/onyx/services/wsdl", array('cache_wsdl' => 0));
//$result.=("<pre>".print_r($client->__getFunctions(), true)."</pre>");
        
        
        /*
        // Authentication with SOAP Header
        $client->__setSoapHeaders(new \SoapHeader('http://localhost/onyx/services/soap', 'authenticate', array(new \SoapVar(array(
            'username'=>'pass',
            'password'=>'pass'
        ), SOAP_ENC_OBJECT)), false));
        $users = $client->getTeacherByFin($fin);
        
        */
        
        // Authentication with session ID
//if(session_start()):
//    $result.="<pre>Local session started</pre>";
//endif;
        
        
        $session_id = "";
        /*
        if(!empty($_SESSION["serverId"])):
            try{
                $client->verifyAuthentication($_SESSION["serverId"]);
                $session_id = $_SESSION["serverId"];
                die("bu");
            }  catch (Exception $e){
                
            }
        endif;
         * 
         */
//if(empty($session_id)):
//    $session_id = $_SESSION["serverId"] = $client->authenticate(array(
//        'username'=>'pass',
//        'password'=>'pass'
//    ));
//endif;
//$result.=("<pre>Using session mode with the remote session ID ".$session_id."</pre>");
//
//
//$fin = "183254481";
        /*
        
        $teacher = $client->getTeacherByFin($fin, $session_id);
        if(is_null($teacher)):
            $teacher = new \stdClass;
            $teacher->nif=$fin;
            $teacher->nome="Cláudio Esperança";
        endif;
        $teacher->email="cesperanc@gmail.com";
        $client->updateTeacher($teacher, $session_id);
        $teacher = $client->getTeacherByFin($fin, $session_id);
        $result.=("<pre>teacher:".print_r($teacher, true)."</pre>");
        */
        //$client->deleteTeacher($teacher, $session_id);

        /*
        $fces = $client->getFutureCoursesEditions($session_id);
        $result.=("<pre>getFutureCoursesEditions:".print_r($fces, true)."</pre>");
        /*
        foreach ($fces as $course):
            $result.=("<pre>getCourseCurricularUnits:".print_r($course->codplanocursofk, true)."</pre>");
            $result.=("<pre>getCourseCurricularUnits ({$course->codplanocursofk->codplanocurso}):".print_r($client->getCourseCurricularUnits($course->codplanocursofk, $session_id), true)."</pre>");
            
            break;
        endforeach;
        */
        
        /*
        $fces = $client->getCoursesCurricularUnits(array(array(
            'codplanocurso'=>'1'
        ),array(
            'codplanocurso'=>'481241'
        )), $session_id);
        $result.=("<pre>getCoursesCurricularUnits:".print_r($fces, true)."</pre>");
        */
        /*
        $fces = $client->getCurricularUnits(array(array(
            'codplanocursofk'=>array('codplanocurso'=>'1'),
            'codplanounidcurr'=>'5062'
        ),array(
            'codplanocursofk'=>array('codplanocurso'=>'481241'),
            'codplanounidcurr'=>'5063'
        ),array(
            'codplanocursofk'=>array('codplanocurso'=>'481241'),
            'codplanounidcurr'=>'5062'
        )), $session_id);
        $result.=("<pre>getCurricularUnits:".print_r($fces, true)."</pre>");
        */
        
        /*
        $fces = $client->setTeacherCurricularUnits(array(array(
            'codplanocursofk2'=>array('codplanocursofk'=>array('codplanocurso'=>'1')),
            'codplanounidcurrfk'=>array('codplanounidcurr'=>'5062'),
            'anoletivofk'=>array('anoletivo'=>'2013'),
            'semestrefk'=>array('semestre'=>'1')
        ),array(
            'codplanocursofk2'=>array('codplanocursofk'=>array('codplanocurso'=>'481241')),
            'codplanounidcurrfk'=>array('codplanounidcurr'=>'5063'),
            'anoletivofk'=>array('anoletivo'=>'2013'),
            'semestrefk'=>array('semestre'=>'1')
        ),array(
            'codplanocursofk2'=>array('codplanocursofk'=>array('codplanocurso'=>'481241')),
            'codplanounidcurrfk'=>array('codplanounidcurr'=>'5062'),
            'anoletivofk'=>array('anoletivo'=>'2013'),
            'semestrefk'=>array('semestre'=>'1')
        )), $fin, $session_id);
        $result.=("<pre>setTeacherCurricularUnits:".print_r($fces, true)."</pre>");
        */
        
        return new ViewModel(array('result' => $result));
    }
}

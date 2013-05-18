<?php
/**
 * Onyx
 *
 * @link      <http://dei.estg.ipleiria.pt>
 * @copyright Copyright (c) 2013 Cláudio Esperança <cesperanc@gmail.com>, Diogo Serra <2120915@my.ipleiria.pt>
 * @license  GNU General Public License as published by the Free Software Foundation; either version 3 of the License, or (at your option) any later version.
 */

namespace Onyx\Service;
        
class Soap{
    const SESSION_NAME = 'OnyxSoapService';
    const SESSION_AUTHENTICATED_FIELD = 'authenticatedUser';
    
    
    private $authenticatedUser=NULL;

    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;

    public function __construct($em=NULL) {
        $this->em = $em;
    }
    
    /**
     * Verifies the authentication
     * 
     * @throws \SoapFault if the request was not authenticated
     */
    private function verifyAuthentication($sessionId=NULL){
        if(!empty($sessionId)):
            session_id($sessionId);
            if(session_start()):
                if(isset($_SESSION[self::SESSION_AUTHENTICATED_FIELD])):
                    $this->authenticatedUser = $_SESSION[self::SESSION_AUTHENTICATED_FIELD];
                endif;
            endif;
        endif;
        if(empty($this->authenticatedUser)):
            throw new \SoapFault("Failed to authenticate. The request must be authenticated with a soap header to authenticate the request.", 412);
        endif;
    }


    /**
     * Authenticates the SOAP request. 
     *
     * @param Onyx\Service\SoapAuthentication
     * @return string with the session ID
     */
    public function authenticate($login){
        if(!empty($login->username) && !empty($login->password)):
            if($login->username=="pass" && $login->password=="pass"):
                $this->authenticatedUser = $login->username;
            
                session_name(self::SESSION_NAME);
                if(session_start()):
                    $_SESSION[self::SESSION_AUTHENTICATED_FIELD] = $login->username;
                    return session_id();
                endif;
                return true;
            endif;
            throw new \SOAPFault("Incorrect username and or password.", 401);
        else:
            throw new \SOAPFault("Invalid username and password format. Values may not be empty and are case-sensitive.", 400);
        endif;
    }


    /**
     * Get all the users in the database
     * 
     * @uses Onyx\Service\Soap::authenticate to authenticate the request
     * @param string $sessionId with the optional session ID from the authenticate method
     * @return Onyx\Entity\UsersTbl[]
     */
    public function getUsers($sessionId=""){
        $this->verifyAuthentication($sessionId);
        
        if(is_null($this->em)):
            throw new \SoapFault("No entity manager found.", 500);
        endif;
        
        return $this->em->getRepository("Onyx\Entity\UsersTbl")->findAll();
    }
}
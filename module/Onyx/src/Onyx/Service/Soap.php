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
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    public function __construct($em=NULL) {
        $this->em = $em;
        //$this->em->getConnection()->getConfiguration()->setSQLLogger(new \Doctrine\DBAL\Logging\DebugStack());
    }
    
    /**
     * Verifies the authentication
     * 
     * @param string $sessionId With the session ID to validate
     * @throws \SoapFault if the request was not authenticated
     */
    public function verifyAuthentication($sessionId=NULL){
        if(!empty($sessionId)):
            session_id($sessionId);
            if(((function_exists("session_status") && session_status() !== PHP_SESSION_ACTIVE) || session_id() === "") && session_start()):
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
     * Verifies the entity manager presence
     * 
     * @throws \SoapFault if no entity manager was found
     */
    private function verifyEntityManager(){
        if(is_null($this->em)):
            throw new \SoapFault("No entity manager found.", 500);
        endif;
    }
    
    /**
     * Copy the properties from an object to another
     * 
     * @param object $origin
     * @param object $destination
     * @return object $destination
     */
    private static function copyProperties($origin, $destination){
        foreach($origin as $property => $value):
            $destination->$property = $value;
        endforeach;
        return $destination;
    }
    
    /**
     * Create a QueryBuilder with the data from $dataObject
     * 
     * @param string $entity with the entity name reference
     * @param object $dataObject with the entity data to copy
     * @param string $prefix with the prefix for the fields
     * @return \Doctrine\ORM\QueryBuilder instance
     */
    private function createQbFromObject($entity, $dataObject, $prefix=NULL){
        $qb = $this->em->createQueryBuilder();
        $qb->update($entity, $prefix);
        foreach($dataObject as $property=>$value):
            if(isset($value)):
                $qb->set((!empty($prefix)?"{$prefix}.":'').$property, $qb->expr()->literal($value));
            endif;
        endforeach;
        return $qb;
    }

    /**
     * Authenticates the SOAP request. 
     *
     * @param \Onyx\Service\SoapAuthentication
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
     * Get a teacher by Fiscal Identification Number
     * 
     * @param string $fin with the Fiscal Identification Number
     * @param string $sessionId with the optional session ID from the authenticate method
     * @return \Onyx\Entity\Docentes
     * 
     * @uses \Onyx\Service\Soap::authenticate to authenticate the request
     * @uses \Onyx\Service\Soap::verifyEntityManager to check for entity manager
     */
    public function getTeacherByFin($fin, $sessionId=""){
        $this->verifyAuthentication($sessionId);
        $this->verifyEntityManager();
        
        return $this->em->getRepository("Onyx\Entity\Docentes")->findOneBy(array('nif' => $fin));
    }
    
    /**
     * Check if a teacher exists by Fiscal Identification Number
     * 
     * @param \Onyx\Entity\Docentes $teacher with the teacher to check
     * @param string $sessionId with the optional session ID from the authenticate method
     * @return boolean true if it exists, false otherwise
     * 
     * @uses \Onyx\Service\Soap::authenticate to authenticate the request
     * @uses \Onyx\Service\Soap::verifyEntityManager to check for entity manager
     */
    public function teacherExists($teacher, $sessionId=""){
        return (!is_null($this->getTeacherByFin($teacher->nif, $sessionId)));
    }
    
    /**
     * Insert a new teacher based on their FIN
     * 
     * @param \Onyx\Entity\Docentes $teacher with the teacher data to insert
     * @param string $sessionId with the optional session ID from the authenticate method
     * @return boolean true if the operation was successfuly terminated, false otherwise
     * 
     * @uses \Onyx\Service\Soap::authenticate to authenticate the request
     * @uses \Onyx\Service\Soap::verifyEntityManager to check for entity manager
     */
    public function insertTeacher($teacher, $sessionId=""){
        return $this->updateTeacher($teacher, $sessionId);
    }
    
    /**
     * Update a teacher data based on their FIN
     * 
     * @param \Onyx\Entity\Docentes $teacher with the teacher data to update
     * @param string $sessionId with the optional session ID from the authenticate method
     * @return boolean true if the operation was successfuly terminated, false otherwise
     * 
     * @uses \Onyx\Service\Soap::authenticate to authenticate the request
     * @uses \Onyx\Service\Soap::verifyEntityManager to check for entity manager
     */
    public function updateTeacher($teacher, $sessionId=""){
        $this->verifyAuthentication($sessionId);
        $this->verifyEntityManager();
        
        if(!$this->teacherExists($teacher, $sessionId)):
            // Insert
            $teacher = self::copyProperties($teacher, new \Onyx\Entity\Docentes());
            $this->em->persist($teacher);
            $this->em->flush();
            return $this->teacherExists($teacher, $sessionId);
            
        else:
            // Update
            $qb = $this->createQbFromObject('Onyx\Entity\Docentes', $teacher, 'd');
            $qb->where('d.nif = :identifier')->setParameter('identifier', $teacher->nif);
            return $qb->getQuery()->execute();
        endif;
        return false;
    }
    
    /**
     * Deletes a teacher based on their FIN
     * 
     * @param \Onyx\Entity\Docentes $teacher with the teacher data to delete
     * @param string $sessionId with the optional session ID from the authenticate method
     * @return boolean true if the operation was successfuly terminated, false otherwise
     * 
     * @uses \Onyx\Service\Soap::authenticate to authenticate the request
     * @uses \Onyx\Service\Soap::verifyEntityManager to check for entity manager
     */
    public function deleteTeacher($teacher, $sessionId=""){
        $this->verifyAuthentication($sessionId);
        $this->verifyEntityManager();
        
        if($this->teacherExists($teacher, $sessionId)):
            // Delete
            $qb = $this->em->createQueryBuilder()->delete('Onyx\Entity\Docentes', 'd');
            $qb->where('d.nif = :identifier')->setParameter('identifier', $teacher->nif);
            return $qb->getQuery()->execute();
        endif;
        return true;
    }
    
    /**
     * Get future course editions based on the current date
     * 
     * @param string $sessionId with the optional session ID from the authenticate method
     * @return \Onyx\Entity\Edicaocursosemestreletivo[]
     * 
     * @uses \Onyx\Service\Soap::authenticate to authenticate the request
     * @uses \Onyx\Service\Soap::verifyEntityManager to check for entity manager
     */
    public function getFutureCoursesEditions($sessionId=""){
        $this->verifyAuthentication($sessionId);
        $this->verifyEntityManager();
        
        try{
            
            $result = $this->em->createQueryBuilder()
            ->select('ecsl')
            ->distinct()
            ->from('Onyx\Entity\Edicaocursosemestreletivo', 'ecsl')
            ->where('ecsl.datainicio > CURRENT_DATE()')
            ->getQuery()
            ->setFetchMode('Onyx\Entity\Edicaocursosemestreletivo', 'codplanocursofk', \Doctrine\ORM\Mapping\ClassMetadataInfo::FETCH_EAGER)
            ->setFetchMode('Onyx\Entity\Edicaocursosemestreletivo', 'iddiretorcursoedicaofk', \Doctrine\ORM\Mapping\ClassMetadataInfo::FETCH_EAGER)
            ->execute();
            
            return $result;
        } catch (Exception $e){
            throw new \SOAPFault("Error occurred ".$e, 500);
        }
        
        return array();
    }
    
    /**
     * Get the curricular units associated with a course
     * 
     * @param \Onyx\Entity\Planocurso $course with the codplanocurso with curricular units associated with
     * @param string $sessionId with the optional session ID from the authenticate method
     * @return \Onyx\Entity\Planounidcurr[]
     * 
     * @uses \Onyx\Service\Soap::authenticate to authenticate the request
     * @uses \Onyx\Service\Soap::verifyEntityManager to check for entity manager
     */
    public function getCourseCurricularUnits($course, $sessionId=""){
        $this->verifyAuthentication($sessionId);
        $this->verifyEntityManager();
        
        try{
            return $this->em->createQueryBuilder()->select('puc')
            ->from('Onyx\Entity\Planounidcurr', 'puc')
            ->where('puc.codplanocursofk = :identifier')
            ->setParameter('identifier', $course->codplanocurso)
            ->getQuery()
            ->execute();
            
        } catch (Exception $e){
            throw new \SOAPFault("Error occurred".$e, 500);
        }
        
        return array();
    }
    
    /**
     * Get the curricular units associated with courses ids
     * 
     * @param \Onyx\Entity\Planocurso[] $coursesIds with an array of Planocurso with codplanocurso defined
     * @param string $sessionId with the optional session ID from the authenticate method
     * @return \Onyx\Entity\Planounidcurr[]
     * 
     * @uses \Onyx\Service\Soap::authenticate to authenticate the request
     * @uses \Onyx\Service\Soap::verifyEntityManager to check for entity manager
     */
    public function getCoursesCurricularUnits($courses, $sessionId=""){
        $this->verifyAuthentication($sessionId);
        $this->verifyEntityManager();
        
        $coursesIds = array();
        foreach ($courses as $course):
            $coursesIds[] = $course->codplanocurso;
        endforeach;
        
        try{
            return $this->em->createQueryBuilder()
            ->select('puc')
            ->from('Onyx\Entity\Planounidcurr', 'puc')
            ->where('puc.codplanocursofk IN (:ids)')
            ->orderBy('puc.nome', 'ASC')
            ->setParameter('ids', $coursesIds)
            ->getQuery()
            ->setFetchMode('Onyx\Entity\Planounidcurr', 'codplanocursofk', \Doctrine\ORM\Mapping\ClassMetadataInfo::FETCH_EAGER)
            ->execute();
            
        } catch (Exception $e){
            throw new \SOAPFault("Error occurred".$e, 500);
        }
        
        return array();
    }
    
    /**
     * Get the full data about the given curricular units associated with the given courses
     * 
     * @param \Onyx\Entity\Planounidcurr[] $courseCurricularUnits with an array of Planounidcurr with codplanocursofk->codplanocurso and codplanounidcurr defined
     * @param string $sessionId with the optional session ID from the authenticate method
     * @return \Onyx\Entity\Planounidcurr[]
     * 
     * @uses Onyx\Service\Soap::authenticate to authenticate the request
     * @uses Onyx\Service\Soap::verifyEntityManager to check for entity manager
     */
    public function getCurricularUnits($courseCurricularUnits, $sessionId=""){
        $this->verifyAuthentication($sessionId);
        $this->verifyEntityManager();
        try{
            $qb = $this->em->createQueryBuilder()
            ->select('puc')
            ->from('Onyx\Entity\Planounidcurr', 'puc')
            ->orderBy('puc.nome', 'ASC');
            
            $i=0;
            foreach ($courseCurricularUnits as $course):
                if(isset($course->codplanocursofk) && isset($course->codplanocursofk->codplanocurso) && isset($course->codplanounidcurr)):
                    if($i==0):
                        $qb->where("(puc.codplanocursofk = :cpc{$i} AND puc.codplanounidcurr = :cpuc{$i})");
                    else:
                        $qb->orWhere("(puc.codplanocursofk = :cpc{$i} AND puc.codplanounidcurr = :cpuc{$i})");
                    endif;
                    $qb->setParameter("cpc{$i}", $course->codplanocursofk->codplanocurso);
                    $qb->setParameter("cpuc{$i}", $course->codplanounidcurr);
                    $i++;
                endif;
            endforeach;
            
            return $qb->getQuery()
            ->setFetchMode('Onyx\Entity\Planounidcurr', 'codplanocursofk', \Doctrine\ORM\Mapping\ClassMetadataInfo::FETCH_EAGER)
            ->execute();
            
        } catch (Exception $e){
            throw new \SOAPFault("Error occurred".$e, 500);
        }
        
        return array();
    }
    
    /**
     * Set the teacher for given curricular units associated with given courses
     * 
     * @param \Onyx\Entity\Edicaounidcurrdocentes[] $curricularUnits with an array of Planounidcurr with codplanocursofk->codplanocurso and codplanounidcurr defined
     * @param string $fin with the Fiscal Identification Number
     * @param string $sessionId with the optional session ID from the authenticate method
     * @return boolean true on sucess, false otherwise
     * 
     * @uses Onyx\Service\Soap::authenticate to authenticate the request
     * @uses Onyx\Service\Soap::verifyEntityManager to check for entity manager
     */
    public function setTeacherCurricularUnits($curricularUnits, $fin="", $sessionId=""){
        
        $this->verifyAuthentication($sessionId);
        $this->verifyEntityManager();
        try{
            $result = true;

            $conn = $this->em->getConnection();
            foreach ($curricularUnits as $curricularUnit):
                
                // Get the teacher
                $teacher = isset($curricularUnit->iddocentefk->iddocente)?$curricularUnit->iddocentefk->iddocente:"";
                if(!empty($fin)):
                    $teacher = $this->em->getRepository("Onyx\Entity\Docentes")->findOneBy(array('nif' => $fin));
                    if(!is_null($teacher)):
                        $teacher = $teacher->getIddocente();
                    endif;
                endif;
                
                // Check the data
                if( empty($teacher) || 
                    empty($curricularUnit) || 
                    empty($curricularUnit->codplanocursofk2) || 
                    empty($curricularUnit->codplanocursofk2->codplanocursofk) || 
                    empty($curricularUnit->codplanocursofk2->codplanocursofk->codplanocurso) || 
                    empty($curricularUnit->codplanounidcurrfk) || 
                    empty($curricularUnit->codplanounidcurrfk->codplanounidcurr) || 
                    empty($curricularUnit->anoletivofk) || 
                    empty($curricularUnit->anoletivofk->anoletivo) || 
                    empty($curricularUnit->semestrefk) || 
                    empty($curricularUnit->semestrefk->semestre)
                ):
                    if($result):
                        $result = false;
                    endif;
                    continue;
                endif;

                // Prepare the data
                $courseId = $curricularUnit->codplanocursofk2->codplanocursofk->codplanocurso;
                $curricularUnitId = $curricularUnit->codplanounidcurrfk->codplanounidcurr;
                $year = $curricularUnit->anoletivofk->anoletivo;
                $semester = $curricularUnit->semestrefk->semestre;
                $percentage = "50"; // @ TODO: should be calculated here
                $semesterHours = ""; // @ TODO: should be calculated here
                $salary = ""; // @ TODO: should be calculated here
                $textualSalaryValue = ""; // @ TODO: should be calculated here

                // Check for similar data
                $existant = $this->em->getRepository("Onyx\Entity\Edicaounidcurrdocentes")->findOneBy(array(
                    'codplanocursofk2' => $courseId,
                    'codplanounidcurrfk' => $curricularUnitId,
                    'anoletivofk' => $year,
                    'semestrefk' => $semester,
                    'iddocentefk' => $teacher,
                    'percentagemservdocfk' => $percentage
                ));

                // If none found, insert it on the database
                if(is_null($existant)):
                    if($conn->insert('edicaoUnidCurrDocentes', array(
                    'codplanocursofk2' => $courseId,
                    'codplanounidcurrfk' => $curricularUnitId,
                    'anoletivofk' => $year,
                    'semestrefk' => $semester,
                    'iddocentefk' => $teacher,
                    'percentagemservdocfk' => $percentage,
                    'horassemestre' => $semesterHours,
                    'vencimento' => $salary,
                    'vencimentoextenso' => $textualSalaryValue
                    ))==0):
                        if($result):
                            $result = false;
                        endif;
                    endif;
                else:
                    // Update if needed
                endif;
            endforeach;
            
            return $result;
            
        } catch (Exception $e){
            throw new \SOAPFault("Error occurred".$e, 500);
        }
        
        return false;
    }
}



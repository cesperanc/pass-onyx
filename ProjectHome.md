ONYX is a software solution which integrates the Zend Framework with Doctrine ORM, using a custom Zend module to provide a SOAP service used by a specific client (in our implementation, the ProcessorMaker).

![http://wiki.pass-onyx.googlecode.com/git/images/architecture.png](http://wiki.pass-onyx.googlecode.com/git/images/architecture.png)

Technically doctrine is used to reverse engineer a MySQL database into classes used by the web service provided with the Zend Framework. Autodiscovery is used the generate the web service descriptors for the clients. With this system, if the structure of the database is updated, this changes can be reflected on the code by running the generateEntities.bash script.

On ProcessMaker we implemented some triggers to interface the provided service with the business process used as a proof of concept.

On this site, in addition to the source code, is made available documentation (development and management) project, virtual machines with the whole solution, data structures, files with the business process, etc.
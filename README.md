ONYX is a software solution which integrates the Zend Framework with the Doctrine ORM, using a custom Zend module to provide a SOAP service used by a specific client (in our implementation, the ProcessorMaker).

![](https://raw.githubusercontent.com/cesperanc/pass-onyx/wiki/images/architecture.png)

Doctrine is used to reverse engineer a MySQL database into classes used by the web service provided with the Zend Framework. Autodiscovery is used to generate the web service descriptors for the clients. With this system, if the structure of the database is updated, those changes can be integrated in the code by running the generateEntities.bash script.

On ProcessMaker we implemented some triggers to interface the provided service with the business process used as a proof of concept.

This is the documentation branch.

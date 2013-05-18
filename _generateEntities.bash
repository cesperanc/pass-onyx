#!/bin/bash

./vendor/doctrine/doctrine-module/bin/doctrine-module orm:convert-mapping --namespace="Onyx\\Entity\\" --force --from-database annotation ./module/Onyx/src/
./vendor/doctrine/doctrine-module/bin/doctrine-module orm:generate-entities ./module/Onyx/src/ --generate-annotations=true
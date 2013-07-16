#!/bin/bash

rsync -avz --delete -e ssh . cesperanc@$1:/opt/onyx/

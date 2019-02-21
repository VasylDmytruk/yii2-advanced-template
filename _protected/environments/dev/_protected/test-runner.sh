#!/usr/bin/env bash

cd tests/codeception/api/
../../../vendor/bin/codecept run

cd ../common/
../../../vendor/bin/codecept run

cd ../console/
../../../vendor/bin/codecept run

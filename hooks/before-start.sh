#!/bin/bash

# ---------------------------------------------------------------------------- #
# Copyright 2015, Plico                                                        #
#                                                                              #
# Licensed under the Apache License, Version 2.0 (the "License"); you may      #
# not use this file except in compliance with the License. You may obtain      #
# a copy of the License at                                                     #
#                                                                              #
# http://www.apache.org/licenses/LICENSE-2.0                                   #
#                                                                              #
# Unless required by applicable law or agreed to in writing, software          #
# distributed under the License is distributed on an "AS IS" BASIS,            #
# WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.     #
# See the License for the specific language governing permissions and          #
# limitations under the License.                                               #
# ---------------------------------------------------------------------------- #

# ---------------------------------------------------------------------------- #
# hooks/before-start.sh                                                        #
# Executed on the very beginig on deploy process, responsible to define the    #
# build variables and pass the context to phing								   #
# ---------------------------------------------------------------------------- #
# 

echo "bamboo_deploy_release $bamboo_deploy_release"
echo "bamboo_deploy_version $bamboo_deploy_version"
echo "bamboo_buildNumber $bamboo_buildNumber"

DIRNAME=`dirname $(readlink -f $0)`


echo $bamboo_vars_version

echo "FULL VERSION: $bamboo_vars_version.$bamboo_buildNumber"
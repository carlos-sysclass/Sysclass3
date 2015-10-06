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
# hooks/srv-after-deploy.sh                                                        #
# Executado após a instalação.                                                 #
# ---------------------------------------------------------------------------- #

DIRNAME=`dirname $(readlink -f $0)`

ln -s ../files/public/ files

#creating cache dir
#mkdir -p "$DIRNAME/../cache/volt"
#chmod 777 "$DIRNAME/../cache/volt" -R


mkdir -p "$DIRNAME/../cache/metadata"
chmod 777 "$DIRNAME/../cache/metadata" -R

mkdir -p "$DIRNAME/../logs"
chmod 777 "$DIRNAME/../logs" -R

mkdir -p "$DIRNAME/../www/resources"
chmod 777 "$DIRNAME/../www/resources" -R

# INJECT VERSION INSIDE DATABASE
$full_version=$1
$branch=$2

echo "[deploy]" > RELEASE
echo "version=$full_version" >> RELEASE
echo "version_suffix=$branch" >> RELEASE


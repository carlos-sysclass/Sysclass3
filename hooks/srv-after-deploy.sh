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
# hooks/srv-after-deploy.sh                                                    #
# Executado após a instalação.                                                 #
# ---------------------------------------------------------------------------- #

declare `awk -F = '{print $0}' $1`

DIRNAME=`dirname $(readlink -f $0)`

#ln -s ../files/public/ files

#creating cache dir
#mkdir -p "$DIRNAME/../cache/volt"
#chmod 777 "$DIRNAME/../cache/volt" -R


mkdir -p "$DIRNAME/../cache/metadata"
chmod 777 "$DIRNAME/../cache/metadata" -R

mkdir -p "$DIRNAME/../cache/models"
chmod 777 "$DIRNAME/../cache/models" -R

mkdir -p "$DIRNAME/../cache/images"
chmod 777 "$DIRNAME/../cache/images" -R

mkdir -p "$DIRNAME/../cache/view/volt/compiled"
chmod 777 "$DIRNAME/../cache/view/volt/compiled" -R

mkdir -p "$DIRNAME/../cache/view/smarty/cache"
chmod 777 "$DIRNAME/../cache/view/smarty/cache" -R

mkdir -p "$DIRNAME/../cache/view/smarty/compiled"
chmod 777 "$DIRNAME/../cache/view/smarty/compiled" -R



mkdir -p "$DIRNAME/../logs"
chmod 777 "$DIRNAME/../logs" -R

mkdir -p "$DIRNAME/../www/resources"
chmod 777 "$DIRNAME/../www/resources" -R

chmod 777 "$DIRNAME/../vendor/mpdf/mpdf/ttfontdata" -R

# INJECT VERSION INSIDE DATABASE
#full_version=$1
#branch=$2

echo "[deploy]" > RELEASE
echo "base_version=$base_version" >> RELEASE
echo "full_version=$full_version" >> RELEASE
echo "build_number=$build_number" >> RELEASE
echo "branch=$branch" >> RELEASE
echo "environment=$environment" >> RELEASE

# UPDATE THE SYSCLASS SERVICE AND LET HIM THERE (MANUAL UPDATE)
dirname=`readlink -f .`; cat hooks/service.sh | sed "s#{base_path}#$dirname#g" | sed "s#{environment}#$environment#g" > sysclassd-$environment
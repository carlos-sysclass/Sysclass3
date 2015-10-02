# -------------------------------------------------------------------------- #
# Copyright 2015, plico web                                                   #
#                                                                            #
# Licensed under the Apache License, Version 2.0 (the "License"); you may    #
# not use this file except in compliance with the License. You may obtain    #
# a copy of the License at                                                   #
#                                                                            #
# http://www.apache.org/licenses/LICENSE-2.0                                 #
#                                                                            #
# Unless required by applicable law or agreed to in writing, software        #
# distributed under the License is distributed on an "AS IS" BASIS,          #
# WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.   #
# See the License for the specific language governing permissions and        #
# limitations under the License.                                             #
#--------------------------------------------------------------------------- #

# -------------------------------------------------------------------------- #
# Process Summary
# - Checkout new tag on a new path
# - ON NEW TAG:
#   - Run pre-release scripts (including database changes (must be reversible))
#   - Run Automated tests
#     - php -l (LINT MODE)
#     - phpunit tests
# - ON CURRENT TAG
#   - Run pre-unrelease scripts
# - Switch Symbolin links from current to new tag
# - ON CURRENT (NOW OLD) TAG
#   - Run post-unrelease scripts
# - ON NEW (NOW CURRENT) TAG :
#   - Run post-release scripts
#   - mark OLD RELEASE to DELETE in 7 Days
# -------------------------------------------------------------------------- #

deploy_version=$bamboo_vars_full_version
enviroment=$bamboo_deploy_environment

if [ -z $deploy_version ]; then
    echo "Please especify the version to deploy. Exiting..."
    exit 1
fi

if [ -z $enviroment ]; then
    echo "Please especify the enviroment to deploy. Exiting..."
    exit 1
fi

cd $enviroment

git clone --depth 1 --branch $deploy_version git@bitbucket.org:wiseflex/sysclass3.git $deploy_version

if [ $? -ne "0" ]; then
    echo "The especified version does not exist. Exiting..."
    exit 2
fi
rm -rf current

# DISABLING FOR NOW
ln -s $deploy_version current

cd $deploy_version
chmod a+x hooks/after-deploy.sh
hooks/./after-deploy.sh

cd ..

#sudo service php-fpm restart; sudo service varnish restart; sudo service nginx restart
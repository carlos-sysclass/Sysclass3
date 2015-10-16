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
 
# SERTTING TO LOWERCASE
bamboo_deploy_environment=`echo $bamboo_deploy_environment | tr '[:upper:]' '[:lower:]'`
echo "environment=$bamboo_deploy_environment" >> $bamboo_working_directory/variables.txt

#deploy_version=$bamboo_vars_full_version
#enviroment=$bamboo_deploy_environment
#enviroment=$bamboo_planRepository_branch
#scp -P 22411 -i ~/.ssh/id_rsa $bamboo_working_directory/hooks/variables.txt 54.149.57.151:/var/www/sysclass/deploy-info/$enviroment/info

#git_bin=$bamboo_capability_system_git_executable

#FULL_RELEASE=$bamboo_vars_full_version
#MESSAGE="RELEASE ${FULL_RELEASE}"

#$git_bin add $bamboo_working_directory/RELEASE
#$git_bin commit -m "RELEASE ${FULL_RELEASE}" 

#$git_bin tag -a ${FULL_RELEASE} -m "RELEASE ${FULL_RELEASE}"
#$git_bin remote add central ${bamboo_planRepository_repositoryUrl}
#$git_bin push central ${FULL_RELEASE}
#$git_bin ls-remote --exit-code --tags central ${FULL_RELEASE}



#bamboo_planRepository_1_branch=develop
#bamboo_repository_revision_number=c4ebf91ebc4f36847e116295294a96d7ba73fef8
#bamboo_repository_360449_previous_revision_number=e4640f99ced475ae886fd58c51c3e0d6c545c3c0
#bamboo_resultsUrl=http://tasks.plicoweb.com.br/bamboo/browse/SC-DEV-JOB1-2
#bamboo_repository_360449_name=wiseflex/sysclass3:develop
#bamboo_planRepository_1_name=wiseflex/sysclass3:develop
#bamboo_shortPlanName=Develop
#bamboo_repository_360449_revision_number=c4ebf91ebc4f36847e116295294a96d7ba73fef8
#bamboo_planRepository_name=wiseflex/sysclass3:develop
#bamboo_buildNumber=2
#bamboo_buildResultsUrl=http://tasks.plicoweb.com.br/bamboo/browse/SC-DEV-JOB1-2
#bamboo_planRepository_repositoryUrl=git@bitbucket.org:wiseflex/sysclass3.git
#bamboo_agentId=131073
#bamboo_shortPlanKey=DEV
#bamboo_repository_360449_branch_name=develop
#bamboo_planRepository_revision=c4ebf91ebc4f36847e116295294a96d7ba73fef8
#bamboo_repository_previous_revision_number=e4640f99ced475ae886fd58c51c3e0d6c545c3c0
#bamboo_buildTimeStamp=2015-10-01T20:30:36.486Z
#bamboo_planRepository_previousRevision=e4640f99ced475ae886fd58c51c3e0d6c545c3c0
#bamboo_repository_360449_git_branch=develop
#bamboo_buildResultKey=SC-DEV-JOB1-2
#bamboo_repository_git_branch=develop
#bamboo_repository_branch_name=develop
#bamboo_buildPlanName=Sysclass - Develop - Default Job
#bamboo_repository_360449_git_username=
#bamboo_planRepository_1_revision=c4ebf91ebc4f36847e116295294a96d7ba73fef8
#bamboo_repository_name=wiseflex/sysclass3:develop
#bamboo_dependenciesDisabled=false
#bamboo_planRepository_branch=develop
#bamboo_repository_360449_git_repositoryUrl=git@bitbucket.org:wiseflex/sysclass3.git
#bamboo_agentWorkingDirectory=/var/atlassian/application-data/bamboo/xml-data/build-dir
#bamboo_capability_system_git_executable=/bin/git
#bamboo_planRepository_1_previousRevision=e4640f99ced475ae886fd58c51c3e0d6c545c3c0
#bamboo_repository_git_username=
#bamboo_planRepository_1_type=git
#bamboo_planRepository_branchName=develop
#bamboo_capability_system_jdk_JDK=/opt/jdk1.8.0_60
#bamboo_planRepository_type=git
#bamboo_planRepository_1_username=
#bamboo_ManualBuildTriggerReason_userName=akucaniz
#bamboo_repository_git_repositoryUrl=git@bitbucket.org:wiseflex/sysclass3.git
#bamboo_working_directory=/var/atlassian/application-data/bamboo/xml-data/build-dir/SC-DEV-JOB1
#bamboo_planKey=SC-DEV
#bamboo_planRepository_1_repositoryUrl=git@bitbucket.org:wiseflex/sysclass3.git
#bamboo_planRepository_username=
#bamboo_capability_system_jdk_JDK_1_8_0_60=/opt/jdk1.8.0_60
#bamboo_capability_system_jdk_JDK_1_8=/opt/jdk1.8.0_60
#bamboo_planRepository_1_branchName=develop
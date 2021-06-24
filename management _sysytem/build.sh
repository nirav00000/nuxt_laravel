#!/bin/bash

set -ex
if [[ -z ${DRONE} && -z ${DRONE_BUILD_NUMBER} ]]; then
  echo "=== executing ${MBT_MODULE_NAME} at ${MBT_MODULE_PATH} with commit ${MBT_BUILD_COMMIT} ==="
  echo TAG=improwised/${MBT_MODULE_PROPERTY_NAME}:${DRONE_BRANCH/\//-}-${DRONE_COMMIT_SHA:0:7}-$(date +%s)
  echo img build -t ${TAG} -f ${MBT_MODULE_PROPERTY_DOCKERFILE} --build-arg=BUILD_TYPE=staging --no-console .
  echo img login --username=${DOCKER_USERNAME} --password=${DOCKER_PASSWORD}
  echo img push ${TAG}
else
  echo "=== executing ${MBT_MODULE_NAME} at ${MBT_MODULE_PATH} with commit ${MBT_BUILD_COMMIT} ==="
  TAG=improwised/${MBT_MODULE_PROPERTY_NAME}:${DRONE_BRANCH/\//-}-${DRONE_COMMIT_SHA:0:7}-$(date +%s)
  img build -t ${TAG} -f ${MBT_MODULE_PROPERTY_DOCKERFILE} --build-arg=BUILD_TYPE=staging --no-console .
  img login --username=${DOCKER_USERNAME} --password=${DOCKER_PASSWORD}
  img push ${TAG}
fi

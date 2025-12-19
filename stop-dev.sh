#!/bin/bash

# Цвета для вывода
GREEN='\033[0;32m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${GREEN}Останавливаем среду разработки...${NC}"

# Остановка и удаление контейнера
docker stop boostmode-dev 2>/dev/null || true
docker rm boostmode-dev 2>/dev/null || true

echo -e "${GREEN}Среда разработки остановлена.${NC}" 
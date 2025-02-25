apt update

apt upgrade -y

curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.1/install.sh | bash

source ~/.bashrc

nvm install 22

apt install -y git

apt install -y ssh

apt install -y nano

cd /app/backend

rm -Rf vendor && composer config process-timeout 4000 && composer install &&   php artisan jwt:secret


cd /app/frontend && rm -rf node_modules && npm cache clean --force && npm_config_fetch_timeout=1000000 npm install --legacy-peer-deps && npm run serve


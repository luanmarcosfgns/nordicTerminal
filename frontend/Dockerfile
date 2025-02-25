FROM node:20
WORKDIR /app
CMD ["/bin/sh", "-c", "cd /app && rm -rf node_modules && npm cache clean --force && npm_config_fetch_timeout=1000000 npm install --legacy-peer-deps && npm run serve"]


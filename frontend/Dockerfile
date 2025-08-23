FROM node:20.19

WORKDIR /app

COPY package*.json ./

RUN npm install --no-fund --no-audit

COPY . .

EXPOSE 5173

CMD ["npm", "run", "dev", "--", "--host", "0.0.0.0", "--port", "5173"]

FROM node:22-alpine
WORKDIR /app
COPY package.json package-lock.json* ./
RUN npm install
COPY . .
EXPOSE 5173
CMD ["npm","run","dev","--","--host"]

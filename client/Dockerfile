FROM node:22.18-alpine3.22

WORKDIR /app

RUN npm install -g pnpm

COPY package.json pnpm-lock.yaml ./

# Install dependencies
RUN pnpm install --frozen-lockfile

COPY . .

RUN pnpm run build

EXPOSE 4000

CMD ["pnpm", "serve:ssr", "--host", "0.0.0.0"]

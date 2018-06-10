FROM node as client-builder

# set working directory
RUN mkdir /usr/src/app
WORKDIR /usr/src/app

# install and cache app dependencies
COPY /my-app/package.json /usr/src/app/package.json
RUN npm install --silent
RUN npm install -g @angular/cli --unsafe

# add app
COPY ./my-app /usr/src/app

WORKDIR /usr/src/app

# generate build
RUN ng build --prod

# base image
FROM nginx:1.13.9-alpine

## Remove default nginx website
RUN rm -rf /usr/share/nginx/html/*

## From 'builder' stage copy over the artifacts in dist folder to default nginx public folder
COPY --from=client-builder /usr/src/app/dist/my-app /usr/share/nginx/html

CMD ["nginx", "-g", "daemon off;"]
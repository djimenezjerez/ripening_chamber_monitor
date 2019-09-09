FROM node:12.10.0-alpine
LABEL maintainer="Daniel Jimenez <djimenezjerez@gmail.com>"
ARG TZ=America/La_Paz
ENV TZ ${TZ}
RUN apk update && apk add --no-cache tzdata
RUN cp /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
CMD [ "npm", "start" ]
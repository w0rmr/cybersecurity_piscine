FROM debian:bookworm

RUN apt-get update && apt-get install -y \
    gcc \
    gdb \
    make \
    wget 

RUN dpkg --add-architecture i386 \
    && apt-get update \
    && apt-get install -y libc6-i386

WORKDIR /app
COPY . /app

CMD ["tail","-f","/dev/null"]
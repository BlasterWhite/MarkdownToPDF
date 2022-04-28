FROM debian:11.1
FROM bigpapoo/weasyprint

WORKDIR /work

COPY autorun.sh /work
COPY mdToHtml.php /work
COPY facturation.php /work

RUN apt update -yq \
&& DEBIAN_FRONTEND=noninteractive \
&& apt -y update \
&& apt -y upgrade \
&& apt -y install dos2unix \
&& apt -y clean \
&& cd /work \
&& chmod +x autorun.sh mdToHtml.php facturation.php \
&& dos2unix autorun.sh

CMD ["./autorun.sh"]
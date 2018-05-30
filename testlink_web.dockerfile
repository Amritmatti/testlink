FROM webdevops/php-nginx:alpine-php7

ENV TL_USER "application:application"
ENV TL_VERSION "1.9.17"
ENV TL_ROOT "/app"
ENV TL_VAR_DIR "/var/testlink"
ENV TL_OPT_DIR "/opt/testlink"
ENV TL_FILES_PATH "files"
ENV TL_FILES_DIR "${TL_ROOT}/${TL_FILES_PATH}"

COPY ["context/web/app/*", "${TL_ROOT}/"]
COPY ["context/web/opt/*", "${TL_OPT_DIR}/"]

RUN set -e && chdir ${TL_ROOT} \
	# Download Testlink and extract it to $TL_ROOT
	&& wget --no-check-certificate -q -P . "https://github.com/TestLinkOpenSourceTRMS/testlink-code/archive/${TL_VERSION}.tar.gz" \
	&& tar xf ${TL_VERSION}.tar.gz --strip-components=1 && rm ${TL_VERSION}.tar.gz \
	# Create required directories and set permissions
	&& mkdir -p ${TL_VAR_DIR}/logs ${TL_VAR_DIR}/upload_area ${TL_FILES_DIR} \
	&& chown -R ${TL_USER} ${TL_VAR_DIR} ${TL_FILES_DIR} ${TL_ROOT}/gui/templates_c \
	# Create entrypoint symlink
	&& chmod +x ${TL_OPT_DIR}/entrypoint.sh && ln -s ${TL_OPT_DIR}/entrypoint.sh /testlink-entrypoint.sh

CMD ["/bin/sh", "/testlink-entrypoint.sh"]
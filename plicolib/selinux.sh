semanage fcontext -a -t httpd_sys_content_t "/www/docs(/.*)?"
restorecon -R -v /www/docs/

setsebool -PV allow_httpd_anon_write true
semanage fcontext -a -t public_content_rw_t "/usr/local/share/plicolib/cache(/.*)?"
restorecon -R -v /usr/local/share/plicolib/cache/
chmod a+w /usr/local/share/plicolib/cache/smarty -R

start() {
        initlog -c "echo -n Starting Sysclass Daemon (sysclassd-{environment}): "
        /usr/bin/php {base_path}/{environment}/current/listener.php &
        ### Create the lock file ###
        touch /var/lock/subsys/sysclassd-{environment}
        success $"MAT server startup"
        echo
}
# Restart the service MAT
stop() {
        initlog -c "echo -n Starting Sysclass Daemon (sysclassd-{environment}): "
        killproc sysclassd-{environment}
        ### Now, delete the lock file ###
        rm -f /var/lock/subsys/sysclassd-{environment}
        echo
}
### main logic ###
case "$1" in
  start)
        start
        ;;
  stop)
        stop
        ;;
  status)
        status sysclassd-{environment}
        ;;
  restart|reload|condrestart)
        stop
        start
        ;;
  *)
        echo $"Usage: $0 {start|stop|restart|reload|status}"
        exit 1
esac
exit 0
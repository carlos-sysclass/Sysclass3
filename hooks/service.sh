#!/bin/sh
#
# openfire  Stops and starts the Openfire XMPP service.
#
# chkconfig: 2345 99 1
# description: Openfire is an XMPP server, which is a server that facilitates \
#              XML based communication, such as chat.
# config: /opt/openfire/conf/openfire.xml
# config: /etc/sysconfig/openfire
# pidfile: /var/run/openfire.pid
# 
# This script has currently been tested on Redhat, CentOS, and Fedora  based
# systems.
#

#####
# Begin setup work
#####

# Initialization
PATH="/sbin:/bin:/usr/bin:/usr/sbin"
RETVAL=0

# Check that we are root ... so non-root users stop here.
if [ "`id -u`" != 0 ]; then
    echo $0 must be run as root
    exit 1
fi

# Get config.
#[ -f "/etc/sysconfig/openfire" ] && . /etc/sysconfig/openfire
if [ -f "/etc/init.d/functions" ]; then
  FUNCTIONS_FOUND=true
  . /etc/init.d/functions
fi

# If openfire user is not set in sysconfig, set to daemon.
[ -z "$SYSCLASS_USER" ] && SYSCLASS_USER="bamboo"

# If pid file path is not set in sysconfig, set to /var/run/openfire.pid.
[ -z "$SYSCLASS_PIDFILE" ] && SYSCLASS_PIDFILE="/var/run/sysclassd-{environment}.pid"

# -----------------------------------------------------------------

# If a openfire home variable has not been specified, try to determine it.

#if [ -z "$OPENFIRE_HOME" -o ! -d "$OPENFIRE_HOME" ]; then
#    if [ -d "/usr/share/openfire" ]; then
#        OPENFIRE_HOME="/usr/share/openfire"
#    elif [ -d "/usr/local/openfire" ]; then
#        OPENFIRE_HOME="/usr/local/openfire"
#    elif [ -d "/opt/openfire" ]; then
#        OPENFIRE_HOME="/opt/openfire"
#    else
#        echo "Could not find Openfire installation under /opt, /usr/share, or /usr/local."
#        echo "Please specify the Openfire installation location as variable OPENFIRE_HOME"
#        echo "in /etc/sysconfig/openfire."
#        exit 1
#    fi
#fi

SYSCLASS_HOME=$(readlink -f {base_path}/../current)

# If log path is not set in sysconfig, set to $OPENFIRE_HOME/logs.
[ -z "$SYSCLASS_LOGDIR" ] && SYSCLASS_LOGDIR="${SYSCLASS_HOME}/logs"

# Attempt to locate java installation.
#if [ -z "$JAVA_HOME" ]; then
#    if [ -d "${OPENFIRE_HOME}/jre" ]; then
#        JAVA_HOME="${OPENFIRE_HOME}/jre"
#    elif [ -d "/etc/alternatives/jre" ]; then
#        JAVA_HOME="/etc/alternatives/jre"
#    else
#        jdks=`ls -r1d /usr/java/j*`
#        for jdk in $jdks; do
#            if [ -f "${jdk}/bin/java" ]; then
#                JAVA_HOME="$jdk"
#                break
#            fi
#        done
#    fi
#fi
#JAVACMD="${JAVA_HOME}/bin/java"

#if [ ! -d "$JAVA_HOME" -o ! -x "$JAVACMD" ]; then
#    echo "Error: JAVA_HOME is not defined correctly."
#    echo "       Can not sure execute $JAVACMD."
#    exit 1
#fi

# Prepare location of openfire libraries
#OPENFIRE_LIB="${OPENFIRE_HOME}/lib"

# Prepare openfire command line
#OPENFIRE_OPTS="${OPENFIRE_OPTS} -DopenfireHome=${OPENFIRE_HOME} -Dopenfire.lib.dir=${OPENFIRE_LIB}"

# Prepare local java class path
#if [ -z "$LOCALCLASSPATH" ]; then
#    LOCALCLASSPATH="${OPENFIRE_LIB}/startup.jar"
#else
#    LOCALCLASSPATH="${OPENFIRE_LIB}/startup.jar:${LOCALCLASSPATH}"
#fi

# Export any necessary variables
#export JAVA_HOME JAVACMD

# Lastly, prepare the full command that we are going to run.
#OPENFIRE_RUN_CMD="${JAVACMD} -server ${OPENFIRE_OPTS} -classpath \"${LOCALCLASSPATH}\" -jar \"${OPENFIRE_LIB}/startup.jar\""

PHP_CMD="/usr/bin/php";
SYSCLASS_RUN_CMD="${PHP_CMD} ${SYSCLASS_HOME}/listener.php"

#####
# End setup work
#####



start() {
    OLD_PWD=`pwd`
    cd $SYSCLASS_LOGDIR

    PID=$(findPID)
    if [ -n "$PID" ]; then                                                
        echo "sysclassd-{environment} is already running."                                 
        RETVAL=1                                                           
        return                                                             
    fi                                                                     

    # Start daemons.                                                       
    echo -n "Starting sysclassd-{environment}: "                                          

    rm -f daemon.out
    su -s /bin/sh -c "nohup $SYSCLASS_RUN_CMD > $SYSCLASS_LOGDIR/daemon.out 2>&1 &" $SYSCLASS_USER
    RETVAL=$?

    [ $RETVAL -eq 0 -a -d /var/lock/subsys ] && touch /var/lock/subsys/sysclassd-{environment}

    sleep 1 # allows prompt to return

    PID=$(findPID)
    echo $PID > $SYSCLASS_PIDFILE

    cd $OLD_PWD

    if [ ! -z $PID ]; then
        success
    else
        failure
    fi
    echo
}

stop() {
    # Stop daemons.
    echo -n "Shutting down sysclassd-{environment}: "

    if [ -f "$SYSCLASS_PIDFILE" ]; then
        killproc -p $SYSCLASS_PIDFILE -d 10
        rm -f $SYSCLASS_PIDFILE
    else
        PID=$(findPID)
        if [ ! -z $PID ]; then
            kill $PID
        else
            echo "sysclassd-{environment} is not running."
        fi
    fi
    
    RETVAL=$?
    echo

    [ $RETVAL -eq 0 -a -f "/var/lock/subsys/sysclassd-{environment}" ] && rm -f /var/lock/subsys/sysclassd-{environment}
}

restart() {
    stop
    sleep 3 # give it a few moments to shut down
    start
}

condrestart() {
    [ -e "/var/lock/subsys/sysclassd-{environment}" ] && restart
    return 0
}

status() {
    PID=$(findPID)
    if [ -n "$PID" ]; then
        echo "sysclassd-{environment} is running"
        RETVAL=0
    else 
        echo "sysclassd-{environment} is not running"
        RETVAL=1
    fi
}

findPID() {
    echo `ps ax --width=1000 | grep {environment} | grep listener.php | awk '{print $1}'`
}

# Handle how we were called.
case "$1" in
    start)
        start
        ;;
    stop)
        stop
        ;;
    restart)
        restart
        ;;
    condrestart)
        condrestart
        ;;
    status) 
        status
        ;;
    *)
        echo "Usage $0 {start|stop|restart|status|condrestart}"
        RETVAL=1
esac

exit $RETVAL
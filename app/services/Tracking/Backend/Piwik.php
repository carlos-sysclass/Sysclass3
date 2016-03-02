<?php
namespace Sysclass\Services\Tracking\Backend;

use Phalcon\Mvc\User\Component,
    Sysclass\Services\Tracking\Interfaces\ITracking;

class Piwik extends Component implements ITracking {

    protected $trackerUrl;
    protected $siteId;


    public function initialize() {
        /**
         * @todo Get this info from a model or module
         * @var [type]
         */
        $this->trackerUrl = $this->environment->tracking_piwik->tracker_url;
        $this->siteId = $this->environment->tracking_piwik->site_id;
    }

    public function generateTrackingTag() {
        if (!empty($this->trackerUrl) && !empty($this->siteId)) {
            $script = sprintf('<script type="text/javascript">
                  var _paq = _paq || [];
                  _paq.push([\'trackPageView\']);
                  _paq.push([\'enableLinkTracking\']);
                  (function() {
                    var u="%1$s/";
                    _paq.push([\'setTrackerUrl\', u+\'piwik.php\']);
                    _paq.push([\'setSiteId\', %2$s]);
                    var d=document, g=d.createElement(\'script\'), s=d.getElementsByTagName(\'script\')[0];
                    g.type=\'text/javascript\'; g.async=true; g.defer=true; g.src=u+\'piwik.js\'; s.parentNode.insertBefore(g,s);
                  })();
                </script>
                <noscript><p><img src="%1$s/piwik.php?idsite=%2$s" style="border:0;" alt="" /></p></noscript>
            ', $this->trackerUrl, $this->siteId);

            return $script;
        }
        return "";
    }
}

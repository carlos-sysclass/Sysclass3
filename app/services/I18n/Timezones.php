<?php
namespace Sysclass\Services\I18n;

use Phalcon\Mvc\User\Component;

class Timezones extends Component
{
    public function initialize()
    {
        //$this->setSource("timezones");
    }

    public function findAll() {

        $timezones = \DateTimeZone::listAbbreviations();

        $cities = array();
        foreach( $timezones as $key => $zones )
        {
            foreach( $zones as $id => $zone )
            {
                /**
                 * Only get timezones explicitely not part of "Others".
                 * @see http://www.php.net/manual/en/timezones.others.php
                 */
                if ( preg_match( '/^(America|Antartica|Arctic|Asia|Atlantic|Europe|Indian|Pacific)\//', $zone['timezone_id'] ) ) {
                    $negative = $zone['offset'] < 0;
                    $interval = new \Plico\Php\DateInterval('PT' . abs($zone['offset']) . 'S');

                    $zone['name'] = sprintf("%s  (%s)",
                        $zone['timezone_id'],
                        $negative ? $interval->recalculate()->format("-%H:%I") : $interval->format("+%H:%I")
                    );

                    $cities[$zone['timezone_id']] = $zone;
                    break;
                }
            }
        }
        // For each city, have a comma separated list of all possible timezones for that city.

        foreach( $cities as $key => $value ) {
            $index[$key] = $value['offset'];
        }

        array_multisort($index, SORT_ASC, $cities);

        return $cities;
    }
}

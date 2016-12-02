<?php
namespace Sysclass\Models\Organizations;

use Plico\Mvc\Model;

class Organization extends Model
{
    public function initialize()
    {
        $this->setSource("mod_organization");


        $this->belongsTo("logo_id", "Sysclass\\Models\\Dropbox\\File", "id",  array('alias' => 'logo'));

        $this->hasMany("id", "Sysclass\\Models\\Organizations\\OrganizationL10n", "id",  array('alias' => 'details'));
    }

    public function afterFetch()
    {

        $translate = $this->getDI()->get("translate");

        $details = $this->getDetails([
            'conditions' => "language_code = ?0",
            'bind' => [$translate->getSource()]
        ]);

        if (!$details) {
            $details = $this->getDetails([
                'conditions' => "language_code = 'en'"
            ]);
        }

        /*

		$this->postal_code = $details->postal_code;
		$this->street = $details->street;
		$this->street_number = $details->street_number;
		$this->street2 = $details->street2;
		$this->district = $details->district;
		$this->city = $details->city;
		$this->state = $details->state;
		$this->country = $details->country;
		$this->phone = $details->phone;
		$this->website = $details->website;
		$this->timezone = $details->timezone;
		$this->facebook = $details->facebook;
		$this->linkedin = $details->linkedin;
		$this->skype = $details->skype;
		$this->googleplus = $details->googleplus;

        */
    }
}

<?php
namespace Sysclass\Models\Organizations;

use Plico\Mvc\Model;

class Organization extends Model
{
    public $details = [];
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

        if ($details->count() > 0) {
            $detailsRec = $details->getFirst();
        } else {
            $detailsRec = new OrganizationL10n();
            $detailsRec->id = $this->id;
            $detailsRec->language_code = $translate->getSource();
        }

        $detailsRec->postal_code = is_null($detailsRec->postal_code) ? $this->postal_code : $detailsRec->postal_code;
        $detailsRec->street = is_null($detailsRec->street) ? $this->street : $detailsRec->street;
        $detailsRec->street_number = is_null($detailsRec->street_number) ? $this->street_number : $detailsRec->street_number;
        $detailsRec->street2 = is_null($detailsRec->street2) ? $this->street2 : $detailsRec->street2;
        $detailsRec->district = is_null($detailsRec->district) ? $this->district : $detailsRec->district;
        $detailsRec->city = is_null($detailsRec->city) ? $this->city : $detailsRec->city;
        $detailsRec->state = is_null($detailsRec->state) ? $this->state : $detailsRec->state;
        $detailsRec->country = is_null($detailsRec->country) ? $this->country : $detailsRec->country;
        $detailsRec->phone = is_null($detailsRec->phone) ? $this->phone : $detailsRec->phone;
        $detailsRec->website = is_null($detailsRec->website) ? $this->website : $detailsRec->website;
        $detailsRec->facebook = is_null($detailsRec->facebook) ? $this->facebook : $detailsRec->facebook;
        $detailsRec->linkedin = is_null($detailsRec->linkedin) ? $this->linkedin : $detailsRec->linkedin;
        $detailsRec->skype = is_null($detailsRec->skype) ? $this->skype : $detailsRec->skype;
        $detailsRec->googleplus = is_null($detailsRec->googleplus) ? $this->googleplus : $detailsRec->googleplus;

        $detailsRec->timezone = is_null($detailsRec->timezone) ? $this->timezone : $detailsRec->timezone;

        $this->details = $detailsRec->toArray();
       
        /*

		$this->timezone = $details->timezone;

        */
    }

    public function toArray() {
        $item = parent::toArray();
        $item['details'] = $this->details;

        return $item;
    }
}

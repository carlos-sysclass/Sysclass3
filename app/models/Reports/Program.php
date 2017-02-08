<?php
namespace Sysclass\Models\Reports;

use Plico\Mvc\Model;

class Program extends Model
{
    public function initialize()
    {
        $this->setSource("mod_reports_programs");

        $this->belongsTo(
            "language_id",
            "Sysclass\Models\I18n\Language",
            "id",
            array(
                'alias' => 'Language',
            )
        );

    }
}

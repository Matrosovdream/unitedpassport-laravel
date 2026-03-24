<?php

namespace App\Repositories\Easypost;

use App\Models\EasypostShipmentLabel;
use App\Repositories\AbstractRepo;

class EasypostShipmentLabelRepo extends AbstractRepo
{
    public function __construct()
    {
        $this->model = new EasypostShipmentLabel();
    }

    public function mapItem($item)
    {
        if (empty($item)) {
            return null;
        }

        return [
            'id' => $item->id,
            'easypost_id' => $item->easypost_id,
            'label_date' => $item->label_date,
            'label_type' => $item->label_type,
            'label_url' => $item->label_url,
            'label_pdf_url' => $item->label_pdf_url,
            'Model' => $item,
        ];
    }
}

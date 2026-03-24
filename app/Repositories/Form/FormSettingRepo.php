<?php

namespace App\Repositories\Form;

use App\Models\FormSetting;
use App\Repositories\AbstractRepo;

class FormSettingRepo extends AbstractRepo
{
    public function __construct()
    {
        $this->model = new FormSetting();
    }

    public function getByFormId($formId)
    {
        $items = $this->model
            ->where('form_id', $formId)
            ->get();

        return $this->mapItems($items);
    }

    public function mapItem($item)
    {
        if (empty($item)) {
            return null;
        }

        return [
            'id' => $item->id,
            'form_id' => $item->form_id,
            'setting_code' => $item->setting_code,
            'setting_id' => $item->setting_id,
            'Model' => $item,
        ];
    }
}

<?php

namespace App\Repositories\Easypost;

use App\Models\EasypostShipment;
use App\Repositories\AbstractRepo;

class EasypostShipmentRepo extends AbstractRepo
{
    protected $withRelations = ['addresses', 'label', 'parcel', 'rates'];

    public function __construct()
    {
        $this->model = new EasypostShipment();
    }

    public function search(array $filter = [], int $perPage = 20, array $sorting = [])
    {
        $query = $this->model->with($this->withRelations);

        if (!empty($filter['search'])) {
            $term = '%' . $filter['search'] . '%';
            $query->where(function ($q) use ($term) {
                $q->where('tracking_code', 'LIKE', $term)
                  ->orWhere('easypost_id', 'LIKE', $term);
            });
        }

        if (!empty($filter['entry_id'])) {
            $query->where('entry_id', $filter['entry_id']);
        }

        if (isset($filter['status']) && $filter['status'] !== '') {
            $query->where('status', $filter['status']);
        }

        if (isset($filter['mode']) && $filter['mode'] !== '') {
            $query->where('mode', $filter['mode']);
        }

        if (isset($filter['is_return']) && $filter['is_return'] !== '') {
            $query->where('is_return', (bool) $filter['is_return']);
        }

        if (!empty($filter['date_from'])) {
            $query->whereDate('created_at', '>=', $filter['date_from']);
        }

        if (!empty($filter['date_to'])) {
            $query->whereDate('created_at', '<=', $filter['date_to']);
        }

        if (!empty($sorting)) {
            foreach ($sorting as $column => $direction) {
                $query->orderBy($column, $direction);
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        $items = $query->paginate($perPage);

        return $this->mapItems($items);
    }

    public function getByEasypostId($easypostId)
    {
        $item = $this->model
            ->where('easypost_id', $easypostId)
            ->with($this->withRelations)
            ->first();

        return $this->mapItem($item);
    }

    public function getByEntryId($entryId)
    {
        $items = $this->model
            ->where('entry_id', $entryId)
            ->with($this->withRelations)
            ->orderBy('id', 'desc')
            ->get();

        return $this->mapItems($items);
    }

    public function mapItem($item)
    {
        if (empty($item)) {
            return null;
        }

        $label = $item->label;
        $rates = $item->rates;
        $selectedRate = $rates ? $rates->first() : null;

        return [
            'id'             => $item->id,
            'easypost_id'    => $item->easypost_id,
            'entry_id'       => $item->entry_id,
            'is_return'      => $item->is_return,
            'status'         => $item->status,
            'tracking_code'  => $item->tracking_code,
            'tracking_url'   => $item->tracking_url,
            'refund_status'  => $item->refund_status,
            'mode'           => $item->mode,
            'created_at'     => $item->created_at,
            'updated_at'     => $item->updated_at,
            'label' => $label ? [
                'easypost_id'    => $label->easypost_id,
                'label_url'      => $label->label_url,
                'label_pdf_url'  => $label->label_pdf_url,
                'label_size'     => $label->label_size,
                'label_type'     => $label->label_type,
                'label_file_type'=> $label->label_file_type,
                'label_date'     => $label->label_date,
            ] : null,
            'selected_rate' => $selectedRate ? [
                'carrier'  => $selectedRate->carrier,
                'service'  => $selectedRate->service,
                'rate'     => $selectedRate->rate,
                'currency' => $selectedRate->currency,
                'delivery_days' => $selectedRate->delivery_days,
            ] : null,
            'rates_count' => $rates ? $rates->count() : 0,
            'Model' => $item,
        ];
    }
}

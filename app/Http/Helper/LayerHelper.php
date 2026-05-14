<?php

namespace App\Http\Helper;

use App\Models\CLT_Layup;

class LayerHelper
{
    public function map(CLT_Layup $layup)
    {
        $layersData = $layup->layers->map(function ($layer) {
            return [
                'id'          => $layer->id,
                'layer_order' => $layer->layer_order,
                'thickness'   => (float) $layer->thickness,
                'width'       => (float) $layer->width,
                'angle'       => (int) $layer->angle,
                'grade'       => $layer->grade ?? 'C24',
            ];
        });

        return $layersData;
    }
}
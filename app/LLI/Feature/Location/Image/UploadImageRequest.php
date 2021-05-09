<?php

namespace LocalheroPortal\LLI\Feature\Location\Image;
use Illuminate\Foundation\Http\FormRequest;

class UploadImageRequest extends FormRequest
{
    public function rules()
    {
        return [
            'image'                => 'required|image:jpeg|min:10|max:5120|dimensions:min_width=720,min_height=720'
        ];
    }

    public function messages()
    {
        return [
            'image.required' => "Ein Bild ist erforderlich.",
            'image.image' => "Das Bild sollte im JPEG-Format vorliegen",
            'image.max' => "Die Bildgröße sollte maximal 5 MB betragen",
            'image.min' => "Die Bildgröße sollte mindestens 10 KB betragen",
            'image.dimensions' => "Die Abmessungen des Bildes sollten mindestens 720 x 720 betragen"
        ];
    }
}

<?php

namespace LocalheroPortal\Core\Services;

use Spatie\MediaLibrary\Support\UrlGenerator\DefaultUrlGenerator;

class UrlGenerator extends DefaultUrlGenerator
{

    public function getUrl(): string
    {
        return '/storage/media/' . $this->getPathRelativeToRoot();
    }
}

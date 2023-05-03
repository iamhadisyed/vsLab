<?php

namespace Spatie\MediaLibrary\PathGenerator;

use Spatie\MediaLibrary\Media;

class BasePathGenerator implements PathGenerator
{
    /*
     * Get the path for the given media, relative to the root storage path.
     */
    public function getPath(Media $media): string
    {
        return $this->getBasePath($media).'/';
    }

    /*
     * Get the path for conversions of the given media, relative to the root storage path.
     */
    public function getPathForConversions(Media $media): string
    {
        return $this->getBasePath($media).'/conversions/';
    }

    /*
     * Get a (unique) base path for the given media.
     */
    protected function getBasePath(Media $media): string
    {
        // Add collection_name on the path by James @ 10/12/2018
        return $media->getAttributeValue('collection_name') . '/' . $media->getKey();
    }
}

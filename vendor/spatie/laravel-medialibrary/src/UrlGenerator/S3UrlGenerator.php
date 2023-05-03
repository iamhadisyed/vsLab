<?php

namespace Spatie\MediaLibrary\UrlGenerator;

class S3UrlGenerator extends BaseUrlGenerator
{
    /**
     * Get the url for the profile of a media item.
     *
     * @return string
     */
    public function getUrl(): string
    {
        $bucket = $this->media->getAttributeValue('collection_name');
        if ($bucket == 'avatars') {
            return config('medialibrary.avatars.domain').'/'.$this->getPathRelativeToRoot();
        } elseif ($bucket == 'submissions') {
            return config('medialibrary.lab-submissions.domain').'/'.$this->getPathRelativeToRoot();
        }
    }

    /**
     * Get the url for the profile of a media item.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->getPathRelativeToRoot();
    }
}

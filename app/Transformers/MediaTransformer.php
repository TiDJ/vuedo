<?php

namespace App\Transformers;

use App\Media;
use Hashids;
use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class MediaTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var  array
     */
    protected $availableIncludes = ['owner', 'categories'];

    /**
     * List of resources to automatically include
     *
     * @var  array
     */
    protected $defaultIncludes = [];

    /**
     * Transform object into a generic array
     *
     * @var  object
     * @return array
     */
    public function transform(Media $media)
    {
        return [
            'hashid' => Hashids::encode($media->id),
            'model_id' => $media->model_id,
            'file_name' => $media->slug,
            'url' => $media->getUrl(),
            'title' => $media->title,
            'alt' => $media->alt,
            'description' => $media->description,
            'disk' => $media->disk,
            'size' => $media->size,
            'collection_name' => $media->collection_name
        ];
    }

    public function includeOwner(Post $post){
        return $this->item($post->owner, new UserTransformer);
    }
}

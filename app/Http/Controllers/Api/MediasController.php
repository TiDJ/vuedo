<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Post;
use App\Media;
use App\Transformers\MediaTransformer;
use App\User;
use Illuminate\Http\Request;
use Hashids;

use App\Http\Requests;

class MediasController extends ApiController
{
    public function __construct()
    {
        $this->middleware('authorized:manage-post,posts', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->respondWith(
            Media::paginate(10)->appends([ 'include' => $request->input('include')]),
            new MediaTransformer
        );
    }


    public function unique($hashMedia)
    {
        $id_Media = Hashids::decode($hashMedia);
        $media = Media::where('id', $id_Media)->first();
        return $this->respondWith($media, new MediaTransformer);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $media = new Media;
        $media->title = hash('adler32', time());
        $media->save();

        return $this->respondWith($media, new MediaTransformer);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Media $media
     *
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function show(Media $media)
    {
        return $this->respondWith($media, new MediaTransformer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param \App\Media $media
     *
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function update(Request $request, Media $media)
    {
        $id_Media = Hashids::decode($request->hashid);
        $media = Media::where('id', $id_Media)->first();
        $media->fill($request->all());
        //escape any html tags before saving
        //$media->content = strip_tags($media->content);
        $media->save();
        dd($request->all());
        return $this->respondWith($media, new MediaTransformer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Media $media
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     *
     */
    public function destroy(Request $request, Media $media)
    {
        $id_Media = Hashids::decode($request->hashid);
        $media = Media::where('id', $id_Media)->first();
        $media->delete();

        return $this->respondWithArray([
            'success' => true,
            'message' => 'Successfully deleted media.'
        ]);
    }
}

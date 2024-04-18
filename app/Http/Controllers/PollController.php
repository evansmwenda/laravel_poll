<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePollRequest;
use App\Http\Requests\UpdatePollRequest;
use App\Http\Resources\PollResource;
use App\Models\Poll;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class PollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        return PollResource::collection(Poll::where('user_id',$user->id)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePollRequest $request)
    {
        $data = $request->validated();

        if(isset($data['image'])){
            //save the image
            $imagePath = $this->saveImage($data['image']);
            $data['image'] = $imagePath;
        }

        $result = Poll::create($data);
        return new PollResource($result);
    }

    public function saveImage($image){
        //check if image is valid base64 string
        if(preg_match('/^data:image\/(\w+);base64,/',$image,$type)){
            //matches
            $image = substr($image, strpos($image,',') + 1);
            $type = strtolower($type[1]);//jpg ,png,gif
            if(!in_array($type,['jpg','png','gif','jpeg'])){
                throw new Exception("Did not match image extension");
            }

            $image = str_replace(' ','+',$image);
            $image = base64_decode($image);

            if($image == false){
                throw new \Exception("base64_decode failed");
            }

            $dir = 'images/';
            $file = Str::random().'.'.$type;
            $absolutePath = public_path($dir);
            $relativePath = $dir.$file;
            if (!File::exists($absolutePath)){
                File::makeDirectory($absolutePath,0755,true);
            }

            file_put_contents($relativePath,$image);

            return $relativePath;
        }else{
            throw new Exception("Did not match data URI with image data");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Poll $poll,Request $request)
    {
        $user = $request->user();
        if($user->id !== $poll->user_id){
            //not the owner of the poll
            abort(403, 'Unauthorized action');
        }

        return new PollResource($poll);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePollRequest $request, Poll $poll)
    {
        $data = $request->validated();

        if(isset($data['image'])){
            //save the image
            $imagePath = $this->saveImage($data['image']);
            $data['image'] = $imagePath;

            //delete old image
            if($poll->image){
                $absolutePath = public_path($poll->image);
                File::delete($absolutePath);
            }
        }

        $poll->update($data);
        return new PollResource($poll);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Poll $poll,Request $request)
    {
        $user = $request->user();
        if($user->id !== $poll->user_id){
            //not the owner of the poll
            abort(403, 'Unauthorized action');
        }

        $poll->delete();
        return response('',204);
    }
}

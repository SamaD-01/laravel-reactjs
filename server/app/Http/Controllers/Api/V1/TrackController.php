<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Track;
use App\Http\Requests\StoreTrackRequest;
use App\Http\Resources\V1\TrackResource;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class TrackController extends Controller
{
    public function index() {

        try {
            return TrackResource::collection(Track::all());
        } catch (\Exception $err) {
            return response()->json([
                'message' => 'Something went wrong!'
            ], 500);
        }

        
    }

    public function show(Track $track) {
        try {
            $coverUrl = Storage::disk('public')->url($track->img);
            return new TrackResource($track->merge(['cover_url' => $coverUrl]));
        } catch (\Exception $err) {
            return response()->json([
                'message' => 'Somthing went wrong!'
            ], 500);
        }
    }

    public function store(StoreTrackRequest $request) {

        // try {
        //     $imgName = $Str::random(32).'.'.$request->img->getClientOriginalExtension();

        //     $request->merge(['img' => $imgName]);

        //     Track::create($request -> validated());

        //     Strorage::disk('public')->put($imageName, file_get_contents($request->image));

        //     return response()->json([
        //         'message' => 'Track created!'
        //     ], 200);

        // } catch (\Exception $err) {
        //     return response()->json([
        //         'message' => 'Somthing went wrong!'
        //     ], 500);
        // }

        try {
            $imgName = Str::random(32) . '.' . $request->file('img')->extension();
            
            $request->merge(['img' => $imgName]);
            
            $path = $request->file('img')->store('covers'); 
            
            Track::create($request->validated());
            
            Storage::disk('public')->put($imgName, file_get_contents($request->file('img'))); 
            
            return response()->json([
                'message' => 'Track created!'
            ], 200);
        } catch (\Exception $err) {
            return response()->json([
                'message' => 'Somthing went wrong!'
            ], 500);
        }
        // Track::create($request -> validated());
        // return response()->json("Track created");
    }

    public function update(StoreTrackRequest $request, Track $track) {
        // $track -> update($request -> validated());
        // return response()->json("Track updated");

        try {
            $reqData = $request->validated();
            
            if ($request->hasFile('img')) {
                $imgName = Str::random(32) . '.' . $request->file('img')->extension();
                $reqData['img'] = $imgName;
                $path = $request->file('img')->store('covers');
                Storage::disk('public')->put($imgName, file_get_contents($request->file('img')));
            }
            
            $track->update($reqData);
            
            return response()->json("Track updated");
        } catch (\Exception $err) {
            return response()->json([
                'message' => 'Something went wrong!'
            ], 500);
        }
    }

    public function destroy(Track $track) {
        try {
            if (!empty($track->img)) {
                Storage::disk('public')->delete($track->img);
            }
            
            $track->delete();
            
            return response()->json("Track deleted");
        } catch (\Exception $err) {
            return response()->json([
                'message' => 'Something went wrong!'
            ], 500);
        }
    }
}

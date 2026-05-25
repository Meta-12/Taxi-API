<?php

namespace App\Http\Controllers;

use App\Models\Direction;
use Illuminate\Http\Request;

class DirectionController extends Controller
{
    // add direction
    public function addDirection(Request $request)
    {
        $input = $request->validate([
            'direction' => 'required|string',
            'price' => 'required|numeric',
        ]);
        $images = [];
        if ($request->hasFile('image1')) {
            $path = $request->file('image1')->getClientOriginalName();
            $request->file('image1')->move('upload/', $path);
            $images[] = url('upload/' . $path);
        }
        if ($request->hasFile('image2')) {
            $path = $request->file('image2')->getClientOriginalName();
            $request->file('image2')->move('upload/', $path);
            $images[] = url('upload/' . $path);
        }
        $input['images'] = json_encode($images);
        $insert = Direction::create($input);
        if ($insert) {
            return response()->json([
                'status' => 200,
                'message' => 'Add direction success',
                'data' => $input,
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Fail to add direction',
            ]);
        }
    }

    // get direction
    public function getDirection()
    {
        $direction = Direction::all();
        return response()->json([
            'status' => 200,
            'message' => 'Get direction success',
            'data' => $direction,
        ]);
    }

    // edit direction
    public function editDirection(Request $request, $id)
    {
        // ✅ validate
        $input = $request->validate([
            'direction' => 'required|string',
            'price' => 'required|numeric',
        ]);

        // ✅ find existing record
        $direction = Direction::find($id);
        if (!$direction) {
            return response()->json([
                'status' => 404,
                'message' => 'Direction not found',
            ]);
        }

        // ✅ decode existing images (if any)
        $oldImages = $direction->images ? json_decode($direction->images, true) : [];

        $newImages = $oldImages; // start with old images

        // ✅ replace or keep each image
        if ($request->hasFile('image1')) {
            $path1 = $request->file('image1')->getClientOriginalName();
            $request->file('image1')->move('upload/', $path1);
            $newImages[0] = url('upload/' . $path1); // replace image1
        }

        if ($request->hasFile('image2')) {
            $path2 = $request->file('image2')->getClientOriginalName();
            $request->file('image2')->move('upload/', $path2);
            $newImages[1] = url('upload/' . $path2); // replace image2
        }

        // ✅ save new images
        $input['images'] = json_encode($newImages);

        // ✅ update
        $update = $direction->update($input);

        if ($update) {
            return response()->json([
                'status' => 200,
                'message' => 'Update direction success',
                'data' => $direction,
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Fail to update direction',
            ]);
        }
    }



    // delete direction
    public function deleteDirection($id)
    {
        $delete = Direction::query()->where('id', $id)->delete();
        if ($delete) {
            return response()->json([
                'status' => 200,
                'message' => 'delete direction success',
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Fail to delete direction',
            ]);
        }
    }


    // view direction front-end
    public function ViewDirection()
    {
        $direction = Direction::all();
        return response()->json([
            'status' => 200,
            'message' => 'Get direction success',
            'data' => $direction,
        ]);
    }
}

<?php

namespace App\Http\Controllers;


use App\Models\Image;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class MainController extends Controller
{

    public function index()
    {
//        $users = User::query()->orderByDesc('created_at')->paginate(10);
        $users = User::all();

        return view('users', compact('users'));
    }

    public function form()
    {

        return view('admin.main.form');
    }


    public function submit(Request $request)
    {

        $request->validate([
            'url' =>'required'
        ]);

        $url = $request->post('url');

        $test = Artisan::call('amazon:fetch', [
            'url' => $url
        ]);

        if (File::exists('storage/products.json')) {
            $json = File::get('storage/products.json');
            $json = json_decode($json);

            $data = [
                'title' => $json->title,
                'price' => $json->price,
                'description' => $json->description,
                'notes' => $json->note,
                'url' => $url,
            ];
            $product = Product::create($data);
            if (!empty($json->images)) {
                foreach ($json->images as $image) {
                    $imageModel = new Image();
                    $imageModel->product_id = $product->id;
                    $imageModel->url = $image;
                    $imageModel->save();
                    Storage::disk('local')->put('public/images/' . $product->id . '/' . basename($image), file_get_contents($image));
                }
            }


            return redirect()->route('amazon.view', $product->slug);


        }

        return true;

    }

    public function view(Request $request, $slug)
    {
        $product = Product::with('images')->where('slug', $slug)->first();
        return view('admin.main.view', ['product' => $product]);
    }


    public function deleteAll()
    {
        Product::query()->delete();
        File::deleteDirectory(storage_path('app/public/images/'));
        return back();
    }


    public function delete(Request $request, $id)
    {

        File::deleteDirectory(storage_path('app/public/images/'.$id));
        Product::where('id', $id)->delete();
        return back();
    }


}

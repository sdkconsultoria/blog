<?php
namespace Sdkconsultoria\Blog\Controllers;

use Illuminate\Http\Request;
use Sdkconsultoria\Base\Controllers\ResourceController;
use Sdkconsultoria\Blog\Models\{Blog, BlogImage};
use Sdkconsultoria\Base\Helpers\Images;

class BlogPostController extends ResourceController
{
    protected $model       = 'Sdkconsultoria\Blog\Models\BlogPost';
    protected $view        = 'blog::blog-post';
    protected $resource    = 'blog-post';
    protected $createEmpty = false;

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($blog = '')
    {
        if ($this->createEmpty) {
            $model = $this->createOrFind();
        }else{
            $model = new $this->model();
        }

        $blog = Blog::where('seoname', $blog)->orWhere('id', $blog)->orWhere('identifier', $blog)->first();

        if ($blog) {
            $model->blogs_id = $blog->id;
        }
        return view($this->view . '.create', compact('model'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function blog($blog = '')
    {
        dd('hola');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request);

        $model = new $this->model();
        $model->status = $this->model::STATUS_ACTIVE;
        $model->created_by = \Auth::user()->id;
        $this->loadData($model, $request);
        $model->save();

        foreach ($model->blog->getKeys() as $key => $value) {
            $model->addKey($value, $request->input($value['name']));
        }

        return redirect()->route($this->resource . '.index')->with('success', __('base::messages.saved'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, 'rulesUpdate');

        $model = $this->findModel($id);
        $this->loadData($model, $request);

        $model->save();

        foreach ($model->blog->getKeys() as $key => $value) {
            $model->addKey($value, $request->input($value['name']));
        }

        if($request->hasfile('blog_posts_images'))
        {
            foreach($request->file('blog_posts_images') as $file)
            {
                $extension = $file->getClientOriginalExtension();
                $filename  = $file->getClientOriginalName();

                $image = new BlogImage();
                $image->created_by    = \Auth::user()->id;
                $image->extension     =  $file->extension();
                $image->blog_posts_id =  $model->id;
                $image->save();
                $file->storeAs('blogs/' . $model->id, $image->id . '.' . $file->extension(), 'public');
                $image->convertImage();
            }
        }

        return redirect()->route($this->resource . '.index')->with('success', __('base::messages.saved'));
    }

    public function removeImage($id, Request $request)
    {
        $model = $this->findModel($id, 'id');

        $image = BlogImage::find($request->input('id_image'));
        $image->removeImage(false);
        $image->delete();

        return response()->json([
        ]);
    }

    public function saveImage($id, Request $request)
    {
        $model = $this->findModel($id, 'id');

        $image = BlogImage::find($request->input('id_image'));
        $image->type = $request->input('type');
        $image->alt = $request->input('alt');
        $image->save();

        return response()->json([
        ]);
    }

    public function page($identifier, Request $request)
    {
        $method = $request->method();
        $model = $this->model::where('identifier', $identifier)->first();

        if (!$model) {
            $model             = new $this->model();
            $model->identifier = $identifier;
            $model->created_by = auth()->user()->id;
            $model->blogs_id   = 2;
            $model->images_types = $model->blog->images_types;
            $model->sizes = $model->blog->sizes;
            $model->save();
        }

        if ($request->isMethod('put')) {
            $this->loadData($model, $request);
            $model->save();

            if($request->hasfile('blog_posts_images'))
            {
                foreach($request->file('blog_posts_images') as $file)
                {
                    $extension = $file->getClientOriginalExtension();
                    $filename  = $file->getClientOriginalName();

                    $image = new BlogImage();
                    $image->created_by    = \Auth::user()->id;
                    $image->extension     =  $file->extension();
                    $image->blog_posts_id =  $model->id;
                    $image->save();

                    $file->storeAs('blogs/' . $model->id, $image->id . '.' . $file->extension(), 'public');

                    Images::convertImage('blogs/'.$model->id.'/', $image->id, $file->extension(), $model->blog->getSizes());
                }
            }
        }

        return view($this->view . '.page', [
            'model' => $model,
            'name' => $identifier
        ]);
    }

    public function saveImageSizes(Request $request)
    {
        $image = BlogImage::find($request->input('id'));
        $image->sizes = serialize(json_decode($request->input('sizes'), TRUE));
        $image->save();

        switch ($request->input('save_type')) {
            case 'only_this':
                $image->removeImage(false);
                $image->convertImage();
                break;
            case 'only_category':
                \Artisan::call('clear:images '.$image->id.' --type');
                \Artisan::call('convert:images '.$image->id.' --type');
                break;
            case 'only_blog':
                \Artisan::call('clear:images --blog=' . $image->blog_posts_id);
                \Artisan::call('convert:images --blog=' . $image->blog_posts_id);
                break;

            default:
                // code...
                break;
        }
    }
}

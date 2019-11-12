<?php
namespace Sdkconsultoria\Blog\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
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

        $blog = Blog::where('seoname', $blog)->orWhere('id', $blog)->first();

        if ($blog) {
            $model->blogs_id = $blog->id;
        }
        return view($this->view . '.create', compact('model'));
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
        $this->validate($request);

        $model = $this->findModel($id);
        $this->loadData($model, $request);

        $model->save();

        foreach ($model->blog->getKeys() as $key => $value) {
            $model->addKey($value, $request->input($value['name']));
        }

        if($request->hasfile('images'))
        {
            foreach($request->file('images') as $file)
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

        return redirect()->route($this->resource . '.index')->with('success', __('base::messages.saved'));
    }

    public function removeImage($id, Request $request)
    {
        $model = $this->findModel($id, 'id');

        $image = BlogImage::find($request->input('id_image'));
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
}
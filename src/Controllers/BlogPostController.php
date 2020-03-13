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
    protected $createEmpty = true;

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($blog = '')
    {
        $blog = Blog::where('seoname', $blog)->orWhere('id', $blog)->orWhere('identifier', $blog)->first();

        if ($blog) {
            $model = $this->createOrFind(['blog_id' => $blog->id]);
        }else{
            $model = new $this->model();
        }

        return view($this->view . '.create', compact('model'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function blogs(Request $request, $blog = '')
    {
        $blog = Blog::where('seoname', $blog)->first();
        if (!$blog) {
            abort(404, 'base::messages.not_found');
        }

        $model = new $this->model();

        if (!$request->input('status')) {
            $models = $this->model::where($model->getTable().'.status', '!=', $this->model::STATUS_DELETE)
            ->where($model->getTable().'.status', '!=',$this->model::STATUS_CREATE);
        }else{
            $models = $this->model::where($model->getTable().'.id', '>', '0');
        }

        $models = $models->where('blog_id', $blog->id);

        $models = $models->where($model->getTable().'.status', '!=' ,'0');

        $models   = Self::getOrder($models, $request->get('order'));
        if ($request->input('pagination')) {
            $this->filters['pagination'] = $request->input('pagination');
        }
        $models   = Self::setFilter($models, $request);
        $models   = $models->paginate($this->filters['pagination'])->appends($this->filters);

        return view($this->view . '.blogs', compact('models', 'model', 'blog'));
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
        $model->status = \Sdkconsultoria\Blog\Models\BlogPost::STATUS_ACTIVE;
        $model->created_by = \Auth::user()->id;
        $this->loadData($model, $request);
        $model->save();

        foreach ($model->blog->getKeys() as $key => $value) {
            $model->addKey($value, $request->input($value['name']));
        }

        $this->saveImages($model, $request);

        return redirect()->route($this->resource . '.blogs', $model->blog->seoname)->with('success', __('base::messages.saved'));
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

        $this->saveImages($model, $request);

        return redirect()->route($this->resource . '.index')->with('success', __('base::messages.saved'));
    }

    private function saveImages($model, $request)
    {
        if($request->hasfile('blog_posts_images'))
        {
            foreach($request->file('blog_posts_images') as $file)
            {
                $extension = $file->getClientOriginalExtension();
                $filename  = $file->getClientOriginalName();

                $image = new BlogImage();
                $image->created_by    = \Auth::user()->id;
                $image->extension     =  $file->extension();
                $image->blog_post_id =  $model->id;
                $image->save();
                $file->storeAs('blogs/' . $model->id, $image->id . '.' . $file->extension(), 'public');
                $image->convertImage();
            }
        }
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
            $model->status     = \Sdkconsultoria\Blog\Models\BlogPost::STATUS_ACTIVE;
            $model->identifier = $identifier;
            $model->created_by = auth()->user()->id;
            $model->blog_id   = 2;
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
                    $image->blog_post_id =  $model->id;
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
                \Artisan::call('clear:images --blog=' . $image->blog_post_id);
                \Artisan::call('convert:images --blog=' . $image->blog_post_id);
                break;

            default:
                // code...
                break;
        }
    }
}

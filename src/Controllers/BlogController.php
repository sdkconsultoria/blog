<?php
namespace Sdkconsultoria\Blog\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Sdkconsultoria\Base\Controllers\ResourceController;
use Sdkconsultoria\Blog\Models\{Blog, BlogPost};

class BlogController extends ResourceController
{
    protected $model    = 'Sdkconsultoria\Blog\Models\Blog';
    protected $view     = 'blog::blog';
    protected $resource = 'blog';

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = $this->createOrFind();
        $model->created_by = \Auth::user()->id;
        if (!$model->sizes) {
            $model->sizes = serialize(config('base.images'));
        }
        if (!$model->images_types) {
            $model->images_types = serialize(config('base.images_types'));
        }

        $model->save();
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

        $model = $this->createOrFind();
        $model->status = $this->model::STATUS_ACTIVE;
        $this->loadData($model, $request);
        $model->created_by = \Auth::user()->id;

        $model->save();

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

        return redirect()->route($this->resource . '.index')->with('success', __('base::messages.saved'));
    }

    public function addKey(String $id, Request $request)
    {
        $model = $this->findModel($id, 'id');

        $items = $model->getKeys();
        array_push($items, [
            'name' => $request->post('name'),
            'seoname' => $request->post('name'),
            'values' => $request->post('name'),
            'category' => $request->post('name'),
            'seocategory' => $request->post('name'),
        ]);

        $model->keys = serialize($items);
        $model->save();

        return response()->json([
        ]);
    }

    public function removeKey($id, Request $request)
    {
        $model = $this->findModel($id, 'id');

        $items = $model->getKeys();
        array_splice($items, $request->post('index'), 1);
        $model->keys = serialize($items);
        $model->save();

        return response()->json([
        ]);
    }


    public function addSize(String $id, Request $request)
    {
        $model = $this->findModel($id, 'id');

        $sizes = $model->getSizes();
        array_push($sizes, [
            'name' => $request->post('name'),
            'height' => $request->post('height'),
            'width' => $request->post('width'),
            'quality' => (int) $request->post('quality'),
        ]);

        $model->sizes = serialize($sizes);
        $model->save();

        return response()->json([
        ]);
    }

    public function removeSize($id, Request $request)
    {
        $model = $this->findModel($id, 'id');

        $sizes = $model->getSizes();
        array_splice($sizes, $request->post('index'), 1);
        $model->sizes = serialize($sizes);
        $model->save();

        return response()->json([
        ]);
    }

    public function addImage(String $id, Request $request)
    {
        $model = $this->findModel($id, 'id');

        $items = $model->getImagesTypes();
        array_push($items, $request->post('name'));

        $model->images_types = serialize($items);
        $model->save();

        return response()->json([
        ]);
    }

    public function removeImage($id, Request $request)
    {
        $model = $this->findModel($id, 'id');

        $items = $model->getImagesTypes();
        array_splice($items, $request->post('index'), 1);
        $model->images_types = serialize($items);
        $model->save();

        return response()->json([
        ]);
    }

    public function pages($identifier, Request $request)
    {
        $method = $request->method();
        $model  = $this->model::where('identifier', $identifier)->first();

        if (!$model) {
            $model               = new $this->model();
            $model->identifier   = $identifier;
            $model->created_by   = auth()->user()->id;
            $model->images_types = serialize(config('base.images_types'));
            $model->sizes        = serialize(config('base.images'));
            $model->save();
        }


        $post  = new BlogPost();
        if ($request->isMethod('post')) {
            $this->loadData($post, $request);
            $post->created_by = auth()->user()->id;
            $post->blogs_id   = $model->id;
            $post->status     = BlogPost::STATUS_ACTIVE;
            $post->save();

            $this->loadData($model, $request);
            $model->save();
        }

        $posts = BlogPost::where('blogs_id', $model->id)->where('status', '!=', BlogPost::STATUS_DELETE)->paginate($this->filters['pagination']);

        return view($this->view . '.pages', [
            'model' => $model,
            'name'  => $identifier,
            'posts' => $posts,
            'post'  => $post
        ]);
    }

    public function deletePages($id)
    {
        $model = BlogPost::find($id);
        $model->status = BlogPost::STATUS_DELETE;
        $model->save();

        return redirect()->route('blog-post.pages', $model->blog->identifier);
    }
}

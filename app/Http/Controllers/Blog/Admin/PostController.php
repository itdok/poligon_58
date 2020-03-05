<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Requests\BlogPostCreateRequest;
use App\Http\Requests\BlogPostUpdateRequest;
use App\Models\BlogPost;
use App\Repositories\BlogCategoryRepository;
use App\Repositories\BlogPostRepository;
use Illuminate\Http\Response;

/**
 * Управление статьями блога.
 *
 * @package App\Http\Controllers\Blog\Admin
 */
class PostController extends BaseController
{
    /**
     * @var BlogPostRepository
     */
    private $blogPostRepository;

    /**
     * @var blogCategoryRepository
     */
    private $blogCategoryRepository;

    /**
     * PostController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->blogPostRepository = app(BlogPostRepository::class);
        $this->blogCategoryRepository = app(blogCategoryRepository::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $paginator = $this->blogPostRepository->getAllWithPaginate();

        return view('blog.admin.posts.index', compact('paginator'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $item = new BlogPost();
        $categoryList
            = $this->blogCategoryRepository->getForComboBox();

        return view('blog.admin.posts.edit',
            compact('item', 'categoryList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  BlogPostCreateRequest  $request
     * @return Response
     */
    public function store(BlogPostCreateRequest $request)
    {
        $data   = $request->input();
        $item = (new BlogPost())->create($data);  // Создаст объект и добавит в db

        if ($item) {
            return redirect()->route('blog.admin.posts.edit', [$item->id])
                ->with(['success' => "Успешно сохранено"]);
        } else {
            return back()->withErrors(['msg' => "Ошибка сохранения"])
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $item = $this->blogPostRepository->getEdit($id);
        if (empty($item)){
            abort(404);
        }

        $categoryList
            = $this->blogCategoryRepository->getForComboBox();

        return view('blog.admin.posts.edit',
            compact('item', 'categoryList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param BlogPostUpdateRequest  $request
     * @param  int  $id
     * @return Response
     */
    public function update(BlogPostUpdateRequest $request, $id)
    {
         $item = $this->blogPostRepository->getEdit($id);

         if (empty($item)){
             return back()
                 ->withErrors(['msg' => "Запись id=[{$id}] не найдена"])
                 ->withInput();
         }

         $data = $request->all();
         $result = $item->update($data);

         if ($result){
             return redirect()
                 ->route('blog.admin.posts.edit', $item->id)
                 ->with(['success' => 'Успешно сохранено']);
         }else{
             return back()
                 ->withErrors(['msg' => "Ошибка сохранения"])
                 ->withInput();
         }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
//        dd(__METHOD__, $id, request()->all());
        //софт-удаление, в бд останется
        $result = BlogPost::destroy($id);

        // полное удаление из бд
//        $result = BlogPost::find($id)->forceDelete();

        if ($result){
            return redirect()
                ->route('blog.admin.posts.index')
                ->with(['success' => "Запись id[$id] удалена"]);
        }else{
            return back()
                ->withErrors(['msg' => "Ошибка удаления"]);
        }
    }
}

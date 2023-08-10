<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tag_names=array();

        foreach(explode(",", $request->query('tags')) as $tag_name) {
            if($tag_name=="") {
                continue;
            }
            array_push($tag_names, trim($tag_name));
        }

        if(!$tag_names) {
            $articles=Article::where('user_id', $request->user()->id)->paginate(5);
        } else {
            $articles=Article::where('user_id', $request->user()->id)->whereHas('tags', function (Builder $query) use ($tag_names) {
                $query->whereIn('name', $tag_names);
            })->paginate(5);
        }


        foreach($articles as $article) {
            $tag_names="";

            foreach($article->tags as $key=>$tag) {
                if($key==0) {
                    $tag_names=$tag->name;
                } else {
                    $tag_names=$tag_names . ", " . $tag->name;
                }
            }

            $article->tag_names=$tag_names;
        }

        return view('articles.index', ['articles'=>$articles]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('articles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request)
    {
        $request->validated();

        $title=$request->string('title')->trim();
        $content=$request->string('content')->trim();
        $tags=$request->string('tags')->trim();

        $tag_ids=array();

        foreach(explode(",", $tags) as $tag) {
            $tagFromDb=Tag::firstOrCreate(['name'=>trim($tag)]);
            // add tag id to array
            array_push($tag_ids, $tagFromDb->id);
        }

        $article=new Article();

        $article->title=$title;
        $article->content=$content;
        $article->user()->associate($request->user());

        $article->save();

        $article->tags()->sync($tag_ids);

        return redirect()->route('articles.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Article $article)
    {
        if ($request->user()->cannot('view', $article)) {
            abort(403);
        }

        $tag_names="";

        foreach($article->tags as $key=>$tag) {
            if($key==0) {
                $tag_names=$tag->name;
            } else {
                $tag_names=$tag_names . ", " . $tag->name;
            }
        }

        $article->tag_names=$tag_names;

        return view('articles.show', ['article'=>$article]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Article $article)
    {
        if ($request->user()->cannot('update', $article)) {
            abort(403);
        }

        $tag_names="";

        foreach($article->tags as $key=>$tag) {
            if($key==0) {
                $tag_names=$tag->name;
            } else {
                $tag_names=$tag_names . ", " . $tag->name;
            }
        }

        $article->tag_names=$tag_names;

        return view('articles.edit', ['article'=>$article]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        if ($request->user()->cannot('update', $article)) {
            abort(403);
        }

        $request->validated();

        $title=$request->string('title')->trim();
        $content=$request->string('content')->trim();
        $tags=$request->string('tags')->trim();

        $tag_ids=array();

        foreach(explode(",", $tags) as $tag) {
            $tagFromDb=Tag::firstOrCreate(['name'=>trim($tag)]);
            // add tag id to array
            array_push($tag_ids, $tagFromDb->id);
        }

        $article->title=$title;
        $article->content=$content;
        $article->user()->associate($request->user());

        $article->save();

        $article->tags()->sync($tag_ids);

        return redirect()->route('articles.show', ['article'=>$article]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Article $article)
    {
        if ($request->user()->cannot('delete', $article)) {
            abort(403);
        }

        // detach tags
        $article->tags()->detach();
        // delete the article
        $article->delete() ;

        return redirect()->route('articles.index');
    }
}

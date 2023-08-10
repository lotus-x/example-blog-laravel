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
        // create an array to hold tag names
        $tag_names=array();

        // iterate through tags from request query
        foreach(explode(",", $request->query('tags')) as $tag_name) {
            if($tag_name=="") {
                continue;
            }
            // push trimmed tag name to the array
            array_push($tag_names, trim($tag_name));
        }

        // check tag names are available in the request
        if(!$tag_names) {
            // fetch articles from db without filtering using tags
            $articles=Article::where('user_id', $request->user()->id)->paginate(5);
        } else {
            // filter articles for the tags
            $articles=Article::where('user_id', $request->user()->id)->whereHas('tags', function (Builder $query) use ($tag_names) {
                $query->whereIn('name', $tag_names);
            })->paginate(5);
        }

        // iterate through articles for create readable tag name list
        foreach($articles as $article) {
            $tag_names="";

            foreach($article->tags as $key=>$tag) {
                // append comma to the rest of tags without the first one
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
        // validate the request
        $request->validated();

        // extract data from the request
        $title=$request->string('title')->trim();
        $content=$request->string('content')->trim();
        $tags=$request->string('tags')->trim();

        // create an array to hold tag ids for later use
        $tag_ids=array();

        foreach(explode(",", $tags) as $tag) {
            // get the tag from db if already exist or create a new one
            $tagFromDb=Tag::firstOrCreate(['name'=>trim($tag)]);
            // add tag id to array
            array_push($tag_ids, $tagFromDb->id);
        }

        // create an empty article object
        $article=new Article();

        // set article field values
        $article->title=$title;
        $article->content=$content;
        $article->user()->associate($request->user());

        // save article
        $article->save();

        // sync article tag ids
        $article->tags()->sync($tag_ids);

        // return to the article listing page
        return redirect()->route('articles.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Article $article)
    {
        // check user has permission to view his own article
        if ($request->user()->cannot('view', $article)) {
            abort(403);
        }

        // append comma to the rest of tags without the first one
        $tag_names="";

        // iterate through articles for create readable tag name list
        foreach($article->tags as $key=>$tag) {
            if($key==0) {
                $tag_names=$tag->name;
            } else {
                $tag_names=$tag_names . ", " . $tag->name;
            }
        }

        // set an extra variable to article
        $article->tag_names=$tag_names;

        return view('articles.show', ['article'=>$article]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Article $article)
    {
        // check user has permission to update his own article
        if ($request->user()->cannot('update', $article)) {
            abort(403);
        }

        $tag_names="";

        // iterate through articles for create readable tag name list
        foreach($article->tags as $key=>$tag) {
            if($key==0) {
                $tag_names=$tag->name;
            } else {
                $tag_names=$tag_names . ", " . $tag->name;
            }
        }

        // set an extra variable to article
        $article->tag_names=$tag_names;

        return view('articles.edit', ['article'=>$article]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        // check user has permission to update his own article
        if ($request->user()->cannot('update', $article)) {
            abort(403);
        }

        // validate the request
        $request->validated();

        // extract data from the request
        $title=$request->string('title')->trim();
        $content=$request->string('content')->trim();
        $tags=$request->string('tags')->trim();

        // create an array to hold tag ids for later use
        $tag_ids=array();

        foreach(explode(",", $tags) as $tag) {
            // get the tag from db if already exist or create a new one
            $tagFromDb=Tag::firstOrCreate(['name'=>trim($tag)]);
            // add tag id to array
            array_push($tag_ids, $tagFromDb->id);
        }

        // set article field values
        $article->title=$title;
        $article->content=$content;
        $article->user()->associate($request->user());

        // save article
        $article->save();

        // sync article tag ids
        $article->tags()->sync($tag_ids);

        // return to article detail page
        return redirect()->route('articles.show', ['article'=>$article]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Article $article)
    {
        // check user has permission to delete his own article
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

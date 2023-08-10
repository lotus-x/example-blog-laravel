@extends('layouts.home')

@section('title', 'Articles')

@section('actions')
    <form class="mt-3 sm:ml-4 sm:mt-0" method="GET" action="{{ route('articles.index') }}">
        <label for="tags" class="sr-only">Search</label>
        <div class="flex rounded-md shadow-sm">
            <div class="relative flex-grow focus-within:z-10">
                <input type="text" name="tags" id="tags" value="{{ Request::query('tags') }}"
                    class="hidden w-64 rounded-none rounded-l-md border-0 py-1.5 px-3 text-sm leading-6 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:block"
                    placeholder="Search by comma separated tags">
            </div>
            <button type="submit"
                class="relative -ml-px inline-flex items-center gap-x-1.5 rounded-r-md px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </form>
    <a href="{{ route('articles.create') }}">
        <button type="button"
            class="ml-3 inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
            Create article
        </button>
    </a>

@endsection()

@section('content-inner')
    <div class="mt-7 flex flex-col flex-grow justify-between">
        <div class="flex flex-col flex-grow divide-y-2">
            @forelse ($articles as $article)
                <div class="flex justify-between items-center py-3">
                    <div>
                        <p class="text-sm font-semibold leading-6 text-gray-900">
                            <a href="{{ route('articles.show', ['article' => $article]) }}"
                                class="hover:underline">{{ $article->title }}</a>
                        </p>
                        <div class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500">
                            <p>
                                @if ($article->tag_names == '')
                                    <span>No tag specified</span>
                                @else()
                                    <span>{{ $article->tag_names }}</a>
                                @endif()
                            </p>
                        </div>
                    </div>
                    <dl class="flex flex-none justify-between sm:w-auto">
                        <p class="text-xs"><time
                                datetime="2023-01-23T22:34Z">{{ \Carbon\Carbon::parse($article->created_at)->toFormattedDateString() }}</time>
                        </p>
                    </dl>
                </div>
            @empty
                <p>No Articles Available</p>
            @endforelse
        </div>
        {{ $articles->links() }}
    </div>
@endsection()

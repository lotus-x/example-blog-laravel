@extends('layouts.home')

@section('title', 'Create an Article')

@section('actions')
    <a href="{{ route('articles.index') }}"><button type="button"
            class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
            Cancel
        </button></a>
    <button type="submit" form="article-create-form"
        class="ml-3 inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
        Publish
    </button>
@endsection()

@section('content-inner')
    <form id="article-create-form" class="mt-10" method="POST" action="{{ route('articles.store') }}">
        @csrf
        <div class="space-y-12 sm:space-y-16">
            <div>
                <h2 class="text-base font-semibold leading-7 text-gray-900">Basic</h2>
                <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-600">Basic details for the article</p>

                <div
                    class="mt-10 space-y-8 border-b border-gray-900/10 pb-12 sm:space-y-0 sm:divide-y sm:divide-gray-900/10 sm:border-t sm:pb-0">
                    <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:py-6">
                        <label for="title"
                            class="block text-sm font-medium leading-6 text-gray-900 sm:pt-1.5">Title</label>
                        <div class="mt-2 sm:col-span-2 sm:mt-0">
                            <div
                                class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
                                <input type="text" name="title" id="title" autocomplete="title"
                                    class="block flex-1 border-0 bg-transparent py-1.5 px-3 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                                    placeholder="My first article">
                            </div>
                            @if ($errors->has('title'))
                                <span class="text-xs text-red-600">{{ $errors->first('title') }}</span>
                            @endif()
                        </div>
                    </div>

                    <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:py-6">
                        <label for="content"
                            class="block text-sm font-medium leading-6 text-gray-900 sm:pt-1.5">Content</label>
                        <div class="mt-2 sm:col-span-2 sm:mt-0">
                            <textarea id="content" name="content" rows="3"
                                class="block w-full max-w-2xl rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                            @if ($errors->has('content'))
                                <span class="text-xs text-red-600">{{ $errors->first('content') }}</span>
                            @endif()
                            <p class="mt-3 text-sm leading-6 text-gray-600">Write your article here</p>
                        </div>
                    </div>

                    <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:py-6">
                        <label for="tags"
                            class="block text-sm font-medium leading-6 text-gray-900 sm:pt-1.5">Tags</label>
                        <div class="mt-2 sm:col-span-2 sm:mt-0">
                            <div
                                class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
                                <input type="text" name="tags" id="tags" autocomplete="tags"
                                    class="block flex-1 border-0 px-3 bg-transparent py-1.5 text-gray-900 placeholder:text-gray-400 focus:ring sm:text-sm sm:leading-6"
                                    placeholder="life, economy, nature">
                            </div>
                            @if ($errors->has('tags'))
                                <span class="text-xs text-red-600">{{ $errors->first('tags') }}</span>
                            @endif()

                            <p class="mt-3 text-sm leading-6 text-gray-600">Add tags using comma separated list</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection()

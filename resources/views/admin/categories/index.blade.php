@extends('layouts.app')

@section('title', 'Admin: Categories')

@section('content')
    {{-- カテゴリ追加用フォーム --}}
    <form action="{{ route('admin.categories.store') }}" method="post">
        @csrf

        <div class="row gx-2 mb-4">
            <div class="col-4">
                <input type="text" name="name" class="form-control sns-input" placeholder="Add a category..." value="{{ old('name') }}" autofocus>
                @error('name')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-mini"><i class="fa-solid fa-plus"></i> Add</button>
            </div>
        </div>
    </form>

    {{-- カテゴリ一覧テーブル --}}
    <div class="row">
        <div class="col-9">
            <table class="table table-hover align-middle bg-white border text-secondary text-center">
                <thead class="small table-category text-secondary">
                    <tr>
                        <th>#</th>
                        <th>NAME</th>
                        <th>COUNT</th>
                        <th>LAST UPDATED</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($all_categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td class="text-dark fw-bold">{{ $category->name }}</td>
                            <td>{{ $category->categoryPost->count() }}</td> {{-- または紐づく投稿数を示すプロパティ --}}
                            <td>{{ $category->updated_at }}</td>
                            <td>
                                {{-- 編集・削除ボタン（Uncategorized以外に表示する場合など） --}}
                                @if ($category->name != 'Uncategorized')
                                    <button class="btn btn-outline-orange btn-sm" data-bs-toggle="modal" data-bs-target="#edit-category-{{ $category->id }}" title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delete-category-{{ $category->id }}" title="Delete">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                   
                                    @include('admin.categories.modals.status') 
                                @endif
                            </td>
                        </tr>
                    @endforeach

                    {{-- Uncategorized（未分類）の行 --}}
                    <tr>
                        <td></td>
                        <td class="text-dark fw-bold">
                            Uncategorized
                            <p class="mb-0 xsmall text-muted">Hidden posts are not included.</p>
                        </td>
                        <td>{{ $uncategorized_count }}</td> {{-- 未分類の投稿数 --}}
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
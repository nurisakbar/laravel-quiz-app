@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

<!-- Content Row -->
        <div class="card shadow">
            <div class="card-header">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">{{ __('create question') }}</h1>
                    <a href="{{ route('admin.questions.index') }}" class="btn btn-primary btn-sm shadow-sm">{{ __('Go Back') }}</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.questions.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="question_text">{{ __('question text') }}</label>
                        <textarea class="form-control" id="question_text" placeholder="{{ __('question text') }}" name="question_text">{{ old('question_text') }}</textarea>
                        {{-- <input type="text" class="form-control" id="question_text" placeholder="{{ __('question text') }}" name="question_text" value="{{ old('question_text') }}" /> --}}
                    </div>
                    <div class="form-group">
                        <label for="category">{{ __('Category') }}</label>
                        <select class="form-control" name="category_id" id="category">
                            @foreach($categories as $id => $category)
                                <option value="{{ $id }}">{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="category">Category Topic</label>
                        <select class="form-control" name="topic_categori_id" id="category">
                            @foreach($categories as $id => $category)
                                <option value="{{ $id }}">{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>


                    @for($i=1;$i<=4;$i++)
                    <div class="form-group">
                        <label for="question_text">Option {{ $i }}</label>
                        <textarea class="form-control" id="question_text" placeholder="Option {{ $i }}" name="option[]"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="question_text">Point To Answer {{ $i }}</label>
                        <input type="text" class="form-control" id="question_text" placeholder="Point To Answer {{ $i }}" name="point[]" value="0" />
                    </div>
                    @endfor


                    <button type="submit" class="btn btn-primary btn-block">{{ __('Save') }}</button>
                </form>
            </div>
        </div>
    

    <!-- Content Row -->

</div>
@endsection
@push('script-alt')
<script src="https://cdn.ckeditor.com/4.16.2/standard-all/ckeditor.js"></script>
<script>
    CKEDITOR.replace('question_text', {
      extraPlugins: 'embed,autoembed,image2,justify',
      height: 200,
      // Load the default contents.css file plus customizations for this sample.
      contentsCss: [
        'http://cdn.ckeditor.com/4.16.2/full-all/contents.css',
        'https://ckeditor.com/docs/ckeditor4/4.16.2/examples/assets/css/widgetstyles.css'
      ],
      // Setup content provider. See https://ckeditor.com/docs/ckeditor4/latest/features/media_embed
      embed_provider: '//ckeditor.iframe.ly/api/oembed?url={url}&callback={callback}',
      // Configure the Enhanced Image plugin to use classes instead of styles and to disable the
      // resizer (because image size is controlled by widget styles or the image takes maximum
      // 100% of the editor width).
      image2_alignClasses: ['image-align-left', 'image-align-center', 'image-align-right'],
      image2_disableResizer: true,
      removeButtons: 'PasteFromWord'
    });
  </script>
  @endpush
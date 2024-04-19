<?php /** @var \Webid\Druid\App\Models\Post $model */
$model = $getRecord();
$model->refresh();
$model->loadMissing('translationOriginModel.translations');
?>
@if($model->translationOriginModel?->translations)
    <ul>
        @foreach($model->translationOriginModel->translations as $translation)
            <li><a href="/admin/posts/{{$translation->getKey()}}/edit">[{{ $translation->lang?->value }}] {{$translation->title}}</a></li>
        @endforeach
    </ul>
@endif

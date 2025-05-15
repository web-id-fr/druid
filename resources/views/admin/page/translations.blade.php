<?php /** @var \Webid\Druid\Models\Page $model */
// @phpstan-ignore-next-line
$model = $getRecord();
$model->loadMissing('translationOriginModel.translations');
?>
@if($model->relationLoaded('translationOriginModel') && $model->translationOriginModel?->relationLoaded('translations'))
    <ul>
        @foreach($model->translationOriginModel->translations as $translation)
            <li><a href="/admin/pages/{{$translation->getKey()}}/edit">[{{ $translation->lang }}] {{$translation->title}}</a></li>
        @endforeach
    </ul>
@else
    <p>-</p>
@endif

<?php /** @var \Webid\Druid\Models\Menu $model */
// @phpstan-ignore-next-line
$model = $getRecord();
$model->refresh();
$model->loadMissing('translationOriginModel.translations');
?>
@if($model->translationOriginModel?->translations)
    <ul>
        @foreach($model->translationOriginModel->translations as $translation)
            <li><a href="/admin/menus/{{$translation->getKey()}}/edit">[{{ $translation->lang }}] {{$translation->title}}</a></li>
        @endforeach
    </ul>
@endif

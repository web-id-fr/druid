<?php /** @var \Webid\Druid\App\Models\Page $page */
$page = $getRecord();
$page->refresh();
$page->loadMissing('translationOriginModel.translations');
?>
@if($page->translationOriginModel?->translations)
    <ul>
        @foreach($page->translationOriginModel->translations as $translation)
            <li><a href="/admin/pages/{{$translation->getKey()}}/edit">[{{ $translation->lang?->value }}] {{$translation->title}}</a></li>
        @endforeach
    </ul>
@endif

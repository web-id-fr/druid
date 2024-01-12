<?php /** @var \App\Models\Page $page */
$page = $getRecord();
$page->refresh();
$page->loadMissing('translationOriginPage.translations');
?>
@if($page->translationOriginPage?->translations)
    <ul>
        @foreach($page->translationOriginPage->translations as $translation)
            <li><a href="/admin/pages/{{$translation->getKey()}}/edit">[{{ $translation->lang?->value }}] {{$translation->title}}</a></li>
        @endforeach
    </ul>
@endif

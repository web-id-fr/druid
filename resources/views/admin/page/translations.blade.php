<?php /** @var \Webid\Druid\Models\Page $model */
// @phpstan-ignore-next-line
$model = $getRecord();
$model->loadMissing('translationOriginModel.translations');
$langs = $model->relationLoaded('translationOriginModel') && $model->translationOriginModel->relationLoaded('translations')
    ? $model->translationOriginModel->translations->map(fn(\Webid\Druid\Models\Page $page) => $page->lang)->toArray()
    : [];
?>
@if($model->relationLoaded('translationOriginModel') && $model->translationOriginModel?->relationLoaded('translations'))
    <ul>
        @foreach($model->translationOriginModel->translations as $translation)
            <li><a href="/admin/pages/{{$translation->getKey()}}/edit">[{{ $translation->lang }}] {{$translation->title}}</a></li>
        @endforeach
        @foreach(\Webid\Druid\Facades\Druid::getLocales() as $key => $locale)
            @if(! in_array($key, $langs))
                <div>
                    <span>
                       <a
                           href="/admin/pages/create?lang={{$key}}&translation_origin_model_id={{$model->translationOriginModel->id}}&title={{$model->translationOriginModel->title}} [{{$key}}]"
                           class="font-semibold text-sm text-custom-600 dark:text-custom-400 group-hover/link:underline group-focus-visible/link:underline"
                           style="--c-400:var(--primary-400);--c-600:var(--primary-600);"
                       >
                        [{{ $key }}] créer la traduction
                    </a>
                    </span>

                </div>
            @endif
        @endforeach

    </ul>
@else
    <p>-</p>
@endif

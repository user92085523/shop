<div>
    <select name="" id="" wire:model.live="csf_target_model_name.cur">
        @foreach ($csf_models_obj as $model_name => $value)
            <option value="{{ $model_name }}">{{ $value['base']['label'] }}</option>
        @endforeach
    </select>
    <br>

    @foreach ($csf_search_forms as $form_tree)
        @foreach ($form_tree as $row)
            @foreach ($row as $option)
                {{-- <{{ $loop->parent->parent->index }}.{{ $loop->parent->index }}.{{ $loop->index }}> --}}
                @if ($option['html_tag'] === 'select')
                    <select name="" id="" wire:model.live="csf_search_forms.{{ $loop->parent->parent->index }}.{{ $loop->parent->index }}.{{ $loop->index }}.obj_idx" wire:key="csf_search_forms.{{ $loop->parent->parent->index }}.{{ $loop->parent->index }}.{{ $loop->index }}.obj_idx">
                        @foreach ($option['objs'] as $item)
                            <option value="{{ $loop->index }}">{{ $item['label'] }}</option>
                        @endforeach
                    </select>
                @elseif ($option['html_tag'] === 'input')
                    <input type="text" wire:model.live="csf_search_forms.{{ $loop->parent->parent->index }}.{{ $loop->parent->index }}.{{ $loop->index }}.objs.{{ $csf_search_forms[$loop->parent->parent->index][$loop->parent->index][$loop->index]['obj_idx'] }}.data.value" placeholder="{{ $option['objs'][$option['obj_idx']]['label'] }}">
                @endif
                {{-- @dump($csf_search_forms[$loop->parent->parent->index][$loop->parent->index][$loop->index]) --}}
            @endforeach
        @endforeach

        @if (! $loop->first)
            <button wire:click="csf_DeleteSearchForm({{ $loop->index }})">この条件を取り消す</button>
        @endif
        <br>
        @if ($loop->last && count($csf_search_forms) < $csf_max_search_form_cnt)
            <button wire:click="csf_AddSearchForm">さらに条件を追加する</button>
        @endif
    @endforeach

    <br>
    <button wire:click="csf_Search">検索する</button>
    <br>

    @empty($csf_records)
        <h2>検索してください</h2>
    @else
        @empty($csf_records[0])
            <h2>データは存在しません</h2>
        @else
            @foreach ($csf_records as $record)
                <br>
                {{ $record }}
            @endforeach
        @endempty
    @endempty
</div>
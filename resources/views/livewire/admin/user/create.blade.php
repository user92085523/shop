<div>
    @foreach ($user_creation_form_obj as $model_name => $model)
        @foreach ($model as $key => $form)
            @if ($form['html_tag'] === 'input')
                <label for="">{{ $form['label'] }}:</label>
                <input type="text" wire:model{{ $form['model_mod'] }}="{{ $form['var_name'] }}" wire:key="{{ $form['var_name'] }}">
                {{ ${strtolower($model_name) . '_form'}->{'msgs'}[$key] }}
            @elseif ($form['html_tag'] === 'select')
                <select name="" id="" wire:model{{ $form['model_mod'] }}="{{ $form['var_name'] }}">
                    @foreach ($form['elements'] as $item)
                        <option value="{{ $item['value'] }}">{{ $item['label'] }}</option>
                    @endforeach
                </select>
            @endif
            <br>
        @endforeach
    @endforeach

    <button wire:click="createUser">作成する</button>
</div>
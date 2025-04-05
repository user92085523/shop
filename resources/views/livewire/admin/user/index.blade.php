@php
    use App\Enums\Models\User\Role;
@endphp
<div>

    <form wire:submit="search">
        <select wire:model.change="query.model">
            <option value="user">ユーザーの検索</option>
            @foreach (Role::formOrder() as $role)
                <option value="{{ $role->en_US_lower() }}">{{ $role->ja_JP() }}の検索</option>
            @endforeach
        </select>

        @if ($query['model'] !== '')
            @php
                $query_model_info = $models_info[$query['model']];
            @endphp

            <select name="" id="" wire:model.live="query.column">
                <option value="">すべて</option>
                @foreach ($query_model_info as $table_name => $value)
                    <option value="{{ $table_name }}">{{ $value['label'] }}</option>
                @endforeach
            </select>

            @if ($query['column'] !== '')
                @php
                    $childs = $query_model_info[$query['column']]['childs']
                @endphp

                <select name="" id="" wire:model.live="query.child_num">
                    @foreach ($childs as $child)
                        <option value="{{ $loop->index }}">{{ $child['label'] }}</option>
                    @endforeach
                </select>

                @if ($childs[$query['child_num']]['html_tag'] === 'input')
                    <input type="text" wire:model.live="query.value">                    
                @endif
            @endif
        @endif

        {{-- <input type="text" wire:model="query.keyword"> --}}

        <button type="submit">検索</button>
    </form>

    @dump($query)
    {{-- <a href="" wire:click.prevent="set('a', 'user')">ユーザー</a>
    <a href="" wire:click.prevent="set('a', 'employee')">従業員</a>
    <a href="" wire:click.prevent="set('a', 'customer')">顧客</a> --}}

    @isset($fetched_models)
        <ul>
            @foreach ($fetched_models as $model)
                <li>{{ $model }}</li>
            @endforeach
        </ul>
    @endisset
</div>
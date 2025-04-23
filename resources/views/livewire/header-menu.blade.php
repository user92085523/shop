<div>
    <select name="" id="" wire:model.change="category_num">
        @foreach ($header_menu as $category)
            <option value="{{ $loop->index }}">{{ $category['label'] }}</option>
        @endforeach
    </select>
    <select name="" id="" wire:model.change="subcategory_num" wire:key="{{ $category_num }}">
        @foreach ($header_menu[$category_num]['next'] as $subcategory)
            <option value="{{ $loop->index }}">{{ $subcategory['label'] }}</option>
        @endforeach
    </select>
    <button wire:click="jump">移動する</button>
</div>

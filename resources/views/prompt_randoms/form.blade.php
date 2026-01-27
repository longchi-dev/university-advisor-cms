@php
    $currentGroup  = old('group', $item->group ?? ($group ?? ''));
    $currentValue  = old('value', $item->value ?? '');
    $currentWeight = old('weight', $item->weight ?? 1);
    $currentActive = old('is_active', $item->is_active ?? 1);
@endphp

<div class="form-group">
    <label>Group</label>
    <select name="group" class="form-control" required>
        <option value="">Chọn group</option>
        @foreach(\App\Enums\RandomGroupEnum::values() as $group)
            <option value="{{ $group }}" {{ $currentGroup == $group ? "selected" : "" }} >{{ $group }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label class="form-label">Value</label>
    <input
        type="text"
        name="value"
        class="form-control"
        value="{{ $currentValue }}"
        required
    >
</div>

<div class="form-group">
    <label class="form-label">Weight</label>
    <input
        type="number"
        name="weight"
        class="form-control"
        min="1"
        value="{{ $currentWeight }}"
    >
</div>

<div class="form-group">
    <label class="form-label">Active</label>
    <select name="is_active" class="form-control">
        <option value="1" @if($currentActive) selected @endif>Yes</option>
        <option value="0" @if(!$currentActive) selected @endif>No</option>
    </select>
</div>

@php
    $related = $column->getRelated();
    $resolved = $column->getResolvedColumns();
    $table = $column->getTable();
    $record = $getRecord();
    $recordKey = $column->getRecordKey();
    $rowLoop = $column->getRowLoop();
@endphp

@if ($resolved)
    @foreach ($resolved as $child)
        {!! $child->table($table)->record($record)->recordKey($recordKey)->rowLoop($rowLoop)->renderInLayout() !!}
    @endforeach
@elseif ($related)
    {{ class_basename($related) }} #{{ $related->getKey() }}
@endif

@php echo "<?php";
@endphp


namespace {{ $exportNamespace }};

use {{ $modelFullName }};
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class {{ $classBaseName }} implements FromCollection, WithMapping, WithHeadings
{
    /**
     * {{'@'}}return Collection
     */
    public function collection()
    {
        return {{$modelBaseName}}::all();
    }

    /**
     * {{'@'}}return array
     */
    public function headings(): array
    {
        return [
@foreach($columnsToExport as $column)
            trans('admin.{{ $modelLangFormat }}.columns.{{ $column }}'),
@endforeach
        ];
    }

    /**
     * {{'@'}}param {{$modelBaseName}} ${{ $modelVariableName }}
     * {{'@'}}return array
     *
     */
    public function map(${{ $modelVariableName }}): array
    {
        return [
@foreach($columnsToExport as $column)
            ${{$modelVariableName}}->{{$column}},
@endforeach
        ];
    }
}

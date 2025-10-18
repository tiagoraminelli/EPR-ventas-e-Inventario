<?php

namespace App\Exports;


use App\Models\Venta;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VentaExport implements FromCollection, WithHeadings, WithMapping
{
    protected $venta;

    public function __construct(Venta $venta)
    {
        $this->venta = $venta;
    }

    public function collection()
    {
        $productos = $this->venta->detalleVentas->map(function($detalle) {
            return [
                'descripcion' => $detalle->producto->nombre ?? 'Sin producto',
                'cantidad' => $detalle->cantidad,
                'precio_unitario' => $detalle->precio_unitario,
                'descuento' => $detalle->descuento,
                'subtotal' => $detalle->subtotal,
            ];
        });

        $servicios = $this->venta->serviciosVentas->map(function($sv) {
            return [
                'descripcion' => $sv->servicio->nombre ?? 'Sin servicio',
                'cantidad' => $sv->cantidad,
                'precio_unitario' => $sv->precio,
                'descuento' => 0,
                'subtotal' => $sv->cantidad * $sv->precio,
            ];
        });

        return $productos->merge($servicios);
    }

    public function headings(): array
    {
        return ['Descripción', 'Cantidad', 'Precio Unitario', 'Descuento', 'Subtotal'];
    }

    public function map($row): array
    {
        return [
            $row['descripcion'],
            $row['cantidad'],
            $row['precio_unitario'],
            $row['descuento'],
            $row['subtotal'],
        ];
    }
}
